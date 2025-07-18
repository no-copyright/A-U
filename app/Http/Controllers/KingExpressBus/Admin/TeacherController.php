<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
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
            'full_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'qualifications' => 'required|string',
            'avatar' => 'required|string|max:255',
            'facebook' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email|max:50',
            'description' => 'nullable|string',
        ]);

        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        DB::table('teachers')->insert($validated);

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
            'full_name' => 'required|string|max:255',
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
}
