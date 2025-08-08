<!-- Header -->
<header class="bg-white shadow-sm border-b">
    <style>
        /* Cart Sidebar Styles */
        #cart-sidebar {
            height: 100vh;
            max-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        #cart-items-container {
            flex: 1;
            overflow-y: auto;
        }
        
        /* Category Dropdown Styles */
        #categoryDropdown {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }
        
        /* Account Dropdown Styles */
        #accountDropdown {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        /* Animation for cart items */
        .cart-item-enter {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Improved hover effects */
        .category-item:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            #cart-sidebar {
                width: 100vw;
            }
            
            #categoryDropdown {
                width: 95vw;
                left: 2.5vw !important;
                max-width: none;
            }
            
            #accountDropdown {
                width: 280px;
                right: 1rem !important;
                left: auto !important;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            /* Hide text labels on mobile */
            .mobile-hide-text {
                display: none;
            }
            
            /* Header responsive adjustments */
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            /* Mobile search input */
            input[type="text"] {
                font-size: 16px; /* Prevent zoom on iOS */
            }
        }
        
        @media (max-width: 640px) {
            #categoryDropdown .grid-cols-2 {
                grid-template-columns: 1fr;
            }
            
            /* Mobile header adjustments */
            .container {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }
        }
    </style>
    
    <!-- Main header -->
    <div class="container mx-auto px-4 py-3">
        
        <div class="flex items-center justify-between flex-wrap lg:flex-nowrap gap-4">
            <!-- Logo -->
            <div class="flex items-center flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('admin_css/images/logo_techvicom.png') }}" alt="Techvicom" class="w-10 h-10 rounded-lg mr-3 object-cover">
                    <span class="text-xl font-bold text-gray-800">Techvicom</span>
                </a>
            </div>

            <!-- Category Menu Button -->
            <div class="ml-2 lg:ml-6 relative">
                <button id="categoryMenuBtn" class="flex items-center space-x-2 px-3 lg:px-4 py-2 border border-orange-300 rounded-lg hover:bg-orange-50 transition">
                    <i class="fas fa-bars text-gray-600"></i>
                    <span class="hidden sm:inline text-gray-700 font-medium">Danh mục</span>
                </button>
                
                <!-- Category Dropdown Menu -->
                <div id="categoryDropdown" class="absolute top-full left-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 hidden">
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-th-large text-orange-500 mr-2"></i>
                            Danh mục sản phẩm
                        </h3>
                        <div class="grid grid-cols-2 gap-2">
                            @if(isset($categories) && $categories->count() > 0)
                                @foreach($categories as $category)
                                    <a href="{{ route('categories.show', $category->slug) }}" class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                        @if($category->image)
                                            <img src="{{ asset('uploads/categories/' . $category->image) }}" 
                                                 alt="{{ $category->name }}" 
                                                 class="w-8 h-8 object-cover rounded mr-3 group-hover:scale-110 transition-transform">
                                        @else
                                            <div class="w-8 h-8 bg-orange-100 rounded flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                                <i class="fas fa-tag text-orange-500 text-sm"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="text-gray-700 font-medium">{{ $category->name }}</span>
                                            <p class="text-xs text-gray-500">{{ $category->children->count() }} danh mục con</p>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <!-- Fallback static categories -->
                                <a href="{{ route('products.index') }}?category=phone" class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <i class="fas fa-mobile-alt text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                                    <div>
                                        <span class="text-gray-700 font-medium">Điện thoại</span>
                                        <p class="text-xs text-gray-500">Smartphone</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=laptop" class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <i class="fas fa-laptop text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                                    <div>
                                        <span class="text-gray-700 font-medium">Laptop</span>
                                        <p class="text-xs text-gray-500">Máy tính xách tay</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=tablet" class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <i class="fas fa-tablet-alt text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                                    <div>
                                        <span class="text-gray-700 font-medium">Tablet</span>
                                        <p class="text-xs text-gray-500">Máy tính bảng</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=watch" class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <i class="fas fa-watch text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                                    <div>
                                        <span class="text-gray-700 font-medium">Đồng hồ thông minh</span>
                                        <p class="text-xs text-gray-500">Smart Watch</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=accessory" class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <i class="fas fa-headphones text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                                    <div>
                                        <span class="text-gray-700 font-medium">Phụ kiện</span>
                                        <p class="text-xs text-gray-500">Tai nghe, sạc, bao da...</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=gaming" class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <i class="fas fa-gamepad text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                                    <div>
                                        <span class="text-gray-700 font-medium">Gaming</span>
                                        <p class="text-xs text-gray-500">Thiết bị chơi game</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                        
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <a href="{{ route('categories.index') }}" class="flex items-center justify-center text-orange-600 hover:text-orange-700 font-medium transition">
                                <span>Xem tất cả danh mục</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search bar -->
            <div class="flex-1 max-w-2xl mx-2 lg:mx-6 w-full lg:w-auto">
                <div class="relative">
                    <input type="text" 
                           id="header-search-input"
                           placeholder="Nhập để tìm kiếm sản phẩm..." 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400">
                    <button id="header-search-btn" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-orange-500">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Right side buttons -->
            <div class="flex items-center space-x-2 lg:space-x-3">
                @auth
                @if(Auth::user()->hasRole(['admin', 'staff']))
                <!-- Admin/Staff Quick Access Button -->
                <div class="relative">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-1 lg:space-x-2 px-3 lg:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" title="Quản trị hệ thống">
                        <i class="fas fa-cogs"></i>
                        <span class="hidden lg:inline font-medium">Quản trị</span>
                    </a>
                </div>
                @endif
                @endauth
                
                <!-- Account Button with Dropdown -->
                <div class="relative">
                    <button id="accountMenuBtn" class="flex items-center space-x-1 lg:space-x-2 px-3 lg:px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                        <i class="fas fa-user"></i>
                        <span class="hidden lg:inline font-medium">
                            @auth
                                {{ Str::limit(Auth::user()->name, 15) }}
                            @else
                                Tài khoản
                            @endauth
                        </span>
                        <i class="fas fa-chevron-down ml-1 text-sm"></i>
                    </button>
                    
                    <!-- Account Dropdown Menu -->
                    <div id="accountDropdown" class="absolute top-full right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-50 hidden">
                        @guest
                        <!-- Logged out state -->
                        <div class="p-4">
                            <div class="text-center mb-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-600 text-sm">Đăng nhập để trải nghiệm đầy đủ</p>
                            </div>
                            <div class="space-y-2">
                                <a href="{{ route('login') }}" class="block w-full text-center bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">
                                    Đăng nhập
                                </a>
                                <a href="{{ route('register') }}" class="block w-full text-center border border-orange-500 text-orange-500 py-2 rounded-lg hover:bg-orange-50 transition">
                                    Đăng ký
                                </a>
                            </div>
                        </div>
                        @else
                        <!-- Logged in state -->
                        <div>
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                        <p class="text-sm text-gray-500">
                                            @if(Auth::user()->hasRole('admin'))
                                                Quản trị viên
                                            @elseif(Auth::user()->hasRole('staff'))
                                                Nhân viên
                                            @else
                                                Khách hàng thân thiết
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                @if(Auth::user()->hasRole(['admin', 'staff']))
                                <!-- Admin/Staff access -->
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fas fa-cogs mr-3 text-blue-500"></i>
                                    Quản trị hệ thống
                                </a>
                                <div class="border-t border-gray-200 my-2"></div>
                                @endif
                                
                                <a href="{{ route('accounts.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                    <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                                    Thông tin tài khoản
                                </a>
                                
                                <a href="{{ route('accounts.orders') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                    <i class="fas fa-shopping-bag mr-3 text-gray-400"></i>
                                    Đơn hàng của tôi
                                </a>
                                
                                <a href="{{ route('accounts.addresses') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                    <i class="fas fa-map-marker-alt mr-3 text-gray-400"></i>
                                    Địa chỉ giao hàng
                                </a>
                                
                                <div class="border-t border-gray-200 my-2"></div>
                                <form action="{{ route('logout') }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-3 py-2 text-[#ff6c2f] hover:bg-orange-50 rounded-lg transition">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endguest
                    </div>
                </div>

                <!-- Cart -->
                <div class="relative">
                    <button id="cartMenuBtn" class="flex items-center space-x-1 lg:space-x-2 px-3 lg:px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="hidden lg:inline font-medium">Giỏ hàng</span>
                        <span class="absolute -top-2 -right-2 bg-[#ff6c2f] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" id="cart-count">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Cart Sidebar -->
