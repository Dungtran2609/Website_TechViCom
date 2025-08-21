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

// =========================================================================
// === CLIENT ROUTES ===
// =========================================================================


// Trang chủ client
// Test route to add product to cart
Route::get('/test-add-to-cart', function () {
    $cart = session()->get('cart', []);
    $cart[] = [
        'product_id' => 1,
        'quantity' => 1,
        'variant_id' => null
    ];
    session(['cart' => $cart]);

    return 'Product added to cart. Cart size: ' . count($cart) . ' - <a href="/checkout">Go to checkout</a>';
});

Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');
// Test route cho checkout flow
Route::get('/test-checkout-flow', function () {
    // Clear session và tạo test cart
    session()->forget('cart');

    $cart = [
        'items' => [
            '1' => [
                'product_id' => 1,
                'variant_id' => 1,
                'quantity' => 1,
                'price' => 100000
            ]
        ],
        'total' => 100000
    ];
    session(['cart' => $cart]);

    return 'Test cart created. <a href="/checkout">Go to checkout</a>';
});

// Test route để kiểm tra có session cart không
Route::get('/test-check-cart', function () {
    $cart = session()->get('cart', []);
    return 'Session cart: <pre>' . json_encode($cart, JSON_PRETTY_PRINT) . '</pre> - <a href="/checkout">Go to checkout</a>';
});






// Routes chính
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route cho client.home để tránh lỗi route không tồn tại
Route::get('/client', [HomeController::class, 'index'])->name('client.home');
Route::view('/about', 'client.about')->name('about');
Route::view('/policy', 'client.policy')->name('policy');



// Test cart functionality
Route::get('/test-cart-page', function () {
    return view('test-cart');
})->name('test-cart-page');

// Test route for debugging cart
Route::get('/test-cart', function () {
    $cart = session()->get('cart', []);
    return response()->json([
        'session_cart' => $cart,
        'session_id' => session()->getId(),
        'count' => array_sum(array_column($cart, 'quantity'))
    ]);
});

// Test add to cart (no CSRF for testing)
Route::post('/test-add-cart', function (Request $request) {
    error_log('TEST: Test add cart request: ' . json_encode($request->all()));
    Log::info('Test add cart request: ', $request->all());
    $cart = session()->get('cart', []);
    error_log('TEST: Current cart: ' . json_encode($cart));
    $cart['test_item'] = [
        'product_id' => 1,
        'quantity' => 1,
        'variant_id' => null
    ];
    session()->put('cart', $cart);
    session()->save();
    error_log('TEST: Cart after save: ' . json_encode(session()->get('cart', [])));
    Log::info('Test add cart session after: ', session()->get('cart', []));

    return response()->json([
        'success' => true,
        'session_cart' => session()->get('cart', [])
    ]);
})->withoutMiddleware(['csrf']);

// Debug cart API
Route::get('/debug-cart-api', function () {
    $controller = new \App\Http\Controllers\Client\Carts\ClientCartController();
    $request = new \Illuminate\Http\Request();
    $request->headers->set('Accept', 'application/json');

    $response = $controller->index($request);
    return $response;
});

// Debug cart data
Route::get('/debug-cart-data', function () {
    if (Auth::check()) {
        $cartItems = \App\Models\Cart::with(['product.productAllImages', 'productVariant.attributeValues.attribute'])
            ->where('user_id', Auth::id())
            ->get();
    } else {
        $sessionCart = session()->get('cart', []);
        $cartItems = [];

        foreach ($sessionCart as $key => $item) {
            $product = \App\Models\Product::with(['productAllImages', 'variants.attributeValues.attribute'])
                ->find($item['product_id']);

            if ($product) {
                $cartItem = (object) [
                    'id' => $key,
                    'product' => $product,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'productVariant' => $item['variant_id'] ? \App\Models\ProductVariant::with('attributeValues.attribute')->find($item['variant_id']) : null
                ];
                $cartItems[] = $cartItem;
            }
        }
    }

    return response()->json([
        'type' => Auth::check() ? 'database' : 'session',
        'user_id' => Auth::check() ? Auth::id() : null,
        'session_id' => session()->getId(),
        'cart_items' => $cartItems,
        'session_cart' => session()->get('cart', [])
    ]);
});

