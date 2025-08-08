// Reviews and Comments JavaScript functionality

// Sample reviews data
const sampleReviews = [
    {
        id: 1,
        productId: 1,
        userId: 101,
        userName: "Nguyễn Văn A",
        userAvatar: null,
        rating: 5,
        title: "Sản phẩm tuyệt vời, rất hài lòng!",
        content: "iPhone 15 Pro thực sự là một chiếc điện thoại xuất sắc. Camera chụp ảnh cực kỳ sắc nét, đặc biệt là chế độ chụp đêm. Hiệu năng mượt mà, không lag khi chơi game hay sử dụng các ứng dụng nặng. Pin cũng khá ổn, có thể sử dụng cả ngày mà không lo hết pin.",
        pros: "Camera xuất sắc, hiệu năng mạnh mẽ, thiết kế đẹp",
        cons: "Giá hơi cao, sạc chậm hơn mong đợi",
        isVerified: true,
        isRecommended: true,
        helpfulCount: 24,
        replyCount: 3,
        date: "2024-01-15T10:30:00Z",
        images: []
    },
    {
        id: 2,
        productId: 1,
        userId: 102,
        userName: "Trần Thị B",
        userAvatar: null,
        rating: 5,
        title: "Chất lượng cao, đáng đồng tiền bát gạo",
        content: "Mình đã sử dụng iPhone 15 Pro được 2 tuần và thực sự rất ấn tượng. Màn hình hiển thị rất sắc nét, màu sắc chân thực. Tính năng Action Button rất tiện lợi, có thể tùy chỉnh theo ý muốn. Face ID nhận diện rất nhanh và chính xác.",
        pros: "Màn hình đẹp, Face ID nhanh, Action Button tiện dụng",
        cons: "Nặng hơn iPhone thường",
        isVerified: true,
        isRecommended: true,
        helpfulCount: 18,
        replyCount: 1,
        date: "2024-01-12T14:20:00Z",
        images: []
    },
    {
        id: 3,
        productId: 1,
        userId: 103,
        userName: "Lê Minh C",
        userAvatar: null,
        rating: 4,
        title: "Tốt nhưng giá hơi cao",
        content: "Sản phẩm chất lượng tốt, camera thực sự ấn tượng với khả năng zoom 3x quang học. Chip A17 Pro xử lý mọi tác vụ một cách mượt mà. Tuy nhiên, giá thành khá cao so với các đối thủ cùng phân khúc. Pin có thể tốt hơn nếu sử dụng nhiều.",
        pros: "Camera zoom tốt, chip A17 Pro mạnh",
        cons: "Giá cao, pin chưa thực sự ấn tượng",
        isVerified: false,
        isRecommended: true,
        helpfulCount: 12,
        replyCount: 0,
        date: "2024-01-10T09:15:00Z",
        images: []
    },
    {
        id: 4,
        productId: 1,
        userId: 104,
        userName: "Phạm Thị D",
        userAvatar: null,
        rating: 5,
        title: "Camera quá đỉnh, chụp ảnh như photographer",
        content: "Là một người yêu thích nhiếp ảnh, mình rất hài lòng với camera của iPhone 15 Pro. Chế độ Portrait Mode tạo ra những bức ảnh với độ xóa phông tự nhiên. Khả năng quay video 4K cũng rất ổn định. Thiết kế titan sang trọng và bền bỉ.",
        pros: "Camera tuyệt vời, quay video 4K ổn định, thiết kế cao cấp",
        cons: "Giá thành cao",
        isVerified: true,
        isRecommended: true,
        helpfulCount: 31,
        replyCount: 5,
        date: "2024-01-08T16:45:00Z",
        images: []
    },
    {
        id: 5,
        productId: 1,
        userId: 105,
        userName: "Hoàng Văn E",
        userAvatar: null,
        rating: 3,
        title: "Ổn nhưng không quá ấn tượng",
        content: "Sản phẩm có chất lượng tốt nhưng không có nhiều đổi mới so với thế hệ trước. Hiệu năng thì không có gì để chê, nhưng cảm giác như Apple chưa thực sự tạo ra breakthrough. Giá cả vẫn là vấn đề lớn nhất.",
        pros: "Hiệu năng ổn định, chất lượng build tốt",
        cons: "Ít đổi mới, giá cao, không có charger trong hộp",
        isVerified: true,
        isRecommended: false,
        helpfulCount: 8,
        replyCount: 2,
        date: "2024-01-05T11:30:00Z",
        images: []
    }
];

