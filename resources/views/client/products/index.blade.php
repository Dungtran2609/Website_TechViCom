@extends('client.layouts.app')

@section('title', 'Danh sách sản phẩm - Techvicom')

@push('styles')
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
<style>
    .category-btn {
        @apply px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:border-[#ff6c2f] hover:text-[#ff6c2f] transition-all duration-200 cursor-pointer;
    }
    
    .category-btn.active {
        @apply bg-[#ff6c2f] text-white border-[#ff6c2f];
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
</style>
@endpush

@section('content')

    <!-- Breadcrumb -->
    <nav class="bg-white border-b border-gray-200 py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                @if(isset($currentCategory) && $currentCategory)
                    <span class="text-gray-900 font-medium">{{ $currentCategory->name }}</span>
                @elseif(request('search'))
                    <span class="text-gray-900 font-medium">Tìm kiếm: "{{ request('search') }}"</span>
                @else
                    <span class="text-gray-900 font-medium">Danh sách sản phẩm</span>
                @endif
            </div>
        </div>
    </nav>

    <!-- Page Header with Search -->
    <section class="bg-gradient-to-r from-[#ff6c2f] to-[#e55a28] text-white py-8">
        <div class="container mx-auto px-4">
            <div class="text-center mb-6">
                @if(isset($currentCategory) && $currentCategory)
                    <h1 class="text-4xl font-bold mb-4">{{ $currentCategory->name }}</h1>
                    <p class="text-xl">Khám phá các sản phẩm {{ strtolower($currentCategory->name) }} chất lượng cao</p>
                @elseif(request('search'))
                    <h1 class="text-4xl font-bold mb-4">Kết quả tìm kiếm</h1>
                    <p class="text-xl">Tìm kiếm cho: "{{ request('search') }}"</p>
                @else
                    <h1 class="text-4xl font-bold mb-4">Danh sách sản phẩm</h1>
                    <p class="text-xl">Khám phá tất cả sản phẩm công nghệ với giá tốt nhất</p>
                @endif
            </div>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" 
                           id="search-input" 
                           placeholder="Tìm kiếm sản phẩm..." 
                           value="{{ request('search') }}"
                           class="w-full px-6 py-4 rounded-full text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                    <button id="search-btn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#ff6c2f] text-white p-3 rounded-full hover:bg-[#e55a28] transition">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Selection -->
    <section class="bg-white py-4 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center gap-4">
                <h3 class="font-semibold text-gray-700">Danh mục:</h3>
                <div class="flex flex-wrap gap-2">
                    <button class="category-btn {{ !request('category') ? 'active' : '' }}" data-category="">Tất cả</button>
                    @foreach($categories as $category)
                        <button class="category-btn {{ request('category') == $category->slug ? 'active' : '' }}" data-category="{{ $category->slug }}">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>
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
                        
                        <!-- Price Range Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Khoảng giá</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="price" value="" class="mr-2 price-filter" checked>
                                    <span>Tất cả</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price" value="0-5" class="mr-2 price-filter">
                                    <span>Dưới 5 triệu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price" value="5-10" class="mr-2 price-filter">
                                    <span>5 - 10 triệu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price" value="10-20" class="mr-2 price-filter">
                                    <span>10 - 20 triệu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price" value="20-30" class="mr-2 price-filter">
                                    <span>20 - 30 triệu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price" value="30+" class="mr-2 price-filter">
                                    <span>Trên 30 triệu</span>
                                </label>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Thương hiệu</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" value="apple" class="mr-2 brand-filter">
                                    <span>Apple</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="samsung" class="mr-2 brand-filter">
                                    <span>Samsung</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="xiaomi" class="mr-2 brand-filter">
                                    <span>Xiaomi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="oppo" class="mr-2 brand-filter">
                                    <span>OPPO</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="vivo" class="mr-2 brand-filter">
                                    <span>Vivo</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="huawei" class="mr-2 brand-filter">
                                    <span>Huawei</span>
                                </label>
                            </div>
                        </div>

                        <!-- RAM Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">RAM</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" value="4" class="mr-2 ram-filter">
                                    <span>4GB</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="6" class="mr-2 ram-filter">
                                    <span>6GB</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="8" class="mr-2 ram-filter">
                                    <span>8GB</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="12" class="mr-2 ram-filter">
                                    <span>12GB</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="16" class="mr-2 ram-filter">
                                    <span>16GB</span>
                                </label>
                            </div>
                        </div>

                        <!-- Storage Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Bộ nhớ</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" value="64" class="mr-2 storage-filter">
                                    <span>64GB</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="128" class="mr-2 storage-filter">
                                    <span>128GB</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="256" class="mr-2 storage-filter">
                                    <span>256GB</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="512" class="mr-2 storage-filter">
                                    <span>512GB</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="1024" class="mr-2 storage-filter">
                                    <span>1TB</span>
                                </label>
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Đánh giá</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="rating" value="" class="mr-2 rating-filter" checked>
                                    <span>Tất cả</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="rating" value="5" class="mr-2 rating-filter">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 text-sm mr-2">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        </div>
                                        <span>5 sao</span>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="rating" value="4" class="mr-2 rating-filter">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 text-sm mr-2">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                                        </div>
                                        <span>4 sao trở lên</span>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="rating" value="3" class="mr-2 rating-filter">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 text-sm mr-2">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                                        </div>
                                        <span>3 sao trở lên</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        <button id="clear-filters" class="w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition">
                            Xóa bộ lọc
                        </button>
                    </div>
                </div>

                <!-- Products Content -->
                <div class="lg:col-span-3">
                    <!-- Sort and View Options -->
                    <div class="flex flex-wrap items-center justify-between mb-6 bg-white p-4 rounded-lg shadow-md">
                        <div class="flex items-center gap-4">
                            <span class="text-gray-700">Hiển thị {{ $products->count() }} trong tổng số {{ $products->total() }} sản phẩm</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-gray-700">Sắp xếp:</span>
                            <select class="bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:border-[#ff6c2f]" id="sort-filter">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="products-grid">
                        @forelse($products as $product)
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group product-card" onclick="goToProductDetail({{ $product->id }})">
                                <div class="relative">
                                    @if($product->productAllImages->isNotEmpty())
                                        <img src="{{ asset('uploads/products/' . $product->productAllImages->first()->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-48 object-cover rounded-t-lg" 
                                             onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                    @else
                                        <img src="{{ asset('client_css/images/placeholder.svg') }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-48 object-cover rounded-t-lg">
                                    @endif
                                    
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        @php
                                            $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                        @endphp
                                        <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">
                                            -{{ $discount }}%
                                        </div>
                                    @endif
                                    
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                        <button onclick="event.stopPropagation()" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                            <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                                    <div class="flex items-center mb-2">
                                        <div class="flex text-yellow-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= ($product->rating ?? 4))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-gray-500 text-sm ml-2">({{ $product->productComments->count() }})</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <span class="text-lg font-bold text-[#ff6c2f]">{{ number_format($product->sale_price) }}₫</span>
                                                <span class="text-sm text-gray-500 line-through ml-2">{{ number_format($product->price) }}₫</span>
                                            @else
                                                <span class="text-lg font-bold text-[#ff6c2f]">{{ number_format($product->price) }}₫</span>
                                            @endif
                                        </div>
                                        <button onclick="event.stopPropagation(); addToCartStatic({{ $product->id }}, '{{ $product->name }}', {{ $product->sale_price ?? $product->price }}, '{{ $product->productAllImages->isNotEmpty() ? asset('uploads/products/' . $product->productAllImages->first()->image_path) : asset('client_css/images/placeholder.svg') }}')" 
                                                class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-600 mb-2">Không tìm thấy sản phẩm</h3>
                                <p class="text-gray-500">Hãy thử tìm kiếm với từ khóa khác hoặc thay đổi bộ lọc</p>
                            </div>
                        @endforelse
                <!-- Static Phone Products -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group" onclick="goToProductDetail(1)">
                    <div class="relative">
                        <img src="{{ asset('uploads/products/iphone-15-pro-max.jpg') }}" alt="iPhone 15 Pro Max" class="w-full h-48 object-cover rounded-t-lg" onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                        <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">-8%</div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button onclick="event.stopPropagation()" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">iPhone 15 Pro Max 256GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(156)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">34.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">37.990.000₫</span>
                            </div>
                            <button onclick="event.stopPropagation(); addToCartStatic(1, 'iPhone 15 Pro Max 256GB', 34990000, '{{ asset('uploads/products/iphone-15-pro-max.jpg') }}')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group" onclick="goToProductDetail(2)">
                    <div class="relative">
                        <img src="{{ asset('uploads/products/samsung-s24-ultra.jpg') }}" alt="Samsung Galaxy S24 Ultra" class="w-full h-48 object-cover rounded-t-lg" onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                        <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">-8%</div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button onclick="event.stopPropagation()" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Samsung Galaxy S24 Ultra 512GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(89)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">33.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">36.990.000₫</span>
                            </div>
                            <button onclick="event.stopPropagation(); addToCartStatic(2, 'Samsung Galaxy S24 Ultra 512GB', 33990000, '{{ asset('uploads/products/samsung-s24-ultra.jpg') }}')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group" onclick="goToProductDetail(9)">
                    <div class="relative">
                        <img src="{{ asset('uploads/products/iphone-14-pro.jpg') }}" alt="iPhone 14 Pro" class="w-full h-48 object-cover rounded-t-lg" onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                        <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">-15%</div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button onclick="event.stopPropagation()" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">iPhone 14 Pro 128GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(203)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">25.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">30.990.000₫</span>
                            </div>
                            <button onclick="event.stopPropagation(); addToCartStatic(9, 'iPhone 14 Pro 128GB', 25990000, '{{ asset('uploads/products/iphone-14-pro.jpg') }}')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group" onclick="goToProductDetail(10)">
                    <div class="relative">
                        <img src="{{ asset('uploads/products/xiaomi-13-pro.jpg') }}" alt="Xiaomi 13 Pro" class="w-full h-48 object-cover rounded-t-lg" onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                        <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">-20%</div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button onclick="event.stopPropagation()" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Xiaomi 13 Pro 256GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(178)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">19.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">24.990.000₫</span>
                            </div>
                            <button onclick="event.stopPropagation(); addToCartStatic(10, 'Xiaomi 13 Pro 256GB', 19990000, '{{ asset('uploads/products/xiaomi-13-pro.jpg') }}')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group">
                    <div class="relative">
                        <img src="{{ asset('uploads/products/') }}samsung-galaxy-a54.jpg" alt="Samsung Galaxy A54" class="w-full h-48 object-cover rounded-t-lg">
                        <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-sm font-bold">HOT</div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Samsung Galaxy A54 5G 128GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(245)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">8.990.000₫</span>
                            </div>
                            <button onclick="addToCartStatic(11, 'Samsung Galaxy A54 5G 128GB', 8990000, '{{ asset('uploads/products/') }}samsung-galaxy-a54.jpg')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group">
                    <div class="relative">
                        <img src="{{ asset('uploads/products/') }}oppo-find-x6.jpg" alt="OPPO Find X6" class="w-full h-48 object-cover rounded-t-lg">
                        <div class="absolute top-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-sm font-bold">NEW</div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">OPPO Find X6 Pro 256GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(134)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">26.990.000₫</span>
                            </div>
                            <button onclick="addToCartStatic(12, 'OPPO Find X6 Pro 256GB', 26990000, '{{ asset('uploads/products/') }}oppo-find-x6.jpg')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group">
                    <div class="relative">
                        <img src="{{ asset('uploads/products/') }}vivo-v29.jpg" alt="Vivo V29" class="w-full h-48 object-cover rounded-t-lg">
                        <div class="absolute top-2 left-2 bg-purple-600 text-white px-2 py-1 rounded text-sm font-bold">-12%</div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Vivo V29 5G 256GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(167)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">12.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">14.990.000₫</span>
                            </div>
                            <button onclick="addToCartStatic(13, 'Vivo V29 5G 256GB', 12990000, '{{ asset('uploads/products/') }}vivo-v29.jpg')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group">
                    <div class="relative">
                        <img src="{{ asset('uploads/products/') }}realme-gt3.jpg" alt="Realme GT3" class="w-full h-48 object-cover rounded-t-lg">
                        <div class="absolute top-2 left-2 bg-orange-600 text-white px-2 py-1 rounded text-sm font-bold">-25%</div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Realme GT3 240W 256GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(156)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">14.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">19.990.000₫</span>
                            </div>
                            <button onclick="addToCartStatic(14, 'Realme GT3 240W 256GB', 14990000, '{{ asset('uploads/products/') }}realme-gt3.jpg')" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Brands -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Thương hiệu nổi bật</h2>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-6">
                <div class="text-center group cursor-pointer">
                    <div class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition group-hover:scale-105">
                        <img src="{{ asset('uploads/products/') }}brands/apple.png" alt="Apple" class="mx-auto h-12" 
                             onerror="this.onerror=null; this.src='{{ asset('uploads/products/') }}placeholder.svg'">
                    </div>
                </div>
                <div class="text-center group cursor-pointer">
                    <div class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition group-hover:scale-105">
                        <img src="{{ asset('uploads/products/') }}brands/samsung.png" alt="Samsung" class="mx-auto h-12"
                             onerror="this.onerror=null; this.src='{{ asset('uploads/products/') }}placeholder.svg'">
                    </div>
                </div>
                <div class="text-center group cursor-pointer">
                    <div class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition group-hover:scale-105">
                        <img src="{{ asset('uploads/products/') }}brands/xiaomi.png" alt="Xiaomi" class="mx-auto h-12"
                             onerror="this.onerror=null; this.src='{{ asset('uploads/products/') }}placeholder.svg'">
                    </div>
                </div>
                <div class="text-center group cursor-pointer">
                    <div class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition group-hover:scale-105">
                        <img src="{{ asset('uploads/products/') }}brands/oppo.png" alt="Oppo" class="mx-auto h-12"
                             onerror="this.onerror=null; this.src='{{ asset('uploads/products/') }}placeholder.svg'">
                    </div>
                </div>
                <div class="text-center group cursor-pointer">
                    <div class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition group-hover:scale-105">
                        <img src="{{ asset('admin_css/images/brands/vivo.png') }}" alt="Vivo" class="mx-auto h-12"
                             onerror="this.onerror=null; this.src='{{ asset('admin_css/images/placeholder.svg') }}'">
                    </div>
                </div>
                <div class="text-center group cursor-pointer">
                    <div class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition group-hover:scale-105">
                        <img src="{{ asset('admin_css/images/brands/huawei.png') }}" alt="Huawei" class="mx-auto h-12"
                             onerror="this.onerror=null; this.src='{{ asset('admin_css/images/placeholder.svg') }}'">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category buttons functionality
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.dataset.category;
                const url = new URL(window.location);
                
                if (category) {
                    url.searchParams.set('category', category);
                } else {
                    url.searchParams.delete('category');
                }
                
                // Reset to first page
                url.searchParams.delete('page');
                
                window.location.href = url.toString();
            });
        });

        // Search functionality
        const searchInput = document.getElementById('search-input');
        const searchBtn = document.getElementById('search-btn');
        
        function performSearch() {
            const searchTerm = searchInput.value.trim();
            const url = new URL(window.location);
            
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            
            // Reset to first page and clear category
            url.searchParams.delete('page');
            url.searchParams.delete('category');
            
            window.location.href = url.toString();
        }

        if (searchBtn) {
            searchBtn.addEventListener('click', performSearch);
        }

        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }

        // Sort functionality
        const sortFilter = document.getElementById('sort-filter');
        if (sortFilter) {
            sortFilter.addEventListener('change', function() {
                const url = new URL(window.location);
                
                if (this.value && this.value !== 'latest') {
                    url.searchParams.set('sort', this.value);
                } else {
                    url.searchParams.delete('sort');
                }
                
                // Reset to first page
                url.searchParams.delete('page');
                
                window.location.href = url.toString();
            });
        }

        // Filter functionality for sidebar filters
        document.querySelectorAll('.price-filter, .brand-filter, .ram-filter, .storage-filter, .rating-filter').forEach(filter => {
            filter.addEventListener('change', function() {
                applyFilters();
            });
        });

        // Clear filters functionality
        const clearFiltersBtn = document.getElementById('clear-filters');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                // Reset all filters
                document.querySelectorAll('input[name="price"]').forEach(input => {
                    input.checked = input.value === '';
                });
                document.querySelectorAll('.brand-filter, .ram-filter, .storage-filter').forEach(input => {
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
            
            // Price filter
            const priceFilter = document.querySelector('input[name="price"]:checked');
            if (priceFilter && priceFilter.value) {
                const priceRange = priceFilter.value.split('-');
                if (priceRange.length === 2) {
                    url.searchParams.set('min_price', priceRange[0] * 1000000);
                    if (priceRange[1] !== '+') {
                        url.searchParams.set('max_price', priceRange[1] * 1000000);
                    } else {
                        url.searchParams.delete('max_price');
                    }
                }
            } else {
                url.searchParams.delete('min_price');
                url.searchParams.delete('max_price');
            }

            // Brand filter
            const brandFilters = Array.from(document.querySelectorAll('.brand-filter:checked')).map(cb => cb.value);
            if (brandFilters.length > 0) {
                url.searchParams.set('brands', brandFilters.join(','));
            } else {
                url.searchParams.delete('brands');
            }

            // Reset to first page
            url.searchParams.delete('page');
            
            window.location.href = url.toString();
        }
    });

    // Static add to cart function
    function addToCartStatic(productId, name, price, image) {
        // Use the global addToCart function from header
        if (typeof addToCart === 'function') {
            addToCart(productId, null, 1);
        } else {
            // Fallback cart functionality
            const cartItem = {
                id: productId,
                name: name,
                price: price,
                image: image,
                quantity: 1
            };
            
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const existingIndex = cart.findIndex(item => item.id === cartItem.id);
            
            if (existingIndex !== -1) {
                cart[existingIndex].quantity += 1;
            } else {
                cart.push(cartItem);
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Show notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            notification.textContent = `Đã thêm "${name}" vào giỏ hàng!`;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }
    }
    
    // Navigate to product detail page
    function goToProductDetail(productId) {
        window.location.href = `/products/${productId}`;
    }
</script>
@endpush
