<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    /**
     * Hiển thị trang hồ sơ của quản trị viên đang đăng nhập.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Sử dụng Auth::user() để lấy thông tin của người dùng đã được xác thực
        $admin = Auth::user();

        // Trả về một view và truyền dữ liệu của admin vào view đó
        return view('admin.users.profile', compact('admin'));
    }
}
