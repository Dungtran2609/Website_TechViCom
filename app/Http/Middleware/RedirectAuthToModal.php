<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthToModal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a GET request to an auth route
        $authRoutes = [
            'login',
            'register', 
            'forgot-password',
            'confirm-password',
            'verify-email'
        ];

        $currentRoute = $request->route()->getName();
        
        if (in_array($currentRoute, $authRoutes)) {
            // Redirect to home with modal parameter
            return redirect()->route('home')->with('openAuthModal', $currentRoute);
        }

        // Handle reset password route
        if ($currentRoute === 'password.reset') {
            $token = $request->route('token');
            return redirect()->route('home')
                ->with('openAuthModal', 'reset-password')
                ->with('token', $token);
        }

        return $next($request);
    }
}
