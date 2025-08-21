@extends('client.layouts.app')

@section('title', 'Techvicom - Điện thoại, Laptop, Tablet, Phụ kiện chính hãng')

@push('styles')
    <style>
        /* ================= Slideshow ================= */
        .slideshow-container {
            position: relative
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
            transition: transform .6s ease, opacity .6s ease
        }

        .slide.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
            position: relative
        }

        .slide-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, .85);
            border: none;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            color: #333;
            transition: all .2s ease;
            z-index: 10;
            opacity: 0;
            visibility: hidden
        }

        .slideshow-container:hover .slide-nav {
            opacity: 1;
            visibility: visible
        }

        .slide-nav:hover {
            background: #fff;
            transform: translateY(-50%) scale(1.08)
        }

        .slide-nav.prev {
            left: 16px
        }

        .slide-nav.next {
            right: 16px
        }

        .slide-indicators {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
            background: rgba(0, 0, 0, .35);
            padding: 4px 8px;
            border-radius: 9999px
        }

        .indicator {
            width: 10px;
            height: 8px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, .7);
            transition: all .2s ease
        }

        .indicator.active {
            width: 20px;
            background: #fff
        }

        .slide-in {
            animation: slideInLeft .6s ease-out
        }

        .slide-in-delay-1 {
            animation: slideInLeft .6s ease-out .15s both
        }

        .slide-in-delay-2 {
            animation: slideInLeft .6s ease-out .3s both
        }

        .slide-in-right {
            animation: slideInRight .6s ease-out .2s both
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-24px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(24px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        .banner-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            image-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            backface-visibility: hidden;
            transform: translateZ(0)
        }

        @media (max-width:768px) {
            .slide-nav {
                width: 40px;
                height: 40px;
                font-size: 14px
            }

            .slide-nav.prev {
                left: 10px
            }

            .slide-nav.next {
                right: 10px
            }

            .slide-indicators {
                bottom: 10px
            }
        }

        /* ============== Horizontal slider (categories & brands) ============== */
        .hslider {
            position: relative
        }

        .hslider .htrack {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            gap: 24px;
            padding: 2px;
            -ms-overflow-style: none;
            scrollbar-width: none
        }

        .hslider .htrack::-webkit-scrollbar {
            display: none
        }

        .hslider .hnav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, .85);
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            cursor: pointer;
            font-size: 16px;
            color: #333;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
            transition: all .2s ease;
            z-index: 20;
            opacity: 0;
            visibility: hidden;
            pointer-events: auto
        }

        .hslider:hover .hnav {
            opacity: 1;
            visibility: visible
        }

        .hslider .hnav:hover {
            transform: translateY(-50%) scale(1.08);
            background: #fff
        }

        .hslider .hnav.prev {
            left: 8px
        }

        .hslider .hnav.next {
            right: 8px
        }

        .hitem {
            flex: 0 0 auto;
            scroll-snap-align: start
        }

        @media (min-width:1024px) {
            .hitem {
                width: calc(25% - 18px)
            }
        }

        @media (min-width:768px) and (max-width:1023.98px) {
            .hitem {
                width: calc(50% - 12px)
            }
        }

        @media (max-width:767.98px) {
            .hitem {
                width: 85vw
            }
        }

        /* ============== Product image style ============== */
        .prod-card .img-wrap {
            height: 240px;
            background: #fff;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            position: relative;
            overflow: hidden;
        }

        .prod-card .img-wrap img {
            position: relative;
            z-index: 0;
            max-height: 100%;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            image-rendering: auto;
            image-rendering: -webkit-optimize-contrast;
            backface-visibility: hidden;
            transform: translateZ(0);
            transition: transform .25s ease;
        }

        .prod-card:hover .img-wrap img {
            transform: scale(1.03)
        }

        @media (max-width:1023.98px) {
            .prod-card .img-wrap {
                height: 200px
            }
        }

        @media (max-width:767.98px) {
            .prod-card .img-wrap {
                height: 180px
            }
        }

        /* ============== Chips & Heart (fixed corners) ============== */
        .chip {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ef4444;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 4
        }

        .chip-neutral {
            background: #111827
        }

        .wish-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 5;
            background: #fff;
            border-radius: 9999px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .14)
        }

        .wish-btn i {
            color: #9ca3af;
            transition: color .15s ease
        }

        .wish-btn.active i {
            color: #ff6c2f
        }

        /* ====== Brands: thu nhỏ & 5 item/khung ====== */
        .brands-slider .brand-card {
            padding: 16px
        }

        .brands-slider .brand-card img {
            width: 56px;
            height: 56px
        }

        @media (min-width:1024px) {
            .brands-slider .hitem {
                width: calc((100% - 96px)/5) !important;
            }

            /* 5 items + 4 gaps(24px)=96px */
        }
    </style>
