<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Products\AdminBrandController;
use App\Http\Controllers\Admin\Products\AdminCategoryController;

// Trang chủ client
Route::get('/', function () {
    return view('client.home');
})->name('home');

// Trang dashboard admin (chỉ cho admin hoặc staff)
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {

    // Trang dashboard admin
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Quản lý danh mục sản phẩm
    Route::prefix('products/categories')->name('products.categories.')->group(function () {
        Route::get('trashed', [AdminCategoryController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminCategoryController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminCategoryController::class, 'forceDelete'])->name('force-delete');
        Route::resource('/', AdminCategoryController::class)->parameters(['' => 'category'])->names('');
    });

    // Quản lý thương hiệu sản phẩm
    Route::prefix('products/brands')->name('products.brands.')->group(function () {
    Route::get('trashed', [AdminBrandController::class, 'trashed'])->name('trashed');
    Route::post('{id}/restore', [AdminBrandController::class, 'restore'])->name('restore');
    Route::delete('{id}/force-delete', [AdminBrandController::class, 'forceDelete'])->name('force-delete');
    Route::resource('/', AdminBrandController::class)->parameters(['' => 'brand'])->names('');
});

});

require __DIR__.'/auth.php';
