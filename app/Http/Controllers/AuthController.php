<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // Đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'account_username' => 'required|string',
            'account_password' => 'required|string',
        ], [
            'account_username.required' => 'Tên đăng nhập không được để trống.',
            'account_password.required' => 'Mật khẩu không được để trống.',
            'string' => ':attribute phải là chuỗi ký tự hợp lệ.',
        ]);

        // Kiểm tra tài khoản và mật khẩu
        $account = Accounts::where('account_username', $request->account_username)->first();

        if (!$account) {
            return back()->withErrors(['account_username' => 'Tên đăng nhập không chính xác']);
        }
        if (!Hash::check($request->account_password, $account->account_password)) {
            return back()->withErrors(['account_password' => 'Mật khẩu không chính xác']);
        }

        // Kiểm tra nếu tài khoản bị khóa
        if ($account->account_active === 'locked') {
            return back()->withErrors(['account_username' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.']);
        }

        // Kiểm tra vai trò của tài khoản, chỉ cho phép 'admin' hoặc 'employee' đăng nhập
        if ($account->account_role !== 'admin' && $account->account_role !== 'employee') {
            return back()->with('alert-danger', 'Bạn không có quyền truy cập');
        }

        Auth::login($account);

        // Xác định role của người dùng
        if ($account->account_role == 'admin') {
            // Đăng nhập là quản trị viên
            return redirect()->route('admin.dashboard')->with('alert-info', 'Đăng nhập thành công');
        } else {
            // Đăng nhập là nhân viên
            
            return redirect()->route('admin.dashboard_employee')->with('alert-info', 'Đăng nhập thành công');
            
        }
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('alert-info', 'Bạn đã đăng xuất thành công');;
    }

    public function showChangePasswordForm()
{
    return view('admin.auth.change_password');
}

public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Mật khẩu mới không khớp.',
        ]);


    $user = Accounts::find(Auth::id());

    // Kiểm tra mật khẩu hiện tại
    if (!Hash::check($request->current_password, $user->account_password)) {
        return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác']);
    }
     // Lấy tài khoản với account_id = 1

    // Cập nhật mật khẩu mới
    $user->account_password = Hash::make($request->new_password);
    $user->save();

    Auth::logout();

    // Thêm thông báo và chuyển hướng về trang đăng nhập
    return redirect()->route('login')->with('alert-info', 'Mật khẩu đã được thay đổi thành công. Vui lòng đăng nhập lại.');
}

}