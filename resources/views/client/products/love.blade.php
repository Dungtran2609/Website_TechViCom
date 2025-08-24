@extends('client.layouts.app')


@section('title', 'Sản phẩm yêu thích - Techvicom')


@push('styles')
    <style>
        :root{
            /* CHIỀU CAO ẢNH - đổi 1 chỗ là xong */
            --prod-img-h: 300px; /* Chiều cao ảnh giống hình bạn gửi */
        }
        /* Product Card Styles */
        .product-card {
            position: relative;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            border-radius: .75rem;
            overflow: hidden;
            background: #fff;
            cursor: pointer; /* toàn bộ card có thể bấm */
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }


        /* Hộp ảnh: chiều cao cố định theo biến trên */
        .product-image-wrap {
            width: 100%;
            height: var(--prod-img-h);
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        /* Ảnh hiển thị đầy đủ không bị cắt */
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
            image-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            backface-visibility: hidden;
            transform: translateZ(0);
        }
        .product-card:hover .product-image {
            transform: scale(1.04);
        }


        /* Favorite Button */
        .favorite-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.92);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            z-index: 10;
            box-shadow: 0 2px 6px rgba(0,0,0,.08);
        }
        .favorite-btn:hover { background: #ffffff; transform: scale(1.06); }
        .favorite-btn i { color: #9ca3af; }
        .favorite-btn.active{ background:#ff6c2f; color:#fff; }
        .favorite-btn.active i { color:#fff; }


        /* Empty state */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 4rem; color: #d1d5db; margin-bottom: 20px; }
        .empty-state h3 { font-size: 1.5rem; color: #6b7280; margin-bottom: 10px; }
        .empty-state p { color: #9ca3af; margin-bottom: 30px; }


        /* Show/Hide buttons */
        .show-more-btn, .hide-some-btn {
            background: #ff6c2f;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .show-more-btn:hover, .hide-some-btn:hover {
            background: #e55a28;
            transform: translateY(-2px);
        }
        .product-card.hidden {
            display: none;
        }
    </style>
@endpush


@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="techvicom-container py-8">
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


        <!-- Count -->
        <div class="flex justify-between items-center mb-6">
            <span id="product-count" class="text-gray-600">{{ count($products ?? []) }} sản phẩm yêu thích</span>
        </div>


        <!-- Products Grid: 5 cột trên desktop -->
        <div id="products-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($products as $product)
                @php
                    $variant = $product->variants->first();
                    $price = $variant ? $variant->price : 0;
                    $salePrice = $variant && $variant->sale_price ? $variant->sale_price : null;


                    // 4K-ready sources
                    $base = $product->thumbnail ? asset('storage/' . $product->thumbnail) : null;
                    $kw = trim($product->name ?? 'technology');
                    $q = rawurlencode($kw);
                    $f4k = 'https://source.unsplash.com/3840x2160/?' . $q;
                    $f2k = 'https://source.unsplash.com/2560x1440/?' . $q;
                    $fhd = 'https://source.unsplash.com/1920x1080/?' . $q;
                @endphp


                <div class="product-card show"
                     data-product-id="{{ $product->id }}"
                     data-href="{{ route('products.show', $product->id) }}">
                    <div class="relative">
                        <div class="product-image-wrap">
                            @if($base)
                                <img src="{{ $base }}"
                                     alt="{{ $product->name }}"
                                     class="product-image"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-400 text-center">
                                    <i class="fas fa-image text-4xl mb-2"></i>
                                    <div class="text-sm">Hình ảnh không tồn tại</div>
                                    <div class="text-xs">No Image Available</div>
                                </div>
                            @endif
                        </div>


                        <!-- Favorite -->
                        <button type="button" class="favorite-btn active"
                                data-product-id="{{ $product->id }}"
                                onclick="event.stopPropagation(); toggleFavorite({{ $product->id }})">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>


                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>


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


                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span>{{ $product->category->name ?? 'N/A' }}</span>
                            <span>{{ $product->brand->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="far fa-heart text-gray-400 text-3xl"></i>
                    </div>
                    @if(isset($notLoggedIn) && $notLoggedIn)
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Vui lòng đăng nhập</h3>
                        <p class="text-gray-500">Bạn cần đăng nhập để xem sản phẩm yêu thích</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition mt-4">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Đăng nhập
                        </a>
                    @else
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Chưa có sản phẩm yêu thích</h3>
                        <p class="text-gray-500">Bạn chưa thêm sản phẩm nào vào danh sách yêu thích</p>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition mt-4">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Khám phá sản phẩm
                        </a>
                    @endif
                </div>
            @endforelse
                 </div>
         
         <!-- Show More/Hide Some Buttons -->
         <div class="text-center mt-8" id="show-hide-buttons" style="display: none;">
             <button id="show-more-btn" class="show-more-btn mr-4">
                 <i class="fas fa-plus mr-2"></i>Xem thêm sản phẩm
             </button>
             <button id="hide-some-btn" class="hide-some-btn">
                 <i class="fas fa-minus mr-2"></i>Ẩn bớt sản phẩm
             </button>
         </div>
     </div>
 </div>
 @endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsGrid = document.getElementById('products-grid');
    const productCards = document.querySelectorAll('.product-card.show');
    const showMoreBtn = document.getElementById('show-more-btn');
    const hideSomeBtn = document.getElementById('hide-some-btn');
    const showHideButtons = document.getElementById('show-hide-buttons');
    const productCount = document.getElementById('product-count');
    
    let isShowingAll = true;
    const itemsPerPage = 6; // Số sản phẩm hiển thị mỗi lần

    // Khởi tạo
    function initialize() {
        const totalProducts = productCards.length;
        
        if (totalProducts === 0) {
            return;
        }

        updateProductCount(totalProducts);
        showInitialProducts();
        enableCardClicks();
    }

    // Hiển thị sản phẩm ban đầu
    function showInitialProducts() {
        productCards.forEach((card, index) => {
            if (index < itemsPerPage) {
                card.classList.remove('hidden');
                card.classList.add('show');
            } else {
                card.classList.add('hidden');
                card.classList.remove('show');
            }
        });
        
        updateButtons();
    }

    // Cập nhật số lượng sản phẩm
    function updateProductCount(count) {
        if (productCount) {
            productCount.textContent = `${count} sản phẩm yêu thích`;
        }
    }

    // Cập nhật trạng thái nút
    function updateButtons() {
        const visibleCount = document.querySelectorAll('.product-card.show').length;
        const totalCount = productCards.length;
        
        if (totalCount > itemsPerPage) {
            showHideButtons.style.display = 'block';
            showMoreBtn.style.display = visibleCount < totalCount ? 'inline-block' : 'none';
            hideSomeBtn.style.display = visibleCount > itemsPerPage ? 'inline-block' : 'none';
        } else {
            showHideButtons.style.display = 'none';
        }
    }

    // Toàn bộ card click -> sang chi tiết
    function enableCardClicks() {
        document.querySelectorAll('.product-card[data-href]').forEach(card => {
            card.addEventListener('click', (e) => {
                if (e.target.closest('.favorite-btn')) return;
                const href = card.getAttribute('data-href');
                if (href) window.location.href = href;
            });
        });
    }

    // Hiển thị tất cả sản phẩm
    showMoreBtn.addEventListener('click', function() {
        productCards.forEach(card => {
            card.classList.remove('hidden');
            card.classList.add('show');
        });
        isShowingAll = true;
        updateButtons();
    });

    // Ẩn bớt sản phẩm
    hideSomeBtn.addEventListener('click', function() {
        productCards.forEach((card, index) => {
            if (index < itemsPerPage) {
                card.classList.remove('hidden');
                card.classList.add('show');
            } else {
                card.classList.add('hidden');
                card.classList.remove('show');
            }
        });
        isShowingAll = false;
        updateButtons();
    });

    // Toggle favorite (giữ nguyên API bạn đang dùng)
    window.toggleFavorite = function(productId) {
        const btn = document.querySelector('.favorite-btn[data-product-id=\"' + productId + '\"]');
        const card = btn ? btn.closest('.product-card') : null;
        if (!btn || !card) return;


        const icon = btn.querySelector('i');
        const orig = icon ? icon.className : '';
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        btn.disabled = true;


        fetch('{{ route("accounts.favorites.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success) {
                if (!data.is_favorite) {
                    card.remove();
                    // cập nhật số đếm
                    const cnt = document.querySelectorAll('.product-card').length;
                    const counter = document.getElementById('product-count');
                    if (counter) counter.textContent = cnt + ' sản phẩm yêu thích';
                }
            } else if (data.redirect) {
                // Nếu server yêu cầu redirect (user chưa đăng nhập)
                alert(data.message || 'Vui lòng đăng nhập để thêm vào yêu thích');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                if (icon) icon.className = orig;
            }
        })
        .catch(() => { if (icon) icon.className = orig; })
        .finally(() => { btn.disabled = false; });
    };

    // Khởi tạo trang
    initialize();
});
</script>
@endpush





