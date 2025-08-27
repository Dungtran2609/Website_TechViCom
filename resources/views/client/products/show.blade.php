@extends('client.layouts.app')

@section('title', isset($product) ? $product->name . ' - Techvicom' : 'Chi tiết sản phẩm - Techvicom')

@push('styles')
    <style>
        .pv-main {
            position: relative;
            width: 100%;
            aspect-ratio: 1/1;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
        }

        .pv-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            image-rendering: auto;
        }

        .pv-thumbs {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: 84px;
            gap: 10px;
            overflow-x: auto;
            padding: 8px 2px;
            -webkit-overflow-scrolling: touch;
        }

        .pv-thumb {
            position: relative;
            width: 84px;
            height: 84px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            transition: .15s;
            cursor: pointer;
        }

        .pv-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            image-rendering: auto;
        }

        .pv-thumb:hover {
            border-color: #ff6c2f;
        }

        .pv-thumb.active {
            border: 2px solid #ff6c2f;
            box-shadow: 0 0 0 2px rgba(255, 108, 47, .15) inset;
        }

        .pv-tabs {
            border-bottom: 1px solid #eee;
        }

        .pv-tab {
            padding: 14px 18px;
            font-weight: 600;
            color: #374151;
            position: relative;
        }

        .pv-tab.active {
            color: #111827;
        }

        .pv-tab.active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            height: 3px;
            background: #111827;
            border-radius: 2px;
        }

        /* ===== LONG DESC ===== */
        .pv-longdesc {
            line-height: 1.75;
        }

        /* Đồng bộ cỡ chữ trong phần mô tả */
        .pv-longdesc,
        .pv-longdesc * {
            font-size: 1rem !important;
            /* tất cả chữ bằng nhau */
            line-height: 1.75;
        }
        
        /* ===== VALIDATION STYLES ===== */
        .hidden {
            display: none !important;
        }
        
        .rating-error, .content-error, .reply-content-error {
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: #ff6c2f;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #e55a1f;
            transform: translateY(-1px);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }

        /* ===== PRODUCT CARD STYLES ===== */
        .rp-card {
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .rp-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .rp-card .aspect-square {
            aspect-ratio: 1/1;
            overflow: hidden;
        }

        .rp-card img {
            transition: all 0.3s ease;
        }

        .rp-card:hover img {
            transform: scale(1.05);
        }

        .rp-like {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.2s ease;
        }

        .rp-like:hover {
            background: #ff6c2f;
            color: white;
        }

        .rp-like i.fas {
            color: #ff6c2f;
        }

        .pv-longdesc h2,
        .pv-longdesc h3 {
            font-weight: 600;
            margin-top: 1.25rem;
            margin-bottom: .75rem;
        }

        .pv-longdesc p {
            color: #374151;
            margin: .5rem 0 1rem;
        }

        .pv-longdesc ul {
            list-style: none;
            padding: 0;
            margin: 0 0 1rem;
        }

        .pv-longdesc ul li {
            display: flex;
            gap: .5rem;
            align-items: flex-start;
            color: #374151;
            margin: .35rem 0;
        }

        .pv-longdesc ul li:before {
            content: "";
            width: .45rem;
            height: .45rem;
            margin-top: .45rem;
            background: #ff6c2f;
            border-radius: 9999px;
            flex-shrink: 0;
        }

        .pv-longdesc table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin: 1rem 0;
        }

        .pv-longdesc td,
        .pv-longdesc th {
            padding: .75rem .9rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }

        .pv-longdesc tr:nth-child(2n) {
            background: #fafafa;
        }

        .pv-longdesc img {
            display: block;
            margin: 10px auto;
            max-width: 100%;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .05);
        }

        /* Thu gọn / Đọc thêm */
        .pv-desc-collapsed {
            max-height: 480px;
            /* chiều cao hiển thị ban đầu */
            overflow: hidden;
            position: relative;
            transition: max-height .35s ease;
        }

        .pv-desc-collapsed::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 80px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, #ffffff 70%);
        }

        .pv-desc-expanded {
            max-height: none;
        }

        .rp-card {
            position: relative;
        }

        .rp-like {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
            transition: .15s;
            cursor: pointer;
            z-index: 2;
        }

        .rp-like:hover {
            border-color: #ff6c2f;
        }

        .rp-like.active {
            background: #ffefea;
            border-color: #ff6c2f;
        }

        .rp-like i {
            color: #9ca3af;
        }

        .rp-like.active i {
            color: #ff6c2f;
        }

        .btn-primary {
            background: #ff6c2f;
            color: #fff;
        }

        .btn-primary:hover {
            background: #e55a28;
        }

        .toast {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 60;
            padding: 10px 14px;
            border-radius: 10px;
            color: #fff;
            background: #111827;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .15);
            opacity: 0;
            transform: translateY(-8px);
            transition: .25s;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .variant-disabled {
            opacity: .35;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(25%);
        }

        .opt-selected {
            position: relative;
        }

        .opt-selected::after {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: -6px;
            top: -6px;
            width: 18px;
            height: 18px;
            border-radius: 9999px;
            background: #10b981;
            color: #fff;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
        }

        /* Đảm bảo layout ổn định cho quantity và buttons */
        .quantity-container {
            min-width: 120px;
            flex-shrink: 0;
        }

        .action-buttons {
            min-width: 200px;
            flex-shrink: 0;
        }

        #qty {
            min-width: 64px;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    @if (isset($product))
        @php $activeVariants = $product->variants->where('is_active', true); @endphp

        <nav class="bg-white border-b border-gray-200 py-3">
            <div class="container mx-auto px-4">
                <div class="flex items-center space-x-2 text-sm">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-[#ff6c2f]">Sản phẩm</a>
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <span class="text-gray-900 font-medium">{{ $product->name }}</span>
                </div>
            </div>
        </nav>

        <section class="py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- LEFT: GALLERY --}}
                    <div>
                        <div class="pv-main">
                            <img id="pv-main-img"
                                src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                alt="{{ $product->name }}"
                                onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                        </div>
                        <div class="mt-3 pv-thumbs" id="pv-thumbs">
                            @php $allImgs = $product->productAllImages; @endphp
                            @if ($allImgs->count() === 0)
                                <button type="button" class="pv-thumb active">
                                    <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                        alt="thumb" loading="lazy" decoding="async">
                                </button>
                            @else
                                @foreach ($allImgs as $idx => $image)
                                    <button type="button" class="pv-thumb {{ $idx === 0 ? 'active' : '' }}"
                                        data-src="{{ asset('storage/' . $image->image_path) }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Ảnh phụ"
                                            loading="lazy" decoding="async"
                                            onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- RIGHT: INFO --}}
                    <div class="flex flex-col space-y-5">
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">{{ $product->name }}</h1>

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                            @if ($product->brand)
                                <span class="text-gray-600"><i class="fas fa-tag mr-2 text-gray-400"></i>Thương hiệu:
                                    <b class="text-blue-600">{{ $product->brand->name }}</b></span>
                            @endif
                            @if ($product->category)
                                <span class="text-gray-600"><i class="fas fa-folder-open mr-2 text-gray-400"></i>Danh mục:
                                    <b>{{ $product->category->name }}</b></span>
                            @endif
                        </div>

                        @if ($product->short_description)
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg text-gray-700">
                                {{ $product->short_description }}</div>
                        @endif

                        {{-- PRICE --}}
                        <div id="price-display" class="text-4xl font-bold text-[#ff6c2f]">
                            @if (!empty($flashSaleInfo))
                                @php
                                    $v = $activeVariants->first();
                                    $originalPrice = $v ? $v->price : null;
                                    $salePrice = $flashSaleInfo['sale_price'] ?? null;
                                @endphp
                                @if ($salePrice && $originalPrice && $salePrice < $originalPrice)
                                    <div class="flex items-end gap-3">
                                        <span class="text-2xl line-through text-gray-500">{{ number_format($originalPrice, 0, ',', '.') }}₫</span>
                                        <span>{{ number_format($salePrice, 0, ',', '.') }}₫</span>
                                    </div>
                                @elseif($salePrice && $salePrice > 0)
                                    <span>{{ number_format($salePrice, 0, ',', '.') }}₫</span>
                                @elseif($originalPrice)
                                    {{ number_format($originalPrice, 0, ',', '.') }}₫
                                @else
                                    <span class="text-3xl text-gray-500">Tạm hết hàng</span>
                                @endif
                            @elseif ($product->type === 'variable' && $activeVariants->isNotEmpty())
                                @php
                                    $minPrice = $activeVariants->min('price');
                                    $maxPrice = $activeVariants->max('price');
                                @endphp
                                {!! $minPrice === $maxPrice
                                    ? number_format($minPrice, 0, ',', '.') . '₫'
                                    : number_format($minPrice, 0, ',', '.') . '₫ - ' . number_format($maxPrice, 0, ',', '.') . '₫' !!}
                            @elseif ($product->type === 'simple' && $activeVariants->isNotEmpty())
                                @php $v=$activeVariants->first(); @endphp
                                @if ($v->sale_price && $v->sale_price < $v->price)
                                    <div class="flex items-end gap-3">
                                        <span
                                            class="text-2xl line-through text-gray-500">{{ number_format($v->price, 0, ',', '.') }}₫</span>
                                        <span>{{ number_format($v->sale_price, 0, ',', '.') }}₫</span>
                                    </div>
                                @else
                                    {{ number_format($v->price, 0, ',', '.') }}₫
                                @endif
                            @else
                                <span class="text-3xl text-gray-500">Tạm hết hàng</span>
                            @endif
                        </div>

                        <div id="info-container" class="flex flex-wrap items-center gap-3 text-sm min-h-[2rem]"></div>

                        {{-- VARIANTS --}}
                        @if ($product->type === 'variable' && $activeVariants->isNotEmpty())
                            @php
                                $attributesData = $activeVariants
                                    ->flatMap(fn($v) => $v->attributeValues)
                                    ->groupBy('attribute.name')
                                    ->map(fn($vals) => $vals->unique('value')->sortBy('value')->values());
                            @endphp
                            <form id="variant-form" class="space-y-4">
                                @foreach ($attributesData as $name => $attributeValues)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-800 mb-2">
                                            {{ $name }}: <span class="text-gray-500 font-normal"
                                                data-variant-name-display="{{ $name }}"></span>
                                        </label>
                                        <div class="flex flex-wrap gap-2 items-center attribute-options"
                                            data-attribute-name="{{ $name }}">
                                            @foreach ($attributeValues as $av)
                                                @if ((str_contains(strtolower($name), 'màu') || str_contains(strtolower($name), 'color')) && $av->color_code)
                                                    <button type="button"
                                                        class="variant-option-button w-8 h-8 rounded-full border border-gray-300"
                                                        style="background-color: {{ $av->color_code }}"
                                                        title="{{ $av->value }}"
                                                        data-attribute-name="{{ $name }}"
                                                        data-attribute-value="{{ $av->value }}"></button>
                                                @else
                                                    <button type="button"
                                                        class="variant-option-button px-3 py-1.5 border border-gray-300 rounded-lg text-sm hover:border-[#ff6c2f]"
                                                        data-attribute-name="{{ $name }}"
                                                        data-attribute-value="{{ $av->value }}">{{ $av->value }}</button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </form>
                        @endif

                        {{-- Quantity + Actions --}}
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-medium">Số lượng:</label>
                            <div class="flex items-center quantity-container">
                                <button type="button" id="quantity-minus-btn"
                                    class="w-8 h-8 border border-gray-300 rounded-l-lg hover:bg-gray-100">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input id="qty" type="number" value="1" min="1"
                                    class="w-16 h-8 text-center border-t border-b border-gray-300 focus:outline-none"
                                    readonly>
                                <button type="button" id="quantity-plus-btn"
                                    class="w-8 h-8 border border-gray-300 rounded-r-lg hover:bg-gray-100">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3 action-buttons">
                            <button id="btn-add-cart" type="button"
                                class="w-full min-w-[200px] btn-primary py-3 px-4 rounded-lg font-bold disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0">
                                <i class="fas fa-shopping-cart mr-2"></i> Thêm vào giỏ hàng
                            </button>
                            <button id="btn-buy-now" type="button"
                                class="w-full min-w-[200px] bg-gray-800 text-white py-3 px-4 rounded-lg font-bold hover:bg-black disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0">
                                Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- TABS: Mô tả | Đánh giá --}}
        <section class="py-10 bg-white">
            <div class="container mx-auto px-4 max-w-6xl">
                <div class="pv-tabs flex gap-2">
                    <button class="pv-tab active" data-tab="desc">Mô tả</button>
                    <button class="pv-tab" data-tab="review">Đánh giá</button>
                </div>

                <div id="tab-desc" class="pt-5">
                    @if (!empty($product->long_description))
                        <div id="product-description" class="pv-longdesc pv-desc-collapsed">{!! $product->long_description !!}</div>
                        <div class="flex justify-center">
                            <button id="toggle-desc"
                                class="mt-3 px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-sm font-medium">
                                Đọc thêm
                            </button>
                        </div>
                    @elseif (!empty($product->description))
                        <div id="product-description" class="pv-longdesc pv-desc-collapsed">{!! nl2br(e($product->description)) !!}</div>
                        <div class="flex justify-center">
                            <button id="toggle-desc"
                                class="mt-3 px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-sm font-medium">
                                Đọc thêm
                            </button>
                        </div>
                    @else
                        <div class="text-gray-500">Chưa có mô tả cho sản phẩm này.</div>
                    @endif
                </div>

                <div id="tab-review" class="pt-5 hidden">
                    @php
                        $approvedComments = $product
                            ->productComments()
                            ->where('status', 'approved')
                            ->where('is_hidden', false)
                            ->whereNull('parent_id')
                            ->with(['user', 'replies.user', 'order'])
                            ->latest()
                            ->get();
                    @endphp

                    @auth
                        @php
                            $reviewStatus = \App\Helpers\CommentHelper::getReviewStatus($product->id);
                            $remainingDays = \App\Helpers\CommentHelper::getRemainingDaysToReview($product->id);
                            $purchasedItems = \App\Helpers\CommentHelper::getPurchasedItemsGrouped($product->id);
                        @endphp
                        @if ($reviewStatus['can_review'])
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-semibold text-gray-900">Viết đánh giá của bạn</h3>
                                    <div class="text-sm text-orange-600 bg-orange-50 px-3 py-1 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>
                                        Còn {{ $remainingDays }} ngày để đánh giá
                                    </div>
                                </div>
                                
                                <!-- Hiển thị form đánh giá cho từng đơn hàng (gom nhóm biến thể) -->
                                @if($purchasedItems->count() > 0)
                                    <div class="space-y-6">
                                        @foreach($purchasedItems as $groupedItem)
                                            <div class="bg-white rounded-lg border border-gray-200 p-5">
                                                <!-- Thông tin đơn hàng -->
                                                <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <h5 class="font-semibold text-blue-900">
                                                            <i class="fas fa-shopping-cart mr-2"></i>
                                                            Đơn hàng #{{ $groupedItem->order->order_number }}
                                                        </h5>
                                                        <span class="text-sm text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                                            {{ $groupedItem->items->count() }} biến thể
                                                        </span>
                                                    </div>

                                                    <p class="text-sm text-blue-700">
                                                        <i class="fas fa-boxes mr-1"></i>
                                                        Tổng số lượng: {{ $groupedItem->total_quantity }} | 
                                                        <i class="fas fa-money-bill mr-1"></i>
                                                        Tổng giá: {{ number_format($groupedItem->total_price) }}₫
                                                    </p>
                                                    @if($groupedItem->remaining_days > 0)
                                                        <p class="text-xs text-[#ff6c2f] font-medium mt-2">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Còn {{ $groupedItem->remaining_days }} ngày để đánh giá
                                                        </p>
                                                    @endif
                                                </div>

                                                <!-- Danh sách các biến thể trong đơn hàng -->
                                                <div class="mb-4 space-y-3">
                                                    <h6 class="font-medium text-gray-700 mb-2">Các biến thể đã mua:</h6>
                                                    @foreach($groupedItem->items as $item)
                                                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                                                @if($item->productVariant && $item->productVariant->image)
                                                                    <img src="{{ asset('storage/' . ltrim($item->productVariant->image, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                                                @elseif($item->image_product)
                                                                    <img src="{{ asset('storage/' . ltrim($item->image_product, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                                                @elseif($item->productVariant && $item->productVariant->product && $item->productVariant->product->thumbnail)
                                                                    <img src="{{ asset('storage/' . ltrim($item->productVariant->product->thumbnail, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                                                @else
                                                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                                                        <i class="fas fa-image text-sm"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <h6 class="font-medium text-gray-900 text-sm truncate">{{ $item->name_product }}</h6>
                                                                @if($item->productVariant)
                                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                                        @foreach($item->productVariant->attributeValues as $attrValue)
                                                                            <span class="inline-block bg-white border border-gray-200 px-2 py-1 rounded text-xs text-gray-600">
                                                                                {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                                <p class="text-xs text-gray-500 mt-1">
                                                                    Số lượng: {{ $item->quantity }} | Giá: {{ number_format($item->price) }}₫
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <!-- Form đánh giá cho đơn hàng này -->
                                                @if($groupedItem->can_review)
                                                    <form action="{{ route('products.comments.store', $product->id) }}" method="POST" class="order-review-form" onsubmit="console.log('Form submitted');">
                                                        @csrf
                                                        <input type="hidden" name="order_id" value="{{ $groupedItem->order->id }}">
                                                        
                                                        <div class="mb-3">
                                                            <label class="block text-sm font-medium mb-1">Đánh giá <span class="text-red-500">*</span></label>
                                                            <div class="flex items-center space-x-1">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <input type="radio" id="star{{ $groupedItem->order->id }}_{{ $i }}" name="rating"
                                                                        value="{{ $i }}" class="sr-only">
                                                                    <label for="star{{ $groupedItem->order->id }}_{{ $i }}"
                                                                        class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400">
                                                                        <i class="fas fa-star"></i>
                                                                    </label>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="block text-sm font-medium mb-1">Nội dung <span class="text-red-500">*</span></label>
                                                            <textarea name="content" rows="4" maxlength="500"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#ff6c2f]" 
                                                                placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                <span class="charCount">0</span>/500 ký tự
                                                            </div>
                                                        </div>
                                                        
                                                        <button type="submit" class="btn-primary px-5 py-2 rounded-md">
                                                            <i class="fas fa-paper-plane mr-2"></i>Gửi đánh giá cho đơn hàng #{{ $groupedItem->order->order_number }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="text-center py-4 text-gray-500">
                                                        <i class="fas fa-check-circle text-2xl mb-2 text-green-500"></i>
                                                        <p>Bạn đã đánh giá đơn hàng này rồi hoặc đã hết thời gian đánh giá</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <div class="flex items-start gap-3">
                                    <div class="text-blue-600 mt-1">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <b class="text-blue-800">Thông báo</b>
                                        <p class="text-blue-700 text-sm mt-1">{{ $reviewStatus['message'] }}</p>
                                        @if ($remainingDays > 0 && $remainingDays <= 15)
                                            <p class="text-orange-600 text-sm mt-1">
                                                <i class="fas fa-clock mr-1"></i>
                                                Còn {{ $remainingDays }} ngày để đánh giá
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <div class="text-blue-600 mt-1">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <b class="text-blue-800">Thông báo</b>
                                    <p class="text-blue-700 text-sm mt-1">Bạn cần đăng nhập để đánh giá sản phẩm.</p>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <!-- Rating Filter Buttons -->
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-2">
                            <button class="rating-filter-btn px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors" data-rating="all">
                                Tất cả
                            </button>
                            @for ($i = 5; $i >= 1; $i--)
                                <button class="rating-filter-btn px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors" data-rating="{{ $i }}">
                                    {{ $i }} ☆
                                </button>
                            @endfor
                        </div>
                    </div>

                    <div id="comments-container" class="space-y-5">
                        @forelse($approvedComments as $cmt)
                            @if($cmt->user)
                            <div class="bg-white rounded-lg border border-gray-200 p-5">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-[#ff6c2f] text-white flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($cmt->user->name ?? 'K', 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <b class="text-gray-900">{{ $cmt->user->name ?? 'Khách vãng lai' }}</b>
                                            <span
                                                class="text-gray-500 text-sm">{{ $cmt->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        @if ($cmt->rating)
                                            <div class="text-yellow-400 mb-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fas fa-star {{ $i <= $cmt->rating ? '' : 'text-gray-300' }}"></i>
                                                @endfor
                                                <span class="text-sm text-gray-600 ml-1">{{ $cmt->rating }}/5</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Hiển thị thông tin sản phẩm đã mua -->
                                        @if($cmt->order)
                                            @php
                                                $orderItems = \App\Models\OrderItem::where('order_id', $cmt->order->id)
                                                    ->where('product_id', $product->id)
                                                    ->with(['productVariant.attributeValues.attribute', 'order'])
                                                    ->get();
                                            @endphp
                                            @if($orderItems->count() > 0)
                                                <div class="mb-3">
                                                    <div class="text-xs text-gray-500 mb-2 font-medium">Sản phẩm đã mua trong đơn hàng #{{ $orderItems->first()->order->order_number ?? 'N/A' }}:</div>
                                                    @foreach($orderItems as $orderItem)
                                                        <div class="mb-2 p-3 bg-gray-50 rounded-lg border-l-4 border-[#ff6c2f]">
                                                            <div class="flex items-center gap-3">
                                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                                                    @if($orderItem->productVariant && $orderItem->productVariant->image)
                                                                        <img src="{{ asset('storage/' . ltrim($orderItem->productVariant->image, '/')) }}" alt="{{ $orderItem->name_product }}" class="w-full h-full object-cover">
                                                                    @elseif($orderItem->image_product)
                                                                        <img src="{{ asset('storage/' . ltrim($orderItem->image_product, '/')) }}" alt="{{ $orderItem->name_product }}" class="w-full h-full object-cover">
                                                                    @elseif($orderItem->productVariant && $orderItem->productVariant->product && $orderItem->productVariant->product->thumbnail)
                                                                        <img src="{{ asset('storage/' . ltrim($orderItem->productVariant->product->thumbnail, '/')) }}" alt="{{ $orderItem->name_product }}" class="w-full h-full object-cover">
                                                                    @else
                                                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                                                            <span class="text-xs">IMG</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="flex-1">
                                                                    <h6 class="font-medium text-gray-900 text-sm">{{ $orderItem->name_product }}</h6>
                                                                    @if($orderItem->productVariant && $orderItem->productVariant->attributeValues->count() > 0)
                                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                                            @foreach($orderItem->productVariant->attributeValues as $attrValue)
                                                                                <span class="inline-block bg-white border border-gray-200 px-2 py-1 rounded text-xs text-gray-700">
                                                                                    {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                                                                                </span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                    <div class="flex items-center justify-between mt-1">
                                                                        <span class="text-xs text-gray-500">
                                                                            Số lượng: {{ $orderItem->quantity }}
                                                                        </span>
                                                                        <span class="text-xs text-gray-500">
                                                                            Giá: {{ number_format($orderItem->price, 0, ',', '.') }}đ
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                        
                                        <div class="mb-3 p-3 bg-gray-50 rounded-lg border-l-4 border-gray-200">
                                            <h6 class="text-sm font-medium text-gray-700 mb-2">Nội dung đánh giá:</h6>
                                            @php
                                                // Xử lý nội dung comment
                                                $content = $cmt->content;
                                                
                                                // Nếu nội dung chứa form data, trích xuất phần content
                                                if (strpos($content, '&content=') !== false) {
                                                    // Tìm và trích xuất nội dung sau &content=
                                                    if (preg_match('/&content=([^&]*)/', $content, $matches)) {
                                                        $cleanContent = urldecode($matches[1]);
                                                    } else {
                                                        $cleanContent = $content;
                                                    }
                                                } else {
                                                    // Nếu không có form data, chỉ loại bỏ token
                                                    $cleanContent = preg_replace('/_token=[^&\s]*/', '', $content);
                                                    $cleanContent = preg_replace('/&order_id=[^&\s]*/', '', $cleanContent);
                                                    $cleanContent = preg_replace('/&rating=[^&\s]*/', '', $cleanContent);
                                                }
                                                
                                                $cleanContent = trim($cleanContent);
                                            @endphp
                                            @if(!empty($cleanContent))
                                                <p class="text-gray-800 text-sm leading-relaxed">{{ $cleanContent }}</p>
                                            @else
                                                <p class="text-gray-500 text-sm italic">Không có nội dung đánh giá</p>
                                                @if(config('app.debug'))
                                                    <details class="mt-2 text-xs text-gray-400">
                                                        <summary>Debug: Nội dung gốc</summary>
                                                        <pre class="mt-1 p-2 bg-gray-100 rounded text-xs overflow-auto">{{ $cmt->content }}</pre>
                                                    </details>
                                                @endif
                                            @endif
                                        </div>

                                        @if ($cmt->replies->count() > 0)
                                            <div class="mt-3 space-y-3">
                                                @foreach ($cmt->replies as $rep)
                                                    @if ($rep->status === 'approved' && !$rep->is_hidden && $rep->user)
                                                        <div class="bg-white rounded-md p-3 ml-4 border border-gray-200">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <b class="text-sm text-gray-800">{{ $rep->user->name ?? 'Khách vãng lai' }}</b>
                                                                <span
                                                                    class="text-xs text-gray-500">{{ $rep->created_at->format('d/m/Y H:i') }}</span>
                                                            </div>
                                                            @php
                                                                // Xử lý nội dung reply
                                                                $replyContent = $rep->content;
                                                                
                                                                // Nếu nội dung chứa form data, trích xuất phần content
                                                                if (strpos($replyContent, '&content=') !== false) {
                                                                    // Tìm và trích xuất nội dung sau &content=
                                                                    if (preg_match('/&content=([^&]*)/', $replyContent, $matches)) {
                                                                        $cleanReplyContent = urldecode($matches[1]);
                                                                    } else {
                                                                        $cleanReplyContent = $replyContent;
                                                                    }
                                                                } else {
                                                                    // Nếu không có form data, chỉ loại bỏ token
                                                                    $cleanReplyContent = preg_replace('/_token=[^&\s]*/', '', $replyContent);
                                                                    $cleanReplyContent = preg_replace('/&order_id=[^&\s]*/', '', $cleanReplyContent);
                                                                    $cleanReplyContent = preg_replace('/&rating=[^&\s]*/', '', $cleanReplyContent);
                                                                }
                                                                
                                                                $cleanReplyContent = trim($cleanReplyContent);
                                                            @endphp
                                                            @if(!empty($cleanReplyContent))
                                                                <div class="text-sm text-gray-700 leading-relaxed">{{ $cleanReplyContent }}</div>
                                                            @else
                                                                <div class="text-gray-500 text-sm italic">Không có nội dung phản hồi</div>
                                                                @if(config('app.debug'))
                                                                    <details class="mt-2 text-xs text-gray-400">
                                                                        <summary>Debug: Nội dung gốc</summary>
                                                                        <pre class="mt-1 p-2 bg-gray-100 rounded text-xs overflow-auto">{{ $rep->content }}</pre>
                                                                    </details>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <!-- Form reply comment -->
                                        @auth
                                            @if(\App\Helpers\CommentHelper::canReply($product->id))
                                                <div class="mt-3">
                                                    <button type="button" class="text-sm text-[#ff6c2f] hover:text-[#e55a1f] transition-colors" onclick="toggleReplyForm({{ $cmt->id }})">
                                                        <i class="fas fa-reply mr-1"></i>Phản hồi
                                                    </button>
                                                    
                                                    <div id="replyForm{{ $cmt->id }}" class="hidden mt-3 ml-4">
                                                        <form action="{{ route('products.comments.reply', ['productId' => $product->id, 'commentId' => $cmt->id]) }}" method="POST" class="reply-form" onsubmit="return validateReplyForm(this)">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <textarea name="reply_content" rows="3" maxlength="200" 
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#ff6c2f] resize-none" 
                                                                    placeholder="Viết phản hồi của bạn..."></textarea>
                                                                <div class="text-xs text-gray-500 mt-1">
                                                                    <span class="replyCharCount">0</span>/200 ký tự
                                                                </div>
                                                                <div class="reply-content-error text-red-500 text-xs mt-1 hidden"></div>
                                                            </div>
                                                            
                                                            <div class="flex gap-2">
                                                                <button type="submit" class="btn-primary px-4 py-2 rounded-md text-sm">
                                                                    <i class="fas fa-paper-plane mr-1"></i>Gửi phản hồi
                                                                </button>
                                                                <button type="button" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors" onclick="toggleReplyForm({{ $cmt->id }})">
                                                                    Hủy
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <div class="bg-white rounded-lg border border-gray-200 p-6 text-center text-gray-500">Chưa có
                                bình luận nào.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        @if (isset($relatedProducts) && $relatedProducts->count() > 0)
            <section class="py-8 bg-gray-50">
                <div class="container mx-auto px-4">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Sản phẩm liên quan</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $rp)
                            <a href="{{ route('products.show', $rp->id) }}"
                                class="rp-card bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow transition">
                                <button type="button" class="rp-like favorite-once" data-product-id="{{ $rp->id }}" title="Yêu thích" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="{{ in_array($rp->id, $favoriteProductIds ?? []) ? 'fas' : 'far' }} fa-heart"></i>
                                </button>
                                <div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $rp->thumbnail ? asset('storage/' . $rp->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                        alt="{{ $rp->name }}" class="w-full h-full object-cover"
                                        onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">{{ $rp->name }}</h3>
                                    
                                    <div class="flex items-center mb-2">
                                        @php
                                            $avgRating = $rp->comments()->where('status', 'approved')->avg('rating') ?? 0;
                                            $reviewCount = $rp->comments()->where('status', 'approved')->count();
                                            $viewCount = $rp->view_count ?? 0;
                                        @endphp
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($avgRating))
                                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                @elseif ($i - 0.5 <= $avgRating)
                                                    <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300 text-sm"></i>
                                                @endif
                                            @endfor
                                            <span class="text-xs text-gray-500 ml-1">({{ $reviewCount }})</span>
                                        </div>
                                        @if($viewCount > 0)
                                            <div class="flex items-center ml-auto text-xs text-gray-500">
                                                <i class="far fa-eye mr-1"></i>
                                                {{ number_format($viewCount) }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <p class="text-[#ff6c2f] font-bold">
                                        @if ($rp->type === 'simple' && $rp->variants->count() > 0)
                                            {{ number_format($rp->variants->first()->price) }}₫
                                        @elseif($rp->type === 'variable' && $rp->variants->count() > 0)
                                            @php
                                                $min = $rp->variants->min('price');
                                                $max = $rp->variants->max('price');
                                            @endphp
                                            {!! $min === $max ? number_format($min) . '₫' : number_format($min) . ' - ' . number_format($max) . '₫' !!}
                                        @else
                                            Liên hệ
                                        @endif
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @else
        <section class="py-16">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Sản phẩm không tìm thấy</h1>
                <p class="text-gray-600 mb-6">Sản phẩm bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center btn-primary px-6 py-3 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách sản phẩm
                </a>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        /* ===== Server data ===== */
        @php
            $jsProductData = [
                'type' => $product->type ?? null,
                'variants' => $activeVariants
                    ->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'price' => $v->price,
                            'sale_price' => $v->sale_price,
                            'stock' => $v->stock,
                            'sku' => $v->sku,
                            'image' => $v->image ? asset('storage/' . $v->image) : null,
                            'attributes' => $v->attributeValues->pluck('value', 'attribute.name'),
                        ];
                    })
                    ->values(),
                'is_featured' => $product->is_featured ?? false,
                'thumbnail' => $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg'),
                'id' => $product->id ?? null,
            ];
        @endphp
        const productData = @json($jsProductData);

        /* ===== Helpers ===== */
        
        // Function để xử lý nội dung comment
        function processCommentContent(content) {
            if (!content) return '';
            
            // Nếu nội dung chứa form data, trích xuất phần content
            if (content.includes('&content=')) {
                const match = content.match(/&content=([^&]*)/);
                if (match) {
                    return decodeURIComponent(match[1]);
                }
            }
            
            // Nếu không có form data, chỉ loại bỏ token
            return content
                .replace(/_token=[^&\s]*/g, '')
                .replace(/&order_id=[^&\s]*/g, '')
                .replace(/&rating=[^&\s]*/g, '')
                .trim();
        }
        const el = s => document.querySelector(s);
        const els = s => Array.from(document.querySelectorAll(s));
        const VND = n => (Number(n) || 0).toLocaleString('vi-VN') + '₫';
        const getCsrf = () => (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || '';
        const toast = (m, t = 'info') => {
            const n = document.createElement('div');
            n.className = 'toast ' + (t === 'error' ? 'bg-red-600' : t === 'success' ? 'bg-emerald-600' :
            'bg-gray-900');
            n.textContent = m;
            document.body.appendChild(n);
            requestAnimationFrame(() => n.classList.add('show'));
            setTimeout(() => {
                n.classList.remove('show');
                setTimeout(() => n.remove(), 250)
            }, 2500);
        };
        const norm = v => (v == null ? '' : String(v)).trim().toLowerCase();
        const canon = v => norm(v).replace(/\s+/g, '');
        const cmp = (a, b) => canon(a) === canon(b);
        const cssEsc = s => (window.CSS && CSS.escape) ? CSS.escape(s) : s.replace(/["\\]/g, '\\$&');

        /* ===== Gallery ===== */
        const mainImg = document.getElementById('pv-main-img');
        document.getElementById('pv-thumbs')?.addEventListener('click', e => {
            const btn = e.target.closest('.pv-thumb');
            if (!btn) return;
            const src = btn.dataset.src || btn.querySelector('img')?.src;
            if (!src) return;
            mainImg.src = src;
            els('.pv-thumb').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
        });

        /* ===== State & persistence ===== */
        const STORAGE_KEY = `pv_selected_variant_${productData.id}`;
        const state = {
            selectedOptions: {},
            quantity: 1,
            activeVariant: null
        };
        const variantForm = document.getElementById('variant-form');

        /* ===== Variant helpers ===== */
        const scopeFor = (selection) => productData.variants.filter(v =>
            Object.entries(selection).every(([k, val]) => cmp(v.attributes?.[k], val))
        );
        const pickCheapest = arr => arr.reduce((best, v) => {
            const pb = +best.sale_price > 0 ? +best.sale_price : +best.price;
            const pv = +v.sale_price > 0 ? +v.sale_price : +v.price;
            return pv < pb ? v : best;
        }, arr[0]);

        function resolvedSelection() {
            const sel = {
                ...state.selectedOptions
            };
            if (!variantForm) return sel;
            variantForm.querySelectorAll('.attribute-options').forEach(group => {
                const name = group.dataset.attributeName;
                if (sel[name]) return;
                const values = Array.from(group.querySelectorAll('.variant-option-button')).map(b => b.dataset
                    .attributeValue);
                const possibles = values.filter(val => scopeFor({
                    ...sel,
                    [name]: val
                }).length > 0);
                if (possibles.length === 1) sel[name] = possibles[0];
            });
            return sel;
        }

        /* ===== Sync UI ===== */
        function updateSelectedUIFromState() {
            if (!variantForm) return;
            const sel = resolvedSelection();
            variantForm.querySelectorAll('.attribute-options').forEach(group => {
                const name = group.dataset.attributeName;
                const val = sel[name];
                group.querySelectorAll('.variant-option-button').forEach(b => {
                    b.classList.remove('ring-2', 'ring-[#ff6c2f]', 'bg-[#ff6c2f]', 'text-white',
                        'border-[#ff6c2f]', 'opt-selected');
                    if (val && cmp(b.dataset.attributeValue, val)) {
                        b.classList.add('opt-selected');
                        if (b.classList.contains('w-8')) b.classList.add('ring-2', 'ring-[#ff6c2f]');
                        else b.classList.add('bg-[#ff6c2f]', 'text-white', 'border-[#ff6c2f]');
                    }
                });
                const label = document.querySelector(`[data-variant-name-display="${cssEsc(name)}"]`);
                if (label) label.textContent = val || '';
            });
        }

        function refreshOptionStates() {
            if (!variantForm) return;
            els('.variant-option-button').forEach(btn => {
                const {
                    attributeName,
                    attributeValue
                } = btn.dataset;
                const possible = scopeFor({
                    ...state.selectedOptions,
                    [attributeName]: attributeValue
                }).length > 0;
                btn.disabled = !possible;
                btn.classList.toggle('variant-disabled', !possible);
            });
        }

        function updatePrice() {
            const box = el('#price-display');
            if (!box) return;
            const sel = resolvedSelection();
            const candidates = scopeFor(sel);
            let html = '';
            if (candidates.length === 1) {
                const v = candidates[0];
                html = (+v.sale_price > 0 && +v.sale_price < +v.price) ?
                    `<div class="flex items-end gap-3"><span class="text-2xl line-through text-gray-500">${VND(v.price)}</span><span>${VND(v.sale_price)}</span></div>` :
                    `<span>${VND(v.price)}</span>`;
            } else if (candidates.length > 0) {
                const prices = candidates.map(v => +v.sale_price > 0 ? +v.sale_price : +v.price);
                const min = Math.min(...prices),
                    max = Math.max(...prices);
                html = (min === max) ? `<span>${VND(min)}</span>` : `<span>${VND(min)} - ${VND(max)}</span>`;
            } else {
                html = `<span class="text-3xl text-gray-500">Không có phiên bản phù hợp</span>`;
            }
            box.innerHTML = html;
        }

        function updateInfo() {
            const c = el('#info-container');
            if (!c) return;
            const sel = resolvedSelection();
            const candidates = scopeFor(sel);
            let html = '';
            if (candidates.length === 1) {
                const v = candidates[0];
                if (v.sku) html +=
                    `<span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full"><i class="fas fa-barcode mr-1"></i>SKU: ${v.sku}</span>`;
                if (productData.is_featured) html +=
                    `<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full"><i class="fas fa-star mr-1"></i>Nổi bật</span>`;
                html += (+v.stock > 0) ? `<span class="text-green-600 font-semibold">Còn hàng: ${v.stock}</span>` :
                    `<span class="text-red-500 font-semibold">Hết hàng</span>`;
            } else if (candidates.length > 1) {
                html = `<span class="text-gray-500">Chọn thêm thuộc tính để xác định phiên bản</span>`;
            } else {
                html = `<span class="text-red-500 font-semibold">Không có phiên bản phù hợp</span>`;
            }
            c.innerHTML = html;
        }

        function updateButtons() {
            const addBtn = el('#btn-add-cart'),
                buyBtn = el('#btn-buy-now');
            const sel = resolvedSelection();
            const candidates = scopeFor(sel);
            state.activeVariant = (candidates.length === 1) ? candidates[0] : null;
            const enable = !!(state.activeVariant && +state.activeVariant.stock > 0);
            [addBtn, buyBtn].forEach(b => {
                if (!b) return;
                b.disabled = !enable;
            });
        }

        function updateMainImage() {
            if (state.activeVariant?.image) mainImg.src = state.activeVariant.image;
            else mainImg.src = productData.thumbnail;
        }

        function updateQtyButtons() {
            const minus = el('#quantity-minus-btn'),
                plus = el('#quantity-plus-btn');
            const v = state.activeVariant || {
                stock: 0
            };
            const cap = +v.stock || 0;
            if (minus) {
                const canMinus = state.quantity > 1;
                minus.disabled = !canMinus;
                minus.classList.toggle('opacity-50', !canMinus);
            }
            if (plus) {
                const reached = cap > 0 && state.quantity >= cap;
                plus.disabled = !state.activeVariant || reached;
                plus.classList.toggle('opacity-50', !state.activeVariant || reached);
                plus.title = reached ? `Chỉ còn ${cap} sản phẩm trong kho` : '';
            }
            const qty = el('#qty');
            if (qty) qty.value = state.quantity;
        }

        function render() {
            refreshOptionStates();
            updateSelectedUIFromState();
            updatePrice();
            updateButtons();
            updateInfo();
            updateMainImage();
            updateQtyButtons();

            const sel = resolvedSelection();
            const cand = scopeFor(sel);
            if (cand.length === 1) localStorage.setItem(STORAGE_KEY, String(cand[0].id));
            else localStorage.removeItem(STORAGE_KEY);
        }

        function restoreOrPreselect() {
            if (productData.type !== 'variable' || !variantForm) return;
            const savedId = localStorage.getItem(STORAGE_KEY);
            let chosen = savedId ? productData.variants.find(v => String(v.id) === String(savedId)) : null;
            if (!chosen) {
                const inStock = productData.variants.filter(v => +v.stock > 0);
                chosen = inStock.length ? pickCheapest(inStock) : pickCheapest(productData.variants);
            }
            if (chosen) {
                state.selectedOptions = {
                    ...(chosen.attributes || {})
                };
                state.activeVariant = chosen;
            }
        }

        variantForm?.addEventListener('click', e => {
            const btn = e.target.closest('.variant-option-button');
            if (!btn || btn.disabled) return;
            const name = btn.dataset.attributeName,
                val = btn.dataset.attributeValue;
            if (state.selectedOptions[name] && cmp(state.selectedOptions[name], val)) delete state.selectedOptions[
                name];
            else state.selectedOptions[name] = val;
            state.quantity = 1;
            render();
        });

        el('#quantity-minus-btn')?.addEventListener('click', () => {
            if (state.quantity > 1) {
                state.quantity -= 1;
                updateQtyButtons();
            } else toast('Số lượng tối thiểu là 1', 'error');
        });
        el('#quantity-plus-btn')?.addEventListener('click', () => {
            if (!state.activeVariant) {
                toast('Hãy chọn đúng thuộc tính để xác định phiên bản.', 'error');
                return;
            }
            const cap = +state.activeVariant.stock || 0;
            if (cap > 0 && state.quantity >= cap) {
                toast(`Chỉ còn ${cap} sản phẩm trong kho!`, 'error');
                return;
            }
            state.quantity += 1;
            updateQtyButtons();
        });

        function requireReady() {
            if (productData.type !== 'variable') return true;
            if (!state.activeVariant) {
                toast('Hãy chọn đúng thuộc tính của biến thể.', 'error');
                return false;
            }
            if (!(state.activeVariant.stock > 0)) {
                toast('Biến thể này đã hết hàng.', 'error');
                return false;
            }
            return true;
        }
        el('#btn-add-cart')?.addEventListener('click', () => {
            if (!requireReady()) return;
            fetch('{{ route('carts.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrf()
                },
                body: JSON.stringify({
                    product_id: productData.id,
                    quantity: state.quantity,
                    variant_id: state.activeVariant.id
                })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    toast(res.message || 'Đã thêm vào giỏ hàng', 'success');
                    if (window.reloadCartAjax) reloadCartAjax();
                    document.dispatchEvent(new CustomEvent('cart:updated'));
                } else {
                    toast(res.message || 'Có lỗi xảy ra', 'error');
                }
            }).catch(() => toast('Lỗi kết nối, vui lòng thử lại.', 'error'));
        });
        el('#btn-buy-now')?.addEventListener('click', () => {
            if (!requireReady()) return;
            fetch('{{ route('carts.setBuyNow') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrf()
                },
                body: JSON.stringify({
                    product_id: productData.id,
                    quantity: state.quantity,
                    variant_id: state.activeVariant.id
                })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    window.location.href = '{{ route('checkout.index') }}';
                } else {
                    toast(res.message || 'Có lỗi xảy ra, không thể mua ngay.', 'error');
                }
            }).catch(() => toast('Lỗi kết nối, vui lòng thử lại.', 'error'));
        });

        /* Tabs & like */
        els('.pv-tab').forEach(t => t.addEventListener('click', () => {
            els('.pv-tab').forEach(x => x.classList.remove('active'));
            t.classList.add('active');
            const key = t.dataset.tab;
            el('#tab-desc').classList.toggle('hidden', key !== 'desc');
            el('#tab-review').classList.toggle('hidden', key !== 'review');
        }));
        document.addEventListener('click', e => {
            const like = e.target.closest('.rp-like');
            if (!like) return;
            e.preventDefault();
            like.classList.toggle('active');
        });

        /* ===== Thu gọn / Đọc thêm logic ===== */
        const descBox = document.getElementById('product-description');
        const toggleBtn = document.getElementById('toggle-desc');

        function updateToggleVisibility() {
            if (!descBox || !toggleBtn) return;
            // nếu nội dung ngắn không cần nút
            const needToggle = descBox.scrollHeight > 520; // cao hơn ngưỡng ban đầu một chút
            toggleBtn.style.display = needToggle ? 'inline-flex' : 'none';
        }
        toggleBtn?.addEventListener('click', () => {
            if (!descBox) return;
            const isCollapsed = descBox.classList.contains('pv-desc-collapsed');
            if (isCollapsed) {
                descBox.classList.remove('pv-desc-collapsed');
                descBox.classList.add('pv-desc-expanded');
                toggleBtn.textContent = 'Thu gọn';
            } else {
                descBox.classList.remove('pv-desc-expanded');
                descBox.classList.add('pv-desc-collapsed');
                toggleBtn.textContent = 'Đọc thêm';
                descBox.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });

        /* ===== Start ===== */
        restoreOrPreselect();
        render();
        updateToggleVisibility();
        window.addEventListener('resize', updateToggleVisibility);

        /* ===== Review Form ===== */
        // Star rating functionality
        const starInputs = document.querySelectorAll('input[name="rating"]');
        const starLabels = document.querySelectorAll('label[for^="star"]');
        
        starLabels.forEach((label, index) => {
            label.addEventListener('click', () => {
                // Reset all stars
                starLabels.forEach(l => l.querySelector('i').classList.remove('text-yellow-400'));
                starLabels.forEach(l => l.querySelector('i').classList.add('text-gray-300'));
                
                // Fill stars up to clicked one
                for (let i = 0; i <= index; i++) {
                    starLabels[i].querySelector('i').classList.remove('text-gray-300');
                    starLabels[i].querySelector('i').classList.add('text-yellow-400');
                }
            });
        });

        // Character count for review textarea
        const reviewTextarea = document.querySelector('textarea[name="content"]');
        const charCount = document.getElementById('charCount');
        
        if (reviewTextarea && charCount) {
            reviewTextarea.addEventListener('input', () => {
                const count = reviewTextarea.value.length;
                charCount.textContent = count;
                
                if (count > 2800) {
                    charCount.classList.add('text-red-500');
                    charCount.classList.remove('text-gray-500');
                } else {
                    charCount.classList.remove('text-red-500');
                    charCount.classList.add('text-gray-500');
                }
            });
        }

        // Character count for order review forms
        document.querySelectorAll('.order-review-form textarea').forEach(textarea => {
            const charCount = textarea.parentElement.querySelector('.charCount');
            
            textarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length;
                
                if (length > 450) {
                    charCount.classList.add('text-red-500');
                } else {
                    charCount.classList.remove('text-red-500');
                }
            });
        });

        // Star rating functionality for order forms
        document.querySelectorAll('.order-review-form').forEach(form => {
            const stars = form.querySelectorAll('input[type="radio"]');
            const starLabels = form.querySelectorAll('label[for^="star"]');
            const submitBtn = form.querySelector('button[type="submit"]');
            const textarea = form.querySelector('textarea[name="content"]');
            const ratingError = form.querySelector('.rating-error') || createRatingErrorElement(form);
            const contentError = form.querySelector('.content-error') || createContentErrorElement(form);
            
            // Tạo element hiển thị lỗi rating
            function createRatingErrorElement(form) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'rating-error text-red-500 text-xs mt-1 hidden';
                form.querySelector('.mb-3').appendChild(errorDiv);
                return errorDiv;
            }
            
            // Tạo element hiển thị lỗi content
            function createContentErrorElement(form) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'content-error text-red-500 text-xs mt-1 hidden';
                textarea.parentElement.appendChild(errorDiv);
                return errorDiv;
            }
            
            // Validation function
            function validateForm() {
                let isValid = true;
                
                // Validate rating
                const selectedRating = form.querySelector('input[name="rating"]:checked');
                if (!selectedRating) {
                    ratingError.textContent = 'Vui lòng chọn đánh giá sao!';
                    ratingError.classList.remove('hidden');
                    isValid = false;
                } else {
                    ratingError.classList.add('hidden');
                }
                
                // Validate content
                const content = textarea.value.trim();
                if (!content) {
                    contentError.textContent = 'Nội dung đánh giá không được để trống!';
                    contentError.classList.remove('hidden');
                    isValid = false;
                } else if (content.length < 10) {
                    contentError.textContent = 'Nội dung đánh giá phải có ít nhất 10 ký tự!';
                    contentError.classList.remove('hidden');
                    isValid = false;
                } else if (content.length > 500) {
                    contentError.textContent = 'Nội dung đánh giá không được quá 500 ký tự!';
                    contentError.classList.remove('hidden');
                    isValid = false;
                } else {
                    contentError.classList.add('hidden');
                }
                
                // Validate HTML tags
                if (content.includes('<') || content.includes('>')) {
                    contentError.textContent = 'Nội dung đánh giá không được chứa HTML tags!';
                    contentError.classList.remove('hidden');
                    isValid = false;
                }
                
                // Validate spam words
                const spamWords = ['spam', 'advertisement', 'quảng cáo', 'mua ngay', 'giá rẻ', 'khuyến mãi'];
                const lowerContent = content.toLowerCase();
                for (const word of spamWords) {
                    if (lowerContent.includes(word)) {
                        contentError.textContent = 'Nội dung đánh giá không được chứa từ khóa quảng cáo!';
                        contentError.classList.remove('hidden');
                        isValid = false;
                        break;
                    }
                }
                
                // Validate repeated characters
                if (/(.)\1{4,}/.test(content)) {
                    contentError.textContent = 'Nội dung đánh giá không được chứa ký tự lặp lại quá nhiều!';
                    contentError.classList.remove('hidden');
                    isValid = false;
                }
                
                return isValid;
            }
            
            // Form submit validation
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Real-time validation for content
            textarea.addEventListener('input', function() {
                const content = this.value.trim();
                if (content.length >= 10 && content.length <= 500) {
                    contentError.classList.add('hidden');
                }
            });
            
            // Real-time validation for rating
            stars.forEach((star, index) => {
                star.addEventListener('change', function() {
                    // Reset all stars in this form
                    starLabels.forEach(label => {
                        label.querySelector('i').classList.remove('text-yellow-400');
                        label.querySelector('i').classList.add('text-gray-300');
                    });
                    
                    // Color stars up to selected
                    for (let i = 0; i <= index; i++) {
                        starLabels[i].querySelector('i').classList.remove('text-gray-300');
                        starLabels[i].querySelector('i').classList.add('text-yellow-400');
                    }
                    
                    // Hide rating error when rating is selected
                    ratingError.classList.add('hidden');
                });
            });
        });

        /* ===== Reply Form Functions ===== */
        // Toggle reply form
        window.toggleReplyForm = function(commentId) {
            const form = document.getElementById('replyForm' + commentId);
            if (form) {
                form.classList.toggle('hidden');
                
                // Focus vào textarea khi hiển thị form
                if (!form.classList.contains('hidden')) {
                    const textarea = form.querySelector('textarea[name="reply_content"]');
                    if (textarea) {
                        textarea.focus();
                    }
                }
            }
        };

        // Validate reply form
        window.validateReplyForm = function(form) {
            const textarea = form.querySelector('textarea[name="reply_content"]');
            const errorDiv = form.querySelector('.reply-content-error');
            const content = textarea.value.trim();
            
            // Reset error
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
            
            // Validate required
            if (!content) {
                errorDiv.textContent = 'Nội dung phản hồi không được để trống!';
                errorDiv.classList.remove('hidden');
                return false;
            }
            
            // Validate length
            if (content.length < 5) {
                errorDiv.textContent = 'Nội dung phản hồi phải có ít nhất 5 ký tự!';
                errorDiv.classList.remove('hidden');
                return false;
            }
            
            if (content.length > 200) {
                errorDiv.textContent = 'Nội dung phản hồi không được quá 200 ký tự!';
                errorDiv.classList.remove('hidden');
                return false;
            }
            
            // Validate HTML tags
            if (content.includes('<') || content.includes('>')) {
                errorDiv.textContent = 'Nội dung phản hồi không được chứa HTML tags!';
                errorDiv.classList.remove('hidden');
                return false;
            }
            
            // Validate spam words
            const spamWords = ['spam', 'advertisement', 'quảng cáo', 'mua ngay', 'giá rẻ', 'khuyến mãi'];
            const lowerContent = content.toLowerCase();
            for (const word of spamWords) {
                if (lowerContent.includes(word)) {
                    errorDiv.textContent = 'Nội dung phản hồi không được chứa từ khóa quảng cáo!';
                    errorDiv.classList.remove('hidden');
                    return false;
                }
            }
            
            // Validate repeated characters
            if (/(.)\1{4,}/.test(content)) {
                errorDiv.textContent = 'Nội dung phản hồi không được chứa ký tự lặp lại quá nhiều!';
                errorDiv.classList.remove('hidden');
                    return false;
            }
            
            return true;
        };

        // Character count for reply forms
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.reply-form textarea').forEach(textarea => {
                const charCount = textarea.parentElement.querySelector('.replyCharCount');
                
                textarea.addEventListener('input', function() {
                    const length = this.value.length;
                    charCount.textContent = length;
                    
                    if (length > 180) {
                        charCount.classList.add('text-red-500');
                        charCount.classList.remove('text-gray-500');
                    } else {
                        charCount.classList.remove('text-red-500');
                        charCount.classList.add('text-gray-500');
                    }
                });
            });
        });

        /* ===== Rating Filter ===== */
        const ratingFilterBtns = document.querySelectorAll('.rating-filter-btn');
        const commentsContainer = document.getElementById('comments-container');
        const productId = {{ $product->id }};

        ratingFilterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const rating = btn.dataset.rating;
                
                // Update active button
                ratingFilterBtns.forEach(b => {
                    b.classList.remove('border-red-500', 'text-red-500');
                    b.classList.add('border-gray-300', 'text-gray-700');
                });
                btn.classList.remove('border-gray-300', 'text-gray-700');
                btn.classList.add('border-red-500', 'text-red-500');

                // Show loading
                commentsContainer.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i><p class="text-gray-500 mt-2">Đang tải...</p></div>';

                // Fetch filtered comments
                fetch(`/products/${productId}/comments/filter?rating=${rating}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.comments.length === 0) {
                            commentsContainer.innerHTML = '<div class="bg-white rounded-lg border border-gray-200 p-6 text-center text-gray-500">Không có bình luận nào cho đánh giá này.</div>';
                            return;
                        }

                        let html = '';
                        data.comments.forEach(comment => {
                            html += `
                                <div class="bg-white rounded-lg border border-gray-200 p-5">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-full bg-[#ff6c2f] text-white flex items-center justify-center font-semibold">
                                            ${comment.user.name.charAt(0).toUpperCase()}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <b class="text-gray-900">${comment.user.name}</b>
                                                <span class="text-gray-500 text-sm">${new Date(comment.created_at).toLocaleDateString('vi-VN')} ${new Date(comment.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}</span>
                                            </div>
                                            ${comment.rating ? `
                                                <div class="text-yellow-400 mb-1">
                                                    ${Array.from({length: 5}, (_, i) => 
                                                        `<i class="fas fa-star ${i < comment.rating ? '' : 'text-gray-300'}"></i>`
                                                    ).join('')}
                                                    <span class="text-sm text-gray-600 ml-1">${comment.rating}/5</span>
                                                </div>
                                            ` : ''}
                                            <p class="text-gray-800">${processCommentContent(comment.content)}</p>
                                            ${comment.replies && comment.replies.length > 0 ? `
                                                <div class="mt-3 space-y-3">
                                                    ${comment.replies.map(reply => 
                                                        reply.status === 'approved' && !reply.is_hidden ? `
                                                            <div class="bg-gray-50 rounded-md p-3 ml-4">
                                                                <div class="flex items-center gap-2">
                                                                    <b class="text-sm">${reply.user.name}</b>
                                                                    <span class="text-xs text-gray-500">${new Date(reply.created_at).toLocaleDateString('vi-VN')} ${new Date(reply.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}</span>
                                                                </div>
                                                                <div class="text-sm text-gray-700">${processCommentContent(reply.content)}</div>
                                                            </div>
                                                        ` : ''
                                                    ).join('')}
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        commentsContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        commentsContainer.innerHTML = '<div class="bg-white rounded-lg border border-gray-200 p-6 text-center text-gray-500">Có lỗi xảy ra khi tải bình luận.</div>';
                    });
            });
        });

        // Set "Tất cả" as default active
        document.querySelector('[data-rating="all"]').classList.add('border-red-500', 'text-red-500');
        document.querySelector('[data-rating="all"]').classList.remove('border-gray-300', 'text-gray-700');

        /* ===== Favorite Products ===== */
        document.querySelectorAll('.favorite-once').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const icon = this.querySelector('i');
                const originalIcon = icon.className;
                
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
                        // Update icon based on favorite status
                        if (data.is_favorite) {
                            icon.className = 'fas fa-heart';
                        } else {
                            icon.className = 'far fa-heart';
                        }
                        
                        // Show toast message
                        toast(data.message, 'success');
                    } else if (data.redirect) {
                        // Nếu server yêu cầu redirect (user chưa đăng nhập)
                        toast(data.message || 'Vui lòng đăng nhập để thêm vào yêu thích', 'error');
                        setTimeout(() => {
                            openAuthModal();
                        }, 1500);
                    } else {
                        // Restore original state on error
                        icon.className = originalIcon;
                        toast(data.message || 'Có lỗi xảy ra, vui lòng thử lại', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Restore original state on error
                    icon.className = originalIcon;
                    toast('Có lỗi xảy ra, vui lòng thử lại', 'error');
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    </script>
@endpush