// Global variables
let allReviews = [...sampleReviews];
let filteredReviews = [...sampleReviews];
let currentPage = 1;
let reviewsPerPage = 5;
let selectedRating = 0;

// Initialize reviews page
document.addEventListener('DOMContentLoaded', function() {
    initializeReviewsPage();
    setupEventListeners();
    loadReviews();
    updateReviewStats();
});

// Initialize page
function initializeReviewsPage() {
    // Get product info from URL or localStorage
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('productId');
    
    // Load product info (in real app, fetch from API)
    loadProductInfo(productId);
    
    // Setup rating input
    setupRatingInput();
    
    // Setup character counter
    setupCharacterCounter();
    
    console.log('Reviews page initialized');
}

// Load product information
function loadProductInfo(productId) {
    // In real app, this would fetch from API
    const productInfo = {
        id: 1,
        name: "iPhone 15 Pro 256GB",
        image: "../assets/images/iphone-15-pro.jpg",
        rating: 4.8,
        reviewCount: 1234
    };
    
    document.getElementById('product-name').textContent = productInfo.name;
    document.getElementById('product-image').src = productInfo.image;
    document.getElementById('rating-text').textContent = `(${productInfo.rating})`;
    document.getElementById('review-count').textContent = productInfo.reviewCount.toLocaleString();
    
    updateProductRatingStars(productInfo.rating);
}

// Setup event listeners
function setupEventListeners() {
    // Filter and sort controls
    document.getElementById('filter-rating').addEventListener('change', handleFilterChange);
    document.getElementById('filter-verified').addEventListener('change', handleFilterChange);
    document.getElementById('sort-reviews').addEventListener('change', handleSortChange);
    
    // Review form
    document.getElementById('review-form').addEventListener('submit', handleReviewSubmit);
    
    // Load more button
    document.getElementById('load-more-reviews').addEventListener('click', loadMoreReviews);
}

