// Product Detail JavaScript functionality

// Sample product data
const sampleProduct = {
    id: 1,
    name: "iPhone 15 Pro 256GB",
    category: "phone",
    price: 28990000,
    originalPrice: 32990000,
    discount: 12,
    rating: 4.8,
    reviews: 1234,
    sold: 2456,
    image: "../assets/images/iphone-15-pro.jpg",
    images: [
        "../assets/images/iphone-15-pro.jpg",
        "../assets/images/iphone-15-pro-2.jpg",
        "../assets/images/iphone-15-pro-3.jpg",
        "../assets/images/iphone-15-pro-4.jpg"
    ],
    colors: [
        { name: "Titan Đen", value: "black", class: "bg-gray-800" },
        { name: "Titan Xanh", value: "blue", class: "bg-blue-900" },
        { name: "Titan Trắng", value: "white", class: "bg-yellow-100" },
        { name: "Titan Tự nhiên", value: "natural", class: "bg-yellow-600" }
    ],
    storage: [
        { capacity: "128GB", price: 26990000 },
        { capacity: "256GB", price: 28990000 },
        { capacity: "512GB", price: 32990000 },
        { capacity: "1TB", price: 36990000 }
    ],
    features: [
        "Chip A17 Pro mạnh mẽ với GPU 6 lõi",
        "Camera chính 48MP với zoom quang học 3x",
        "Khung viền titan cực kỳ bền bỉ",
        "Hỗ trợ USB-C và Action Button"
    ],
    specifications: {
        screen: "6.1\" Super Retina XDR",
        chip: "A17 Pro",
        ram: "8GB",
        camera: "48MP + 12MP + 12MP",
        battery: "3274 mAh",
        os: "iOS 17"
    },
    description: `iPhone 15 Pro đánh dấu một bước tiến vượt bậc trong công nghệ smartphone với chip A17 Pro tiên tiến, 
                  khung viền titan bền bỉ và hệ thống camera chuyên nghiệp. Thiết kế sang trọng với khung viền titan 
                  cực kỳ bền bỉ nhưng vẫn nhẹ hơn so với thế hệ trước.`
};

// Global variables
let currentProduct = null;
let selectedColor = null;
let selectedStorage = null;
let quantity = 1;
let selectedReviewRating = 0;
let allReviews = [];
let filteredReviews = [];

// Sample reviews data
const sampleReviews = [
    {
        id: 1,
        userName: "Nguyễn Văn A",
        rating: 5,
        title: "Sản phẩm tuyệt vời, rất hài lòng!",
        content: "iPhone 15 Pro thực sự là một chiếc điện thoại xuất sắc. Camera chụp ảnh cực kỳ sắc nét, đặc biệt là chế độ chụp đêm. Hiệu năng mượt mà, không lag khi chơi game hay sử dụng các ứng dụng nặng.",
        isVerified: true,
        helpfulCount: 24,
        date: "2024-01-15",
        replies: []
    },
    {
        id: 2,
        userName: "Trần Thị B",
        rating: 5,
        title: "Chất lượng cao, đáng đồng tiền",
        content: "Mình đã sử dụng iPhone 15 Pro được 2 tuần và thực sự rất ấn tượng. Màn hình hiển thị rất sắc nét, màu sắc chân thực. Face ID nhận diện rất nhanh và chính xác.",
        isVerified: true,
        helpfulCount: 18,
        date: "2024-01-12",
        replies: []
    },
    {
        id: 3,
        userName: "Lê Minh C",
        rating: 4,
        title: "Tốt nhưng giá hơi cao",
        content: "Sản phẩm chất lượng tốt, camera thực sự ấn tượng với khả năng zoom 3x quang học. Chip A17 Pro xử lý mọi tác vụ một cách mượt mà. Tuy nhiên, giá thành khá cao so với các đối thủ cùng phân khúc.",
        isVerified: false,
        helpfulCount: 12,
        date: "2024-01-10",
        replies: []
    }
];

