// Optimized Header JavaScript
(function() {
    'use strict';
    
    // Cache DOM elements
    let cartBtn, cartSidebar, cartOverlay, closeCartBtn;
    let headerSearchInput, headerSearchBtn;
    let checkoutNowBtn;
    
    // Initialize when DOM is ready
    function init() {
        cacheElements();
        bindEvents();
        loadCartItems();
    }
    
    function cacheElements() {
        cartBtn = document.getElementById('cartMenuBtn');
        cartSidebar = document.getElementById('cart-sidebar');
        cartOverlay = document.getElementById('cart-overlay');
        closeCartBtn = document.getElementById('close-cart-sidebar');
        headerSearchInput = document.getElementById('header-search-input');
        headerSearchBtn = document.getElementById('header-search-btn');
        checkoutNowBtn = document.getElementById('sidebar-checkout-now');
    }
    
    function bindEvents() {
        // Cart events
        if (cartBtn) {
            cartBtn.addEventListener('click', openCartSidebar);
        }
        if (closeCartBtn) {
            closeCartBtn.addEventListener('click', closeCartSidebar);
        }
        if (cartOverlay) {
            cartOverlay.addEventListener('click', closeCartSidebar);
        }
        
        // Search events
        if (headerSearchBtn) {
            headerSearchBtn.addEventListener('click', performHeaderSearch);
        }
        if (headerSearchInput) {
            headerSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') performHeaderSearch();
            });
        }
        
        // Checkout event
        if (checkoutNowBtn) {
            checkoutNowBtn.addEventListener('click', handleSidebarCheckout);
        }
        
        // Cart update event
        document.addEventListener('cart:updated', loadCartItems);
    }
    
    function openCartSidebar() {
        cartSidebar.classList.remove('translate-x-full');
        cartOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeCartSidebar() {
        cartSidebar.classList.add('translate-x-full');
        cartOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    function performHeaderSearch() {
        const searchTerm = headerSearchInput.value.trim();
        if (searchTerm) {
            window.location.href = `/products?search=${encodeURIComponent(searchTerm)}`;
        }
    }
    
    function handleSidebarCheckout() {
        const selected = Array.from(document.querySelectorAll('.sidebar-item-checkbox:checked'))
            .filter(c => !c.disabled)
            .map(c => c.value);
        if (selected.length > 0) {
            localStorage.setItem('checkout_selected_items', JSON.stringify(selected));
            window.location.href = `/checkout?selected=${selected.join(',')}`;
        } else {
            alert('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán');
        }
    }
    
    // Cart functions
    function loadCartItems() {
        fetch('/carts/count')
            .then(r => r.json())
            .then(d => {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) cartCount.textContent = d.count || 0;
                return fetch('/carts', {
                    headers: { 'Accept': 'application/json' }
                });
            })
            .then(r => r.ok ? r.json() : Promise.reject('HTTP ' + r.status))
            .then(d => {
                if (d.success && d.items) {
                    updateCartDisplay(d.items);
                } else {
                    updateCartDisplay([]);
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
                variantHtml = `<div class="text-xs text-gray-500">${item.attributes.map(a=>`${a.name}: ${a.value}`).join(', ')}</div>`;
            }
            const isOutOfStock = (item.stock !== undefined && item.stock <= 0);
            
            return `<div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg cart-item-enter" data-id="${item.id}">
                <input type="checkbox" class="sidebar-item-checkbox w-4 h-4 text-[#ff6c2f] border-gray-300 rounded focus:ring-[#ff6c2f]" value="${item.id}" ${isOutOfStock ? 'disabled' : ''}>
                <img src="${item.image||'/images/default-product.jpg'}" alt="${item.name}" class="w-14 h-14 object-cover rounded-lg">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 text-sm">
                        <a href="/products/${item.product_id}" class="hover:text-[#ff6c2f] transition-colors">${item.name}</a>
                    </h4>
                    ${variantHtml}
                    <p class="text-orange-500 font-semibold">${formatPrice(price)}</p>
                    ${isOutOfStock ? `<div class="text-red-600 font-bold text-sm">Hết hàng</div>` : 
                    `<div class="flex items-center space-x-2 mt-1">
                        <button onclick="changeSidebarQuantity('${item.id}',-1)" class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100">-</button>
                        <span class="text-sm sidebar-qty" data-id="${item.id}">${item.quantity}</span>
                        <button onclick="changeSidebarQuantity('${item.id}',1)" class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-sm hover:bg-gray-100">+</button>
                    </div>`}
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
    
    function formatPrice(p) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(p);
    }
    
    function updateCartCount() {
        fetch('/carts/count')
            .then(r => r.json())
            .then(d => {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) cartCount.textContent = d.count || 0;
            })
            .catch(e => console.error('Error updating cart count:', e));
    }
    
    // Expose functions globally
    window.closeCartSidebar = closeCartSidebar;
    window.loadCartItems = loadCartItems;
    window.updateCartCount = updateCartCount;
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