// Setup rating input
function setupRatingInput() {
    const stars = document.querySelectorAll('#rating-input i');
    const ratingValue = document.getElementById('rating-value');
    const ratingText = document.getElementById('rating-text-input');
    
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
            highlightStars(selectedRating);
        });
        
        star.addEventListener('click', () => {
            selectedRating = index + 1;
            ratingValue.value = selectedRating;
            ratingText.textContent = ratingLabels[selectedRating];
            highlightStars(selectedRating);
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

// Setup character counter
function setupCharacterCounter() {
    const textarea = document.getElementById('review-content');
    const counter = document.getElementById('character-count');
    const maxLength = 1000;
    
    textarea.addEventListener('input', () => {
        const currentLength = textarea.value.length;
        counter.textContent = currentLength;
        
        if (currentLength > maxLength) {
            counter.classList.add('text-red-500');
            textarea.classList.add('border-custom-primary');
        } else {
            counter.classList.remove('text-red-500');
            textarea.classList.remove('border-custom-primary');
        }
    });
}

// Handle filter changes
function handleFilterChange() {
    const ratingFilter = document.getElementById('filter-rating').value;
    const verifiedFilter = document.getElementById('filter-verified').value;
    
    filteredReviews = allReviews.filter(review => {
        let matchesRating = !ratingFilter || review.rating == ratingFilter;
        let matchesVerified = !verifiedFilter || 
            (verifiedFilter === 'verified' && review.isVerified) ||
            (verifiedFilter === 'unverified' && !review.isVerified);
        
        return matchesRating && matchesVerified;
    });
    
    currentPage = 1;
    loadReviews();
}

// Handle sort changes
function handleSortChange() {
    const sortBy = document.getElementById('sort-reviews').value;
    
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
    
    currentPage = 1;
    loadReviews();
}

// Load and display reviews
function loadReviews(append = false) {
    const container = document.getElementById('reviews-container');
    
    if (!append) {
        container.innerHTML = '';
        currentPage = 1;
    }
    
    const startIndex = (currentPage - 1) * reviewsPerPage;
    const endIndex = startIndex + reviewsPerPage;
    const reviewsToShow = filteredReviews.slice(startIndex, endIndex);
    
    reviewsToShow.forEach(review => {
        const reviewElement = createReviewElement(review);
        container.appendChild(reviewElement);
    });
    
    // Update load more button
    const loadMoreBtn = document.getElementById('load-more-reviews');
    if (endIndex >= filteredReviews.length) {
        loadMoreBtn.style.display = 'none';
    } else {
        loadMoreBtn.style.display = 'block';
    }
}

// Create review element
function createReviewElement(review) {
    const reviewDiv = document.createElement('div');
    reviewDiv.className = 'p-6 review-item';
    reviewDiv.setAttribute('data-review-id', review.id);
    
    const starsHtml = createStarsHtml(review.rating);
    const verifiedBadge = review.isVerified ? 
        '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">✓ Đã mua hàng</span>' : '';
    const recommendBadge = review.isRecommended ? 
        '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">👍 Giới thiệu</span>' : '';
    
    reviewDiv.innerHTML = `
        <div class="flex items-start space-x-4">
            <!-- User Avatar -->
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                ${review.userAvatar ? 
                    `<img src="${review.userAvatar}" alt="${review.userName}" class="w-full h-full rounded-full object-cover">` :
                    '<i class="fas fa-user text-gray-600"></i>'
                }
            </div>
            
            <!-- Review Content -->
            <div class="flex-1">
                <!-- Header -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-3">
                        <span class="font-semibold text-gray-800">${review.userName}</span>
                        ${verifiedBadge}
                        ${recommendBadge}
                    </div>
                    <div class="text-sm text-gray-500">
                        ${formatDate(review.date)}
                    </div>
                </div>
                
                <!-- Rating and Title -->
                <div class="mb-3">
                    <div class="flex items-center space-x-2 mb-1">
                        <div class="flex text-yellow-400">${starsHtml}</div>
                        <span class="text-sm text-gray-500">(${review.rating}/5)</span>
                    </div>
                    <h4 class="font-semibold text-gray-800">${review.title}</h4>
                </div>
                
                <!-- Review Content -->
                <div class="mb-4">
                    <p class="text-gray-700 leading-relaxed">${review.content}</p>
                </div>
                
                <!-- Pros and Cons -->
                ${review.pros || review.cons ? `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        ${review.pros ? `
                            <div class="bg-green-50 p-3 rounded-lg">
                                <div class="flex items-center text-green-700 font-semibold mb-1">
                                    <i class="fas fa-thumbs-up mr-2"></i>
                                    Ưu điểm
                                </div>
                                <p class="text-green-600 text-sm">${review.pros}</p>
                            </div>
                        ` : ''}
                        ${review.cons ? `
                            <div class="bg-red-50 p-3 rounded-lg">
                                <div class="flex items-center text-red-700 font-semibold mb-1">
                                    <i class="fas fa-thumbs-down mr-2"></i>
                                    Nhược điểm
                                </div>
                                <p class="text-red-600 text-sm">${review.cons}</p>
                            </div>
                        ` : ''}
                    </div>
                ` : ''}
                
                <!-- Review Actions -->
                <div class="flex items-center space-x-6 text-sm">
                    <button class="flex items-center space-x-1 text-gray-500 hover:text-orange-600 transition-colors helpful-btn" data-review-id="${review.id}">
                        <i class="far fa-thumbs-up"></i>
                        <span>Hữu ích (${review.helpfulCount})</span>
                    </button>
                    <button class="flex items-center space-x-1 text-gray-500 hover:text-orange-600 transition-colors reply-btn" data-review-id="${review.id}">
                        <i class="far fa-comment"></i>
                        <span>Trả lời (${review.replyCount})</span>
                    </button>
                    <button class="flex items-center space-x-1 text-gray-500 hover:text-orange-600 transition-colors report-btn" data-review-id="${review.id}">
                        <i class="far fa-flag"></i>
                        <span>Báo cáo</span>
                    </button>
                </div>
                
                <!-- Reply Section (Hidden by default) -->
                <div class="reply-section hidden mt-4 pl-4 border-l-2 border-gray-200">
                    <textarea placeholder="Viết phản hồi của bạn..." 
                             class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-400 resize-none" 
                             rows="3"></textarea>
                    <div class="flex justify-end space-x-2 mt-2">
                        <button class="px-4 py-2 text-gray-600 hover:text-gray-800 cancel-reply">Hủy</button>
                        <button class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 submit-reply">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add event listeners
    setupReviewActions(reviewDiv, review);
    
    return reviewDiv;
}

// Setup review action buttons
function setupReviewActions(reviewElement, review) {
    const helpfulBtn = reviewElement.querySelector('.helpful-btn');
    const replyBtn = reviewElement.querySelector('.reply-btn');
    const reportBtn = reviewElement.querySelector('.report-btn');
    const replySection = reviewElement.querySelector('.reply-section');
    const cancelReply = reviewElement.querySelector('.cancel-reply');
    const submitReply = reviewElement.querySelector('.submit-reply');
    
    helpfulBtn.addEventListener('click', () => {
        handleHelpfulClick(review.id, helpfulBtn);
    });
    
    replyBtn.addEventListener('click', () => {
        replySection.classList.toggle('hidden');
    });
    
    cancelReply.addEventListener('click', () => {
        replySection.classList.add('hidden');
        replySection.querySelector('textarea').value = '';
    });
    
    submitReply.addEventListener('click', () => {
        const replyText = replySection.querySelector('textarea').value.trim();
        if (replyText) {
            handleReplySubmit(review.id, replyText);
            replySection.classList.add('hidden');
            replySection.querySelector('textarea').value = '';
        }
    });
    
    reportBtn.addEventListener('click', () => {
        handleReportClick(review.id);
    });
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

// Handle review form submission
function handleReviewSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const reviewData = {
        rating: parseInt(formData.get('rating')),
        title: formData.get('title'),
        content: formData.get('content'),
        pros: formData.get('pros'),
        cons: formData.get('cons'),
        name: formData.get('name'),
        email: formData.get('email'),
        recommend: formData.has('recommend')
    };
    
    // Validate required fields
    if (!reviewData.rating || !reviewData.title || !reviewData.content || !reviewData.name || !reviewData.email) {
        showToast('Vui lòng điền đầy đủ các trường bắt buộc!', 'error');
        return;
    }
    
    if (reviewData.rating === 0) {
        showToast('Vui lòng chọn số sao đánh giá!', 'error');
        return;
    }
    
    // Create new review object
    const newReview = {
        id: Date.now(),
        productId: 1,
        userId: Date.now(),
        userName: reviewData.name,
        userAvatar: null,
        rating: reviewData.rating,
        title: reviewData.title,
        content: reviewData.content,
        pros: reviewData.pros,
        cons: reviewData.cons,
        isVerified: false, // In real app, check if user purchased
        isRecommended: reviewData.recommend,
        helpfulCount: 0,
        replyCount: 0,
        date: new Date().toISOString(),
        images: []
    };
    
    // Add to reviews array
    allReviews.unshift(newReview);
    filteredReviews = [...allReviews];
    
    // Reload reviews
    loadReviews();
    updateReviewStats();
    
    // Reset form
    e.target.reset();
    selectedRating = 0;
    document.getElementById('rating-value').value = 0;
    document.getElementById('rating-text-input').textContent = 'Chọn số sao';
    document.getElementById('character-count').textContent = '0';
    
    // Reset rating stars
    document.querySelectorAll('#rating-input i').forEach(star => {
        star.classList.remove('fas', 'text-yellow-400');
        star.classList.add('far');
    });
    
    showToast('Cảm ơn bạn đã đánh giá sản phẩm!', 'success');
    
    console.log('Review submitted:', newReview);
}

// Handle helpful click
function handleHelpfulClick(reviewId, button) {
    const review = allReviews.find(r => r.id === reviewId);
    if (review) {
        review.helpfulCount++;
        button.querySelector('span').textContent = `Hữu ích (${review.helpfulCount})`;
        
        // Mark as clicked
        button.classList.add('text-orange-600');
        button.disabled = true;
        
        showToast('Cảm ơn phản hồi của bạn!', 'success');
    }
}

// Handle reply submission
function handleReplySubmit(reviewId, replyText) {
    const review = allReviews.find(r => r.id === reviewId);
    if (review) {
        review.replyCount++;
        
        // In a real app, you would save the reply to backend
        console.log('Reply submitted for review', reviewId, ':', replyText);
        
        showToast('Phản hồi của bạn đã được gửi!', 'success');
    }
}

// Handle report click
function handleReportClick(reviewId) {
    if (confirm('Bạn có chắc chắn muốn báo cáo đánh giá này?')) {
        // In a real app, you would send report to backend
        console.log('Review reported:', reviewId);
        showToast('Đã gửi báo cáo. Chúng tôi sẽ xem xét sớm nhất!', 'info');
    }
}

// Load more reviews
function loadMoreReviews() {
    currentPage++;
    loadReviews(true);
}

// Update review statistics
function updateReviewStats() {
    const totalReviews = allReviews.length;
    const avgRating = totalReviews > 0 ? 
        allReviews.reduce((sum, review) => sum + review.rating, 0) / totalReviews : 0;
    
    // Update overall rating
    document.getElementById('overall-rating').textContent = avgRating.toFixed(1);
    document.getElementById('total-reviews').textContent = `${totalReviews.toLocaleString()} đánh giá`;
    
    // Update stars
    updateOverallStars(avgRating);
    
    // Update rating breakdown
    updateRatingBreakdown();
}

// Update overall rating stars
function updateOverallStars(rating) {
    const container = document.getElementById('overall-stars');
    container.innerHTML = '';
    
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('i');
        if (i <= Math.floor(rating)) {
            star.className = 'fas fa-star';
        } else if (i - 0.5 <= rating) {
            star.className = 'fas fa-star-half-alt';
        } else {
            star.className = 'far fa-star';
        }
        container.appendChild(star);
    }
}

// Update product rating stars
function updateProductRatingStars(rating) {
    const container = document.getElementById('product-rating');
    container.innerHTML = '';
    
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('i');
        if (i <= Math.floor(rating)) {
            star.className = 'fas fa-star';
        } else if (i - 0.5 <= rating) {
            star.className = 'fas fa-star-half-alt';
        } else {
            star.className = 'far fa-star';
        }
        container.appendChild(star);
    }
}

// Update rating breakdown bars
function updateRatingBreakdown() {
    const totalReviews = allReviews.length;
    
    for (let rating = 1; rating <= 5; rating++) {
        const count = allReviews.filter(r => r.rating === rating).length;
        const percentage = totalReviews > 0 ? (count / totalReviews) * 100 : 0;
        
        // Find the corresponding bar and update it
        const bars = document.querySelectorAll('.bg-yellow-400');
        const targetBar = bars[5 - rating]; // Reverse order (5 star is first)
        
        if (targetBar) {
            targetBar.style.width = `${percentage}%`;
            targetBar.setAttribute('data-percentage', percentage);
            
            // Update text
            const row = targetBar.closest('.flex');
            if (row) {
                const percentageText = row.querySelector('.text-gray-600');
                const countText = row.querySelector('.text-gray-500');
                
                if (percentageText) percentageText.textContent = `${Math.round(percentage)}%`;
                if (countText) countText.textContent = `(${count})`;
            }
        }
    }
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-semibold transform translate-x-full transition-transform duration-300`;
    
    switch (type) {
        case 'success':
            toast.classList.add('bg-green-500');
            break;
        case 'error':
            toast.classList.add('bg-custom-primary');
            break;
        case 'info':
            toast.classList.add('bg-blue-500');
            break;
        default:
            toast.classList.add('bg-gray-500');
    }
    
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}
