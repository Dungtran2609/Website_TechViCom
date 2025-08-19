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

        /* Ảnh sản phẩm kiểu FPT: ô vuông, nét; phù hợp ảnh lớn/4K nếu có */
        .product-image-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 1/1;
            /* khung vuông */
            background: #fff;
            overflow: hidden;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* cắt đều */
            image-rendering: auto;
            /* hiển thị nét */
        }

        /* Trái tim yêu thích luôn hiện */
        .fav-btn {
            position: absolute;
            top: .5rem;
            right: .5rem;
            background: #fff;
            border-radius: 9999px;
            padding: .5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        /* Swiper arrows/pagination */
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

        /* Danh mục slider */
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

    <!-- Page Header with Banner Slider (thay ô tìm kiếm) -->
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
            <div class="rounded-2xl overflow-hidden shadow-lg">
                <div class="swiper banner-swiper">
                    <div class="swiper-wrapper">
                        @forelse(($banners ?? []) as $banner)
                            <div class="swiper-slide">
                                <img class="w-full h-[280px] md:h-[360px] lg:h-[420px] object-cover"
                                    src="{{ asset($banner->image_path) }}" alt="{{ $banner->title ?? 'Banner' }}"
                                    loading="eager" decoding="async" fetchpriority="high">
                            </div>
                        @empty
                            {{-- Fallback nếu chưa có banner trong DB --}}
                            <div class="swiper-slide"><img class="w-full h-[280px] md:h-[360px] lg:h-[420px] object-cover"
                                    src="{{ asset('client_css/images/banners/banner1.jpg') }}" alt="Banner 1"></div>
                            <div class="swiper-slide"><img class="w-full h-[280px] md:h-[360px] lg:h-[420px] object-cover"
                                    src="{{ asset('client_css/images/banners/banner2.jpg') }}" alt="Banner 2"></div>
                            <div class="swiper-slide"><img class="w-full h-[280px] md:h-[360px] lg:h-[420px] object-cover"
                                    src="{{ asset('client_css/images/banners/banner3.jpg') }}" alt="Banner 3"></div>
                        @endforelse
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Selection (Slider) -->
    <section class="bg-white py-4 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-3 mb-3">
                <h3 class="font-semibold text-gray-700">Danh mục:</h3>
            </div>
            <div class="swiper cat-swiper">
                <div class="swiper-wrapper items-center">
                    <div class="swiper-slide">
                        <button class="category-btn {{ !request('category') ? 'active' : '' }}" data-category="">Tất
                            cả</button>
                    </div>
                    @foreach ($categories as $category)
                        <div class="swiper-slide">
                            <button class="category-btn {{ request('category') == $category->slug ? 'active' : '' }}"
                                data-category="{{ $category->slug }}">{{ $category->name }}</button>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
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
                                <label class="flex items-center"><input type="checkbox" value="apple"
                                        class="mr-2 brand-filter"><span>Apple</span></label>
                                <label class="flex items-center"><input type="checkbox" value="samsung"
                                        class="mr-2 brand-filter"><span>Samsung</span></label>
                                <label class="flex items-center"><input type="checkbox" value="xiaomi"
                                        class="mr-2 brand-filter"><span>Xiaomi</span></label>
                                <label class="flex items-center"><input type="checkbox" value="oppo"
                                        class="mr-2 brand-filter"><span>OPPO</span></label>
                                <label class="flex items-center"><input type="checkbox" value="vivo"
                                        class="mr-2 brand-filter"><span>Vivo</span></label>
                                <label class="flex items-center"><input type="checkbox" value="huawei"
                                        class="mr-2 brand-filter"><span>Huawei</span></label>
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

                        <!-- Rating Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Đánh giá</h4>
                            <div class="space-y-2">
                                <label class="flex items-center"><input type="radio" name="rating" value=""
                                        class="mr-2 rating-filter" checked><span>Tất cả</span></label>
                                <label class="flex items-center">
                                    <input type="radio" name="rating" value="5" class="mr-2 rating-filter">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 text-sm mr-2"><i class="fas fa-star"></i><i
                                                class="fas fa-star"></i><i class="fas fa-star"></i><i
                                                class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                        <span>5 sao</span>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="rating" value="4" class="mr-2 rating-filter">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 text-sm mr-2"><i class="fas fa-star"></i><i
                                                class="fas fa-star"></i><i class="fas fa-star"></i><i
                                                class="fas fa-star"></i><i class="far fa-star"></i></div>
                                        <span>4 sao trở lên</span>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="rating" value="3" class="mr-2 rating-filter">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 text-sm mr-2"><i class="fas fa-star"></i><i
                                                class="fas fa-star"></i><i class="fas fa-star"></i><i
                                                class="far fa-star"></i><i class="far fa-star"></i></div>
                                        <span>3 sao trở lên</span>
                                    </div>
                                </label>
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
                            <span class="text-gray-700">Hiển thị {{ $products->count() }} trong tổng số
                                {{ $products->total() }} sản phẩm</span>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="products-grid">
                        @forelse($products as $product)
                            @php
                                // FIX: đảm bảo $activeVariants luôn tồn tại
                                $activeVariants = $product->variants ?? collect();
                                $minFilter = request('min_price');
                                $maxFilter = request('max_price');
                            @endphp

                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group product-card"
                                onclick="goToProductDetail({{ $product->id }})">
                                <div class="relative product-image-wrap">
                                    @php
                                        $thumb = $product->thumbnail
                                            ? asset('storage/' . $product->thumbnail)
                                            : asset('client_css/images/placeholder.svg');
                                    @endphp
                                    <img src="{{ $thumb }}" alt="{{ $product->name }}" class="product-image"
                                        loading="lazy" decoding="async"
                                        onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">

                                    {{-- Giảm giá --}}
                                    @if ($product->sale_price && $product->sale_price < $product->price)
                                        @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                                        <div
                                            class="absolute top-2 left-2 bg-[#ff6c2f] text-white px-2 py-1 rounded text-sm font-bold">
                                            -{{ $discount }}%</div>
                                    @endif

                                    {{-- Tim yêu thích cố định góc trên phải --}}
                                    <button onclick="event.stopPropagation()" class="fav-btn">
                                        <i class="fas fa-heart text-gray-400 hover:text-[#ff6c2f]"></i>
                                    </button>
                                </div>

                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>

                                    {{-- GIÁ (trước) + Lượt xem (phải) --}}
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            @php
                                                $displayPrice = null;

                                                if ($product->type === 'variable' && $activeVariants->count()) {
                                                    $filtered = $activeVariants->filter(function ($v) use (
                                                        $minFilter,
                                                        $maxFilter,
                                                    ) {
                                                        if ($minFilter && $v->price < $minFilter) {
                                                            return false;
                                                        }
                                                        if ($maxFilter && $v->price > $maxFilter) {
                                                            return false;
                                                        }
                                                        return true;
                                                    });
                                                    if ($filtered->count()) {
                                                        $minPrice = $filtered->min('price');
                                                        $maxPrice = $filtered->max('price');
                                                        $displayPrice =
                                                            $minPrice == $maxPrice
                                                                ? number_format($minPrice) . '₫'
                                                                : number_format($minPrice) .
                                                                    '₫ - ' .
                                                                    number_format($maxPrice) .
                                                                    '₫';
                                                    } else {
                                                        $displayPrice =
                                                            '<span class="text-lg font-bold text-[#ff6c2f]">0₫</span>';
                                                    }
                                                } else {
                                                    // simple
                                                    $variant = $activeVariants->first();
                                                    $price = $variant ? $variant->price : 0;
                                                    $sale_price =
                                                        $variant &&
                                                        $variant->sale_price &&
                                                        $variant->sale_price < $variant->price
                                                            ? $variant->sale_price
                                                            : null;
                                                    $priceToCheck = $sale_price ?? $price;
                                                    $show = true;
                                                    if ($minFilter && $priceToCheck < $minFilter) {
                                                        $show = false;
                                                    }
                                                    if ($maxFilter && $priceToCheck > $maxFilter) {
                                                        $show = false;
                                                    }
                                                    if ($show && $variant) {
                                                        if ($sale_price) {
                                                            $displayPrice =
                                                                '<span class="text-lg font-bold text-[#ff6c2f]">' .
                                                                number_format($sale_price) .
                                                                '₫</span>';
                                                            $displayPrice .=
                                                                '<span class="text-sm text-gray-500 line-through ml-2">' .
                                                                number_format($price) .
                                                                '₫</span>';
                                                        } else {
                                                            $displayPrice =
                                                                '<span class="text-lg font-bold text-[#ff6c2f]">' .
                                                                number_format($price) .
                                                                '₫</span>';
                                                        }
                                                    } else {
                                                        $displayPrice =
                                                            '<span class="text-lg font-bold text-[#ff6c2f]">0₫</span>';
                                                    }
                                                }
                                            @endphp
                                            {!! $displayPrice !!}
                                        </div>

                                        {{-- Lượt xem --}}
                                        <div class="flex items-center text-gray-500 text-sm">
                                            <i class="far fa-eye mr-1"></i>
                                            <span>{{ number_format($product->views ?? 0) }}</span>
                                        </div>
                                    </div>

                                    {{-- ĐÁNH GIÁ chuyển xuống dưới giá --}}
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 text-sm">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= ($product->rating ?? 4))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span
                                            class="text-gray-500 text-sm ml-2">({{ $product->productComments->count() }})</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div
                                    class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-600 mb-2">Không tìm thấy sản phẩm</h3>
                                <p class="text-gray-500">Hãy thử tìm kiếm với từ khóa khác hoặc thay đổi bộ lọc</p>
                            </div>
                        @endforelse

                        <!-- Pagination -->
                        @if ($products->hasPages())
                            <div class="mt-8">{{ $products->links() }}</div>
                        @endif
                    </div>
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
                        <img src="{{ asset('uploads/products/') }}brands/samsung.png" alt="Samsung"
                            class="mx-auto h-12"
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
                prevEl: '.banner-swiper .swiper-button-prev',
            }
        });

        // Category slider
        const catSwiper = new Swiper('.cat-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 12,
            freeMode: true,
            navigation: {
                nextEl: '.cat-swiper .swiper-button-next',
                prevEl: '.cat-swiper .swiper-button-prev',
            },
        });

        document.addEventListener('DOMContentLoaded', function() {
            // ====== Auto-check filters from URL ======
            const urlParams = new URLSearchParams(window.location.search);

            // Brand
            if (urlParams.has('brands')) {
                const brands = urlParams.get('brands').split(',');
                document.querySelectorAll('.brand-filter').forEach(cb => {
                    if (brands.includes(cb.value)) cb.checked = true;
                });
            }
            // RAM
            if (urlParams.has('ram')) {
                const rams = urlParams.get('ram').split(',');
                document.querySelectorAll('.ram-filter').forEach(cb => {
                    if (rams.includes(cb.value)) cb.checked = true;
                });
            }
            // Storage
            if (urlParams.has('storage')) {
                const storages = urlParams.get('storage').split(',');
                document.querySelectorAll('.storage-filter').forEach(cb => {
                    if (storages.includes(cb.value)) cb.checked = true;
                });
            }
            // Rating
            if (urlParams.has('rating')) {
                const rating = urlParams.get('rating');
                document.querySelectorAll('.rating-filter').forEach(cb => {
                    cb.checked = (cb.value === rating);
                });
            }
            // Price
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

            // ====== Category buttons functionality ======
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.dataset.category;
                    const url = new URL(window.location);
                    if (category) url.searchParams.set('category', category);
                    else url.searchParams.delete('category');
                    url.searchParams.delete('page'); // Reset page
                    window.location.href = url.toString();
                });
            });

            // ====== Sort functionality ======
            const sortFilter = document.getElementById('sort-filter');
            if (sortFilter) {
                sortFilter.addEventListener('change', function() {
                    const url = new URL(window.location);
                    if (this.value && this.value !== 'latest') url.searchParams.set('sort', this.value);
                    else url.searchParams.delete('sort');
                    url.searchParams.delete('page'); // Reset page
                    window.location.href = url.toString();
                });
            }

            // ====== Filter functionality ======
            document.querySelectorAll('.price-filter, .brand-filter, .ram-filter, .storage-filter, .rating-filter')
                .forEach(filter => {
                    filter.addEventListener('change', applyFilters);
                });

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

                // Price
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

                // Rating
                const ratingFilter = document.querySelector('input[name="rating"]:checked');
                if (ratingFilter && ratingFilter.value) url.searchParams.set('rating', ratingFilter.value);
                else url.searchParams.delete('rating');

                // Reset page
                url.searchParams.delete('page');

                window.location.href = url.toString();
            }
        });

        // Điều hướng tới chi tiết
        function goToProductDetail(productId) {
            window.location.href = `/products/${productId}`;
        }
    </script>
@endpush
