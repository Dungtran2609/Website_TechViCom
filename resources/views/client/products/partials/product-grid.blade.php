@if($products->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer group product-card"
                 onclick="goToProductDetail({{ $product->id }})">
                <div class="relative product-image-wrap">
                    @php
                        $thumb = $product->thumbnail
                            ? asset('storage/' . $product->thumbnail)
                            : asset('client_css/images/placeholder.svg');
                    @endphp
                    <img src="{{ $thumb }}" alt="{{ $product->name }}" class="product-image"
                         onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                    
                    <!-- Favorite Button -->
                    <button class="absolute top-2 right-2 favorite-btn" 
                            data-product-id="{{ $product->id }}">
                        <i class="fas fa-heart {{ in_array($product->id, $favoriteProductIds) ? 'text-red-500' : 'text-gray-300' }} hover:text-red-500 transition-colors"></i>
                    </button>
                    
                    <!-- Flash Sale Badge -->
                    @if(isset($product->flashSaleInfo))
                        <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                            -{{ $product->flashSaleInfo['discount_percent'] }}%
                        </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>

                    <!-- GIÁ + Lượt xem -->
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            @php
                                $variants = $product->variants ?? collect();
                                $minPrice = null;
                                $maxPrice = null;
                                
                                if ($variants->count()) {
                                    $prices = $variants->map(
                                        fn($v) => $v->sale_price && $v->sale_price < $v->price
                                            ? $v->sale_price
                                            : $v->price,
                                    );
                                    $minPrice = $prices->min();
                                    $maxPrice = $prices->max();
                                }
                            @endphp
                            
                            @if (!is_null($minPrice) && !is_null($maxPrice))
                                @if ($minPrice == $maxPrice)
                                    <span class="text-lg font-bold text-[#ff6c2f]">{{ number_format($minPrice) }}₫</span>
                                @else
                                    <span class="text-lg font-bold text-[#ff6c2f]">{{ number_format($minPrice) }}₫ - {{ number_format($maxPrice) }}₫</span>
                                @endif
                            @else
                                <span class="text-lg font-bold text-[#ff6c2f]">0₫</span>
                            @endif
                        </div>
                        <div class="flex items-center text-gray-500 text-sm">
                            <i class="far fa-eye mr-1"></i>
                            <span>{{ number_format($product->view_count ?? 0) }}</span>
                        </div>
                    </div>

                    <!-- Đánh giá dưới giá -->
                    <div class="flex items-center">
                        <div class="flex text-yellow-400 text-sm">
                            @php
                                $stars = (int) round($product->avg_rating ?? 0);
                                $reviewsCount = (int) ($product->reviews_count ?? 0);
                            @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $stars)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-500 text-sm ml-2">({{ $reviewsCount }})</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
@else
    <div class="text-center py-12">
        <div class="text-gray-500 text-lg mb-4">
            <i class="fas fa-search text-4xl mb-4"></i>
            <p>Không tìm thấy sản phẩm nào phù hợp với bộ lọc của bạn.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            Xóa bộ lọc và xem tất cả sản phẩm
        </a>
    </div>
@endif

<script>
    function goToProductDetail(productId) {
        window.location.href = `/products/${productId}`;
    }
</script>
