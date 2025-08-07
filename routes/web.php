<?php

use App\Http\Controllers\Admin\AdminBannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Contacts\AdminContactsController;
use App\Http\Controllers\Admin\Coupons\AdminCouponController;
use App\Http\Controllers\Admin\News\AdminNewsCategoryController;
use App\Http\Controllers\Admin\News\AdminNewsCommentController;
use App\Http\Controllers\Admin\News\AdminNewsController;
use App\Http\Controllers\Admin\Products\AdminBrandController;
use App\Http\Controllers\Admin\Products\AdminProductController;
use App\Http\Controllers\Admin\Products\AdminCategoryController;
use App\Http\Controllers\Admin\Products\AdminAttributeController;
use App\Http\Controllers\Admin\Products\AdminAttributeValueController;

use App\Http\Controllers\Admin\Users\AdminPermissionController;
use App\Http\Controllers\Admin\Users\AdminProfileController;
use App\Http\Controllers\Admin\Users\AdminRoleController;
use App\Http\Controllers\Admin\Users\AdminUserController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\CheckRole;

use App\Http\Controllers\Admin\Orders\AdminOrderController;
use App\Http\Controllers\Admin\Products\ProductCommentAdminController;
use App\Http\Middleware\CheckPermission;

// Trang chủ client
Route::get('/', fn() => view('client.home'))->name('home');

