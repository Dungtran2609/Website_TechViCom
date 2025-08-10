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
            <div class="slide active" style="display: block;">
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
                @foreach ($flashSaleProducts ?? [] as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                        onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                        <div class="relative">
                            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-lg"
                                onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                            <div class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">
                                -{{ $product->discount_percent ?? 0 }}%
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
                                <span class="text-gray-500 text-sm ml-2">({{ $product->productComments->count() }})</span>
                            </div>
                            <div class="flex items-center justify-between">
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
                                        <span class="text-lg font-bold text-[#ff6c2f]">{{ number_format($minPrice) }} -
                                            {{ number_format($maxPrice) }}₫</span>
                                    @endif
                                @else
                                    <span class="text-lg font-bold text-[#ff6c2f]">Liên hệ</span>
                                @endif
                                <button
                                    onclick="event.stopPropagation(); addToCartStatic({{ $product->id }}, '{{ $product->name }}', 0, '{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}')"
                                    class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                @foreach ($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                        onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                        <div class="relative">
                            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-lg"
                                onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                            <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-sm font-bold">
                                NỔI BẬT
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
                                <span class="text-gray-500 text-sm ml-2">({{ $product->productComments->count() }})</span>
                            </div>
                            <div class="flex items-center justify-between">
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
                                        <span class="text-lg font-bold text-[#ff6c2f]">{{ number_format($minPrice) }} -
                                            {{ number_format($maxPrice) }}₫</span>
                                    @endif
                                @else
                                    <span class="text-lg font-bold text-[#ff6c2f]">Liên hệ</span>
                                @endif
                                <button
                                    onclick="event.stopPropagation(); addToCartStatic({{ $product->id }}, '{{ $product->name }}', 0, '{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}')"
                                    class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Hot Products -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold">Sản phẩm hot</h2>
                <a href="{{ route('products.index') }}"
                    class="text-[#ff6c2f] hover:hover:text-[#ff6c2f] font-semibold flex items-center">
                    Xem tất cả <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($hotProducts as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group"
                        onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                        <div class="relative">
                            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-lg"
                                onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                            <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded text-sm font-bold">
                                {{ $product->view_count }} lượt xem
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
                                <span class="text-gray-500 text-sm ml-2">({{ $product->productComments->count() }})</span>
                            </div>
                            <div class="flex items-center justify-between">
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
                                        <span class="text-lg font-bold text-[#ff6c2f]">{{ number_format($minPrice) }} -
                                            {{ number_format($maxPrice) }}₫</span>
                                    @endif
                                @else
                                    <span class="text-lg font-bold text-[#ff6c2f]">Liên hệ</span>
                                @endif
                                <button
                                    onclick="event.stopPropagation(); addToCartStatic({{ $product->id }}, '{{ $product->name }}', 0, '{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}')"
                                    class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Slideshow functionality
            document.addEventListener('DOMContentLoaded', function() {
                let currentSlideIndex = 0;
                const slides = document.querySelectorAll('.slide');
                const indicators = document.querySelectorAll('.indicator');
                const totalSlides = slides.length;

                function showSlide(index) {
                    slides.forEach(slide => {
                        slide.classList.remove('active');
                    });
                    indicators.forEach(indicator => {
                        indicator.classList.remove('active');
                    });
                    if (slides[index]) {
                        slides[index].classList.add('active');
                    }
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

                function autoSlide() {
                    nextSlide();
                }

                function initSlideshow() {
                    if (slides.length > 0) {
                        showSlide(0);
                        setInterval(autoSlide, 5000);
                    }
                }

                window.changeSlide = changeSlide;
                window.currentSlide = currentSlide;

                setTimeout(initSlideshow, 100);
            });
        </script>
    @endpush

@endsection
