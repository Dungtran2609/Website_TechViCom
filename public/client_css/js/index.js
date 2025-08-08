// index.js - JavaScript cho trang chủ
// Debug: Track script execution
console.log('Index script loading started at:', new Date().toISOString());

// Prevent infinite loops
let executionCount = 0;
const MAX_EXECUTIONS = 10;

function checkExecutionLimit() {
    executionCount++;
    console.log('Script execution count:', executionCount);
    if (executionCount > MAX_EXECUTIONS) {
        console.error('Too many script executions detected! Possible infinite loop.');
        return false;
    }
    return true;
}

// Prevent multiple initialization
let isPageInitialized = false;

function setupAccountDropdownInline() {
    if (!window.PRODUCT_DATA) {
        console.log('PRODUCT_DATA not loaded yet');
        return;
    }
    
    const accountButton = document.getElementById('accountMenuBtn');
    if (!accountButton) return;
    
    // Check if dropdown already exists
    const existingDropdown = accountButton.parentElement.querySelector('.account-dropdown');
    if (existingDropdown) {
        console.log('Account dropdown already exists');
        return;
    }

    // Create dropdown element
    const dropdown = document.createElement('div');
    dropdown.className = 'absolute top-full right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden account-dropdown';
    dropdown.innerHTML = `
        <div class="p-6">
            <!-- User Status Check -->
            <div id="account-status">
                <!-- Not logged in state -->
                <div id="not-logged-in" class="text-center">
                    <div class="mb-4">
                        <i class="fas fa-user-circle text-6xl text-gray-300 mb-2"></i>
                        <p class="text-gray-600">Chưa đăng nhập</p>
                    </div>
                    
                    <div class="space-y-3">
                        <button onclick="window.location.href='pages/login.html'" class="w-full bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
                        </button>
                        
                        <button onclick="window.location.href='pages/register.html'" class="w-full border border-orange-500 text-orange-500 px-6 py-3 rounded-lg font-semibold hover:bg-orange-50 transition">
                            <i class="fas fa-user-plus mr-2"></i>Đăng ký tài khoản
                        </button>
                    </div>
                </div>
                
                <!-- Logged in state -->
                <div id="logged-in" class="hidden">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900" id="user-name">Nguyễn Văn A</h3>
                            <p class="text-sm text-gray-500" id="user-email">user@example.com</p>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="border-t pt-4 mt-4">
                        <div class="space-y-2">
                            <a href="pages/account.html" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                <i class="fas fa-user text-gray-400"></i>
                                <span>Thông tin tài khoản</span>
                            </a>
                            <a href="pages/orders.html" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                <i class="fas fa-clipboard-list text-gray-400"></i>
                                <span>Đơn hàng của tôi</span>
                            </a>
                            <a href="pages/wishlist.html" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                <i class="fas fa-heart text-gray-400"></i>
                                <span>Danh sách yêu thích</span>
                            </a>
                            <a href="pages/contact.html" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                <i class="fas fa-headset text-gray-400"></i>
                                <span>Hỗ trợ khách hàng</span>
                            </a>
                            <hr class="my-2">
                            <button onclick="logoutUser()" class="flex items-center space-x-3 p-3 hover:bg-orange-50 rounded-lg transition text-[#ff6c2f] w-full text-left">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Đăng xuất</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Insert dropdown after button
    accountButton.parentElement.appendChild(dropdown);

    // Toggle functionality
    accountButton.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        dropdown.classList.toggle('hidden');
        
        // Close cart sidebar if open
        const cartSidebar = document.getElementById('cart-sidebar');
        if (cartSidebar && !cartSidebar.classList.contains('translate-x-full')) {
            cartSidebar.classList.add('translate-x-full');
            document.getElementById('cart-overlay').classList.add('hidden');
        }
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!accountButton.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Update account status
    updateAccountStatusInline();
}

function updateAccountStatusInline() {
    const user = JSON.parse(localStorage.getItem('user'));
    const loggedInDiv = document.getElementById('logged-in');
    const notLoggedInDiv = document.getElementById('not-logged-in');
    
    if (user && loggedInDiv && notLoggedInDiv) {
        loggedInDiv.classList.remove('hidden');
        notLoggedInDiv.classList.add('hidden');
        
        const userNameEl = document.getElementById('user-name');
        const userEmailEl = document.getElementById('user-email');
        
        if (userNameEl) userNameEl.textContent = user.name || user.fullName || 'Người dùng';
        if (userEmailEl) userEmailEl.textContent = user.email || '';
    }
}

function setupCartSidebarInline() {
    const cartButton = document.getElementById('cartBtn');
    if (!cartButton) return;

    // Check if sidebar already exists
    const existingSidebar = document.getElementById('cart-sidebar');
    if (existingSidebar) {
        console.log('Cart sidebar already exists');
        return;
    }

    // Create cart sidebar
    const sidebar = document.createElement('div');
    sidebar.id = 'cart-sidebar';
    sidebar.className = 'fixed top-0 right-0 h-full w-96 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 border-l border-gray-200';
    
    sidebar.innerHTML = `
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Giỏ hàng</h2>
                <button id="close-cart" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-times text-gray-500"></i>
                </button>
            </div>
            
            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4" id="cart-items">
                <!-- Cart items will be loaded here -->
            </div>
            
            <!-- Footer -->
            <div class="border-t border-gray-200 p-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-semibold">Tổng cộng:</span>
                    <span class="text-lg font-bold text-[#ff6c2f]" id="cart-total">0₫</span>
                </div>
                <div class="space-y-2">
                    <button onclick="window.location.href='pages/cart.html'" class="w-full bg-[#ff6c2f] text-white py-3 rounded-lg font-semibold hover:bg-[#e55a28] transition">
                        Xem giỏ hàng
                    </button>
                    <button onclick="window.location.href='pages/cart.html'" class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                        Thanh toán ngay
                    </button>
                </div>
            </div>
        </div>
    `;

    // Create overlay
    const overlay = document.createElement('div');
    overlay.id = 'cart-overlay';
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-40 hidden';

    // Append to body
    document.body.appendChild(overlay);
    document.body.appendChild(sidebar);

    // Event listeners
    cartButton.addEventListener('click', function(e) {
        e.preventDefault();
        sidebar.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        loadCartItemsInline();
    });

    document.getElementById('close-cart').addEventListener('click', function() {
        sidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
    });

    overlay.addEventListener('click', function() {
        sidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
    });
}

function loadCartItemsInline() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    
    if (!cartItemsContainer) return;

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">Giỏ hàng trống</p>
            </div>
        `;
        if (cartTotal) cartTotal.textContent = '0₫';
        return;
    }

    let total = 0;
    cartItemsContainer.innerHTML = cart.map(item => {
        total += item.price * item.quantity;
        return `
            <div class="flex items-center space-x-3 mb-4 p-3 border border-gray-200 rounded-lg">
                <img src="${item.image}" alt="${item.name}" 
                     class="w-16 h-16 object-cover rounded"
                     onerror="this.onerror=null; this.src='assets/images/placeholder.svg'">
                <div class="flex-1">
                    <h4 class="font-medium text-sm">${item.name}</h4>
                    <p class="text-[#ff6c2f] font-bold">${item.price.toLocaleString()}₫</p>
                    <div class="flex items-center mt-2">
                        <button onclick="updateCartQuantityInline(${item.id}, ${item.quantity - 1})" 
                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-l hover:bg-gray-100">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <span class="w-12 h-8 flex items-center justify-center border-t border-b border-gray-300 text-sm">
                            ${item.quantity}
                        </span>
                        <button onclick="updateCartQuantityInline(${item.id}, ${item.quantity + 1})" 
                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-r hover:bg-gray-100">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>
                <button onclick="removeFromCartInline(${item.id})" 
                        class="w-8 h-8 flex items-center justify-center text-[#ff6c2f] hover:bg-orange-50 rounded">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        `;
    }).join('');

    if (cartTotal) cartTotal.textContent = total.toLocaleString() + '₫';
    updateCartCountInline();
}

