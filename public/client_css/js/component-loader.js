// Shared Components Loader
class ComponentLoader {
    constructor() {
        this.componentsLoaded = false;
        this.isRootPage = window.location.pathname.includes('index.html') || window.location.pathname === '/' || window.location.pathname.endsWith('/');
    }

    // Get correct path for components based on current page location
    getComponentPath(componentName) {
        const currentPath = window.location.pathname;
        const isInPagesFolder = currentPath.includes('/pages/');
        
        if (isInPagesFolder) {
            return `../components/${componentName}`;
        } else {
            return `components/${componentName}`;
        }
    }

    // Load header component
    async loadHeader() {
        try {
            const headerContainer = document.getElementById('shared-header-container');
            if (!headerContainer) {
                console.warn('Header container not found');
                return;
            }

            const headerPath = this.getComponentPath('shared-header.html');
            const response = await fetch(headerPath);
            
            if (!response.ok) {
                throw new Error(`Failed to load header: ${response.status}`);
            }

            const headerHTML = await response.text();
            headerContainer.innerHTML = headerHTML;
            
            // Initialize header functionality after loading
            this.initializeHeaderFunctionality();
            
            // Update category links path
            this.updateCategoryLinks();
            
            // Update account links path
            this.updateAccountLinks();
            
            // Update logo paths
            this.updateLogoPaths();
            
            console.log('Header loaded successfully');
        } catch (error) {
            console.error('Error loading header:', error);
            // Fallback to basic header
            this.loadFallbackHeader();
        }
    }

    // Load footer component
    async loadFooter() {
        try {
            const footerContainer = document.getElementById('shared-footer-container');
            if (!footerContainer) {
                console.warn('Footer container not found');
                return;
            }

            const footerPath = this.getComponentPath('shared-footer.html');
            const response = await fetch(footerPath);
            
            if (!response.ok) {
                throw new Error(`Failed to load footer: ${response.status}`);
            }

            const footerHTML = await response.text();
            footerContainer.innerHTML = footerHTML;
            
            // Update logo paths
            this.updateLogoPaths();
            
            console.log('Footer loaded successfully');
        } catch (error) {
            console.error('Error loading footer:', error);
            // Fallback to basic footer
            this.loadFallbackFooter();
        }
    }

