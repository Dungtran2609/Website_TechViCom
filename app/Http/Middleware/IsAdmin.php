<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Middleware cho phép user có vai trò admin hoặc staff vào trang quản trị.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || (! $user->hasRole('admin') && ! $user->hasRole('staff'))) {
            return response()->view('errors.403', [], 403); // Trả về view 403
        }

        return $next($request);
    }
}
