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

            .mobile-hide-text {
                display: none;
            }

            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            input[type="text"] {
                font-size: 16px;
            }
        }

        @media (max-width: 640px) {
            #categoryDropdown .grid-cols-2 {
                grid-template-columns: 1fr;
            }

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
                    <img src="{{ asset('admin_css/images/logo_techvicom.png') }}" alt="Techvicom"
                        class="w-10 h-10 rounded-lg mr-3 object-cover">
                    <span class="text-xl font-bold text-gray-800">Techvicom</span>
                </a>
            </div>

            <!-- Category Menu Button -->
            <div class="ml-2 lg:ml-6">
                <button id="categoryMenuBtn"
                    class="flex items-center space-x-2 px-3 lg:px-4 py-2 border border-orange-300 rounded-lg hover:bg-orange-50 transition">
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
                    <input type="text" id="header-search-input" placeholder="Nhập để tìm kiếm sản phẩm..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400">
                    <button id="header-search-btn"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-orange-500">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Right side buttons -->
            <div class="flex items-center space-x-2 lg:space-x-3">
                @auth
                    @if (Auth::user()->hasRole(['admin', 'staff']))
                        <!-- Admin/Staff Quick Access Button -->
                        <div class="relative">
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex items-center space-x-1 lg:space-x-2 px-3 lg:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                title="Quản trị hệ thống">
                                <i class="fas fa-cogs"></i>
                                <span class="hidden lg:inline font-medium">Quản trị</span>
                            </a>
                        </div>
                    @endif
                @endauth

                <!-- Account Button with Dropdown -->
                <div class="relative">
                    <button id="accountMenuBtn" class="flex items-center space-x-1 lg:space-x-2 px-3 lg:px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                        @auth
                            @if(Auth::user()->image_profile && file_exists(public_path('uploads/users/' . Auth::user()->image_profile)))
                                <img src="{{ asset('uploads/users/' . Auth::user()->image_profile) }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="w-6 h-6 rounded-full object-cover border border-white"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                <i class="fas fa-user" style="display: none;"></i>
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        @else
                            <i class="fas fa-user"></i>
                        @endauth
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
                    <div id="accountDropdown"
                        class="absolute top-full right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-50 hidden">
                        @guest
                            <!-- Logged out state -->
                            <div class="p-4">
                                <div class="text-center mb-4">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-600 text-sm">Đăng nhập để trải nghiệm đầy đủ</p>
                                </div>
                                <div class="space-y-2">
                                    <a href="{{ route('login') }}"
                                        class="block w-full text-center bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">Đăng
                                        nhập</a>
                                    <a href="{{ route('register') }}"
                                        class="block w-full text-center border border-orange-500 text-orange-500 py-2 rounded-lg hover:bg-orange-50 transition">Đăng
                                        ký</a>
                                </div>
                            </div>
                        @else
                            <!-- Logged in state -->
                            <div>
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex items-center">
                                        <div
                                            class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-orange-500"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                            <p class="text-sm text-gray-500">
                                                @if (Auth::user()->hasRole('admin'))
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
                                    @if (Auth::user()->hasRole(['admin', 'staff']))
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="flex items-center px-3 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                            <i class="fas fa-cogs mr-3 text-blue-500"></i>
                                            Quản trị hệ thống
                                        </a>
                                        <div class="border-t border-gray-200 my-2"></div>
                                    @endif

                                    <a href="{{ route('accounts.index') }}"
                                        class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                        <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                                        Thông tin tài khoản
                                    </a>

                                    <a href="{{ route('accounts.orders') }}"
                                        class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                        <i class="fas fa-shopping-bag mr-3 text-gray-400"></i>
                                        Đơn hàng của tôi
                                    </a>

                                    <a href="{{ route('accounts.addresses') }}"
                                        class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                        <i class="fas fa-map-marker-alt mr-3 text-gray-400"></i>
                                        Địa chỉ giao hàng
                                    </a>

                                    <div class="border-t border-gray-200 my-2"></div>
                                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-3 py-2 text-[#ff6c2f] hover:bg-orange-50 rounded-lg transition">
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
                    <button id="cartMenuBtn"
                        class="flex items-center space-x-1 lg:space-x-2 px-3 lg:px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="hidden lg:inline font-medium">Giỏ hàng</span>
                        <span
                            class="absolute -top-2 -right-2 bg-[#ff6c2f] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                            id="cart-count">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Dropdown Menu -->
    <div id="categoryDropdown"
        class="absolute top-full left-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 hidden">
        <div class="p-4">
            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-th-large text-orange-500 mr-2"></i>
                Danh mục sản phẩm
            </h3>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('products.index') }}?category=phone" onclick="return true;"
                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                    <i class="fas fa-mobile-alt text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <span class="text-gray-700 font-medium">Điện thoại</span>
                        <p class="text-xs text-gray-500">iPhone, Samsung...</p>
                    </div>
                </a>
                <a href="{{ route('products.index') }}?category=laptop" onclick="return true;"
                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                    <i class="fas fa-laptop text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <span class="text-gray-700 font-medium">Laptop</span>
                        <p class="text-xs text-gray-500">MacBook, Dell...</p>
                    </div>
                </a>
                <a href="{{ route('products.index') }}?category=tablet" onclick="return true;"
                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                    <i class="fas fa-tablet-alt text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <span class="text-gray-700 font-medium">Tablet</span>
                        <p class="text-xs text-gray-500">iPad, Galaxy Tab...</p>
                    </div>
                </a>
                <a href="{{ route('products.index') }}?category=audio" onclick="return true;"
                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                    <i class="fas fa-headphones text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <span class="text-gray-700 font-medium">Âm thanh</span>
                        <p class="text-xs text-gray-500">Tai nghe, Loa...</p>
                    </div>
                </a>
                <a href="{{ route('products.index') }}?category=watch" onclick="return true;"
                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                    <i class="fas fa-clock text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <span class="text-gray-700 font-medium">Đồng hồ</span>
                        <p class="text-xs text-gray-500">Apple Watch, Galaxy Watch...</p>
                    </div>
                </a>
                <a href="{{ route('products.index') }}?category=accessory" onclick="return true;"
                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                    <i class="fas fa-gamepad text-orange-500 mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <span class="text-gray-700 font-medium">Gaming</span>
                        <p class="text-xs text-gray-500">PS5, Xbox, PC...</p>
                    </div>
                </a>
            </div>

            <!-- Quick Links -->
            <div class="border-t border-gray-200 mt-4 pt-4">
                <div class="flex justify-between text-sm">
                    <a href="pages/deals.html" class="text-[#ff6c2f] hover:hover:text-[#ff6c2f] flex items-center">
                        <i class="fas fa-fire mr-1"></i>
                        Khuyến mãi hot
                    </a>
                    <a href="pages/new-arrivals.html" class="text-green-600 hover:text-green-700 flex items-center">
                        <i class="fas fa-star mr-1"></i>
                        Hàng mới về
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Cart Sidebar -->
<div id="cart-sidebar"
    class="fixed inset-y-0 right-0 w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
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
        <div id="cart-bulk-bar"
            class="hidden mb-3 px-2 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm flex items-center justify-between">
            <label class="flex items-center space-x-2">
                <input type="checkbox" id="sidebar-select-all"
                    class="w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]">
                <span>Chọn tất cả</span>
            </label>
            <div class="flex items-center space-x-2">
                <button id="sidebar-delete-selected"
                    class="px-3 py-1 rounded bg-red-100 text-red-600 hover:bg-red-200 text-xs disabled:opacity-40"
                    disabled>Xóa</button>
                <button id="sidebar-buy-selected"
                    class="px-3 py-1 rounded bg-[#ff6c2f] text-white hover:bg-[#e55a28] text-xs disabled:opacity-40"
                    disabled>Mua</button>
            </div>
        </div>
        <!-- Empty cart state -->
        <div id="empty-cart" class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
            </div>
            <h4 class="text-gray-600 font-medium mb-2">Giỏ hàng trống</h4>
            <p class="text-gray-500 text-sm mb-4">Thêm sản phẩm vào giỏ hàng để bắt đầu mua sắm</p>
            <button onclick="window.location.href='{{ route('home') }}'"
                class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                Tiếp tục mua sắm
            </button>
        </div>

        <!-- Cart items will be loaded here -->
        <div id="cart-items-list" class="space-y-4 hidden"></div>
    </div>

    <!-- Cart Footer -->
    <div id="cart-footer" class="border-t border-gray-200 p-4 hidden">
        <!-- Coupon -->
        <div id="sidebar-coupon-box" class="mb-3">
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-medium text-gray-600">Mã giảm giá</label>
                <button type="button" onclick="toggleCouponList()" class="text-[10px] text-[#ff6c2f] underline"
                    id="toggle-coupon-list-btn">Danh sách</button>
            </div>
            <div class="flex space-x-2 mb-1">
                <input type="text" id="sidebar-coupon-code" placeholder="Nhập mã"
                    class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:border-[#ff6c2f]">
                <button type="button" onclick="applySidebarCoupon()"
                    class="px-3 py-1 bg-[#ff6c2f] text-white text-xs rounded hover:bg-[#e55a28]">Áp dụng</button>
                <button type="button" onclick="clearSidebarCoupon()"
                    class="px-2 py-1 bg-gray-200 text-gray-600 text-xs rounded hover:bg-gray-300"
                    title="Hủy">×</button>
            </div>
            <p id="sidebar-coupon-message" class="text-xs mt-1"></p>
            <div id="available-coupons"
                class="mt-2 space-y-2 hidden max-h-40 overflow-y-auto border border-gray-200 rounded p-2 bg-gray-50 text-xs">
            </div>
        </div>

        <div id="sidebar-discount-row" class="flex justify-between items-center mb-2 hidden">
            <span class="text-gray-600">Giảm giá:</span>
            <span class="text-sm font-semibold text-green-600" id="sidebar-discount-amount">-0₫</span>
        </div>

        <!-- Subtotal (after discount) -->
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600">Tạm tính:</span>
            <span class="text-lg font-semibold text-gray-900" id="cart-subtotal">0₫</span>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-2">
            <button onclick="window.location.href='{{ route('carts.index') }}'"
                class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition">
                Xem giỏ hàng
            </button>

            <!-- Nút đã chỉnh: disable khi chưa chọn -->
            <button id="sidebar-checkout-now"
                class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition disabled:opacity-40 disabled:cursor-not-allowed"
                disabled>
                Thanh toán ngay
            </button>
        </div>
    </div>
</div>

<!-- Overlay for sidebar -->
<div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cart sidebar elements
        const cartBtn = document.getElementById('cartMenuBtn');
        const cartSidebar = document.getElementById('cart-sidebar');
        const cartOverlay = document.getElementById('cart-overlay');
        const closeCartBtn = document.getElementById('close-cart-sidebar');

        // Lắng nghe sự kiện cart:updated để reload giỏ hàng mini
        document.addEventListener('cart:updated', function() {
            loadCartItems();
        });
        // Category dropdown
        const categoryBtn = document.getElementById('categoryMenuBtn');
        const categoryDropdown = document.getElementById('categoryDropdown');

        // Account dropdown
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
        window.closeCartSidebar = closeCartSidebar; // expose

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

        // Prevent dropdown closing on inside click
        categoryDropdown.addEventListener('click', e => e.stopPropagation());
        accountDropdown.addEventListener('click', e => e.stopPropagation());

        // Load cart
        loadCartItems();

        // Watch coupon input: nếu sửa khác mã đã áp dụng -> hủy giảm giá
        const couponInput = document.getElementById('sidebar-coupon-code');
        if (couponInput) {
            couponInput.addEventListener('input', () => {
                try {
                    const saved = JSON.parse(localStorage.getItem('appliedDiscount') || 'null');
                    if (!couponInput.value.trim() || (saved && saved.code && couponInput.value.trim()
                            .toUpperCase() !== saved.code.toUpperCase())) {
                        localStorage.removeItem('appliedDiscount');
                        const msg = document.getElementById('sidebar-coupon-message');
                        if (msg) {
                            msg.textContent = couponInput.value.trim() ? 'Nhập mã giảm giá' : '';
                            msg.className = 'text-xs mt-1 text-gray-500';
                        }
                        recalcSelectedSubtotal();
                    }
                } catch (e) {}
            });
        }

        // Bind nút "Thanh toán ngay" -> dùng handler chung, không inline
        const checkoutNowBtn = document.getElementById('sidebar-checkout-now');
        if (checkoutNowBtn) {
            checkoutNowBtn.addEventListener('click', handleSidebarCheckout);
        }

        // Search
        const headerSearchInput = document.getElementById('header-search-input');
        const headerSearchBtn = document.getElementById('header-search-btn');

        function performHeaderSearch() {
            const searchTerm = headerSearchInput.value.trim();
            if (searchTerm) {
                window.location.href =
                    `{{ route('products.index') }}?search=${encodeURIComponent(searchTerm)}`;
            }
        }
        if (headerSearchBtn) headerSearchBtn.addEventListener('click', performHeaderSearch);
        if (headerSearchInput) headerSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') performHeaderSearch();
        });
    });

    // Auth UI (log only)
    function updateAuthenticationUI(isLoggedIn, userData = null) {
        console.log('Authentication state:', isLoggedIn ? 'logged in' : 'logged out');
        if (userData) console.log('User data:', userData);
    }

    function loadCartItems() {
        console.log('Loading cart items...');
        fetch('{{ route('carts.count') }}')
            .then(r => r.json())
            .then(data => {
                document.getElementById('cart-count').textContent = data.count || 0;
                return fetch('{{ route('carts.index') }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.success && data.items) {
                    updateCartDisplay(data.items);
                } else {
                    console.error('Invalid cart data structure:', data);
                    updateCartDisplay([]);
                }
            })
            .catch(err => {
                console.error('Error loading cart:', err);
                updateCartCount();
            });
    }

    function updateCartDisplay(items) {
        const emptyCart = document.getElementById('empty-cart');
        const cartItemsList = document.getElementById('cart-items-list');
        const cartFooter = document.getElementById('cart-footer');

        if (items.length === 0) {
            emptyCart.classList.remove('hidden');
            cartItemsList.classList.add('hidden');
            cartFooter.classList.add('hidden');
            return;
        }

        emptyCart.classList.add('hidden');
        cartItemsList.classList.remove('hidden');
        cartFooter.classList.remove('hidden');

        // Save items globally for coupon & subtotal
        window.currentCartItems = items;

        // Show bulk bar
        const bulkBar = document.getElementById('cart-bulk-bar');
        if (bulkBar) bulkBar.classList.remove('hidden');

        // Keep previously selected ids before re-render
        const previouslySelected = Array.from(document.querySelectorAll('.sidebar-item-checkbox:checked')).map(cb => cb
            .value);

        // Render items
        cartItemsList.innerHTML = items.map(item => {
            const price = parseFloat(item.price) || 0;
            let variantHtml = '';
            if (item.attributes && item.attributes.length > 0) {
                variantHtml = `<div class="text-xs text-gray-500">` +
                    item.attributes.map(attr => `${attr.name}: ${attr.value}`).join(', ') +
                    `</div>`;
            }
            return `
            <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg cart-item-enter" data-id="${item.id}">
                <input type="checkbox" class="sidebar-item-checkbox w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]" value="${item.id}">
                <img src="${item.image || '/images/default-product.jpg'}" alt="${item.name}" class="w-14 h-14 object-cover rounded-lg">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 text-sm">${item.name}</h4>
                    ${variantHtml}
                    <p class="text-orange-500 font-semibold">${formatPrice(price)}</p>
                    <div class="flex items-center space-x-2 mt-1">
                        <button onclick="changeSidebarQuantity('${item.id}', -1)" class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100">-</button>
                        <span class="text-sm sidebar-qty" data-id="${item.id}">${item.quantity}</span>
                        <button onclick="changeSidebarQuantity('${item.id}', 1)" class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100">+</button>
                    </div>
                </div>
                <button onclick="removeFromCart('${item.id}')" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        `;
        }).join('');

        // Re-apply previous selections
        previouslySelected.forEach(id => {
            const cb = cartItemsList.querySelector(`.sidebar-item-checkbox[value="${id}"]`);
            if (cb) cb.checked = true;
        });

        // Init selection logic and recalc subtotal
        initSidebarSelection();
        recalcSelectedSubtotal();
    }

    function initSidebarSelection() {
        const selectAll = document.getElementById('sidebar-select-all');
        const itemCheckboxes = document.querySelectorAll('.sidebar-item-checkbox');
        const deleteBtn = document.getElementById('sidebar-delete-selected');
        const buyBtn = document.getElementById('sidebar-buy-selected');
        const checkoutNowBtn = document.getElementById('sidebar-checkout-now');

        if (!selectAll) return;

        function updateState() {
            const checked = Array.from(itemCheckboxes).filter(c => c.checked);
            const any = checked.length > 0;

            deleteBtn.disabled = !any;
            buyBtn.disabled = !any;
            if (checkoutNowBtn) checkoutNowBtn.disabled = !any;

            // Select all indicator
            selectAll.checked = (itemCheckboxes.length > 0) && (checked.length === itemCheckboxes.length);

            // Recalculate subtotal based on selection
            recalcSelectedSubtotal();
        }

        selectAll.addEventListener('change', () => {
            itemCheckboxes.forEach(c => c.checked = selectAll.checked);
            updateState();
        });
        itemCheckboxes.forEach(c => c.addEventListener('change', updateState));

        deleteBtn.addEventListener('click', () => {
            const ids = Array.from(itemCheckboxes).filter(c => c.checked).map(c => c.value);
            if (ids.length === 0) return;
            if (!confirm('Xóa các sản phẩm đã chọn?')) return;
            deleteBtn.disabled = true;
            Promise.all(ids.map(id => removeFromCartSidebar({
                    id,
                    silent: true
                })))
                .then(() => {
                    showNotification('Đã xóa các sản phẩm khỏi giỏ hàng', 'success');
                    loadCartItems();
                })
                .finally(() => {
                    deleteBtn.disabled = false;
                });
        });

        buyBtn.addEventListener('click', () => {
            const ids = Array.from(itemCheckboxes).filter(c => c.checked).map(c => c.value);
            if (ids.length === 0) return;
            localStorage.setItem('checkout_selected_items', JSON.stringify(ids));
            window.location.href = '{{ route('checkout.index') }}?selected=' + ids.join(',');
        });

        updateState(); // initialize state
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
        fetch('{{ route('carts.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadCartItems();
                    showNotification('Đã thêm sản phẩm vào giỏ hàng', 'success');
                } else {
                    showNotification(data.message || 'Có lỗi xảy ra', 'error');
                }
            })
            .catch(() => showNotification('Có lỗi xảy ra', 'error'));
    }

    function updateCartQuantity(itemId, newQuantity) {
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
                credentials: 'same-origin',
                body: JSON.stringify({
                    quantity: newQuantity
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const qtySpan = document.querySelector(`.sidebar-qty[data-id="${itemId}"]`);
                    if (qtySpan) qtySpan.textContent = newQuantity;
                    if (Array.isArray(window.currentCartItems)) {
                        const it = window.currentCartItems.find(i => String(i.id) === String(itemId));
                        if (it) it.quantity = newQuantity;
                    }
                    recalcSelectedSubtotal();
                } else {
                    showNotification(data.message || 'Có lỗi xảy ra khi cập nhật', 'error');
                }
            })
            .catch(() => showNotification('Có lỗi xảy ra khi cập nhật', 'error'));
    }

    function changeSidebarQuantity(itemId, delta) {
        const qtySpan = document.querySelector(`.sidebar-qty[data-id="${itemId}"]`);
        if (!qtySpan) return;
        const current = parseInt(qtySpan.textContent) || 0;
        const next = current + delta;
        updateCartQuantity(itemId, next);
    }

    function removeFromCart(itemId) {
        return removeFromCartSidebar(itemId);
    }

    function removeFromCartSidebar(item) {
        let silent = false;
        let itemId = item;
        if (typeof item === 'object' && item !== null) {
            silent = item.silent;
            itemId = item.id;
        }
        return fetch(`{{ url('/carts') }}/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (!silent) showNotification('Đã xóa sản phẩm khỏi giỏ hàng', 'success');
                } else {
                    if (!silent) showNotification(data.message || 'Có lỗi xảy ra khi xóa', 'error');
                    if (data.debug) console.error('Debug info:', data.debug);
                }
                return data;
            })
            .catch(() => {
                if (!silent) showNotification('Có lỗi xảy ra khi xóa', 'error');
            });
    }

    function showNotification(message, type = 'info') {
        const el = document.createElement('div');
        el.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            } text-white`;
        el.textContent = message;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    }

    function updateCartCount() {
        fetch('{{ route('carts.count') }}')
            .then(r => r.json())
            .then(data => {
                document.getElementById('cart-count').textContent = data.count || 0;
            })
            .catch(e => console.error('Error updating cart count:', e));
    }

    // ====== Coupon (sidebar) ======
    function applySidebarCoupon() {
        const codeInput = document.getElementById('sidebar-coupon-code');
        const messageEl = document.getElementById('sidebar-coupon-message');
        if (!codeInput) return;
        const code = codeInput.value.trim();
        if (!code) {
            messageEl.textContent = 'Nhập mã giảm giá';
            messageEl.className = 'text-xs mt-1 text-red-500';
            return;
        }
        let subtotal = getSelectedRawSubtotal();
        messageEl.textContent = 'Đang kiểm tra...';
        messageEl.className = 'text-xs mt-1 text-gray-500';
        fetch('/api/apply-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    coupon_code: code,
                    subtotal
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const amount = data.discount_amount || 0;
                    localStorage.setItem('appliedDiscount', JSON.stringify({
                        code,
                        amount,
                        details: {
                            min_order_value: data.coupon.min_order_value || 0,
                            max_order_value: data.coupon.max_order_value || 0,
                            discount_type: data.coupon.discount_type,
                            value: data.coupon.value
                        },
                        fromDatabase: true
                    }));
                    messageEl.textContent = data.coupon && data.coupon.message ? data.coupon.message :
                        'Áp dụng thành công';
                    messageEl.className = 'text-xs mt-1 text-green-600';
                } else {
                    localStorage.removeItem('appliedDiscount');
                    messageEl.textContent = data.message || 'Mã không hợp lệ';
                    messageEl.className = 'text-xs mt-1 text-red-500';
                }
                recalcSelectedSubtotal();
            })
            .catch(() => {
                messageEl.textContent = 'Lỗi áp dụng mã';
                messageEl.className = 'text-xs mt-1 text-red-500';
            });
    }

    function toggleCouponList() {
        const box = document.getElementById('available-coupons');
        if (!box) return;
        if (box.classList.contains('hidden')) {
            loadAvailableCoupons();
            box.classList.remove('hidden');
            document.getElementById('toggle-coupon-list-btn').textContent = 'Ẩn';
        } else {
            box.classList.add('hidden');
            document.getElementById('toggle-coupon-list-btn').textContent = 'Danh sách';
        }
    }

    function loadAvailableCoupons() {
        const box = document.getElementById('available-coupons');
        if (!box) return;
        let subtotal = getSelectedRawSubtotal();
        fetch(`/api/coupons?subtotal=${subtotal}`)
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                if (!Array.isArray(data.coupons) || data.coupons.length === 0) {
                    box.innerHTML = '<p class="text-gray-500">Không có mã phù hợp</p>';
                    return;
                }
                box.innerHTML = data.coupons.map(c => {
                    const can = c.eligible;
                    const cls = can ? 'border-green-300 bg-white hover:border-[#ff6c2f] cursor-pointer' :
                        'border-gray-200 bg-gray-100 opacity-60 cursor-not-allowed';
                    const line = c.discount_type === 'percent' ? `Giảm ${c.value}%` :
                        `Giảm ${Number(c.value).toLocaleString()}₫`;
                    const cond = c.ineligible_reason ?
                        `(<span class="text-red-500">${c.ineligible_reason}</span>)` : '';
                    return `<div class="coupon-item border rounded p-2 ${cls}" data-code="${c.code}" data-eligible="${can}">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold">${c.code}</span>
                                <span class="text-[#ff6c2f] font-medium">${line}</span>
                            </div>
                            <div class="text-[10px] text-gray-600 mt-1">${cond}</div>
                        </div>`;
                }).join('');
                box.querySelectorAll('.coupon-item').forEach(div => {
                    div.addEventListener('click', () => {
                        if (div.dataset.eligible !== 'true') return;
                        box.querySelectorAll('.coupon-item.coupon-selected').forEach(el => el
                            .classList.remove('coupon-selected', 'border-[#ff6c2f]'));
                        div.classList.add('coupon-selected', 'border-[#ff6c2f]');
                        document.getElementById('sidebar-coupon-code').value = div.dataset.code;
                        const msg = document.getElementById('sidebar-coupon-message');
                        if (msg) {
                            msg.textContent = 'Đã chọn mã, bấm Áp dụng để xác nhận';
                            msg.className = 'text-xs mt-1 text-gray-600';
                        }
                    });
                });
            })
            .catch(() => {
                box.innerHTML = '<p class="text-red-500">Lỗi tải mã</p>';
            });
    }

    function clearSidebarCoupon() {
        localStorage.removeItem('appliedDiscount');
        const input = document.getElementById('sidebar-coupon-code');
        const msg = document.getElementById('sidebar-coupon-message');
        if (input) input.value = '';
        if (msg) {
            msg.textContent = '';
            msg.className = 'text-xs mt-1';
        }
        recalcSelectedSubtotal();
    }

    // ================= Subtotal & Selection Helpers =================
    function getSelectedRawSubtotal() {
        const selectedIds = Array.from(document.querySelectorAll('.sidebar-item-checkbox:checked')).map(c => c.value);
        if (!selectedIds.length) return 0;
        let sum = 0;
        (window.currentCartItems || []).forEach(i => {
            if (selectedIds.includes(String(i.id))) {
                const p = parseFloat(i.price) || 0;
                const q = parseInt(i.quantity) || 0;
                sum += p * q;
            }
        });
        return sum;
    }

    function recalcSelectedSubtotal() {
        const subtotalElement = document.getElementById('cart-subtotal');
        if (!subtotalElement) return;
        const discountRow = document.getElementById('sidebar-discount-row');
        const discountAmountEl = document.getElementById('sidebar-discount-amount');
        const codeInput = document.getElementById('sidebar-coupon-code');
        const messageEl = document.getElementById('sidebar-coupon-message');

        let raw = getSelectedRawSubtotal();
        let discount = 0;
        let hasCoupon = false;

        try {
            const saved = localStorage.getItem('appliedDiscount');
            if (saved) {
                const d = JSON.parse(saved);
                if (d && d.code) {
                    if (codeInput && codeInput.value && codeInput.value.trim().toUpperCase() !== d.code.toUpperCase()) {
                        localStorage.removeItem('appliedDiscount');
                    } else if (codeInput && !codeInput.value.trim()) {
                        localStorage.removeItem('appliedDiscount');
                    } else {
                        const minReq = Number(d.details?.min_order_value || 0);
                        const maxReq = Number(d.details?.max_order_value || 0);
                        if ((minReq && raw < minReq) || (maxReq && raw > maxReq)) {
                            localStorage.removeItem('appliedDiscount');
                            if (messageEl) {
                                messageEl.textContent = 'Mã không còn đủ điều kiện và đã bị hủy';
                                messageEl.className = 'text-xs mt-1 text-red-500';
                            }
                        } else {
                            hasCoupon = true;
                            const storedAmount = Number(d.amount) || 0;
                            discount = Math.min(storedAmount, raw);
                        }
                    }
                }
            }
        } catch (e) {}

        if (hasCoupon) {
            discountRow.classList.remove('hidden');
            const displayAmount = discount > 0 ? discount : 0;
            discountAmountEl.textContent = '-' + new Intl.NumberFormat('vi-VN').format(displayAmount) + '₫';
        } else {
            discountRow.classList.add('hidden');
        }

        subtotalElement.textContent = formatPrice(Math.max(0, raw - discount));

        // Cập nhật trạng thái nút "Thanh toán ngay" (phòng khi gọi trực tiếp từ nơi khác)
        const checkoutNowBtn = document.getElementById('sidebar-checkout-now');
        if (checkoutNowBtn) checkoutNowBtn.disabled = raw <= 0;
    }

    // === Checkout handler (chỉ cho phép khi đã chọn) ===
    function handleSidebarCheckout() {
        const selected = Array.from(document.querySelectorAll('.sidebar-item-checkbox:checked')).map(c => c.value);
        if (selected.length > 0) {
            localStorage.setItem('checkout_selected_items', JSON.stringify(selected));
            window.location.href = '{{ route('checkout.index') }}?selected=' + selected.join(',');
        } else {
            // KHÔNG cho đi checkout khi chưa chọn
            alert('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán');
        }
    }

    // Expose some functions globally if needed elsewhere
    window.loadCartItems = loadCartItems;
    window.updateCartCount = updateCartCount;
    window.showNotification = showNotification;
    window.applySidebarCoupon = applySidebarCoupon;
    window.toggleCouponList = toggleCouponList;
    window.clearSidebarCoupon = clearSidebarCoupon;
    window.recalcSelectedSubtotal = recalcSelectedSubtotal;
    window.handleSidebarCheckout = handleSidebarCheckout;
</script>
