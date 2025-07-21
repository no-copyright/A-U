<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TrainingController extends Controller
{
    use SlugGenerator;

    public function index(Request $request)
    {
        $query = DB::table('trainings');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        $trainings = $query->orderBy('priority', 'asc')->orderBy('title', 'asc')->paginate(10);

        return view('kingexpressbus.admin.modules.training.index', compact('trainings'));
    }

    public function create()
    {
        $training = null;
        return view('kingexpressbus.admin.modules.training.createOrEdit', compact('training'));
    }

    public function store(Request $request)
    {
        // Lọc bỏ các học phần rỗng trước khi validate
        $curriculum = $request->input('curriculum', []);
        if (is_array($curriculum)) {
            $curriculum = array_filter($curriculum, function ($item) {
                return !empty($item['module']) && !empty($item['content']);
            });
            $request->merge(['curriculum' => array_values($curriculum)]);
        }

        $rules = [
            'title' => 'required|string|max:255|unique:trainings,title',
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
            'content' => 'nullable|string',
            'images' => 'nullable|array',
            'curriculum' => 'nullable|array',
            'curriculum.*.module' => 'required|string|max:255',
            'curriculum.*.content' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        $validator->setAttributeNames([
            'title' => 'tiêu đề khóa học',
            'priority' => 'độ ưu tiên',
            'age' => 'độ tuổi',
            'description' => 'mô tả ngắn',
            'thumbnail' => 'ảnh đại diện',
            'duration' => 'thời lượng',
            'outcome' => 'kết quả đầu ra',
            'method' => 'phương pháp giảng dạy',
            'speaking' => 'kỹ năng Speaking',
            'listening' => 'kỹ năng Listening',
            'reading' => 'kỹ năng Reading',
            'writing' => 'kỹ năng Writing',
            'curriculum.*.module' => 'tên học phần',
            'curriculum.*.content' => 'nội dung học phần',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated['created_at'] = now();
        $validated['updated_at'] = now();
        $validated['images'] = json_encode($request->input('images', []));
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

        $training->images = json_decode($training->images, true) ?? [];
        $training->curriculum = json_decode($training->curriculum, true) ?? [];

        return view('kingexpressbus.admin.modules.training.createOrEdit', compact('training'));
    }

    public function update(Request $request, string $id)
    {
        $training = DB::table('trainings')->find($id);
        if (!$training) {
            abort(404);
        }

        // Lọc bỏ các học phần rỗng trước khi validate
        $curriculum = $request->input('curriculum', []);
        if (is_array($curriculum)) {
            $curriculum = array_filter($curriculum, function ($item) {
                return !empty($item['module']) && !empty($item['content']);
            });
            $request->merge(['curriculum' => array_values($curriculum)]);
        }

        $rules = [
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
            'content' => 'nullable|string',
            'images' => 'nullable|array',
            'curriculum' => 'nullable|array',
            'curriculum.*.module' => 'required|string|max:255',
            'curriculum.*.content' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        $validator->setAttributeNames([
            'title' => 'tiêu đề khóa học',
            'priority' => 'độ ưu tiên',
            'age' => 'độ tuổi',
            'description' => 'mô tả ngắn',
            'thumbnail' => 'ảnh đại diện',
            'duration' => 'thời lượng',
            'outcome' => 'kết quả đầu ra',
            'method' => 'phương pháp giảng dạy',
            'speaking' => 'kỹ năng Speaking',
            'listening' => 'kỹ năng Listening',
            'reading' => 'kỹ năng Reading',
            'writing' => 'kỹ năng Writing',
            'curriculum.*.module' => 'tên học phần',
            'curriculum.*.content' => 'nội dung học phần',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if ($training->title !== $validated['title']) {
            $validated['slug'] = $this->generateSlug($validated['title'], $id);
        }

        $validated['images'] = json_encode($request->input('images', []));
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

        $training->images = json_decode($training->images, true) ?? [];
        if (!empty($training->images) && is_array($training->images)) {
            $training->images = array_map(function ($path) {
                return url($path);
            }, $training->images);
        }

        $training->curriculum = json_decode($training->curriculum, true) ?? [];

        return response()->json(['success' => true, 'data' => $training]);
    }
}