// Initialize product detail page
document.addEventListener('DOMContentLoaded', function() {
    // Get product ID from URL params or use sample data
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    
    if (productId) {
        loadProductData(productId);
    } else {
        // Use sample data for demo
        currentProduct = sampleProduct;
        initializeProductDetail();
    }
    
    // Initialize reviews
    allReviews = [...sampleReviews];
    filteredReviews = [...sampleReviews];
    
    setupEventListeners();
    setupTabs();
    setupReviewForm();
});

// Load product data (in real app, this would fetch from API)
function loadProductData(productId) {
    // Get product data from main.js or localStorage
    const products = JSON.parse(localStorage.getItem('products')) || [];
    let product = products.find(p => p.id == productId);
    
    // If not found in localStorage, use sample data or fetch from API
    if (!product) {
        // In a real app, you would fetch from API here
        // For demo, we'll enhance sample data based on ID
        product = { ...sampleProduct };
        product.id = parseInt(productId);
        
        // Customize product data based on ID
        if (productId == 2) {
            product.name = "Samsung Galaxy S24 Ultra 512GB";
            product.price = 33990000;
            product.originalPrice = 36990000;
            product.image = "../assets/images/samsung-s24-ultra.jpg";
            product.category = "phone";
        } else if (productId == 3) {
            product.name = "MacBook Air M3 13 inch 256GB";
            product.price = 32990000;
            product.originalPrice = 35990000;
            product.image = "../assets/images/macbook-air-m3.jpg";
            product.category = "laptop";
        }
    }
    
    currentProduct = product;
    initializeProductDetail();
}

// Initialize product detail display
function initializeProductDetail() {
    if (!currentProduct) return;
    
    // Update page title
    document.title = `${currentProduct.name} - Techvicom`;
    
    // Update breadcrumb
    document.getElementById('breadcrumb-product').textContent = currentProduct.name;
    
    // Update product info
    document.getElementById('product-title').textContent = currentProduct.name;
    document.getElementById('current-price').textContent = formatPrice(currentProduct.price);
    document.getElementById('original-price').textContent = formatPrice(currentProduct.originalPrice);
    document.getElementById('discount-badge').textContent = `-${currentProduct.discount}%`;
    document.getElementById('rating-text').textContent = `(${currentProduct.rating})`;
    document.getElementById('review-count').textContent = currentProduct.reviews.toLocaleString();
    document.getElementById('sold-count').textContent = currentProduct.sold.toLocaleString();
    
    // Update rating stars
    updateRatingStars(currentProduct.rating);
    
    // Update main image
    document.getElementById('main-image').src = currentProduct.image;
    document.getElementById('main-image').alt = currentProduct.name;
    
    // Setup image gallery
    setupImageGallery();
    
    // Setup product options
    setupColorOptions();
    setupStorageOptions();
    
    // Update features
    updateProductFeatures();
    
    // Load reviews
    loadReviews();
    
    // Load related products
    loadRelatedProducts();
    
    // Set default selections
    selectedColor = currentProduct.colors[0];
    selectedStorage = currentProduct.storage[1]; // Default to 256GB
    
    console.log('Product detail initialized');
}

// Setup event listeners
function setupEventListeners() {
    // Quantity controls
    document.getElementById('decrease-qty').addEventListener('click', () => {
        if (quantity > 1) {
            quantity--;
            document.getElementById('quantity').value = quantity;
        }
    });
    
    document.getElementById('increase-qty').addEventListener('click', () => {
        quantity++;
        document.getElementById('quantity').value = quantity;
    });
    
    document.getElementById('quantity').addEventListener('change', (e) => {
        const value = parseInt(e.target.value);
        if (value > 0) {
            quantity = value;
        } else {
            quantity = 1;
            e.target.value = 1;
        }
    });
    
    // Action buttons
    document.getElementById('add-to-cart').addEventListener('click', addToCart);
    document.getElementById('buy-now').addEventListener('click', buyNow);
    document.getElementById('add-to-wishlist').addEventListener('click', addToWishlist);
    document.getElementById('compare').addEventListener('click', addToCompare);
}