<div id="cart-sidebar" class="fixed inset-y-0 right-0 w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">
            <i class="fas fa-shopping-cart mr-2 text-orange-500"></i>
            Giỏ hàng của bạn
        </h3>
        <button id="close-cart-sidebar" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fas fa-times text-gray-500"></i>
        </button>
    </div>

    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto p-4" id="cart-items-container">
        <!-- Empty cart state -->
        <div id="empty-cart" class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
            </div>
            <h4 class="text-gray-600 font-medium mb-2">Giỏ hàng trống</h4>
            <p class="text-gray-500 text-sm mb-4">Thêm sản phẩm vào giỏ hàng để bắt đầu mua sắm</p>
            <button onclick="closeCartSidebar()" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                Tiếp tục mua sắm
            </button>
        </div>

        <!-- Cart items will be loaded here -->
        <div id="cart-items-list" class="space-y-4 hidden">
            <!-- Items will be dynamically added here -->
        </div>
    </div>

    <!-- Cart Footer -->
    <div id="cart-footer" class="border-t border-gray-200 p-4 hidden">
        <!-- Subtotal -->
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600">Tạm tính:</span>
            <span class="text-lg font-semibold text-gray-900" id="cart-subtotal">0₫</span>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-2">
            <button onclick="window.location.href='{{ route('carts.index') }}'" class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition">
                Xem giỏ hàng
            </button>
            <button onclick="window.location.href='{{ route('checkout.index') }}'" class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition">
                Thanh toán ngay
            </button>
        </div>
    </div>