// Admin routes (phải đăng nhập và là admin/staff)
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Đăng xuất admin
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Quản lý danh mục sản phẩm
    Route::prefix('products/categories')->name('products.categories.')->group(function () {
        Route::get('trashed', [AdminCategoryController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminCategoryController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminCategoryController::class, 'forceDelete'])->name('force-delete');
        Route::resource('/', AdminCategoryController::class)->parameters(['' => 'category'])->names('');
    });
     // Product Comments
    Route::prefix('product-comments')->name('products.comments.')->group(function () {
        Route::get('/products-with-comments', [ProductCommentAdminController::class, 'productsWithComments'])->name('products-with-comments');
        Route::get('/', [ProductCommentAdminController::class, 'index'])->name('index');
        Route::get('/{id}', [ProductCommentAdminController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductCommentAdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductCommentAdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductCommentAdminController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/approve', [ProductCommentAdminController::class, 'approve'])->name('approve');
        Route::patch('/{id}/toggle', [ProductCommentAdminController::class, 'toggleStatus'])->name('toggle');
    });


    // Banners
    Route::resource('banner', AdminBannerController::class);
    // Quản lý thương hiệu sản phẩm
    Route::prefix('products/brands')->name('products.brands.')->group(function () {
        Route::get('trashed', [AdminBrandController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminBrandController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminBrandController::class, 'forceDelete'])->name('force-delete');
        Route::resource('/', AdminBrandController::class)->parameters(['' => 'brand'])->names('');
    });

    // Quản lý thuộc tính sản phẩm
    Route::get('products/attributes/trashed', [AdminAttributeController::class, 'trashed'])->name('products.attributes.trashed');
    Route::post('products/attributes/{id}/restore', [AdminAttributeController::class, 'restore'])->name('products.attributes.restore');
    Route::delete('products/attributes/{id}/force-delete', [AdminAttributeController::class, 'forceDelete'])->name('products.attributes.force-delete');
    Route::resource('products/attributes', AdminAttributeController::class)->names('products.attributes');

    // Quản lý giá trị thuộc tính
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

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('trashed', [AdminProductController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminProductController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminProductController::class, 'forceDelete'])->name('force-delete');
        Route::resource('/', AdminProductController::class)->parameters(['' => 'product'])->names('');
    });

    // ==== Users ====
    Route::prefix('users')->middleware(CheckRole::class . ':admin')->name('users.')->group(function () {
        // !!! LỖI ĐÃ ĐƯỢC SỬA Ở ĐÂY !!!
        // Thêm route cho profile cá nhân, đặt trước resource để không bị xung đột
        Route::get('profile', [AdminUserController::class, 'profile'])->name('profile');

        Route::get('trashed', [AdminUserController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminUserController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminUserController::class)
            ->parameters(['' => 'user'])
            ->names([
                'index' => 'index',
                'create' => 'create',
                'store' => 'store',
                'show' => 'show',
                'edit' => 'edit',
                'update' => 'update',
                'destroy' => 'destroy',
            ]);
    });

    // ==== Roles ====
    Route::prefix('roles')->middleware(CheckRole::class . ':admin')->name('roles.')->group(function () {
        Route::get('trashed', [AdminRoleController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminRoleController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminRoleController::class, 'forceDelete'])->name('force-delete');
        Route::post('update-users', [AdminRoleController::class, 'updateUsers'])->name('updateUsers');
        Route::get('list', [AdminRoleController::class, 'list'])->name('list');
        Route::resource('/', AdminRoleController::class)
            ->parameters(['' => 'role'])
            ->names([
                'index' => 'index',
                'create' => 'create',
                'store' => 'store',
                'show' => 'show',
                'edit' => 'edit',
                'update' => 'update',
                'destroy' => 'destroy',
            ]);
    });

    // ==== Permissions ====
    Route::prefix('permissions')->middleware(CheckRole::class . ':admin')->name('permissions.')->group(function () {
        Route::post('update-roles', [AdminPermissionController::class, 'updateRoles'])->name('updateRoles');
        Route::get('list', [AdminPermissionController::class, 'list'])->name('list');
        Route::get('trashed', [AdminPermissionController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminPermissionController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminPermissionController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminPermissionController::class)
            ->parameters(['' => 'permission'])
            ->names([
                'index' => 'index',
                'create' => 'create',
                'store' => 'store',
                'show' => 'show',
                'edit' => 'edit',
                'update' => 'update',
                'destroy' => 'destroy',
            ]);
    });

    // ==== Orders ====
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('trashed', [AdminOrderController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminOrderController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminOrderController::class, 'forceDelete'])->name('forceDelete');
        Route::post('{id}/update-status', [AdminOrderController::class, 'updateOrders'])->name('updateOrders');
        Route::get('returns', [AdminOrderController::class, 'returnsIndex'])->name('returns');
        Route::post('returns/{id}/process', [AdminOrderController::class, 'processReturn'])->name('process-return');
        // !!! LỖI COPY-PASTE ĐÃ ĐƯỢC SỬA Ở ĐÂY !!!
        Route::resource('', AdminOrderController::class) // <-- Sửa từ AdminPermissionController thành OrderController
            ->parameters(['' => 'order']) // <-- Sửa từ 'permission' thành 'order' cho đúng
            ->names([
                'index' => 'index',
                'create' => 'create',
                'store' => 'store',
                'show' => 'show',
                'edit' => 'edit',
                'update' => 'update',
                'destroy' => 'destroy',
            ]);
    });
            // Liên hệ (Contacts)
    Route::prefix('contacts')->name('contacts.')->group(function () {
        // Quản lý liên hệ
        Route::get('/', [AdminContactsController::class, 'index'])->name('index');
        Route::get('{id}', [AdminContactsController::class, 'show'])->name('show');
        Route::delete('{id}', [AdminContactsController::class, 'destroy'])->name('destroy');
        Route::patch('{id}/status', [AdminContactsController::class, 'markAsHandled'])->name('markAsHandled');
    });

    // Tin tức (News)
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('trash', [AdminNewsController::class, 'trash'])->name('trash');
        Route::put('{id}/restore', [AdminNewsController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminNewsController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminNewsController::class)->parameters(['' => 'news'])->names('');
    });

    // Danh mục tin tức
    Route::resource('news-categories', AdminNewsCategoryController::class);

    // Bình luận tin tức
    Route::prefix('news-comments')->name('news-comments.')->group(function () {
        Route::get('/', [AdminNewsCommentController::class, 'index'])->name('index');
        Route::get('/{news_id}', [AdminNewsCommentController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminNewsCommentController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle', [AdminNewsCommentController::class, 'toggleVisibility'])->name('toggle');
        Route::post('/{id}/reply', [AdminNewsCommentController::class, 'storeReply'])->name('reply');
        Route::post('/{id}/like', [AdminNewsCommentController::class, 'like'])->name('like');
    });
    // coupon
    Route::prefix('coupons')->middleware(CheckPermission::class . ':manage_coupons')->name('coupons.')->group(function () {
        Route::resource('/', AdminCouponController::class)->parameters(['' => 'coupon'])->except(['show']);
        Route::put('{id}/restore', [AdminCouponController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminCouponController::class, 'forceDelete'])->name('forceDelete');
    });

    
});

Route::post('admin/news/upload-image', [AdminNewsController::class, 'uploadImage'])->name('admin.news.upload-image');
Route::post('/product-comments/{id}/reply', [ProductCommentAdminController::class, 'reply'])->name('products.comments.reply');
require __DIR__ . '/auth.php';
