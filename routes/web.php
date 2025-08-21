<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Controller Imports
|--------------------------------------------------------------------------
*/

// --- ADMIN Controllers ---
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\Contacts\AdminContactsController;
use App\Http\Controllers\Admin\Coupons\AdminCouponController;
use App\Http\Controllers\Admin\News\AdminNewsCategoryController;
use App\Http\Controllers\Admin\News\AdminNewsCommentController;
use App\Http\Controllers\Admin\News\AdminNewsController;
use App\Http\Controllers\Admin\Orders\AdminOrderController;
use App\Http\Controllers\Admin\Products\AdminAttributeController;
use App\Http\Controllers\Admin\Products\AdminAttributeValueController;
use App\Http\Controllers\Admin\Products\AdminBrandController;
use App\Http\Controllers\Admin\Products\AdminCategoryController;
use App\Http\Controllers\Admin\Products\AdminProductController;
use App\Http\Controllers\Admin\Products\ProductCommentAdminController;
use App\Http\Controllers\Admin\Promotions\AdminPromotionController;
use App\Http\Controllers\Admin\Users\AdminPermissionController;
use App\Http\Controllers\Admin\Users\AdminRoleController;
use App\Http\Controllers\Admin\Users\AdminUserController;

// --- CLIENT Controllers ---
use App\Http\Controllers\Client\Accounts\ClientAccountController;
use App\Http\Controllers\Client\Address\ClientAddressController;
use App\Http\Controllers\Client\Brands\ClientBrandController;
use App\Http\Controllers\Client\Carts\ClientCartController;
use App\Http\Controllers\Client\Categories\ClientCategoryController;
use App\Http\Controllers\Client\ChatbotController;
use App\Http\Controllers\Client\Checkouts\ClientCheckoutController;
use App\Http\Controllers\Client\Contacts\ClientContactController;
use App\Http\Controllers\Client\Coupon\ClientCouponController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\News\ClientNewsController;
use App\Http\Controllers\Client\Orders\ClientOrderController;
use App\Http\Controllers\Client\Products\ClientProductCommentController;
use App\Http\Controllers\Client\Products\ClientProductController;

// --- OTHER Controllers ---
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\WebhookController;

// --- Middleware ---
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| CLIENT ROUTES
|--------------------------------------------------------------------------
*/

// --- Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'client.about')->name('about');
Route::view('/policy', 'client.policy')->name('policy');
Route::post('/chatbot-send-message', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');

// Products
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ClientProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ClientProductController::class, 'show'])->name('show');
    Route::prefix('{productId}/comments')->name('comments.')->middleware('auth')->group(function () {
        Route::post('/', [ClientProductCommentController::class, 'store'])->name('store');
        Route::post('/{commentId}/reply', [ClientProductCommentController::class, 'reply'])->name('reply');
    });
});

// Categories & Brands
Route::get('categories', [ClientCategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{slug}', [ClientCategoryController::class, 'show'])->name('categories.show');
Route::get('brands', [ClientBrandController::class, 'index'])->name('brands.index');
Route::get('brands/{slug}', [ClientBrandController::class, 'show'])->name('brands.show');

// Cart
Route::prefix('carts')->name('carts.')->group(function () {
    Route::get('/', [ClientCartController::class, 'index'])->name('index');
    Route::get('/count', [ClientCartController::class, 'count'])->name('count');
    Route::post('/add', [ClientCartController::class, 'add'])->name('add');
    Route::post('/set-buy-now', [ClientCartController::class, 'setBuyNow'])->name('setBuyNow');
    Route::put('/{id}', [ClientCartController::class, 'update'])->name('update');
    Route::delete('/{id}', [ClientCartController::class, 'remove'])->name('remove');
    Route::delete('/', [ClientCartController::class, 'clear'])->name('clear');
});

// Checkout & Payment
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [ClientCheckoutController::class, 'index'])->name('index');
    Route::post('/apply-coupon', [ClientCheckoutController::class, 'applyCoupon'])->name('apply-coupon');
    Route::post('/process', [ClientCheckoutController::class, 'process'])->name('process');
    Route::get('/success/{orderId}', [ClientCheckoutController::class, 'success'])->name('success');
    Route::get('/fail', [ClientCheckoutController::class, 'fail'])->name('fail');
});
Route::prefix('vnpay')->name('vnpay.')->group(function () {
    Route::get('/payment/{order_id}', [ClientCheckoutController::class, 'vnpay_payment'])->name('payment');
    Route::get('/return', [ClientCheckoutController::class, 'vnpay_return'])->name('vnpay_return');
});
Route::post('/clear-payment-message', function () {
    session()->forget('payment_cancelled_message');
    return response()->json(['success' => true]);
})->name('clear.payment.message');


