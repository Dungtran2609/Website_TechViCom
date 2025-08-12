@extends('client.layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <nav class="text-sm text-gray-500 mb-6">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
                        <i class="fas fa-chevron-right mx-2"></i>
                    </li>
                    <li class="text-gray-700">Giỏ hàng</li>
                </ol>
            </nav>

            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-8">Giỏ hàng của bạn</h1>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md">
                            @if (count($cartItems) > 0)
                                <div class="flex items-center justify-between px-6 pt-6 pb-3 border-b border-gray-100">
                                    <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                        <input type="checkbox" id="select-all-items"
                                            class="w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]">
                                        <span>Chọn tất cả ({{ count($cartItems) }})</span>
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <button id="delete-selected-btn"
                                            class="text-sm px-3 py-2 rounded bg-red-50 text-red-600 hover:bg-red-100 disabled:opacity-40"
                                            disabled>Xóa đã chọn</button>
                                        <button id="buy-selected-btn"
                                            class="text-sm px-3 py-2 rounded bg-[#ff6c2f] text-white hover:bg-[#e55a28] disabled:opacity-40"
                                            disabled>Mua đã chọn</button>
                                    </div>
                                </div>

                                <div class="p-6" id="cart-items-wrapper">
                                    @foreach ($cartItems as $item)
                                        @php
                                            $displayPrice = 0;
                                            if (isset($item->productVariant) && $item->productVariant) {
                                                $displayPrice =
                                                    $item->productVariant->sale_price ?? $item->productVariant->price;
                                            } elseif (isset($item->price)) {
                                                $displayPrice = $item->price;
                                            } elseif (
                                                $item->product->variants &&
                                                $item->product->variants->count() > 0
                                            ) {
                                                $variant = $item->product->variants->first();
                                                $displayPrice = $variant->sale_price ?? ($variant->price ?? 0);
                                            }
                                            $imagePath = null;
                                            if (
                                                isset($item->productVariant) &&
                                                $item->productVariant &&
                                                $item->productVariant->image
                                            ) {
                                                $imagePath = asset('storage/' . $item->productVariant->image);
                                            } elseif (
                                                isset($item->product->productAllImages) &&
                                                $item->product->productAllImages->count() > 0
                                            ) {
                                                $imgObj = $item->product->productAllImages->first();
                                                $imgField = $imgObj->image_path ?? null;
                                                if ($imgField) {
                                                    $imagePath = asset('storage/' . $imgField);
                                                }
                                            }
                                        @endphp

                                        <div class="flex items-center justify-between border-b border-gray-200 py-4 {{ $loop->last ? 'border-b-0' : '' }} cart-item"
                                            data-price="{{ (int) $displayPrice }}"
                                            data-quantity="{{ (int) $item->quantity }}" data-id="{{ $item->id }}">
                                            <div class="flex items-center space-x-4">
                                                <input type="checkbox"
                                                    class="item-checkbox w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]"
                                                    value="{{ $item->id }}">
                                                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                                    <img src="{{ $imagePath ?? asset('client_css/images/placeholder.svg') }}"
                                                        alt="{{ $item->product->name }}" class="w-full h-full object-cover"
                                                        onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                                    @if (isset($item->productVariant) && $item->productVariant)
                                                        <div class="text-sm text-gray-500">
                                                            @foreach ($item->productVariant->attributeValues as $attrValue)
                                                                {{ $attrValue->attribute->name }}:
                                                                {{ $attrValue->value }}{{ !$loop->last ? ', ' : '' }}
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="text-sm text-gray-500">
                                                        @if ($displayPrice > 0)
                                                            {{ number_format($displayPrice, 0, ',', '.') }}₫
                                                        @else
                                                            Liên hệ
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-4">
                                                <div class="flex items-center space-x-2">
                                                    <button
                                                        onclick="updateQuantity('{{ $item->id }}', {{ max(1, (int) $item->quantity - 1) }})"
                                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50">
                                                        <i class="fas fa-minus text-xs"></i>
                                                    </button>
                                                    <span class="w-8 text-center">{{ $item->quantity }}</span>
                                                    <button
                                                        onclick="updateQuantity('{{ $item->id }}', {{ (int) $item->quantity + 1 }})"
                                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50">
                                                        <i class="fas fa-plus text-xs"></i>
                                                    </button>
                                                </div>
                                                <button onclick="removeFromCart('{{ $item->id }}')"
                                                    class="text-red-500 hover:text-red-700 transition">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-8 text-center">
                                    <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Giỏ hàng trống</h3>
                                    <p class="text-gray-500 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                                    <a href="{{ route('home') }}"
                                        class="bg-[#ff6c2f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition">
                                        Tiếp tục mua sắm
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (count($cartItems) > 0)
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                                <h3 class="text-xl font-semibold mb-6">Tóm tắt đơn hàng</h3>

                                <div class="space-y-4 mb-6">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tạm tính:</span>
                                        <span class="font-medium" id="subtotal">0₫</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Phí vận chuyển:</span>
                                        <span class="font-medium" id="shipping-fee">Miễn phí</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Giảm giá:</span>
                                        <span class="font-medium text-green-600" id="discount">-0₫</span>
                                    </div>

                                    <hr class="border-gray-200">

                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Tổng cộng:</span>
                                        <span class="text-[#ff6c2f]" id="total">0₫</span>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mã giảm giá</label>
                                    <div class="flex items-center">
                                        <input type="text" id="discount-code" placeholder="Nhập mã giảm giá"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:border-[#ff6c2f]">
                                        <button id="apply-coupon-btn"
                                            class="bg-[#ff6c2f] text-white px-4 py-2 rounded-r-lg hover:bg-[#ff6c2f] transition">
                                            Áp dụng
                                        </button>
                                    </div>
                                </div>

                                <button onclick="proceedToCheckout()"
                                    class="w-full bg-[#ff6c2f] text-white py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition mb-4"
                                    id="checkout-all-btn">
                                    Thanh toán tất cả
                                </button>
                                <button onclick="proceedToCheckoutSelected()"
                                    class="w-full bg-gray-800 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition mb-4 hidden"
                                    id="checkout-selected-btn">
                                    Thanh toán sản phẩm đã chọn
                                </button>

                                <a href="{{ route('home') }}" class="block text-center text-[#ff6c2f] hover:underline">
                                    ← Tiếp tục mua sắm
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const VND = n => (Number(n) || 0).toLocaleString('vi-VN') + '₫';
            const getCsrf = () => (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) ||
                '';

            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                let bgColor = 'bg-green-500';
                if (type === 'error') bgColor = 'bg-red-500';
                if (type === 'info') bgColor = 'bg-blue-500';
                notification.className =
                    `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full ${bgColor}`;
                notification.textContent = message;
                document.body.appendChild(notification);
                setTimeout(() => {
                    notification.style.transform = 'translateX(0)';
                }, 50);
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            function updateCartCount() {
                fetch('{{ route('carts.count') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const el = document.querySelector('.cart-count');
                            if (el) {
                                el.textContent = data.count;
                                el.style.display = data.count > 0 ? 'flex' : 'none';
                            }
                        }
                    }).catch(() => {});
            }

            const getCheckedItems = () => Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb =>
                cb.closest('.cart-item'));

            function calcSelectedSubtotal() {
                return getCheckedItems().reduce((sum, item) => {
                    const price = parseFloat(item.dataset.price) || 0;
                    const qty = parseFloat(item.dataset.quantity) || 0;
                    return sum + price * qty;
                }, 0);
            }

            function renderSummary() {
                const selectedItems = getCheckedItems();
                const subtotal = calcSelectedSubtotal();
                const subtotalEl = document.getElementById('subtotal');
                const totalEl = document.getElementById('total');
                const discountEl = document.getElementById('discount');
                const applyBtn = document.getElementById('apply-coupon-btn');
                const checkoutAllBtn = document.getElementById('checkout-all-btn');
                const checkoutSelectedBtn = document.getElementById('checkout-selected-btn');

                if (selectedItems.length > 0) {
                    checkoutAllBtn.classList.add('hidden');
                    checkoutSelectedBtn.classList.remove('hidden');
                } else {
                    checkoutAllBtn.classList.remove('hidden');
                    checkoutSelectedBtn.classList.add('hidden');
                }

                let appliedDiscountData = null;
                try {
                    appliedDiscountData = JSON.parse(localStorage.getItem('appliedDiscount') || 'null');
                } catch (e) {}

                let discountAmount = 0;
                if (appliedDiscountData && appliedDiscountData.amount && subtotal > 0) {
                    const minOrderValue = appliedDiscountData.details?.min_order_value || 0;
                    const maxOrderValue = appliedDiscountData.details?.max_order_value || 0;
                    let isValid = true;
                    if (minOrderValue > 0 && subtotal < minOrderValue) isValid = false;
                    if (maxOrderValue > 0 && subtotal > maxOrderValue) isValid = false;

                    if (isValid) {
                        discountAmount = Math.min(Number(appliedDiscountData.amount) || 0, subtotal);
                        discountEl.textContent = `-${VND(discountAmount)}`;
                        discountEl.classList.add('text-green-600');
                        const codeInput = document.getElementById('discount-code');
                        if (codeInput) {
                            const clearBtn = codeInput.parentNode.querySelector('button:last-child');
                            if (clearBtn && clearBtn.innerHTML === '×') clearBtn.style.display = '';
                            codeInput.disabled = true;
                        }
                    } else {
                        clearDiscountCode(true);
                        discountEl.textContent = '-0₫';
                        discountEl.classList.add('text-green-600');
                        applyBtn.disabled = subtotal <= 0;
                        return;
                    }
                } else {
                    discountEl.textContent = '-0₫';
                    discountEl.classList.add('text-green-600');
                    applyBtn.disabled = subtotal <= 0;
                    const codeInput = document.getElementById('discount-code');
                    if (codeInput) {
                        const clearBtn = codeInput.parentNode.querySelector('button:last-child');
                        if (clearBtn && clearBtn.innerHTML === '×') clearBtn.style.display = 'none';
                    }
                }

                if (subtotalEl) subtotalEl.textContent = VND(subtotal);
                if (totalEl) totalEl.textContent = VND(Math.max(subtotal - discountAmount, 0));
                if (applyBtn) applyBtn.disabled = subtotal <= 0 || (appliedDiscountData && appliedDiscountData
                    .code);
            }

            function updateQuantity(itemId, newQuantity) {
                if (newQuantity <= 0) {
                    removeFromCart(itemId);
                    return;
                }
                fetch(`/client/carts/${itemId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrf()
                        },
                        body: JSON.stringify({
                            quantity: newQuantity
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message || 'Cập nhật số lượng thành công', 'success');
                            const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
                            if (cartItem) {
                                const quantityEl = cartItem.querySelector('span.w-8.text-center');
                                if (quantityEl) quantityEl.textContent = newQuantity;
                                cartItem.dataset.quantity = newQuantity;
                                const checkbox = cartItem.querySelector('.item-checkbox');
                                if (checkbox && checkbox.checked) renderSummary();
                                updateCartCount();
                            }
                        } else {
                            showNotification(data.message || 'Có lỗi xảy ra khi cập nhật số lượng', 'error');
                        }
                    })
                    .catch(() => showNotification('Có lỗi kết nối', 'error'));
            }

            function removeFromCart(itemId) {
                let silent = false;
                if (typeof itemId === 'object' && itemId !== null) {
                    silent = itemId.silent;
                    itemId = itemId.id;
                }
                if (!silent && !confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) return;
                return fetch(`/client/carts/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrf()
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            if (!silent) showNotification(data.message || 'Đã xóa sản phẩm thành công',
                                'success');
                            const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
                            if (cartItem) cartItem.remove();
                            const remainingItems = document.querySelectorAll('#cart-items-wrapper .cart-item')
                                .length;
                            if (remainingItems === 0) {
                                document.getElementById('cart-items-wrapper').innerHTML = `
                            <div class="p-8 text-center">
                                <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">Giỏ hàng trống</h3>
                                <p class="text-gray-500 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                                <a href="{{ route('home') }}" class="bg-[#ff6c2f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition">
                                    Tiếp tục mua sắm
                                </a>
                            </div>
                        `;
                                const orderSummarySection = document.querySelector('.lg\\:col-span-1');
                                if (orderSummarySection) orderSummarySection.style.display = 'none';
                            }
                            updateCartCount();
                            renderSummary();
                            initSelectionFeatures();
                        } else {
                            if (!silent) showNotification(data.message || 'Có lỗi xảy ra khi xóa sản phẩm',
                                'error');
                        }
                    })
                    .catch(() => {
                        if (!silent) showNotification('Có lỗi kết nối', 'error');
                    });
            }

            function applyDiscountCode() {
                const codeInput = document.getElementById('discount-code');
                const code = codeInput.value.trim();
                codeInput.classList.remove('border-red-500');

                const selectedIds = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb
                    .value);
                if (selectedIds.length === 0) {
                    codeInput.classList.add('border-red-500');
                    showNotification('Vui lòng chọn sản phẩm trước khi áp mã', 'error');
                    return;
                }
                if (!code) {
                    codeInput.classList.add('border-red-500');
                    showNotification('Vui lòng nhập mã giảm giá', 'error');
                    return;
                }

                const subtotal = calcSelectedSubtotal();
                if (subtotal <= 0) {
                    codeInput.classList.add('border-red-500');
                    showNotification('Giá trị đơn hàng đang là 0₫', 'error');
                    return;
                }

                showNotification('🔄 Đang kiểm tra mã giảm giá...', 'info');
                fetch('/api/apply-coupon', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrf()
                        },
                        body: JSON.stringify({
                            coupon_code: code,
                            subtotal,
                            item_ids: selectedIds
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) {
                            codeInput.classList.add('border-red-500');
                            showNotification(`❌ ${data.message || 'Mã giảm giá không hợp lệ'}`, 'error');
                            return;
                        }

                        if (data.coupon && data.coupon.min_order_value > 0 && subtotal < data.coupon
                            .min_order_value) {
                            codeInput.classList.add('border-red-500');
                            showNotification(
                                `❌ Đơn hàng chưa đạt tối thiểu ${Number(data.coupon.min_order_value).toLocaleString('vi-VN')}₫`,
                                'error');
                            return;
                        }
                        if (data.coupon && data.coupon.max_order_value > 0 && subtotal > data.coupon
                            .max_order_value) {
                            codeInput.classList.add('border-red-500');
                            showNotification(
                                `❌ Đơn hàng vượt quá tối đa ${Number(data.coupon.max_order_value).toLocaleString('vi-VN')}₫`,
                                'error');
                            return;
                        }

                        const discountAmount = Number(data.discount_amount) || 0;
                        localStorage.setItem('appliedDiscount', JSON.stringify({
                            code,
                            amount: discountAmount,
                            details: data.coupon,
                            fromDatabase: true
                        }));

                        document.getElementById('discount').textContent = `-${VND(discountAmount)}`;
                        renderSummary();

                        showNotification(`✅ ${data.coupon?.message || 'Áp dụng thành công'}`, 'success');
                        codeInput.disabled = true;

                        const clearBtn = document.createElement('button');
                        clearBtn.innerHTML = '×';
                        clearBtn.className = 'ml-2 text-red-500 hover:text-red-700 font-bold';
                        clearBtn.onclick = () => clearDiscountCode(false);
                        codeInput.parentNode.appendChild(clearBtn);
                    })
                    .catch(() => {
                        codeInput.classList.add('border-red-500');
                        showNotification('❌ Lỗi tải mã giảm giá, thử lại sau', 'error');
                    });
            }

            function clearDiscountCode(silent = false) {
                const input = document.getElementById('discount-code');
                input.value = '';
                input.disabled = false;

                const discountEl = document.getElementById('discount');
                discountEl.textContent = '-0₫';
                discountEl.classList.add('text-green-600');

                localStorage.removeItem('appliedDiscount');

                const clearBtn = input.parentNode.querySelector('button:last-child');
                if (clearBtn && clearBtn.innerHTML === '×') clearBtn.remove();

                renderSummary();
                if (!silent) showNotification('🗑️ Đã xóa mã giảm giá', 'info');
            }

            function initSelectionFeatures() {
                const selectAll = document.getElementById('select-all-items');
                const itemCheckboxes = document.querySelectorAll('.item-checkbox');
                const deleteBtn = document.getElementById('delete-selected-btn');
                const buyBtn = document.getElementById('buy-selected-btn');

                if (!selectAll) return;

                function updateButtonsState() {
                    const checked = Array.from(itemCheckboxes).filter(cb => cb.checked);
                    const anyChecked = checked.length > 0;
                    deleteBtn.disabled = !anyChecked;
                    buyBtn.disabled = !anyChecked;
                    renderSummary();
                }

                const onSelectionChanged = () => {
                    if (localStorage.getItem('appliedDiscount')) {
                        clearDiscountCode(true);
                        showNotification('Giỏ hàng thay đổi, vui lòng áp lại mã giảm giá', 'info');
                    }
                    updateButtonsState();
                };

                selectAll.addEventListener('change', () => {
                    itemCheckboxes.forEach(cb => cb.checked = selectAll.checked);
                    onSelectionChanged();
                });

                itemCheckboxes.forEach(cb => cb.addEventListener('change', () => {
                    const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
                    selectAll.checked = allChecked;
                    onSelectionChanged();
                }));

                deleteBtn.addEventListener('click', () => {
                    const ids = Array.from(itemCheckboxes).filter(c => c.checked).map(c => c.value);
                    if (ids.length === 0) return;
                    if (!confirm('Bạn có chắc chắn muốn xóa các sản phẩm đã chọn?')) return;
                    Promise.all(ids.map(id => removeFromCart({
                        id,
                        silent: true
                    }))).then(() => {
                        showNotification('Đã xóa các sản phẩm thành công', 'success');
                        location.reload();
                    });
                });

                buyBtn.addEventListener('click', proceedToCheckoutSelected);

                selectAll.checked = false;
                itemCheckboxes.forEach(cb => {
                    cb.checked = false;
                });
                updateButtonsState();
            }

            function proceedToCheckout() {
                window.location.href = '{{ route('checkout.index') }}';
            }

            function proceedToCheckoutSelected() {
                const checked = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
                if (checked.length === 0) {
                    showNotification('Vui lòng chọn sản phẩm để thanh toán', 'error');
                    return;
                }
                localStorage.setItem('checkout_selected_items', JSON.stringify(checked));
                window.location.href = '{{ route('checkout.index') }}?selected=1';
            }

            renderSummary();
            updateCartCount();
            initSelectionFeatures();
            document.getElementById('apply-coupon-btn')?.addEventListener('click', applyDiscountCode);
        });
    </script>
    @endsection@extends('client.layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <nav class="text-sm text-gray-500 mb-6">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
                        <i class="fas fa-chevron-right mx-2"></i>
                    </li>
                    <li class="text-gray-700">Giỏ hàng</li>
                </ol>
            </nav>

            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-8">Giỏ hàng của bạn</h1>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md">
                            @if (count($cartItems) > 0)
                                <div class="flex items-center justify-between px-6 pt-6 pb-3 border-b border-gray-100">
                                    <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                        <input type="checkbox" id="select-all-items"
                                            class="w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]">
                                        <span>Chọn tất cả ({{ count($cartItems) }})</span>
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <button id="delete-selected-btn"
                                            class="text-sm px-3 py-2 rounded bg-red-50 text-red-600 hover:bg-red-100 disabled:opacity-40"
                                            disabled>Xóa đã chọn</button>
                                        <button id="buy-selected-btn"
                                            class="text-sm px-3 py-2 rounded bg-[#ff6c2f] text-white hover:bg-[#e55a28] disabled:opacity-40"
                                            disabled>Mua đã chọn</button>
                                    </div>
                                </div>

                                <div class="p-6" id="cart-items-wrapper">
                                    @foreach ($cartItems as $item)
                                        @php
                                            $displayPrice = 0;
                                            if (isset($item->productVariant) && $item->productVariant) {
                                                $displayPrice =
                                                    $item->productVariant->sale_price ?? $item->productVariant->price;
                                            } elseif (isset($item->price)) {
                                                $displayPrice = $item->price;
                                            } elseif (
                                                $item->product->variants &&
                                                $item->product->variants->count() > 0
                                            ) {
                                                $variant = $item->product->variants->first();
                                                $displayPrice = $variant->sale_price ?? ($variant->price ?? 0);
                                            }
                                            $imagePath = null;
                                            if (
                                                isset($item->productVariant) &&
                                                $item->productVariant &&
                                                $item->productVariant->image
                                            ) {
                                                $imagePath = asset('storage/' . $item->productVariant->image);
                                            } elseif (
                                                isset($item->product->productAllImages) &&
                                                $item->product->productAllImages->count() > 0
                                            ) {
                                                $imgObj = $item->product->productAllImages->first();
                                                $imgField = $imgObj->image_path ?? null;
                                                if ($imgField) {
                                                    $imagePath = asset('storage/' . $imgField);
                                                }
                                            }
                                        @endphp

                                        <div class="flex items-center justify-between border-b border-gray-200 py-4 {{ $loop->last ? 'border-b-0' : '' }} cart-item"
                                            data-price="{{ (int) $displayPrice }}"
                                            data-quantity="{{ (int) $item->quantity }}" data-id="{{ $item->id }}">
                                            <div class="flex items-center space-x-4">
                                                <input type="checkbox"
                                                    class="item-checkbox w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]"
                                                    value="{{ $item->id }}">
                                                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                                    <img src="{{ $imagePath ?? asset('client_css/images/placeholder.svg') }}"
                                                        alt="{{ $item->product->name }}"
                                                        class="w-full h-full object-cover"
                                                        onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                                    @if (isset($item->productVariant) && $item->productVariant)
                                                        <div class="text-sm text-gray-500">
                                                            @foreach ($item->productVariant->attributeValues as $attrValue)
                                                                {{ $attrValue->attribute->name }}:
                                                                {{ $attrValue->value }}{{ !$loop->last ? ', ' : '' }}
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="text-sm text-gray-500">
                                                        @if ($displayPrice > 0)
                                                            {{ number_format($displayPrice, 0, ',', '.') }}₫
                                                        @else
                                                            Liên hệ
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-4">
                                                <div class="flex items-center space-x-2">
                                                    <button
                                                        onclick="updateQuantity('{{ $item->id }}', {{ max(1, (int) $item->quantity - 1) }})"
                                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50">
                                                        <i class="fas fa-minus text-xs"></i>
                                                    </button>
                                                    <span class="w-8 text-center">{{ $item->quantity }}</span>
                                                    <button
                                                        onclick="updateQuantity('{{ $item->id }}', {{ (int) $item->quantity + 1 }})"
                                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50">
                                                        <i class="fas fa-plus text-xs"></i>
                                                    </button>
                                                </div>
                                                <button onclick="removeFromCart('{{ $item->id }}')"
                                                    class="text-red-500 hover:text-red-700 transition">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-8 text-center">
                                    <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Giỏ hàng trống</h3>
                                    <p class="text-gray-500 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                                    <a href="{{ route('home') }}"
                                        class="bg-[#ff6c2f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition">
                                        Tiếp tục mua sắm
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (count($cartItems) > 0)
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                                <h3 class="text-xl font-semibold mb-6">Tóm tắt đơn hàng</h3>

                                <div class="space-y-4 mb-6">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tạm tính:</span>
                                        <span class="font-medium" id="subtotal">0₫</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Phí vận chuyển:</span>
                                        <span class="font-medium" id="shipping-fee">Miễn phí</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Giảm giá:</span>
                                        <span class="font-medium text-green-600" id="discount">-0₫</span>
                                    </div>

                                    <hr class="border-gray-200">

                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Tổng cộng:</span>
                                        <span class="text-[#ff6c2f]" id="total">0₫</span>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mã giảm giá</label>
                                    <div class="flex items-center">
                                        <input type="text" id="discount-code" placeholder="Nhập mã giảm giá"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:border-[#ff6c2f]">
                                        <button id="apply-coupon-btn"
                                            class="bg-[#ff6c2f] text-white px-4 py-2 rounded-r-lg hover:bg-[#ff6c2f] transition">
                                            Áp dụng
                                        </button>
                                    </div>
                                </div>

                                <button onclick="proceedToCheckout()"
                                    class="w-full bg-[#ff6c2f] text-white py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition mb-4"
                                    id="checkout-all-btn">
                                    Thanh toán tất cả
                                </button>
                                <button onclick="proceedToCheckoutSelected()"
                                    class="w-full bg-gray-800 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition mb-4 hidden"
                                    id="checkout-selected-btn">
                                    Thanh toán sản phẩm đã chọn
                                </button>

                                <a href="{{ route('home') }}" class="block text-center text-[#ff6c2f] hover:underline">
                                    ← Tiếp tục mua sắm
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const VND = n => (Number(n) || 0).toLocaleString('vi-VN') + '₫';
            const getCsrf = () => (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) ||
                '';

            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                let bgColor = 'bg-green-500';
                if (type === 'error') bgColor = 'bg-red-500';
                if (type === 'info') bgColor = 'bg-blue-500';
                notification.className =
                    `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full ${bgColor}`;
                notification.textContent = message;
                document.body.appendChild(notification);
                setTimeout(() => {
                    notification.style.transform = 'translateX(0)';
                }, 50);
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            function updateCartCount() {
                fetch('{{ route('carts.count') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const el = document.querySelector('.cart-count');
                            if (el) {
                                el.textContent = data.count;
                                el.style.display = data.count > 0 ? 'flex' : 'none';
                            }
                        }
                    }).catch(() => {});
            }

            const getCheckedItems = () => Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb =>
                cb.closest('.cart-item'));

            function calcSelectedSubtotal() {
                return getCheckedItems().reduce((sum, item) => {
                    const price = parseFloat(item.dataset.price) || 0;
                    const qty = parseFloat(item.dataset.quantity) || 0;
                    return sum + price * qty;
                }, 0);
            }

            function renderSummary() {
                const selectedItems = getCheckedItems();
                const subtotal = calcSelectedSubtotal();
                const subtotalEl = document.getElementById('subtotal');
                const totalEl = document.getElementById('total');
                const discountEl = document.getElementById('discount');
                const applyBtn = document.getElementById('apply-coupon-btn');
                const checkoutAllBtn = document.getElementById('checkout-all-btn');
                const checkoutSelectedBtn = document.getElementById('checkout-selected-btn');

                if (selectedItems.length > 0) {
                    checkoutAllBtn.classList.add('hidden');
                    checkoutSelectedBtn.classList.remove('hidden');
                } else {
                    checkoutAllBtn.classList.remove('hidden');
                    checkoutSelectedBtn.classList.add('hidden');
                }

                let appliedDiscountData = null;
                try {
                    appliedDiscountData = JSON.parse(localStorage.getItem('appliedDiscount') || 'null');
                } catch (e) {}

                let discountAmount = 0;
                if (appliedDiscountData && appliedDiscountData.amount && subtotal > 0) {
                    const minOrderValue = appliedDiscountData.details?.min_order_value || 0;
                    const maxOrderValue = appliedDiscountData.details?.max_order_value || 0;
                    let isValid = true;
                    if (minOrderValue > 0 && subtotal < minOrderValue) isValid = false;
                    if (maxOrderValue > 0 && subtotal > maxOrderValue) isValid = false;

                    if (isValid) {
                        discountAmount = Math.min(Number(appliedDiscountData.amount) || 0, subtotal);
                        discountEl.textContent = `-${VND(discountAmount)}`;
                        discountEl.classList.add('text-green-600');
                        const codeInput = document.getElementById('discount-code');
                        if (codeInput) {
                            const clearBtn = codeInput.parentNode.querySelector('button:last-child');
                            if (clearBtn && clearBtn.innerHTML === '×') clearBtn.style.display = '';
                            codeInput.disabled = true;
                        }
                    } else {
                        clearDiscountCode(true);
                        discountEl.textContent = '-0₫';
                        discountEl.classList.add('text-green-600');
                        applyBtn.disabled = subtotal <= 0;
                        return;
                    }
                } else {
                    discountEl.textContent = '-0₫';
                    discountEl.classList.add('text-green-600');
                    applyBtn.disabled = subtotal <= 0;
                    const codeInput = document.getElementById('discount-code');
                    if (codeInput) {
                        const clearBtn = codeInput.parentNode.querySelector('button:last-child');
                        if (clearBtn && clearBtn.innerHTML === '×') clearBtn.style.display = 'none';
                    }
                }

                if (subtotalEl) subtotalEl.textContent = VND(subtotal);
                if (totalEl) totalEl.textContent = VND(Math.max(subtotal - discountAmount, 0));
                if (applyBtn) applyBtn.disabled = subtotal <= 0 || (appliedDiscountData && appliedDiscountData
                    .code);
            }

            function updateQuantity(itemId, newQuantity) {
                if (newQuantity <= 0) {
                    removeFromCart(itemId);
                    return;
                }
                fetch(`/client/carts/${itemId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrf()
                        },
                        body: JSON.stringify({
                            quantity: newQuantity
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message || 'Cập nhật số lượng thành công', 'success');
                            const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
                            if (cartItem) {
                                const quantityEl = cartItem.querySelector('span.w-8.text-center');
                                if (quantityEl) quantityEl.textContent = newQuantity;
                                cartItem.dataset.quantity = newQuantity;
                                const checkbox = cartItem.querySelector('.item-checkbox');
                                if (checkbox && checkbox.checked) renderSummary();
                                updateCartCount();
                            }
                        } else {
                            showNotification(data.message || 'Có lỗi xảy ra khi cập nhật số lượng', 'error');
                        }
                    })
                    .catch(() => showNotification('Có lỗi kết nối', 'error'));
            }

            function removeFromCart(itemId) {
                if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) return;
                fetch(`/client/carts/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrf()
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message || 'Đã xóa sản phẩm thành công', 'success');
                            const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
                            if (cartItem) cartItem.remove();
                            const remainingItems = document.querySelectorAll('#cart-items-wrapper .cart-item')
                                .length;
                            if (remainingItems === 0) {
                                document.getElementById('cart-items-wrapper').innerHTML = `
                            <div class="p-8 text-center">
                                <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">Giỏ hàng trống</h3>
                                <p class="text-gray-500 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                                <a href="{{ route('home') }}" class="bg-[#ff6c2f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition">
                                    Tiếp tục mua sắm
                                </a>
                            </div>
                        `;
                                const orderSummarySection = document.querySelector('.lg\\:col-span-1');
                                if (orderSummarySection) orderSummarySection.style.display = 'none';
                            }
                            updateCartCount();
                            renderSummary();
                            initSelectionFeatures();
                        } else {
                            showNotification(data.message || 'Có lỗi xảy ra khi xóa sản phẩm', 'error');
                        }
                    })
                    .catch(() => showNotification('Có lỗi kết nối', 'error'));
            }

            function applyDiscountCode() {
                const codeInput = document.getElementById('discount-code');
                const code = codeInput.value.trim();
                codeInput.classList.remove('border-red-500');

                const selectedIds = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb
                    .value);
                if (selectedIds.length === 0) {
                    codeInput.classList.add('border-red-500');
                    showNotification('Vui lòng chọn sản phẩm trước khi áp mã', 'error');
                    return;
                }
                if (!code) {
                    codeInput.classList.add('border-red-500');
                    showNotification('Vui lòng nhập mã giảm giá', 'error');
                    return;
                }

                const subtotal = calcSelectedSubtotal();
                if (subtotal <= 0) {
                    codeInput.classList.add('border-red-500');
                    showNotification('Giá trị đơn hàng đang là 0₫', 'error');
                    return;
                }

                showNotification('🔄 Đang kiểm tra mã giảm giá...', 'info');
                fetch('/api/apply-coupon', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrf()
                        },
                        body: JSON.stringify({
                            coupon_code: code,
                            subtotal,
                            item_ids: selectedIds
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) {
                            codeInput.classList.add('border-red-500');
                            showNotification(`❌ ${data.message || 'Mã giảm giá không hợp lệ'}`, 'error');
                            return;
                        }

                        if (data.coupon && data.coupon.min_order_value > 0 && subtotal < data.coupon
                            .min_order_value) {
                            codeInput.classList.add('border-red-500');
                            showNotification(
                                `❌ Đơn hàng chưa đạt tối thiểu ${Number(data.coupon.min_order_value).toLocaleString('vi-VN')}₫`,
                                'error');
                            return;
                        }
                        if (data.coupon && data.coupon.max_order_value > 0 && subtotal > data.coupon
                            .max_order_value) {
                            codeInput.classList.add('border-red-500');
                            showNotification(
                                `❌ Đơn hàng vượt quá tối đa ${Number(data.coupon.max_order_value).toLocaleString('vi-VN')}₫`,
                                'error');
                            return;
                        }

                        const discountAmount = Number(data.discount_amount) || 0;
                        localStorage.setItem('appliedDiscount', JSON.stringify({
                            code,
                            amount: discountAmount,
                            details: data.coupon,
                            fromDatabase: true
                        }));

                        document.getElementById('discount').textContent = `-${VND(discountAmount)}`;
                        renderSummary();

                        showNotification(`✅ ${data.coupon?.message || 'Áp dụng thành công'}`, 'success');
                        codeInput.disabled = true;

                        const clearBtn = document.createElement('button');
                        clearBtn.innerHTML = '×';
                        clearBtn.className = 'ml-2 text-red-500 hover:text-red-700 font-bold';
                        clearBtn.onclick = () => clearDiscountCode(false);
                        codeInput.parentNode.appendChild(clearBtn);
                    })
                    .catch(() => {
                        codeInput.classList.add('border-red-500');
                        showNotification('❌ Lỗi tải mã giảm giá, thử lại sau', 'error');
                    });
            }

            function clearDiscountCode(silent = false) {
                const input = document.getElementById('discount-code');
                input.value = '';
                input.disabled = false;

                const discountEl = document.getElementById('discount');
                discountEl.textContent = '-0₫';
                discountEl.classList.add('text-green-600');

                localStorage.removeItem('appliedDiscount');

                const clearBtn = input.parentNode.querySelector('button:last-child');
                if (clearBtn && clearBtn.innerHTML === '×') clearBtn.remove();

                renderSummary();
                if (!silent) showNotification('🗑️ Đã xóa mã giảm giá', 'info');
            }

            function initSelectionFeatures() {
                const selectAll = document.getElementById('select-all-items');
                const itemCheckboxes = document.querySelectorAll('.item-checkbox');
                const deleteBtn = document.getElementById('delete-selected-btn');
                const buyBtn = document.getElementById('buy-selected-btn');

                if (!selectAll) return;

                function updateButtonsState() {
                    const checked = Array.from(itemCheckboxes).filter(cb => cb.checked);
                    const anyChecked = checked.length > 0;
                    deleteBtn.disabled = !anyChecked;
                    buyBtn.disabled = !anyChecked;
                    renderSummary();
                }

                const onSelectionChanged = () => {
                    if (localStorage.getItem('appliedDiscount')) {
                        clearDiscountCode(true);
                        showNotification('Giỏ hàng thay đổi, vui lòng áp lại mã giảm giá', 'info');
                    }
                    updateButtonsState();
                };

                selectAll.addEventListener('change', () => {
                    itemCheckboxes.forEach(cb => cb.checked = selectAll.checked);
                    onSelectionChanged();
                });

                itemCheckboxes.forEach(cb => cb.addEventListener('change', () => {
                    const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
                    selectAll.checked = allChecked;
                    onSelectionChanged();
                }));

                deleteBtn.addEventListener('click', () => {
                    const ids = Array.from(itemCheckboxes).filter(c => c.checked).map(c => c.value);
                    if (ids.length === 0) return;
                    if (!confirm('Bạn có chắc chắn muốn xóa các sản phẩm đã chọn?')) return;
                    Promise.all(ids.map(id => removeFromCart({
                        id,
                        silent: true
                    }))).then(() => {
                        showNotification('Đã xóa các sản phẩm thành công', 'success');
                        location.reload();
                    });
                });

                buyBtn.addEventListener('click', proceedToCheckoutSelected);

                selectAll.checked = false;
                itemCheckboxes.forEach(cb => {
                    cb.checked = false;
                });
                updateButtonsState();
            }

            function proceedToCheckout() {
                window.location.href = '{{ route('checkout.index') }}';
            }

            function proceedToCheckoutSelected() {
                const checked = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
                if (checked.length === 0) {
                    showNotification('Vui lòng chọn sản phẩm để thanh toán', 'error');
                    return;
                }
                localStorage.setItem('checkout_selected_items', JSON.stringify(checked));
                window.location.href = '{{ route('checkout.index') }}?selected=1';
            }

            renderSummary();
            updateCartCount();
            initSelectionFeatures();
            document.getElementById('apply-coupon-btn')?.addEventListener('click', applyDiscountCode);
        });
    </script>
@endsection