</div>

<!-- Overlay for sidebar -->
<div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cart sidebar functionality
    const cartBtn = document.getElementById('cartMenuBtn');
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartOverlay = document.getElementById('cart-overlay');
    const closeCartBtn = document.getElementById('close-cart-sidebar');

    // Category dropdown functionality
    const categoryBtn = document.getElementById('categoryMenuBtn');
    const categoryDropdown = document.getElementById('categoryDropdown');

    // Account dropdown functionality
    const accountBtn = document.getElementById('accountMenuBtn');
    const accountDropdown = document.getElementById('accountDropdown');

    // Open cart sidebar
    cartBtn.addEventListener('click', function() {
        cartSidebar.classList.remove('translate-x-full');
        cartOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Close cart sidebar
    function closeCartSidebar() {
        cartSidebar.classList.add('translate-x-full');
        cartOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    closeCartBtn.addEventListener('click', closeCartSidebar);
    cartOverlay.addEventListener('click', closeCartSidebar);

    // Category dropdown
    categoryBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        categoryDropdown.classList.toggle('hidden');
        accountDropdown.classList.add('hidden');
    });

    // Account dropdown
    accountBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        accountDropdown.classList.toggle('hidden');
        categoryDropdown.classList.add('hidden');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        categoryDropdown.classList.add('hidden');
        accountDropdown.classList.add('hidden');
    });

    // Prevent dropdown from closing when clicking inside
    categoryDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    accountDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Load cart items
    loadCartItems();

    // Update authentication state on load
    @auth
    updateAuthenticationUI(true, {
        name: '{{ Auth::user()->name }}',
        isAdmin: {{ Auth::user()->hasRole(['admin', 'staff']) ? 'true' : 'false' }}
    });
    @else
    updateAuthenticationUI(false);
    @endauth

    // Header search functionality
    const headerSearchInput = document.getElementById('header-search-input');
    const headerSearchBtn = document.getElementById('header-search-btn');

    function performHeaderSearch() {
        const searchTerm = headerSearchInput.value.trim();
        if (searchTerm) {
            window.location.href = `{{ route('products.index') }}?search=${encodeURIComponent(searchTerm)}`;
        }
    }

    if (headerSearchBtn) {
        headerSearchBtn.addEventListener('click', performHeaderSearch);
    }

    if (headerSearchInput) {
        headerSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performHeaderSearch();
            }
        });
    }
});

function updateAuthenticationUI(isLoggedIn, userData = null) {
    // This function can be used for dynamic updates if needed
    // Currently, the UI is handled by Blade directives
    console.log('Authentication state:', isLoggedIn ? 'logged in' : 'logged out');
    if (userData) {
        console.log('User data:', userData);
    }
}

function loadCartItems() {
    // Load cart items via AJAX for both auth and guest users
    console.log('Loading cart items...');
    
    fetch('{{ route("carts.count") }}')
        .then(response => response.json())
        .then(data => {
            console.log('Cart count response:', data);
            document.getElementById('cart-count').textContent = data.count || 0;
            
            // Also load cart items for sidebar
            return fetch('{{ route("carts.index") }}', {
                headers: {
                    'Accept': 'application/json'
                }
            });
        })
        .then(response => {
            console.log('Cart items response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Cart items data:', data);
            if (data.success && data.items) {
                updateCartDisplay(data.items);
            } else {
                console.error('Invalid cart data structure:', data);
                updateCartDisplay([]);
            }
        })
        .catch(error => {
            console.error('Error loading cart:', error);
            // Fallback - just update count
            updateCartCount();
        });
}

