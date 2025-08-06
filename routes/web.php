<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Products\AdminBrandController;
use App\Http\Controllers\Admin\Products\AdminProductController;
use App\Http\Controllers\Admin\Products\AdminCategoryController;
use App\Http\Controllers\Admin\Products\AdminAttributeController;
use App\Http\Controllers\Admin\Products\AdminAttributeValueController;
use App\Http\Controllers\Admin\Orders\OrderController;
use App\Http\Controllers\Admin\Users\AdminPermissionController;
use App\Http\Controllers\Admin\Users\AdminRoleController;
use App\Http\Controllers\Admin\Users\AdminUserController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\CheckRole;

// Trang chủ client
Route::get('/', fn() => view('client.home'))->name('home');

// Admin routes (phải đăng nhập và là admin/staff)
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // ==== Products: Categories ====
    Route::prefix('products/categories')->name('products.categories.')->group(function () {
        Route::get('trashed', [AdminCategoryController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminCategoryController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminCategoryController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminCategoryController::class)->parameters(['' => 'category'])->names('index', 'create', 'store', 'show', 'edit', 'update', 'destroy');
    });

    // ==== Products: Brands ====
    Route::prefix('products/brands')->name('products.brands.')->group(function () {
        Route::get('trashed', [AdminBrandController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminBrandController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminBrandController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminBrandController::class)->parameters(['' => 'brand'])->names('index', 'create', 'store', 'show', 'edit', 'update', 'destroy');
    });

    // ==== Products: Attributes ====
    Route::prefix('products/attributes')->name('products.attributes.')->group(function () {
        Route::get('trashed', [AdminAttributeController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminAttributeController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminAttributeController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminAttributeController::class)->parameters(['' => 'attribute'])->names('index', 'create', 'store', 'show', 'edit', 'update', 'destroy');
    });

    // ==== Products: Attribute Values ====
    Route::prefix('products/attributes')->name('products.attributes.')->group(function () {
        Route::get('{attribute}/values/trashed', [AdminAttributeValueController::class, 'trashed'])->name('values.trashed');
        Route::post('values/{id}/restore', [AdminAttributeValueController::class, 'restore'])->name('values.restore');
        Route::delete('values/{id}/force-delete', [AdminAttributeValueController::class, 'forceDelete'])->name('values.force-delete');
        Route::get('{attribute}/values', [AdminAttributeValueController::class, 'index'])->name('values.index');
        Route::post('{attribute}/values', [AdminAttributeValueController::class, 'store'])->name('values.store');
        Route::get('values/{value}/edit', [AdminAttributeValueController::class, 'edit'])->name('values.edit');
        Route::put('values/{value}', [AdminAttributeValueController::class, 'update'])->name('values.update');
        Route::delete('values/{value}', [AdminAttributeValueController::class, 'destroy'])->name('values.destroy');
    });

    // ==== Products ====
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('trashed', [AdminProductController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminProductController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminProductController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminProductController::class)->parameters(['' => 'product'])->names('index', 'create', 'store', 'show', 'edit', 'update', 'destroy');
    });

    // ==== Users ====
    Route::prefix('users')->middleware(CheckRole::class . ':admin')->name('users.')->group(function () {
        Route::get('trashed', [AdminUserController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminUserController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('force-delete');
        Route::get('admins', [AdminUserController::class, 'admins'])->name('admins');
        Route::get('staffs', [AdminUserController::class, 'staffs'])->name('staffs');
        Route::get('customers', [AdminUserController::class, 'customers'])->name('customers');
        Route::resource('', AdminUserController::class)->parameters(['' => 'user'])->names('index', 'create', 'store', 'show', 'edit', 'update', 'destroy');
    });

    // ==== Roles ====
    Route::prefix('roles')->middleware(CheckRole::class . ':admin')->name('roles.')->group(function () {
        Route::get('trashed', [AdminRoleController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminRoleController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminRoleController::class, 'forceDelete'])->name('force-delete');
        Route::post('update-users', [AdminRoleController::class, 'updateUsers'])->name('updateUsers');
        Route::get('list', [AdminRoleController::class, 'list'])->name('list');
        Route::resource('', AdminRoleController::class)->parameters(['' => 'role'])->names('index', 'create', 'store', 'show', 'edit', 'update', 'destroy');
    });

    // ==== Permissions ====
    Route::prefix('permissions')->middleware(CheckRole::class . ':admin')->name('permissions.')->group(function () {
        Route::post('update-roles', [AdminPermissionController::class, 'updateRoles'])->name('updateRoles');
        Route::resource('', AdminPermissionController::class)->parameters(['' => 'permission'])->names('index', 'create', 'store', 'show', 'edit', 'update', 'destroy');
    });

    // ==== Orders ====
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('trashed', [OrderController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [OrderController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [OrderController::class, 'forceDelete'])->name('forceDelete');
        Route::post('{id}/update-status', [OrderController::class, 'updateOrders'])->name('updateOrders');
        Route::get('returns', [OrderController::class, 'returnsIndex'])->name('returns');
        Route::post('returns/{id}/process', [OrderController::class, 'processReturn'])->name('process-return');
        Route::resource('', OrderController::class)->parameters(['' => 'order'])->names('index', 'create', 'store', 'show', 'edit', 'update', 'destroy');
    });
});

require __DIR__ . '/auth.php';
