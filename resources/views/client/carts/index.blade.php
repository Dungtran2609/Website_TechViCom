<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thanh toán - Techvicom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-primary': '#ff6c2f',
                        'custom-primary-dark': '#e55a28',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/component-loader.js"></script>
    <style>
        .checkout-step {
            transition: all 0.3s ease;
        }
        .checkout-step.active {
            background-color: #ea580c;
            color: white;
        }
        .checkout-step.completed {
            background-color: #16a34a;
            color: white;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .payment-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .payment-option:hover {
            border-color: #ea580c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .payment-option.selected {
            border-color: #ea580c;
            background-color: #fff7ed;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.2);
        }
        
        /* Keep sidebar visible on success step */
        #checkout-success {
            display: block;
        }
        
        /* Print styles for order summary */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .bg-gray-50 {
                background: white !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Debug Error Messages -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-4 mt-4" role="alert">
            <strong class="font-bold">Lỗi!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mx-4 mt-4" role="alert">
            <strong class="font-bold">Thành công!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-4 mt-4" role="alert">
            <strong class="font-bold">Lỗi validation:</strong>
            <ul class="mt-2">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Shared Header -->
    <div id="shared-header-container" class="no-print"></div>
    
    <!-- Checkout Steps -->
    <div class="bg-white border-b no-print">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-center">
                <div class="hidden md:flex items-center space-x-4">
                    <div id="step-1" class="checkout-step active flex items-center px-4 py-2 rounded-full">
                        <span class="w-6 h-6 bg-white text-orange-600 rounded-full flex items-center justify-center text-sm font-bold mr-2">1</span>
                        <span>Thông tin</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div id="step-2" class="checkout-step flex items-center px-4 py-2 rounded-full bg-gray-200 text-gray-600">
                        <span class="w-6 h-6 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-bold mr-2">2</span>
                        <span>Thanh toán</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div id="step-3" class="checkout-step flex items-center px-4 py-2 rounded-full bg-gray-200 text-gray-600">
                        <span class="w-6 h-6 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-bold mr-2">3</span>
                        <span>Hoàn tất</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container mx-auto px-4 py-8">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-1 order-2 lg:order-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h3 id="order-summary-title" class="text-xl font-semibold mb-4">Đơn hàng của bạn</h3>
                    
                    <!-- Order Items -->
                    <div id="checkout-items" class="space-y-4 mb-6">
                        @if(count($cartItems) > 0)
                            @foreach($cartItems as $item)
                                @php
                                    $displayPrice = 0;
                                    // Get sale price if available, otherwise price
                                    if (isset($item->productVariant) && $item->productVariant) {
                                        $displayPrice = $item->productVariant->sale_price ?? $item->productVariant->price;
                                    } elseif (isset($item->price)) {
                                        $displayPrice = $item->price; // For session cart items
                                    } elseif ($item->product->variants && $item->product->variants->count() > 0) {
                                        $variant = $item->product->variants->first();
                                        $displayPrice = $variant->sale_price ?? $variant->price ?? 0;
                                    }
                                @endphp

                                <div class="flex items-center justify-between py-3 border-b border-gray-100 checkout-item" data-item-id="{{ $item->id }}" data-unit-price="{{ $displayPrice }}" data-quantity="{{ $item->quantity }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                            @if($item->product->productAllImages && $item->product->productAllImages->count() > 0)
                                                <img src="{{ asset('uploads/products/' . $item->product->productAllImages->first()->image_path) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</h4>
                                            @if(isset($item->productVariant) && $item->productVariant)
                                                <p class="text-xs text-gray-500">
                                                    @foreach($item->productVariant->attributeValues as $value)
                                                        {{ $value->attribute->name }}: {{ $value->value }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </p>
                                            @endif
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-orange-500 font-semibold text-sm">{{ number_format($displayPrice) }}₫</span>
                                                <span class="text-gray-500 text-sm">x {{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-medium text-gray-900">{{ number_format($displayPrice * $item->quantity) }}₫</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-shopping-cart text-gray-300 text-3xl mb-2"></i>
                                <p>Không có sản phẩm nào để thanh toán</p>
                            </div>
                        @endif
                    </div>

                    <!-- Promo Code (enhanced like cart sidebar) -->
                    <div class="border-t pt-4 mb-4" id="checkout-coupon-box">
                        <div class="flex items-center justify-between mb-2">
                            <label for="checkout-coupon-code" class="text-sm font-medium text-gray-700">Mã giảm giá</label>
                            <button type="button" id="toggle-coupon-list" onclick="toggleCouponListCheckout()" class="text-xs text-orange-600 underline">Danh sách</button>
                        </div>
                        <div class="flex space-x-2 mb-1">
                            <input type="text" id="checkout-coupon-code" placeholder="Nhập mã" class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500 text-sm">
                            <button type="button" onclick="applyCheckoutCoupon()" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 text-sm">Áp dụng</button>
                            <button type="button" onclick="clearCheckoutCoupon()" class="px-3 py-2 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 text-sm" title="Hủy">×</button>
                        </div>
                        <div id="checkout-coupon-message" class="mt-1 text-xs"></div>
                        <div id="checkout-available-coupons" class="hidden mt-2 space-y-2 max-h-44 overflow-y-auto border border-gray-200 rounded p-2 bg-gray-50 text-xs"></div>
                    </div>

                    <!-- Order Total -->
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Tạm tính:</span>
                            <span id="subtotal">{{ number_format($subtotal) }}₫</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phí vận chuyển:</span>
                            <span id="shipping-fee">{{ number_format(($subtotal ?? 0) >= 3000000 ? 0 : 50000) }}₫</span>
                        </div>
                        <div class="flex justify-between text-green-600" id="discount-row" style="display: none;">
                            <span>Giảm giá:</span>
                            <span id="discount-amount">-0₫</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold border-t pt-2">
                            <span>Tổng cộng:</span>
                            <span id="total-amount" class="text-orange-600">{{ number_format($subtotal + (($subtotal ?? 0) >= 3000000 ? 0 : 50000)) }}₫</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="lg:col-span-2 order-1 lg:order-2">
                <form id="checkout-form" class="space-y-6">
                    @csrf
                    
                    <!-- STEP 1: Customer Information -->
                    <div id="checkout-step-1" class="checkout-content">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-xl font-semibold mb-6">Thông tin khách hàng</h3>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                     <input type="text" id="fullname" name="recipient_name" required 
                         value="{{ old('recipient_name', $currentUser->name ?? '') }}"
                         class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                     <input type="tel" id="phone" name="recipient_phone" required 
                         value="{{ old('recipient_phone', $currentUser->phone_number ?? '') }}"
                         class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="recipient_email" required
                        value="{{ old('recipient_email', $currentUser->email ?? '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
                            </div>

                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng *</label>
                                <textarea id="address" name="recipient_address" required rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500"
                                          placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('recipient_address', $defaultAddress?->address_line ?? '') }}</textarea>
                            </div>

                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tỉnh/Thành phố *</label>
                    <select id="province" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
                                        <option value="">Đang tải...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quận/Huyện *</label>
                    <select id="district" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
                                        <option value="">Chọn quận/huyện</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phường/Xã *</label>
                    <select id="ward" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
                                        <option value="">Chọn phường/xã</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú đơn hàng</label>
                                <textarea id="order-notes" name="order_notes" rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500"
                                          placeholder="Ghi chú thêm về đơn hàng (tùy chọn)"></textarea>
                            </div>
                        </div>

                        <!-- Step 1 Navigation -->
                        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                            <div class="flex justify-end">
                                <button type="button" id="next-step-1" class="px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition">
                                    <i class="fas fa-arrow-right mr-2"></i>Bước tiếp theo
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Payment Method -->
                    <div id="checkout-step-2" class="checkout-content" style="display: none;">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-xl font-semibold mb-6">Phương thức vận chuyển</h3>
                            <div class="space-y-4 mb-8">
                                <div class="payment-option border-2 border-gray-300 rounded-lg p-4 selected" data-shipping="1">
                                    <div class="flex items-center">
                                        <input type="radio" id="shipping1" name="shipping_method_id" value="1" checked class="mr-3">
                                        <div class="flex-1">
                                            <label for="shipping1" class="font-medium cursor-pointer">Giao hàng tận nơi</label>
                                            <p class="text-sm text-gray-600">Nhân viên giao hàng sẽ liên hệ và giao tận địa chỉ bạn cung cấp.</p>
                                        </div>
                                        <i class="fas fa-truck text-orange-600 text-xl"></i>
                                    </div>
                                </div>
                                <div class="payment-option border-2 border-gray-300 rounded-lg p-4" data-shipping="2">
                                    <div class="flex items-center">
                                        <input type="radio" id="shipping2" name="shipping_method_id" value="2" class="mr-3">
                                        <div class="flex-1">
                                            <label for="shipping2" class="font-medium cursor-pointer">Nhận hàng tại cửa hàng</label>
                                            <p class="text-sm text-gray-600">Bạn sẽ đến cửa hàng Techvicom để nhận sản phẩm.</p>
                                        </div>
                                        <i class="fas fa-store text-orange-600 text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-xl font-semibold mb-6">Phương thức thanh toán</h3>
                            <div class="space-y-4">
                                <div class="payment-option border-2 border-gray-300 rounded-lg p-4 selected" data-payment="cod">
                                    <div class="flex items-center">
                                        <input type="radio" id="cod" name="payment_method" value="cod" checked class="mr-3">
                                        <div class="flex-1">
                                            <label for="cod" class="font-medium cursor-pointer">Thanh toán khi nhận hàng (COD)</label>
                                            <p class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận được hàng</p>
                                        </div>
                                        <i class="fas fa-truck text-orange-600 text-xl"></i>
                                    </div>
                                </div>
                                <div class="payment-option border-2 border-gray-300 rounded-lg p-4" data-payment="banking">
                                    <div class="flex items-center">
                                        <input type="radio" id="banking" name="payment_method" value="bank_transfer" class="mr-3">
                                        <div class="flex-1">
                                            <label for="banking" class="font-medium cursor-pointer">Thanh toán online</label>
                                            <p class="text-sm text-gray-600">Thanh toán trực tuyến an toàn</p>
                                        </div>
                                        <i class="fas fa-credit-card text-orange-600 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 Navigation -->
                        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                            <div class="flex justify-between">
                                <button type="button" id="prev-step-2" class="px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition">
                                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                                </button>
                                <button type="button" id="next-step-2" class="px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition">
                                    <i class="fas fa-arrow-right mr-2"></i>Tiếp tục
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Order Confirmation -->
                    <div id="checkout-step-3" class="checkout-content" style="display: none;">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-xl font-semibold mb-6">Xác nhận đơn hàng</h3>
                            
                            <!-- Order Summary -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h4 class="font-semibold mb-4">Thông tin giao hàng</h4>
                                <div id="delivery-summary" class="space-y-2 text-sm">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h4 class="font-semibold mb-4">Phương thức thanh toán</h4>
                                <div id="payment-summary" class="text-sm">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                            </div>

                            <!-- Hidden fields for form submission -->
                            <input type="hidden" name="shipping_method_id" value="1">
                            
                            <div class="flex items-center mb-6">
                                <input type="checkbox" id="agree-terms" required class="mr-3">
                                <label for="agree-terms" class="text-sm">
                                    Tôi đã đọc và đồng ý với 
                                    <a href="#" class="text-orange-600 hover:underline">điều khoản và điều kiện</a> 
                                    của website
                                </label>
                            </div>
                        </div>

                        <!-- Step 3 Navigation -->
                        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                            <div class="flex justify-between">
                                <button type="button" id="prev-step-3" class="px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition">
                                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                                </button>
                                <button type="button" id="confirm-order" class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                                    <i class="fas fa-check mr-2"></i>Xác nhận đặt hàng
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12 no-print">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-lg font-semibold mb-4">Về Techvicom</h4>
                    <p class="text-gray-300">Chuyên cung cấp các sản phẩm công nghệ chính hãng với giá tốt nhất.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Liên hệ</h4>
                    <p class="text-gray-300">📞 1900-xxxx</p>
                    <p class="text-gray-300">📧 support@techvicom.vn</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Chính sách</h4>
                    <ul class="text-gray-300 space-y-2">
                        <li><a href="#" class="hover:text-orange-400">Chính sách bảo hành</a></li>
                        <li><a href="#" class="hover:text-orange-400">Chính sách đổi trả</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Theo dõi</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // ====== Coupon functions (checkout) similar to cart sidebar ======
        function applyCheckoutCoupon(){
            const input = document.getElementById('checkout-coupon-code');
            const msg = document.getElementById('checkout-coupon-message');
            if(!input) return;
            const code = input.value.trim();
            input.classList.remove('border-red-500');
            msg.textContent = '';
            if(!code){
                input.classList.add('border-red-500');
                msg.textContent='Nhập mã giảm giá';
                msg.className='mt-1 text-xs text-red-500';
                return;
            }
            const subtotal = window.checkoutSubtotal || 0;
            msg.textContent='Đang kiểm tra...';
            msg.className='mt-1 text-xs text-gray-500';
            fetch('/api/apply-coupon', {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                body: JSON.stringify({coupon_code: code, subtotal: subtotal})
            }).then(r=>r.json()).then(data=>{
                if(data.success){
                    const amount = data.discount_amount || 0;
                    window.checkoutDiscount = amount;
                    localStorage.setItem('appliedDiscount', JSON.stringify({
                        code: code,
                        amount: amount,
                        details: {
                            min_order_value: data.coupon.min_order_value || 0,
                            max_order_value: data.coupon.max_order_value || 0,
                            discount_type: data.coupon.discount_type,
                            value: data.coupon.value
                        },
                        fromDatabase: true
                    }));
                    msg.textContent = data.coupon && data.coupon.message ? data.coupon.message : 'Áp dụng thành công';
                    msg.className='mt-1 text-xs text-green-600';
                    input.classList.remove('border-red-500');
                    updateCheckoutTotal();
                } else {
                    localStorage.removeItem('appliedDiscount');
                    window.checkoutDiscount = 0;
                    input.classList.add('border-red-500');
                    msg.textContent = data.message || 'Mã giảm giá không hợp lệ';
                    msg.className='mt-1 text-xs text-red-500';
                    updateCheckoutTotal();
                }
            }).catch(err=>{
                input.classList.add('border-red-500');
                msg.textContent='Lỗi tải mã giảm giá, thử lại sau';
                msg.className='mt-1 text-xs text-red-500';
            });
        }

        function clearCheckoutCoupon(){
            localStorage.removeItem('appliedDiscount');
            window.checkoutDiscount = 0;
            const input=document.getElementById('checkout-coupon-code');
            const msg=document.getElementById('checkout-coupon-message');
            if(input) input.value='';
            if(msg){ msg.textContent=''; msg.className='mt-1 text-xs'; }
            updateCheckoutTotal();
        }

        function toggleCouponListCheckout(){
            const box = document.getElementById('checkout-available-coupons');
            const btn = document.getElementById('toggle-coupon-list');
            if(!box||!btn) return;
            if(box.classList.contains('hidden')){
                loadAvailableCouponsCheckout();
                box.classList.remove('hidden');
                btn.textContent='Ẩn';
            } else {
                box.classList.add('hidden');
                btn.textContent='Danh sách';
            }
        }

        function loadAvailableCouponsCheckout(){
            const box = document.getElementById('checkout-available-coupons');
            if(!box) return;
            const subtotal = window.checkoutSubtotal || 0;
            fetch(`/api/coupons?subtotal=${subtotal}`)
                .then(r=>r.json())
                .then(data=>{
                    if(!data.success){ box.innerHTML='<p class="text-red-500">Lỗi tải mã giảm giá</p>'; return; }
                    if(!Array.isArray(data.coupons) || data.coupons.length===0){ box.innerHTML='<p class="text-gray-500">Không có mã giảm giá phù hợp</p>'; return; }
                    const applied = (function(){ try { const s=JSON.parse(localStorage.getItem('appliedDiscount')); return s&&s.code? s.code : null; } catch(e){return null;} })();
                    box.innerHTML = data.coupons.map(c=>{
                        const can = c.eligible;
                        const cls = can ? 'border-green-300 bg-white hover:border-orange-500 cursor-pointer' : 'border-gray-200 bg-gray-100 opacity-60 cursor-not-allowed';
                        const line = c.discount_type==='percent' ? `Giảm ${c.value}%` : `Giảm ${Number(c.value).toLocaleString()}₫`;
                        const reason = !can ? `(<span class='text-red-500'>${c.reason}</span>)` : '';
                        const selectedCls = applied && applied.toUpperCase()===c.code.toUpperCase() ? 'border-orange-500 coupon-selected' : '';
                        return `<div class="coupon-item border rounded p-2 ${cls} ${selectedCls}" data-code="${c.code}" data-eligible="${can}">
                                    <div class='flex justify-between items-center'>
                                        <span class='font-semibold'>${c.code}</span>
                                        <span class='text-orange-600 font-medium'>${line}</span>
                                    </div>
                                    <div class='text-[10px] text-gray-600 mt-1'>${reason}</div>
                                </div>`;
                    }).join('');
                    box.querySelectorAll('.coupon-item').forEach(div=>{
                        div.addEventListener('click',()=>{
                            if(div.dataset.eligible!=='true') return;
                            box.querySelectorAll('.coupon-item.coupon-selected').forEach(el=>el.classList.remove('coupon-selected','border-orange-500'));
                            div.classList.add('coupon-selected','border-orange-500');
                            const input=document.getElementById('checkout-coupon-code');
                            const msg=document.getElementById('checkout-coupon-message');
                            if(input) input.value=div.dataset.code;
                            if(msg){ msg.textContent='Đã chọn mã, bấm Áp dụng để xác nhận'; msg.className='mt-1 text-xs text-gray-600'; }
                        });
                    });
                })
                .catch(err=>{ console.error('Load coupons error', err); box.innerHTML='<p class="text-red-500">Lỗi tải mã giảm giá</p>'; });
        }
        
        function updateCheckoutTotal() {
            const subtotal = typeof window.checkoutSubtotal === 'number' ? window.checkoutSubtotal : 0;
            const currentDiscount = typeof window.checkoutDiscount === 'number' ? window.checkoutDiscount : 0;
            // Preserve zero (free ship); only fallback if undefined/null/not number
            const currentShippingFee = (typeof window.checkoutShippingFee === 'number') ? window.checkoutShippingFee : (subtotal >= 3000000 ? 0 : 50000);
            
            const total = subtotal + currentShippingFee - currentDiscount;
            
            console.log('Updating checkout total:', { subtotal, currentDiscount, currentShippingFee, total });
            
            const subtotalElement = document.getElementById('subtotal');
            const shippingElement = document.getElementById('shipping-fee');
            const totalElement = document.getElementById('total-amount');
            const discountRow = document.getElementById('discount-row');
            const discountAmount = document.getElementById('discount-amount');
            
            if (subtotalElement) subtotalElement.textContent = formatCurrency(subtotal);
            if (shippingElement) shippingElement.textContent = formatCurrency(currentShippingFee);
            if (totalElement) totalElement.textContent = formatCurrency(total);
            
            if (discountRow && discountAmount) {
                if (currentDiscount > 0) {
                    discountRow.style.display = 'flex';
                    discountAmount.textContent = formatCurrency(-currentDiscount);
                } else {
                    discountRow.style.display = 'none';
                }
            }
        }
        
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Use server-side subtotal directly (mutable because may be overridden by selected-only mode)
            let subtotal = {{ $subtotal ?? 0 }};
            // Initial shipping fee: free if subtotal >=3,000,000 else 50,000
            let currentShippingFee = (subtotal >= 3000000) ? 0 : 50000;
            let currentDiscount = 0;
            let currentStep = 1; // Track current step

            // Preserve subtotal if filterSelectedItems IIFE already adjusted it for selected-only mode
            const selectedMode = new URLSearchParams(window.location.search).get('selected');
            if (selectedMode && typeof window.checkoutSubtotal === 'number' && window.checkoutSubtotal !== subtotal) {
                console.log('[Checkout Init] Using filtered subtotal from pre-filter:', window.checkoutSubtotal, 'instead of server subtotal', subtotal);
                subtotal = window.checkoutSubtotal; // keep filtered value
            } else {
                window.checkoutSubtotal = subtotal; // initial set
            }
            window.checkoutShippingFee = currentShippingFee;
            window.checkoutDiscount = currentDiscount;
            window.currentStep = currentStep;

            console.log('Server subtotal:', subtotal);
            console.log('Subtotal type:', typeof subtotal);

            // Initialize display (re-evaluate discount if stored and selected mode adjusted subtotal)
            try {
                const saved = JSON.parse(localStorage.getItem('appliedDiscount')||'null');
                if (saved && saved.amount) {
                    const capped = Math.min(Number(saved.amount)||0, window.checkoutSubtotal||0);
                    window.checkoutDiscount = capped;
                }
            } catch(e){ console.warn('[Checkout Init] discount parse', e); }
            updateCheckoutTotal();
            setupPaymentOptions();
            setupStepNavigation();
            updateShippingFee();
            loadAppliedDiscount(); // Load discount from cart if any
            loadProvinces(); // Load provinces from API
            // If discount already stored, reflect in coupon input
            try { const saved=JSON.parse(localStorage.getItem('appliedDiscount')); if(saved&&saved.code){ const inp=document.getElementById('checkout-coupon-code'); if(inp) inp.value=saved.code; } } catch(e){}

            console.log('✅ Checkout page initialized successfully');

            // Setup step navigation
            function setupStepNavigation() {
                // Next Step 1 -> Step 2
                document.getElementById('next-step-1').addEventListener('click', function() {
                    if (validateStep1()) {
                        goToStep(2);
                    }
                });

                // Previous Step 2 -> Step 1
                document.getElementById('prev-step-2').addEventListener('click', function() {
                    goToStep(1);
                });

                // Next Step 2 -> Step 3
                document.getElementById('next-step-2').addEventListener('click', function() {
                    if (validateStep2()) {
                        populateStep3Summary();
                        goToStep(3);
                    }
                });

                // Previous Step 3 -> Step 2
                document.getElementById('prev-step-3').addEventListener('click', function() {
                    goToStep(2);
                });

                // Confirm Order
                document.getElementById('confirm-order').addEventListener('click', function() {
                    if (validateStep3()) {
                        submitOrder();
                    }
                });
            }

            function goToStep(step) {
                console.log(`🚀 Moving to step ${step}`);
                
                // Hide all steps
                document.querySelectorAll('.checkout-content').forEach(content => {
                    content.style.display = 'none';
                });

                // Show target step (only steps 1-3)
                if (step <= 3) {
                    document.getElementById(`checkout-step-${step}`).style.display = 'block';
                }

                // Update step indicators
                updateStepIndicators(step);
                window.currentStep = step;

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function updateStepIndicators(activeStep) {
                // Reset all steps
                for (let i = 1; i <= 3; i++) {
                    const stepElement = document.getElementById(`step-${i}`);
                    stepElement.classList.remove('active', 'completed');
                    stepElement.classList.add('bg-gray-200', 'text-gray-600');
                    
                    const stepNumber = stepElement.querySelector('span');
                    stepNumber.classList.remove('bg-white', 'text-orange-600', 'bg-green-500');
                    stepNumber.classList.add('bg-gray-400', 'text-white');
                }

                // Mark completed steps
                for (let i = 1; i < activeStep; i++) {
                    const stepElement = document.getElementById(`step-${i}`);
                    stepElement.classList.remove('bg-gray-200', 'text-gray-600');
                    stepElement.classList.add('completed', 'bg-green-500', 'text-white');
                    
                    const stepNumber = stepElement.querySelector('span');
                    stepNumber.classList.remove('bg-gray-400', 'text-white');
                    stepNumber.classList.add('bg-white', 'text-green-500');
                }

                // Mark active step
                if (activeStep <= 3) {
                    const activeElement = document.getElementById(`step-${activeStep}`);
                    activeElement.classList.remove('bg-gray-200', 'text-gray-600');
                    activeElement.classList.add('active', 'bg-orange-500', 'text-white');
                    
                    const activeNumber = activeElement.querySelector('span');
                    activeNumber.classList.remove('bg-gray-400', 'text-white');
                    activeNumber.classList.add('bg-white', 'text-orange-600');
                }
            }

            function validateStep1() {
                const requiredFields = ['fullname', 'phone', 'address', 'province', 'district', 'ward'];
                let isValid = true;
                let errorMessages = [];
                
                requiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field && !field.value.trim()) {
                        field.classList.add('border-red-500');
                        isValid = false;
                        
                        switch(fieldId) {
                            case 'fullname': errorMessages.push('Vui lòng nhập họ và tên'); break;
                            case 'phone': errorMessages.push('Vui lòng nhập số điện thoại'); break;
                            case 'address': errorMessages.push('Vui lòng nhập địa chỉ giao hàng'); break;
                            case 'province': errorMessages.push('Vui lòng chọn tỉnh/thành phố'); break;
                            case 'district': errorMessages.push('Vui lòng chọn quận/huyện'); break;
                            case 'ward': errorMessages.push('Vui lòng chọn phường/xã'); break;
                        }
                    } else if (field) {
                        field.classList.remove('border-red-500');
                    }
                });

                @guest
                    const emailField = document.getElementById('email');
                    if (emailField && !emailField.value.trim()) {
                        emailField.classList.add('border-red-500');
                        errorMessages.push('Vui lòng nhập email');
                        isValid = false;
                    } else if (emailField) {
                        emailField.classList.remove('border-red-500');
                    }
                @endguest
                
                if (!isValid && errorMessages.length > 0) {
                    alert('Lỗi bước 1:\n' + errorMessages.join('\n'));
                }
                
                return isValid;
            }

            function validateStep2() {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethod) {
                    alert('Vui lòng chọn phương thức thanh toán');
                    return false;
                }
                return true;
            }

            function validateStep3() {
                const agreeTerms = document.getElementById('agree-terms');
                if (!agreeTerms.checked) {
                    alert('Vui lòng đồng ý với điều khoản và điều kiện');
                    return false;
                }
                return true;
            }

            function populateStep3Summary() {
                // Populate delivery summary
                const deliverySummary = document.getElementById('delivery-summary');
                const fullname = document.getElementById('fullname').value;
                const phone = document.getElementById('phone').value;
                const address = document.getElementById('address').value;
                const province = document.getElementById('province').selectedOptions[0]?.text || '';
                const district = document.getElementById('district').selectedOptions[0]?.text || '';
                const ward = document.getElementById('ward').selectedOptions[0]?.text || '';

                deliverySummary.innerHTML = `
                    <div><strong>Người nhận:</strong> ${fullname}</div>
                    <div><strong>Số điện thoại:</strong> ${phone}</div>
                    <div><strong>Địa chỉ:</strong> ${address}</div>
                    <div><strong>Khu vực:</strong> ${ward}, ${district}, ${province}</div>
                `;

                // Populate payment summary
                const paymentSummary = document.getElementById('payment-summary');
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                const paymentText = paymentMethod?.value === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Thanh toán online';
                
                paymentSummary.innerHTML = `<div><strong>Phương thức:</strong> ${paymentText}</div>`;
            }

            function submitOrder() {
                console.log('🚀 Submitting order...');
                
                // Validate required fields
                const fullname = document.getElementById('fullname').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const address = document.getElementById('address').value.trim();
                const province = document.getElementById('province').value;
                const district = document.getElementById('district').value;
                const ward = document.getElementById('ward').value;
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                
                if (!fullname) {
                    alert('Vui lòng nhập họ và tên');
                    return;
                }
                if (!phone) {
                    alert('Vui lòng nhập số điện thoại');
                    return;
                }
                if (!address) {
                    alert('Vui lòng nhập địa chỉ cụ thể');
                    return;
                }
                if (!province || province === '') {
                    alert('Vui lòng chọn tỉnh/thành phố');
                    return;
                }
                if (!district || district === '') {
                    alert('Vui lòng chọn quận/huyện');
                    return;
                }
                if (!ward || ward === '') {
                    alert('Vui lòng chọn phường/xã');
                    return;
                }
                if (!paymentMethod) {
                    alert('Vui lòng chọn phương thức thanh toán');
                    return;
                }
                
                // Show loading on confirm button
                const confirmBtn = document.getElementById('confirm-order');
                const originalText = confirmBtn.innerHTML;
                confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
                confirmBtn.disabled = true;

                // Prepare form data
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('recipient_name', document.getElementById('fullname').value);
                formData.append('recipient_phone', document.getElementById('phone').value);
                
                // Get address parts
                const provinceSelect = document.getElementById('province');
                const districtSelect = document.getElementById('district');
                const wardSelect = document.getElementById('ward');
                const addressText = document.getElementById('address').value;
                
                // Build full address
                const provinceName = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
                const districtName = districtSelect.options[districtSelect.selectedIndex]?.text || '';
                const wardName = wardSelect.options[wardSelect.selectedIndex]?.text || '';
                
                const fullAddress = `${addressText}, ${wardName}, ${districtName}, ${provinceName}`;
                formData.append('recipient_address', fullAddress);
                
                formData.append('payment_method', document.querySelector('input[name="payment_method"]:checked').value);
                formData.append('shipping_method_id', '1');
                formData.append('order_notes', document.getElementById('order-notes').value);
                
                @guest
                    formData.append('guest_email', document.getElementById('email').value);
                @endguest

                // Add coupon if applied
                // Fix: lấy đúng id input mã giảm giá
                const couponInput = document.getElementById('checkout-coupon-code');
                const promoCode = couponInput ? couponInput.value : '';
                if (promoCode) {
                    formData.append('coupon_code', promoCode);
                }

                console.log('📝 Form data prepared:', Object.fromEntries(formData));
                
                // Debug: Show all form fields being sent
                for (const [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                
                // Submit to server using regular form submission for better redirect handling
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("checkout.process") }}';
                
                // Add all form data as hidden inputs
                for (const [key, value] of formData.entries()) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }
                
                // Append to body and submit
                document.body.appendChild(form);
                console.log('🚀 Submitting form with action:', form.action);
                form.submit();
            }

            function populateSuccessInfo() {
                const successInfo = document.getElementById('order-success-info');
                const deliveryInfo = document.getElementById('delivery-success-info');
                const total = window.checkoutSubtotal + window.checkoutShippingFee - window.checkoutDiscount;
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                const paymentText = paymentMethod?.value === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Thanh toán online';

                // Order information
                successInfo.innerHTML = `
                    <div class="flex justify-between py-1">
                        <span>Mã đơn hàng:</span>
                        <span class="font-medium">#${Date.now()}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Tạm tính:</span>
                        <span class="font-medium">${formatCurrency(window.checkoutSubtotal)}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Phí vận chuyển:</span>
                        <span class="font-medium">${formatCurrency(window.checkoutShippingFee)}</span>
                    </div>
                    ${window.checkoutDiscount > 0 ? `
                    <div class="flex justify-between py-1 text-green-600">
                        <span>Giảm giá:</span>
                        <span class="font-medium">-${formatCurrency(window.checkoutDiscount)}</span>
                    </div>
                    ` : ''}
                    <div class="flex justify-between py-2 border-t border-gray-300 mt-2">
                        <span class="font-semibold">Tổng cộng:</span>
                        <span class="font-bold text-orange-600 text-lg">${formatCurrency(total)}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Phương thức thanh toán:</span>
                        <span class="font-medium">${paymentText}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Trạng thái:</span>
                        <span class="font-medium text-blue-600">Đang xử lý</span>
                    </div>
                `;

                // Delivery information
                const fullname = document.getElementById('fullname').value;
                const phone = document.getElementById('phone').value;
                const address = document.getElementById('address').value;
                const province = document.getElementById('province').selectedOptions[0]?.text || '';
                const district = document.getElementById('district').selectedOptions[0]?.text || '';
                const ward = document.getElementById('ward').selectedOptions[0]?.text || '';
                const orderNotes = document.getElementById('order-notes').value;

                deliveryInfo.innerHTML = `
                    <div class="flex justify-between py-1">
                        <span>Người nhận:</span>
                        <span class="font-medium">${fullname}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Số điện thoại:</span>
                        <span class="font-medium">${phone}</span>
                    </div>
                    <div class="py-1">
                        <span class="block mb-1">Địa chỉ giao hàng:</span>
                        <span class="font-medium text-sm">${address}</span>
                    </div>
                    <div class="py-1">
                        <span class="block mb-1">Khu vực:</span>
                        <span class="font-medium text-sm">${ward}, ${district}, ${province}</span>
                    </div>
                    ${orderNotes ? `
                    <div class="py-1">
                        <span class="block mb-1">Ghi chú:</span>
                        <span class="font-medium text-sm italic">"${orderNotes}"</span>
                    </div>
                    ` : ''}
                `;
            }

            function loadProvinces() {
                const provinceSelect = document.getElementById('province');
                if (!provinceSelect) return;
                
                fetch('/api/provinces')
                    .then(response => response.json())
                    .then(provinces => {
                        console.log('Provinces loaded:', provinces);
                        provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                        if (Array.isArray(provinces) && provinces.length > 0) {
                            provinces.forEach(province => {
                                provinceSelect.innerHTML += `<option value="${province.code}">${province.name}</option>`;
                            });
                            // Prefill for authenticated user
                            @auth
                                const userCity = @json($defaultAddress?->city);
                                if (userCity) {
                                    // Try match by name (case-insensitive)
                                    const opt = Array.from(provinceSelect.options).find(o => o.text.trim().toLowerCase() === userCity.trim().toLowerCase());
                                    if (opt) { provinceSelect.value = opt.value; provinceSelect.dispatchEvent(new Event('change')); }
                                }
                            @endauth
                        } else {
                            provinceSelect.innerHTML = '<option value="">Không có dữ liệu</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading provinces:', error);
                        provinceSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                    });
            }

            function loadAppliedDiscount() {
                const appliedDiscount = localStorage.getItem('appliedDiscount');
                const promoCodeInput = document.getElementById('promo-code');
                const promoMessage = document.getElementById('promo-message');
                
                if (appliedDiscount && promoCodeInput && promoMessage) {
                    try {
                        const discountData = JSON.parse(appliedDiscount);
                        console.log('Loading applied discount from localStorage:', discountData);
                        
                        // Set the promo code input
                        promoCodeInput.value = discountData.code;
                        
                        // Apply the discount
                        window.checkoutDiscount = discountData.amount;
                        
                        // Show success message - use detailed message if available
                        let message = `Mã "${discountData.code}" đã được áp dụng`;
                        if (discountData.details && discountData.details.message) {
                            message = discountData.details.message;
                        }
                        promoMessage.innerHTML = `<span class="text-green-600"><i class="fas fa-check mr-1"></i>${message}</span>`;
                        
                        // Update total
                        updateCheckoutTotal();
                        
                        // Disable input to prevent re-application
                        promoCodeInput.disabled = true;
                        
                        console.log('Discount loaded successfully:', window.checkoutDiscount);
                    } catch (error) {
                        console.error('Error loading discount:', error);
                    }
                }
            }

            function setupPaymentOptions() {
                const paymentOptions = document.querySelectorAll('.payment-option');
                
                paymentOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        // Remove selected class from all options
                        paymentOptions.forEach(opt => opt.classList.remove('selected'));
                        
                        // Add selected class to clicked option
                        this.classList.add('selected');
                        
                        // Check the radio button
                        const radio = this.querySelector('input[type="radio"]');
                        radio.checked = true;
                    });
                });
            }

            function updateShippingFee() {
                const recompute = () => {
                    const sub = window.checkoutSubtotal || 0;
                    window.checkoutShippingFee = (sub >= 3000000) ? 0 : 50000;
                    updateCheckoutTotal();
                };
                recompute();
                // Province no longer affects fee, but keep hook if rule changes later
                const provinceSelect = document.getElementById('province');
                if (provinceSelect) provinceSelect.addEventListener('change', recompute);
            }

            // Setup address dropdowns
            function setupAddressDropdowns() {
                const provinceSelect = document.getElementById('province');
                const districtSelect = document.getElementById('district');
                const wardSelect = document.getElementById('ward');

                if (!provinceSelect || !districtSelect || !wardSelect) return;

                // Update districts when province changes
                provinceSelect.addEventListener('change', function() {
                    const provinceCode = this.value;
                    
                    console.log('Province changed to:', provinceCode);
                    
                    // Clear previous options
                    districtSelect.innerHTML = '<option value="">Đang tải...</option>';
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    
                    if (!provinceCode) {
                        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                        return;
                    }
                    
                    const apiUrl = `/api/districts/${provinceCode}`;
                    console.log('Fetching from:', apiUrl);
                    
                    // Fetch districts from API
                    fetch(apiUrl)
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(districts => {
                            console.log('Districts received:', districts);
                            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                            if (Array.isArray(districts) && districts.length > 0) {
                                districts.forEach(district => {
                                    districtSelect.innerHTML += `<option value="${district.code}">${district.name}</option>`;
                                });
                                @auth
                                    const userDistrict = @json($defaultAddress?->district);
                                    if (userDistrict) {
                                        const opt = Array.from(districtSelect.options).find(o => o.text.trim().toLowerCase() === userDistrict.trim().toLowerCase());
                                        if (opt) { districtSelect.value = opt.value; districtSelect.dispatchEvent(new Event('change')); }
                                    }
                                @endauth
                            } else {
                                districtSelect.innerHTML = '<option value="">Không có dữ liệu</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading districts:', error);
                            districtSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                        });
                });

                // Update wards when district changes
                districtSelect.addEventListener('change', function() {
                    const districtCode = this.value;
                    
                    console.log('District changed to:', districtCode);
                    
                    // Clear previous options
                    wardSelect.innerHTML = '<option value="">Đang tải...</option>';
                    
                    if (!districtCode) {
                        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                        return;
                    }
                    
                    const apiUrl = `/api/wards/${districtCode}`;
                    console.log('Fetching wards from:', apiUrl);
                    
                    // Fetch wards from API
                    fetch(apiUrl)
                        .then(response => {
                            console.log('Wards response status:', response.status);
                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(wards => {
                            console.log('Wards received:', wards);
                            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                            if (Array.isArray(wards) && wards.length > 0) {
                                wards.forEach(ward => {
                                    wardSelect.innerHTML += `<option value="${ward.code}">${ward.name}</option>`;
                                });
                                @auth
                                    const userWard = @json($defaultAddress?->ward);
                                    if (userWard) {
                                        const opt = Array.from(wardSelect.options).find(o => o.text.trim().toLowerCase() === userWard.trim().toLowerCase());
                                        if (opt) { wardSelect.value = opt.value; }
                                    }
                                @endauth
                            } else {
                                wardSelect.innerHTML = '<option value="">Không có dữ liệu</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading wards:', error);
                            wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                        });
                });
            }

            // Call setup functions
            setupAddressDropdowns();
        });

        // ================= Filter checkout items if user chose selected only =================
        (function filterSelectedItems(){
            try {
                const urlParams = new URLSearchParams(window.location.search);
                if (!urlParams.get('selected')) { console.log('[Checkout Filter] Not in selected-only mode'); return; }
                const raw = localStorage.getItem('checkout_selected_items');
                if (!raw) { console.log('[Checkout Filter] No stored selected IDs'); return; }
                let selectedIds = [];
                try { selectedIds = JSON.parse(raw); } catch(e){ console.warn('[Checkout Filter] JSON parse error', e); return; }
                if (!Array.isArray(selectedIds) || selectedIds.length === 0) { console.log('[Checkout Filter] Parsed selected IDs empty'); return; }
                // Normalize to string for comparison
                selectedIds = selectedIds.map(id => String(id));
                console.log('[Checkout Filter] Selected IDs =>', selectedIds);
                const allItems = document.querySelectorAll('.checkout-item');
                // Remove unselected first
                let keptItems = [];
                allItems.forEach(el => {
                    const id = String(el.getAttribute('data-item-id'));
                    if (!selectedIds.includes(id)) {
                        el.remove();
                    } else {
                        keptItems.push(el);
                    }
                });
                // Recompute subtotal from kept items (price * qty)
                let newSubtotal = 0;
                keptItems.forEach(el => {
                    const unit = parseInt(el.getAttribute('data-unit-price')) || 0;
                    const qty = parseInt(el.getAttribute('data-quantity')) || 1;
                    newSubtotal += unit * qty;
                });
                console.log('[Checkout Filter] Kept items:', keptItems.length, 'New subtotal:', newSubtotal);
                const subtotalEl = document.getElementById('subtotal');
                if (subtotalEl) subtotalEl.textContent = newSubtotal.toLocaleString('vi-VN') + '₫';
                window.checkoutSubtotal = newSubtotal;
                // Re-cap discount
                let discount = 0;
                try { const saved = JSON.parse(localStorage.getItem('appliedDiscount')||'null'); if(saved && saved.amount) discount = Math.min(Number(saved.amount)||0, newSubtotal); } catch(e){ console.warn('[Checkout Filter] discount parse', e); }
                window.checkoutDiscount = discount;
                const discountRow = document.getElementById('discount-row');
                const discountAmount = document.getElementById('discount-amount');
                if (discountRow && discountAmount) { if (discount>0) { discountRow.style.display='flex'; discountAmount.textContent='-' + discount.toLocaleString('vi-VN') + '₫'; } else { discountRow.style.display='none'; } }
                const totalAmountEl = document.getElementById('total-amount');
                if (totalAmountEl) { const shipping = (newSubtotal >= 3000000) ? 0 : 50000; window.checkoutShippingFee = shipping; totalAmountEl.textContent = (newSubtotal + shipping - discount).toLocaleString('vi-VN') + '₫'; }
                const title = document.getElementById('order-summary-title');
                if (title) title.textContent = 'Đơn hàng (sản phẩm đã chọn)';
                try { const listBox = document.getElementById('checkout-available-coupons'); if(listBox && !listBox.classList.contains('hidden')) loadAvailableCouponsCheckout(); } catch(e){}
                // If nothing kept (edge), show empty state message
                if (keptItems.length === 0) {
                    const itemsWrap = document.getElementById('checkout-items');
                    if (itemsWrap) itemsWrap.innerHTML = '<div class="text-center py-6 text-gray-500"><i class="fas fa-shopping-cart text-3xl mb-2"></i><p>Không có sản phẩm đã chọn</p></div>';
                }
            } catch(e){ console.error('Filter selected checkout items error', e); }
        })();
    </script>

    <!-- Shared Footer -->
    <div id="shared-footer-container"></div>
</body>
</html>
