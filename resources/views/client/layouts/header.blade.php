<!-- Header (all category items use the same icon) -->
<header class="bg-white shadow-sm border-b">
    <style>
        /* Cart Sidebar */
        #cart-sidebar {
            height: 100vh;
            max-height: 100vh;
            display: flex;
            flex-direction: column
        }

        #cart-items-container {
            flex: 1;
            overflow-y: auto
        }

        /* Dropdown shadows */
        #categoryDropdown,
        #accountDropdown {
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
            backdrop-filter: blur(10px)
        }

        /* Stable hover open: either :hover OR .open will show panel */
        .dropdown-group {
            position: relative
        }

        .dropdown-panel {
            display: none;
            pointer-events: auto
        }

        .dropdown-group:hover .dropdown-panel,
        .dropdown-group.open .dropdown-panel {
            display: block
        }

        /* Cart item animation */
        .cart-item-enter {
            animation: slideInRight .3s ease-out
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100px);
                opacity: 0
            }

            to {
                transform: translateX(0);
                opacity: 1
            }
        }

        .category-item:hover {
            transform: translateY(-2px);
            transition: all .2s ease
        }

        /* Shared category icon look */
        .cat-icon {
            width: 2rem;
            height: 2rem;
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #FFF7ED
        }

        /* orange-50 */
        .cat-icon i {
            color: #F97316
        }

        /* orange-500 */

        /* Responsive */
        @media (max-width:768px) {
            #cart-sidebar {
                width: 100vw
            }

            #categoryDropdown {
                width: 95vw;
                left: 2.5vw !important;
                max-width: none
            }

            #accountDropdown {
                width: 280px;
                right: 1rem !important;
                left: auto !important
            }

            .container {
                padding-left: .5rem;
                padding-right: .5rem
            }

            input[type="text"] {
                font-size: 16px
            }
        }

        @media (max-width:640px) {
            #categoryDropdown .grid-cols-2 {
                grid-template-columns: 1fr
            }

            .container {
                padding-left: .25rem;
                padding-right: .25rem
            }
        }
    </style>

    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between flex-nowrap gap-4">
            <!-- Logo -->
            <div class="flex items-center flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('admin_css/images/logo_techvicom.png') }}" alt="Techvicom"
                        class="w-10 h-10 rounded-lg mr-3 object-cover">
                    <span class="text-xl font-bold text-gray-800">Techvicom</span>
                </a>
            </div>

            <!-- Category (hover to open, stable) -->
            <div class="ml-2 lg:ml-6 dropdown-group">
                <button
                    class="flex items-center space-x-2 px-3 lg:px-4 py-2 border border-orange-300 rounded-lg hover:bg-orange-50 transition"
                    data-dropdown="category">
                    <i class="fas fa-bars text-gray-600"></i>
                    <span class="hidden sm:inline text-gray-700 font-medium">Danh mục</span>
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
                                    <a href="{{ route('categories.show', $category->slug) }}"
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
                            @else
                                <!-- Fallback items, same icon for all -->
                                <a href="{{ route('products.index') }}?category=phone"
                                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <div class="cat-icon mr-3 group-hover:scale-110 transition-transform"><i
                                            class="fas fa-tags text-sm"></i></div>
                                    <div><span class="text-gray-700 font-medium">Điện thoại</span>
                                        <p class="text-xs text-gray-500">Smartphone</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=laptop"
                                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <div class="cat-icon mr-3 group-hover:scale-110 transition-transform"><i
                                            class="fas fa-tags text-sm"></i></div>
                                    <div><span class="text-gray-700 font-medium">Laptop</span>
                                        <p class="text-xs text-gray-500">Máy tính xách tay</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=tablet"
                                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <div class="cat-icon mr-3 group-hover:scale-110 transition-transform"><i
                                            class="fas fa-tags text-sm"></i></div>
                                    <div><span class="text-gray-700 font-medium">Tablet</span>
                                        <p class="text-xs text-gray-500">Máy tính bảng</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=watch"
                                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <div class="cat-icon mr-3 group-hover:scale-110 transition-transform"><i
                                            class="fas fa-tags text-sm"></i></div>
                                    <div><span class="text-gray-700 font-medium">Đồng hồ</span>
                                        <p class="text-xs text-gray-500">Smart Watch</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=accessory"
                                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <div class="cat-icon mr-3 group-hover:scale-110 transition-transform"><i
                                            class="fas fa-tags text-sm"></i></div>
                                    <div><span class="text-gray-700 font-medium">Phụ kiện</span>
                                        <p class="text-xs text-gray-500">Tai nghe, sạc, bao da...</p>
                                    </div>
                                </a>
                                <a href="{{ route('products.index') }}?category=gaming"
                                    class="category-item flex items-center p-3 hover:bg-orange-50 rounded-lg transition group">
                                    <div class="cat-icon mr-3 group-hover:scale-110 transition-transform"><i
                                            class="fas fa-tags text-sm"></i></div>
                                    <div><span class="text-gray-700 font-medium">Gaming</span>
                                        <p class="text-xs text-gray-500">Thiết bị chơi game</p>
                                    </div>
                                </a>
                            @endif
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

            <!-- Right section -->
            <div class="flex flex-nowrap items-center space-x-2 lg:space-x-3 justify-end">
                <!-- Account (hover to open, stable) -->
                <div class="dropdown-group">
                    <button
                        class="flex items-center space-x-1 lg:space-x-2 px-3 lg:px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition"
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
                                    <a href="{{ route('login') }}"
                                        class="block w-full text-center bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">Đăng
                                        nhập</a>
                                    <a href="{{ route('register') }}"
                                        class="block w-full text-center border border-orange-500 text-orange-500 py-2 rounded-lg hover:bg-orange-50 transition">Đăng
                                        ký</a>
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
                                            <p class="text-sm text-gray-500">Khách hàng thân thiết</p>
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
                        class="flex items-center px-3 lg:px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition relative">
                        <i class="fas fa-shopping-basket text-lg"></i>
                        <span
                            class="absolute -top-2 -right-2 bg-[#ff6c2f] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
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

