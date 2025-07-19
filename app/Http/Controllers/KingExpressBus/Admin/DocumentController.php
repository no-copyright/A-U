<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('document');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $documents = $query->orderBy('priority', 'asc')->orderBy('name', 'asc')->paginate(10);

        return view('kingexpressbus.admin.modules.document.index', compact('documents'));
    }

    public function create()
    {
        $document = null;
        return view('kingexpressbus.admin.modules.document.createOrEdit', compact('document'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'priority' => 'required|integer|min:0',
            'src' => 'required|string',
        ]);

        try {
            $validated['created_at'] = now();
            $validated['updated_at'] = now();
            DB::table('document')->insert($validated);
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi tạo tài liệu: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.documents.index')->with('success', 'Tài liệu đã được tạo thành công!');
    }

    public function edit(string $id)
    {
        $document = DB::table('document')->find($id);
        if (!$document) {
            abort(404);
        }
        return view('kingexpressbus.admin.modules.document.createOrEdit', compact('document'));
    }

    public function update(Request $request, string $id)
    {
        $document = DB::table('document')->find($id);
        if (!$document) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'priority' => 'required|integer|min:0',
            'src' => 'required|string',
        ]);

        try {
            $validated['updated_at'] = now();
            DB::table('document')->where('id', $id)->update($validated);
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật tài liệu: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.documents.index')->with('success', 'Tài liệu đã được cập nhật thành công!');
    }

    public function destroy(string $id)
    {
        $document = DB::table('document')->find($id);
        if (!$document) {
            return back()->with('error', 'Không tìm thấy tài liệu để xóa.');
        }
        DB::table('document')->where('id', $id)->delete();
        return redirect()->route('admin.documents.index')->with('success', 'Tài liệu đã được xóa thành công!');
    }

    public function getDocumentsApi(Request $request): JsonResponse
    {
        $pageSize = $request->query('pageSize', 10);

        $paginator = DB::table('document')
            ->select('id', 'name', 'src', 'created_at')
            ->orderBy('priority', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($pageSize);

        $transformedData = $paginator->getCollection()->map(function ($document) {
            if (!empty($document->src)) {
                $document->src = url($document->src);
            }
            return $document;
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
