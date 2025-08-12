@extends('client.layouts.app')

@section('title', 'Techvicom - Điện thoại, Laptop, Tablet, Phụ kiện chính hãng')

@push('styles')
    <style>
        /* Slideshow Styles */
        .slideshow-container {
            position: relative;
        }

        .slide {
            display: none;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transform: translateX(100%);
            transition: transform 0.8s ease, opacity 0.8s ease;
        }


        .slide.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
            position: relative;
        }

        .slide-nav {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.8);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            color: #333;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .slide-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            background: rgba(0, 0, 0, 0.4);
            padding: 4px 8px;
            border-radius: 20px;
        }


        .slideshow-container:hover .slide-nav {
            opacity: 1;
            visibility: visible;
        }

        .slide-nav:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-50%) scale(1.1);
        }

        .slide-nav.prev {
            left: 20px;
        }

        .slide-nav.next {
            right: 20px;
        }

        .slide-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .indicator {
            width: 10px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .indicator.active {
            width: 20px;
            border-radius: 10px;
            background: white;
        }

        .indicator:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        /* Animation Classes */
        .slide-in {
            animation: slideInLeft 0.8s ease-out;
        }

        .slide-in-delay-1 {
            animation: slideInLeft 0.8s ease-out 0.2s both;
        }

        .slide-in-delay-2 {
            animation: slideInLeft 0.8s ease-out 0.4s both;
        }

        .slide-in-delay-3 {
            animation: slideInLeft 0.8s ease-out 0.6s both;
        }

        .slide-in-right {
            animation: slideInRight 0.8s ease-out 0.3s both;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .slide-nav {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }

            .slide-nav.prev {
                left: 10px;
            }

            .slide-nav.next {
                right: 10px;
            }

            .slide-indicators {
                bottom: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Banner Slideshow -->
    <section class="relative overflow-hidden bg-gray-50">
        @if (isset($banners) && $banners->count() > 0)
            <div class="slideshow-container relative w-full h-96 md:h-[400px] lg:h-[500px]">
                @foreach ($banners as $index => $banner)
                    <div class="slide {{ $index == 0 ? 'active' : '' }}">
                        @if ($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" class="block h-full group">
                                <div class="relative h-full overflow-hidden">
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner {{ $index + 1 }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                        onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                    <!-- Overlay for better text readability -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
                                </div>
                            </a>
                        @else
                            <div class="relative h-full overflow-hidden">
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner {{ $index + 1 }}"
                                    class="w-full h-full object-cover transition-transform duration-700"
                                    onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                <!-- Overlay for better text readability -->
                                <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
                            </div>
                        @endif
                    </div>
                @endforeach

                @if ($banners->count() > 1)
                    <!-- Navigation arrows -->
                    <button class="slide-nav prev group" onclick="changeSlide(-1)">
                        <div
                            class="bg-white/80 hover:bg-white text-gray-800 hover:text-black rounded-full w-12 h-12 flex items-center justify-center transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-chevron-left text-lg"></i>
                        </div>
                    </button>
                    <button class="slide-nav next group" onclick="changeSlide(1)">
                        <div
                            class="bg-white/80 hover:bg-white text-gray-800 hover:text-black rounded-full w-12 h-12 flex items-center justify-center transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-chevron-right text-lg"></i>
                        </div>
                    </button>

                    <!-- Slide indicators -->
                    <div class="slide-indicators">
                        @foreach ($banners as $index => $banner)
                            <span class="indicator {{ $index == 0 ? 'active' : '' }}"
                                onclick="currentSlide({{ $index + 1 }})"></span>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <!-- Fallback banner khi không có banner nào -->
            <div class="slideshow-container relative w-full h-96 md:h-[500px] lg:h-[600px]">
                <div class="slide active">
                    <div
                        class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white h-full relative overflow-hidden">
                        <!-- Animated background elements -->
                        <div class="absolute inset-0">
                            <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                            <div class="absolute bottom-10 right-10 w-40 h-40 bg-white/5 rounded-full blur-xl"></div>
                            <div class="absolute top-1/2 left-1/4 w-20 h-20 bg-white/10 rounded-full blur-lg"></div>
                        </div>

                        <div class="container mx-auto px-4 h-full flex items-center relative z-10">
                            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-12 w-full">
                                <div class="space-y-6">
                                    <div
                                        class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">
                                        <i class="fas fa-star text-yellow-300 mr-2"></i>
                                        Công nghệ hàng đầu
                                    </div>
                                    <h1
                                        class="text-5xl lg:text-7xl font-bold mb-4 slide-in bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                                        TECHVICOM
                                    </h1>
                                    <h2 class="text-2xl lg:text-4xl mb-6 slide-in-delay-1 font-light">
                                        Công nghệ tiên tiến
                                    </h2>
                                    <p class="text-lg lg:text-xl mb-8 slide-in-delay-2 text-blue-100 leading-relaxed">
                                        Khám phá các sản phẩm công nghệ mới nhất với chất lượng cao và giá cả hợp lý
                                    </p>
                                    <div class="flex flex-col sm:flex-row gap-4 slide-in-delay-3">
                                        <button onclick="window.location.href='{{ route('products.index') }}'"
                                            class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            KHÁM PHÁ NGAY
                                        </button>
                                        <button onclick="window.location.href='{{ route('contacts.index') }}'"
                                            class="border-2 border-white/30 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/10 transition-all duration-300 backdrop-blur-sm">
                                            <i class="fas fa-phone mr-2"></i>
                                            LIÊN HỆ
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center relative">
                                    <div class="relative inline-block">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full blur-3xl opacity-30">
                                        </div>
                                        <img src="{{ asset('client_css/images/placeholder.svg') }}" alt="TechViCom"
                                            class="relative z-10 max-w-full h-auto slide-in-right transform hover:scale-105 transition-transform duration-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- Featured Categories -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Danh mục nổi bật</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach ($categories as $category)
                    <div class="text-center group">
                        <div
                            class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group-hover:scale-105 relative">
                            <!-- Category Image -->
                            @if ($category->image)
                                <img src="{{ asset('uploads/categories/' . $category->image) }}"
                                    alt="{{ $category->name }}" class="w-16 h-16 mx-auto mb-4 object-cover rounded-lg">
                            @else
                                <img src="{{ asset('client_css/images/categories/category-default.jpg') }}"
                                    alt="{{ $category->name }}" class="w-16 h-16 mx-auto mb-4 object-cover rounded-lg"
                                    onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                            @endif

                            <!-- Category Name -->
                            <h3 class="font-semibold mb-2">{{ $category->name }}</h3>

                            <!-- Category Actions -->
                            @if ($category->children->count() > 0)
                                <div class="category-dropdown-wrapper">
                                    <!-- Main category link -->
                                    <a href="{{ route('categories.show', $category->slug) }}"
                                        class="block text-sm text-blue-600 hover:text-blue-800 mb-2">
                                        Xem tất cả
                                    </a>

                                    <!-- Dropdown trigger -->
                                    <button class="text-xs text-gray-500 hover:text-gray-700 flex items-center mx-auto"
                                        onclick="toggleCategoryDropdown({{ $category->id }})">
                                        <span>{{ $category->children->count() }} danh mục con</span>
                                        <i class="fas fa-chevron-down ml-1 transition-transform duration-200"
                                            id="icon-{{ $category->id }}"></i>
                                    </button>

                                    <!-- Dropdown menu -->
                                    <div class="category-dropdown absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden mt-2"
                                        id="dropdown-{{ $category->id }}">
                                        <div class="p-3">
                                            <div class="space-y-2">
                                                @foreach ($category->children as $subcategory)
                                                    <a href="{{ route('categories.show', $subcategory->slug) }}"
                                                        class="block text-sm text-gray-700 hover:text-blue-600 hover:bg-gray-50 px-2 py-1 rounded transition">
                                                        {{ $subcategory->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Direct link for categories without children -->
                                <a href="{{ route('categories.show', $category->slug) }}"
                                    class="text-sm text-blue-600 hover:text-blue-800 cursor-pointer">
                                    Xem sản phẩm
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Flash Sale -->
    <section class="py-12 bg-yellow-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-[#ff6c2f]">⚡ FLASH SALE</h2>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}"
                        class="text-[#ff6c2f] hover:hover:text-[#ff6c2f] font-semibold flex items-center">
                        Xem tất cả <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <div class="flex items-center space-x-2 text-lg font-semibold">
                        <span>Kết thúc trong:</span>
                        <div class="bg-[#ff6c2f] text-white px-3 py-1 rounded min-w-[3rem] text-center" id="hours">12
                        </div>
                        <span>:</span>
                        <div class="bg-[#ff6c2f] text-white px-3 py-1 rounded min-w-[3rem] text-center" id="minutes">34
                        </div>
                        <span>:</span>
                        <div class="bg-[#ff6c2f] text-white px-3 py-1 rounded min-w-[3rem] text-center" id="seconds">56
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="flash-sale-products">
                <!-- Flash sale products - Static HTML -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                    data-product-id="1" onclick="window.location.href='{{ route('products.show', 1) }}'">
                    <div class="relative">
                        <img src="assets/images/placeholder.svg" alt="iPhone 15 Pro Max"
                            class="w-full h-48 object-cover rounded-t-lg">
                        <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">
                            -8%
                        </div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">iPhone 15 Pro Max 256GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(156)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">34.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">37.990.000₫</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCartStatic(1, 'iPhone 15 Pro Max 256GB', 34990000, 'assets/images/placeholder.svg')"
                                class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                    data-product-id="2">
                    <div class="relative">
                        <img src="assets/images/samsung-s24-ultra.jpg" alt="Samsung Galaxy S24 Ultra"
                            class="w-full h-48 object-cover rounded-t-lg">
                        <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">
                            -8%
                        </div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">Samsung Galaxy S24 Ultra 512GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(89)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">33.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">36.990.000₫</span>
                            </div>
                            <button
                                onclick="addToCartStatic(2, 'Samsung Galaxy S24 Ultra 512GB', 33990000, 'assets/images/samsung-s24-ultra.jpg')"
                                class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                    data-product-id="3">
                    <div class="relative">
                        <img src="assets/images/macbook-pro-m3.jpg" alt="MacBook Pro M3"
                            class="w-full h-48 object-cover rounded-t-lg">
                        <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">
                            -10%
                        </div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">MacBook Pro M3 14inch 512GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(124)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">53.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">59.990.000₫</span>
                            </div>
                            <button
                                onclick="addToCartStatic(3, 'MacBook Pro M3 14inch 512GB', 53990000, 'assets/images/macbook-pro-m3.jpg')"
                                class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                    data-product-id="4">
                    <div class="relative">
                        <img src="assets/images/ipad-pro-m2.jpg" alt="iPad Pro M2"
                            class="w-full h-48 object-cover rounded-t-lg">
                        <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">
                            -12%
                        </div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">iPad Pro M2 11inch 256GB</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(98)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-[#ff6c2f]">24.990.000₫</span>
                                <span class="text-sm text-gray-500 line-through ml-2">28.990.000₫</span>
                            </div>
                            <button
                                onclick="addToCartStatic(4, 'iPad Pro M2 11inch 256GB', 24990000, 'assets/images/ipad-pro-m2.jpg')"
                                class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold">Sản phẩm nổi bật</h2>
                <a href="{{ route('products.index') }}"
                    class="text-[#ff6c2f] hover:hover:text-[#ff6c2f] font-semibold flex items-center">
                    Xem tất cả <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($latestProducts as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                        onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                        <div class="relative">
                            @if ($product->productAllImages->count() > 0)
                                <img src="{{ asset('uploads/products/' . $product->productAllImages->first()->image_url) }}"
                                    alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-lg">
                            @else
                                <img src="{{ asset('client_css/images/placeholder.svg') }}" alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover rounded-t-lg">
                            @endif

                            <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-sm font-bold">
                                HOT
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50"
                                    onclick="event.stopPropagation();">
                                    <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <span class="text-gray-500 text-sm ml-2">(0)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if ($product->type === 'simple' && $product->variants->count() > 0)
                                        @php
                                            $variant = $product->variants->first();
                                        @endphp
                                        <span
                                            class="text-lg font-bold text-[#ff6c2f]">{{ number_format($variant->price) }}₫</span>
                                    @elseif($product->type === 'variable' && $product->variants->count() > 0)
                                        @php
                                            $minPrice = $product->variants->min('price');
                                            $maxPrice = $product->variants->max('price');
                                        @endphp
                                        @if ($minPrice === $maxPrice)
                                            <span
                                                class="text-lg font-bold text-[#ff6c2f]">{{ number_format($minPrice) }}₫</span>
                                        @else
                                            <span class="text-lg font-bold text-[#ff6c2f]">{{ number_format($minPrice) }}
                                                - {{ number_format($maxPrice) }}₫</span>
                                        @endif
                                    @else
                                        <span class="text-lg font-bold text-[#ff6c2f]">Liên hệ</span>
                                    @endif
                                </div>
                                <button onclick="event.stopPropagation(); addToCart({{ $product->id }}, null, 1)"
                                    class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($latestProducts->count() < 8)
                    <!-- Static products to fill up the grid if needed -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                        data-product-id="5">
                        <div class="relative">
                            <img src="{{ asset('client_css/images/placeholder.svg') }}" alt="AirPods Pro 2"
                                class="w-full h-48 object-cover rounded-t-lg">
                            <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-sm font-bold">
                                HOT
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                    <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">AirPods Pro 2nd Gen</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="text-gray-500 text-sm ml-2">(234)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-[#ff6c2f]">6.990.000₫</span>
                                </div>
                                <button
                                    onclick="addToCartStatic(5, 'AirPods Pro 2nd Gen', 6990000, '{{ asset('client_css/images/placeholder.svg') }}')"
                                    class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                        data-product-id="6">
                        <div class="relative">
                            <img src="{{ asset('client_css/images/placeholder.svg') }}" alt="Apple Watch Series 9"
                                class="w-full h-48 object-cover rounded-t-lg">
                            <div class="absolute top-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-sm font-bold">
                                NEW
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                    <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">Apple Watch Series 9 GPS 45mm</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="text-gray-500 text-sm ml-2">(167)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-[#ff6c2f]">10.990.000₫</span>
                                </div>
                                <button
                                    onclick="addToCartStatic(6, 'Apple Watch Series 9 GPS 45mm', 10990000, '{{ asset('client_css/images/placeholder.svg') }}')"
                                    class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                        data-product-id="7">
                        <div class="relative">
                            <img src="{{ asset('client_css/images/placeholder.svg') }}" alt="Sony WH-1000XM5"
                                class="w-full h-48 object-cover rounded-t-lg">
                            <div
                                class="absolute top-2 left-2 bg-purple-600 text-white px-2 py-1 rounded text-sm font-bold">
                                -15%
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                    <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">Sony WH-1000XM5 Wireless</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="text-gray-500 text-sm ml-2">(145)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-[#ff6c2f]">8.490.000₫</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">9.990.000₫</span>
                                </div>
                                <button
                                    onclick="addToCartStatic(7, 'Sony WH-1000XM5 Wireless', 8490000, '{{ asset('client_css/images/placeholder.svg') }}')"
                                    class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                        data-product-id="8">
                        <div class="relative">
                            <img src="{{ asset('client_css/images/placeholder.svg') }}" alt="Samsung Galaxy Buds2"
                                class="w-full h-48 object-cover rounded-t-lg">
                            <div
                                class="absolute top-2 left-2 bg-orange-600 text-white px-2 py-1 rounded text-sm font-bold">
                                -20%
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                    <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">Samsung Galaxy Buds2 Pro</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="text-gray-500 text-sm ml-2">(189)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-[#ff6c2f]">3.990.000₫</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">4.990.000₫</span>
                                </div>
                                <button
                                    onclick="addToCartStatic(8, 'Samsung Galaxy Buds2 Pro', 3990000, '{{ asset('client_css/images/placeholder.svg') }}')"
                                    class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let slides = document.querySelectorAll(".slide");
            let indicators = document.querySelectorAll(".indicator");
            let currentIndex = 0;
            let slideInterval = setInterval(nextSlide, 6000); // 4 giây

            function showSlide(nextIndex, direction = 1) {
                slides.forEach((slide, i) => {
                    slide.classList.remove("active", "prev-active");
                    if (i === currentIndex) {
                        slide.classList.add(direction === 1 ? "prev-active" : "active");
                    }
                    if (i === nextIndex) {
                        slide.classList.add("active");
                    }
                });

                indicators.forEach((ind, i) => {
                    ind.classList.toggle("active", i === nextIndex);
                });

                currentIndex = nextIndex;
            }

            function nextSlide() {
                let nextIndex = (currentIndex + 1) % slides.length;
                showSlide(nextIndex, 1);
            }

            function prevSlide() {
                let prevIndex = (currentIndex - 1 + slides.length) % slides.length;
                showSlide(prevIndex, -1);
            }

            // Nút điều hướng
            document.querySelector(".slide-nav.next")?.addEventListener("click", () => {
                clearInterval(slideInterval);
                nextSlide();
                slideInterval = setInterval(nextSlide, 5000);
            });

            document.querySelector(".slide-nav.prev")?.addEventListener("click", () => {
                clearInterval(slideInterval);
                prevSlide();
                slideInterval = setInterval(nextSlide, 4000);
            });

            showSlide(currentIndex);
        });
    </script>
@endpush
