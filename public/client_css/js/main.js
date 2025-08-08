// Techvicom - Main JavaScript (Static Data Version)

// Global variables
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let isInitialized = false; // Prevent multiple initialization

// Navigation and UI functions
function setupMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
}

function setupDropdowns() {
    // Prevent duplicate dropdown setup if already exists
    if (document.querySelector('.category-dropdown-menu')) {
        return;
    }

    const categoryBtn = document.getElementById('category-btn');
    const categoryDropdown = document.getElementById('category-dropdown');
    const accountBtn = document.getElementById('account-btn');
    const accountDropdown = document.getElementById('account-dropdown');

    if (categoryBtn && categoryDropdown) {
        categoryBtn.addEventListener('click', (e) => {
            e.preventDefault();
            categoryDropdown.classList.toggle('hidden');
            if (accountDropdown) accountDropdown.classList.add('hidden');
        });
    }

    if (accountBtn && accountDropdown) {
        accountBtn.addEventListener('click', (e) => {
            e.preventDefault();
            accountDropdown.classList.toggle('hidden');
            if (categoryDropdown) categoryDropdown.classList.add('hidden');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (categoryDropdown && !categoryBtn?.contains(e.target) && !categoryDropdown.contains(e.target)) {
            categoryDropdown.classList.add('hidden');
        }
        if (accountDropdown && !accountBtn?.contains(e.target) && !accountDropdown.contains(e.target)) {
            accountDropdown.classList.add('hidden');
        }
    });
}

function setupCartSidebar() {
    const cartBtn = document.getElementById('cart-btn');
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartOverlay = document.getElementById('cart-overlay');
    const closeCartBtn = document.getElementById('close-cart');

    if (cartBtn && cartSidebar) {
        cartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            cartSidebar.classList.remove('translate-x-full');
            if (cartOverlay) cartOverlay.classList.remove('hidden');
            loadCartSidebar();
        });
    }

    if (closeCartBtn) {
        closeCartBtn.addEventListener('click', () => {
            cartSidebar?.classList.add('translate-x-full');
            cartOverlay?.classList.add('hidden');
        });
    }

    if (cartOverlay) {
        cartOverlay.addEventListener('click', () => {
            cartSidebar?.classList.add('translate-x-full');
            cartOverlay.classList.add('hidden');
        });
    }
}

function loadCartSidebar() {
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const cartCount = document.querySelector('.cart-count');

    if (!cartItems) return;

    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-gray-500 text-center py-8">Giỏ hàng trống</p>';
        if (cartTotal) cartTotal.textContent = '0₫';
        if (cartCount) {
            cartCount.textContent = '0';
            cartCount.style.display = 'none';
        }
        return;
    }

    let total = 0;
    let totalItems = 0;

    cartItems.innerHTML = cart.map(item => {
        total += item.price * item.quantity;
        totalItems += item.quantity;
        return `
            <div class="flex items-center space-x-3 p-3 border-b border-gray-200">
                <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded">
                <div class="flex-1">
                    <h4 class="font-medium text-sm">${item.name}</h4>
                    <p class="text-red-600 font-bold text-sm">${item.price.toLocaleString()}₫</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})" class="text-gray-400 hover:text-red-600">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="text-sm">${item.quantity}</span>
                    <button onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})" class="text-gray-400 hover:text-red-600">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <button onclick="removeFromCartSidebar(${item.id})" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        `;
    }).join('');

    if (cartTotal) cartTotal.textContent = total.toLocaleString() + '₫';
    if (cartCount) {
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'block' : 'none';
    }
}

function updateCartQuantity(productId, newQuantity) {
    if (newQuantity <= 0) {
        removeFromCartSidebar(productId);
        return;
    }

    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCartSidebar();
    }
}

function removeFromCartSidebar(productId) {
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCartSidebar();
}

