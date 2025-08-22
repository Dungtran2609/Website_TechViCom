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
use App\Http\Controllers\Admin\Mails\AdminMailController;
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
use App\Http\Controllers\Admin\Logo\AdminLogoController;

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
use App\Http\Controllers\Client\FavoriteController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\News\ClientNewsController;
use App\Http\Controllers\Client\Orders\ClientOrderController;
use App\Http\Controllers\Client\Products\ClientProductCommentController;
use App\Http\Controllers\Client\Products\ClientProductController;
use App\Http\Controllers\Client\InvoiceController;

// --- OTHER Controllers ---
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\WebhookController;

// --- Middleware ---
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;


/*
|--------------------------------------------------------------------------
| CLIENT ROUTES
|--------------------------------------------------------------------------
*/

// Trang chủ và các trang tĩnh
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'client.pages.about')->name('about');
Route::view('/policy', 'client.pages.policy')->name('policy');
Route::view('/store-system', 'client.pages.store_system')->name('client.store_system');
Route::view('/warranty', 'client.pages.warranty')->name('warranty');
Route::view('/authorized-dealer', 'client.pages.authorized_dealer')->name('authorized_dealer');
Route::view('/enterprise-project', 'client.pages.enterprise_project')->name('enterprise_project');
Route::view('/tuyen-dung', 'client.pages.recruitment')->name('recruitment');

// Tra cứu hóa đơn
Route::prefix('invoice')->name('client.invoice.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::post('/send-verification-code', [InvoiceController::class, 'sendVerificationCode'])->name('send-code');
    Route::post('/verify-code', [InvoiceController::class, 'verifyCode'])->name('verify-code');
    Route::get('/order/{id}', [InvoiceController::class, 'showOrder'])->name('show-order');
    Route::get('/download/{id}', [InvoiceController::class, 'downloadInvoice'])->name('download');
});

// Sản phẩm, Danh mục, Thương hiệu (Phía Client)
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ClientProductController::class, 'index'])->name('index');
    Route::get('/love', [ClientProductController::class, 'love'])->name('love');
    Route::get('/{id}', [ClientProductController::class, 'show'])->name('show');
    Route::get('/{productId}/comments/filter', [ClientProductCommentController::class, 'filterComments'])->name('comments.filter');
    Route::prefix('{productId}/comments')->name('comments.')->middleware('auth')->group(function () {
        Route::post('/', [ClientProductCommentController::class, 'store'])->name('store');
        Route::post('/{commentId}/reply', [ClientProductCommentController::class, 'reply'])->name('reply');
    });
});

Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [ClientCategoryController::class, 'index'])->name('index');
    Route::get('/{slug}', [ClientCategoryController::class, 'show'])->name('show');
});

Route::prefix('brands')->name('brands.')->group(function () {
    Route::get('/', [ClientBrandController::class, 'index'])->name('index');
    Route::get('/{slug}', [ClientBrandController::class, 'show'])->name('show');
});

// Giỏ hàng, Thanh toán
Route::prefix('carts')->name('carts.')->group(function () {
    Route::get('/', [ClientCartController::class, 'index'])->name('index');
    Route::get('/count', [ClientCartController::class, 'count'])->name('count');
    Route::post('/add', [ClientCartController::class, 'add'])->name('add');
    Route::post('/set-buy-now', [ClientCartController::class, 'setBuyNow'])->name('setBuyNow');
    Route::put('/{id}', [ClientCartController::class, 'update'])->name('update');
    Route::delete('/{id}', [ClientCartController::class, 'remove'])->name('remove');
    Route::delete('/', [ClientCartController::class, 'clear'])->name('clear');
});

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

// Tin tức, Liên hệ (Phía Client)
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/tin-tuc', [ClientNewsController::class, 'index'])->name('news.index');
    Route::get('/tin-tuc/{id}', [ClientNewsController::class, 'show'])->name('news.show');
    Route::middleware('auth')->group(function () {
        Route::post('/tin-tuc/{id}/comment', [ClientNewsController::class, 'storeComment'])->name('news-comments.store');
        Route::post('/tin-tuc/comment/{id}/like', [ClientNewsController::class, 'likeComment'])->name('news-comments.like');
        Route::post('/tin-tuc/comment/{id}/reply', [ClientNewsController::class, 'replyComment'])->name('news-comments.reply');
    });
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [ClientContactController::class, 'create'])->name('index');
        Route::post('/', [ClientContactController::class, 'store'])->name('store');
    });
});