    // Initialize header functionality (dropdowns, cart, etc.)
    initializeHeaderFunctionality() {
        // Account dropdown functionality
        const accountBtn = document.getElementById('accountMenuBtn');
        const accountDropdown = document.getElementById('accountDropdown');
        
        if (accountBtn && accountDropdown) {
            accountBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                accountDropdown.classList.toggle('hidden');
                // Close category dropdown if open
                const categoryDropdown = document.getElementById('categoryDropdown');
                if (categoryDropdown) {
                    categoryDropdown.classList.add('hidden');
                }
            });
        }

        // Category dropdown functionality with improved positioning
        const categoryBtn = document.getElementById('categoryMenuBtn');
        const categoryDropdown = document.getElementById('categoryDropdown');
        
        if (categoryBtn && categoryDropdown) {
            categoryBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                categoryDropdown.classList.toggle('hidden');
                
                // Position dropdown relative to button
                const btnRect = categoryBtn.getBoundingClientRect();
                categoryDropdown.style.left = btnRect.left + 'px';
                categoryDropdown.style.top = (btnRect.bottom + 8) + 'px';
                categoryDropdown.style.position = 'fixed';
                
                // Close account dropdown if open
                if (accountDropdown) {
                    accountDropdown.classList.add('hidden');
                }
            });
        }

        // Cart functionality with sidebar
        const cartBtn = document.getElementById('cartMenuBtn');
        if (cartBtn) {
            cartBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showCartSidebar();
            });
        }

        // Cart sidebar close functionality
        const closeSidebarBtn = document.getElementById('close-cart-sidebar');
        const cartOverlay = document.getElementById('cart-overlay');
        
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', () => {
                this.closeCartSidebar();
            });
        }
        
        if (cartOverlay) {
            cartOverlay.addEventListener('click', () => {
                this.closeCartSidebar();
            });
        }

        // Dark mode toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark');
                const icon = darkModeToggle.querySelector('i');
                if (document.body.classList.contains('dark')) {
                    icon.className = 'fas fa-sun';
                } else {
                    icon.className = 'fas fa-moon';
                }
            });
        }

        // Search functionality
        const searchInput = document.querySelector('input[placeholder*="tìm kiếm"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const searchTerm = e.target.value.trim();
                    if (searchTerm) {
                        this.performSearch(searchTerm);
                    }
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            if (accountDropdown) accountDropdown.classList.add('hidden');
            if (categoryDropdown) categoryDropdown.classList.add('hidden');
        });

        // ESC key to close sidebar and dropdowns
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeCartSidebar();
                if (accountDropdown) accountDropdown.classList.add('hidden');
                if (categoryDropdown) categoryDropdown.classList.add('hidden');
            }
        });

        // Update cart count and load cart items
        this.updateCartCount();
        this.loadCartItems();

        // Update auth state
        this.updateAuthState();
    }

    // Update category links based on current page location
    updateCategoryLinks() {
        const categoryLinks = document.querySelectorAll('#categoryDropdown a[href^="pages/"]');
        const isInPagesFolder = this.isInPagesFolder();
        
        categoryLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (isInPagesFolder) {
                // Remove 'pages/' prefix if we're already in pages folder
                link.setAttribute('href', href.replace('pages/', ''));
            }
        });
    }

    // Update account links based on current page location
    updateAccountLinks() {
        const accountLinks = document.querySelectorAll('#accountDropdown a[href^="pages/"]');
        const isInPagesFolder = this.isInPagesFolder();
        
        accountLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (isInPagesFolder) {
                // Remove 'pages/' prefix if we're already in pages folder
                link.setAttribute('href', href.replace('pages/', ''));
            }
        });
    }

    // Update logo image paths and links based on current page location
    updateLogoPaths() {
        const logoImages = document.querySelectorAll('img[src^="assets/images/logo"]');
        const logoLinks = document.querySelectorAll('a[href="index.html"]');
        const isInPagesFolder = this.isInPagesFolder();
        
        // Update image paths
        logoImages.forEach(img => {
            const src = img.getAttribute('src');
            if (isInPagesFolder && !src.startsWith('../')) {
                // Add ../ prefix if we're in pages folder
                img.setAttribute('src', '../' + src);
            } else if (!isInPagesFolder && src.startsWith('../')) {
                // Remove ../ prefix if we're in root folder
                img.setAttribute('src', src.replace('../', ''));
            }
        });
        
        // Update logo links
        logoLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (isInPagesFolder && href === 'index.html') {
                // Add ../ prefix for pages folder
                link.setAttribute('href', '../index.html');
            } else if (!isInPagesFolder && href === '../index.html') {
                // Remove ../ prefix for root folder
                link.setAttribute('href', 'index.html');
            }
        });
    }

    // Check if current page is in pages folder
    isInPagesFolder() {
        return window.location.pathname.includes('/pages/');
    }

    // Perform search
    performSearch(searchTerm) {
        const searchPath = this.isInPagesFolder() ? 'search.html' : 'pages/search.html';
        window.location.href = `${searchPath}?q=${encodeURIComponent(searchTerm)}`;
    }

    // Update cart count from localStorage
    updateCartCount() {
        const cartCount = document.getElementById('cart-count');
        if (cartCount) {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
            cartCount.textContent = totalItems;
        }
    }

    // Update authentication state
    updateAuthState() {
        const loggedInMenu = document.getElementById('loggedInMenu');
        const loggedOutMenu = document.getElementById('loggedOutMenu');
        const userName = document.getElementById('userName');
        
        const user = JSON.parse(localStorage.getItem('user'));
        
        if (user && loggedInMenu && loggedOutMenu) {
            loggedOutMenu.classList.add('hidden');
            loggedInMenu.classList.remove('hidden');
            
            if (userName) {
                userName.textContent = user.name || user.email || 'Người dùng';
            }
        }
    }

    // Show cart sidebar
    showCartSidebar() {
        const cartSidebar = document.getElementById('cart-sidebar');
        const cartOverlay = document.getElementById('cart-overlay');
        
        if (cartSidebar && cartOverlay) {
            cartSidebar.classList.remove('translate-x-full');
            cartOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Load cart items
            this.loadCartItems();
        }
    }

    // Close cart sidebar
    closeCartSidebar() {
        const cartSidebar = document.getElementById('cart-sidebar');
        const cartOverlay = document.getElementById('cart-overlay');
        
        if (cartSidebar && cartOverlay) {
            cartSidebar.classList.add('translate-x-full');
            cartOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // Load cart items into sidebar
    loadCartItems() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const emptyCart = document.getElementById('empty-cart');
        const cartItemsList = document.getElementById('cart-items-list');
        const cartFooter = document.getElementById('cart-footer');
        const cartSubtotal = document.getElementById('cart-subtotal');
        
        if (cart.length === 0) {
            if (emptyCart) emptyCart.classList.remove('hidden');
            if (cartItemsList) cartItemsList.classList.add('hidden');
            if (cartFooter) cartFooter.classList.add('hidden');
        } else {
            if (emptyCart) emptyCart.classList.add('hidden');
            if (cartItemsList) cartItemsList.classList.remove('hidden');
            if (cartFooter) cartFooter.classList.remove('hidden');
            
            // Render cart items
            if (cartItemsList) {
                cartItemsList.innerHTML = cart.map((item, index) => `
                    <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                        <img src="${item.image || '../assets/images/placeholder.svg'}" 
                             alt="${item.name}" 
                             class="w-16 h-16 object-cover rounded-lg"
                             onerror="this.src='../assets/images/placeholder.svg'">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 text-sm line-clamp-2">${item.name}</h4>
                            <p class="text-xs text-gray-500">
                                ${item.color ? `Màu: ${item.color}` : ''} 
                                ${item.storage ? `| ${item.storage}` : ''}
                            </p>
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateCartQuantity(${index}, ${item.quantity - 1})" 
                                            class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded-full text-xs hover:bg-gray-200">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="text-sm font-medium">${item.quantity}</span>
                                    <button onclick="updateCartQuantity(${index}, ${item.quantity + 1})" 
                                            class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded-full text-xs hover:bg-gray-200">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <button onclick="removeFromCart(${index})" 
                                        class="text-red-500 hover:text-red-600 text-xs">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-orange-600 text-sm">
                                ${this.formatCurrency(item.price * item.quantity)}
                            </p>
                        </div>
                    </div>
                `).join('');
            }
            
            // Calculate and display subtotal
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            if (cartSubtotal) {
                cartSubtotal.textContent = this.formatCurrency(subtotal);
            }
        }
    }

    // Format currency
    formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    // Fallback header (basic version)
    loadFallbackHeader() {
        const headerContainer = document.getElementById('shared-header-container');
        if (headerContainer) {
            headerContainer.innerHTML = `
                <header class="bg-white shadow-sm border-b">
                    <div class="container mx-auto px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-coffee text-white text-lg"></i>
                                </div>
                                <span class="text-xl font-bold text-gray-800">Techvicom</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="${this.isInPagesFolder() ? '../index.html' : 'index.html'}" class="text-gray-700 hover:text-orange-500">Trang chủ</a>
                                <a href="${this.isInPagesFolder() ? 'phones.html' : 'pages/phones.html'}" class="text-gray-700 hover:text-orange-500">Điện thoại</a>
                                <a href="${this.isInPagesFolder() ? 'cart.html' : 'pages/cart.html'}" class="text-gray-700 hover:text-orange-500">Giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                </header>
            `;
        }
    }

    // Fallback footer (basic version)
    loadFallbackFooter() {
        const footerContainer = document.getElementById('shared-footer-container');
        if (footerContainer) {
            footerContainer.innerHTML = `
                <footer class="bg-gray-900 text-white py-8">
                    <div class="container mx-auto px-4 text-center">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-coffee text-white text-lg"></i>
                            </div>
                            <span class="text-xl font-bold">Techvicom</span>
                        </div>
                        <p class="text-gray-400">&copy; 2025 Techvicom. All rights reserved.</p>
                    </div>
                </footer>
            `;
        }
    }

    // Load all components
    async loadAllComponents() {
        if (this.componentsLoaded) return;
        
        await Promise.all([
            this.loadHeader(),
            this.loadFooter()
        ]);
        
        this.componentsLoaded = true;
    }
}

