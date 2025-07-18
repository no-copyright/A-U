<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Throwable;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = DB::table('customers')
            ->leftJoin('trainings', 'customers.training_id', '=', 'trainings.id')
            ->select('customers.id', 'customers.full_name_parent', 'customers.phone', 'customers.full_name_children', 'customers.created_at', 'trainings.title as training_title')
            ->orderBy('customers.created_at', 'desc')
            ->get();

        return view('kingexpressbus.admin.modules.customers.index', compact('customers'));
    }

    /**
     * Display the specified resource.
     * HÀM MỚI ĐƯỢC THÊM VÀO
     */
    public function show(string $id)
    {
        $customer = DB::table('customers')
            ->leftJoin('trainings', 'customers.training_id', '=', 'trainings.id')
            ->select('customers.*', 'trainings.title as training_title')
            ->where('customers.id', $id)
            ->firstOrFail(); // Dùng firstOrFail để tự động báo lỗi 404 nếu không tìm thấy

        return view('kingexpressbus.admin.modules.customers.show', compact('customer'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = DB::table('customers')->find($id);
        if (!$customer) {
            return back()->with('error', 'Không tìm thấy khách hàng để xóa.');
        }

        DB::table('customers')->where('id', $id)->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Thông tin khách hàng đã được xóa thành công!');
    }


    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'training_id' => 'nullable|exists:trainings,id',
            'full_name_parent' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^(0\d{9})$/'],
            'email' => 'required|email|max:50',
            'full_name_children' => 'required|string|max:255',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'address' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $validator->setAttributeNames([
            'training_id' => 0
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        try {
            $customerId = DB::table('customers')->insertGetId($validatedData);
        } catch (Throwable $e) {
            Log::error('API Customer Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra, không thể lưu thông tin.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thông tin thành công!',
            'data' => [
                'customer_id' => $customerId
            ]
        ], 201);
    }
}