// Setup image gallery
function setupImageGallery() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach((thumb, index) => {
        if (currentProduct.images[index]) {
            const img = thumb.querySelector('img');
            img.src = currentProduct.images[index];
            img.alt = `${currentProduct.name} - Hình ${index + 1}`;
            
            thumb.addEventListener('click', () => {
                // Update main image
                document.getElementById('main-image').src = currentProduct.images[index];
                
                // Update active thumbnail
                thumbnails.forEach(t => t.classList.remove('active', 'border-orange-500'));
                thumbnails.forEach(t => t.classList.add('border-transparent'));
                thumb.classList.add('active', 'border-orange-500');
                thumb.classList.remove('border-transparent');
            });
        }
    });
}

// Setup color options
function setupColorOptions() {
    const colorContainer = document.getElementById('color-options');
    colorContainer.innerHTML = '';
    
    currentProduct.colors.forEach((color, index) => {
        const colorBtn = document.createElement('button');
        colorBtn.className = `color-option w-12 h-12 rounded-full border-2 ${color.class}`;
        colorBtn.setAttribute('data-color', color.name);
        
        if (index === 0) {
            colorBtn.classList.add('active', 'border-orange-500', 'border-4');
        } else {
            colorBtn.classList.add('border-gray-300');
        }
        
        colorBtn.addEventListener('click', () => {
            // Update selected color
            selectedColor = color;
            document.getElementById('selected-color').textContent = color.name;
            
            // Update active state
            document.querySelectorAll('.color-option').forEach(btn => {
                btn.classList.remove('active', 'border-orange-500', 'border-4');
                btn.classList.add('border-gray-300', 'border-2');
            });
            colorBtn.classList.add('active', 'border-orange-500', 'border-4');
            colorBtn.classList.remove('border-gray-300', 'border-2');
        });
        
        colorContainer.appendChild(colorBtn);
    });
}

// Setup storage options
function setupStorageOptions() {
    const storageContainer = document.getElementById('storage-options');
    storageContainer.innerHTML = '';
    
    currentProduct.storage.forEach((storage, index) => {
        const storageBtn = document.createElement('button');
        storageBtn.className = 'storage-option border-2 rounded-lg p-3 text-center hover:border-orange-500';
        storageBtn.setAttribute('data-storage', storage.capacity);
        
        if (index === 1) { // Default to 256GB
            storageBtn.classList.add('active', 'border-orange-500', 'bg-orange-50');
        } else {
            storageBtn.classList.add('border-gray-300');
        }
        
        storageBtn.innerHTML = `
            <div class="font-semibold">${storage.capacity}</div>
            <div class="text-sm ${index === 1 ? 'text-orange-600' : 'text-gray-500'}">${formatPrice(storage.price)}</div>
        `;
        
        storageBtn.addEventListener('click', () => {
            // Update selected storage
            selectedStorage = storage;
            updatePrice(storage.price);
            
            // Update active state
            document.querySelectorAll('.storage-option').forEach(btn => {
                btn.classList.remove('active', 'border-orange-500', 'bg-orange-50');
                btn.classList.add('border-gray-300');
                const priceEl = btn.querySelector('.text-sm');
                priceEl.classList.remove('text-orange-600');
                priceEl.classList.add('text-gray-500');
            });
            storageBtn.classList.add('active', 'border-orange-500', 'bg-orange-50');
            storageBtn.classList.remove('border-gray-300');
            const priceEl = storageBtn.querySelector('.text-sm');
            priceEl.classList.add('text-orange-600');
            priceEl.classList.remove('text-gray-500');
        });
        
        storageContainer.appendChild(storageBtn);
    });
}

