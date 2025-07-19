<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator; // <-- BƯỚC 1: Thêm Trait đã tạo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    use SlugGenerator; // <-- BƯỚC 2: Sử dụng Trait trong Controller

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newsItems = DB::table('news')
            ->leftJoin('categories', 'news.category_id', '=', 'categories.id')
            ->select('news.*', 'categories.name as category_name')
            ->orderBy('news.created_at', 'desc')
            ->get();

        return view('kingexpressbus.admin.modules.news.index', compact('newsItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $news = null;
        $categories = DB::table('categories')->orderBy('name', 'asc')->get();
        return view('kingexpressbus.admin.modules.news.createOrEdit', compact('news', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Bỏ validation cho 'author'
        $validated = $request->validate([
            'title' => 'required|string|max:255,title',
            'thumbnail' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Tự động gán tác giả là "Admin"
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
            return back()->with('error', 'Đã xảy ra lỗi khi tạo tin tức: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $news = DB::table('news')->find($id);
        if (!$news) {
            abort(404);
        }
        $categories = DB::table('categories')->orderBy('name', 'asc')->get();
        return view('kingexpressbus.admin.modules.news.createOrEdit', compact('news', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $news = DB::table('news')->find($id);
        if (!$news) {
            abort(404);
        }
        
        $old_category_id = $news->category_id;

        // Bỏ validation cho 'author'
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:news,title,' . $id,
            'thumbnail' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);
        
        try {
            DB::transaction(function () use ($validated, $news, $id, $old_category_id) {
                if ($news->title !== $validated['title']) {
                    $validated['slug'] = $this->generateSlug($validated['title'], $id);
                }

                // Tự động gán tác giả là "Admin"
                $validated['author'] = 'Admin';
                $validated['updated_at'] = now();

                DB::table('news')->where('id', $id)->update($validated);

                if ($old_category_id != $validated['category_id']) {
                    DB::table('categories')->where('id', $old_category_id)->decrement('count');
                    DB::table('categories')->where('id', $validated['category_id'])->increment('count');
                }
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật tin tức: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = DB::table('news')->find($id);
        if (!$news) {
            return back()->with('error', 'Không tìm thấy tin tức để xóa.');
        }

        try {
            DB::transaction(function () use ($news, $id) {
                // 1. Xóa tin tức khỏi bảng 'news'
                DB::table('news')->where('id', $id)->delete();

                // 2. Giảm số lượng trong bảng 'categories'
                // Đảm bảo rằng count không bao giờ âm
                DB::table('categories')->where('id', $news->category_id)->where('count', '>', 0)->decrement('count');
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi xóa tin tức: ' . $e->getMessage());
        }

        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được xóa thành công!');
    }


    public function getNewsList(Request $request): JsonResponse
    {
        // Bạn có thể tùy chỉnh số lượng item mỗi trang ở đây
        $pageSize = $request->query('pageSize', 10); 

        // Sử dụng paginate() của Laravel để tự động xử lý phân trang
        $paginator = DB::table('news')
            ->join('categories', 'news.category_id', '=', 'categories.id')
            ->select(
                'news.id',
                'news.title',
                'news.slug',
                'news.thumbnail',
                'news.author',
                'news.view',
                'news.created_at',
                'categories.name as category_name',
                'categories.slug as category_slug'
            )
            ->orderBy('news.created_at', 'desc')
            ->paginate($pageSize);
            
        // Chuyển đổi collection data để có URL đầy đủ cho thumbnail
        $transformedNews = $paginator->getCollection()->map(function ($newsItem) {
            if (!empty($newsItem->thumbnail)) {
                $newsItem->thumbnail = url($newsItem->thumbnail);
            }
            return $newsItem;
        });

        // Xây dựng cấu trúc response cuối cùng theo đúng yêu cầu
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
        // Tìm bài viết theo slug
        $news = DB::table('news')
            ->join('categories', 'news.category_id', '=', 'categories.id')
            ->select(
                'news.id',
                'news.title',
                'news.slug',
                'news.thumbnail',
                'news.author',
                'news.view',
                'news.content', // Lấy thêm trường nội dung
                'news.created_at',
                'news.updated_at',
                'categories.name as category_name',
                'categories.slug as category_slug'
            )
            ->where('news.slug', $slug)
            ->first();

        // Nếu không tìm thấy, trả về lỗi 404
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found.'
            ], 404);
        }

        // Tăng lượt xem (view count) của bài viết lên 1
        DB::table('news')->where('id', $news->id)->increment('view');

        // Cập nhật lại số view trong đối tượng trả về
        $news->view += 1;
        
        // Chuyển đổi URL cho thumbnail
        if (!empty($news->thumbnail)) {
            $news->thumbnail = url($news->thumbnail);
        }

        // Trả về dữ liệu chi tiết
        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }
}