// --- Authenticated User Routes (Account Management) ---
Route::middleware('auth')->prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/', [ClientAccountController::class, 'index'])->name('index');
    Route::get('/edit', [ClientAccountController::class, 'edit'])->name('edit');
    Route::get('/profile', [ClientAccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [ClientAccountController::class, 'updateProfile'])->name('update-profile');
    Route::get('/change-password', [ClientAccountController::class, 'changePassword'])->name('change-password');
    Route::put('/change-password', [ClientAccountController::class, 'updatePassword'])->name('update-password');

    // Orders
    Route::get('/orders', [ClientAccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [ClientAccountController::class, 'orderDetail'])->name('order-detail');
    Route::post('/orders/{id}/cancel', [ClientAccountController::class, 'cancelOrder'])->name('cancel-order');

    // Addresses
    Route::get('/addresses', [ClientAccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [ClientAccountController::class, 'storeAddress'])->name('store-address');
    Route::get('/addresses/{id}/edit', [ClientAccountController::class, 'editAddress'])->name('edit-address');
    Route::put('/addresses/{id}', [ClientAccountController::class, 'updateAddress'])->name('update-address');
    Route::delete('/addresses/{id}', [ClientAccountController::class, 'deleteAddress'])->name('delete-address');
    Route::patch('/addresses/{id}/set-default', [ClientAccountController::class, 'setDefaultAddress'])->name('addresses.set-default');
});

// Routes with 'client' prefix
Route::prefix('client')->name('client.')->group(function () {
    // Other client order actions (AJAX)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::post('{id}/confirm-received', [ClientOrderController::class, 'confirmReceived'])->name('confirm-received');
        Route::post('{id}/request-return', [ClientOrderController::class, 'requestReturn'])->name('request-return');
        Route::post('{id}/cancel', [ClientOrderController::class, 'cancel'])->name('cancel');
    });

    // Contacts
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [ClientContactController::class, 'index'])->name('index');
        Route::post('/', [ClientContactController::class, 'store'])->name('store');
    });

    // News (ĐÂY LÀ KHỐI ĐÃ ĐƯỢC SỬA LỖI)
    Route::get('/tin-tuc', [ClientNewsController::class, 'index'])->name('news.index');
    Route::get('/tin-tuc/{id}', [ClientNewsController::class, 'show'])->name('news.show');
    Route::middleware('auth')->group(function () {
        Route::post('/tin-tuc/{id}/comment', [ClientNewsController::class, 'storeComment'])->name('news-comments.store');
        Route::post('/tin-tuc/comment/{id}/like', [ClientNewsController::class, 'likeComment'])->name('news-comments.like');
        Route::post('/tin-tuc/comment/{id}/reply', [ClientNewsController::class, 'replyComment'])->name('news-comments.reply');
    });
});


