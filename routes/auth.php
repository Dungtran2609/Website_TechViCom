<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthModalController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Modal routes - these will open the auth modal with specific forms
Route::get('auth/modal/login', [AuthModalController::class, 'showLogin'])
    ->name('auth.modal.login');

Route::get('auth/modal/register', [AuthModalController::class, 'showRegister'])
    ->name('auth.modal.register');

Route::get('auth/modal/forgot-password', [AuthModalController::class, 'showForgotPassword'])
    ->name('auth.modal.forgot-password');

Route::get('auth/modal/reset-password/{token}', [AuthModalController::class, 'showResetPassword'])
    ->name('auth.modal.reset-password');

Route::get('auth/modal/confirm-password', [AuthModalController::class, 'showConfirmPassword'])
    ->name('auth.modal.confirm-password');

Route::get('auth/modal/verify-email', [AuthModalController::class, 'showVerifyEmail'])
    ->name('auth.modal.verify-email');

Route::middleware('guest')->group(function () {
    // Legacy routes - redirect to modal
    Route::get('register', function() {
        return redirect()->route('home')->with('openAuthModal', 'register');
    })->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', function() {
        return redirect()->route('home')->with('openAuthModal', 'login');
    })->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', function() {
        return redirect()->route('home')->with('openAuthModal', 'forgot-password');
    })->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', function($token) {
        return redirect()->route('home')->with('openAuthModal', 'reset-password')->with('token', $token);
    })->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', function() {
        return redirect()->route('home')->with('openAuthModal', 'verify-email');
    })->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', function() {
        return redirect()->route('home')->with('openAuthModal', 'confirm-password');
    })->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