function updateCartQuantityInline(productId, newQuantity) {
    if (newQuantity <= 0) {
        removeFromCartInline(productId);
        return;
    }

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCartItemsInline();
    }
}

function removeFromCartInline(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCartItemsInline();
}

function updateCartCountInline() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartCount = document.querySelector('.cart-count');
    
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'block' : 'none';
    }
}

function proceedToCheckout() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert('Giỏ hàng của bạn đang trống!');
        return;
    }
    window.location.href = 'pages/checkout.html';
}

function logoutUser() {
    localStorage.removeItem('user');
    window.location.reload();
}

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
        updateCartCountInline();
        
        // Show notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300';
        notification.textContent = 'Đã thêm sản phẩm vào giỏ hàng!';
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
        
        console.log('Added to cart:', product);
    } catch (error) {
        console.error('Error adding to cart:', error);
    }
}

// Function for hero section "MUA NGAY" button
function goToFeaturedProduct() {
    // Direct to iPhone 15 Pro product detail page
    window.location.href = 'pages/product-detail.html?id=iphone-15-pro';
}

// Slideshow functionality
let currentSlideIndex = 0;
const slides = document.querySelectorAll('.slide');
const indicators = document.querySelectorAll('.indicator');
const totalSlides = slides.length;

function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => {
        slide.classList.remove('active');
    });
    
    // Remove active from all indicators
    indicators.forEach(indicator => {
        indicator.classList.remove('active');
    });
    
    // Show current slide
    if (slides[index]) {
        slides[index].classList.add('active');
    }
    
    // Update indicator
    if (indicators[index]) {
        indicators[index].classList.add('active');
    }
}

function nextSlide() {
    currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
    showSlide(currentSlideIndex);
}

function prevSlide() {
    currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
    showSlide(currentSlideIndex);
}

function changeSlide(direction) {
    if (direction === 1) {
        nextSlide();
    } else {
        prevSlide();
    }
}

function currentSlide(index) {
    currentSlideIndex = index - 1;
    showSlide(currentSlideIndex);
}

// Auto slide every 5 seconds
function autoSlide() {
    nextSlide();
}

// Initialize slideshow
function initSlideshow() {
    if (slides.length > 0) {
        showSlide(0);
        // Auto-advance slides every 5 seconds
        setInterval(autoSlide, 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (!checkExecutionLimit() || isPageInitialized) return;
    
    try {
        isPageInitialized = true;
        
        // Initialize cart count
        updateCartCountInline();
        
        // Setup UI components
        setupAccountDropdownInline();
        setupCartSidebarInline();
        
        // Initialize slideshow
        setTimeout(initSlideshow, 100);
        
        console.log('Index page initialized successfully');
    } catch (error) {
        console.error('Error initializing index page:', error);
    }
});

// Make functions globally available
window.changeSlide = changeSlide;
window.currentSlide = currentSlide;
window.addToCartStatic = addToCartStatic;
window.goToFeaturedProduct = goToFeaturedProduct;
window.updateCartQuantityInline = updateCartQuantityInline;
window.removeFromCartInline = removeFromCartInline;
window.proceedToCheckout = proceedToCheckout;
window.logoutUser = logoutUser;
