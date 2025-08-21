<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckPermission
{
    /**
     * Middleware kiểm tra xem user có quyền cụ thể không.
     *
     * Cách dùng trong route:
     * Route::middleware('checkPermission:assign_permission')->group(...)
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param string $permission Tên quyền cần kiểm tra (theo `name`)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Nếu user có đúng quyền này thì cho phép
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        // Nếu là quyền nhỏ lẻ (vd: admin.products.create), kiểm tra quyền tổng quát manage_products
        $parts = explode('.', $permission);
        if (count($parts) >= 2) {
            $module = $parts[1];
            $managePermission = 'manage_' . $module;
            if ($user->hasPermission($managePermission)) {
                return $next($request);
            }
        }

        abort(403, 'Bạn không có quyền truy cập: ' . $permission);
    }
}
