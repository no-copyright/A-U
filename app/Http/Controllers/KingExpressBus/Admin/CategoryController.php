<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator; // <-- THÊM DÒNG NÀY
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use SlugGenerator; // <-- THÊM DÒNG NÀY

    // ... (Các phương thức index, create, edit không thay đổi)
    public function index(Request $request)
    {
        $query = DB::table('categories');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $categories = $query->orderBy('name', 'asc')->paginate(10);

        return view('kingexpressbus.admin.modules.categories.index', compact('categories'));
    }

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
            'name' => 'required|max:255',
        ]);

        // Thêm các trường mặc định
        $validated['count'] = 0;
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        // === LOGIC TẠO SLUG MỚI (Đã thay đổi) ===
        // B1: Tạm thời tạo slug rỗng hoặc từ tên để insert trước
        $validated['slug'] = Str::slug($validated['name']);

        // B2: Thêm bản ghi vào DB để lấy ID
        $id = DB::table('categories')->insertGetId($validated);

        // B3: Tạo slug cuối cùng với ID và cập nhật lại bản ghi
        $finalSlug = $this->generateSlug($validated['name'], $id);
        DB::table('categories')->where('id', $id)->update(['slug' => $finalSlug]);
        // ===========================================

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công!');
    }

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

        // === LOGIC CẬP NHẬT SLUG MỚI (Đã thay đổi) ===
        // Chỉ cập nhật slug nếu tên danh mục thay đổi
        if ($category->name !== $validated['name']) {
            $validated['slug'] = $this->generateSlug($validated['name'], $id);
        }
        // ===========================================

        $validated['updated_at'] = now();

        DB::table('categories')->where('id', $id)->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    // ... (Phương thức destroy không thay đổi)
    public function destroy(string $id)
    {
        $category = DB::table('categories')->find($id);
        if (!$category) {
            return back()->with('error', 'Không tìm thấy danh mục để xóa.');
        }

        $newsCount = DB::table('news')->where('category_id', $id)->count();
        if ($newsCount > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì vẫn còn bài viết liên quan.');
        }

        DB::table('categories')->where('id', $id)->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công!');
    }

    public function getCategories(): JsonResponse
    {
        $categories = DB::table('categories')
            ->select('id', 'name', 'slug', 'count')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }


    public function getNewsByCategorySlug(Request $request, string $categorySlug): JsonResponse
    {
        $category = DB::table('categories')->where('slug', $categorySlug)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);
        }

        $pageSize = $request->query('pageSize', 10);

        // SỬA LẠI CÁC TRƯỜNG SELECT: Bỏ đi category_name và category_slug
        $paginator = DB::table('news')
            ->where('category_id', $category->id) // Lọc trực tiếp bằng category_id sẽ hiệu quả hơn
            ->select(
                'id',
                'title',
                'slug',
                'thumbnail',
                'author',
                'excerpt',
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
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'currentPage' => $paginator->currentPage(),
            'totalPages' => $paginator->lastPage(),
            'totalElements' => $paginator->total(),
            'pageSize' => $paginator->perPage(),
            'data' => $transformedNews,
        ]);
    }
}