// Search functionality (simplified for static data)
function setupSearch() {
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');
    
    if (searchInput && searchSuggestions) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length > 0) {
                // Show basic suggestions for static version
                const staticSuggestions = [
                    'iPhone 15 Pro Max',
                    'Samsung Galaxy S24 Ultra',
                    'MacBook Pro M3',
                    'iPad Pro M2',
                    'AirPods Pro 2',
                    'Apple Watch Series 9'
                ].filter(item => item.toLowerCase().includes(query.toLowerCase()));
                
                if (staticSuggestions.length > 0) {
                    searchSuggestions.innerHTML = staticSuggestions.map(suggestion => 
                        `<div class="p-2 hover:bg-gray-100 cursor-pointer">${suggestion}</div>`
                    ).join('');
                    searchSuggestions.classList.remove('hidden');
                } else {
                    searchSuggestions.classList.add('hidden');
                }
            } else {
                searchSuggestions.classList.add('hidden');
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.classList.add('hidden');
            }
        });
    }
}

// User authentication status
function updateUserStatus() {
    const user = JSON.parse(localStorage.getItem('user'));
    const loginBtn = document.getElementById('login-btn');
    const userInfo = document.getElementById('user-info');
    const userName = document.getElementById('user-name');

    if (user && loginBtn && userInfo) {
        loginBtn.style.display = 'none';
        userInfo.style.display = 'block';
        if (userName) userName.textContent = user.name || user.email;
    }
}

function logout() {
    localStorage.removeItem('user');
    window.location.href = 'pages/login.html';
}

// Notification system
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
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

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (isInitialized) return;
    isInitialized = true;

    setupMobileMenu();
    setupDropdowns();
    setupCartSidebar();
    setupSearch();
    updateUserStatus();
    loadCartSidebar(); // Load initial cart state

    // Update cart count on page load
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'block' : 'none';
    }

    // Add click listeners to product cards
    const productCards = document.querySelectorAll('[data-product-id]');
    console.log('Found product cards:', productCards.length);
    
    if (productCards.length === 0) {
        console.warn('No product cards found with data-product-id attribute');
    }
    
    productCards.forEach((card, index) => {
        console.log('Setting up click listener for card', index, 'with product ID:', card.getAttribute('data-product-id'));
        card.addEventListener('click', function(e) {
            // Don't navigate if clicking on buttons
            if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                console.log('Button clicked, not navigating');
                return;
            }
            
            const productId = this.getAttribute('data-product-id');
            console.log('Product card clicked:', productId);
            goToProductDetail(productId);
        });
    });
});

// Global functions
window.updateCartQuantity = updateCartQuantity;
window.removeFromCartSidebar = removeFromCartSidebar;
window.logout = logout;
window.showNotification = showNotification;

// Product and cart functions for static data
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
        
        // Reload cart sidebar if it exists
        loadCartSidebar();
        
    } catch (error) {
        console.error('Error adding product to cart:', error);
        showNotification('Có lỗi xảy ra khi thêm sản phẩm!', 'error');
    }
}

// Navigation functions
function goToFeaturedProduct() {
    window.location.href = 'pages/product-detail.html?id=iphone-15-pro';
}

function goToProductDetail(productId) {
    console.log('Navigating to product detail:', productId);
    // Check if we're already in pages directory
    const currentPath = window.location.pathname;
    if (currentPath.includes('/pages/')) {
        // We're in pages directory, use relative path
        window.location.href = `product-detail.html?id=${productId}`;
    } else {
        // We're in root directory, use pages/ path
        window.location.href = `pages/product-detail.html?id=${productId}`;
    }
}

function navigateToList() {
    console.log('Navigating to products page');
    // Navigate to Laravel products route
    window.location.href = '/products';
}

function logoutUser() {
    localStorage.removeItem('user');
    showNotification('Đăng xuất thành công!', 'success');
    setTimeout(() => {
        window.location.href = 'pages/login.html';
    }, 1000);
}

// Make new functions globally available
window.addToCartStatic = addToCartStatic;
window.goToFeaturedProduct = goToFeaturedProduct;
window.goToProductDetail = goToProductDetail;
window.navigateToList = navigateToList;
window.logoutUser = logoutUser;
