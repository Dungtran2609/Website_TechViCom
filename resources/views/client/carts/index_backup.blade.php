@extends('client.layouts.app')

@section('title', 'Giỏ hàng - Techvicom')

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-white border-b border-gray-200 py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-gray-900 font-medium">Giỏ hàng</span>
            </div>
        </div>
    </nav>

    <!-- Shopping Cart -->
    <section class="py-8">
        <div class="container mx-auto px-4">
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
                                                            {{ number_format($displayPrice) }}₫ (Raw: {{ $displayPrice }})
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
                                    <div class="max-w-md mx-auto">
                                        <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-6"></i>
                                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Giỏ hàng của bạn đang trống</h2>
                                        <p class="text-gray-600 mb-8">Hãy khám phá những sản phẩm tuyệt vời của chúng tôi</p>
                                        <a href="{{ route('home') }}" 
                                           class="bg-[#ff6c2f] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#e55a28] transition inline-block">
                                            Bắt đầu mua sắm
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
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
                            <button onclick="updateCartSummary()" 
                                    class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 transition mb-2"
                                    id="test-summary-btn">
                                Test Update Summary
                            </button>
                            
                            <button onclick="proceedToCheckout()" 
                                    class="w-full bg-[#ff6c2f] text-white py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition mb-4"
                                    id="checkout-btn">
                                Tiến hành thanh toán
                            </button>

                            <!-- Continue Shopping -->
                            <a href="{{ route('home') }}" 
                               class="block w-full text-center border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition">
                                Tiếp tục mua sắm
                            </a>

                            <!-- Security Info -->
                            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                    <span class="text-sm font-medium">Thanh toán an toàn</span>
                                </div>
                                <div class="text-xs text-gray-600">
                                    Thông tin của bạn được bảo mật bằng SSL 256-bit
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suggested Products -->
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Có thể bạn cũng thích</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="suggested-products">
                        <!-- Static Suggested Products -->
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group">
                            <div class="relative">
                                <img src="../assets/images/airpods-pro-2.jpg" alt="AirPods Pro 2" class="w-full h-48 object-cover rounded-t-lg">
                                <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">-10%</div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2">AirPods Pro 2nd Generation</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 text-sm">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(89)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-lg font-bold text-[#ff6c2f]">6.290.000₫</span>
                                        <span class="text-sm text-gray-500 line-through ml-2">6.990.000₫</span>
                                    </div>
                                    <button onclick="addToCartStatic(6, 'AirPods Pro 2nd Generation', 6290000, '../assets/images/airpods-pro-2.jpg')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group">
                            <div class="relative">
                                <img src="../assets/images/apple-watch-series-9.jpg" alt="Apple Watch Series 9" class="w-full h-48 object-cover rounded-t-lg">
                                <div class="absolute top-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-sm font-bold">NEW</div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2">Apple Watch Series 9 GPS</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 text-sm">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(67)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-lg font-bold text-[#ff6c2f]">9.990.000₫</span>
                                    </div>
                                    <button onclick="addToCartStatic(7, 'Apple Watch Series 9 GPS', 9990000, '../assets/images/apple-watch-series-9.jpg')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group">
                            <div class="relative">
                                <img src="../assets/images/samsung-galaxy-buds2-pro.jpg" alt="Samsung Galaxy Buds2 Pro" class="w-full h-48 object-cover rounded-t-lg">
                                <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">-15%</div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2">Samsung Galaxy Buds2 Pro</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 text-sm">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(123)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-lg font-bold text-[#ff6c2f]">4.250.000₫</span>
                                        <span class="text-sm text-gray-500 line-through ml-2">4.990.000₫</span>
                                    </div>
                                    <button onclick="addToCartStatic(8, 'Samsung Galaxy Buds2 Pro', 4250000, '../assets/images/samsung-galaxy-buds2-pro.jpg')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group">
                            <div class="relative">
                                <img src="../assets/images/macbook-pro-m3.jpg" alt="MacBook Pro M3" class="w-full h-48 object-cover rounded-t-lg">
                                <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-sm font-bold">HOT</div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2">MacBook Pro 14" M3</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 text-sm">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(45)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-lg font-bold text-[#ff6c2f]">52.990.000₫</span>
                                    </div>
                                    <button onclick="addToCartStatic(3, 'MacBook Pro 14 M3', 52990000, '../assets/images/macbook-pro-m3.jpg')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Empty Cart Message -->
    <div id="empty-cart" class="hidden py-16 text-center">
        <div class="max-w-md mx-auto">
            <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-6"></i>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Giỏ hàng của bạn đang trống</h2>
            <p class="text-gray-600 mb-8">Hãy khám phá những sản phẩm tuyệt vời của chúng tôi</p>
            <a href="{{ route('home') }}" 
               class="bg-[#ff6c2f] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition inline-block">
                Bắt đầu mua sắm
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4">Về Techvicom</h3>
                    <ul class="space-y-2">
                        <li><a href="about.html" class="hover:text-red-400">Giới thiệu</a></li>
                        <li><a href="careers.html" class="hover:text-red-400">Tuyển dụng</a></li>
                        <li><a href="news.html" class="hover:text-red-400">Tin tức</a></li>
                        <li><a href="stores.html" class="hover:text-red-400">Hệ thống cửa hàng</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Chính sách</h3>
                    <ul class="space-y-2">
                        <li><a href="warranty.html" class="hover:text-red-400">Chính sách bảo hành</a></li>
                        <li><a href="return.html" class="hover:text-red-400">Chính sách đổi trả</a></li>
                        <li><a href="shipping.html" class="hover:text-red-400">Chính sách giao hàng</a></li>
                        <li><a href="privacy.html" class="hover:text-red-400">Chính sách bảo mật</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Hỗ trợ khách hàng</h3>
                    <ul class="space-y-2">
                        <li><a href="contact.html" class="hover:text-red-400">Hotline: 1800.6601</a></li>
                        <li><a href="contact.html" class="hover:text-red-400">Email: support@techvicom.vn</a></li>
                        <li><a href="contact.html" class="hover:text-red-400">Live Chat</a></li>
                        <li><a href="help.html" class="hover:text-red-400">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Kết nối với chúng tôi</h3>
                    <div class="flex space-x-4 mb-4">
                        <a href="#" class="text-2xl hover:text-blue-400"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-2xl hover:text-pink-400"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-2xl hover:text-red-400"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-2">Đăng ký nhận tin</h4>
                        <div class="flex">
                            <input type="email" placeholder="Email của bạn" class="px-3 py-2 bg-gray-700 rounded-l-lg flex-1">
                            <button class="bg-[#ff6c2f] px-4 py-2 rounded-r-lg hover:bg-[#ff6c2f]">Đăng ký</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p>&copy; 2025 Techvicom. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    
    <!-- Shared Footer -->
    <div id="shared-footer-container"></div>
@endsection

@push('scripts')
    <script>
        // Static cart management functions
        function addToCartStatic(productId, name, price, image) {
            try {
                const product = {
                    id: productId,
                    name: name,
                    price: price,
                    image: image,
                    quantity: 1
                };

                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                
                const existingProduct = cart.find(item => item.id === productId);
                if (existingProduct) {
                    existingProduct.quantity += 1;
                } else {
                    cart.push(product);
                }
                
                localStorage.setItem('cart', JSON.stringify(cart));
                
                // Update cart count in header if it exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                    cartCount.textContent = totalItems;
                    cartCount.style.display = totalItems > 0 ? 'block' : 'none';
                }

                // Show success notification
                showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');
                
                // Reload cart display
                loadCartFromStorage();
                
            } catch (error) {
                console.error('Error adding product to cart:', error);
                showNotification('Có lỗi xảy ra khi thêm sản phẩm!', 'error');
            }
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500' : 'bg-[#ff6c2f]'
            }`;
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

        // Cart display and management functions
        function loadCartFromStorage() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const container = document.getElementById('cart-items-container');
            const emptyCart = document.getElementById('empty-cart');
            
            if (cart.length === 0) {
                container.innerHTML = '';
                document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-3').style.display = 'none';
                emptyCart.classList.remove('hidden');
                return;
            }
            
            emptyCart.classList.add('hidden');
            document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-3').style.display = 'grid';
            
            let cartHTML = '<div class="p-6">';
            let subtotal = 0;
            
            cart.forEach((item, index) => {
                subtotal += item.price * item.quantity;
                cartHTML += `
                    <div class="flex items-center justify-between border-b border-gray-200 py-4 ${index === cart.length - 1 ? 'border-b-0' : ''}">
                        <div class="flex items-center space-x-4">
                            <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg">
                            <div>
                                <h4 class="font-semibold text-gray-800">${item.name}</h4>
                                <p class="text-[#ff6c2f] font-bold">${item.price.toLocaleString()}₫</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border rounded-lg">
                                <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" class="px-3 py-1 hover:bg-gray-100">-</button>
                                <span class="px-3 py-1 border-x">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" class="px-3 py-1 hover:bg-gray-100">+</button>
                            </div>
                            <button onclick="removeFromCart(${item.id})" class="text-[#ff6c2f] hover:hover:text-[#ff6c2f]">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            cartHTML += '</div>';
            container.innerHTML = cartHTML;
            
            // Update order summary
            document.getElementById('subtotal').textContent = subtotal.toLocaleString() + '₫';
            document.getElementById('total').textContent = subtotal.toLocaleString() + '₫';
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the quantity in DOM
                    const cartItems = document.querySelectorAll('.cart-item');
                    cartItems.forEach(item => {
                        const buttons = item.querySelectorAll('button[onclick*="updateQuantity"]');
                        buttons.forEach(button => {
                            if (button.getAttribute('onclick').includes(`'${itemId}'`)) {
                                const quantitySpan = item.querySelector('span.w-8.text-center');
                                quantitySpan.textContent = newQuantity;
                                item.dataset.quantity = newQuantity; // Update data attribute
                                
                                // Update onclick handlers
                                const minusBtn = item.querySelector('button[onclick*="' + itemId + '"]');
                                const plusBtn = item.querySelectorAll('button[onclick*="' + itemId + '"]')[1];
                                minusBtn.setAttribute('onclick', `updateQuantity('${itemId}', ${newQuantity - 1})`);
                                plusBtn.setAttribute('onclick', `updateQuantity('${itemId}', ${newQuantity + 1})`);
                                
                                // Update cart summary
                                updateCartSummary();
                                updateCartCount();
                                return;
                            }
                        });
                    });
                    showNotification('Đã cập nhật số lượng', 'success');
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
            
            fetch(`/client/carts/${itemId}`, {
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
                    location.reload(); // Reload to update the view
                } else {
                    showNotification(data.message || 'Có lỗi xảy ra', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra', 'error');
            });
        }

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                cartCount.textContent = totalItems;
                cartCount.style.display = totalItems > 0 ? 'block' : 'none';
            }
        }

        function applyDiscountCode() {
            const code = document.getElementById('discount-code').value.trim();
            if (code === 'SAVE10') {
                showNotification('Mã giảm giá đã được áp dụng!', 'success');
                document.getElementById('discount').textContent = '-100.000₫';
            } else if (code === '') {
                showNotification('Vui lòng nhập mã giảm giá!', 'error');
            } else {
                showNotification('Mã giảm giá không hợp lệ!', 'error');
            }
        }

        function proceedToCheckout() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length === 0) {
                showNotification('Giỏ hàng của bạn đang trống!', 'error');
                return;
            }
            window.location.href = '{{ route("checkout.index") }}';
        }

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

        // Initialize cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartSummary();
            updateCartCount();
        });
    </script>
@endpush
