<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ParentsCornerController extends Controller
{
    use SlugGenerator;

    public function index(Request $request)
    {
        $query = DB::table('parents_corner');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $reviews = $query->orderBy('priority', 'asc')->orderBy('created_at', 'desc')->paginate(10);

        return view('kingexpressbus.admin.modules.parents_corner.index', compact('reviews'));
    }

    public function create()
    {
        $review = null;
        return view('kingexpressbus.admin.modules.parents_corner.createOrEdit', compact('review'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'priority' => 'required|integer|min:0',
            'describe' => 'required|string|max:50',
            'image' => 'required|string|max:255',
            'rate' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            $validated['created_at'] = now();
            $validated['updated_at'] = now();

            $validated['slug'] = Str::slug($validated['name']);
            $id = DB::table('parents_corner')->insertGetId($validated);

            $finalSlug = $this->generateSlug($validated['name'], $id);
            DB::table('parents_corner')->where('id', $id)->update(['slug' => $finalSlug]);
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi tạo đánh giá: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.parents-corner.index')->with('success', 'Đánh giá của phụ huynh đã được tạo thành công!');
    }

    public function edit(string $id)
    {
        $review = DB::table('parents_corner')->find($id);
        if (!$review) {
            abort(404);
        }
        return view('kingexpressbus.admin.modules.parents_corner.createOrEdit', compact('review'));
    }

    public function update(Request $request, string $id)
    {
        $review = DB::table('parents_corner')->find($id);
        if (!$review) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'priority' => 'required|integer|min:0',
            'describe' => 'required|string|max:50',
            'image' => 'required|string|max:255',
            'rate' => 'required|string',
            'content' => 'required|string',
        ]);

        if ($review->name !== $validated['name']) {
            $validated['slug'] = $this->generateSlug($validated['name'], $id);
        }

        try {
            $validated['updated_at'] = now();
            DB::table('parents_corner')->where('id', $id)->update($validated);
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật đánh giá: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.parents-corner.index')->with('success', 'Đánh giá đã được cập nhật thành công!');
    }

    public function destroy(string $id)
    {
        $review = DB::table('parents_corner')->find($id);
        if (!$review) {
            return back()->with('error', 'Không tìm thấy đánh giá để xóa.');
        }

        DB::table('parents_corner')->where('id', $id)->delete();
        return redirect()->route('admin.parents-corner.index')->with('success', 'Đánh giá đã được xóa thành công!');
    }

    public function getParentsCornerApi(Request $request): JsonResponse
    {
        $pageSize = $request->query('pageSize', 10);

        $paginator = DB::table('parents_corner')
            ->select('id', 'name', 'slug', 'describe', 'image', 'rate', 'content', 'created_at')
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize);

        $transformedData = $paginator->getCollection()->map(function ($review) {
            if (!empty($review->image)) {
                $review->image = url($review->image);
            }
            return $review;
        });

        return response()->json([
            'success' => true,
            'currentPage' => $paginator->currentPage(),
            'totalPages' => $paginator->lastPage(),
            'totalElements' => $paginator->total(),
            'pageSize' => $paginator->perPage(),
            'data' => $transformedData,
        ]);
    }

    public function getParentsCornerDetailApi(string $slug): JsonResponse
    {
        $review = DB::table('parents_corner')->where('slug', $slug)->first();

        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Review not found.'], 404);
        }

        if (!empty($review->image)) {
            $review->image = url($review->image);
        }

        return response()->json(['success' => true, 'data' => $review]);
    }
}
