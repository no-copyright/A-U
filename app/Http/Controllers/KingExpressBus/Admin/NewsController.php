<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable; // Import Throwable để bắt lỗi trong transaction

class NewsController extends Controller
{
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Tạo slug
                $baseSlug = Str::slug($validated['title']);
                $slug = $baseSlug;
                $counter = 1;
                while (DB::table('news')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $validated['slug'] = $slug;

                $validated['created_at'] = now();
                $validated['updated_at'] = now();

                // 1. Thêm tin tức mới vào bảng 'news'
                DB::table('news')->insert($validated);

                // 2. Cập nhật số lượng trong bảng 'categories'
                DB::table('categories')->where('id', $validated['category_id'])->increment('count');
            });
        } catch (Throwable $e) {
            // Nếu có lỗi, quay lại với thông báo lỗi
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

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);
        
        try {
            DB::transaction(function () use ($validated, $news, $id, $old_category_id) {
                // Cập nhật slug nếu tiêu đề thay đổi
                if ($news->title !== $validated['title']) {
                    $baseSlug = Str::slug($validated['title']);
                    $slug = $baseSlug;
                    $counter = 1;
                    while (DB::table('news')->where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $baseSlug . '-' . $counter++;
                    }
                    $validated['slug'] = $slug;
                }

                $validated['updated_at'] = now();

                // 1. Cập nhật bảng 'news'
                DB::table('news')->where('id', $id)->update($validated);

                // 2. Cập nhật số lượng nếu danh mục thay đổi
                if ($old_category_id != $validated['category_id']) {
                    // Giảm count của danh mục cũ
                    DB::table('categories')->where('id', $old_category_id)->decrement('count');
                    // Tăng count của danh mục mới
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
                DB::table('categories')->where('id', $news->category_id)->decrement('count');
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi xóa tin tức: ' . $e->getMessage());
        }

        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được xóa thành công!');
    }
}