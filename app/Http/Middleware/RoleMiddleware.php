<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle($request, Closure $next, string $roles): Response
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login'); // Chuyển hướng đến trang login
        }

        // Lấy vai trò của người dùng hiện tại
        $userRole = Auth::user()->account_role;

        // Tách các vai trò từ $roles bằng dấu '|'
        $allowedRoles = explode('|', $roles);

        // Kiểm tra nếu vai trò của người dùng không nằm trong danh sách cho phép
        if (!in_array($userRole, $allowedRoles)) {
            return redirect()->route('admin.dashboard'); // Chuyển hướng về dashboard nếu không có quyền
        }

        return $next($request);
    }
}