<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthModalController extends Controller
{
    /**
     * Show login form in modal
     */
    public function showLogin(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'action' => 'showLoginForm'
        ]);
    }

    /**
     * Show register form in modal
     */
    public function showRegister(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'action' => 'showRegisterForm'
        ]);
    }

    /**
     * Show forgot password form in modal
     */
    public function showForgotPassword(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'action' => 'showForgotPasswordForm'
        ]);
    }

    /**
     * Show reset password form in modal
     */
    public function showResetPassword(Request $request): JsonResponse
    {
        $token = $request->route('token');
        $email = $request->query('email', '');

        return response()->json([
            'success' => true,
            'action' => 'showResetPasswordForm',
            'data' => [
                'token' => $token,
                'email' => $email
            ]
        ]);
    }

    /**
     * Show confirm password form in modal
     */
    public function showConfirmPassword(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'action' => 'showConfirmPasswordForm'
        ]);
    }

    /**
     * Show verify email form in modal
     */
    public function showVerifyEmail(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'action' => 'showVerifyEmailForm'
        ]);
    }
}
