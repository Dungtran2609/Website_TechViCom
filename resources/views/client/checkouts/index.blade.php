@extends('client.layouts.app')

@section('title', 'Thanh toán - Techvicom')

@push('styles')
    {{-- Nếu layout đã có CSS chung thì có thể bỏ dòng dưới --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .checkout-step {
            transition: .3s
        }

        .checkout-step.active {
            background: #ea580c;
            color: #fff
        }

        .checkout-step.completed {
            background: #16a34a;
            color: #fff
        }

        .form-group {
            margin-bottom: 1rem
        }

        .payment-option {
            transition: .2s;
            cursor: pointer
        }

        .payment-option:hover {
            border-color: #ea580c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1)
        }

        .payment-option.selected {
            border-color: #ea580c;
            background: #fff7ed;
            box-shadow: 0 4px 12px rgba(234, 88, 12, .2)
        }

        #checkout-success {
            display: block
        }

        @media print {
            .no-print {
                display: none !important
            }

            body,
            .bg-gray-50 {
                background: #fff !important
            }
        }
    </style>
@endpush

@section('content')
    @if(session('notification'))
        <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300
            @if(session('notification.type') === 'success') bg-green-500
            @elseif(session('notification.type') === 'error') bg-red-500
            @else bg-yellow-500 @endif">
            {{ session('notification.message') }}
        </div>
    @endif

    @if (session('payment_cancelled_message'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mx-4 mt-4" role="alert"
            data-message="payment-cancelled">
            <span class="block sm:inline">{{ session('payment_cancelled_message') }}</span>
            <div class="absolute top-0 bottom-0 right-0 px-4 py-3 flex items-center space-x-2">
                <a href="{{ route('checkout.index', ['action' => 'clear_message']) }}"
                    class="text-red-500 hover:text-red-700 text-sm underline">
                    Đã hiểu
                </a>
                <button onclick="clearPaymentMessage()" class="text-red-500 hover:text-red-700">
                    <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-4 mt-4" role="alert">
            <strong class="font-bold">Lỗi!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mx-4 mt-4" role="alert">
            <strong class="font-bold">Thành công!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-4 mt-4" role="alert">
            <strong class="font-bold">Lỗi validation:</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header dùng chung --}}
    <div id="shared-header-container" class="no-print"></div>

    {{-- Steps --}}
    <div class="bg-white border-b no-print">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-center">
                <div class="hidden md:flex items-center space-x-4">
                    <div id="step-1" class="checkout-step active flex items-center px-4 py-2 rounded-full">
                        <span
                            class="w-6 h-6 bg-white text-orange-600 rounded-full flex items-center justify-center text-sm font-bold mr-2">1</span>
                        <span>Thông tin</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div id="step-2"
                        class="checkout-step flex items-center px-4 py-2 rounded-full bg-gray-200 text-gray-600">
                        <span
                            class="w-6 h-6 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-bold mr-2">2</span>
                        <span>Thanh toán</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div id="step-3"
                        class="checkout-step flex items-center px-4 py-2 rounded-full bg-gray-200 text-gray-600">
                        <span
                            class="w-6 h-6 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-bold mr-2">3</span>
                        <span>Hoàn tất</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- FORM CHECKOUT (2/3) --}}
            <div class="lg:col-span-2 order-2 lg:order-1">
                <form id="checkout-form" class="space-y-6">
                    @csrf
                    <input type="hidden" id="selected-input" name="selected" value="{{ request('selected') }}">
                    {{-- STEP 1 --}}
                    <div id="checkout-step-1" class="checkout-content">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-xl font-semibold mb-6">Thông tin khách hàng</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                                    <input type="text" id="fullname" name="recipient_name" required value="{{ old('recipient_name', $currentUser->name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
                                    <span id="fullname-error" class="text-xs text-red-500"></span>
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                                    <input type="tel" id="phone" name="recipient_phone" required value="{{ old('recipient_phone', $currentUser->phone_number ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
                                    <span id="phone-error" class="text-xs text-red-500"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" id="email" name="recipient_email" required value="{{ old('recipient_email', $currentUser->email ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
                                <span id="email-error" class="text-xs text-red-500"></span>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng *</label>
                                @if (isset($addresses) && count($addresses) > 0)
                                    <div class="mb-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Chọn địa chỉ đã lưu</label>
                                        <div class="space-y-2">
                                            @foreach ($addresses as $address)
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    <input type="radio" name="selected_address" value="{{ $address->id }}" @if ($loop->first) checked @endif onchange="toggleAddressForm(false)"
                                                        data-ward="{{ $address->ward }}" data-district="{{ $address->district }}" data-city="{{ $address->city }}" data-address="{{ $address->address_line }}">
                                                    <span>
                                                        {{ $address->address_line }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->city }}
                                                        @if ($address->is_default)
                                                            <span class="text-xs text-orange-500 font-semibold">(Mặc định)</span>
                                                        @endif
                                                    </span>
                                                </label>
                                            @endforeach
                                            <label class="flex items-center space-x-2 cursor-pointer mt-2">
                                                <input type="radio" name="selected_address" value="new" onchange="toggleAddressForm(true)">
                                                <span>Thêm địa chỉ mới</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <div id="add-address-form" style="display: {{ isset($addresses) && count($addresses) > 0 ? 'none' : 'block' }};">
                                    <div class="form-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng *</label>
                                        <div class="grid md:grid-cols-3 gap-4">
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Tỉnh/Thành phố *</label>
                                                <select id="province" name="province_code" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500" data-default-city="{{ $defaultAddress?->city ?? '' }}" @if ($defaultAddress?->city) data-default-city-name="{{ $defaultAddress->city }}" @endif @if ($defaultAddress?->city_code) data-default-city-code="{{ $defaultAddress->city_code }}" @endif>
                                                    <option value="">Đang tải...</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Quận/Huyện *</label>
                                                <select id="district" name="district_code" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500" data-default-district="{{ $defaultAddress?->district ?? '' }}" @if ($defaultAddress?->district) data-default-district-name="{{ $defaultAddress->district }}" @endif @if ($defaultAddress?->district_code) data-default-district-code="{{ $defaultAddress->district_code }}" @endif>
                                                    <option value="">Chọn quận/huyện</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Phường/Xã *</label>
                                                <select id="ward" name="ward_code" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500" data-default-ward="{{ $defaultAddress?->ward ?? '' }}" @if ($defaultAddress?->ward) data-default-ward-name="{{ $defaultAddress->ward }}" @endif @if ($defaultAddress?->ward_code) data-default-ward-code="{{ $defaultAddress->ward_code }}" @endif>
                                                    <option value="">Chọn phường/xã</option>
                                                </select>
                                            </div>
                                        </div>
                                        <textarea id="address" name="recipient_address" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('recipient_address', $defaultAddress?->address_line ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú đơn hàng</label>
                                <textarea id="order-notes" name="order_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500" placeholder="Ghi chú thêm về đơn hàng (tùy chọn)"></textarea>
                            </div>
                        </div>
                        <div id="step1-next-btn-wrapper" class="mt-8 flex justify-end">
                            <button type="button" id="next-step-1" class="px-8 py-3 bg-gradient-to-r from-orange-400 to-orange-600 text-white rounded-full font-bold shadow-lg hover:from-orange-500 hover:to-orange-700 transition-all duration-200 flex items-center gap-2 text-lg border-2 border-orange-400 hover:border-orange-600">
                                <span class="inline-block"><i class="fas fa-arrow-right"></i></span>
                                <span>Bước tiếp theo</span>
                            </button>
                        </div>
                    </div>
                    {{-- STEP 2 --}}
                    <div id="checkout-step-2" class="checkout-content" style="display:none">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-xl font-semibold mb-6">Phương thức vận chuyển</h3>
                            <div class="space-y-4 mb-8">
                                @foreach ($shippingMethods->whereIn('id', [1,2]) as $method)
                                    <div class="payment-option border-2 border-gray-300 rounded-lg p-4 {{ $loop->first ? 'selected' : '' }} flex items-center" data-shipping="{{ $method->id }}">
                                        <input type="radio" id="shipping{{ $method->id }}" name="shipping_method" value="{{ $method->id }}" {{ $loop->first ? 'checked' : '' }} class="mr-3 accent-orange-500">
                                        <div class="flex-1">
                                            <label for="shipping{{ $method->id }}" class="font-medium cursor-pointer">{{ $method->name }}</label>
                                            <p class="text-sm text-gray-600">{{ $method->description }}</p>
                                        </div>
                                        <i class="fas fa-truck text-orange-600 text-xl"></i>
                                    </div>
                                @endforeach
                            </div>
                            <h3 class="text-xl font-semibold mb-6">Phương thức thanh toán</h3>
                            <div class="space-y-4">
                                <div class="payment-option border-2 border-gray-300 rounded-lg p-4 selected flex items-center" data-payment="cod">
                                    <input type="radio" id="cod" name="payment_method" value="cod" checked class="mr-3 accent-orange-500">
                                    <div class="flex-1">
                                        <label for="cod" class="font-medium cursor-pointer">Thanh toán khi nhận hàng (COD)</label>
                                        <p class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận được hàng</p>
                                    </div>
                                    <i class="fas fa-money-bill-wave text-orange-600 text-xl"></i>
                                </div>
                                <div class="payment-option border-2 border-gray-300 rounded-lg p-4 flex items-center" data-payment="bank_transfer">
                                    <input type="radio" id="banking" name="payment_method" value="bank_transfer" class="mr-3 accent-orange-500">
                                    <div class="flex-1">
                                        <label for="banking" class="font-medium cursor-pointer">Thanh toán VNPAY</label>
                                        <p class="text-sm text-gray-600">Thanh toán trực tuyến an toàn</p>
                                    </div>
                                    <i class="fas fa-university text-orange-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6 mt-6 flex justify-between">
                            <button type="button" id="prev-step-2" class="px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition flex items-center"><i class="fas fa-arrow-left mr-2"></i>Quay lại</button>
                            <button type="button" id="next-step-2" class="px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition flex items-center">Bước tiếp theo<i class="fas fa-arrow-right ml-2"></i></button>
                        </div>
                    </div>
                    {{-- STEP 3 --}}
                    <div id="checkout-step-3" class="checkout-content" style="display:none">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-xl font-semibold mb-6">Xác nhận đơn hàng</h3>
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h4 class="font-semibold mb-4">Thông tin giao hàng</h4>
                                <div id="delivery-summary" class="space-y-2 text-sm"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h4 class="font-semibold mb-4">Phương thức vận chuyển</h4>
                                <div id="shipping-summary" class="text-sm"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h4 class="font-semibold mb-4">Phương thức thanh toán</h4>
                                <div id="payment-summary" class="text-sm"></div>
                            </div>
                            <div class="flex items-center mb-6">
                                <input type="checkbox" id="agree-terms" required class="mr-3 accent-orange-500">
                                <label for="agree-terms" class="text-sm">Tôi đã đọc và đồng ý với <a href="#" class="text-orange-600 hover:underline">điều khoản và điều kiện</a> của website</label>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6 mt-6 flex justify-between">
                            <button type="button" id="prev-step-3" class="px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition flex items-center"><i class="fas fa-arrow-left mr-2"></i>Quay lại</button>
                            <button type="button" id="confirm-order" class="px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition flex items-center">Xác nhận<i class="fas fa-arrow-right ml-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- TÓM TẮT ĐƠN (1/3) --}}
            <div class="lg:col-span-1 order-1 lg:order-2">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h3 id="order-summary-title" class="text-xl font-semibold mb-4">Đơn hàng của bạn</h3>
                    <div id="checkout-items" class="space-y-4 mb-6">
                        @if (count($cartItems) > 0)
                            @foreach ($cartItems as $item)
                                @php
                                    $product = $item->product ?? null;
                                    $variant = $item->productVariant ?? null;
                                    $qty = (int) ($item->quantity ?? 1);
                                    $safeId = $item->cart_item_id ?? ($product?->id ? $product->id : $loop->index) . ':' . ($variant?->id ?? 0);
                                    $productName = $item->product_name ?? ($product?->name ?? 'Sản phẩm');
                                    $imagePath = $item->image ?? null;
                                    if (!$imagePath && $product && $product->productAllImages && $product->productAllImages->count() > 0) {
                                        $imagePath = 'uploads/products/' . $product->productAllImages->first()->image_path;
                                    }
                                    $isAbsolute = $imagePath ? preg_match('~^https?://|^//~', $imagePath) : false;
                                    if (isset($item->price)) {
                                        $displayPrice = (float) $item->price;
                                    } elseif ($variant) {
                                        $displayPrice = $variant->sale_price ?? ($variant->price ?? 0);
                                    } elseif ($product && $product->variants && $product->variants->count() > 0) {
                                        $v = $product->variants->first();
                                        $displayPrice = $v->sale_price ?? ($v->price ?? 0);
                                    } else {
                                        $displayPrice = $product?->sale_price ?? ($product?->price ?? 0);
                                    }
                                @endphp
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 checkout-item" data-cart-id="{{ $item->id ?? '' }}" data-item-id="{{ $safeId }}" data-unit-price="{{ $displayPrice }}" data-quantity="{{ $qty }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                            @php
                                                $imageUrl = '';
                                                if ($variant && $variant->image) {
                                                    $imageUrl = asset('storage/' . $variant->image);
                                                } elseif ($imagePath) {
                                                    $imageUrl = $isAbsolute ? $imagePath : asset($imagePath);
                                                }
                                            @endphp
                                            @if ($imageUrl)
                                                <img src="{{ $imageUrl }}" alt="{{ $productName }}" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                            @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 text-sm">{{ $productName }}</h4>
                                            @if (!empty($variant) && method_exists($variant, 'attributeValues'))
                                                <p class="text-xs text-gray-500">
                                                    @foreach ($variant->attributeValues as $value)
                                                        {{ $value->attribute->name }}: {{ $value->value }}@if (!$loop->last), @endif
                                                    @endforeach
                                                </p>
                                            @endif
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-orange-500 font-semibold text-sm">{{ number_format($displayPrice) }}₫</span>
                                                <span class="text-gray-500 text-sm">x {{ $qty }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-medium text-gray-900">{{ number_format($displayPrice * $qty) }}₫</span>
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
                    {{-- MÃ GIẢM GIÁ --}}
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
                    {{-- TỔNG TIỀN --}}
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between"><span>Tạm tính:</span><span id="subtotal">{{ number_format($subtotal) }}₫</span></div>
                        <div class="flex justify-between"><span>Phí vận chuyển:</span><span id="shipping-fee">{{ number_format(($subtotal ?? 0) >= 3000000 ? 0 : 50000) }}₫</span></div>
                        <div class="flex justify-between text-green-600" id="discount-row" style="display:none"><span>Giảm giá:</span><span id="discount-amount">-0₫</span></div>
                        <div class="flex justify-between text-lg font-semibold border-t pt-2"><span>Tổng cộng:</span><span id="total-amount" class="text-orange-600">{{ number_format($subtotal + (($subtotal ?? 0) >= 3000000 ? 0 : 50000)) }}₫</span></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('scripts')
    {{-- nếu bạn có script dùng chung, nạp ở layout; ở đây chỉ nạp cần thiết --}}
    <script src="{{ asset('assets/js/component-loader.js') }}"></script>

    <script>
        window.shippingMethods = @json($shippingMethods->pluck('name', 'id'));
    </script>

    <script>
        // Toggle hiển thị form nhập địa chỉ mới
        function toggleAddressForm(show) {
            var form = document.getElementById('add-address-form');
            if (!form) return;
            if (show) {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
                // Reset các trường nhập khi ẩn để tránh gửi dữ liệu thừa
                var inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(function(input) {
                    if (input.type === 'radio' || input.type === 'checkbox') input.checked = false;
                    else input.value = '';
                });
            }
        }
        /* ===== Helpers CSRF (fallback nếu thiếu meta) ===== */
        function csrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) return meta.getAttribute('content');
            const t = document.querySelector('input[name="_token"]');
            return t ? t.value : '';
        }

        /* ===================== COUPON ===================== */
        function applyCheckoutCoupon() {
            const input = document.getElementById('checkout-coupon-code');
            const msg = document.getElementById('checkout-coupon-message');
            if (!input) return;
            const code = (input.value || '').trim();
            input.classList.remove('border-red-500');
            msg.textContent = '';
            if (!code) {
                input.classList.add('border-red-500');
                msg.textContent = 'Nhập mã giảm giá';
                msg.className = 'mt-1 text-xs text-red-500';
                return;
            }
            const subtotal = Number(window.checkoutSubtotal || 0);
            msg.textContent = 'Đang kiểm tra...';
            msg.className = 'mt-1 text-xs text-gray-500';

            fetch('/api/apply-coupon', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken()
                    },
                    body: JSON.stringify({
                        coupon_code: code,
                        subtotal
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) {
                        localStorage.removeItem('appliedDiscount');
                        window.checkoutDiscount = 0;
                        input.classList.add('border-red-500');
                        msg.textContent = data.message || 'Mã giảm giá không hợp lệ';
                        msg.className = 'mt-1 text-xs text-red-500';
                        updateCheckoutTotal();
                        return;
                    }
                    if (data.coupon && data.coupon.min_order_value > 0 && subtotal < data.coupon.min_order_value) {
                        input.classList.add('border-red-500');
                        msg.textContent =
                            `Đơn hàng chưa đạt giá trị tối thiểu ${Number(data.coupon.min_order_value).toLocaleString('vi-VN')}₫`;
                        msg.className = 'mt-1 text-xs text-red-500';
                        return;
                    }
                    if (data.coupon && data.coupon.max_order_value > 0 && subtotal > data.coupon.max_order_value) {
                        input.classList.add('border-red-500');
                        msg.textContent =
                            `Đơn hàng vượt quá giá trị tối đa ${Number(data.coupon.max_order_value).toLocaleString('vi-VN')}₫`;
                        msg.className = 'mt-1 text-xs text-red-500';
                        return;
                    }
                    const rawAmount = Number(data.discount_amount || 0);
                    const amount = Math.min(Math.max(rawAmount, 0), subtotal);
                    window.checkoutDiscount = amount;
                    localStorage.setItem('appliedDiscount', JSON.stringify({
                        code,
                        amount,
                        details: {
                            min_order_value: data.coupon?.min_order_value || 0,
                            max_order_value: data.coupon?.max_order_value || 0,
                            discount_type: data.coupon?.discount_type || null,
                            value: data.coupon?.value || null
                        },
                        fromDatabase: true
                    }));
                    msg.textContent = data.coupon?.message || 'Áp dụng thành công';
                    msg.className = 'mt-1 text-xs text-green-600';
                    updateCheckoutTotal();
                })
                .catch(() => {
                    input.classList.add('border-red-500');
                    msg.textContent = 'Lỗi tải mã giảm giá, thử lại sau';
                    msg.className = 'mt-1 text-xs text-red-500';
                });
        }

        function clearCheckoutCoupon() {
            localStorage.removeItem('appliedDiscount');
            window.checkoutDiscount = 0;
            const input = document.getElementById('checkout-coupon-code');
            const msg = document.getElementById('checkout-coupon-message');
            if (input) input.value = '';
            if (msg) {
                msg.textContent = '';
                msg.className = 'mt-1 text-xs';
            }
            updateCheckoutTotal();
        }

        function toggleCouponListCheckout() {
            const box = document.getElementById('checkout-available-coupons');
            const btn = document.getElementById('toggle-coupon-list');
            if (!box || !btn) return;
            if (box.classList.contains('hidden')) {
                loadAvailableCouponsCheckout();
                box.classList.remove('hidden');
                btn.textContent = 'Ẩn';
            } else {
                box.classList.add('hidden');
                btn.textContent = 'Danh sách';
            }
        }

        function loadAvailableCouponsCheckout() {
            const box = document.getElementById('checkout-available-coupons');
            if (!box) return;
            const subtotal = Number(window.checkoutSubtotal || 0);
            fetch(`/api/coupons?subtotal=${subtotal}`).then(r => r.json()).then(data => {
                if (!data.success) {
                    box.innerHTML = '<p class="text-red-500">Lỗi tải</p>';
                    return;
                }
                if (!Array.isArray(data.coupons) || data.coupons.length === 0) {
                    box.innerHTML = '<p class="text-gray-500">Không có mã phù hợp</p>';
                    return;
                }
                const applied = (() => {
                    try {
                        const s = JSON.parse(localStorage.getItem('appliedDiscount'));
                        return s && s.code ? s.code : null;
                    } catch {
                        return null;
                    }
                })();
                box.innerHTML = data.coupons.map(c => {
                    const can = c.eligible;
                    const cls = can ? 'border-green-300 bg-white hover:border-orange-500 cursor-pointer' :
                        'border-gray-200 bg-gray-100 opacity-60 cursor-not-allowed';
                    const line = c.discount_type === 'percent' ? `Giảm ${c.value}%` :
                        `Giảm ${Number(c.value).toLocaleString()}₫`;
                    const reason = c.reason ? `(<span class='text-red-500'>${c.reason}</span>)` : '';
                    const selectedCls = applied && applied.toUpperCase() === c.code.toUpperCase() ?
                        'border-orange-500 coupon-selected' : '';
                    return `<div class="coupon-item border rounded p-2 ${cls} ${selectedCls}" data-code="${c.code}" data-eligible="${can}">
                        <div class='flex justify-between items-center'>
                            <span class='font-semibold'>${c.code}</span>
                            <span class='text-orange-600 font-medium'>${line}</span>
                        </div>
                        <div class='text-[10px] text-gray-600 mt-1'>${reason}</div>
                    </div>`;
                }).join('');
                box.querySelectorAll('.coupon-item').forEach(div => {
                    div.addEventListener('click', () => {
                        if (div.dataset.eligible !== 'true') return;
                        box.querySelectorAll('.coupon-item.coupon-selected').forEach(el => el
                            .classList.remove('coupon-selected', 'border-orange-500'));
                        div.classList.add('coupon-selected', 'border-orange-500');
                        const input = document.getElementById('checkout-coupon-code');
                        const msg = document.getElementById('checkout-coupon-message');
                        if (input) input.value = div.dataset.code;
                        if (msg) {
                            msg.textContent = 'Đã chọn mã, bấm Áp dụng để xác nhận';
                            msg.className = 'mt-1 text-xs text-gray-600';
                        }
                    });
                });
            }).catch(() => {
                box.innerHTML = '<p class="text-red-500">Lỗi tải mã</p>';
            });
        }

        /* ===================== TOTAL ===================== */
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(Number(amount || 0)) + '₫';
        }

        function updateCheckoutTotal() {
            const subtotal = Number(window.checkoutSubtotal || 0);
            const discount = Number(window.checkoutDiscount || 0);
            const method = document.querySelector('input[name="shipping_method"]:checked')?.value || '1';
            let shipping = 0;
            if (method == '1') {
                shipping = subtotal >= 3000000 ? 0 : 50000;
            } else {
                shipping = 0;
            }
            const total = Math.max(0, subtotal + shipping - discount);
            const subtotalEl = document.getElementById('subtotal');
            const shippingEl = document.getElementById('shipping-fee');
            const totalEl = document.getElementById('total-amount');
            const discountRow = document.getElementById('discount-row');
            const discountAmount = document.getElementById('discount-amount');
            if (subtotalEl) subtotalEl.textContent = formatCurrency(subtotal);
            if (shippingEl) shippingEl.textContent = formatCurrency(shipping);
            if (totalEl) totalEl.textContent = formatCurrency(total);
            if (discountRow && discountAmount) {
                if (discount > 0) {
                    discountRow.style.display = 'flex';
                    discountAmount.textContent = '-' + formatCurrency(discount);
                } else {
                    discountRow.style.display = 'none';
                }
            }
        }

        /* ===================== PAGE INIT ===================== */
        document.addEventListener('DOMContentLoaded', function() {
                let subtotal = {{ $subtotal ?? 0 }};
                window.checkoutSubtotal = subtotal;
                window.checkoutDiscount = 0;
                window.currentStep = 1;
                window.checkoutShippingMethod = document.querySelector('input[name="shipping_method"]:checked')
                    ?.value || 'home_delivery';

                try {
                    const saved = JSON.parse(localStorage.getItem('appliedDiscount') || 'null');
                    if (saved && saved.amount) {
                        window.checkoutDiscount = Math.min(Number(saved.amount) || 0, Number(window.checkoutSubtotal ||
                            0));
                        const input = document.getElementById('checkout-coupon-code');
                        const msg = document.getElementById('checkout-coupon-message');
                        if (input) input.value = saved.code || '';
                        if (msg) {
                            msg.innerHTML =
                                `<span class="text-green-600"><i class="fas fa-check mr-1"></i>Mã "${saved.code}" đã được áp dụng</span>`;
                            msg.className = 'mt-1 text-xs text-green-600';
                        }
                    }
                } catch {}

                // Khôi phục coupon từ session (khi thanh toán thất bại)
                @if (isset($restoredCoupon) && $restoredCoupon)
                    const restoredCoupon = @json($restoredCoupon);
                    if (restoredCoupon && restoredCoupon.amount) {
                        window.checkoutDiscount = Math.min(Number(restoredCoupon.amount) || 0, Number(window
                            .checkoutSubtotal || 0));
                        const input = document.getElementById('checkout-coupon-code');
                        const msg = document.getElementById('checkout-coupon-message');
                        if (input) input.value = restoredCoupon.code || '';
                        if (msg) {
                            msg.innerHTML =
                                `<span class="text-green-600"><i class="fas fa-check mr-1"></i>Mã "${restoredCoupon.code}" đã được khôi phục</span>`;
                            msg.className = 'mt-1 text-xs text-green-600';
                        }

                        // Lưu vào localStorage để duy trì trạng thái
                        localStorage.setItem('appliedDiscount', JSON.stringify({
                            code: restoredCoupon.code,
                            amount: restoredCoupon.amount,
                            details: restoredCoupon.details
                        }));

                        // Xóa session để tránh hiển thị lại khi refresh
                        fetch('{{ route('checkout.index') }}?clear_restored_coupon=1', {
                            method: 'GET'
                        });
                    }
                @endif

                updateCheckoutTotal();
                setupPaymentOptions();
                setupStepNavigation();
                loadProvinces();
                setupAddressDropdowns();
                setupShippingMethodListeners();
                setupRealTimeValidation();

                function setupStepNavigation() {
                    document.getElementById('next-step-1').addEventListener('click', () => {
                        if (validateStep1()) goToStep(2);
                    });
                    document.getElementById('prev-step-2').addEventListener('click', () => goToStep(1));
                    document.getElementById('next-step-2').addEventListener('click', () => {
                        if (validateStep2()) {
                            populateStep3Summary();
                            goToStep(3);
                        }
                    });
                    document.getElementById('prev-step-3').addEventListener('click', () => goToStep(2));
                    document.getElementById('confirm-order').addEventListener('click', () => {
                        if (validateStep3()) submitOrder();
                    });
                }

                function goToStep(step) {
                    document.querySelectorAll('.checkout-content').forEach(c => c.style.display = 'none');
                    if (step <= 3) document.getElementById(`checkout-step-${step}`).style.display = 'block';
                    updateStepIndicators(step);
                    window.currentStep = step;
                    updateStep1NextBtnVisibility(step); // Ensure button visibility syncs with step
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }

                function updateStepIndicators(active) {
                    for (let i = 1; i <= 3; i++) {
                        const stepEl = document.getElementById(`step-${i}`);
                        stepEl.classList.remove('active', 'completed');
                        stepEl.classList.add('bg-gray-200', 'text-gray-600');
                        const num = stepEl.querySelector('span.w-6.h-6');
                        if (num) {
                            num.classList.remove('bg-white', 'text-orange-600', 'bg-green-500');
                            num.classList.add('bg-gray-400', 'text-white');
                        }
                    }
                    for (let i = 1; i < active; i++) {
                        const stepEl = document.getElementById(`step-${i}`);
                        stepEl.classList.remove('bg-gray-200', 'text-gray-600');
                        stepEl.classList.add('completed', 'bg-green-500', 'text-white');
                        const num = stepEl.querySelector('span.w-6.h-6');
                        if (num) {
                            num.classList.remove('bg-gray-400', 'text-white');
                            num.classList.add('bg-white', 'text-green-500');
                        }
                    }
                    if (active <= 3) {
                        const act = document.getElementById(`step-${active}`);
                        act.classList.remove('bg-gray-200', 'text-gray-600');
                        act.classList.add('active', 'bg-orange-500', 'text-white');
                        const num = act.querySelector('span.w-6.h-6');
                        if (num) {
                            num.classList.remove('bg-gray-400', 'text-white');
                            num.classList.add('bg-white', 'text-orange-600');
                        }
                    }
                }

                function validateStep1() {
                    // Nếu chọn địa chỉ đã lưu thì không cần validate form nhập mới
                    var selected = document.querySelector('input[name="selected_address"]:checked');
                    if (selected && selected.value !== 'new') {
                        return true;
                    }
                    // Nếu chọn thêm địa chỉ mới thì validate như cũ
                    const required = ['fullname', 'phone', 'address', 'province', 'district', 'ward'];
                    let ok = true,
                        msgs = [];
                    required.forEach(id => {
                        const f = document.getElementById(id);
                        if (f && !f.value.trim()) {
                            f.classList.add('border-red-500');
                            ok = false;
                            if (id === 'fullname') msgs.push('Vui lòng nhập họ và tên');
                            if (id === 'phone') msgs.push('Vui lòng nhập số điện thoại');
                            if (id === 'address') msgs.push('Vui lòng nhập địa chỉ giao hàng');
                            if (id === 'province') msgs.push('Vui lòng chọn tỉnh/thành phố');
                            if (id === 'district') msgs.push('Vui lòng chọn quận/huyện');
                            if (id === 'ward') msgs.push('Vui lòng chọn phường/xã');
                        } else if (f) {
                            f.classList.remove('border-red-500');
                        }
                    });
                    const phoneField = document.getElementById('phone');
                    if (phoneField && phoneField.value.trim()) {
                        const phoneRegex = /^0[3-9][0-9]{8}$/;
                        if (!phoneRegex.test(phoneField.value.trim())) {
                            phoneField.classList.add('border-red-500');
                            msgs.push('Số điện thoại không đúng định dạng (VD: 0362729054)');
                            ok = false;
                        } else {
                            phoneField.classList.remove('border-red-500');
                        }
                    }
                    const emailField = document.getElementById('email');
                    if (emailField && !emailField.value.trim()) {
                        emailField.classList.add('border-red-500');
                        msgs.push('Vui lòng nhập email');
                        ok = false;
                    } else if (emailField && emailField.value.trim()) {
                        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                        if (!emailRegex.test(emailField.value.trim())) {
                            emailField.classList.add('border-red-500');
                            msgs.push('Email không đúng định dạng (VD: example@gmail.com)');
                            ok = false;
                        } else {
                            emailField.classList.remove('border-red-500');
                        }
                    }
                    if (!ok && msgs.length > 0) alert('Lỗi bước 1:\n' + msgs.join('\n'));
                    return ok;
                }

                function validateStep2() {
                    const pm = document.querySelector('input[name="payment_method"]:checked');
                    if (!pm) {
                        alert('Vui lòng chọn phương thức thanh toán');
                        return false;
                    }
                    return true;
                }

                function validateStep3() {
                    const agree = document.getElementById('agree-terms');
                    if (!agree.checked) {
                        alert('Vui lòng đồng ý với điều khoản và điều kiện');
                        return false;
                    }
                    return true;
                }

                function populateStep3Summary() {
                    const delivery = document.getElementById('delivery-summary');
                    const fullname = document.getElementById('fullname').value;
                    const phone = document.getElementById('phone').value;
                    let address = document.getElementById('address').value;
                    let province = '', district = '', ward = '';
                    const selectedAddressRadio = document.querySelector('input[name="selected_address"]:checked');
                    if (selectedAddressRadio && selectedAddressRadio.value !== 'new') {
                        ward = selectedAddressRadio.dataset.ward || '';
                        district = selectedAddressRadio.dataset.district || '';
                        province = selectedAddressRadio.dataset.city || '';
                        address = selectedAddressRadio.dataset.address || address;
                    } else {
                        province = document.getElementById('province').selectedOptions[0]?.text || '';
                        district = document.getElementById('district').selectedOptions[0]?.text || '';
                        ward = document.getElementById('ward').selectedOptions[0]?.text || '';
                    }
                    delivery.innerHTML = `<div><strong>Người nhận:</strong> ${fullname}</div>
                                      <div><strong>Số điện thoại:</strong> ${phone}</div>
                                      <div><strong>Địa chỉ:</strong> ${address}</div>
                                      <div><strong>Khu vực:</strong> ${ward}, ${district}, ${province}</div>`;

                    const shipping = document.getElementById('shipping-summary');
                    const sm = document.querySelector('input[name="shipping_method"]:checked');
                    let shippingText = 'Chưa chọn';
                    if (sm && window.shippingMethods) {
                        shippingText = window.shippingMethods[sm.value] || 'Chưa chọn';
                    }
                    shipping.innerHTML = `<div><strong>Phương thức:</strong> ${shippingText}</div>`;

                    const pay = document.getElementById('payment-summary');
                    const pm = document.querySelector('input[name="payment_method"]:checked');
                    const txt = pm?.value === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Thanh toán online';
                    pay.innerHTML = `<div><strong>Phương thức:</strong> ${txt}</div>`;
                }

                function setupPaymentOptions() {
                    const opts = document.querySelectorAll('.payment-option');
                    opts.forEach(op => {
                        op.addEventListener('click', function() {
                            opts.forEach(o => o.classList.remove('selected'));
                            this.classList.add('selected');
                            const radio = this.querySelector('input[type="radio"]');
                            if (radio) {
                                radio.checked = true;
                                radio.dispatchEvent(new Event('change'));
                            }
                        });
                    });
                }

                function setupShippingMethodListeners() {
                    document.querySelectorAll('input[name="shipping_method"]').forEach(r => {
                        r.addEventListener('change', () => {
                            window.checkoutShippingMethod = r.value || 'home_delivery';
                            updateCheckoutTotal();
                        });
                    });
                }

                function loadProvinces() {
                    const provinceSelect = document.getElementById('province');
                    if (!provinceSelect) return;
                    fetch('/api/provinces').then(res => res.json()).then(provinces => {
                            provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                            if (Array.isArray(provinces) && provinces.length) {
                                provinces.forEach(p => provinceSelect.innerHTML +=
                                    `<option value="${p.code}">${p.name}</option>`);
                                @auth
                                const userCity = @json($defaultAddress?->city);
                                if (userCity) {
                                    const opt = [...provinceSelect.options].find(o => o.text.trim()
                                    .toLowerCase() === userCity.trim().toLowerCase());
                                    if (opt) {
                                        provinceSelect.value = opt.value;
                                        provinceSelect.dispatchEvent(new Event('change'));
                                    }
                                }
                            @endauth
                        } else {
                            provinceSelect.innerHTML = '<option value="">Không có dữ liệu</option>';
                        }
                    }).catch(() => {
                    provinceSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                });
            }

            function setupAddressDropdowns() {
                const provinceSelect = document.getElementById('province');
                const districtSelect = document.getElementById('district');
                const wardSelect = document.getElementById('ward');
                if (!provinceSelect || !districtSelect || !wardSelect) return;

                provinceSelect.addEventListener('change', function() {
                        const code = this.value;
                        districtSelect.innerHTML = '<option value="">Đang tải...</option>';
                        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                        if (!code) {
                            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                            return;
                        }
                        fetch(`/api/districts/${code}`).then(r => {
                                if (!r.ok) throw new Error();
                                return r.json();
                            })
                            .then(ds => {
                                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                                    if (Array.isArray(ds) && ds.length) {
                                        ds.forEach(d => districtSelect.innerHTML +=
                                            `<option value="${d.code}">${d.name}</option>`);
                                        @auth
                                        const userDistrict = @json($defaultAddress?->district);
                                        if (userDistrict) {
                                            const opt = [...districtSelect.options].find(o => o.text.trim()
                                                .toLowerCase() === userDistrict.trim().toLowerCase());
                                            if (opt) {
                                                districtSelect.value = opt.value;
                                                districtSelect.dispatchEvent(new Event('change'));
                                            }
                                        }
                                    @endauth
                                } else {
                                    districtSelect.innerHTML = '<option value="">Không có dữ liệu</option>';
                                }
                            }).catch(() => {
                        districtSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                    });
                });

            districtSelect.addEventListener('change', function() {
                    const code = this.value;
                    wardSelect.innerHTML = '<option value="">Đang tải...</option>';
                    if (!code) {
                        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                        return;
                    }
                    fetch(`/api/wards/${code}`).then(r => {
                            if (!r.ok) throw new Error();
                            return r.json();
                        })
                        .then(ws => {
                                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                                if (Array.isArray(ws) && ws.length) {
                                    ws.forEach(w => wardSelect.innerHTML +=
                                        `<option value="${w.code}">${w.name}</option>`);
                                    @auth
                                    const userWard = @json($defaultAddress?->ward);
                                    if (userWard) {
                                        const opt = [...wardSelect.options].find(o => o.text.trim().toLowerCase() ===
                                            userWard.trim().toLowerCase());
                                        if (opt) wardSelect.value = opt.value;
                                    }
                                @endauth
                            } else {
                                wardSelect.innerHTML = '<option value="">Không có dữ liệu</option>';
                            }
                        }).catch(() => {
                    wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                });
            });
        }

        function setupRealTimeValidation() {
            const phoneField = document.getElementById('phone');
            if (phoneField) {
                phoneField.addEventListener('input', function() {
                    const value = this.value.trim();
                    const phoneRegex = /^0[3-9][0-9]{8}$/;
                    if (value && !phoneRegex.test(value)) {
                        this.classList.add('border-red-500');
                        this.title = 'Số điện thoại không đúng định dạng (VD: 0362729054)';
                    } else {
                        this.classList.remove('border-red-500');
                        this.title = '';
                    }
                });
            }

            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.addEventListener('input', function() {
                    const value = this.value.trim();
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    if (value && !emailRegex.test(value)) {
                        this.classList.add('border-red-500');
                        this.title = 'Email không đúng định dạng (VD: example@gmail.com)';
                    } else {
                        this.classList.remove('border-red-500');
                        this.title = '';
                    }
                });
            }
        }

        function validateFullname() {
            const fullname = document.getElementById('fullname');
            const msg = document.getElementById('fullname-error');
            const val = fullname.value.trim();
            if (!val) {
                msg.textContent = 'Vui lòng nhập họ và tên';
                fullname.classList.add('border-red-500');
                return false;
            }
            if (!/^([a-zA-ZÀ-ỹ\s]{2,})$/.test(val)) {
                msg.textContent = 'Họ tên chỉ được chứa ký tự chữ và khoảng trắng, tối thiểu 2 ký tự';
                fullname.classList.add('border-red-500');
                return false;
            }
            msg.textContent = '';
            fullname.classList.remove('border-red-500');
            return true;
        }

        function validateEmail() {
            const email = document.getElementById('email');
            const msg = document.getElementById('email-error');
            const val = email.value.trim();
            if (!val) {
                msg.textContent = 'Vui lòng nhập email';
                email.classList.add('border-red-500');
                return false;
            }
            // Chỉ cho phép 1 dấu chấm cuối cùng và sau đó là 2-6 ký tự, không có gì sau đó
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailRegex.test(val)) {
                msg.textContent = 'Email không đúng định dạng (VD: example@gmail.com)';
                email.classList.add('border-red-500');
                return false;
            }
            // Không cho phép thêm ký tự sau đuôi domain
            const lastDot = val.lastIndexOf('.');
            if (lastDot === -1 || lastDot === val.length - 1) {
                msg.textContent = 'Email không đúng định dạng (VD: example@gmail.com)';
                email.classList.add('border-red-500');
                return false;
            }
            const afterDot = val.substring(lastDot + 1);
            if (!/^[a-zA-Z]{2,6}$/.test(afterDot)) {
                msg.textContent = 'Email không đúng định dạng (VD: example@gmail.com)';
                email.classList.add('border-red-500');
                return false;
            }
            // Không cho phép ký tự nào sau đuôi domain
            if (val.length !== lastDot + afterDot.length + 1) {
                msg.textContent = 'Email không đúng định dạng (VD: example@gmail.com)';
                email.classList.add('border-red-500');
                return false;
            }
            // Không cho phép nhiều dấu chấm liên tiếp ở cuối
            if (/\.{2,}/.test(val.substring(lastDot))) {
                msg.textContent = 'Email không đúng định dạng (VD: example@gmail.com)';
                email.classList.add('border-red-500');
                return false;
            }
            msg.textContent = '';
            email.classList.remove('border-red-500');
            return true;
        }

        function validatePhone() {
            const phone = document.getElementById('phone');
            const msg = document.getElementById('phone-error');
            const val = phone.value.trim();
            if (!val) {
                msg.textContent = 'Vui lòng nhập số điện thoại';
                phone.classList.add('border-red-500');
                return false;
            }
            if (!/^0[3-9][0-9]{8}$/.test(val)) {
                msg.textContent = 'Số điện thoại không đúng định dạng (VD: 0362729054)';
                phone.classList.add('border-red-500');
                return false;
            }
            msg.textContent = '';
            phone.classList.remove('border-red-500');
            return true;
        }

        function submitOrder() {
            var selected = document.querySelector('input[name="selected_address"]:checked');
            const paymentEl = document.querySelector('input[name="payment_method"]:checked');
            const shippingEl = document.querySelector('input[name="shipping_method"]:checked');
            if (!paymentEl) return alert('Vui lòng chọn phương thức thanh toán');
            if (!shippingEl) return alert('Vui lòng chọn phương thức vận chuyển');

            const btn = document.getElementById('confirm-order');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            if (selected && selected.value !== 'new') {
                formData.append('selected_address', selected.value);
                formData.append('province', selected.dataset.city || '');
                formData.append('district', selected.dataset.district || '');
                formData.append('ward', selected.dataset.ward || '');
                formData.append('recipient_address', selected.dataset.address || '');
            } else {
                const fullname = document.getElementById('fullname').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const emailVal = (document.getElementById('email').value || '').trim();
                const address = document.getElementById('address').value.trim();
                const province = document.getElementById('province').selectedOptions[0]?.text || '';
                const district = document.getElementById('district').selectedOptions[0]?.text || '';
                const ward = document.getElementById('ward').selectedOptions[0]?.text || '';
                formData.append('recipient_name', fullname);
                formData.append('recipient_phone', phone);
                formData.append('recipient_email', emailVal);
                formData.append('recipient_address', address);
                formData.append('province', province);
                formData.append('district', district);
                formData.append('ward', ward);
            }
            formData.append('shipping_method_id', shippingEl.value); // value là id (1 hoặc 2)
            formData.append('payment_method', paymentEl.value);
            formData.append('order_notes', document.getElementById('order-notes').value || '');
            const couponInput = document.getElementById('checkout-coupon-code');
            if (couponInput && couponInput.value.trim()) {
                formData.append('coupon_code', couponInput.value.trim());
            }
            let selectedVal = document.getElementById('selected-input')?.value || '';
            if (!selectedVal) {
                const domItems = Array.from(document.querySelectorAll('.checkout-item'));
                if (domItems.length) {
                    const hasCartId = domItems.some(el => el.getAttribute('data-cart-id'));
                    selectedVal = domItems.map(el => hasCartId ? (el.getAttribute('data-cart-id') || '') :
                        (el.getAttribute('data-item-id') || ''))
                        .filter(Boolean).join(',');
                }
            }
            formData.append('selected', selectedVal);
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('checkout.process') }}';
            for (const [k, v] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = k;
                input.value = v;
                form.appendChild(input);
            }
            document.body.appendChild(form);
            form.submit();
        }
        });

        // Filter sản phẩm đã chọn
        function filterSelectedItems() {
            try {
                const urlParams = new URLSearchParams(window.location.search);
                const selectedParam = urlParams.get('selected');
                let selectedIds = [];

                if (selectedParam) {
                    selectedIds = selectedParam.split(',').map(id => id.trim()).filter(id => id);
                    if (selectedParam === '1' && selectedIds.length > 0) {
                        const checkoutItems = document.querySelectorAll('.checkout-item');
                        let hasMatchingItem = false;
                        checkoutItems.forEach(item => {
                            const cartId = item.getAttribute('data-cart-id');
                            const itemId = item.getAttribute('data-item-id');
                            for (let selectedId of selectedIds) {
                                if ((cartId && cartId === selectedId) ||
                                    (itemId && itemId === selectedId) ||
                                    (itemId && itemId.includes(':') && itemId.split(':')[0] === selectedId)) {
                                    hasMatchingItem = true;
                                    break;
                                }
                            }
                        });
                        if (!hasMatchingItem) {
                            try {
                                const stored = localStorage.getItem('checkout_selected_items');
                                if (stored) selectedIds = JSON.parse(stored);
                            } catch {}
                        }
                    }
                }

                if (selectedIds.length === 0) {
                    try {
                        const savedState = localStorage.getItem('checkout_state');
                        if (savedState) {
                            const state = JSON.parse(savedState);
                            if (state.selected) {
                                selectedIds = state.selected.split(',').map(id => id.trim()).filter(id => id);
                            }
                        }
                    } catch {}
                }

                if (selectedIds.length === 0) return;

                const checkoutItems = document.querySelectorAll('.checkout-item');
                let keptItems = [];
                checkoutItems.forEach(item => {
                    const cartId = item.getAttribute('data-cart-id');
                    const itemId = item.getAttribute('data-item-id');
                    let shouldKeep = false;

                    for (let selectedId of selectedIds) {
                        if (cartId && /^\d+$/.test(cartId) && cartId === selectedId) {
                            shouldKeep = true;
                            break;
                        }
                        if (itemId && itemId === selectedId) {
                            shouldKeep = true;
                            break;
                        }
                        if (itemId && itemId.includes(':')) {
                            const productId = itemId.split(':')[0];
                            if (productId === selectedId) {
                                shouldKeep = true;
                                break;
                            }
                        }
                        if (itemId && itemId.includes(':')) {
                            const converted = itemId.replace(':', '_');
                            if (converted === selectedId) {
                                shouldKeep = true;
                                break;
                            }
                        }
                        if (selectedId.includes('_')) {
                            const converted = selectedId.replace('_', ':');
                            if (itemId && itemId === converted) {
                                shouldKeep = true;
                                break;
                            }
                        }
                    }

                    if (shouldKeep) keptItems.push(item);
                    else item.remove();
                });

                if (keptItems.length === 0) {
                    const title = document.getElementById('order-summary-title');
                    if (title) title.textContent = 'Đơn hàng của bạn (không tìm thấy sản phẩm đã chọn)';
                    return;
                }

                let newSubtotal = 0;
                keptItems.forEach(item => {
                    const unitPrice = parseInt(item.getAttribute('data-unit-price')) || 0;
                    const quantity = parseInt(item.getAttribute('data-quantity')) || 1;
                    newSubtotal += unitPrice * quantity;
                });

                const subtotalEl = document.getElementById('subtotal');
                if (subtotalEl) subtotalEl.textContent = newSubtotal.toLocaleString('vi-VN') + '₫';

                const title = document.getElementById('order-summary-title');
                if (title) title.textContent = 'Đơn hàng (sản phẩm đã chọn)';

                const hiddenInput = document.getElementById('selected-input');
                if (hiddenInput) {
                    const validIds = selectedIds.filter(id => /^\d+$/.test(id));
                    hiddenInput.value = validIds.join(',');
                }

                if (typeof updateCheckoutTotal === 'function') {
                    window.checkoutSubtotal = newSubtotal;
                    updateCheckoutTotal();
                }
            } catch (error) {
                console.error('Lỗi trong filterSelectedItems:', error);
            }
        }

        // --- Thay đổi đoạn JS tự động chọn địa chỉ ---
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const provinceSelect = document.getElementById('province');
                const districtSelect = document.getElementById('district');
                const wardSelect = document.getElementById('ward');
                // Lấy tên từ data attribute
                const defaultCity = provinceSelect?.getAttribute('data-default-city-name');
                const defaultDistrict = districtSelect?.getAttribute('data-default-district-name');
                const defaultWard = wardSelect?.getAttribute('data-default-ward-name');
                // Chọn tỉnh/thành phố theo tên
                if (provinceSelect && defaultCity) {
                    const opt = [...provinceSelect.options].find(o => o.text.trim().toLowerCase() ===
                        defaultCity.trim().toLowerCase());
                    if (opt) {
                        provinceSelect.value = opt.value;
                        provinceSelect.dispatchEvent(new Event('change'));
                    }
                }
                setTimeout(function() {
                    if (districtSelect && defaultDistrict) {
                        const opt = [...districtSelect.options].find(o => o.text.trim()
                        .toLowerCase() === defaultDistrict.trim().toLowerCase());
                        if (opt) {
                            districtSelect.value = opt.value;
                            districtSelect.dispatchEvent(new Event('change'));
                        }
                    }
                    setTimeout(function() {
                        if (wardSelect && defaultWard) {
                            const opt = [...wardSelect.options].find(o => o.text.trim()
                                .toLowerCase() === defaultWard.trim().toLowerCase());
                            if (opt) wardSelect.value = opt.value;
                        }
                    }, 500);
                }, 500);
            }, 1000);
        });
        window.addEventListener('load', function() {
            setTimeout(filterSelectedItems, 200);
        });
        if (document.readyState !== 'loading') {
            setTimeout(filterSelectedItems, 50);
        }

        function clearPaymentMessage() {
            fetch('/clear-payment-message', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'Content-Type': 'application/json',
                },
            }).then(() => {
                const messageDiv = document.querySelector('[data-message="payment-cancelled"]');
                if (messageDiv) messageDiv.style.display = 'none';
            });
        }

        // Hide the step 1 next button except when on step 1
        function updateStep1NextBtnVisibility(currentStep) {
            var btnWrapper = document.getElementById('step1-next-btn-wrapper');
            if (btnWrapper) {
                btnWrapper.style.display = (currentStep === 1) ? '' : 'none';
            }
        }
        // Initial call
        updateStep1NextBtnVisibility(window.currentStep || 1);

    </script>
@endpush