// Global logout function
function logout() {
    localStorage.removeItem('user');
    localStorage.removeItem('authToken');
    
    // Redirect to login page
    const currentPath = window.location.pathname;
    const isInPagesFolder = currentPath.includes('/pages/');
    const loginPath = isInPagesFolder ? 'login.html' : 'pages/login.html';
    
    window.location.href = loginPath;
}

// Global cart functions
function updateCartQuantity(index, newQuantity) {
    if (newQuantity <= 0) {
        removeFromCart(index);
        return;
    }
    
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart[index]) {
        cart[index].quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Reload cart sidebar and update count
        if (window.componentLoader) {
            window.componentLoader.loadCartItems();
            window.componentLoader.updateCartCount();
        }
    }
}

function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Reload cart sidebar and update count
    if (window.componentLoader) {
        window.componentLoader.loadCartItems();
        window.componentLoader.updateCartCount();
    }
}

function closeCartSidebar() {
    if (window.componentLoader) {
        window.componentLoader.closeCartSidebar();
    }
}

function goToCart() {
    const currentPath = window.location.pathname;
    const isInPagesFolder = currentPath.includes('/pages/');
    const cartPath = isInPagesFolder ? 'cart.html' : 'pages/cart.html';
    
    window.location.href = cartPath;
}

function goToCheckout() {
    const currentPath = window.location.pathname;
    const isInPagesFolder = currentPath.includes('/pages/');
    const checkoutPath = isInPagesFolder ? 'checkout.html' : 'pages/checkout.html';
    
    window.location.href = checkoutPath;
}

// Initialize component loader when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const componentLoader = new ComponentLoader();
    componentLoader.loadAllComponents();
    
    // Make component loader globally available
    window.componentLoader = componentLoader;
});

// Export for module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ComponentLoader;
}
