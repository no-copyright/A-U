<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class TrainingController extends Controller
{
    use SlugGenerator;

    public function index()
    {
        $trainings = DB::table('trainings')->orderBy('priority', 'asc')->orderBy('title', 'asc')->get();
        return view('kingexpressbus.admin.modules.training.index', compact('trainings'));
    }

    public function create()
    {
        $training = null;
        return view('kingexpressbus.admin.modules.training.createOrEdit', compact('training'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255,title',
            'priority' => 'required|integer|min:0',
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
            'curriculum' => 'nullable|array',
            'curriculum.*.module' => 'required_with:curriculum|string|max:255',
            'curriculum.*.content' => 'required_with:curriculum|string',
        ]);

        $validated['created_at'] = now();
        $validated['updated_at'] = now();
        
        $validated['curriculum'] = json_encode($request->input('curriculum', []));
        
        $validated['slug'] = Str::slug($validated['title']);
        $id = DB::table('trainings')->insertGetId($validated);
        
        $finalSlug = $this->generateSlug($validated['title'], $id);
        DB::table('trainings')->where('id', $id)->update(['slug' => $finalSlug]);

        return redirect()->route('admin.training.index')->with('success', 'Khoá đào tạo đã được tạo thành công!');
    }

    public function edit(string $id)
    {
        $training = DB::table('trainings')->find($id);
        if (!$training) {
            abort(404);
        }

        $training->curriculum = json_decode($training->curriculum, true) ?? [];

        return view('kingexpressbus.admin.modules.training.createOrEdit', compact('training'));
    }

    public function update(Request $request, string $id)
    {
        $training = DB::table('trainings')->find($id);
        if (!$training) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:trainings,title,' . $id,
            'priority' => 'required|integer|min:0',
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
            'curriculum' => 'nullable|array',
            'curriculum.*.module' => 'required_with:curriculum|string|max:255',
            'curriculum.*.content' => 'required_with:curriculum|string',
        ]);

        if ($training->title !== $validated['title']) {
            $validated['slug'] = $this->generateSlug($validated['title'], $id);
        }

        $validated['curriculum'] = json_encode($request->input('curriculum', []));
        $validated['updated_at'] = now();

        DB::table('trainings')->where('id', $id)->update($validated);

        return redirect()->route('admin.training.index')->with('success', 'Khoá đào tạo đã được cập nhật thành công!');
    }

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

    public function getTrainingListApi(Request $request): JsonResponse
    {
        $pageSize = $request->query('pageSize', 10);

        $paginator = DB::table('trainings')
            ->select('id', 'title', 'slug', 'age', 'description', 'thumbnail', 'duration')
            ->orderBy('priority', 'asc')
            ->orderBy('title', 'asc')
            ->paginate($pageSize);
        
        $transformedData = $paginator->getCollection()->map(function ($training) {
            if (!empty($training->thumbnail)) {
                $training->thumbnail = url($training->thumbnail);
            }
            return $training;
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

    public function getTrainingDetailApi(string $slug): JsonResponse
    {
        $training = DB::table('trainings')->where('slug', $slug)->first();

        if (!$training) {
            return response()->json(['success' => false, 'message' => 'Training not found.'], 404);
        }
        
        if (!empty($training->thumbnail)) {
            $training->thumbnail = url($training->thumbnail);
        }

        $training->curriculum = json_decode($training->curriculum, true) ?? [];

        return response()->json(['success' => true, 'data' => $training]);
    }
}