<?php

use App\Http\Controllers\Admin\Coupons\AdminCouponController;
use App\Http\Middleware\CheckPermission;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;

// Trang chủ client
Route::get('/', function () {
    return view('client.home');
})->name('home');

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Trang dashboard admin (chỉ cho admin hoặc staff)
    Route::get('/', function () {
        return view('admin.dashboard');
    })->middleware(['auth', IsAdmin::class])->name('dashboard');
    Route::prefix('coupons')->middleware(CheckPermission::class . ':manage_coupons')->name('coupons.')->group(function () {
        Route::resource('/', AdminCouponController::class)->parameters(['' => 'coupon'])->except(['show']);
        Route::put('{id}/restore', [AdminCouponController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminCouponController::class, 'forceDelete'])->name('forceDelete');
    });

    
});
require __DIR__ . '/auth.php';
