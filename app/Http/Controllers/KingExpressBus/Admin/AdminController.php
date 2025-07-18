<?php

namespace App\Http\Controllers\KingExpressBus\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Không cần dùng DB hay Validator nữa
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Hiển thị trang dashboard chính.
     * Logic lấy dữ liệu từ 'web_info' đã được loại bỏ.
     */
    public function index()
    {
        // Bây giờ, hàm này chỉ đơn giản trả về một view dashboard trống.
        // Tôi đã đổi view từ 'dashboard.edit' thành 'dashboard.index' cho hợp lý hơn.
        return view("kingexpressbus.admin.modules.dashboard.index");
    }

    /**
     * Hàm này không còn chức năng cập nhật.
     * Nó chỉ tồn tại để không gây lỗi nếu route POST vẫn còn.
     */
    public function update(Request $request)
    {
        // Toàn bộ logic cập nhật 'web_info' đã được loại bỏ.
        // Chỉ cần chuyển hướng người dùng về trang dashboard chính.
        return redirect()->route('admin.dashboard.index');
    }
}