// Route resource cho promotions và mails (trong group admin)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('promotions', App\Http\Controllers\Admin\Promotions\AdminPromotionController::class)->names('promotions');
    // Quản lý mail động
    Route::get('mails/send', [App\Http\Controllers\Admin\Mails\AdminMailController::class, 'sendForm'])->name('mails.send');
    Route::post('mails/send', [App\Http\Controllers\Admin\Mails\AdminMailController::class, 'send'])->name('mails.send');
    Route::get('mails/trash', [App\Http\Controllers\Admin\Mails\AdminMailController::class, 'trash'])->name('mails.trash');
    Route::post('mails/{mail}/restore', [App\Http\Controllers\Admin\Mails\AdminMailController::class, 'restore'])->name('mails.restore');
    Route::delete('mails/{mail}/force-delete', [App\Http\Controllers\Admin\Mails\AdminMailController::class, 'forceDelete'])->name('mails.forceDelete');
    Route::post('mails/{mail}/toggle-auto-send', [App\Http\Controllers\Admin\Mails\AdminMailController::class, 'toggleAutoSend'])->name('mails.toggleAutoSend');
    Route::post('mails/{mail}/send-test', [App\Http\Controllers\Admin\Mails\AdminMailController::class, 'sendTest'])->name('mails.sendTest');
    Route::resource('mails', App\Http\Controllers\Admin\Mails\AdminMailController::class)->names('mails');
});

// Test cart operations with debug
Route::post('/debug-cart-update', function () {
    $id = request('id');
    $quantity = request('quantity');

    $sessionId = session()->getId();
    $cart = session()->get('cart', []);

    $response = [
        'request_data' => [
            'id' => $id,
            'quantity' => $quantity
        ],
        'session_info' => [
            'session_id' => $sessionId,
            'session_started' => session()->isStarted(),
            'cart_before' => $cart,
            'available_keys' => array_keys($cart),
            'key_exists' => isset($cart[$id])
        ]
    ];

    if (isset($cart[$id])) {
        $cart[$id]['quantity'] = $quantity;
        session()->put('cart', $cart);
        session()->save();

        $response['update_result'] = [
            'success' => true,
            'cart_after' => session()->get('cart', [])
        ];
    } else {
        $response['update_result'] = [
            'success' => false,
            'message' => 'Key not found'
        ];
    }

    return response()->json($response);
})->withoutMiddleware(['csrf']);

// Test session directly
Route::get('/test-session', function () {
    // Start session if not started
    if (!session()->isStarted()) {
        session()->start();
    }

    $sessionId = session()->getId();
    $cart = session()->get('cart', []);

    // Add or update cart
    if (request('action') === 'add') {
        $key = '1_default';
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += 1;
        } else {
            $cart[$key] = [
                'product_id' => 1,
                'variant_id' => null,
                'quantity' => 1
            ];
        }
        session()->put('cart', $cart);
        session()->save();
        $cart = session()->get('cart', []); // Refresh
    }

    return response()->json([
        'session_started' => session()->isStarted(),
        'session_id' => $sessionId,
        'cart_before' => request('action') ? 'modified' : $cart,
        'cart_after' => $cart,
        'session_driver' => config('session.driver'),
        'urls' => [
            'add' => url('/test-session?action=add'),
            'view' => url('/test-session')
        ]
    ]);
});

// Products
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ClientProductController::class, 'index'])->name('index');
    Route::get('/love', [ClientProductController::class, 'love'])->name('love');
    Route::get('/{id}', [ClientProductController::class, 'show'])->name('show');
    Route::prefix('{productId}/comments')->name('comments.')->middleware('auth')->group(function () {
        Route::post('/', [ClientProductCommentController::class, 'store'])->name('store');
        Route::post('/{commentId}/reply', [ClientProductCommentController::class, 'reply'])->name('reply');
    });
    
    // Product comments filter (public access)
    Route::get('/{productId}/comments/filter', [ClientProductCommentController::class, 'filterComments'])->name('comments.filter');
});

// Categories routes (public access)
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [ClientCategoryController::class, 'index'])->name('index');
    Route::get('/{slug}', [ClientCategoryController::class, 'show'])->name('show');
});

// Brands routes (public access)
Route::prefix('brands')->name('brands.')->group(function () {
    Route::get('/', [ClientBrandController::class, 'index'])->name('index');
    Route::get('/{slug}', [ClientBrandController::class, 'show'])->name('show');
});

// Checkout routes (public access - không cần prefix client)
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [App\Http\Controllers\Client\Checkouts\ClientCheckoutController::class, 'index'])->name('index');
    Route::post('/apply-coupon', [App\Http\Controllers\Client\Checkouts\ClientCheckoutController::class, 'applyCoupon'])->name('apply-coupon');
    Route::post('/process', [App\Http\Controllers\Client\Checkouts\ClientCheckoutController::class, 'process'])->name('process');
    Route::get('/success/{orderId}', [App\Http\Controllers\Client\Checkouts\ClientCheckoutController::class, 'success'])->name('success');
    Route::get('/fail', [App\Http\Controllers\Client\Checkouts\ClientCheckoutController::class, 'fail'])->name('fail');
    
});