<script>
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
        if (closeCartBtn) closeCartBtn.addEventListener('click', closeCartSidebar);
        if (cartOverlay) cartOverlay.addEventListener('click', closeCartSidebar);

        document.addEventListener('cart:updated', function() {
            loadCartItems();
        });

        const headerSearchInput = document.getElementById('header-search-input');
        const headerSearchBtn = document.getElementById('header-search-btn');

        function performHeaderSearch() {
            const s = headerSearchInput.value.trim();
            if (s) window.location.href = `{{ route('products.index') }}?search=${encodeURIComponent(s)}`;
        }
        if (headerSearchBtn) headerSearchBtn.addEventListener('click', performHeaderSearch);
        if (headerSearchInput) headerSearchInput.addEventListener('keypress', e => {
            if (e.key === 'Enter') performHeaderSearch();
        });

        loadCartItems();
        const checkoutNowBtn = document.getElementById('sidebar-checkout-now');
        if (checkoutNowBtn) checkoutNowBtn.addEventListener('click', handleSidebarCheckout);
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
            return `<div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg cart-item-enter" data-id="${item.id}">
      <input type="checkbox" class="sidebar-item-checkbox w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]" value="${item.id}">
      <img src="${item.image||'/images/default-product.jpg'}" alt="${item.name}" class="w-14 h-14 object-cover rounded-lg">
      <div class="flex-1">
        <h4 class="font-medium text-gray-900 text-sm">${item.name}</h4>
        ${variantHtml}
        <p class="text-orange-500 font-semibold">${formatPrice(price)}</p>
        <div class="flex items-center space-x-2 mt-1">
          <button onclick="changeSidebarQuantity('${item.id}',-1)" class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100">-</button>
          <span class="text-sm sidebar-qty" data-id="${item.id}">${item.quantity}</span>
          <button onclick="changeSidebarQuantity('${item.id}',1)" class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100">+</button>
        </div>
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
            boxes.forEach(c => c.checked = selectAll.checked);
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
            const ids = Array.from(boxes).filter(c => c.checked).map(c => c.value);
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
        const selected = Array.from(document.querySelectorAll('.sidebar-item-checkbox:checked')).map(c => c.value);
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
</script>
