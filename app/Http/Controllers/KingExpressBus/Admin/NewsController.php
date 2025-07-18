<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator; // <-- BƯỚC 1: Thêm Trait đã tạo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

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
        $validated = $request->validate([
            'title' => 'required|string|max:255,title',
            'thumbnail' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $validated['created_at'] = now();
                $validated['updated_at'] = now();

                // === LOGIC TẠO SLUG MỚI (Đã thay đổi) ===
                // 1. Tạm thời tạo slug rỗng hoặc từ title để insert trước
                $validated['slug'] = Str::slug($validated['title']);

                // 2. Thêm tin tức vào DB để lấy về ID
                $id = DB::table('news')->insertGetId($validated);

                // 3. Tạo slug cuối cùng với ID và cập nhật lại bản ghi
                $finalSlug = $this->generateSlug($validated['title'], $id);
                DB::table('news')->where('id', $id)->update(['slug' => $finalSlug]);
                // ===========================================

                // Cập nhật số lượng bài viết trong danh mục tương ứng
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
            'title' => 'required|string|max:255|unique:news,title,' . $id, // Thêm unique và ignore id hiện tại
            'thumbnail' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);
        
        try {
            DB::transaction(function () use ($validated, $news, $id, $old_category_id) {
                // === LOGIC CẬP NHẬT SLUG MỚI (Đã thay đổi) ===
                // Chỉ cập nhật slug nếu tiêu đề thay đổi
                if ($news->title !== $validated['title']) {
                    $validated['slug'] = $this->generateSlug($validated['title'], $id);
                }
                // ===========================================

                $validated['updated_at'] = now();

                // 1. Cập nhật bảng 'news'
                DB::table('news')->where('id', $id)->update($validated);

                // 2. Cập nhật số lượng nếu danh mục thay đổi
                if ($old_category_id != $validated['category_id']) {
                    // Giảm count của danh mục cũ (nếu nó vẫn tồn tại)
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
                // Đảm bảo rằng count không bao giờ âm
                DB::table('categories')->where('id', $news->category_id)->where('count', '>', 0)->decrement('count');
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi xóa tin tức: ' . $e->getMessage());
        }

        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được xóa thành công!');
    }
}