// Update price based on storage selection
function updatePrice(newPrice) {
    const discount = currentProduct.discount;
    const originalPrice = Math.round(newPrice / (1 - discount / 100));
    
    document.getElementById('current-price').textContent = formatPrice(newPrice);
    document.getElementById('original-price').textContent = formatPrice(originalPrice);
}

// Update product features
function updateProductFeatures() {
    const featuresContainer = document.getElementById('product-features');
    featuresContainer.innerHTML = '';
    
    currentProduct.features.forEach(feature => {
        const li = document.createElement('li');
        li.className = 'flex items-start space-x-2';
        li.innerHTML = `
            <i class="fas fa-check text-green-500 mt-1"></i>
            <span>${feature}</span>
        `;
        featuresContainer.appendChild(li);
    });
}

// Update rating stars
function updateRatingStars(rating) {
    const ratingContainer = document.getElementById('product-rating');
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    
    ratingContainer.innerHTML = '';
    
    for (let i = 0; i < 5; i++) {
        const star = document.createElement('i');
        if (i < fullStars) {
            star.className = 'fas fa-star';
        } else if (i === fullStars && hasHalfStar) {
            star.className = 'fas fa-star-half-alt';
        } else {
            star.className = 'far fa-star';
        }
        ratingContainer.appendChild(star);
    }
}

// Setup tabs functionality
function setupTabs() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetTab = btn.getAttribute('data-tab');
            
            // Update active tab button
            tabBtns.forEach(b => {
                b.classList.remove('active', 'border-orange-500', 'text-orange-600');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            btn.classList.add('active', 'border-orange-500', 'text-orange-600');
            btn.classList.remove('border-transparent', 'text-gray-500');
            
            // Update active tab content
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`${targetTab}-tab`).classList.remove('hidden');
        });
    });
}

// Load reviews
function loadReviews() {
    const reviewsList = document.getElementById('reviews-list');
    reviewsList.innerHTML = '';
    
    sampleReviews.forEach(review => {
        const reviewEl = document.createElement('div');
        reviewEl.className = 'border-b border-gray-200 pb-4';
        
        // Create stars
        let starsHTML = '';
        for (let i = 0; i < 5; i++) {
            if (i < review.rating) {
                starsHTML += '<i class="fas fa-star text-yellow-400"></i>';
            } else {
                starsHTML += '<i class="far fa-star text-gray-300"></i>';
            }
        }
        
        reviewEl.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-gray-600"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="font-semibold">${review.user}</span>
                        <div class="flex space-x-1">${starsHTML}</div>
                        <span class="text-gray-500 text-sm">${review.date}</span>
                    </div>
                    <p class="text-gray-700 mb-2">${review.comment}</p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <button class="hover:text-orange-600">
                            <i class="fas fa-thumbs-up mr-1"></i>
                            Hữu ích (${review.helpful})
                        </button>
                        <button class="hover:text-orange-600">
                            <i class="fas fa-reply mr-1"></i>
                            Trả lời
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        reviewsList.appendChild(reviewEl);
    });
}

