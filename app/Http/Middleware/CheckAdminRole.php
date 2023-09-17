<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Kiểm tra vai trò của người dùng
            $user = Auth::user();
            if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
                return $next($request);
            }
        }

        // Nếu không phải "super-admin" hoặc "admin", chuyển hướng hoặc trả về lỗi 403
        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang quản trị.');
    }
}