// Route riêng cho success để test
Route::get('/checkout-success/{orderId}', [App\Http\Controllers\Client\Checkouts\ClientCheckoutController::class, 'success'])->name('success.test');
// VNPAY Payment routes
Route::prefix('vnpay')->name('vnpay.')->group(function () {
    Route::get('/payment/{order_id}', [App\Http\Controllers\Client\Checkouts\ClientCheckoutController::class, 'vnpay_payment'])->name('payment');
    Route::get('/return', [App\Http\Controllers\Client\Checkouts\ClientCheckoutController::class, 'vnpay_return'])->name('vnpay_return');
});

// Clear payment message route
Route::post('/clear-payment-message', function () {
    session()->forget('payment_cancelled_message');
    return response()->json(['success' => true]);
})->name('clear.payment.message');
// Carts routes (public access - không cần prefix client)
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
    // Route::post('/buy-now', [ClientCheckoutController::class, 'buyNow'])->name('checkout.buyNow');
});

// ACCOUNTS ROUTES (Không có prefix /client)
Route::middleware(['auth'])->prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/', [ClientAccountController::class, 'index'])->name('index');
    Route::get('/edit', [ClientAccountController::class, 'edit'])->name('edit');
    Route::get('/orders', [ClientAccountController::class, 'orders'])->name('orders'); // <-- Đường dẫn này
    Route::get('/orders/{id}', [ClientAccountController::class, 'orderDetail'])->name('order-detail');
    Route::post('/orders/{id}/cancel', [ClientAccountController::class, 'cancelOrder'])->name('cancel-order');
    Route::get('/profile', [ClientAccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [ClientAccountController::class, 'updateProfile'])->name('update-profile');
    Route::get('/change-password', [ClientAccountController::class, 'changePassword'])->name('change-password');
    Route::put('/change-password', [ClientAccountController::class, 'updatePassword'])->name('update-password');
    Route::get('/addresses', [ClientAccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [ClientAccountController::class, 'storeAddress'])->name('store-address');
    Route::get('/addresses/{id}/edit', [ClientAccountController::class, 'editAddress'])->name('edit-address');
    Route::put('/addresses/{id}', [ClientAccountController::class, 'updateAddress'])->name('update-address');
    Route::delete('/addresses/{id}', [ClientAccountController::class, 'deleteAddress'])->name('delete-address');
    Route::patch('/addresses/{id}/set-default', [ClientAccountController::class, 'setDefaultAddress'])->name('addresses.set-default');
    
    // Favorite Products Routes
    Route::middleware('auth')->group(function () {
        Route::get('/favorites', [App\Http\Controllers\Client\FavoriteController::class, 'index'])->name('favorites');
        Route::post('/favorites/toggle', [App\Http\Controllers\Client\FavoriteController::class, 'toggle'])->name('favorites.toggle');
        Route::delete('/favorites/remove', [App\Http\Controllers\Client\FavoriteController::class, 'remove'])->name('favorites.remove');
        Route::post('/favorites/check', [App\Http\Controllers\Client\FavoriteController::class, 'check'])->name('favorites.check');
    });
});

// Client Orders Routes
Route::prefix('client/orders')->name('client.orders.')->group(function () {
    Route::get('/', [App\Http\Controllers\Client\Orders\ClientOrderController::class, 'index'])->name('index');
    Route::get('/{id}', [App\Http\Controllers\Client\Orders\ClientOrderController::class, 'show'])->name('show');
    Route::post('/{id}/cancel', [App\Http\Controllers\Client\Orders\ClientOrderController::class, 'cancel'])->name('cancel');
    Route::post('/{id}/confirm-payment', [App\Http\Controllers\Client\Orders\ClientOrderController::class, 'confirmPayment'])->name('confirm-payment');
    Route::post('/{id}/request-return', [App\Http\Controllers\Client\Orders\ClientOrderController::class, 'requestReturn'])->name('request-return');
    Route::post('/{id}/confirm-received', [App\Http\Controllers\Client\Orders\ClientOrderController::class, 'confirmReceived'])->name('confirm-received');
});