// Load related products
function loadRelatedProducts() {
    const relatedContainer = document.getElementById('related-products');
    
    // In a real app, this would fetch related products from API
    // For now, we'll create some sample related products
    const relatedProducts = [
        {
            name: "iPhone 15 128GB",
            price: 24990000,
            originalPrice: 27990000,
            image: "../assets/images/iphone-15.jpg",
            rating: 4.7
        },
        {
            name: "iPhone 15 Pro Max 256GB",
            price: 32990000,
            originalPrice: 36990000,
            image: "../assets/images/iphone-15-pro-max.jpg",
            rating: 4.9
        },
        {
            name: "AirPods Pro 2",
            price: 6190000,
            originalPrice: 6990000,
            image: "../assets/images/airpods-pro-2.jpg",
            rating: 4.6
        },
        {
            name: "Apple Watch Series 9",
            price: 10990000,
            originalPrice: 12990000,
            image: "../assets/images/apple-watch-s9.jpg",
            rating: 4.7
        }
    ];
    
    relatedContainer.innerHTML = '';
    
    relatedProducts.forEach(product => {
        const productEl = document.createElement('div');
        productEl.className = 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer';
        
        const discount = Math.round((1 - product.price / product.originalPrice) * 100);
        
        productEl.innerHTML = `
            <div class="relative">
                <img src="${product.image}" alt="${product.name}" class="w-full h-48 object-contain p-4">
                <div class="absolute top-2 left-2 bg-custom-primary text-white px-2 py-1 rounded text-xs font-semibold">
                    -${discount}%
                </div>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">${product.name}</h3>
                <div class="flex items-center mb-2">
                    <div class="flex text-yellow-400 text-sm">
                        ${'<i class="fas fa-star"></i>'.repeat(Math.floor(product.rating))}
                    </div>
                    <span class="ml-1 text-gray-600 text-sm">(${product.rating})</span>
                </div>
                <div class="flex items-baseline space-x-2">
                    <span class="text-xl font-bold text-orange-600">${formatPrice(product.price)}</span>
                    <span class="text-sm text-gray-500 line-through">${formatPrice(product.originalPrice)}</span>
                </div>
            </div>
        `;
        
        productEl.addEventListener('click', () => {
            // In a real app, this would navigate to the product detail page
            console.log('Navigate to product:', product.name);
        });
        
        relatedContainer.appendChild(productEl);
    });
}

// Action functions
function addToCart() {
    // Auto-select first color and storage if not selected
    if (!selectedColor && currentProduct.colors && currentProduct.colors.length > 0) {
        selectColor(currentProduct.colors[0]);
    }
    if (!selectedStorage && currentProduct.storageOptions && currentProduct.storageOptions.length > 0) {
        selectStorage(currentProduct.storageOptions[0]);
    }
    
    // Skip validation - use defaults if still not selected
    const finalColor = selectedColor || { name: 'Mặc định', value: 'default' };
    const finalStorage = selectedStorage || { capacity: '128GB', price: currentProduct.price };
    
    const cartItem = {
        id: `${currentProduct.id}-${finalColor.value}-${finalStorage.capacity}`,
        productId: currentProduct.id,
        name: currentProduct.name,
        color: finalColor.name,
        storage: finalStorage.capacity,
        price: finalStorage.price,
        quantity: quantity,
        image: currentProduct.image
    };
    
    // Get existing cart
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Check if item already exists
    const existingIndex = cart.findIndex(item => item.id === cartItem.id);
    
    if (existingIndex !== -1) {
        cart[existingIndex].quantity += quantity;
    } else {
        cart.push(cartItem);
    }
    
    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Update cart count
    updateCartCount();
    
 
     // Show success message
    showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
    
    console.log('Added to cart:', cartItem);
}

function buyNow() {
    addToCart();
    // Redirect to checkout
    window.location.href = 'checkout.html';
}

function addToWishlist() {
    const wishlistItem = {
        id: currentProduct.id,
        name: currentProduct.name,
        price: selectedStorage ? selectedStorage.price : currentProduct.price,
        image: currentProduct.image
    };
    
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    
    if (!wishlist.find(item => item.id === wishlistItem.id)) {
        wishlist.push(wishlistItem);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        showToast('Đã thêm vào danh sách yêu thích!', 'success');
        
        // Update button state
        const btn = document.getElementById('add-to-wishlist');
        btn.innerHTML = '<i class="fas fa-heart text-red-500"></i><span>Đã yêu thích</span>';
    } else {
        showToast('Sản phẩm đã có trong danh sách yêu thích!', 'info');
    }
}

function addToCompare() {
    showToast('Tính năng so sánh đang được phát triển!', 'info');
}