@endpush

@section('content')
    <!-- ================= Hero Banner Slideshow ================= -->
    <section class="relative overflow-hidden bg-gray-50">
        @if (isset($banners) && $banners->count() > 0)
            <div class="slideshow-container relative w-full h-80 md:h-[420px] lg:h-[520px]">
                @foreach ($banners as $index => $banner)
                    <div class="slide {{ $index === 0 ? 'active' : '' }}">
                        @php $img = asset('storage/' . $banner->image); @endphp
                        @if ($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" class="block h-full">
                                <div class="relative h-full bg-white flex items-center justify-center">
                                    <img src="{{ $img }}" alt="Banner {{ $index + 1 }}" class="banner-img"
                                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                        fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}" decoding="async"
                                        onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                </div>
                            </a>
                        @else
                            <div class="relative h-full bg-white flex items-center justify-center">
                                <img src="{{ $img }}" alt="Banner {{ $index + 1 }}" class="banner-img"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                    fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}" decoding="async"
                                    onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                            </div>
                        @endif
                    </div>
                @endforeach

                @if ($banners->count() > 1)
                    <button class="slide-nav prev" type="button" aria-label="Trước" data-slide="prev"><i
                            class="fas fa-chevron-left"></i></button>
                    <button class="slide-nav next" type="button" aria-label="Sau" data-slide="next"><i
                            class="fas fa-chevron-right"></i></button>
                    <div class="slide-indicators">
                        @foreach ($banners as $index => $banner)
                            <span class="indicator {{ $index === 0 ? 'active' : '' }}"
                                data-to="{{ $index }}"></span>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="slideshow-container relative w-full h-80 md:h-[420px] lg:h-[520px]">
                <div class="slide active">
                    <div
                        class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white h-full relative overflow-hidden">
                        <div class="absolute inset-0">
                            <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                            <div class="absolute bottom-10 right-10 w-40 h-40 bg-white/5 rounded-full blur-xl"></div>
                            <div class="absolute top-1/2 left-1/4 w-20 h-20 bg-white/10 rounded-full blur-lg"></div>
                        </div>
                        <div class="container mx-auto px-4 h-full flex items-center relative z-10">
                            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8 w-full">
                                <div class="space-y-5">
                                    <div
                                        class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">
                                        <i class="fas fa-star text-yellow-300 mr-2"></i> Công nghệ hàng đầu
                                    </div>
                                    <h1
                                        class="text-5xl lg:text-7xl font-bold slide-in bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                                        TECHVICOM</h1>
                                    <h2 class="text-2xl lg:text-4xl slide-in-delay-1 font-light">Công nghệ tiên tiến</h2>
                                    <p class="text-lg lg:text-xl slide-in-delay-2 text-blue-100 leading-relaxed">
                                        Khám phá các sản phẩm công nghệ mới nhất với chất lượng cao và giá cả hợp lý
                                    </p>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <a href="{{ route('products.index') }}"
                                            class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold hover:bg-blue-50 transition shadow-lg hover:shadow-xl">
                                            <i class="fas fa-shopping-cart mr-2"></i> KHÁM PHÁ NGAY
                                        </a>
                                        <a href="{{ route('contacts.index') }}"
                                            class="border-2 border-white/30 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/10 transition backdrop-blur-sm">
                                            <i class="fas fa-phone mr-2"></i> LIÊN HỆ
                                        </a>
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

    <!-- ================= Featured Categories: CAROUSEL ================= -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold">Danh mục</h2>
                <a href="{{ route('categories.index') }}" class="text-[#ff6c2f] font-semibold flex items-center">Xem tất cả
                    <i class="fas fa-arrow-right ml-2"></i></a>
            </div>

            <div class="hslider" data-slider>
                <button class="hnav prev" type="button" aria-label="Trước"><i class="fas fa-chevron-left"></i></button>
                <button class="hnav next" type="button" aria-label="Sau"><i class="fas fa-chevron-right"></i></button>

                <div class="htrack">
                    @foreach ($categories->take(12) as $category)
                        <div class="hitem">
                            <div
                                class="text-center group relative bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        class="w-16 h-16 mx-auto mb-4 object-cover rounded-lg" loading="lazy"
                                        decoding="async"
                                        onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                @else
                                    <img src="{{ asset('client_css/images/placeholder.svg') }}"
                                        alt="{{ $category->name }}" class="w-16 h-16 mx-auto mb-4 object-cover rounded-lg">
                                @endif
                                <h3 class="font-semibold mb-2 line-clamp-1">{{ $category->name }}</h3>
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                    {{ $category->children->count() > 0 ? 'Xem tất cả' : 'Xem sản phẩm' }}
                                </a>
                            </div>
                        </div>
                    @endforeach

                    @if ($categories->count() > 12)
                        <div class="hitem">
                            <button onclick="window.location.href='{{ route('categories.index') }}'"
                                class="w-full h-full min-h-[200px] flex flex-col items-center justify-center bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-2 border-dashed border-orange-400 text-orange-600">
                                <i class="fas fa-ellipsis-h text-3xl mb-2"></i>
                                <span class="font-semibold">Xem thêm danh mục</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- ================= Flash Sale ================= -->
    <section class="py-12 bg-yellow-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-[#ff6c2f]">⚡ FLASH SALE</h2>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-[#ff6c2f] font-semibold flex items-center">Xem
                        tất cả <i class="fas fa-arrow-right ml-2"></i></a>
                    <div class="flex items-center space-x-2 text-lg font-semibold">
                        <span>Kết thúc trong:</span>
                        <div class="bg-[#ff6c2f] text-white px-3 py-1 rounded min-w-[3rem] text-center" id="hours">00
                        </div>
                        <span>:</span>
                        <div class="bg-[#ff6c2f] text-white px-3 py-1 rounded min-w-[3rem] text-center" id="minutes">00
                        </div>
                        <span>:</span>
                        <div class="bg-[#ff6c2f] text-white px-3 py-1 rounded min-w-[3rem] text-center" id="seconds">00
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6" id="flash-sale-products">
                @if (!empty($flashSaleProducts) && count($flashSaleProducts) > 0)
                    @foreach ($flashSaleProducts as $product)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group prod-card"
                            onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                            <div class="relative img-wrap">
                                <div class="chip"><i class="fas fa-bolt"></i> -{{ $product->discount_percent ?? 0 }}%
                                </div>
                                <button class="wish-btn" data-id="{{ $product->id }}" title="Yêu thích"
                                    onclick="event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </button>
                                <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                    alt="{{ $product->name }}" loading="lazy" decoding="async"
                                    onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                                <div class="flex items-center justify-between mb-2">
                                    @php
                                        $variant = $product->variants->first();
                                    @endphp
                                    @if ($variant)
                                        <span
                                            class="text-lg font-bold text-[#ff6c2f]">{{ number_format($product->flash_sale_price ?? $variant->price) }}₫</span>
                                        @if ($product->flash_sale_price && $variant->price > $product->flash_sale_price)
                                            <span
                                                class="text-sm text-gray-500 line-through ml-2">{{ number_format($variant->price) }}₫</span>
                                        @endif
                                    @else
                                        <span class="text-lg font-bold text-[#ff6c2f]">Liên hệ</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 text-sm">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                    <span
                                        class="text-gray-500 text-sm ml-2">({{ $product->productComments->count() }})</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-5 text-center text-muted py-5">Hiện không có chương trình Flash Sale nào đang diễn
                        ra.</div>
                @endif
            </div>
        </div>
    </section>

    <!-- ================= Featured Products ================= -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold">Sản phẩm nổi bật</h2>
                <a href="{{ route('products.index') }}" class="text-[#ff6c2f] font-semibold flex items-center">Xem tất cả
                    <i class="fas fa-arrow-right ml-2"></i></a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach ($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group prod-card"
                        onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                        <div class="relative img-wrap">
                            @if ($product->flash_sale_price && $product->discount_percent > 0)
                                <div class="chip"><i class="fas fa-bolt"></i> -{{ $product->discount_percent }}%</div>
                            @endif
                            <button class="wish-btn" data-id="{{ $product->id }}" title="Yêu thích"
                                onclick="event.stopPropagation();">
                                <i class="far fa-heart"></i>
                            </button>
                            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                alt="{{ $product->name }}" loading="lazy" decoding="async"
                                onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mb-2">
                                @php $variant = $product->variants->first(); @endphp
                                @if ($variant)
                                    @if ($product->flash_sale_price && $variant->price > $product->flash_sale_price)
                                        <span
                                            class="text-lg font-bold text-[#ff6c2f]">{{ number_format($product->flash_sale_price) }}₫</span>
                                        <span
                                            class="text-sm text-gray-500 line-through ml-2">{{ number_format($variant->price) }}₫</span>
                                    @else
                                        <span
                                            class="text-lg font-bold text-[#ff6c2f]">{{ number_format($variant->price) }}₫</span>
                                    @endif
                                @else
                                    <span class="text-lg font-bold text-[#ff6c2f]">Liên hệ</span>
                                @endif
                            </div>
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <span class="text-gray-500 text-sm ml-2">({{ $product->productComments->count() }})</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ================= Sản phẩm hot ================= -->
    <!-- ================= Sản phẩm hot ================= -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-3xl font-bold">Sản phẩm hot</h3>
                <a href="{{ route('products.index') }}" class="text-[#ff6c2f] font-semibold flex items-center">Xem tất cả
                    <i class="fas fa-arrow-right ml-2"></i></a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach ($hotProducts as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group prod-card"
                        onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                        <div class="relative img-wrap">
                            <button class="wish-btn" data-id="{{ $product->id }}" title="Yêu thích"
                                onclick="event.stopPropagation();">
                                <i class="far fa-heart"></i>
                            </button>
                            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                alt="{{ $product->name }}" loading="lazy" decoding="async"
                                onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                        </div>
                        <div class="p-4" style="position: relative;">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mb-2">
                                @if ($product->type === 'simple' && $product->variants->count() > 0)
                                    @php $variant = $product->variants->first(); @endphp
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
                                        <span class="text-lg font-bold text-[#ff6c2f]">{{ number_format($minPrice) }} -
                                            {{ number_format($maxPrice) }}₫</span>
                                    @endif
                                @else
                                    <span class="text-lg font-bold text-[#ff6c2f]">Liên hệ</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-1">
                                    <span class="flex text-yellow-400 text-sm">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </span>
                                    <span class="text-gray-500 text-sm">({{ $product->productComments->count() }})</span>
                                </span>
                                <span class="flex items-center ml-4" style="gap: 4px; font-size: 17px; color: #6b7280;">
                                    <i class="fas fa-eye" style="color: #6b7280;"></i>
                                    <span
                                        style="font-size: 15px; color: #6b7280;">{{ number_format($product->view_count) }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    <!-- ================= Brand Section: CAROUSEL ================= -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Thương hiệu</h2>

            <div class="hslider brands-slider" data-slider>
                <button class="hnav prev" type="button" aria-label="Trước"><i class="fas fa-chevron-left"></i></button>
                <button class="hnav next" type="button" aria-label="Sau"><i class="fas fa-chevron-right"></i></button>

                <div class="htrack">
                    @foreach ($brands as $brand)
                        <div class="hitem">
                            <div
                                class="brand-card text-center group bg-white rounded-lg shadow-md hover:shadow-lg transition">
                                @if ($brand->image)
                                    <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}"
                                        class="mx-auto mb-3 object-cover rounded-lg" loading="lazy" decoding="async"
                                        onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}';">
                                @else
                                    <img src="{{ asset('client_css/images/brand-default.jpg') }}" alt="Brand default"
                                        class="mx-auto mb-3 object-cover rounded-lg" loading="lazy" decoding="async"
                                        onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}';">
                                @endif
                                <h3 class="font-semibold text-sm mb-1 line-clamp-1">{{ $brand->name }}</h3>

                                <a href="{{ route('brands.show', $brand->slug) }}"
                                    class="block text-[#ff6c2f] text-sm font-semibold hover:underline">
                                    Xem thương hiệu
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>



    <!-- ================= Bài viết mới ================= -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Bài viết mới</h2>
                    <a href="{{ route('client.news.index') }}"
                        class="inline-flex items-center text-[#ff6c2f] font-semibold hover:text-orange-600 transition-colors duration-200">
                        <span>Xem tất cả</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($latestNews as $item)
                        <article
                            class="group bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                            <a href="{{ route('client.news.show', $item->id) }}" class="block">
                                <!-- Image Container -->
                                <div class="relative h-48 overflow-hidden bg-gray-100">
                                    <img src="{{ asset($item->image ?? 'client_css/images/placeholder.svg') }}"
                                        alt="{{ $item->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>

                                <!-- Content -->
                                <div class="px-4 py-3">
                                    <h3
                                        class="font-semibold text-gray-800 text-sm leading-tight group-hover:text-[#ff6c2f] transition-colors duration-200 line-clamp-3">
                                        {{ $item->title }}
                                    </h3>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>


    <!-- ================= Dịch vụ ================= -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Thương hiệu đảm bảo -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="fas fa-shield-alt text-[#ff6c2f] text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Thương hiệu đảm bảo</h3>
                    <p class="text-gray-600 text-sm">Nhập khẩu, bảo hành chính hãng</p>
                </div>

                <!-- Card 2: Đổi trả dễ dàng -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="fas fa-exchange-alt text-[#ff6c2f] text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Đổi trả dễ dàng</h3>
                    <p class="text-gray-600 text-sm">Theo chính sách đổi trả tại TechViCom</p>
                </div>

                <!-- Card 3: Giao hàng tận nơi -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="fas fa-truck text-[#ff6c2f] text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Giao hàng tận nơi</h3>
                    <p class="text-gray-600 text-sm">Trên toàn hà nội</p>
                </div>

                <!-- Card 4: Sản phẩm chất lượng -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="fas fa-award text-[#ff6c2f] text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Sản phẩm chất lượng</h3>
                    <p class="text-gray-600 text-sm">Đảm bảo tương thích và độ bền cao</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // === Slideshow ===
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.slideshow-container');
            if (!slider) return;
            const slides = slider.querySelectorAll('.slide');
            const prevBtn = slider.querySelector('[data-slide="prev"]');
            const nextBtn = slider.querySelector('[data-slide="next"]');
            const indicators = slider.querySelectorAll('.indicator');
            let index = 0,
                timer = null;
            const DURATION = 5000;

            function go(to) {
                if (!slides.length) return;
                slides.forEach(s => s.classList.remove('active'));
                indicators.forEach(i => i.classList.remove('active'));
                index = (to + slides.length) % slides.length;
                slides[index].classList.add('active');
                if (indicators[index]) indicators[index].classList.add('active');
            }
            const next = () => go(index + 1);
            const prev = () => go(index - 1);
            const start = () => {
                stop();
                timer = setInterval(next, DURATION);
            }
            const stop = () => {
                if (timer) clearInterval(timer);
            }
            go(0);
            start();
            nextBtn && nextBtn.addEventListener('click', () => {
                stop();
                next();
                start();
            });
            prevBtn && prevBtn.addEventListener('click', () => {
                stop();
                prev();
                start();
            });
            indicators.forEach(i => i.addEventListener('click', () => {
                stop();
                go(parseInt(i.dataset.to, 10));
                start();
            }));
            slider.addEventListener('mouseenter', stop);
            slider.addEventListener('mouseleave', start);
        });

        // === Countdown Flash Sale ===
        (function() {
            const h = document.getElementById('hours'),
                m = document.getElementById('minutes'),
                s = document.getElementById('seconds');
            if (!h || !m || !s) return;
            @if (!empty($flashSaleEndTime))
                const end = new Date(@json($flashSaleEndTime));
            @else
                const end = null;
            @endif
            function updateCountdown() {
                if (!end) {
                    h.textContent = m.textContent = s.textContent = '00';
                    return;
                }
                const now = new Date();
                let left = Math.floor((end - now) / 1000);
                if (left < 0) left = 0;
                const hh = Math.floor(left / 3600);
                const mm = Math.floor((left % 3600) / 60);
                const ss = left % 60;
                h.textContent = String(hh).padStart(2, '0');
                m.textContent = String(mm).padStart(2, '0');
                s.textContent = String(ss).padStart(2, '0');
            }
            updateCountdown();
            setInterval(updateCountdown, 1000);
        })();

        // === Wishlist: toggle + persist localStorage ===
        (function() {
            const KEY = 'tv_wishlist_ids';
            const parse = () => {
                try {
                    return new Set(JSON.parse(localStorage.getItem(KEY) || '[]'));
                } catch {
                    return new Set();
                }
            };
            const save = (set) => localStorage.setItem(KEY, JSON.stringify([...set]));
            const liked = parse();

            document.querySelectorAll('.wish-btn[data-id]').forEach(btn => {
                const id = String(btn.dataset.id);
                const icon = btn.querySelector('i');
                const active = liked.has(id);
                btn.classList.toggle('active', active);
                if (icon) {
                    icon.classList.toggle('fas', active);
                    icon.classList.toggle('far', !active);
                }
                btn.addEventListener('click', () => {
                    const nowActive = btn.classList.toggle('active');
                    if (icon) {
                        icon.classList.toggle('fas', nowActive);
                        icon.classList.toggle('far', !nowActive);
                    }
                    if (nowActive) liked.add(id);
                    else liked.delete(id);
                    save(liked);
                });
            });
        })();

        // === Horizontal sliders controller (cho mọi .hslider) ===
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-slider]').forEach((root) => {
                const track = root.querySelector('.htrack');
                const prev = root.querySelector('.hnav.prev');
                const next = root.querySelector('.hnav.next');

                const getStep = () => {
                    const first = track.querySelector('.hitem');
                    if (!first) return 300;
                    const cs = getComputedStyle(track);
                    const gap = parseFloat(cs.columnGap || cs.gap || 0);
                    return first.getBoundingClientRect().width + gap;
                };

                const scrollByStep = (dir) => track.scrollBy({
                    left: dir * getStep(),
                    behavior: 'smooth'
                });

                prev && prev.addEventListener('click', () => scrollByStep(-1));
                next && next.addEventListener('click', () => scrollByStep(1));

                const update = () => {
                    const max = track.scrollWidth - track.clientWidth - 2;
                    const atStart = track.scrollLeft <= 0;
                    const atEnd = track.scrollLeft >= max;
                    if (prev) {
                        prev.disabled = atStart;
                        prev.style.opacity = atStart ? .4 : 1;
                    }
                    if (next) {
                        next.disabled = atEnd;
                        next.style.opacity = atEnd ? .4 : 1;
                    }
                };
                track.addEventListener('scroll', update, {
                    passive: true
                });
                window.addEventListener('resize', update);
                update();
            });
        });
    </script>
@endpush
