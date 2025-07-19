<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class ParentsCornerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = DB::table('parents_corner')->orderBy('created_at', 'desc')->get();
        return view('kingexpressbus.admin.modules.parents_corner.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $review = null;
        return view('kingexpressbus.admin.modules.parents_corner.createOrEdit', compact('review'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'describe' => 'required|string|max:50',
            'image' => 'required|string|max:255',
            'rate' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            $validated['created_at'] = now();
            $validated['updated_at'] = now();
            DB::table('parents_corner')->insert($validated);
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi tạo đánh giá: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.parents-corner.index')->with('success', 'Đánh giá của phụ huynh đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $review = DB::table('parents_corner')->find($id);
        if (!$review) {
            abort(404);
        }
        return view('kingexpressbus.admin.modules.parents_corner.createOrEdit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $review = DB::table('parents_corner')->find($id);
        if (!$review) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'describe' => 'required|string|max:50',
            'image' => 'required|string|max:255',
            'rate' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            $validated['updated_at'] = now();
            DB::table('parents_corner')->where('id', $id)->update($validated);
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật đánh giá: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.parents-corner.index')->with('success', 'Đánh giá đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
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
            ->select('id', 'name', 'describe', 'image', 'rate', 'content', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize);

        // Chuyển đổi URL cho ảnh đại diện
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
}