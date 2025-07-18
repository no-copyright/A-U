<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = DB::table('categories')
            ->orderBy('name', 'asc')
            ->get();

        return view('kingexpressbus.admin.modules.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = null;
        return view('kingexpressbus.admin.modules.categories.createOrEdit', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        // Mặc định 'count' là 0 khi tạo mới
        $validated['count'] = 0;
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        DB::table('categories')->insert($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = DB::table('categories')->find($id);
        if (!$category) {
            abort(404);
        }
        return view('kingexpressbus.admin.modules.categories.createOrEdit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = DB::table('categories')->find($id);
        if (!$category) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('categories')->ignore($id),
            ],
        ]);

        $validated['updated_at'] = now();

        DB::table('categories')->where('id', $id)->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = DB::table('categories')->find($id);
        if (!$category) {
            return back()->with('error', 'Không tìm thấy danh mục để xóa.');
        }

        // Kiểm tra xem có bài viết nào thuộc danh mục này không
        $newsCount = DB::table('news')->where('category_id', $id)->count();
        if ($newsCount > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì vẫn còn bài viết liên quan.');
        }

        DB::table('categories')->where('id', $id)->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công!');
    }
}
