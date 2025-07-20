<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    use SlugGenerator;

    public function index(Request $request)
    {
        $query = DB::table('news')
            ->leftJoin('categories', 'news.category_id', '=', 'categories.id')
            ->select('news.*', 'categories.name as category_name');

        if ($request->filled('search')) {
            $query->where('news.title', 'like', '%' . $request->input('search') . '%');
        }

        $newsItems = $query->orderBy('news.created_at', 'desc')->paginate(10);

        return view('kingexpressbus.admin.modules.news.index', compact('newsItems'));
    }

    public function create()
    {
        $news = null;
        $categories = DB::table('categories')->orderBy('name', 'asc')->get();
        return view('kingexpressbus.admin.modules.news.createOrEdit', compact('news', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:news,title',
            'excerpt' => 'required|string|max:500',
            'thumbnail' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $validated['author'] = 'Admin';
                $validated['created_at'] = now();
                $validated['updated_at'] = now();

                $validated['slug'] = Str::slug($validated['title']);
                $id = DB::table('news')->insertGetId($validated);

                $finalSlug = $this->generateSlug($validated['title'], $id);
                DB::table('news')->where('id', $id)->update(['slug' => $finalSlug]);

                DB::table('categories')->where('id', $validated['category_id'])->increment('count');
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Error creating news: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.news.index')->with('success', 'News created successfully!');
    }

    public function edit(string $id)
    {
        $news = DB::table('news')->find($id);
        if (!$news) {
            abort(404);
        }
        $categories = DB::table('categories')->orderBy('name', 'asc')->get();
        return view('kingexpressbus.admin.modules.news.createOrEdit', compact('news', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $news = DB::table('news')->find($id);
        if (!$news) {
            abort(404);
        }

        $old_category_id = $news->category_id;

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:news,title,' . $id,
            'excerpt' => 'required|string|max:500',
            'thumbnail' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $news, $id, $old_category_id) {
                if ($news->title !== $validated['title']) {
                    $validated['slug'] = $this->generateSlug($validated['title'], $id);
                }

                $validated['author'] = 'Admin';
                $validated['updated_at'] = now();

                DB::table('news')->where('id', $id)->update($validated);

                if ($old_category_id != $validated['category_id']) {
                    DB::table('categories')->where('id', $old_category_id)->decrement('count');
                    DB::table('categories')->where('id', $validated['category_id'])->increment('count');
                }
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Error updating news: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully!');
    }

    public function destroy(string $id)
    {
        $news = DB::table('news')->find($id);
        if (!$news) {
            return back()->with('error', 'News item not found.');
        }

        try {
            DB::transaction(function () use ($news, $id) {
                DB::table('news')->where('id', $id)->delete();
                DB::table('categories')->where('id', $news->category_id)->where('count', '>', 0)->decrement('count');
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Error deleting news: ' . $e->getMessage());
        }

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully!');
    }

    public function getNewsList(Request $request): JsonResponse
    {
        $pageSize = $request->query('pageSize', 10);
        $search = $request->query('search');

        $query = DB::table('news')
            ->join('categories', 'news.category_id', '=', 'categories.id')
            ->select(
                'news.id',
                'news.title',
                'news.slug',
                'news.excerpt',
                'news.thumbnail',
                'news.author',
                'news.view',
                'news.created_at',
                'categories.name as category_name',
                'categories.slug as category_slug'
            );

        if ($search) {
            $query->where('news.title', 'like', '%' . $search . '%');
        }

        $paginator = $query->orderBy('news.created_at', 'desc')
            ->paginate($pageSize)
            ->appends($request->query());

        $transformedNews = $paginator->getCollection()->map(function ($newsItem) {
            if (!empty($newsItem->thumbnail)) {
                $newsItem->thumbnail = url($newsItem->thumbnail);
            }
            return $newsItem;
        });

        return response()->json([
            'success' => true,
            'currentPage' => $paginator->currentPage(),
            'totalPages' => $paginator->lastPage(),
            'totalElements' => $paginator->total(),
            'pageSize' => $paginator->perPage(),
            'data' => $transformedNews,
        ]);
    }

    public function getNewsDetailBySlug(string $slug): JsonResponse
    {
        $news = DB::table('news')
            ->join('categories', 'news.category_id', '=', 'categories.id')
            ->select(
                'news.id',
                'news.title',
                'news.slug',
                'news.excerpt',
                'news.thumbnail',
                'news.author',
                'news.view',
                'news.content',
                'news.created_at',
                'news.updated_at',
                'categories.name as category_name',
                'categories.slug as category_slug'
            )
            ->where('news.slug', $slug)
            ->first();

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found.'
            ], 404);
        }

        DB::table('news')->where('id', $news->id)->increment('view');
        $news->view += 1;

        if (!empty($news->thumbnail)) {
            $news->thumbnail = url($news->thumbnail);
        }

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    public function getKnowledgeNewsApi(Request $request): JsonResponse
    {
        $knowledgeCategory = DB::table('categories')->where('name', 'Kiến thức và kinh nghiệm')->first();

        if (!$knowledgeCategory) {
            return response()->json([
                'success' => false,
                'message' => 'Category "Kiến thức và kinh nghiệm" not found. Please run the seeder.'
            ], 404);
        }

        $pageSize = $request->query('pageSize', 6);

        $paginator = DB::table('news')
            ->where('category_id', $knowledgeCategory->id)
            ->select(
                'id',
                'title',
                'slug',
                'thumbnail',
                'excerpt',
                'author',
                'view',
                'created_at'
            )
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize);

        $transformedNews = $paginator->getCollection()->map(function ($newsItem) {
            if (!empty($newsItem->thumbnail)) {
                $newsItem->thumbnail = url($newsItem->thumbnail);
            }
            return $newsItem;
        });

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $knowledgeCategory->id,
                'name' => $knowledgeCategory->name,
                'slug' => $knowledgeCategory->slug,
            ],
            'currentPage' => $paginator->currentPage(),
            'totalPages' => $paginator->lastPage(),
            'totalElements' => $paginator->total(),
            'pageSize' => $paginator->perPage(),
            'data' => $transformedNews,
        ]);
    }
}