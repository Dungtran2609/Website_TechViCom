<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class InvoiceSpamMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $email = $request->input('email');
        
        // Rate limiting cho IP
        $ipKey = 'invoice_ip_' . $ip;
        $ipLimit = RateLimiter::tooManyAttempts($ipKey, 10); // 10 requests per minute
        
        if ($ipLimit) {
            return response()->json([
                'success' => false,
                'message' => 'Quá nhiều yêu cầu từ IP này. Vui lòng thử lại sau 1 phút.'
            ], 429);
        }
        
        // Rate limiting cho email
        if ($email) {
            $emailKey = 'invoice_email_' . md5($email);
            $emailLimit = RateLimiter::tooManyAttempts($emailKey, 5); // 5 requests per minute per email
            
            if ($emailLimit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quá nhiều yêu cầu cho email này. Vui lòng thử lại sau 1 phút.'
                ], 429);
            }
        }
        
        // Increment counters
        RateLimiter::hit($ipKey, 60); // 1 minute
        if ($email) {
            RateLimiter::hit($emailKey, 60); // 1 minute
        }
        
        return $next($request);
    }
}
