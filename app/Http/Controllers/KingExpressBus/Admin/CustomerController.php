<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Throwable;

use Illuminate\Support\Facades\Mail;
use App\Mail\KingExpressBus\CustomerRegistrationSuccess;
use App\Mail\KingExpressBus\AdminNewCustomerNotification;

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
            'training_id' => 'khóa học',
            'full_name_parent' => 'họ tên phụ huynh',
            'phone' => 'số điện thoại',
            'full_name_children' => 'họ tên học viên',
            'date_of_birth' => 'ngày sinh',
            'address' => 'địa chỉ',
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
            
            // === BẮT ĐẦU LOGIC GỬI EMAIL ===
            
            // Lấy thêm tên khóa học để hiển thị trong email
            if (!empty($validatedData['training_id'])) {
                $training = DB::table('trainings')->find($validatedData['training_id']);
                $validatedData['training_title'] = $training ? $training->title : 'Chưa chọn';
            }

            // Gửi email trong một khối try-catch riêng để không ảnh hưởng đến response của API
            try {
                // 1. Gửi email cho khách hàng
                Mail::to($validatedData['email'])->send(new CustomerRegistrationSuccess($validatedData));

                // 2. Gửi email cho admin (lấy từ file .env)
                $adminEmail = env('ADMIN_EMAIL_RECIPIENT', config('mail.from.address'));
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new AdminNewCustomerNotification($validatedData));
                }
            } catch (Throwable $e) {
                // Nếu gửi mail lỗi, chỉ ghi log chứ không báo lỗi cho người dùng
                Log::error('Failed to send registration emails for customer ID ' . $customerId . ': ' . $e->getMessage());
            }

            // === KẾT THÚC LOGIC GỬI EMAIL ===

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