function updateCartDisplay(items) {
    console.log('updateCartDisplay called with items:', items);
    const emptyCart = document.getElementById('empty-cart');
    const cartItemsList = document.getElementById('cart-items-list');
    const cartFooter = document.getElementById('cart-footer');

    if (items.length === 0) {
        console.log('No items in cart, showing empty state');
        emptyCart.classList.remove('hidden');
        cartItemsList.classList.add('hidden');
        cartFooter.classList.add('hidden');
    } else {
        console.log('Cart has items, showing cart content');
        emptyCart.classList.add('hidden');
        cartItemsList.classList.remove('hidden');
        cartFooter.classList.remove('hidden');
        
        // Render cart items
        cartItemsList.innerHTML = items.map(item => {
            console.log('Rendering item:', item);
            const price = parseFloat(item.price) || 0;
            let variantHtml = '';
            if (item.variant && item.variant.attributes && item.variant.attributes.length > 0) {
                variantHtml = `<div class=\"text-xs text-gray-500\">` +
                    item.variant.attributes.map(attr => `${attr.name}: ${attr.value}`).join(', ') +
                    `</div>`;
            }
            return `
            <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg cart-item-enter">
                <img src="${item.image || '/images/default-product.jpg'}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 text-sm">${item.name}</h4>
                    ${variantHtml}
                    <p class="text-orange-500 font-semibold">${formatPrice(price)}</p>
                    <div class="flex items-center space-x-2 mt-1">
                        <button onclick="updateCartQuantity('${item.id}', ${item.quantity - 1})" class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100">-</button>
                        <span class="text-sm">${item.quantity}</span>
                        <button onclick="updateCartQuantity('${item.id}', ${item.quantity + 1})" class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100">+</button>
                    </div>
                </div>
                <button onclick="removeFromCart('${item.id}')" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        `;
        }).join('');

        // Update subtotal
        const subtotal = items.reduce((sum, item) => {
            const price = parseFloat(item.price) || 0;
            const quantity = parseInt(item.quantity) || 0;
            console.log(`Adding item price: ${price} x ${quantity} = ${price * quantity}`);
            return sum + (price * quantity);
        }, 0);
        console.log('Calculated subtotal:', subtotal);
        const subtotalElement = document.getElementById('cart-subtotal');
        if (subtotalElement) {
            subtotalElement.textContent = formatPrice(subtotal);
            console.log('Updated subtotal element:', subtotalElement.textContent);
        } else {
            console.error('cart-subtotal element not found');
        }
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

function addToCart(productId, variantId = null, quantity = 1) {
    const data = {
        product_id: productId,
        quantity: quantity,
        variant_id: variantId,
        _token: '{{ csrf_token() }}'
    };

    fetch('{{ route("carts.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin', // Include cookies
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCartItems();
            // Show success message
            showNotification('Đã thêm sản phẩm vào giỏ hàng', 'success');
        } else {
            showNotification(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra', 'error');
    });
}

function updateCartQuantity(itemId, newQuantity) {
    console.log('updateCartQuantity called with:', itemId, newQuantity);
    
    if (newQuantity < 1) {
        removeFromCart(itemId);
        return;
    }

    fetch(`{{ url('/carts') }}/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin', // Include cookies
        body: JSON.stringify({
            quantity: newQuantity
        })
    })
    .then(response => {
        console.log('Update response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Update response data:', data);
        if (data.success) {
            loadCartItems();
            showNotification('Đã cập nhật số lượng', 'success');
        } else {
            showNotification(data.message || 'Có lỗi xảy ra khi cập nhật', 'error');
            // Show debug info if available
            if (data.debug) {
                console.error('Debug info:', data.debug);
            }
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        showNotification('Có lỗi xảy ra khi cập nhật', 'error');
    });
}

function removeFromCart(itemId) {
    console.log('removeFromCart called with:', itemId);
    
    fetch(`/carts/${itemId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin' // Include cookies
    })
    .then(response => {
        console.log('Remove response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Remove response data:', data);
        if (data.success) {
            loadCartItems();
            showNotification('Đã xóa sản phẩm khỏi giỏ hàng', 'success');
        } else {
            showNotification(data.message || 'Có lỗi xảy ra khi xóa', 'error');
            // Show debug info if available
            if (data.debug) {
                console.error('Debug info:', data.debug);
            }
        }
    })
    .catch(error => {
        console.error('Error removing from cart:', error);
        showNotification('Có lỗi xảy ra khi xóa', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function updateCartCount() {
    fetch('{{ route("carts.count") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cart-count').textContent = data.count || 0;
        })
        .catch(error => console.error('Error updating cart count:', error));
}

// Make functions available globally
window.loadCartItems = loadCartItems;
window.updateCartCount = updateCartCount;
window.closeCartSidebar = closeCartSidebar;
window.showNotification = showNotification;
</script>