// =========================================================================
// === ADMIN ROUTES ===
// =========================================================================
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

        // Resource chính cho user
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

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('trashed', [AdminOrderController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminOrderController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminOrderController::class, 'forceDelete'])->name('forceDelete');
        Route::post('{id}/update-status', [AdminOrderController::class, 'updateOrders'])->name('updateOrders');
        Route::get('returns', [AdminOrderController::class, 'returnsIndex'])->name('returns');
        Route::post('returns/{id}/process', [AdminOrderController::class, 'processReturn'])->name('process-return');
        Route::get('{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::get('{id}/edit', [AdminOrderController::class, 'edit'])->name('edit');
        Route::put('{id}', [AdminOrderController::class, 'updateOrders'])->name('update');
        Route::delete('{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
        Route::post('{id}/reset-vnpay-counter', [AdminOrderController::class, 'resetVnpayCancelCount'])->name('reset-vnpay-counter');
    });

    // ... (Thêm lại các khối route admin khác của bạn vào đây)
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
        Route::patch('/{id}/toggle-hidden', [ProductCommentAdminController::class, 'toggleHidden'])->name('toggle-hidden');
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
    // ==== Roles ====
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
    Route::prefix('products')->middleware(CheckPermission::class . ':manage_products')->name('products.')->group(function () {
        Route::get('trashed', [AdminProductController::class, 'trashed'])->name('trashed');
        Route::post('{id}/restore', [AdminProductController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminProductController::class, 'forceDelete'])->name('force-delete');
        Route::resource('/', AdminProductController::class)->parameters(['' => 'product'])->names('');


        // Quản lý danh mục
        Route::prefix('categories')->middleware(CheckPermission::class . ':manage_categories')->name('categories.')->group(function () {
            Route::get('trashed', [AdminCategoryController::class, 'trashed'])->name('trashed');
            Route::post('{id}/restore', [AdminCategoryController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [AdminCategoryController::class, 'forceDelete'])->name('force-delete');
            Route::resource('manage', AdminCategoryController::class)->parameters(['manage' => 'category'])->names('');
        });

        // Quản lý thương hiệu
        Route::prefix('brands')->middleware(CheckPermission::class . ':manage_brands')->name('brands.')->group(function () {
            Route::get('trashed', [AdminBrandController::class, 'trashed'])->name('trashed');
            Route::post('{id}/restore', [AdminBrandController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [AdminBrandController::class, 'forceDelete'])->name('force-delete');
            Route::resource('manage', AdminBrandController::class)->parameters(['manage' => 'brand'])->names('');
        });

        // Quản lý thuộc tính
        Route::prefix('attributes')->middleware(CheckPermission::class . ':manage_attributes')->name('attributes.')->group(function () {
            Route::get('trashed', [AdminAttributeController::class, 'trashed'])->name('trashed');
            Route::post('{id}/restore', [AdminAttributeController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [AdminAttributeController::class, 'forceDelete'])->name('force-delete');
            Route::resource('manage', AdminAttributeController::class)->parameters(['manage' => 'attribute'])->names('');
        });

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
    Route::prefix('orders')->middleware(CheckPermission::class . ':manage_orders')->name('orders.')->group(function () {
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
    Route::prefix('news')->middleware(CheckPermission::class . ':manage_news')->name('news.')->group(function () {
        Route::get('trash', [AdminNewsController::class, 'trash'])->name('trash');
        Route::put('{id}/restore', [AdminNewsController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [AdminNewsController::class, 'forceDelete'])->name('force-delete');
        Route::resource('', AdminNewsController::class)->parameters(['' => 'news'])->names('');
    });
    Route::resource('news-categories', AdminNewsCategoryController::class)->middleware(CheckPermission::class . ':manage_news_categories');
    Route::prefix('news-comments')->middleware(CheckPermission::class . ':manage_news_comments')->name('news-comments.')->group(function () {
        Route::get('/', [AdminNewsCommentController::class, 'index'])->name('index');
        Route::get('/{news_id}', [AdminNewsCommentController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminNewsCommentController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle', [AdminNewsCommentController::class, 'toggleVisibility'])->name('toggle');
        Route::post('/{id}/reply', [AdminNewsCommentController::class, 'storeReply'])->name('reply');
    });

    // Other Management
    Route::resource('banner', AdminBannerController::class)->middleware(CheckPermission::class . ':manage_banner');
    Route::resource('promotions', AdminPromotionController::class)->middleware(CheckPermission::class . ':manage_promotions');
    Route::prefix('contacts')->middleware(CheckPermission::class . ':manage_contacts')->name('contacts.')->group(function () {
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

// Gợi ý fix lỗi: View [client.accounts.orders] not found

// Route quản lý logo admin phải nằm trong group admin




// =========================================================================
// === ADMIN ROUTES ===
// =========================================================================
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // ...existing admin routes...

    // Quản lý logo admin
    Route::resource('logos', \App\Http\Controllers\Admin\Logo\AdminLogoController::class)->names('logos');
});

// 1. Tạo file: resources/views/client/accounts/orders.blade.php
// 2. Đảm bảo controller trả về đúng view: return view('client.accounts.orders', ...);
// 3. Nếu muốn đổi tên view, sửa lại trong controller cho khớp.




// Gợi ý fix lỗi: View [client.accounts.orders] not found
// 1. Tạo file: resources/views/client/accounts/orders.blade.php
// 2. Đảm bảo controller trả về đúng view: return view('client.accounts.orders', ...);
// 3. Nếu muốn đổi tên view, sửa lại trong controller cho khớp.
