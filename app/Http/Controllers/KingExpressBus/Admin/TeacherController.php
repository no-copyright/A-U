<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator; // <-- THÊM DÒNG NÀY
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    use SlugGenerator; // <-- SỬ DỤNG TRAIT

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = DB::table('teachers')->orderBy('full_name', 'asc')->get();
        return view('kingexpressbus.admin.modules.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teacher = null;
        return view('kingexpressbus.admin.modules.teachers.createOrEdit', compact('teacher'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255,full_name',
            'role' => 'required|string|max:255',
            'qualifications' => 'required|string',
            'avatar' => 'required|string|max:255',
            'facebook' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email|max:50',
            'description' => 'nullable|string',
        ]);
        
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        // === LOGIC TẠO SLUG MỚI (Đã thay đổi) ===
        // 1. Tạo slug tạm
        $validated['slug'] = Str::slug($validated['full_name']);
        
        // 2. Insert để lấy ID
        $id = DB::table('teachers')->insertGetId($validated);
        
        // 3. Tạo slug cuối cùng và update
        $finalSlug = $this->generateSlug($validated['full_name'], $id);
        DB::table('teachers')->where('id', $id)->update(['slug' => $finalSlug]);
        // ===========================================

        return redirect()->route('admin.teachers.index')->with('success', 'Giáo viên đã được thêm thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = DB::table('teachers')->find($id);
        if (!$teacher) {
            abort(404);
        }
        return view('kingexpressbus.admin.modules.teachers.createOrEdit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacher = DB::table('teachers')->find($id);
        if (!$teacher) {
            abort(404);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255|unique:teachers,full_name,' . $id,
            'role' => 'required|string|max:255',
            'qualifications' => 'required|string',
            'avatar' => 'required|string|max:255',
            'facebook' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('teachers')->ignore($id),
            ],
            'description' => 'nullable|string',
        ]);

        // === LOGIC CẬP NHẬT SLUG MỚI (Đã thay đổi) ===
        if ($teacher->full_name !== $validated['full_name']) {
            $validated['slug'] = $this->generateSlug($validated['full_name'], $id);
        }
        // ===========================================

        $validated['updated_at'] = now();

        DB::table('teachers')->where('id', $id)->update($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Thông tin giáo viên đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = DB::table('teachers')->find($id);
        if (!$teacher) {
            return back()->with('error', 'Không tìm thấy giáo viên để xóa.');
        }

        DB::table('teachers')->where('id', $id)->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Thông tin giáo viên đã được xóa thành công!');
    }

    public function getTeacherListApi(Request $request): JsonResponse
    {
        $pageSize = $request->query('pageSize', 10);

        $paginator = DB::table('teachers')
            ->select('id', 'full_name', 'slug', 'role', 'qualifications', 'avatar')
            ->orderBy('full_name', 'asc')
            ->paginate($pageSize);
        
        $transformedData = $paginator->getCollection()->map(function ($teacher) {
            // Chuyển đổi URL avatar
            if (!empty($teacher->avatar)) {
                $teacher->avatar = url($teacher->avatar);
            }
            
            // SỬA LẠI TRƯỜNG QUALIFICATIONS
            if (!empty($teacher->qualifications)) {
                // Tách chuỗi bằng dấu ',', sau đó xóa khoảng trắng thừa ở mỗi phần tử
                $teacher->qualifications = array_map('trim', explode(',', $teacher->qualifications));
            } else {
                // Nếu trường này rỗng, trả về một mảng trống
                $teacher->qualifications = [];
            }

            return $teacher;
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

    /**
     * API: Lấy thông tin chi tiết của một giáo viên theo slug.
     */
    public function getTeacherDetailApi(string $slug): JsonResponse
    {
        $teacher = DB::table('teachers')->where('slug', $slug)->first();

        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found.'], 404);
        }
        
        // Chuyển đổi URL avatar
        if (!empty($teacher->avatar)) {
            $teacher->avatar = url($teacher->avatar);
        }

        // SỬA LẠI TRƯỜNG QUALIFICATIONS
        if (!empty($teacher->qualifications)) {
            $teacher->qualifications = array_map('trim', explode(',', $teacher->qualifications));
        } else {
            $teacher->qualifications = [];
        }

        return response()->json(['success' => true, 'data' => $teacher]);
    }
}