// Chatbot
Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/', [ClientAccountController::class, 'index'])->name('index');
    Route::get('/profile', [ClientAccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [ClientAccountController::class, 'updateProfile'])->name('update-profile');
    Route::get('/change-password', [ClientAccountController::class, 'changePassword'])->name('change-password');
    Route::put('/change-password', [ClientAccountController::class, 'updatePassword'])->name('update-password');
    Route::get('/orders', [ClientAccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [ClientAccountController::class, 'orderDetail'])->name('order-detail');
    Route::post('/orders/{id}/cancel', [ClientAccountController::class, 'cancelOrder'])->name('cancel-order');
    Route::get('/addresses', [ClientAccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [ClientAccountController::class, 'storeAddress'])->name('store-address');
    Route::get('/addresses/{id}/edit', [ClientAccountController::class, 'editAddress'])->name('edit-address');
    Route::put('/addresses/{id}', [ClientAccountController::class, 'updateAddress'])->name('update-address');
    Route::delete('/addresses/{id}', [ClientAccountController::class, 'deleteAddress'])->name('delete-address');
    Route::patch('/addresses/{id}/set-default', [ClientAccountController::class, 'setDefaultAddress'])->name('addresses.set-default');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favorites/remove', [FavoriteController::class, 'remove'])->name('favorites.remove');
    Route::post('/favorites/check', [FavoriteController::class, 'check'])->name('favorites.check');
});

/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->group(function () {
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

    // Users, Roles, Permissions
    Route::prefix('users')->middleware(CheckRole::class . ':admin')->name('users.')->group(function () {
        Route::get('trashed', [AdminUserController::class, 'trashed'])->name('trashed');
        Route::post('{user}/restore', [AdminUserController::class, 'restore'])->name('restore');
        Route::delete('{user}/force-delete', [AdminUserController::class, 'forceDelete'])->name('force-delete');
        Route::get('{user}/addresses', [AdminUserController::class, 'addresses'])->name('addresses.index');
        Route::post('{user}/addresses', [AdminUserController::class, 'addAddress'])->name('addresses.store');
        Route::put('addresses/{address}', [AdminUserController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('addresses/{address}', [AdminUserController::class, 'destroyAddress'])->name('addresses.destroy');
        Route::resource('', AdminUserController::class)->parameters(['' => 'user']);
    });
    Route::prefix('roles')->middleware(CheckRole::class . ':admin')->name('roles.')->group(function () {
        Route::get('list', [AdminRoleController::class, 'list'])->name('list');
        Route::post('update-users', [AdminRoleController::class, 'updateUsers'])->name('updateUsers');
        Route::get('trashed', [AdminRoleController::class, 'trashed'])->name('trashed');
        Route::post('{role}/restore', [AdminRoleController::class, 'restore'])->name('restore');
        Route::delete('{role}/force-delete', [AdminRoleController::class, 'forceDelete'])->name('force-delete');
        Route::resource('/', AdminRoleController::class)->parameters(['' => 'role']);
    });
    Route::prefix('permissions')->middleware(CheckRole::class . ':admin')->name('permissions.')->group(function () {
        Route::get('list', [AdminPermissionController::class, 'list'])->name('list');
        Route::post('update-roles', [AdminPermissionController::class, 'updateRoles'])->name('updateRoles');
        Route::get('trashed', [AdminPermissionController::class, 'trashed'])->name('trashed');
        Route::post('sync', [AdminPermissionController::class, 'sync'])->name('sync');
        Route::post('{permission}/restore', [AdminPermissionController::class, 'restore'])->name('restore');
        Route::delete('{permission}/force-delete', [AdminPermissionController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminPermissionController::class)->parameters(['' => 'permission']);
    });

    // Products Management - ĐÃ SẮP XẾP LẠI ĐỂ FIX LỖI 404
    Route::prefix('products')->name('products.')->middleware(CheckPermission::class . ':manage_products')->group(function () {
        // === ĐỊNH NGHĨA CÁC ROUTE CON (CATEGORIES, BRANDS, ATTRIBUTES...) LÊN TRƯỚC ===
        Route::prefix('categories')->name('categories.')->middleware(CheckPermission::class . ':manage_categories')->group(function () {
            Route::get('trashed', [AdminCategoryController::class, 'trashed'])->name('trashed');
            Route::post('{category}/restore', [AdminCategoryController::class, 'restore'])->name('restore');
            Route::delete('{category}/force-delete', [AdminCategoryController::class, 'forceDelete'])->name('force-delete');
            Route::resource('/', AdminCategoryController::class)->parameters(['' => 'category']);
        });
        Route::prefix('brands')->name('brands.')->middleware(CheckPermission::class . ':manage_brands')->group(function () {
            Route::get('trashed', [AdminBrandController::class, 'trashed'])->name('trashed');
            Route::post('{brand}/restore', [AdminBrandController::class, 'restore'])->name('restore');
            Route::delete('{brand}/force-delete', [AdminBrandController::class, 'forceDelete'])->name('force-delete');
            Route::resource('/', AdminBrandController::class)->parameters(['' => 'brand']);
        });
        Route::prefix('attributes')->name('attributes.')->middleware(CheckPermission::class . ':manage_attributes')->group(function () {
            Route::get('trashed', [AdminAttributeController::class, 'trashed'])->name('trashed');
            Route::post('{attribute}/restore', [AdminAttributeController::class, 'restore'])->name('restore');
            Route::delete('{attribute}/force-delete', [AdminAttributeController::class, 'forceDelete'])->name('force-delete');
            Route::prefix('{attribute}/values')->name('values.')->group(function() {
                 Route::get('trashed', [AdminAttributeValueController::class, 'trashed'])->name('trashed');
                 Route::post('{value}/restore', [AdminAttributeValueController::class, 'restore'])->name('restore');
                 Route::delete('{value}/force-delete', [AdminAttributeValueController::class, 'forceDelete'])->name('force-delete');
                 Route::resource('/', AdminAttributeValueController::class)->parameters(['' => 'value']);
            });
            Route::resource('/', AdminAttributeController::class)->parameters(['' => 'attribute']);
        });
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::get('/products-with-comments', [ProductCommentAdminController::class, 'productsWithComments'])->name('products-with-comments');
            Route::patch('/{id}/toggle-hidden', [ProductCommentAdminController::class, 'toggleHidden'])->name('toggle-hidden');
            Route::patch('/{id}/toggle', [ProductCommentAdminController::class, 'toggleStatus'])->name('toggle');
            Route::post('/{id}/reply', [ProductCommentAdminController::class, 'reply'])->name('reply');
            Route::resource('/', ProductCommentAdminController::class)->parameters(['' => 'id']);
        });
        // === ĐẶT ROUTE CỦA PRODUCT (CHA) XUỐNG CUỐI CÙNG ===
        Route::get('trashed', [AdminProductController::class, 'trashed'])->name('trashed');
        Route::post('{product}/restore', [AdminProductController::class, 'restore'])->name('restore');
        Route::delete('{product}/force-delete', [AdminProductController::class, 'forceDelete'])->name('force-delete');
        Route::resource('/', AdminProductController::class)->parameters(['' => 'product']);
    });

    // Orders Management
    Route::prefix('orders')->middleware(CheckPermission::class . ':manage_orders')->name('orders.')->group(function () {
        Route::get('trashed', [AdminOrderController::class, 'trashed'])->name('trashed');
        Route::post('{order}/restore', [AdminOrderController::class, 'restore'])->name('restore');
        Route::delete('{order}/force-delete', [AdminOrderController::class, 'forceDelete'])->name('forceDelete');
        Route::post('{order}/update-status', [AdminOrderController::class, 'updateOrders'])->name('updateOrders');
        Route::get('returns', [AdminOrderController::class, 'returnsIndex'])->name('returns');
        Route::post('returns/{id}/process', [AdminOrderController::class, 'processReturn'])->name('process-return');
        Route::post('{order}/reset-vnpay-counter', [AdminOrderController::class, 'resetVnpayCancelCount'])->name('reset-vnpay-counter');
        Route::resource('', AdminOrderController::class)->parameters(['' => 'order']);
    });
    // News Management
    Route::prefix('news')->name('news.')->middleware(CheckPermission::class . ':manage_news')->group(function () {
        Route::get('trash', [AdminNewsController::class, 'trash'])->name('trash');
        Route::put('{news}/restore', [AdminNewsController::class, 'restore'])->name('restore');
        Route::delete('{news}/force-delete', [AdminNewsController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminNewsController::class)->parameters(['' => 'news']);
    });
    Route::resource('news-categories', AdminNewsCategoryController::class)->middleware(CheckPermission::class . ':manage_news-categories');
    Route::prefix('news-comments')->name('news-comments.')->middleware(CheckPermission::class . ':manage_news_comments')->group(function () {
        Route::get('/', [AdminNewsCommentController::class, 'index'])->name('index');
        Route::get('/{news_id}', [AdminNewsCommentController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminNewsCommentController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle', [AdminNewsCommentController::class, 'toggleVisibility'])->name('toggle');
        Route::post('/{id}/reply', [AdminNewsCommentController::class, 'storeReply'])->name('reply');
    });

    // Other Management
    Route::resource('banner', AdminBannerController::class)->middleware(CheckPermission::class . ':manage_banner');
    Route::resource('promotions', AdminPromotionController::class)->middleware(CheckPermission::class . ':manage_promotions');
    Route::resource('logos', AdminLogoController::class)->names('logos');
    Route::prefix('contacts')->name('contacts.')->middleware(CheckPermission::class . ':manage_contacts')->group(function () {
        Route::get('/', [AdminContactsController::class, 'index'])->name('index');
        Route::get('{id}', [AdminContactsController::class, 'show'])->name('show');
        Route::delete('{id}', [AdminContactsController::class, 'destroy'])->name('destroy');
        Route::patch('{id}/status', [AdminContactsController::class, 'markAsHandled'])->name('markAsHandled');
    });
    Route::prefix('coupons')->name('coupons.')->middleware(CheckPermission::class . ':manage_coupons')->group(function () {
        Route::put('{coupon}/restore', [AdminCouponController::class, 'restore'])->name('restore');
        Route::delete('{coupon}/force-delete', [AdminCouponController::class, 'forceDelete'])->name('forceDelete');
        Route::resource('/', AdminCouponController::class)->parameters(['' => 'coupon'])->except(['show']);
    });
    Route::prefix('mails')->name('mails.')->middleware(CheckPermission::class . ':manage_mails')->group(function () {
        Route::get('/send', [AdminMailController::class, 'sendForm'])->name('send');
        Route::post('/send', [AdminMailController::class, 'send'])->name('send.submit');
        Route::get('/trash', [AdminMailController::class, 'trash'])->name('trash');
        Route::post('/{mail}/restore', [AdminMailController::class, 'restore'])->name('restore');
        Route::delete('/{mail}/force-delete', [AdminMailController::class, 'forceDelete'])->name('forceDelete');
        Route::post('/{mail}/toggle-auto-send', [AdminMailController::class, 'toggleAutoSend'])->name('toggleAutoSend');
        Route::post('/{mail}/send-test', [AdminMailController::class, 'sendTest'])->name('sendTest');
        Route::resource('/', AdminMailController::class)->parameters(['' => 'mail']);
    });
});

/*
|--------------------------------------------------------------------------
| GLOBAL & WEBHOOK ROUTES
|--------------------------------------------------------------------------
*/
// Social Login
Route::get('auth/google', [SocialController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);
Route::get('auth/facebook', [SocialController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('auth/facebook/callback', [SocialController::class, 'handleFacebookCallback']);

// Webhooks
Route::post('/webhooks/payos', [WebhookController::class, 'handlePayment'])->name('webhook.payos');

// Other global routes
Route::post('admin/news/upload-image', [AdminNewsController::class, 'uploadImage'])->name('admin.news.upload-image')->middleware(['auth', 'is_admin']);

/*
|--------------------------------------------------------------------------
| DEBUGGING ROUTES
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::prefix('debug')->group(function () {
        Route::get('/phpinfo', fn() => phpinfo());
        Route::get('/test-add-to-cart', function () {
            $cart = session()->get('cart', []);
            $cart['test_product'] = ['product_id' => 1, 'quantity' => 1, 'variant_id' => null];
            session(['cart' => $cart]);
            return 'Product added. <a href="/carts">Go to cart</a>';
        });
        Route::get('/test-checkout-flow', function () {
            session()->forget('cart');
            session(['cart' => ['1_1' => ['product_id' => 1, 'variant_id' => 1, 'quantity' => 1, 'price' => 100000]]]);
            return 'Test cart created. <a href="/checkout">Go to checkout</a>';
        });
        Route::get('/test-check-cart', function () {
            return response()->json(session()->get('cart', []));
        });
        Route::get('/test-session', function () {
            return response()->json([
                'session_id' => session()->getId(),
                'cart' => session()->get('cart', []),
                'session_driver' => config('session.driver'),
            ]);
        });
    });
}

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
