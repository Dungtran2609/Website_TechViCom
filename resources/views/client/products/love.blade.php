@extends('client.layouts.app')

@section('title', 'Sản phẩm yêu thích - Techvicom')

@push('styles')
    <style>
        /* Product Card Styles */
        .product-card {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        /* Favorite Button */
        .favorite-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .favorite-btn:hover {
            background: white;
            transform: scale(1.1);
        }

        .favorite-btn.active {
            background: #ff6c2f;
            color: white;
        }

        .favorite-btn.active i {
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #9ca3af;
            margin-bottom: 30px;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .filter-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #374151;
        }

        .filter-option {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .filter-option input[type="checkbox"] {
            margin-right: 8px;
        }

        .filter-option label {
            cursor: pointer;
            color: #6b7280;
        }

        .filter-option:hover label {
            color: #374151;
        }

        /* Price Range */
        .price-range {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .price-range input {
            flex: 1;
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }

        /* Sort Options */
        .sort-select {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background: white;
            color: #374151;
        }

        /* Loading State */
        .loading {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }

        .loading i {
            font-size: 2rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Hidden Products */
        .product-card.hidden {
            display: none;
        }

        /* Show Products */
        .product-card.show {
            display: block;
        }
    </style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Trang chủ</a></li>
                <li><span class="text-gray-400">></span></li>
                <li><span class="text-gray-900 font-medium">Sản phẩm yêu thích</span></li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Sản phẩm yêu thích</h1>
            <p class="text-lg text-gray-600">Những sản phẩm bạn đã thêm vào danh sách yêu thích</p>
        </div>

        <!-- Loading State -->
        <div id="loading" class="loading">
            <i class="fas fa-spinner"></i>
            <p>Đang tải sản phẩm yêu thích...</p>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="empty-state" style="display: none;">
            <i class="far fa-heart"></i>
            <h3>Chưa có sản phẩm yêu thích</h3>
            <p>Bạn chưa thêm sản phẩm nào vào danh sách yêu thích</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition">
                <i class="fas fa-shopping-bag mr-2"></i>
                Khám phá sản phẩm
            </a>
        </div>

        <!-- Content -->
        <div id="content" style="display: none;">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters -->
                <div class="lg:w-1/4">
                    <div class="filter-section">
                        <h3 class="filter-title">Bộ lọc</h3>
                        
                        <!-- Category Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Danh mục</h4>
                            <div class="space-y-2">
                                @foreach($categories as $category)
                                    <div class="filter-option">
                                        <input type="checkbox" id="cat_{{ $category->id }}" class="category-filter" value="{{ $category->id }}">
                                        <label for="cat_{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Thương hiệu</h4>
                            <div class="space-y-2">
                                @foreach($brands as $brand)
                                    <div class="filter-option">
                                        <input type="checkbox" id="brand_{{ $brand->id }}" class="brand-filter" value="{{ $brand->id }}">
                                        <label for="brand_{{ $brand->id }}">{{ $brand->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Khoảng giá</h4>
                            <div class="price-range">
                                <input type="number" id="min-price" placeholder="Từ" class="price-input">
                                <span>-</span>
                                <input type="number" id="max-price" placeholder="Đến" class="price-input">
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        <button id="clear-filters" class="w-full py-2 px-4 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Xóa bộ lọc
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="lg:w-3/4">
                    <!-- Sort and Count -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <span id="product-count" class="text-gray-600">0 sản phẩm yêu thích</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <label for="sort-select" class="text-sm text-gray-600">Sắp xếp:</label>
                            <select id="sort-select" class="sort-select">
                                <option value="newest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                                <option value="price-low">Giá tăng dần</option>
                                <option value="price-high">Giá giảm dần</option>
                                <option value="name-asc">Tên A-Z</option>
                                <option value="name-desc">Tên Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden" data-product-id="{{ $product->id }}" data-category="{{ $product->category_id }}" data-brand="{{ $product->brand_id }}">
                                <div class="relative">
                                    <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image"
                                         onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                    
                                    <button class="favorite-btn active" data-product-id="{{ $product->id }}" onclick="toggleFavorite({{ $product->id }})">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                                    
                                    @php
                                        $variant = $product->variants->first();
                                        $price = $variant ? $variant->price : 0;
                                        $salePrice = $variant && $variant->sale_price ? $variant->sale_price : null;
                                        $finalPrice = $salePrice ?: $price;
                                    @endphp
                                    
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            @if($salePrice)
                                                <span class="text-lg font-bold text-orange-600">{{ number_format($salePrice) }}đ</span>
                                                <span class="text-sm text-gray-500 line-through">{{ number_format($price) }}đ</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-800">{{ number_format($price) }}đ</span>
                                            @endif
                                        </div>
                                        
                                        @if($product->avg_rating)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                                <span>{{ number_format($product->avg_rating, 1) }}</span>
                                                <span class="ml-1">({{ $product->reviews_count }})</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-3">
                                        <span>{{ $product->category->name ?? 'N/A' }}</span>
                                        <span>{{ $product->brand->name ?? 'N/A' }}</span>
                                    </div>
                                    
                                    <button onclick="window.location.href='{{ route('products.show', $product->id) }}'" 
                                            class="w-full py-2 px-4 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                                        Xem chi tiết
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- No Results -->
                    <div id="no-results" class="text-center py-12" style="display: none;">
                        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Không tìm thấy sản phẩm</h3>
                        <p class="text-gray-500">Thử thay đổi bộ lọc hoặc tìm kiếm sản phẩm khác</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const FAVORITES_KEY = 'favorites';
    const FAVORITES_KEY_ALT = 'tv_wishlist_ids'; // Key khác được sử dụng trong home.blade.php
    
    let favoriteIds = [];
    let allProducts = [];
    
    // Load favorite IDs từ localStorage
    function loadFavorites() {
        try {
            // Thử cả hai key
            let favorites = JSON.parse(localStorage.getItem(FAVORITES_KEY) || '[]');
            if (favorites.length === 0) {
                favorites = JSON.parse(localStorage.getItem(FAVORITES_KEY_ALT) || '[]');
            }
            favoriteIds = favorites.map(id => parseInt(id));
        } catch (e) {
            favoriteIds = [];
        }
    }
    
    // Lọc sản phẩm yêu thích
    function filterFavoriteProducts() {
        const productCards = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        productCards.forEach(card => {
            const productId = parseInt(card.dataset.productId);
            const isFavorite = favoriteIds.includes(productId);
            
            if (isFavorite) {
                card.classList.remove('hidden');
                card.classList.add('show');
                visibleCount++;
            } else {
                card.classList.add('hidden');
                card.classList.remove('show');
            }
        });
        
        // Cập nhật số lượng sản phẩm
        document.getElementById('product-count').textContent = `${visibleCount} sản phẩm yêu thích`;
        
        // Hiển thị/ẩn empty state
        if (visibleCount === 0) {
            document.getElementById('empty-state').style.display = 'block';
            document.getElementById('content').style.display = 'none';
        } else {
            document.getElementById('empty-state').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        }
        
        // Ẩn loading
        document.getElementById('loading').style.display = 'none';
    }
    
    // Toggle favorite
    window.toggleFavorite = function(productId) {
        const index = favoriteIds.indexOf(productId);
        if (index > -1) {
            favoriteIds.splice(index, 1);
        } else {
            favoriteIds.push(productId);
        }
        
        // Lưu vào localStorage
        localStorage.setItem(FAVORITES_KEY, JSON.stringify(favoriteIds));
        localStorage.setItem(FAVORITES_KEY_ALT, JSON.stringify(favoriteIds));
        
        // Cập nhật UI
        filterFavoriteProducts();
        
        // Hiển thị thông báo
        const message = index > -1 ? 'Đã xóa khỏi yêu thích' : 'Đã thêm vào yêu thích';
        showToast(message);
    };
    
    // Hiển thị toast
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white';
        toast.style.backgroundColor = '#10b981';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 1500);
    }
    
    // Filter functions
    function applyFilters() {
        const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked')).map(cb => cb.value);
        const selectedBrands = Array.from(document.querySelectorAll('.brand-filter:checked')).map(cb => cb.value);
        const minPrice = parseFloat(document.getElementById('min-price').value) || 0;
        const maxPrice = parseFloat(document.getElementById('max-price').value) || Infinity;
        
        const productCards = document.querySelectorAll('.product-card.show');
        let visibleCount = 0;
        
        productCards.forEach(card => {
            const categoryId = card.dataset.category;
            const brandId = card.dataset.brand;
            const priceElement = card.querySelector('.text-lg.font-bold');
            const price = parseFloat(priceElement.textContent.replace(/[^\d]/g, '')) || 0;
            
            let show = true;
            
            // Filter by category
            if (selectedCategories.length > 0 && !selectedCategories.includes(categoryId)) {
                show = false;
            }
            
            // Filter by brand
            if (selectedBrands.length > 0 && !selectedBrands.includes(brandId)) {
                show = false;
            }
            
            // Filter by price
            if (price < minPrice || price > maxPrice) {
                show = false;
            }
            
            if (show) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide no results
        const noResults = document.getElementById('no-results');
        if (visibleCount === 0) {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    }
    
    // Sort products
    function sortProducts() {
        const sortValue = document.getElementById('sort-select').value;
        const productsGrid = document.getElementById('products-grid');
        const productCards = Array.from(document.querySelectorAll('.product-card.show'));
        
        productCards.sort((a, b) => {
            const aId = parseInt(a.dataset.productId);
            const bId = parseInt(b.dataset.productId);
            const aPrice = parseFloat(a.querySelector('.text-lg.font-bold').textContent.replace(/[^\d]/g, '')) || 0;
            const bPrice = parseFloat(b.querySelector('.text-lg.font-bold').textContent.replace(/[^\d]/g, '')) || 0;
            const aName = a.querySelector('h3').textContent;
            const bName = b.querySelector('h3').textContent;
            
            switch (sortValue) {
                case 'newest':
                    return bId - aId;
                case 'oldest':
                    return aId - bId;
                case 'price-low':
                    return aPrice - bPrice;
                case 'price-high':
                    return bPrice - aPrice;
                case 'name-asc':
                    return aName.localeCompare(bName);
                case 'name-desc':
                    return bName.localeCompare(aName);
                default:
                    return 0;
            }
        });
        
        // Reorder in DOM
        productCards.forEach(card => productsGrid.appendChild(card));
    }
    
    // Event listeners
    document.querySelectorAll('.category-filter, .brand-filter').forEach(filter => {
        filter.addEventListener('change', applyFilters);
    });
    
    document.getElementById('min-price').addEventListener('input', applyFilters);
    document.getElementById('max-price').addEventListener('input', applyFilters);
    
    document.getElementById('sort-select').addEventListener('change', sortProducts);
    
    document.getElementById('clear-filters').addEventListener('click', function() {
        document.querySelectorAll('.category-filter, .brand-filter').forEach(cb => cb.checked = false);
        document.getElementById('min-price').value = '';
        document.getElementById('max-price').value = '';
        document.getElementById('sort-select').value = 'newest';
        applyFilters();
    });
    
    // Initialize
    loadFavorites();
    filterFavoriteProducts();
});
</script>
@endpush