// Utility functions
function formatPrice(price) {
    return price.toLocaleString('vi-VN') + '₫';
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    const cartCountEl = document.getElementById('cart-count');
    if (cartCountEl) {
        cartCountEl.textContent = totalItems;
    }
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-semibold transform translate-x-full transition-transform duration-300`;
    
    // Set color based on type
    switch (type) {
        case 'success':
            toast.classList.add('bg-green-500');
            break;
        case 'error':
            toast.classList.add('bg-custom-primary');
            break;
        default:
            toast.classList.add('bg-blue-500');
    }
    
    toast.textContent = message;
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Hide toast after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});

// Setup review form functionality
function setupReviewForm() {
    setupReviewRatingInput();
    setupReviewFormSubmission();
    setupReviewFilters();
    loadReviewsInTab();
}

// Setup review rating input
function setupReviewRatingInput() {
    const stars = document.querySelectorAll('#rating-input i');
    const ratingValue = document.getElementById('rating-value');
    const ratingText = document.getElementById('rating-text');
    
    const ratingLabels = {
        1: "Rất tệ",
        2: "Tệ", 
        3: "Bình thường",
        4: "Tốt",
        5: "Tuyệt vời"
    };
    
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', () => {
            highlightStars(index + 1);
        });
        
        star.addEventListener('mouseleave', () => {
            highlightStars(selectedReviewRating);
        });
        
        star.addEventListener('click', () => {
            selectedReviewRating = index + 1;
            ratingValue.value = selectedReviewRating;
            ratingText.textContent = ratingLabels[selectedReviewRating];
            highlightStars(selectedReviewRating);
        });
    });
    
    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('far');
                star.classList.add('fas', 'text-yellow-400');
            } else {
                star.classList.remove('fas', 'text-yellow-400');
                star.classList.add('far');
            }
        });
    }
}

// Setup review form submission
function setupReviewFormSubmission() {
    const form = document.getElementById('review-form');
    
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const reviewData = {
            rating: selectedReviewRating,
            title: document.getElementById('review-title').value,
            content: document.getElementById('review-content').value,
            name: document.getElementById('reviewer-name').value,
            email: document.getElementById('reviewer-email').value
        };
        
        // Validate required fields
        if (!reviewData.rating || !reviewData.title || !reviewData.content || !reviewData.name || !reviewData.email) {
            showToast('Vui lòng điền đầy đủ các trường bắt buộc!', 'error');
            return;
        }
        
        // Create new review
        const newReview = {
            id: Date.now(),
            userName: reviewData.name,
            rating: reviewData.rating,
            title: reviewData.title,
            content: reviewData.content,
            isVerified: false,
            helpfulCount: 0,
            date: new Date().toISOString().split('T')[0],
            replies: []
        };
        
        // Add to reviews array
        allReviews.unshift(newReview);
        filteredReviews = [...allReviews];
        
        // Reload reviews
        loadReviewsInTab();
        
        // Reset form
        form.reset();
        selectedReviewRating = 0;
        document.getElementById('rating-value').value = 0;
        document.getElementById('rating-text').textContent = 'Chọn số sao';
        
        // Reset rating stars
        document.querySelectorAll('#rating-input i').forEach(star => {
            star.classList.remove('fas', 'text-yellow-400');
            star.classList.add('far');
        });
        
        showToast('Cảm ơn bạn đã đánh giá sản phẩm!', 'success');
    });
}

// Setup review filters
function setupReviewFilters() {
    const filterRating = document.getElementById('filter-rating');
    const sortReviews = document.getElementById('sort-reviews');
    
    filterRating.addEventListener('change', () => {
        applyFiltersAndSort();
    });
    
    sortReviews.addEventListener('change', () => {
        applyFiltersAndSort();
    });
}

// Apply filters and sort
function applyFiltersAndSort() {
    const ratingFilter = document.getElementById('filter-rating').value;
    const sortBy = document.getElementById('sort-reviews').value;
    
    // Filter by rating
    filteredReviews = allReviews.filter(review => {
        return !ratingFilter || review.rating == ratingFilter;
    });
    
    // Sort reviews
    filteredReviews.sort((a, b) => {
        switch (sortBy) {
            case 'newest':
                return new Date(b.date) - new Date(a.date);
            case 'oldest':
                return new Date(a.date) - new Date(b.date);
            case 'highest':
                return b.rating - a.rating;
            case 'lowest':
                return a.rating - b.rating;
            case 'helpful':
                return b.helpfulCount - a.helpfulCount;
            default:
                return 0;
        }
    });
    
    loadReviewsInTab();
}

// Load reviews in the tab
function loadReviewsInTab() {
    const reviewsList = document.getElementById('reviews-list');
    
    // Keep existing static reviews and add dynamic ones
    const staticReviews = reviewsList.querySelectorAll('.border-b');
    const dynamicReviews = filteredReviews.slice(0, 5); // Show first 5
    
    // Clear only dynamic content (keep static reviews)
    const dynamicElements = reviewsList.querySelectorAll('.dynamic-review');
    dynamicElements.forEach(el => el.remove());
    
    // Add dynamic reviews
    dynamicReviews.forEach(review => {
        const reviewElement = createReviewElement(review);
        reviewElement.classList.add('dynamic-review');
        reviewsList.appendChild(reviewElement);
    });
    
    // Update load more button
    const loadMoreBtn = document.getElementById('load-more-reviews');
    if (filteredReviews.length <= 5) {
        loadMoreBtn.style.display = 'none';
    } else {
        loadMoreBtn.style.display = 'block';
    }
}

// Create review element
function createReviewElement(review) {
    const reviewDiv = document.createElement('div');
    reviewDiv.className = 'border-b border-gray-200 pb-6';
    
    const starsHtml = createStarsHtml(review.rating);
    const verifiedBadge = review.isVerified ? 
        '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">✓ Đã mua hàng</span>' : '';
    
    reviewDiv.innerHTML = `
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-gray-600"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-3">
                        <span class="font-semibold text-gray-800">${review.userName}</span>
                        ${verifiedBadge}
                    </div>
                    <span class="text-sm text-gray-500">${formatReviewDate(review.date)}</span>
                </div>
                
                <div class="flex items-center space-x-2 mb-2">
                    <div class="flex text-yellow-400">${starsHtml}</div>
                    <span class="text-sm text-gray-500">(${review.rating}/5)</span>
                </div>
                
                <h5 class="font-semibold text-gray-800 mb-2">${review.title}</h5>
                <p class="text-gray-700 mb-3">${review.content}</p>
                
                <div class="flex items-center space-x-6 text-sm">
                    <button class="flex items-center space-x-1 text-gray-500 hover:text-orange-600 transition-colors helpful-btn" onclick="markHelpful(${review.id})">
                        <i class="far fa-thumbs-up"></i>
                        <span>Hữu ích (${review.helpfulCount})</span>
                    </button>
                    <button class="flex items-center space-x-1 text-gray-500 hover:text-orange-600 transition-colors">
                        <i class="far fa-comment"></i>
                        <span>Trả lời</span>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    return reviewDiv;
}

// Create stars HTML
function createStarsHtml(rating) {
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            starsHtml += '<i class="fas fa-star"></i>';
        } else {
            starsHtml += '<i class="far fa-star"></i>';
        }
    }
    return starsHtml;
}

// Format review date
function formatReviewDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

// Mark review as helpful
function markHelpful(reviewId) {
    const review = allReviews.find(r => r.id === reviewId);
    if (review) {
        review.helpfulCount++;
        
        // Update the display
        const helpfulBtn = document.querySelector(`button[onclick="markHelpful(${reviewId})"]`);
        if (helpfulBtn) {
            helpfulBtn.querySelector('span').textContent = `Hữu ích (${review.helpfulCount})`;
            helpfulBtn.classList.add('text-orange-600');
            helpfulBtn.disabled = true;
        }
        
        showToast('Cảm ơn phản hồi của bạn!', 'success');
    }
}
