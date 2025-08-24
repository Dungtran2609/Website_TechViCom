<!-- Header (all category items use the same icon) -->
<header id="main-header" class="bg-white shadow-lg border-b border-gray-100 fixed top-0 left-0 right-0 z-40 transition-transform duration-300">
    <link rel="stylesheet" href="{{ asset('client_css/css/header-optimized.css') }}">

    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between flex-nowrap gap-4">
            <!-- Logo -->
            <div class="flex items-center flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    @php
                        $clientLogo = \App\Models\Logo::where('type', 'client')->orderByDesc('id')->first();
                    @endphp
                    <img src="{{ $clientLogo ? asset('storage/' . $clientLogo->path) : asset('admin_css/images/logo_techvicom.png') }}" alt="{{ $clientLogo->alt ?? 'Techvicom' }}" class="w-10 h-10 rounded-lg mr-3 object-cover">
                    <span class="text-xl font-bold text-gray-800">Techvicom</span>
                </a>
            </div>

            <!-- Category (hover to open, stable) -->
            <div class="ml-2 lg:ml-6 dropdown-group">
                <button
                    class="flex items-center space-x-2 px-4 lg:px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl hover:from-orange-600 hover:to-orange-700 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5"
                    data-dropdown="category">
                    <i class="fas fa-bars text-white"></i>
                    <span class="hidden sm:inline text-white font-semibold">Danh mục</span>
                </button>
                <!-- Category Dropdown (ALL ITEMS USE SAME ICON) -->
                <div id="categoryDropdown"
                    class="dropdown-panel absolute top-full left-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-th-large text-orange-500 mr-2"></i>
                            Danh mục sản phẩm
                        </h3>
                        <div class="grid grid-cols-2 gap-2">
                            @if (isset($categories) && $categories->count() > 0)
                                @foreach ($categories->take(6) as $category)
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                        class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                        <!-- Shared icon (ignore per-category images) -->
                                        <div class="cat-icon mr-3 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-tags text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="text-gray-700 font-medium">{{ $category->name }}</span>
                                            <p class="text-xs text-gray-500">{{ $category->children->count() }} danh mục
                                                con</p>
                                        </div>
                                    </a>
                                @endforeach
                            @endcan
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <a href="{{ route('categories.index') }}"
                                class="flex items-center justify-center text-orange-600 hover:text-orange-700 font-medium transition">
                                <span>Xem tất cả danh mục</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="flex-1 max-w-2xl mx-4 lg:mx-8 w-full lg:w-auto">
                <div class="relative my-2">
                    <input type="text" id="header-search-input" placeholder="Nhập để tìm kiếm sản phẩm..."
                        class="w-full px-5 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all duration-300 shadow-sm hover:shadow-md">
                    <button id="header-search-btn"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-orange-500 transition-colors duration-200">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                    
                    <!-- Search History Dropdown -->
                    <div id="search-history-dropdown" class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-xl border border-gray-200 z-50 hidden">
                        <div class="p-3 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-gray-700">Lịch sử tìm kiếm</h4>
                                <button id="clear-search-history" class="text-xs text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-trash mr-1"></i>Xóa tất cả
                                </button>
                            </div>
                        </div>
                        <div id="search-history-list" class="max-h-60 overflow-y-auto">
                            <!-- Search history items will be populated here -->
                        </div>
                        <div id="search-suggestions" class="p-3 border-t border-gray-100 hidden">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Gợi ý tìm kiếm</h4>
                            <div id="suggestions-list" class="space-y-1">
                                <!-- Suggestions will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right section -->
            <div class="flex flex-nowrap items-center space-x-3 lg:space-x-4 justify-end">
                <!-- Account (hover to open, stable) -->
                <div class="dropdown-group">
                                    <button
                    class="flex items-center space-x-1 lg:space-x-2 px-4 lg:px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl hover:from-orange-600 hover:to-orange-700 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5"
                    data-dropdown="account">
                        @auth
                            @if (Auth::user()->image_profile && file_exists(public_path('uploads/users/' . Auth::user()->image_profile)))
                                <img src="{{ asset('uploads/users/' . Auth::user()->image_profile) }}"
                                    alt="{{ Auth::user()->name }}"
                                    class="w-6 h-6 rounded-full object-cover border border-white"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                <i class="fas fa-user" style="display:none;"></i>
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        @else
                            <i class="fas fa-user"></i>
                        @endauth
                        <span class="hidden lg:inline font-medium">
                            @auth {{ Str::limit(Auth::user()->name, 15) }}
                            @else
                            Tài khoản @endauth
                        </span>
                        <i class="fas fa-chevron-down ml-1 text-sm"></i>
                    </button>

                    <div id="accountDropdown"
                        class="dropdown-panel absolute top-full right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        @guest
                            <div class="p-4">
                                <div class="text-center mb-4">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-600 text-sm">Đăng nhập để trải nghiệm đầy đủ</p>
                                </div>
                                <div class="space-y-2">
                                    <button type="button" onclick="openAuthModalAndShowLogin()"
                                        class="block w-full text-center bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">Đăng
                                        nhập</button>
                                    <button type="button" onclick="openAuthModalAndShowRegister()"
                                        class="block w-full text-center border border-orange-500 text-orange-500 py-2 rounded-lg hover:bg-orange-50 transition">Đăng
                                        ký</button>
                                </div>
                            </div>
                        @else
                            <div>
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex items-center">
                                        <div
                                            class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-orange-500"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
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
                                        <i class="fas fa-user-circle mr-3 text-gray-400"></i> Thông tin tài khoản
                                    </a>
                                    <a href="{{ route('accounts.orders') }}"
                                        class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                        <i class="fas fa-shopping-bag mr-3 text-gray-400"></i> Đơn hàng của tôi
                                    </a>
                                    <a href="{{ route('accounts.addresses') }}"
                                        class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                        <i class="fas fa-map-marker-alt mr-3 text-gray-400"></i> Địa chỉ giao hàng
                                    </a>
                                    <a href="{{ route('products.love') }}"
                                        class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                                        <i class="fas fa-heart mr-3 text-gray-400"></i> Sản phẩm yêu thích
                                    </a>

                                    <div class="border-t border-gray-200 my-2"></div>
                                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-3 py-2 text-[#ff6c2f] hover:bg-orange-50 rounded-lg transition">
                                            <i class="fas fa-sign-out-alt mr-3"></i> Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>

                <!-- Cart (icon only) -->
                <div class="relative">
                    <button id="cartMenuBtn"
                        class="flex items-center px-4 lg:px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl hover:from-orange-600 hover:to-orange-700 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 relative">
                        <i class="fas fa-shopping-basket text-lg"></i>
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold shadow-lg animate-pulse"
                            id="cart-count">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Cart Sidebar -->
<div id="cart-sidebar"
    class="fixed inset-y-0 right-0 w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900"><i class="fas fa-shopping-cart mr-2 text-orange-500"></i> Giỏ
            hàng của bạn</h3>
        <button id="close-cart-sidebar" class="p-2 hover:bg-gray-100 rounded-lg transition"><i
                class="fas fa-times text-gray-500"></i></button>
    </div>

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

        <div id="empty-cart" class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
            </div>
            <h4 class="text-gray-600 font-medium mb-2">Giỏ hàng trống</h4>
            <p class="text-gray-500 text-sm mb-4">Thêm sản phẩm vào giỏ hàng để bắt đầu mua sắm</p>
            <button onclick="window.location.href='{{ route('home') }}'"
                class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">Tiếp tục mua
                sắm</button>
        </div>

        <div id="cart-items-list" class="space-y-4 hidden"></div>
    </div>

    <div id="cart-footer" class="border-t border-gray-200 p-4 hidden">
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

        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600">Tạm tính:</span>
            <span class="text-lg font-semibold text-gray-900" id="cart-subtotal">0₫</span>
        </div>

        <div class="space-y-2">
            <button onclick="window.location.href='{{ route('carts.index') }}'"
                class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition">Xem giỏ
                hàng</button>
            <button id="sidebar-checkout-now"
                class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition disabled:opacity-40 disabled:cursor-not-allowed"
                disabled>Thanh toán ngay</button>
        </div>
    </div>
</div>

<!-- Overlay -->
<div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<style>
    /* Search History Dropdown Styles */
    #search-history-dropdown {
        animation: slideDown 0.2s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .search-history-item {
        transition: all 0.2s ease;
    }
    
    .search-history-item:hover {
        background-color: #f8fafc;
    }
    
    .suggestion-item {
        transition: all 0.2s ease;
    }
    
    .suggestion-item:hover {
        background-color: #f8fafc;
    }
    
    .remove-history-item {
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .search-history-item:hover .remove-history-item {
        opacity: 1;
    }
    
    /* Highlight search terms */
    mark {
        background-color: #fef3c7;
        color: #92400e;
        padding: 0 2px;
        border-radius: 2px;
    }
    
    /* Scrollbar styling for search history */
    #search-history-list::-webkit-scrollbar {
        width: 6px;
    }
    
    #search-history-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    #search-history-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    #search-history-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>

<script>
    /* Header scroll effect */
    (function() {
        let lastScrollTop = 0;
        const header = document.getElementById('main-header');
        const scrollThreshold = 10; // Minimum scroll distance to trigger effect
        
        function handleScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollDelta = scrollTop - lastScrollTop;
            
            // Only trigger if scroll distance is significant
            if (Math.abs(scrollDelta) > scrollThreshold) {
                if (scrollDelta > 0 && scrollTop > 100) {
                    // Scrolling down - hide header
                    header.classList.remove('header-visible');
                    header.classList.add('header-hidden');
                } else if (scrollDelta < 0) {
                    // Scrolling up - show header
                    header.classList.remove('header-hidden');
                    header.classList.add('header-visible');
                }
                lastScrollTop = scrollTop;
            }
        }
        
        // Throttle scroll events for better performance
        let ticking = false;
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(() => {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick, { passive: true });
        
        // Show header when at top of page
        window.addEventListener('scroll', () => {
            if (window.pageYOffset <= 100) {
                header.classList.remove('header-hidden');
                header.classList.add('header-visible');
            }
        }, { passive: true });
    })();

    /* Stable hover dropdowns */
    (function() {
        const groups = document.querySelectorAll('.dropdown-group');
        groups.forEach(g => {
            let hideTimer = null;
            const open = () => {
                clearTimeout(hideTimer);
                g.classList.add('open');
            };
            const close = () => {
                hideTimer = setTimeout(() => g.classList.remove('open'), 120);
            };
            g.addEventListener('mouseenter', open);
            g.addEventListener('mouseleave', close);
            const btn = g.querySelector('button,[data-dropdown]');
            const panel = g.querySelector('.dropdown-panel');
            if (btn) {
                btn.addEventListener('focus', open);
                btn.addEventListener('blur', close);
            }
            if (panel) {
                panel.addEventListener('mouseenter', open);
                panel.addEventListener('mouseleave', close);
                panel.addEventListener('click', open);
            }
        });
    })();

    /* Cart, search & existing logic */
    document.addEventListener('DOMContentLoaded', function() {
        const cartBtn = document.getElementById('cartMenuBtn');
        const cartSidebar = document.getElementById('cart-sidebar');
        const cartOverlay = document.getElementById('cart-overlay');
        const closeCartBtn = document.getElementById('close-cart-sidebar');

        if (cartBtn) {
            cartBtn.addEventListener('click', function() {
                cartSidebar.classList.remove('translate-x-full');
                cartOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }

        function closeCartSidebar() {
            cartSidebar.classList.add('translate-x-full');
            cartOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        window.closeCartSidebar = closeCartSidebar;
        
        // Category submenu toggle functionality
        document.querySelectorAll('.toggle-submenu').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const categoryId = this.dataset.categoryId;
                const submenu = document.getElementById(`submenu-${categoryId}`);
                const icon = this.querySelector('i');
                
                // Toggle submenu
                if (submenu.style.display === 'none') {
                    submenu.style.display = 'block';
                    this.classList.add('active');
                } else {
                    submenu.style.display = 'none';
                    this.classList.remove('active');
                }
            });
        });
        if (closeCartBtn) closeCartBtn.addEventListener('click', closeCartSidebar);
        if (cartOverlay) cartOverlay.addEventListener('click', closeCartSidebar);

        document.addEventListener('cart:updated', function() {
            loadCartItems();
        });

        const headerSearchInput = document.getElementById('header-search-input');
        const headerSearchBtn = document.getElementById('header-search-btn');

        function performHeaderSearch() {
            const s = headerSearchInput.value.trim();
            if (s) {
                addToSearchHistory(s);
                window.location.href = `{{ route('products.index') }}?search=${encodeURIComponent(s)}`;
            }
        }
        if (headerSearchBtn) headerSearchBtn.addEventListener('click', performHeaderSearch);
        if (headerSearchInput) headerSearchInput.addEventListener('keypress', e => {
            if (e.key === 'Enter') performHeaderSearch();
        });

        loadCartItems();
        const checkoutNowBtn = document.getElementById('sidebar-checkout-now');
        if (checkoutNowBtn) checkoutNowBtn.addEventListener('click', handleSidebarCheckout);
        
        // Search History Management
        initSearchHistory();
    });

    function updateAuthenticationUI(isLoggedIn, userData = null) {
        console.log('Authentication:', isLoggedIn ? 'in' : 'out');
        if (userData) console.log('User data:', userData);
    }

    function loadCartItems() {
        fetch('{{ route('carts.count') }}').then(r => r.json()).then(d => {
                document.getElementById('cart-count').textContent = d.count || 0;
                return fetch('{{ route('carts.index') }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
            }).then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json()
            })
            .then(d => {
                if (d.success && d.items) updateCartDisplay(d.items);
                else {
                    console.error('Invalid cart data', d);
                    updateCartDisplay([])
                }
            })
            .catch(e => {
                console.error('Error loading cart:', e);
                updateCartCount();
            });
    }

    function updateCartDisplay(items) {
        const emptyCart = document.getElementById('empty-cart');
        const list = document.getElementById('cart-items-list');
        const footer = document.getElementById('cart-footer');

        if (items.length === 0) {
            emptyCart.classList.remove('hidden');
            list.classList.add('hidden');
            footer.classList.add('hidden');
            return;
        }
        emptyCart.classList.add('hidden');
        list.classList.remove('hidden');
        footer.classList.remove('hidden');

        window.currentCartItems = items;

        const bulkBar = document.getElementById('cart-bulk-bar');
        if (bulkBar) bulkBar.classList.remove('hidden');

        const prevSel = Array.from(document.querySelectorAll('.sidebar-item-checkbox:checked')).map(cb => cb.value);

        list.innerHTML = items.map(item => {
            const price = parseFloat(item.price) || 0;
            let variantHtml = '';
            if (item.attributes && item.attributes.length > 0) {
                variantHtml =
                    `<div class="text-xs text-gray-500">${item.attributes.map(a=>`${a.name}: ${a.value}`).join(', ')}</div>`;
            }
            const isOutOfStock = (item.stock !== undefined && item.stock <= 0);
            return `<div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg cart-item-enter" data-id="${item.id}">
      <input type="checkbox" class="sidebar-item-checkbox w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]" value="${item.id}" ${isOutOfStock ? 'disabled' : ''}>
    <img src="${item.type === 'simple' ? (item.thumbnail ? '/storage/' + item.thumbnail : '/images/default-product.jpg') : (item.image||'/images/default-product.jpg')}" alt="${item.name}" class="w-14 h-14 object-cover rounded-lg">
      <div class="flex-1">
        <h4 class="font-medium text-gray-900 text-sm">
          <a href="/products/${item.product_id}" class="hover:text-[#ff6c2f] transition-colors">
            ${item.name}
          </a>
        </h4>
        ${variantHtml}
        <p class="text-orange-500 font-semibold">${formatPrice(price)}</p>
        ${
          isOutOfStock
            ? `<div class=\"text-red-600 font-bold text-sm\">Hết hàng</div>`
            : `<div class=\"flex items-center space-x-2 mt-1\">
                <button onclick=\"changeSidebarQuantity('${item.id}',-1)\" class=\"w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100\">-</button>
                <span class=\"text-sm sidebar-qty\" data-id=\"${item.id}\">${item.quantity}</span>
                <button onclick=\"changeSidebarQuantity('${item.id}',1)\" class=\"w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100\">+</button>
              </div>`
        }
      </div>
      <button onclick="removeFromCart('${item.id}')" class="text-red-500 hover:text-red-700"><i class="fas fa-trash text-sm"></i></button>
    </div>`;
        }).join('');

        prevSel.forEach(id => {
            const cb = list.querySelector(`.sidebar-item-checkbox[value="${id}"]`);
            if (cb) cb.checked = true;
        });

        initSidebarSelection();
        recalcSelectedSubtotal();
    }

    function initSidebarSelection() {
        const selectAll = document.getElementById('sidebar-select-all');
        const boxes = document.querySelectorAll('.sidebar-item-checkbox');
        const delBtn = document.getElementById('sidebar-delete-selected');
        const buyBtn = document.getElementById('sidebar-buy-selected');
        const checkoutNowBtn = document.getElementById('sidebar-checkout-now');
        if (!selectAll) return;

        function update() {
            const checked = Array.from(boxes).filter(c => c.checked);
            const any = checked.length > 0;
            delBtn.disabled = !any;
            buyBtn.disabled = !any;
            if (checkoutNowBtn) checkoutNowBtn.disabled = !any;
            selectAll.checked = (boxes.length > 0) && (checked.length === boxes.length);
            recalcSelectedSubtotal();
        }

        selectAll.addEventListener('change', () => {
            boxes.forEach(c => {
                if (!c.disabled) c.checked = selectAll.checked;
            });
            update();
        });
        boxes.forEach(c => c.addEventListener('change', update));

        delBtn.addEventListener('click', () => {
            const ids = Array.from(boxes).filter(c => c.checked).map(c => c.value);
            if (!ids.length) return;
            if (!confirm('Xóa các sản phẩm đã chọn?')) return;
            delBtn.disabled = true;
            Promise.all(ids.map(id => removeFromCartSidebar({
                    id,
                    silent: true
                })))
                .then(() => {
                    showNotification('Đã xóa các sản phẩm khỏi giỏ hàng', 'success');
                    loadCartItems();
                })
                .finally(() => {
                    delBtn.disabled = false;
                });
        });

        buyBtn.addEventListener('click', () => {
            const ids = Array.from(boxes)
                .filter(c => c.checked && !c.disabled)
                .map(c => c.value);
            if (!ids.length) return;
            localStorage.setItem('checkout_selected_items', JSON.stringify(ids));
            window.location.href = '{{ route('checkout.index') }}?selected=' + ids.join(',');
        });

        update();
    }

    function formatPrice(p) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(p)
    }

    function addToCart(productId, variantId = null, quantity = 1) {
        const data = {
            product_id: productId,
            quantity,
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
            .then(d => {
                if (d.success) {
                    loadCartItems();
                    showNotification('Đã thêm sản phẩm vào giỏ hàng', 'success')
                } else showNotification(d.message || 'Có lỗi xảy ra', 'error')
            })
            .catch(() => showNotification('Có lỗi xảy ra', 'error'));
    }

    function updateCartQuantity(itemId, newQuantity) {
        if (newQuantity < 1) {
            removeFromCart(itemId);
            return
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
            .then(d => {
                if (d.success) {
                    const span = document.querySelector(`.sidebar-qty[data-id="${itemId}"]`);
                    if (span) span.textContent = newQuantity;
                    if (Array.isArray(window.currentCartItems)) {
                        const it = window.currentCartItems.find(i => String(i.id) === String(itemId));
                        if (it) it.quantity = newQuantity;
                    }
                    recalcSelectedSubtotal();
                } else showNotification(d.message || 'Có lỗi xảy ra khi cập nhật', 'error');
            })
            .catch(() => showNotification('Có lỗi xảy ra khi cập nhật', 'error'));
    }

    function changeSidebarQuantity(itemId, delta) {
        const span = document.querySelector(`.sidebar-qty[data-id="${itemId}"]`);
        if (!span) return;
        const cur = parseInt(span.textContent) || 0;
        updateCartQuantity(itemId, cur + delta);
    }

    function removeFromCart(itemId) {
        return removeFromCartSidebar(itemId)
    }

    function removeFromCartSidebar(item) {
        let silent = false,
            itemId = item;
        if (typeof item === 'object' && item !== null) {
            silent = item.silent;
            itemId = item.id
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
            .then(d => {
                if (d.success) {
                    if (!silent) showNotification('Đã xóa sản phẩm khỏi giỏ hàng', 'success')
                } else {
                    if (!silent) showNotification(d.message || 'Có lỗi xảy ra khi xóa', 'error');
                    if (d.debug) console.error('Debug:', d.debug)
                }
                return d;
            })
            .catch(() => {
                if (!silent) showNotification('Có lỗi xảy ra khi xóa', 'error')
            });
    }

    function showNotification(msg, type = 'info') {
        const el = document.createElement('div');
        el.className =
            `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${type==='success'?'bg-green-500':type==='error'?'bg-red-500':'bg-blue-500'} text-white`;
        el.textContent = msg;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    }

    function updateCartCount() {
        fetch('{{ route('carts.count') }}').then(r => r.json()).then(d => {
            document.getElementById('cart-count').textContent = d.count || 0
        }).catch(e => console.error('Error updating cart count:', e));
    }

    /* Coupon */
    function applySidebarCoupon() {
        const codeInput = document.getElementById('sidebar-coupon-code');
        const msg = document.getElementById('sidebar-coupon-message');
        if (!codeInput) return;
        const code = codeInput.value.trim();
        if (!code) {
            msg.textContent = 'Nhập mã giảm giá';
            msg.className = 'text-xs mt-1 text-red-500';
            return
        }
        const subtotal = getSelectedRawSubtotal();
        msg.textContent = 'Đang kiểm tra...';
        msg.className = 'text-xs mt-1 text-gray-500';
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
            .then(d => {
                if (d.success) {
                    const amount = d.discount_amount || 0;
                    localStorage.setItem('appliedDiscount', JSON.stringify({
                        code,
                        amount,
                        details: {
                            min_order_value: d.coupon.min_order_value || 0,
                            max_order_value: d.coupon.max_order_value || 0,
                            discount_type: d.coupon.discount_type,
                            value: d.coupon.value
                        },
                        fromDatabase: true
                    }));
                    msg.textContent = d.coupon && d.coupon.message ? d.coupon.message : 'Áp dụng thành công';
                    msg.className = 'text-xs mt-1 text-green-600';
                } else {
                    localStorage.removeItem('appliedDiscount');
                    msg.textContent = d.message || 'Mã không hợp lệ';
                    msg.className = 'text-xs mt-1 text-red-500';
                }
                recalcSelectedSubtotal();
            })
            .catch(() => {
                msg.textContent = 'Lỗi áp dụng mã';
                msg.className = 'text-xs mt-1 text-red-500'
            });
    }

    function toggleCouponList() {
        const box = document.getElementById('available-coupons');
        if (!box) return;
        if (box.classList.contains('hidden')) {
            loadAvailableCoupons();
            box.classList.remove('hidden');
            document.getElementById('toggle-coupon-list-btn').textContent = 'Ẩn'
        } else {
            box.classList.add('hidden');
            document.getElementById('toggle-coupon-list-btn').textContent = 'Danh sách'
        }
    }

    function loadAvailableCoupons() {
        const box = document.getElementById('available-coupons');
        if (!box) return;
        const subtotal = getSelectedRawSubtotal();
        fetch(`/api/coupons?subtotal=${subtotal}`).then(r => r.json()).then(d => {
            if (!d.success) return;
            if (!Array.isArray(d.coupons) || d.coupons.length === 0) {
                box.innerHTML = '<p class="text-gray-500">Không có mã phù hợp</p>';
                return
            }
            box.innerHTML = d.coupons.map(c => {
                const can = c.eligible;
                const cls = can ? 'border-green-300 bg-white hover:border-[#ff6c2f] cursor-pointer' :
                    'border-gray-200 bg-gray-100 opacity-60 cursor-not-allowed';
                const line = c.discount_type === 'percent' ? `Giảm ${c.value}%` :
                    `Giảm ${Number(c.value).toLocaleString()}₫`;
                const cond = c.ineligible_reason ?
                    `(<span class="text-red-500">${c.ineligible_reason}</span>)` : '';
                return `<div class="coupon-item border rounded p-2 ${cls}" data-code="${c.code}" data-eligible="${can}">
        <div class="flex justify-between items-center"><span class="font-semibold">${c.code}</span><span class="text-[#ff6c2f] font-medium">${line}</span></div>
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
                        msg.className = 'text-xs mt-1 text-gray-600'
                    }
                });
            });
        }).catch(() => {
            box.innerHTML = '<p class="text-red-500">Lỗi tải mã</p>'
        });
    }

    function clearSidebarCoupon() {
        localStorage.removeItem('appliedDiscount');
        const input = document.getElementById('sidebar-coupon-code');
        const msg = document.getElementById('sidebar-coupon-message');
        if (input) input.value = '';
        if (msg) {
            msg.textContent = '';
            msg.className = 'text-xs mt-1'
        }
        recalcSelectedSubtotal();
    }

    /* Subtotal helpers */
    function getSelectedRawSubtotal() {
        const ids = Array.from(document.querySelectorAll('.sidebar-item-checkbox:checked')).map(c => c.value);
        if (!ids.length) return 0;
        let sum = 0;
        (window.currentCartItems || []).forEach(i => {
            if (ids.includes(String(i.id))) {
                const p = parseFloat(i.price) || 0;
                const q = parseInt(i.quantity) || 0;
                sum += p * q;
            }
        });
        return sum;
    }

    function recalcSelectedSubtotal() {
        const subEl = document.getElementById('cart-subtotal');
        if (!subEl) return;
        const discountRow = document.getElementById('sidebar-discount-row');
        const discountAmountEl = document.getElementById('sidebar-discount-amount');
        const codeInput = document.getElementById('sidebar-coupon-code');
        const messageEl = document.getElementById('sidebar-coupon-message');
        let raw = getSelectedRawSubtotal();
        let discount = 0,
            has = false;
        try {
            const saved = localStorage.getItem('appliedDiscount');
            if (saved) {
                const d = JSON.parse(saved);
                if (d && d.code) {
                    if (codeInput && codeInput.value && codeInput.value.trim().toUpperCase() !== d.code.toUpperCase()) {
                        localStorage.removeItem('appliedDiscount')
                    } else if (codeInput && !codeInput.value.trim()) {
                        localStorage.removeItem('appliedDiscount')
                    } else {
                        const min = Number(d.details?.min_order_value || 0);
                        const max = Number(d.details?.max_order_value || 0);
                        if ((min && raw < min) || (max && raw > max)) {
                            localStorage.removeItem('appliedDiscount');
                            if (messageEl) {
                                messageEl.textContent = 'Mã không còn đủ điều kiện và đã bị hủy';
                                messageEl.className = 'text-xs mt-1 text-red-500'
                            }
                        } else {
                            has = true;
                            const amt = Number(d.amount) || 0;
                            discount = Math.min(amt, raw)
                        }
                    }
                }
            }
        } catch (e) {}
        if (has) {
            discountRow.classList.remove('hidden');
            discountAmountEl.textContent = '-' + new Intl.NumberFormat('vi-VN').format(discount > 0 ? discount : 0) +
                '₫'
        } else {
            discountRow.classList.add('hidden')
        }
        subEl.textContent = formatPrice(Math.max(0, raw - discount));
        const checkoutNowBtn = document.getElementById('sidebar-checkout-now');
        if (checkoutNowBtn) checkoutNowBtn.disabled = raw <= 0;
    }

    /* Checkout */
    function handleSidebarCheckout() {
        const selected = Array.from(document.querySelectorAll('.sidebar-item-checkbox:checked'))
            .filter(c => !c.disabled)
            .map(c => c.value);
        if (selected.length > 0) {
            localStorage.setItem('checkout_selected_items', JSON.stringify(selected));
            window.location.href = '{{ route('checkout.index') }}?selected=' + selected.join(',');
        } else {
            alert('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán');
        }
    }

    /* Expose helpers */
    window.loadCartItems = loadCartItems;
    window.updateCartCount = updateCartCount;
    window.showNotification = showNotification;
    window.applySidebarCoupon = applySidebarCoupon;
    window.toggleCouponList = toggleCouponList;
    window.clearSidebarCoupon = clearSidebarCoupon;
    window.recalcSelectedSubtotal = recalcSelectedSubtotal;
    window.handleSidebarCheckout = handleSidebarCheckout;

    // Search History Functions
    function initSearchHistory() {
        const searchInput = document.getElementById('header-search-input');
        const searchDropdown = document.getElementById('search-history-dropdown');
        const clearHistoryBtn = document.getElementById('clear-search-history');
        
        if (!searchInput || !searchDropdown) return;
        
        // Show dropdown on focus
        searchInput.addEventListener('focus', function() {
            if (this.value.trim() === '') {
                showSearchHistory();
            } else {
                showSearchSuggestions(this.value);
            }
        });
        
        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                hideSearchDropdown();
            }
        });
        
        // Handle input changes
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query === '') {
                showSearchHistory();
            } else {
                showSearchSuggestions(query);
            }
        });
        
        // Handle Enter key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    addToSearchHistory(query);
                    hideSearchDropdown();
                }
            }
        });
        
        // Clear history button
        if (clearHistoryBtn) {
            clearHistoryBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm('Bạn có chắc muốn xóa tất cả lịch sử tìm kiếm?')) {
                    clearSearchHistory();
                    hideSearchDropdown();
                }
            });
        }
    }
    
    function showSearchHistory() {
        const dropdown = document.getElementById('search-history-dropdown');
        const historyList = document.getElementById('search-history-list');
        const suggestions = document.getElementById('search-suggestions');
        
        if (!dropdown || !historyList) return;
        
        const history = getSearchHistory();
        
        if (history.length === 0) {
            historyList.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <i class="fas fa-search text-2xl mb-2"></i>
                    <p class="text-sm">Chưa có lịch sử tìm kiếm</p>
                </div>
            `;
        } else {
            historyList.innerHTML = history.map(item => `
                <div class="search-history-item flex items-center justify-between p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" data-query="${item.query}">
                    <div class="flex items-center flex-1">
                        <i class="fas fa-history text-gray-400 mr-3 text-sm"></i>
                        <span class="text-gray-700">${item.query}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-400">${formatSearchDate(item.timestamp)}</span>
                        <button class="remove-history-item text-gray-400 hover:text-red-500 transition-colors" data-query="${item.query}">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                </div>
            `).join('');
            
            // Add click handlers for history items
            historyList.querySelectorAll('.search-history-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (!e.target.closest('.remove-history-item')) {
                        const query = this.dataset.query;
                        document.getElementById('header-search-input').value = query;
                        performSearch(query);
                        hideSearchDropdown();
                    }
                });
            });
            
            // Add click handlers for remove buttons
            historyList.querySelectorAll('.remove-history-item').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const query = this.dataset.query;
                    removeFromSearchHistory(query);
                    showSearchHistory(); // Refresh the list
                });
            });
        }
        
        suggestions.classList.add('hidden');
        dropdown.classList.remove('hidden');
    }
    
    function showSearchSuggestions(query) {
        const dropdown = document.getElementById('search-history-dropdown');
        const historyList = document.getElementById('search-history-list');
        const suggestions = document.getElementById('search-suggestions');
        const suggestionsList = document.getElementById('suggestions-list');
        
        if (!dropdown || !suggestions || !suggestionsList) return;
        
        // Filter history for suggestions
        const history = getSearchHistory().filter(item => 
            item.query.toLowerCase().includes(query.toLowerCase())
        );
        
        // Show filtered history
        if (history.length > 0) {
            historyList.innerHTML = history.map(item => `
                <div class="search-history-item flex items-center justify-between p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" data-query="${item.query}">
                    <div class="flex items-center flex-1">
                        <i class="fas fa-history text-gray-400 mr-3 text-sm"></i>
                        <span class="text-gray-700">${highlightQuery(item.query, query)}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-400">${formatSearchDate(item.timestamp)}</span>
                        <button class="remove-history-item text-gray-400 hover:text-red-500 transition-colors" data-query="${item.query}">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                </div>
            `).join('');
            
            // Add click handlers
            historyList.querySelectorAll('.search-history-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (!e.target.closest('.remove-history-item')) {
                        const query = this.dataset.query;
                        document.getElementById('header-search-input').value = query;
                        performSearch(query);
                        hideSearchDropdown();
                    }
                });
            });
            
            historyList.querySelectorAll('.remove-history-item').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const query = this.dataset.query;
                    removeFromSearchHistory(query);
                    showSearchSuggestions(document.getElementById('header-search-input').value);
                });
            });
        } else {
            historyList.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <i class="fas fa-search text-2xl mb-2"></i>
                    <p class="text-sm">Không tìm thấy lịch sử phù hợp</p>
                </div>
            `;
        }
        
        // Show popular suggestions
        const popularSuggestions = getPopularSuggestions(query);
        if (popularSuggestions.length > 0) {
            suggestionsList.innerHTML = popularSuggestions.map(suggestion => `
                <div class="suggestion-item flex items-center p-2 hover:bg-gray-50 cursor-pointer rounded" data-query="${suggestion}">
                    <i class="fas fa-lightbulb text-yellow-400 mr-3 text-sm"></i>
                    <span class="text-gray-700">${highlightQuery(suggestion, query)}</span>
                </div>
            `).join('');
            
            suggestionsList.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', function() {
                    const query = this.dataset.query;
                    document.getElementById('header-search-input').value = query;
                    performSearch(query);
                    hideSearchDropdown();
                });
            });
            
            suggestions.classList.remove('hidden');
        } else {
            suggestions.classList.add('hidden');
        }
        
        dropdown.classList.remove('hidden');
    }
    
    function hideSearchDropdown() {
        const dropdown = document.getElementById('search-history-dropdown');
        if (dropdown) {
            dropdown.classList.add('hidden');
        }
    }
    
    function addToSearchHistory(query) {
        if (!query.trim()) return;
        
        let history = getSearchHistory();
        
        // Remove existing entry if exists
        history = history.filter(item => item.query.toLowerCase() !== query.toLowerCase());
        
        // Add new entry at the beginning
        history.unshift({
            query: query.trim(),
            timestamp: Date.now()
        });
        
        // Keep only last 10 searches
        history = history.slice(0, 10);
        
        localStorage.setItem('searchHistory', JSON.stringify(history));
    }
    
    function getSearchHistory() {
        try {
            const history = localStorage.getItem('searchHistory');
            return history ? JSON.parse(history) : [];
        } catch (e) {
            return [];
        }
    }
    
    function removeFromSearchHistory(query) {
        let history = getSearchHistory();
        history = history.filter(item => item.query !== query);
        localStorage.setItem('searchHistory', JSON.stringify(history));
    }
    
    function clearSearchHistory() {
        localStorage.removeItem('searchHistory');
    }
    
    function formatSearchDate(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        const minutes = Math.floor(diff / (1000 * 60));
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        
        if (minutes < 1) return 'Vừa xong';
        if (minutes < 60) return `${minutes} phút trước`;
        if (hours < 24) return `${hours} giờ trước`;
        if (days < 7) return `${days} ngày trước`;
        
        return date.toLocaleDateString('vi-VN');
    }
    
    function highlightQuery(text, query) {
        if (!query) return text;
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
    }
    
    function getPopularSuggestions(query) {
        // Popular search suggestions based on query
        const suggestions = {
            'iphone': ['iPhone 15 Pro', 'iPhone 14', 'iPhone 13', 'iPhone 12'],
            'samsung': ['Samsung Galaxy S24', 'Samsung Galaxy A55', 'Samsung Galaxy Tab'],
            'laptop': ['Laptop Gaming', 'Laptop Văn phòng', 'MacBook', 'Dell'],
            'tai nghe': ['Tai nghe Bluetooth', 'Tai nghe có dây', 'AirPods', 'Sony'],
            'điện thoại': ['iPhone', 'Samsung', 'Xiaomi', 'OPPO'],
            'máy tính': ['Laptop', 'PC Gaming', 'MacBook', 'Máy tính bảng']
        };
        
        for (const [key, values] of Object.entries(suggestions)) {
            if (query.toLowerCase().includes(key.toLowerCase())) {
                return values.filter(suggestion => 
                    suggestion.toLowerCase().includes(query.toLowerCase())
                );
            }
        }
        
        return [];
    }
    
    function performSearch(query) {
        if (query.trim()) {
            window.location.href = `{{ route('products.index') }}?search=${encodeURIComponent(query)}`;
        }
    }
</script>