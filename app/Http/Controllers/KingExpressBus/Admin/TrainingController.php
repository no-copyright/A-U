<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator; // <-- THÊM DÒNG NÀY
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    use SlugGenerator; // <-- SỬ DỤNG TRAIT

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainings = DB::table('trainings')->orderBy('title', 'asc')->get();
        return view('kingexpressbus.admin.modules.training.index', compact('trainings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $training = null;
        return view('kingexpressbus.admin.modules.training.createOrEdit', compact('training'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255,title',
            'age' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'outcome' => 'required|string',
            'method' => 'required|string|max:255',
            'speaking' => 'required|string',
            'listening' => 'required|string',
            'reading' => 'required|string',
            'writing' => 'required|string',
            'curriculum' => 'nullable|string',
        ]);

        $validated['created_at'] = now();
        $validated['updated_at'] = now();
        
        // === LOGIC TẠO SLUG MỚI (Đã thay đổi) ===
        // 1. Tạo slug tạm
        $validated['slug'] = Str::slug($validated['title']);
        
        // 2. Insert để lấy ID
        $id = DB::table('trainings')->insertGetId($validated);
        
        // 3. Tạo slug cuối cùng và update
        $finalSlug = $this->generateSlug($validated['title'], $id);
        DB::table('trainings')->where('id', $id)->update(['slug' => $finalSlug]);
        // ===========================================

        return redirect()->route('admin.training.index')->with('success', 'Khoá đào tạo đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $training = DB::table('trainings')->find($id);
        if (!$training) {
            abort(404);
        }
        return view('kingexpressbus.admin.modules.training.createOrEdit', compact('training'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $training = DB::table('trainings')->find($id);
        if (!$training) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:trainings,title,' . $id,
            'age' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'outcome' => 'required|string',
            'method' => 'required|string|max:255',
            'speaking' => 'required|string',
            'listening' => 'required|string',
            'reading' => 'required|string',
            'writing' => 'required|string',
            'curriculum' => 'nullable|string',
        ]);

        // === LOGIC CẬP NHẬT SLUG MỚI (Đã thay đổi) ===
        if ($training->title !== $validated['title']) {
            $validated['slug'] = $this->generateSlug($validated['title'], $id);
        }
        // ===========================================

        $validated['updated_at'] = now();

        DB::table('trainings')->where('id', $id)->update($validated);

        return redirect()->route('admin.training.index')->with('success', 'Khoá đào tạo đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $training = DB::table('trainings')->find($id);
        if (!$training) {
            return back()->with('error', 'Không tìm thấy khoá đào tạo để xóa.');
        }

        $customerCount = DB::table('customers')->where('training_id', $id)->count();
        if ($customerCount > 0) {
            return back()->with('error', 'Không thể xóa khoá đào tạo này vì có khách hàng đã đăng ký.');
        }

        DB::table('trainings')->where('id', $id)->delete();

        return redirect()->route('admin.training.index')->with('success', 'Khoá đào tạo đã được xóa thành công!');
    }
}