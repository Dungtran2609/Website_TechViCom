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
            transition: opacity 0.5s ease-in-out;
        }

        .slide.active {
            display: block;
            opacity: 1;
            position: relative;
        }

        .slide-nav {
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
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            background: white;
            transform: scale(1.2);
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
    <section class="relative overflow-hidden">
        <div class="slideshow-container relative w-full h-96 md:h-[500px]">
            <!-- Slide 1 -->
            <div class="slide active">
                <div class="bg-gradient-to-r from-[#ff6c2f] to-[#e55a28] text-white h-full">
                    <div class="container mx-auto px-4 h-full flex items-center">
                        <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8 w-full">
                            <div>
                                <h1 class="text-4xl lg:text-6xl font-bold mb-4 slide-in">SIÊU SALE</h1>
                                <h2 class="text-2xl lg:text-3xl mb-6 slide-in-delay-1">iPhone 15 Series</h2>
                                <p class="text-lg mb-8 slide-in-delay-2">Giảm đến 5 triệu - Trả góp 0%</p>
                                <button onclick="goToFeaturedProduct()"
                                    class="bg-yellow-400 text-black px-8 py-3 rounded-lg font-bold hover:bg-yellow-500 transition slide-in-delay-3">
                                    MUA NGAY
                                </button>
                            </div>
                            <div class="text-center">
                                <img src="assets/images/iphone-15.png" alt="iPhone 15"
                                    class="max-w-full h-auto slide-in-right"
                                    onerror="this.onerror=null; this.src='assets/images/placeholder.svg'">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white h-full">
                    <div class="container mx-auto px-4 h-full flex items-center">
                        <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8 w-full">
                            <div>
                                <h1 class="text-4xl lg:text-6xl font-bold mb-4 slide-in">SAMSUNG</h1>
                                <h2 class="text-2xl lg:text-3xl mb-6 slide-in-delay-1">Galaxy S24 Ultra</h2>
                                <p class="text-lg mb-8 slide-in-delay-2">Công nghệ AI tiên tiến - Giảm 3 triệu</p>
                                <button onclick="window.location.href='{{ route('products.index') }}'"
                                    class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition slide-in-delay-3">
                                    KHÁM PHÁ
                                </button>
                            </div>
                            <div class="text-center">
                                <img src="assets/images/samsung-s24-ultra.jpg" alt="Samsung S24 Ultra"
                                    class="max-w-full h-auto slide-in-right"
                                    onerror="this.onerror=null; this.src='assets/images/placeholder.svg'">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide">
                <div class="bg-gradient-to-r from-gray-700 to-gray-900 text-white h-full">
                    <div class="container mx-auto px-4 h-full flex items-center">
                        <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8 w-full">
                            <div>
                                <h1 class="text-4xl lg:text-6xl font-bold mb-4 slide-in">MACBOOK</h1>
                                <h2 class="text-2xl lg:text-3xl mb-6 slide-in-delay-1">Pro M3 Series</h2>
                                <p class="text-lg mb-8 slide-in-delay-2">Hiệu năng đỉnh cao - Ưu đãi học sinh sinh viên</p>
                                <button onclick="window.location.href='{{ route('products.index') }}'"
                                    class="bg-gray-200 text-black px-8 py-3 rounded-lg font-bold hover:bg-gray-300 transition slide-in-delay-3">
                                    XEM NGAY
                                </button>
                            </div>
                            <div class="text-center">
                                <img src="assets/images/macbook-pro-m3.jpg" alt="MacBook Pro M3"
                                    class="max-w-full h-auto slide-in-right"
                                    onerror="this.onerror=null; this.src='assets/images/placeholder.svg'">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation arrows -->
            <button class="slide-nav prev" onclick="changeSlide(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slide-nav next" onclick="changeSlide(1)">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Slide indicators -->
            <div class="slide-indicators">
                <span class="indicator active" onclick="currentSlide(1)"></span>
                <span class="indicator" onclick="currentSlide(2)"></span>
                <span class="indicator" onclick="currentSlide(3)"></span>
            </div>
        </div>
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
                                <img src="{{ asset('uploads/categories/' . $category->image) }}" alt="{{ $category->name }}"
                                    class="w-16 h-16 mx-auto mb-4 object-cover rounded-lg">
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
    <!-- Bài viết mới Section -->
    <section class="py-10 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8 text-[#ff6c2f]">Bài viết mới</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 justify-center items-stretch">
                @foreach ($latestNews as $item)
                    <div class="bg-white border border-[#ff6c2f] rounded-xl shadow-lg flex flex-col overflow-hidden hover:shadow-xl hover:border-[#0052cc] transition-all duration-300 group mx-auto relative h-[320px] min-w-[260px] max-w-[320px] w-full">
                        <button onclick="window.location.href='{{ route('client.news.show', $item->id) }}'" class="absolute inset-0 w-full h-full z-10 cursor-pointer opacity-0" aria-label="Xem bài viết"></button>
                        <div class="w-full h-[180px] relative flex items-center justify-center bg-gray-100">
                            <img src="{{ asset($item->image ?? 'client_css/images/placeholder.svg') }}" alt="{{ $item->title }}" class="w-full h-full object-cover rounded-t-xl group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="flex-1 flex items-center justify-center">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#ff6c2f] transition-colors duration-200 text-center px-4">{{ $item->title }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-center mt-6">
                <a href="{{ route('client.news.index') }}" class="px-6 py-3 bg-[#ff6c2f] text-white rounded-full font-semibold shadow hover:bg-[#e55a28] transition text-lg">Xem tất cả bài viết</a>
            </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Slideshow functionality
        document.addEventListener('DOMContentLoaded', function() {

            // Page-specific initialization will be handled in the main DOMContentLoaded listener below

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
                dropdown.className =
                    'absolute top-full right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden account-dropdown';
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
                        
                        <!-- Logged in state (hidden by default) -->
                        <div id="logged-in" class="hidden">
                            <div class="flex items-center space-x-3 mb-4 pb-4 border-b">
                                <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900" id="user-name">Nguyễn Văn A</h3>
                                    <p class="text-sm text-gray-500" id="user-email">user@example.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="border-t pt-4 mt-4">
                        <div class="space-y-2">
                            <a href="pages/account.html" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                <i class="fas fa-user text-gray-500 w-5"></i>
                                <span>Thông tin tài khoản</span>
                            </a>
                            <a href="pages/orders.html" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                <i class="fas fa-shopping-bag text-gray-500 w-5"></i>
                                <span>Đơn hàng của tôi</span>
                            </a>
                            <a href="pages/wishlist.html" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                <i class="fas fa-heart text-gray-500 w-5"></i>
                                <span>Sản phẩm yêu thích</span>
                            </a>
                            <a href="pages/contact.html" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                <i class="fas fa-headset text-gray-500 w-5"></i>
                                <span>Hỗ trợ khách hàng</span>
                            </a>
                            
                            <!-- Logout button (only show when logged in) -->
                            <div id="logout-section" class="hidden border-t pt-2 mt-2">
                                <button onclick="logoutUser()" class="flex items-center space-x-3 p-3 hover:bg-orange-50 rounded-lg transition text-[#ff6c2f] w-full text-left">
                                    <i class="fas fa-sign-out-alt text-[#ff6c2f] w-5"></i>
                                    <span>Đăng xuất</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                // Add dropdown to button's parent
                accountButton.parentElement.appendChild(dropdown);

                // Toggle dropdown on button click
                accountButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Close other dropdowns
                    closeAllDropdowns();
                    dropdown.classList.toggle('hidden');
                });

                // Prevent dropdown from closing when clicking inside it
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Check login status and update UI
                updateAccountStatus();
            }

            function updateAccountStatus() {
                // Check if user is logged in (you can implement your own logic here)
                const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
                const userData = JSON.parse(localStorage.getItem('userData') || '{}');

                const notLoggedInSection = document.getElementById('not-logged-in');
                const loggedInSection = document.getElementById('logged-in');
                const logoutSection = document.getElementById('logout-section');

                if (isLoggedIn && userData.name) {
                    // Show logged in state
                    notLoggedInSection.classList.add('hidden');
                    loggedInSection.classList.remove('hidden');
                    logoutSection.classList.remove('hidden');

                    // Update user info
                    document.getElementById('user-name').textContent = userData.name;
                    document.getElementById('user-email').textContent = userData.email;
                } else {
                    // Show not logged in state
                    notLoggedInSection.classList.remove('hidden');
                    loggedInSection.classList.add('hidden');
                    logoutSection.classList.add('hidden');
                }
            }

            function logoutUser() {
                // Clear user data
                localStorage.removeItem('isLoggedIn');
                localStorage.removeItem('userData');

                // Update UI
                updateAccountStatus();

                // Close dropdown
                closeAllDropdowns();

                // Show notification
                showNotificationInline('Đã đăng xuất thành công!');
            }

            function closeAllDropdowns() {
                const dropdowns = document.querySelectorAll(
                '.account-dropdown, .category-dropdown, .cart-dropdown');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }

            function setupCartSidebarInline() {
                const cartButton = document.getElementById('cartMenuBtn');
                const cartSidebar = document.getElementById('cart-sidebar');
                const cartOverlay = document.getElementById('cart-overlay');
                const closeSidebarBtn = document.getElementById('close-cart-sidebar');

                if (!cartButton || !cartSidebar || !cartOverlay || !closeSidebarBtn) return;

                // Check if listeners already added
                if (cartButton.dataset.listenerAdded === 'true') {
                    console.log('Cart sidebar listeners already added');
                    return;
                }

                // Open sidebar when cart button clicked
                cartButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    openCartSidebar();
                });

                // Close sidebar when close button clicked
                closeSidebarBtn.addEventListener('click', function() {
                    closeCartSidebar();
                });

                // Close sidebar when overlay clicked
                cartOverlay.addEventListener('click', function() {
                    closeCartSidebar();
                });

                // Mark listeners as added
                cartButton.dataset.listenerAdded = 'true';

                // Load cart content
                loadCartSidebar();
            }

            function openCartSidebar() {
                const cartSidebar = document.getElementById('cart-sidebar');
                const cartOverlay = document.getElementById('cart-overlay');

                if (cartSidebar && cartOverlay) {
                    cartSidebar.classList.remove('hidden');
                    cartOverlay.classList.remove('hidden');

                    // Add slight delay for smooth animation
                    setTimeout(() => {
                        cartSidebar.classList.remove('translate-x-full');
                    }, 10);

                    // Load latest cart data
                    loadCartSidebar();

                    // Prevent body scroll
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeCartSidebar() {
                const cartSidebar = document.getElementById('cart-sidebar');
                const cartOverlay = document.getElementById('cart-overlay');

                if (cartSidebar && cartOverlay) {
                    cartSidebar.classList.add('translate-x-full');

                    setTimeout(() => {
                        cartSidebar.classList.add('hidden');
                        cartOverlay.classList.add('hidden');
                    }, 300);

                    // Restore body scroll
                    document.body.style.overflow = '';
                }
            }

            function loadCartSidebar() {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const itemsContainer = document.getElementById('cart-items-container');
                const summaryContainer = document.getElementById('cart-summary');

                if (!itemsContainer || !summaryContainer) return;

                if (cart.length === 0) {
                    // Empty cart state
                    itemsContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-center py-12">
                        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Giỏ hàng trống</h3>
                        <p class="text-gray-500 mb-6">Hãy thêm sản phẩm để tiếp tục mua sắm</p>
                        <button onclick="closeCartSidebar()" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition">
                            Tiếp tục mua sắm
                        </button>
                    </div>
                `;
                    summaryContainer.innerHTML = '';
                    return;
                }

                // Cart items
                itemsContainer.innerHTML = `
                <div class="space-y-4">
                    ${cart.map(item => `
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg cart-item" data-id="${item.id}">
                                <div class="w-16 h-16 bg-white rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="${item.image || 'assets/images/placeholder.svg'}" 
                                         alt="${item.name}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.src='assets/images/placeholder.svg'">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 truncate">${item.name}</h4>
                                    <p class="text-sm text-gray-500">${item.color || ''} ${item.storage || ''}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="text-orange-600 font-semibold">
                                            ${window.PRODUCT_UTILS.formatCurrency(item.price)}
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="updateCartQuantityInline(${item.id}, ${item.quantity - 1})" 
                                                    class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded hover:bg-gray-50">
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <span class="w-8 text-center font-medium">${item.quantity}</span>
                                            <button onclick="updateCartQuantityInline(${item.id}, ${item.quantity + 1})" 
                                                    class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded hover:bg-gray-50">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                            <button onclick="removeFromCartInline(${item.id})" 
                                                    class="w-8 h-8 flex items-center justify-center text-[#ff6c2f] hover:bg-orange-50 rounded">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                </div>
            `;

                // Cart summary
                const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);

                summaryContainer.innerHTML = `
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Tổng sản phẩm:</span>
                        <span class="font-medium">${itemCount} sản phẩm</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Tạm tính:</span>
                        <span class="font-medium">${window.PRODUCT_UTILS.formatCurrency(total)}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Phí vận chuyển:</span>
                        <span class="font-medium text-green-600">Miễn phí</span>
                    </div>
                    <div class="border-t pt-4">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-lg font-semibold text-gray-900">Tổng cộng:</span>
                            <span class="text-xl font-bold text-orange-600">${window.PRODUCT_UTILS.formatCurrency(total)}</span>
                        </div>
                        <div class="space-y-3">
                            <button onclick="proceedToCheckout()" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                                <i class="fas fa-credit-card mr-2"></i>
                                Thanh toán ngay
                            </button>
                            <button onclick="window.location.href='pages/cart.html'" class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                                Xem giỏ hàng chi tiết
                            </button>
                        </div>
                    </div>
                </div>
            `;
            }

            function updateCartQuantityInline(productId, newQuantity) {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                if (newQuantity <= 0) {
                    // Remove item if quantity is 0 or less
                    cart = cart.filter(item => item.id !== productId);
                } else {
                    // Update quantity
                    const itemIndex = cart.findIndex(item => item.id === productId);
                    if (itemIndex >= 0) {
                        cart[itemIndex].quantity = newQuantity;
                    }
                }

                localStorage.setItem('cart', JSON.stringify(cart));
                loadCartSidebar();
                updateCartCountInline();
            }

            function removeFromCartInline(productId) {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart = cart.filter(item => item.id !== productId);
                localStorage.setItem('cart', JSON.stringify(cart));
                loadCartSidebar();
                updateCartCountInline();
                showNotificationInline('Đã xóa sản phẩm khỏi giỏ hàng!');
            }

            function proceedToCheckout() {
                // Close sidebar
                closeCartSidebar();

                // Navigate to checkout page
                window.location.href = 'pages/checkout.html';
            }

            // Global click listener flag
            let globalClickListenerAdded = false;

            function demoLogin() {
                localStorage.setItem('isLoggedIn', 'true');
                localStorage.setItem('userData', JSON.stringify({
                    name: 'Nguyễn Văn A',
                    email: 'nguyenvana@email.com'
                }));
                updateAccountStatus();
                showNotificationInline('Đăng nhập thành công!');
            }

            function loadFeaturedProductsInline() {
                try {
                    const container = document.getElementById('featured-products');
                    if (!container) {
                        console.log('Featured products container not found');
                        return;
                    }

                    if (!window.PRODUCT_DATA || !window.PRODUCT_DATA.products) {
                        console.error('Product data not available');
                        return;
                    }

                    container.innerHTML = '';
                    const products = window.PRODUCT_DATA.products.slice(0, 8);

                    products.forEach(product => {
                        const productCard = createProductCardInline(product);
                        container.appendChild(productCard);
                    });

                    console.log('Featured products loaded successfully');
                } catch (error) {
                    console.error('Error loading featured products:', error);
                }
            }

            function loadFlashSaleProductsInline() {
                try {
                    const container = document.getElementById('flash-sale-products');
                    if (!container) {
                        console.log('Flash sale products container not found');
                        return;
                    }

                    if (!window.PRODUCT_UTILS) {
                        console.error('Product utils not available');
                        return;
                    }

                    container.innerHTML = '';
                    const flashProducts = window.PRODUCT_UTILS.getFlashSaleProducts();

                    flashProducts.forEach(product => {
                        const productCard = createProductCardInline(product, true);
                        container.appendChild(productCard);
                    });

                    console.log('Flash sale products loaded successfully');
                } catch (error) {
                    console.error('Error loading flash sale products:', error);
                }
            }

            function createProductCardInline(product, isFlashSale = false) {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow-md overflow-hidden product-card cursor-pointer';

                const discountBadge = product.discount ?
                    `<div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">-${product.discount}%</div>` :
                    '';

                const flashSaleBadge = isFlashSale ?
                    '<div class="absolute top-2 right-2 bg-yellow-400 text-[#ff6c2f] px-2 py-1 rounded text-sm font-bold">⚡ FLASH</div>' :
                    '';

                const price = isFlashSale && product.salePrice ? product.salePrice : product.price;
                const originalPriceHtml = product.originalPrice ?
                    `<span class="text-gray-500 line-through text-sm">${window.PRODUCT_UTILS.formatCurrency(product.originalPrice)}</span>` :
                    '';

                card.innerHTML = `
                <div class="relative">
                    ${discountBadge}
                    ${flashSaleBadge}
                    <img src="${product.image || 'assets/images/placeholder.svg'}" alt="${product.name}" class="w-full h-48 object-cover" 
                         onerror="this.onerror=null; this.src='assets/images/placeholder.svg'">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">${product.name}</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            ${window.PRODUCT_UTILS.createStarRating(product.rating || 0)}
                        </div>
                        <span class="text-gray-500 text-sm ml-2">(${product.reviews || 0})</span>
                    </div>
                    <div class="mb-3">
                        <div class="text-[#ff6c2f] font-bold text-lg">${window.PRODUCT_UTILS.formatCurrency(price)}</div>
                        ${originalPriceHtml}
                    </div>
                    <button onclick="addToCartInline(${product.id})" 
                            class="w-full bg-[#ff6c2f] text-white py-2 rounded-lg hover:bg-[#ff6c2f] transition">
                        Thêm vào giỏ
                    </button>
                </div>
            `;

                // Add click event for product details
                card.addEventListener('click', function(e) {
                    if (!e.target.closest('button')) {
                        window.location.href = `pages/product-detail.html?id=${product.id}`;
                    }
                });

                return card;
            }

            function addToCartInline(productId) {
                const product = window.PRODUCT_UTILS.getProductById(productId);
                if (!product) return;

                // Get existing cart from localStorage
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Check if product already in cart
                const existingItem = cart.find(item => item.id === productId);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        image: product.image,
                        quantity: 1,
                        category: product.category
                    });
                }

                // Save to localStorage
                localStorage.setItem('cart', JSON.stringify(cart));

                // Update cart count
                updateCartCountInline();

                // Show notification
                showNotificationInline(`Đã thêm ${product.name} vào giỏ hàng!`);
            }

            function updateCartCountInline() {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = totalItems;
                }
            }

            function showNotificationInline(message) {
                const notification = document.createElement('div');
                notification.className =
                    'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300';
                notification.textContent = message;

                document.body.appendChild(notification);

                // Show notification
                setTimeout(() => {
                    notification.classList.remove('translate-x-full', 'opacity-0');
                }, 100);

                // Hide notification
                setTimeout(() => {
                    notification.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }, 3000);
            }

            function startCountdownInline() {
                const hoursElement = document.getElementById('hours');
                const minutesElement = document.getElementById('minutes');
                const secondsElement = document.getElementById('seconds');

                if (!hoursElement || !minutesElement || !secondsElement) return;

                let timeLeft = 43200; // 12 hours in seconds

                const countdown = setInterval(() => {
                    if (timeLeft <= 0) {
                        clearInterval(countdown);
                        timeLeft = 43200; // Reset
                        return;
                    }

                    const hours = Math.floor(timeLeft / 3600);
                    const minutes = Math.floor((timeLeft % 3600) / 60);
                    const seconds = timeLeft % 60;

                    hoursElement.textContent = hours.toString().padStart(2, '0');
                    minutesElement.textContent = minutes.toString().padStart(2, '0');
                    secondsElement.textContent = seconds.toString().padStart(2, '0');

                    timeLeft--;
                }, 1000);
            }

            // Initialize cart count on page load
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOMContentLoaded fired');

                if (!checkExecutionLimit()) {
                    return;
                }

                // Prevent double initialization
                if (isPageInitialized) {
                    console.log('Page already initialized, skipping');
                    return;
                }

                console.log('Initializing page...');
                isPageInitialized = true;

                // Check if PRODUCT_DATA is loaded
                if (!window.PRODUCT_DATA) {
                    console.error('PRODUCT_DATA not loaded!');
                    return;
                }

                try {
                    // Initialize cart count
                    updateCartCountInline();

                    // Setup UI components
                    setupAccountDropdownInline();
                    setupCartSidebarInline();

                    // Load products
                    loadFeaturedProductsInline();
                    loadFlashSaleProductsInline();
                    startCountdownInline();

                    // Add global click listener only once
                    if (!globalClickListenerAdded) {
                        document.addEventListener('click', function() {
                            closeAllDropdowns();
                        });
                        globalClickListenerAdded = true;
                    }

                    console.log('Page initialization completed');
                } catch (error) {
                    console.error('Error during page initialization:', error);
                }
            });

            // Static add to cart function for hardcoded products
            function addToCartStatic(id, name, price, image) {
                const cartItem = {
                    id: id,
                    productId: id,
                    name: name,
                    price: price,
                    quantity: 1,
                    image: image,
                    color: 'Mặc định',
                    storage: 'Mặc định'
                };

                // Get existing cart
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Check if item already exists
                const existingIndex = cart.findIndex(item => item.id === cartItem.id);

                if (existingIndex !== -1) {
                    cart[existingIndex].quantity += 1;
                } else {
                    cart.push(cartItem);
                }

                // Save to localStorage
                localStorage.setItem('cart', JSON.stringify(cart));

                // Update cart count if function exists
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }

                // Show simple notification
                const notification = document.createElement('div');
                notification.className =
                    'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                notification.textContent = `Đã thêm "${name}" vào giỏ hàng!`;
                document.body.appendChild(notification);

                // Remove notification after 3 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 3000);

                console.log('Added to cart:', cartItem);
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

            // Initialize slideshow when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Add a small delay to ensure all elements are loaded
                setTimeout(initSlideshow, 100);
            });

            // Make functions globally available
            window.changeSlide = changeSlide;
            window.currentSlide = currentSlide;

            // Category dropdown functionality
            window.toggleCategoryDropdown = function(categoryId) {
                const dropdown = document.getElementById('dropdown-' + categoryId);
                const icon = document.getElementById('icon-' + categoryId);

                if (dropdown && icon) {
                    dropdown.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                }
            };

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.category-dropdown-wrapper')) {
                    const dropdowns = document.querySelectorAll('.category-dropdown');
                    const icons = document.querySelectorAll('[id^="icon-"]');

                    dropdowns.forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });

                    icons.forEach(icon => {
                        icon.classList.remove('rotate-180');
                    });
                }
            });
        });
    </script>
@endpush