// --- API Routes ---
Route::prefix('api')->group(function () {
    Route::get('/provinces', [ClientAddressController::class, 'getProvinces']);
    Route::get('/districts/{provinceCode}', [ClientAddressController::class, 'getDistricts']);
    Route::get('/wards/{districtCode}', [ClientAddressController::class, 'getWards']);
    Route::post('/apply-coupon', [ClientCouponController::class, 'validateCoupon']);
    Route::get('/coupons', [ClientCouponController::class, 'listAvailableCoupons']);
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // Users Management
    Route::prefix('users')->middleware(CheckRole::class . ':admin')->name('users.')->group(function () {
        Route::get('trashed', [AdminUserController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminUserController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminUserController::class)->parameters(['' => 'user']);
        // Addresses
        Route::get('{user}/addresses', [AdminUserController::class, 'addresses'])->name('addresses.index');
        Route::post('{user}/addresses', [AdminUserController::class, 'addAddress'])->name('addresses.store');
        Route::put('addresses/{address}', [AdminUserController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('addresses/{address}', [AdminUserController::class, 'deleteAddress'])->name('addresses.destroy');
    });

    // Roles & Permissions (ĐÂY LÀ KHỐI ĐÃ ĐƯỢC SỬA LỖI)
    Route::prefix('roles')->middleware(CheckRole::class . ':admin')->name('roles.')->group(function () {
    Route::get('list', [AdminRoleController::class, 'list'])->name('list');
    Route::post('update-users', [AdminRoleController::class, 'updateUsers'])->name('updateUsers');
    Route::get('trashed', [AdminRoleController::class, 'trashed'])->name('trashed');
    Route::post('{id}/restore', [AdminRoleController::class, 'restore'])->name('restore');
    Route::delete('{id}/force-delete', [AdminRoleController::class, 'forceDelete'])->name('force-delete');
    // Route xem chi tiết vai trò
    Route::get('{role}', [AdminRoleController::class, 'show'])->name('show');
    Route::resource('/', AdminRoleController::class)->parameters(['' => 'role']);
    });
    Route::prefix('permissions')->middleware(CheckRole::class . ':admin')->name('permissions.')->group(function () {
        Route::get('list', [AdminPermissionController::class, 'list'])->name('list');
        Route::post('update-roles', [AdminPermissionController::class, 'updateRoles'])->name('updateRoles');
        Route::get('trashed', [AdminPermissionController::class, 'trashed'])->name('trashed');
        Route::post('sync', [AdminPermissionController::class, 'sync'])->name('sync');
        Route::post('{id}/restore', [AdminPermissionController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminPermissionController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminPermissionController::class)->parameters(['' => 'permission']);
    });

    // Products Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('trashed', [AdminProductController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminProductController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminProductController::class, 'forceDelete'])->name('force-delete');
        Route::resource('/', AdminProductController::class)->parameters(['' => 'product'])->names('');

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('trashed', [AdminCategoryController::class, 'trashed'])->name('trashed');
            Route::post('{id}/restore', [AdminCategoryController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [AdminCategoryController::class, 'forceDelete'])->name('force-delete');
            Route::resource('/', AdminCategoryController::class)->parameters(['' => 'category'])->names('');
        });

        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('trashed', [AdminBrandController::class, 'trashed'])->name('trashed');
            Route::post('{id}/restore', [AdminBrandController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [AdminBrandController::class, 'forceDelete'])->name('force-delete');
            Route::resource('/', AdminBrandController::class)->parameters(['' => 'brand'])->names('');
        });

        Route::get('attributes/trashed', [AdminAttributeController::class, 'trashed'])->name('attributes.trashed');
        Route::post('attributes/{id}/restore', [AdminAttributeController::class, 'restore'])->name('attributes.restore');
        Route::delete('attributes/{id}/force-delete', [AdminAttributeController::class, 'forceDelete'])->name('attributes.force-delete');
        Route::resource('attributes', AdminAttributeController::class);

        Route::prefix('attributes/{attribute}')->name('attributes.')->group(function () {
            Route::get('values/trashed', [AdminAttributeValueController::class, 'trashed'])->name('values.trashed');
            Route::post('values/{id}/restore', [AdminAttributeValueController::class, 'restore'])->name('values.restore');
            Route::delete('values/{id}/force-delete', [AdminAttributeValueController::class, 'forceDelete'])->name('values.force-delete');
            Route::resource('values', AdminAttributeValueController::class)->parameters(['values' => 'value']);
        });

        Route::prefix('comments')->name('comments.')->group(function () {
            Route::get('/products-with-comments', [ProductCommentAdminController::class, 'productsWithComments'])->name('products-with-comments');
            Route::patch('/{id}/approve', [ProductCommentAdminController::class, 'approve'])->name('approve');
            Route::patch('/{id}/toggle', [ProductCommentAdminController::class, 'toggleStatus'])->name('toggle');
            Route::resource('/', ProductCommentAdminController::class)->parameters(['' => 'id']);
        });
    });

    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('trashed', [AdminOrderController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminOrderController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminOrderController::class, 'forceDelete'])->name('forceDelete');
        Route::post('{id}/update-status', [AdminOrderController::class, 'updateOrders'])->name('updateOrders');
        Route::get('returns', [AdminOrderController::class, 'returnsIndex'])->name('returns');
        Route::post('returns/{id}/process', [AdminOrderController::class, 'processReturn'])->name('process-return');
        Route::post('{id}/reset-vnpay-counter', [AdminOrderController::class, 'resetVnpayCancelCount'])->name('reset-vnpay-counter');
        Route::resource('', AdminOrderController::class)->parameters(['' => 'order']);
    });

    // News Management
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('trash', [AdminNewsController::class, 'trash'])->name('trash');
        Route::put('{id}/restore', [AdminNewsController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminNewsController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminNewsController::class)->parameters(['' => 'news'])->names('');
    });
    Route::resource('news-categories', AdminNewsCategoryController::class);
    Route::prefix('news-comments')->name('news-comments.')->group(function () {
        Route::get('/', [AdminNewsCommentController::class, 'index'])->name('index');
        Route::get('/{news_id}', [AdminNewsCommentController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminNewsCommentController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle', [AdminNewsCommentController::class, 'toggleVisibility'])->name('toggle');
        Route::post('/{id}/reply', [AdminNewsCommentController::class, 'storeReply'])->name('reply');
    });

    // Other Management
    Route::resource('banner', AdminBannerController::class);
    Route::resource('promotions', AdminPromotionController::class);
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [AdminContactsController::class, 'index'])->name('index');
        Route::get('{id}', [AdminContactsController::class, 'show'])->name('show');
        Route::delete('{id}', [AdminContactsController::class, 'destroy'])->name('destroy');
        Route::patch('{id}/status', [AdminContactsController::class, 'markAsHandled'])->name('markAsHandled');
    });
    Route::prefix('coupons')->middleware(CheckPermission::class . ':manage_coupons')->name('coupons.')->group(function () {
        Route::put('{id}/restore', [AdminCouponController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminCouponController::class, 'forceDelete'])->name('forceDelete');
        Route::resource('/', AdminCouponController::class)->parameters(['' => 'coupon'])->except(['show']);
    });
});


/*
|--------------------------------------------------------------------------
| WEBHOOK, SOCIALITE & OTHER GLOBAL ROUTES
|--------------------------------------------------------------------------
*/
Route::post('/webhooks/payos', [WebhookController::class, 'handlePayment'])->name('webhook.payos');
Route::post('admin/news/upload-image', [AdminNewsController::class, 'uploadImage'])->name('admin.news.upload-image');
Route::post('/product-comments/{id}/reply', [ProductCommentAdminController::class, 'reply'])->name('products.comments.reply');

Route::get('auth/google', [SocialController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);
Route::get('auth/facebook', [SocialController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('auth/facebook/callback', [SocialController::class, 'handleFacebookCallback']);

/*
|--------------------------------------------------------------------------
| DEBUGGING ROUTES (Only available in local environment)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::prefix('debug')->group(function () {
        Route::get('/phpinfo', fn() => phpinfo());
        // All test routes from original files are placed here
    });
}

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES (Laravel Breeze/UI)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
