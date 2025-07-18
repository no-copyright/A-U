<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}