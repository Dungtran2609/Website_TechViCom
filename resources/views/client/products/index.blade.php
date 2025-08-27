@extends('client.layouts.app')

@section('title', 'Danh sách sản phẩm - Techvicom')

@push('styles')
    {{-- Tailwind theme --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-primary': '#ff6c2f',
                        'custom-primary-dark': '#e55a28',
                    }
                }
            }
        }
    </script>

    {{-- Swiper CSS cho banner & danh mục --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper@10/swiper-bundle.min.css" />

    <style>
        .category-btn {
            @apply px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:border-[#ff6c2f] hover:text-[#ff6c2f] transition-all duration-200 cursor-pointer;
        }

        .category-btn.active {
            @apply bg-[#ff6c2f] text-white border-[#ff6c2f];
        }

        .product-card {
            border: 1px solid #eee;
        }

        .product-card:hover {
            transform: translateY(-4px);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Ảnh sản phẩm kiểu FPT */
        .product-image-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 1/1;
            background: #fff;
            overflow: hidden;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            image-rendering: auto;
        }

        /* Tim yêu thích: luôn hiện và giữ trạng thái bằng class is-active */
        .fav-btn {
            position: absolute;
            top: .5rem;
            right: .5rem;
            background: #fff;
            border-radius: 9999px;
            padding: .5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .fav-btn i {
            transition: transform .15s;
        }

        .fav-btn.is-active i {
            color: #ff6c2f !important;
        }

        .fav-btn:not(.is-active) i {
            color: #9ca3af !important;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #fff;
            --swiper-navigation-size: 24px;
        }

        .banner-swiper .swiper-pagination-bullet {
            background: #fff;
            opacity: .7;
        }

        .banner-swiper .swiper-pagination-bullet-active {
            opacity: 1;
        }

        /* Category Filter Styles */
        .category-filter-group {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        
        .category-filter-group:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        
        .toggle-subcategories {
            transition: all 0.2s ease;
            padding: 2px;
            border-radius: 4px;
        }
        
        .toggle-subcategories:hover {
            background-color: #f3f4f6;
            transform: scale(1.1);
        }
        
        .subcategories {
            transition: all 0.3s ease;
        }
        
        .parent-category:indeterminate {
            background-color: #ff6c2f;
            border-color: #ff6c2f;
        }
        
        .category-filter:checked {
            accent-color: #ff6c2f;
        }
        }

        .cat-swiper .swiper-slide {
            width: auto;
        }
    </style>
@endpush

@section('content')

    <!-- Breadcrumb -->
    <nav class="bg-white border-b border-gray-200 py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                @if (isset($currentCategory) && $currentCategory)
                    <span class="text-gray-900 font-medium">{{ $currentCategory->name }}</span>
                @elseif(request('search'))
                    <span class="text-gray-900 font-medium">Tìm kiếm: "{{ request('search') }}"</span>
                @else
                    <span class="text-gray-900 font-medium">Danh sách sản phẩm</span>
                @endif
            </div>
        </div>
    </nav>

    <!-- Page Header với Banner Slider -->
    <section class="bg-gradient-to-r from-[#ff6c2f] to-[#e55a28] text-white pt-6 pb-10">
        <div class="container mx-auto px-4">
            <div class="text-center mb-4">
                @if (isset($currentCategory) && $currentCategory)
                    <h1 class="text-4xl font-bold mb-2">{{ $currentCategory->name }}</h1>
                    <p class="text-lg opacity-90">Khám phá các sản phẩm {{ strtolower($currentCategory->name) }} chất lượng
                        cao</p>
                @elseif(request('search'))
                    <h1 class="text-4xl font-bold mb-2">Kết quả tìm kiếm</h1>
                    <p class="text-lg opacity-90">Tìm kiếm cho: "{{ request('search') }}"</p>
                @else
                    <h1 class="text-4xl font-bold mb-2">Danh sách sản phẩm</h1>
                    <p class="text-lg opacity-90">Khám phá tất cả sản phẩm công nghệ với giá tốt nhất</p>
                @endif
            </div>

            {{-- Banner slide --}}
            
        </div>
    </section>



    <!-- Products Grid -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar Filters -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h3 class="text-lg font-bold mb-6 text-gray-800">Bộ lọc</h3>

                        <!-- Category Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Danh mục</h4>
                            <div class="space-y-2">
                                @foreach($categories->whereNull('parent_id') as $parentCategory)
                                    <div class="category-filter-group">
                                        <div class="flex items-center justify-between">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" value="{{ $parentCategory->slug }}" class="mr-2 category-filter parent-category" data-category-id="{{ $parentCategory->id }}">
                                                <span class="font-medium">{{ $parentCategory->name }}</span>
                                            </label>
                                            @if($parentCategory->children->count() > 0)
                                                <button type="button" class="toggle-subcategories text-gray-400 hover:text-gray-600" data-category-id="{{ $parentCategory->id }}">
                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                </button>
                                            @endif
                                        </div>
                                        
                                        @if($parentCategory->children->count() > 0)
                                            <div class="subcategories ml-4 mt-2 space-y-1" id="subcategories-{{ $parentCategory->id }}" style="display: none;">
                                                @foreach($parentCategory->children as $childCategory)
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="checkbox" value="{{ $childCategory->slug }}" class="mr-2 category-filter child-category" data-parent-id="{{ $parentCategory->id }}" data-category-id="{{ $childCategory->id }}">
                                                        <span class="text-sm">{{ $childCategory->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <!-- Clear Category Filter Button -->
                            <div class="mt-3">
                                <button id="clear-category-filters" class="w-full px-3 py-2 text-sm bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition">
                                    Xóa bộ lọc danh mục
                                </button>
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Khoảng giá</h4>
                            <div class="space-y-2">
                                <label class="flex items-center"><input type="radio" name="price" value=""
                                        class="mr-2 price-filter" checked><span>Tất cả</span></label>
                                <label class="flex items-center"><input type="radio" name="price" value="0-5"
                                        class="mr-2 price-filter"><span>Dưới 5 triệu</span></label>
                                <label class="flex items-center"><input type="radio" name="price" value="5-10"
                                        class="mr-2 price-filter"><span>5 - 10 triệu</span></label>
                                <label class="flex items-center"><input type="radio" name="price" value="10-20"
                                        class="mr-2 price-filter"><span>10 - 20 triệu</span></label>
                                <label class="flex items-center"><input type="radio" name="price" value="20-30"
                                        class="mr-2 price-filter"><span>20 - 30 triệu</span></label>
                                <label class="flex items-center"><input type="radio" name="price" value="30+"
                                        class="mr-2 price-filter"><span>Trên 30 triệu</span></label>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Thương hiệu</h4>
                            <div class="space-y-2">
                                @foreach ($brands as $b)
                                    <label class="flex items-center">
                                        <input type="checkbox" value="{{ $b->slug }}" class="mr-2 brand-filter">
                                        <span>{{ $b->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- RAM Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">RAM</h4>
                            <div class="space-y-2">
                                <label class="flex items-center"><input type="checkbox" value="4"
                                        class="mr-2 ram-filter"><span>4GB</span></label>
                                <label class="flex items-center"><input type="checkbox" value="6"
                                        class="mr-2 ram-filter"><span>6GB</span></label>
                                <label class="flex items-center"><input type="checkbox" value="8"
                                        class="mr-2 ram-filter"><span>8GB</span></label>
                                <label class="flex items-center"><input type="checkbox" value="12"
                                        class="mr-2 ram-filter"><span>12GB</span></label>
                                <label class="flex items-center"><input type="checkbox" value="16"
                                        class="mr-2 ram-filter"><span>16GB</span></label>
                            </div>
                        </div>

                        <!-- Storage Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Bộ nhớ</h4>
                            <div class="space-y-2">
                                <label class="flex items-center"><input type="checkbox" value="64"
                                        class="mr-2 storage-filter"><span>64GB</span></label>
                                <label class="flex items-center"><input type="checkbox" value="128"
                                        class="mr-2 storage-filter"><span>128GB</span></label>
                                <label class="flex items-center"><input type="checkbox" value="256"
                                        class="mr-2 storage-filter"><span>256GB</span></label>
                                <label class="flex items-center"><input type="checkbox" value="512"
                                        class="mr-2 storage-filter"><span>512GB</span></label>
                                <label class="flex items-center"><input type="checkbox" value="1024"
                                        class="mr-2 storage-filter"><span>1TB</span></label>
                            </div>
                        </div>

                        <!-- Rating Filter: 1–5 sao -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Đánh giá</h4>
                            <div class="space-y-2">
                                <label class="flex items-center"><input type="radio" name="rating" value=""
                                        class="mr-2 rating-filter" checked><span>Tất cả</span></label>

                                @for ($r = 1; $r <= 5; $r++)
                                    <label class="flex items-center">
                                        <input type="radio" name="rating" value="{{ $r }}"
                                            class="mr-2 rating-filter">
                                        <div class="flex items-center">
                                            <div class="flex text-yellow-400 text-sm mr-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $r)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span>{{ $r }} sao</span>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        <button id="clear-filters"
                            class="w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition">Xóa bộ
                            lọc</button>
                    </div>
                </div>

                <!-- Products Content -->
                <div class="lg:col-span-3">
                    <!-- Sort -->
                    <div class="flex flex-wrap items-center justify-between mb-6 bg-white p-4 rounded-lg shadow-md">
                        <div class="flex items-center gap-4">
                            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <span class="text-gray-700">Tổng số {{ $products->total() }} sản phẩm</span>
                            @else
                                <span class="text-gray-700">Hiển thị tất cả {{ $products->count() }} sản phẩm</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-gray-700">Sắp xếp:</span>
                            <select
                                class="bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:border-[#ff6c2f]"
                                id="sort-filter">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất
                                </option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng
                                    dần</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá
                                    giảm dần</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div id="products-container">
                        @include('client.products.partials.product-grid', ['products' => $products, 'favoriteProductIds' => $favoriteProductIds])
                    </div>
                    
                    <!-- Show More/Less Buttons -->
                    <div class="mt-8 text-center" id="show-more-container">
                        @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasMorePages())
                            <div class="space-y-3">
                                <button id="show-more-btn" class="bg-[#ff6c2f] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#e55a28] transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Xem thêm sản phẩm
                                </button>
                                @if($products->total() > 12)
                                    <button id="show-all-btn" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-list mr-2"></i>Xem tất cả {{ $products->total() }} sản phẩm
                                    </button>
                                @endif
                            </div>
                        @elseif($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->currentPage() > 1)
                            <button id="show-less-btn" class="bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
                                <i class="fas fa-minus mr-2"></i>Ẩn bớt sản phẩm
                            </button>
                        @elseif(!($products instanceof \Illuminate\Pagination\LengthAwarePaginator))
                            <button id="show-less-btn" class="bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
                                <i class="fas fa-minus mr-2"></i>Ẩn bớt sản phẩm
                            </button>
                        @endif
                        
                        <!-- Info text -->
                        <div class="mt-4 text-sm text-gray-600">
                            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <p>Tổng số {{ $products->total() }} sản phẩm</p>
                                @if($products->hasMorePages())
                                    <p class="mt-1">Đang hiển thị {{ $products->count() }} sản phẩm đầu tiên</p>
                                @endif
                            @else
                                <p>Đang hiển thị tất cả {{ $products->count() }} sản phẩm</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- Swiper JS --}}
    <script src="https://unpkg.com/swiper@10/swiper-bundle.min.js"></script>

    <script>
        // Banner slider
        const bannerSwiper = new Swiper('.banner-swiper', {
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false
            },
            pagination: {
                el: '.banner-swiper .swiper-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.banner-swiper .swiper-button-next',
                prevEl: '.banner-swiper .swiper-button-prev'
            }
        });

        // Category slider
        const catSwiper = new Swiper('.cat-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 12,
            freeMode: true,
            navigation: {
                nextEl: '.cat-swiper .swiper-button-next',
                prevEl: '.cat-swiper .swiper-button-prev'
            },
        });

        document.addEventListener('DOMContentLoaded', function() {
            // --- Category filter toggle functionality ---
            document.querySelectorAll('.toggle-subcategories').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const categoryId = this.dataset.categoryId;
                    const subcategories = document.getElementById(`subcategories-${categoryId}`);
                    const icon = this.querySelector('i');
                    
                    if (subcategories.style.display === 'none') {
                        subcategories.style.display = 'block';
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    } else {
                        subcategories.style.display = 'none';
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    }
                });
            });

            // --- Parent-child category relationship ---
            document.querySelectorAll('.parent-category').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const categoryId = this.dataset.categoryId;
                    const childCheckboxes = document.querySelectorAll(`.child-category[data-parent-id="${categoryId}"]`);
                    
                    childCheckboxes.forEach(child => {
                        child.checked = this.checked;
                    });
                });
            });

            document.querySelectorAll('.child-category').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const parentId = this.dataset.parentId;
                    const parentCheckbox = document.querySelector(`.parent-category[data-category-id="${parentId}"]`);
                    const siblingCheckboxes = document.querySelectorAll(`.child-category[data-parent-id="${parentId}"]`);
                    const checkedSiblings = document.querySelectorAll(`.child-category[data-parent-id="${parentId}"]:checked`);
                    
                    if (checkedSiblings.length === 0) {
                        parentCheckbox.checked = false;
                    } else if (checkedSiblings.length === siblingCheckboxes.length) {
                        parentCheckbox.checked = true;
                    } else {
                        parentCheckbox.indeterminate = true;
                    }
                });
            });

            // --- Auto-check filters from URL ---
            const urlParams = new URLSearchParams(window.location.search);
            
            // Auto-check categories
            if (urlParams.has('category')) {
                const categories = urlParams.get('category').split(',');
                document.querySelectorAll('.category-filter').forEach(cb => {
                    if (categories.includes(cb.value)) {
                        cb.checked = true;
                        
                        // If it's a child category, show its parent's subcategories
                        if (cb.classList.contains('child-category')) {
                            const parentId = cb.dataset.parentId;
                            const subcategories = document.getElementById(`subcategories-${parentId}`);
                            const toggleButton = document.querySelector(`.toggle-subcategories[data-category-id="${parentId}"]`);
                            const icon = toggleButton?.querySelector('i');
                            
                            if (subcategories) {
                                subcategories.style.display = 'block';
                                if (icon) {
                                    icon.classList.remove('fa-chevron-down');
                                    icon.classList.add('fa-chevron-up');
                                }
                            }
                        }
                    }
                });
                
                // Update parent checkboxes based on children
                document.querySelectorAll('.parent-category').forEach(parent => {
                    const categoryId = parent.dataset.categoryId;
                    const childCheckboxes = document.querySelectorAll(`.child-category[data-parent-id="${categoryId}"]`);
                    const checkedChildren = document.querySelectorAll(`.child-category[data-parent-id="${categoryId}"]:checked`);
                    
                    if (checkedChildren.length === childCheckboxes.length && childCheckboxes.length > 0) {
                        parent.checked = true;
                    } else if (checkedChildren.length > 0) {
                        parent.indeterminate = true;
                    }
                });
            }
            
            if (urlParams.has('brands')) {
                const brands = urlParams.get('brands').split(',');
                document.querySelectorAll('.brand-filter').forEach(cb => {
                    if (brands.includes(cb.value)) cb.checked = true;
                });
            }
            if (urlParams.has('ram')) {
                const rams = urlParams.get('ram').split(',');
                document.querySelectorAll('.ram-filter').forEach(cb => {
                    if (rams.includes(cb.value)) cb.checked = true;
                });
            }
            if (urlParams.has('storage')) {
                const storages = urlParams.get('storage').split(',');
                document.querySelectorAll('.storage-filter').forEach(cb => {
                    if (storages.includes(cb.value)) cb.checked = true;
                });
            }
            if (urlParams.has('rating')) {
                const rating = urlParams.get('rating');
                document.querySelectorAll('.rating-filter').forEach(cb => {
                    cb.checked = (cb.value === rating);
                });
            }
            if (urlParams.has('min_price') || urlParams.has('max_price')) {
                const min = urlParams.get('min_price') ? parseInt(urlParams.get('min_price')) : null;
                const max = urlParams.get('max_price') ? parseInt(urlParams.get('max_price')) : null;
                document.querySelectorAll('input[name="price"]').forEach(cb => {
                    let val = cb.value;
                    if (!val && !min && !max) cb.checked = true;
                    else if (val && val.includes('-')) {
                        const [vmin, vmax] = val.split('-');
                        if (min === parseInt(vmin) * 1000000 && max === parseInt(vmax) * 1000000) cb
                            .checked = true;
                    } else if (val && val.endsWith('+')) {
                        if (min === parseInt(val) * 1000000 && !max) cb.checked = true;
                    }
                });
            }



            // Parent-child category relationship
            document.querySelectorAll('.parent-category').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const categoryId = this.dataset.categoryId;
                    const childCheckboxes = document.querySelectorAll(`.child-category[data-parent-id="${categoryId}"]`);
                    
                    childCheckboxes.forEach(child => {
                        child.checked = this.checked;
                    });
                });
            });

            document.querySelectorAll('.child-category').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const parentId = this.dataset.parentId;
                    const parentCheckbox = document.querySelector(`.parent-category[data-category-id="${parentId}"]`);
                    const siblingCheckboxes = document.querySelectorAll(`.child-category[data-parent-id="${parentId}"]`);
                    const checkedSiblings = document.querySelectorAll(`.child-category[data-parent-id="${parentId}"]:checked`);
                    
                    if (checkedSiblings.length === 0) {
                        parentCheckbox.checked = false;
                    } else if (checkedSiblings.length === siblingCheckboxes.length) {
                        parentCheckbox.checked = true;
                    } else {
                        parentCheckbox.indeterminate = true;
                    }
                });
            });

            // Clear category filters button
            const clearCategoryBtn = document.getElementById('clear-category-filters');
            if (clearCategoryBtn) {
                clearCategoryBtn.addEventListener('click', function() {
                    document.querySelectorAll('.category-filter').forEach(cb => {
                        cb.checked = false;
                    });
                    applyFilters();
                });
            }

            // Sort change
            const sortFilter = document.getElementById('sort-filter');
            if (sortFilter) {
                sortFilter.addEventListener('change', function() {
                    const url = new URL(window.location);
                    if (this.value && this.value !== 'latest') url.searchParams.set('sort', this.value);
                    else url.searchParams.delete('sort');
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            }

            // Filters change
            document.querySelectorAll('.price-filter, .brand-filter, .ram-filter, .storage-filter, .rating-filter, .category-filter')
                .forEach(filter => filter.addEventListener('change', applyFilters));

            // Clear filters
            const clearFiltersBtn = document.getElementById('clear-filters');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    document.querySelectorAll('input[name="price"]').forEach(input => {
                        input.checked = input.value === '';
                    });
                    document.querySelectorAll('.brand-filter, .ram-filter, .storage-filter').forEach(
                        input => {
                            input.checked = false;
                        });
                    document.querySelectorAll('input[name="rating"]').forEach(input => {
                        input.checked = input.value === '';
                    });
                    applyFilters();
                });
            }

            function applyFilters() {
                const url = new URL(window.location);

                // Category (parent and child)
                const categoryFilters = [];
                document.querySelectorAll('.category-filter:checked').forEach(cb => {
                    categoryFilters.push(cb.value);
                });
                if (categoryFilters.length > 0) {
                    url.searchParams.set('category', categoryFilters.join(','));
                } else {
                    url.searchParams.delete('category');
                }

                // Price (triệu -> VND)
                const priceFilter = document.querySelector('input[name="price"]:checked');
                if (priceFilter && priceFilter.value) {
                    const priceRange = priceFilter.value.split('-');
                    if (priceRange.length === 2) {
                        url.searchParams.set('min_price', priceRange[0] * 1000000);
                        if (priceRange[1] !== '+') url.searchParams.set('max_price', priceRange[1] * 1000000);
                        else url.searchParams.delete('max_price');
                    }
                } else {
                    url.searchParams.delete('min_price');
                    url.searchParams.delete('max_price');
                }

                // Brand
                const brandFilters = Array.from(document.querySelectorAll('.brand-filter:checked')).map(cb => cb
                    .value);
                if (brandFilters.length > 0) url.searchParams.set('brands', brandFilters.join(','));
                else url.searchParams.delete('brands');

                // RAM
                const ramFilters = Array.from(document.querySelectorAll('.ram-filter:checked')).map(cb => cb.value);
                if (ramFilters.length > 0) url.searchParams.set('ram', ramFilters.join(','));
                else url.searchParams.delete('ram');

                // Storage
                const storageFilters = Array.from(document.querySelectorAll('.storage-filter:checked')).map(cb => cb
                    .value);
                if (storageFilters.length > 0) url.searchParams.set('storage', storageFilters.join(','));
                else url.searchParams.delete('storage');

                // Rating: 1..5
                const ratingFilter = document.querySelector('input[name="rating"]:checked');
                if (ratingFilter && ratingFilter.value) url.searchParams.set('rating', ratingFilter.value);
                else url.searchParams.delete('rating');

                url.searchParams.delete('page');
                window.location.href = url.toString();
            }

            // Yêu thích: sử dụng API thay vì localStorage
            const favBtns = document.querySelectorAll('.favorite-once');

            function setHeart(btn, isFavorite) {
                const icon = btn.querySelector('i');
                btn.classList.toggle('is-active', isFavorite);
                if (icon) {
                    icon.classList.toggle('fas', isFavorite);
                    icon.classList.toggle('far', !isFavorite);
                }
            }

            // Khởi tạo trạng thái yêu thích từ server
            favBtns.forEach(btn => {
                const productId = parseInt(btn.dataset.productId);
                const icon = btn.querySelector('i');
                const isFavorite = icon.classList.contains('fas');
                setHeart(btn, isFavorite);
            });

            favBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = parseInt(this.dataset.productId);
                    
                    // Hiển thị loading
                    const originalIcon = this.querySelector('i').className;
                    this.querySelector('i').className = 'fas fa-spinner fa-spin';
                    this.disabled = true;
                    
                    fetch('{{ route("accounts.favorites.toggle") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);
                        
                        // Kiểm tra content-type
                        const contentType = response.headers.get('content-type');
                        console.log('Content-Type:', contentType);
                        
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // Nếu không phải JSON, có thể là HTML redirect
                            console.log('Non-JSON response detected, likely HTML redirect');
                            throw new Error('Non-JSON response');
                        }
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            setHeart(this, data.is_favorite);
                            toast(data.message);
                        } else if (data.redirect) {
                            // Nếu server yêu cầu redirect (user chưa đăng nhập)
                            console.log('Redirecting to:', data.redirect);
                            toast(data.message || 'Vui lòng đăng nhập để thêm vào yêu thích');
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        } else {
                            // Khôi phục trạng thái ban đầu nếu có lỗi
                            this.querySelector('i').className = originalIcon;
                            toast(data.message || 'Có lỗi xảy ra, vui lòng thử lại');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Nếu có lỗi, có thể là do chưa đăng nhập
                        this.querySelector('i').className = originalIcon;
                        toast('Vui lòng đăng nhập để thêm vào yêu thích');
                        setTimeout(() => {
                            openAuthModal();
                        }, 1500);
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
                });
            });

            function toast(msg) {
                const node = document.createElement('div');
                node.className = 'fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white';
                node.style.backgroundColor = '#10b981';
                node.textContent = msg;
                document.body.appendChild(node);
                setTimeout(() => node.remove(), 1500);
            }
        });

        function goToProductDetail(productId) {
            window.location.href = `/products/${productId}`;
        }

        // AJAX Filtering
        let filterTimeout;
        const productsContainer = document.getElementById('products-container');
        const loadingHtml = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-600">Đang tải sản phẩm...</span>
            </div>
        `;

        function applyFilters() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                const formData = new FormData();
                
                // Category filters
                const selectedCategories = [];
                document.querySelectorAll('.category-filter:checked').forEach(cb => {
                    selectedCategories.push(cb.value);
                });
                if (selectedCategories.length > 0) {
                    formData.append('category', selectedCategories.join(','));
                }

                // Brand filters
                const selectedBrands = [];
                document.querySelectorAll('.brand-filter:checked').forEach(cb => {
                    selectedBrands.push(cb.value);
                });
                if (selectedBrands.length > 0) {
                    formData.append('brands', selectedBrands.join(','));
                }

                // RAM filters
                const selectedRams = [];
                document.querySelectorAll('.ram-filter:checked').forEach(cb => {
                    selectedRams.push(cb.value);
                });
                if (selectedRams.length > 0) {
                    formData.append('ram', selectedRams.join(','));
                }

                // Storage filters
                const selectedStorages = [];
                document.querySelectorAll('.storage-filter:checked').forEach(cb => {
                    selectedStorages.push(cb.value);
                });
                if (selectedStorages.length > 0) {
                    formData.append('storage', selectedStorages.join(','));
                }

                // Rating filter
                const selectedRating = document.querySelector('.rating-filter:checked');
                if (selectedRating) {
                    formData.append('rating', selectedRating.value);
                }

                // Price range
                const minPrice = document.getElementById('min-price')?.value;
                const maxPrice = document.getElementById('max-price')?.value;
                if (minPrice) formData.append('min_price', minPrice);
                if (maxPrice) formData.append('max_price', maxPrice);

                // Sort
                const sortSelect = document.getElementById('sort-filter');
                if (sortSelect) {
                    formData.append('sort', sortSelect.value);
                }

                // Search
                const searchInput = document.getElementById('search-input');
                if (searchInput && searchInput.value.trim()) {
                    formData.append('search', searchInput.value.trim());
                }

                // Show loading
                productsContainer.innerHTML = loadingHtml;

                // Send AJAX request
                fetch('{{ route("products.filter") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        productsContainer.innerHTML = data.html;
                        
                        // Reinitialize favorite buttons
                        initializeFavoriteButtons();
                        
                        // Update URL without page reload
                        updateURL(formData);
                    } else {
                        productsContainer.innerHTML = `
                            <div class="text-center py-12">
                                <div class="text-red-500 text-lg mb-4">
                                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                                    <p>Có lỗi xảy ra khi tải sản phẩm.</p>
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    productsContainer.innerHTML = `
                        <div class="text-center py-12">
                            <div class="text-red-500 text-lg mb-4">
                                <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                                <p>Có lỗi xảy ra khi tải sản phẩm.</p>
                            </div>
                        </div>
                    `;
                });
            }, 300); // Debounce 300ms
        }

        function updateURL(formData) {
            const params = new URLSearchParams();
            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            const newURL = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.pushState({}, '', newURL);
        }

        function initializeFavoriteButtons() {
            const favBtns = document.querySelectorAll('.favorite-btn');
            favBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = parseInt(this.dataset.productId);
                    
                    // Toggle heart icon
                    const icon = this.querySelector('i');
                    const isFavorite = icon.classList.contains('text-red-500');
                    
                    // Show loading
                    icon.className = 'fas fa-spinner fa-spin';
                    this.disabled = true;
                    
                    fetch('{{ route("accounts.favorites.toggle") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            icon.className = data.is_favorite ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-300';
                        } else {
                            // Restore original state
                            icon.className = isFavorite ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-300';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Restore original state
                        icon.className = isFavorite ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-300';
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
                });
            });
        }

        // Add event listeners for filters
        document.addEventListener('DOMContentLoaded', function() {
            // Category filters
            document.querySelectorAll('.category-filter').forEach(cb => {
                cb.addEventListener('change', applyFilters);
            });

            // Brand filters
            document.querySelectorAll('.brand-filter').forEach(cb => {
                cb.addEventListener('change', applyFilters);
            });

            // RAM filters
            document.querySelectorAll('.ram-filter').forEach(cb => {
                cb.addEventListener('change', applyFilters);
            });

            // Storage filters
            document.querySelectorAll('.storage-filter').forEach(cb => {
                cb.addEventListener('change', applyFilters);
            });

            // Rating filters
            document.querySelectorAll('.rating-filter').forEach(cb => {
                cb.addEventListener('change', applyFilters);
            });

            // Price range
            const minPriceInput = document.getElementById('min-price');
            const maxPriceInput = document.getElementById('max-price');
            if (minPriceInput) {
                minPriceInput.addEventListener('input', applyFilters);
            }
            if (maxPriceInput) {
                maxPriceInput.addEventListener('input', applyFilters);
            }

            // Sort
            const sortSelect = document.getElementById('sort-filter');
            if (sortSelect) {
                sortSelect.addEventListener('change', applyFilters);
            }

            // Search
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }

            // Initialize favorite buttons
            initializeFavoriteButtons();
            
            // Show More/Less functionality
            const showMoreBtn = document.getElementById('show-more-btn');
            const showLessBtn = document.getElementById('show-less-btn');
            const showAllBtn = document.getElementById('show-all-btn');
            
            if (showMoreBtn) {
                showMoreBtn.addEventListener('click', function() {
                    showAllProducts();
                });
            }
            
            if (showLessBtn) {
                showLessBtn.addEventListener('click', function() {
                    showLessProducts();
                });
            }
            
            if (showAllBtn) {
                showAllBtn.addEventListener('click', function() {
                    showAllProducts();
                });
            }
            
            // Lưu trạng thái show_all vào localStorage để cải thiện UX
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('show_all') === 'true') {
                localStorage.setItem('products_show_all', 'true');
            } else {
                localStorage.removeItem('products_show_all');
            }
        });
        
        // Function to show all products
        function showAllProducts() {
            const btn = document.getElementById('show-more-btn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang tải...';
            }
            
            const url = new URL(window.location);
            url.searchParams.set('show_all', 'true');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
        
        // Function to show less products (back to pagination)
        function showLessProducts() {
            const btn = document.getElementById('show-less-btn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang tải...';
            }
            
            const url = new URL(window.location);
            url.searchParams.delete('show_all');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    </script>
@endpush