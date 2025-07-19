<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    use SlugGenerator;

    public function index(Request $request)
    {
        $query = DB::table('teachers');

        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->input('search') . '%');
        }

        $teachers = $query->orderBy('priority', 'asc')->orderBy('full_name', 'asc')->paginate(10);

        return view('kingexpressbus.admin.modules.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $teacher = null;
        return view('kingexpressbus.admin.modules.teachers.createOrEdit', compact('teacher'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255,full_name',
            'priority' => 'required|integer|min:0',
            'role' => 'required|string|max:255',
            'qualifications' => 'required|string',
            'avatar' => 'required|string|max:255',
            'facebook' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email|max:50',
            'description' => 'nullable|string',
        ]);

        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        $validated['slug'] = Str::slug($validated['full_name']);
        $id = DB::table('teachers')->insertGetId($validated);

        $finalSlug = $this->generateSlug($validated['full_name'], $id);
        DB::table('teachers')->where('id', $id)->update(['slug' => $finalSlug]);

        return redirect()->route('admin.teachers.index')->with('success', 'Giáo viên đã được thêm thành công!');
    }

    public function edit(string $id)
    {
        $teacher = DB::table('teachers')->find($id);
        if (!$teacher) {
            abort(404);
        }
        return view('kingexpressbus.admin.modules.teachers.createOrEdit', compact('teacher'));
    }

    public function update(Request $request, string $id)
    {
        $teacher = DB::table('teachers')->find($id);
        if (!$teacher) {
            abort(404);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255|unique:teachers,full_name,' . $id,
            'priority' => 'required|integer|min:0',
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

        if ($teacher->full_name !== $validated['full_name']) {
            $validated['slug'] = $this->generateSlug($validated['full_name'], $id);
        }

        $validated['updated_at'] = now();

        DB::table('teachers')->where('id', $id)->update($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Thông tin giáo viên đã được cập nhật thành công!');
    }

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
            // Thêm 'facebook' và 'email' vào đây
            ->select('id', 'full_name', 'slug', 'role', 'qualifications', 'avatar', 'facebook', 'email')
            ->orderBy('priority', 'asc')
            ->orderBy('full_name', 'asc')
            ->paginate($pageSize);

        $transformedData = $paginator->getCollection()->map(function ($teacher) {
            if (!empty($teacher->avatar)) {
                $teacher->avatar = url($teacher->avatar);
            }

            if (!empty($teacher->qualifications)) {
                $teacher->qualifications = array_map('trim', explode(',', $teacher->qualifications));
            } else {
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

    public function getTeacherDetailApi(string $slug): JsonResponse
    {
        $teacher = DB::table('teachers')->where('slug', $slug)->first();

        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found.'], 404);
        }

        if (!empty($teacher->avatar)) {
            $teacher->avatar = url($teacher->avatar);
        }

        if (!empty($teacher->qualifications)) {
            $teacher->qualifications = array_map('trim', explode(',', $teacher->qualifications));
        } else {
            $teacher->qualifications = [];
        }

        return response()->json(['success' => true, 'data' => $teacher]);
    }
}
