<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Kiểm tra nếu user có 1 trong các vai trò (slug) được truyền vào middleware.
     *
     * Cách sử dụng: middleware('checkRole:admin') hoặc middleware('checkRole:admin,staff')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Danh sách slug vai trò được chấp nhận
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Lấy danh sách slug các vai trò của người dùng
        $userRoles = $user->roles->pluck('slug')->toArray();

        // Nếu không có vai trò nào trùng khớp với danh sách yêu cầu
        if (!array_intersect($roles, $userRoles)) {
            abort(403, 'Bạn không có quyền truy cập. Cần vai trò: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
