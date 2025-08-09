@extends('client.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
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

            <!-- Cart Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md">
                        @if(count($cartItems) > 0)
                            <div class="p-6">
                                @foreach($cartItems as $item)
                                    @php
                                        $displayPrice = 0;
                                        // Ưu tiên giá từ variant được chọn
                                        if(isset($item->productVariant) && $item->productVariant && $item->productVariant->price > 0) {
                                            $displayPrice = $item->productVariant->price;
                                        } 
                                        // Nếu không có variant cụ thể, lấy giá từ variant đầu tiên
                                        elseif($item->product->variants->count() > 0 && $item->product->variants->first()->price > 0) {
                                            $displayPrice = $item->product->variants->first()->price;
                                        }
                                        // Fallback về giá sale/regular của product
                                        elseif($item->product->sale_price > 0) {
                                            $displayPrice = $item->product->sale_price;
                                        } 
                                        elseif($item->product->regular_price > 0) {
                                            $displayPrice = $item->product->regular_price;
                                        }
                                    @endphp
                                    
                                    <div class="flex items-center justify-between border-b border-gray-200 py-4 {{ $loop->last ? 'border-b-0' : '' }} cart-item" 
                                         data-price="{{ $displayPrice }}" data-quantity="{{ $item->quantity }}">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                                @if($item->product->productAllImages->count() > 0)
                                                    <img src="{{ asset('uploads/products/' . $item->product->productAllImages->first()->image_url) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <img src="{{ asset('client_css/images/placeholder.svg') }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                                @if(isset($item->productVariant) && $item->productVariant)
                                                    <div class="text-sm text-gray-500">
                                                        @foreach($item->productVariant->attributeValues as $attrValue)
                                                            {{ $attrValue->attribute->name }}: {{ $attrValue->value }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <div class="text-sm text-gray-500">
                                                    @if($displayPrice > 0)
                                                        {{ number_format($displayPrice) }}₫
                                                    @else
                                                        Liên hệ
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="updateQuantity('{{ $item->id }}', {{ $item->quantity - 1 }})" 
                                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50">
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                                <span class="w-8 text-center">{{ $item->quantity }}</span>
                                                <button onclick="updateQuantity('{{ $item->id }}', {{ $item->quantity + 1 }})" 
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

                <!-- Order Summary -->
                @if(count($cartItems) > 0)
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

                        <!-- Discount Code -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mã giảm giá
                            </label>
                            <div class="flex">
                                <input type="text" id="discount-code" 
                                       placeholder="Nhập mã giảm giá"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:border-[#ff6c2f]">
                                <button onclick="applyDiscountCode()" 
                                        class="bg-[#ff6c2f] text-white px-4 py-2 rounded-r-lg hover:bg-[#ff6c2f] transition">
                                    Áp dụng
                                </button>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button onclick="proceedToCheckout()" 
                                class="w-full bg-[#ff6c2f] text-white py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition mb-4">
                            Tiến hành thanh toán
                        </button>

                        <!-- Continue Shopping -->
                        <a href="{{ route('home') }}" 
                           class="block text-center text-[#ff6c2f] hover:underline">
                            ← Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Cart functions
    function updateCartSummary() {
        let subtotal = 0;
        const cartItems = document.querySelectorAll('.cart-item');
        
        console.log('Updating cart summary, found items:', cartItems.length);
        
        cartItems.forEach((item, index) => {
            const price = parseFloat(item.dataset.price) || 0;
            const quantity = parseFloat(item.dataset.quantity) || 0;
            const itemTotal = price * quantity;
            subtotal += itemTotal;
            
            console.log(`Item ${index + 1}: price=${price}, quantity=${quantity}, total=${itemTotal}`);
        });
        
        console.log('Final subtotal:', subtotal);
        
        const subtotalElement = document.getElementById('subtotal');
        const totalElement = document.getElementById('total');
        
        console.log('Subtotal element found:', subtotalElement);
        console.log('Total element found:', totalElement);
        
        if (subtotalElement) {
            subtotalElement.textContent = subtotal.toLocaleString() + '₫';
        }
        if (totalElement) {
            totalElement.textContent = subtotal.toLocaleString() + '₫';
        }
    }

    function updateQuantity(itemId, newQuantity) {
        if (newQuantity <= 0) {
            removeFromCart(itemId);
            return;
        }
        
    fetch(`/carts/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: newQuantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Simple reload for now
            } else {
                showNotification(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra', 'error');
        });
    }

    function removeFromCart(itemId) {
        if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;
        
    fetch(`/carts/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Đã xóa sản phẩm', 'success');
                location.reload();
            } else {
                showNotification(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra', 'error');
        });
    }

    function proceedToCheckout() {
        // Check if discount is applied and transfer to checkout
        const appliedDiscount = localStorage.getItem('appliedDiscount');
        if (appliedDiscount) {
            // Keep the discount for checkout page
            console.log('Transferring discount to checkout:', appliedDiscount);
        }
        window.location.href = '{{ route("checkout.index") }}';
    }

    function applyDiscountCode() {
        const code = document.getElementById('discount-code').value.trim();
        if (!code) {
            showNotification('Vui lòng nhập mã giảm giá', 'error');
            return;
        }
        
        // Calculate current subtotal
        let subtotal = 0;
        const cartItems = document.querySelectorAll('.cart-item');
        cartItems.forEach(item => {
            const price = parseFloat(item.dataset.price) || 0;
            const quantity = parseFloat(item.dataset.quantity) || 0;
            subtotal += price * quantity;
        });
        
        // Show loading notification
        showNotification('🔄 Đang kiểm tra mã giảm giá...', 'info');
        
        // Call API to validate coupon from database
        fetch('/api/apply-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                coupon_code: code,
                subtotal: subtotal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const discountAmount = data.discount_amount;
                
                // Update UI
                const discountElement = document.getElementById('discount');
                if (discountElement) {
                    discountElement.textContent = `-${discountAmount.toLocaleString()}₫`;
                    discountElement.classList.add('text-green-600');
                }
                
                // Update total
                const totalElement = document.getElementById('total');
                if (totalElement) {
                    const newTotal = subtotal - discountAmount;
                    totalElement.textContent = `${newTotal.toLocaleString()}₫`;
                }
                
                // Store applied discount with database info
                localStorage.setItem('appliedDiscount', JSON.stringify({
                    code: code,
                    amount: discountAmount,
                    details: data.coupon,
                    fromDatabase: true
                }));
                
                showNotification(`✅ ${data.coupon.message}`, 'success');
                
                // Disable input to prevent multiple applications
                document.getElementById('discount-code').disabled = true;
                
                // Add clear button
                const clearBtn = document.createElement('button');
                clearBtn.innerHTML = '×';
                clearBtn.className = 'ml-2 text-red-500 hover:text-red-700 font-bold';
                clearBtn.onclick = clearDiscountCode;
                document.getElementById('discount-code').parentNode.appendChild(clearBtn);
                
            } else {
                showNotification(`❌ ${data.message}`, 'error');
            }
        })
        .catch(error => {
            console.error('Coupon validation error:', error);
            showNotification('❌ Có lỗi xảy ra khi kiểm tra mã giảm giá', 'error');
        });
    }

    function clearDiscountCode() {
        // Clear input and enable it
        const input = document.getElementById('discount-code');
        input.value = '';
        input.disabled = false;
        
        // Reset discount display
        const discountElement = document.getElementById('discount');
        if (discountElement) {
            discountElement.textContent = '-0₫';
            discountElement.classList.remove('text-green-600');
        }
        
        // Recalculate total without discount
        updateCartSummary();
        
        // Remove clear button
        const clearBtn = input.parentNode.querySelector('button');
        if (clearBtn && clearBtn.innerHTML === '×') {
            clearBtn.remove();
        }
        
        // Clear localStorage
        localStorage.removeItem('appliedDiscount');
        
        showNotification('🗑️ Đã xóa mã giảm giá', 'info');
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        let bgColor = 'bg-green-500';
        if (type === 'error') bgColor = 'bg-red-500';
        if (type === 'info') bgColor = 'bg-blue-500';
        
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full ${bgColor}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(full)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    function updateCartCount() {
    fetch('/carts/count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const countElement = document.querySelector('.cart-count');
                    if (countElement) {
                        countElement.textContent = data.count;
                    }
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
    }

    // Initialize cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartSummary();
        updateCartCount();
    });
</script>
@endpush
