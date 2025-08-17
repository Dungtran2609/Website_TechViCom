@extends('client.layouts.app')


@section('title', isset($product) ? $product->name . ' - Techvicom' : 'Chi tiết sản phẩm - Techvicom')


@section('content')
    @if (isset($product))
        @php
            $activeVariants = $product->variants->where('is_active', true);
        @endphp

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

        <section class="py-10">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="flex flex-col items-center w-full">
                        <div
                            class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm p-4 w-full h-[450px] flex items-center justify-center">
                            <img id="main-image"
                                src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                                alt="{{ $product->name }}" class="w-full h-full object-contain transition-all duration-300"
                                onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                        </div>

                        <div class="flex gap-3 justify-center mt-4 w-full">
                            @foreach ($product->productAllImages as $image)
                                <button type="button"
                                    class="border-2 border-gray-200 rounded-lg p-1 bg-white hover:border-[#ff6c2f] transition w-16 h-16 flex items-center justify-center"
                                    onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Ảnh phụ sản phẩm"
                                        class="w-14 h-14 object-contain rounded-md"
                                        onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col space-y-6">
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">{{ $product->name }}</h1>

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                            @if ($product->brand)
                                <span class="flex items-center text-gray-600"><i class="fas fa-tag mr-2 text-gray-400"></i>
                                    Thương hiệu: <a href="#"
                                        class="ml-1 font-semibold text-blue-600 hover:underline">{{ $product->brand->name }}</a></span>
                            @endif
                            @if ($product->category)
                                <span class="flex items-center text-gray-600"><i
                                        class="fas fa-folder-open mr-2 text-gray-400"></i> Danh mục: <span
                                        class="ml-1 font-semibold">{{ $product->category->name }}</span></span>
                            @endif
                        </div>

                        @if ($product->short_description)
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                <p class="text-gray-700 italic">{{ $product->short_description }}</p>
                            </div>
                        @endif
                        @if ($product->description)
                            <div class="mt-2">
                                <button type="button" id="toggle-description"
                                    class="text-[#ff6c2f] font-semibold underline mb-2">Xem mô tả chi tiết</button>
                                <div id="full-description"
                                    class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-800 mt-2 hidden">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        @endif

                        <div class="price-display-area text-4xl font-bold text-[#ff6c2f]">
                            @if ($product->type === 'variable' && $activeVariants->isNotEmpty())
                                @php
                                    $minPrice = $activeVariants->min('price');
                                    $maxPrice = $activeVariants->max('price');
                                @endphp
                                @if ($minPrice === $maxPrice)
                                    <span>{{ number_format($minPrice, 0, ',', '.') }}₫</span>
                                @else
                                    <span>{{ number_format($minPrice, 0, ',', '.') }} -
                                        {{ number_format($maxPrice, 0, ',', '.') }}₫</span>
                                @endif
                            @elseif ($product->type === 'simple' && $activeVariants->isNotEmpty())
                                @php
                                    $currentVariant = $activeVariants->first();
                                    $price = $currentVariant->price;
                                    $salePrice = $currentVariant->sale_price;
                                @endphp
                                @if ($salePrice && $salePrice < $price)
                                    <div class="flex items-end gap-3">
                                        <span
                                            class="text-2xl line-through text-gray-500">{{ number_format($price, 0, ',', '.') }}₫</span>
                                        <span
                                            class="text-4xl font-bold text-[#ff6c2f]">{{ number_format($salePrice, 0, ',', '.') }}₫</span>
                                    </div>
                                @else
                                    <span>{{ number_format($price, 0, ',', '.') }}₫</span>
                                @endif
                            @else
                                <span class="text-3xl text-gray-500">Tạm hết hàng</span>
                            @endif
                        </div>

                        <div id="info-container" class="flex flex-wrap items-center gap-3 text-sm h-8"></div>

                        @if ($product->type === 'variable' && $activeVariants->isNotEmpty())
                            <form id="variant-form" class="space-y-5">
                                @php
                                    $attributesData = $activeVariants
                                        ->flatMap(fn($v) => $v->attributeValues)
                                        ->groupBy('attribute.name')
                                        ->map(fn($vals) => $vals->unique('value')->sortBy('value')->values());
                                @endphp
                                @foreach ($attributesData as $name => $attributeValues)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-800 mb-2">{{ $name }}:
                                            <span class="text-gray-500 font-normal"
                                                data-variant-name-display="{{ $name }}"></span></label>
                                        <div class="flex flex-wrap gap-3 items-center attribute-options"
                                            data-attribute-name="{{ $name }}">
                                            @foreach ($attributeValues as $attrValue)
                                                @if (
                                                    (str_contains(strtolower($name), 'màu') || str_contains(strtolower($name), 'color')) &&
                                                        !empty($attrValue->color_code))
                                                    <button type="button" title="{{ $attrValue->value }}"
                                                        class="variant-option-button color-swatch w-8 h-8 rounded-full border-2 border-transparent focus:outline-none transition-all duration-200"
                                                        style="background-color: {{ $attrValue->color_code }}"
                                                        data-attribute-name="{{ $name }}"
                                                        data-attribute-value="{{ $attrValue->value }}"></button>
                                                @else
                                                    <button type="button"
                                                        class="variant-option-button px-4 py-2 border border-gray-300 rounded-lg text-sm hover:border-[#ff6c2f] focus:outline-none transition"
                                                        data-attribute-name="{{ $name }}"
                                                        data-attribute-value="{{ $attrValue->value }}">{{ $attrValue->value }}</button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </form>
                        @endif

                        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                            <label class="text-sm font-medium">Số lượng:</label>
                            <div class="flex items-center">
                                <button type="button" id="quantity-minus-btn" onclick="updateQuantity(-1, {{ $product->id ?? 0 }})"
                                    class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-l-lg hover:bg-gray-100 transition quantity-btn">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" id="quantity" value="1" min="1"
                                    class="w-12 h-8 text-center border-t border-b border-gray-300 focus:outline-none"
                                    readonly>
                                <button type="button" id="quantity-plus-btn" onclick="updateQuantity(1, {{ $product->id ?? 0 }})"
                                    class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-r-lg hover:bg-gray-100 transition quantity-btn">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button type="button" onclick="addProductToCart()"
                                class="w-full bg-[#ff6c2f] text-white py-3 px-4 rounded-lg hover:bg-[#e55a28] transition font-bold flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-shopping-cart mr-2"></i> Thêm vào giỏ hàng
                            </button>
                            <button type="button" onclick="buyNow()"
                                class="w-full bg-gray-800 text-white py-3 px-4 rounded-lg hover:bg-black transition font-bold disabled:opacity-50 disabled:cursor-not-allowed">
                                Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="py-8 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sản phẩm liên quan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition">
                        <a href="{{ route('products.show', $relatedProduct->id) }}">
                            <div class="aspect-square bg-gray-50">
                                @if($relatedProduct->productAllImages->count() > 0)
                                    <img src="{{ asset($relatedProduct->productAllImages->first()->image_path) }}" 
                                         alt="{{ $relatedProduct->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('admin_css/images/placeholder.jpg') }}" 
                                         alt="{{ $relatedProduct->name }}" 
                                         class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">{{ $relatedProduct->name }}</h3>
                                <p class="text-[#ff6c2f] font-bold">
                                    {{ number_format($relatedProduct->price, 0, ',', '.') }}₫
                                </p>
                            </div>
                        </a>
                    </div>
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
                   class="inline-flex items-center bg-[#ff6c2f] text-white px-6 py-3 rounded-lg hover:bg-[#e55a28] transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại danh sách sản phẩm
                </a>
            </div>
        </section>
    @endif

<!-- Comments Section -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Đánh giá & Bình luận</h2>
            
            <!-- Comment Form -->
            @auth
                @php
                    $commentController = new \App\Http\Controllers\Client\Products\ClientProductCommentController();
                    $canComment = $commentController->canComment($product->id);
                @endphp
                
                @if($canComment)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Viết đánh giá của bạn</h3>
                        
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('products.comments.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Đánh giá</label>
                                <div class="flex items-center space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" id="rating_{{ $i }}" name="rating" value="{{ $i }}" class="sr-only" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <label for="rating_{{ $i }}" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400 transition-colors">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Nội dung bình luận</label>
                                <textarea id="content" name="content" rows="4" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#ff6c2f] focus:border-transparent"
                                          placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này...">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" 
                                    class="bg-[#ff6c2f] text-white px-6 py-2 rounded-md hover:bg-[#e55a28] transition">
                                Gửi đánh giá
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                            <div>
                                <p class="text-blue-800 font-medium">Thông báo</p>
                                <p class="text-blue-700 text-sm">
                                    @if(!auth()->check())
                                        Bạn cần <a href="{{ route('login') }}" class="underline">đăng nhập</a> để bình luận.
                                    @else
                                        Bạn cần mua sản phẩm này trước khi bình luận hoặc đã bình luận rồi.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-blue-800 font-medium">Thông báo</p>
                            <p class="text-blue-700 text-sm">
                                Bạn cần <a href="{{ route('login') }}" class="underline">đăng nhập</a> để bình luận.
                            </p>
                        </div>
                    </div>
                </div>
            @endauth
            
            <!-- Comments List -->
            <div class="space-y-6">
                @php
                    $approvedComments = $product->productComments()
                        ->where('status', 'approved')
                        ->whereNull('parent_id')
                        ->with(['user', 'replies.user'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp
                
                @if($approvedComments->count() > 0)
                    @foreach($approvedComments as $comment)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-[#ff6c2f] rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $comment->user->name }}</h4>
                                        <span class="text-gray-500 text-sm">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    
                                    @if($comment->rating)
                                        <div class="flex items-center mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-{{ $i <= $comment->rating ? 'yellow' : 'gray' }}-400"></i>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">{{ $comment->rating }}/5</span>
                                        </div>
                                    @endif
                                    
                                    <p class="text-gray-700 mb-4">{{ $comment->content }}</p>
                                    
                                    <!-- Reply Form -->
                                    @auth
                                        @php
                                            $canReply = $commentController->canComment($product->id);
                                        @endphp
                                        
                                        @if($canReply)
                                            <button onclick="toggleReplyForm({{ $comment->id }})" 
                                                    class="text-[#ff6c2f] hover:text-[#e55a28] text-sm font-medium">
                                                Phản hồi
                                            </button>
                                            
                                            <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
                                                <form action="{{ route('products.comments.reply', $comment->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <textarea name="reply_content" rows="3" 
                                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#ff6c2f] focus:border-transparent"
                                                                  placeholder="Viết phản hồi của bạn..."></textarea>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <button type="submit" 
                                                                class="bg-[#ff6c2f] text-white px-4 py-2 rounded-md hover:bg-[#e55a28] transition text-sm">
                                                            Gửi phản hồi
                                                        </button>
                                                        <button type="button" 
                                                                onclick="toggleReplyForm({{ $comment->id }})"
                                                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition text-sm">
                                                            Hủy
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                    
                                    <!-- Replies -->
                                    @if($comment->replies->count() > 0)
                                        <div class="mt-4 space-y-3">
                                            @foreach($comment->replies as $reply)
                                                @if($reply->status === 'approved')
                                                    <div class="bg-gray-50 rounded-lg p-4 ml-4">
                                                        <div class="flex items-start space-x-3">
                                                            <div class="flex-shrink-0">
                                                                <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center">
                                                                    <span class="text-white font-semibold text-xs">
                                                                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex-1">
                                                                <div class="flex items-center space-x-2 mb-1">
                                                                    <h5 class="font-medium text-gray-900 text-sm">{{ $reply->user->name }}</h5>
                                                                    <span class="text-gray-500 text-xs">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                                                                </div>
                                                                <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                        <i class="fas fa-comments text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Chưa có bình luận nào cho sản phẩm này.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>


@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-description');
            const fullDesc = document.getElementById('full-description');
            if (toggleBtn && fullDesc) {
                toggleBtn.addEventListener('click', function() {
                    fullDesc.classList.toggle('hidden');
                    toggleBtn.textContent = fullDesc.classList.contains('hidden') ? 'Xem mô tả chi tiết' :
                        'Ẩn mô tả chi tiết';
                });
            }

            @php
                $jsProductData = [
                    'type' => $product->type,
                    'variants' => $activeVariants
                        ->map(
                            fn($variant) => [
                                'id' => $variant->id,
                                'price' => $variant->price,
                                'sale_price' => $variant->sale_price,
                                'stock' => $variant->stock,
                                'sku' => $variant->sku,
                                'image' => $variant->image ? asset('storage/' . $variant->image) : null,
                                'attributes' => $variant->attributeValues->pluck('value', 'attribute.name'),
                            ],
                        )
                        ->values(),
                    'is_featured' => $product->is_featured,
                    'default_image' => $product->productAllImages->first() ? asset('storage/' . $product->productAllImages->first()->image_path) : asset('client_css/images/placeholder.svg'),
                ];
            @endphp

            const productData = @json($jsProductData);

            const mainImage = document.getElementById('main-image');
            const priceDisplay = document.querySelector('.price-display-area');
            const infoContainer = document.getElementById('info-container');
            const actionButtons = document.querySelectorAll(
                'button[onclick="addProductToCart()"], button[onclick="buyNow()"]');
            const variantForm = document.getElementById('variant-form');
            const quantityInput = document.getElementById('quantity');

            let state = {
                selectedOptions: {},
                activeVariant: null,
                quantity: 1,
            };

            const VND = n => (Number(n) || 0).toLocaleString('vi-VN') + '₫';
            const getCsrf = () => (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) ||
                '';

            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                let bgColor = 'bg-green-500';
                if (type === 'error') bgColor = 'bg-red-500';
                if (type === 'info') bgColor = 'bg-blue-500';
                notification.className =
                    `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full ${bgColor}`;
                notification.textContent = message;
                document.body.appendChild(notification);
                setTimeout(() => {
                    notification.style.transform = 'translateX(0)';
                }, 50);
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            function updateCartCount() {
                fetch('{{ route('carts.count') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const countEl = document.querySelector('.cart-count');
                            if (countEl) {
                                countEl.textContent = data.count;
                                countEl.style.display = data.count > 0 ? 'flex' : 'none';
                            }
                        }
                    }).catch(() => {});
            }

            function getActiveVariant() {
                if (productData.type !== 'variable') {
                    return productData.variants.length > 0 ? productData.variants[0] : null;
                }
                const attributeCount = variantForm ? variantForm.querySelectorAll('.attribute-options').length : 0;
                if (Object.keys(state.selectedOptions).length !== attributeCount) {
                    return null;
                }
                return productData.variants.find(variant =>
                    Object.entries(state.selectedOptions).every(([key, val]) => variant.attributes[key] === val)
                ) || null;
            }

            function updatePriceDisplay() {
                if (!priceDisplay) return;
                let html = '';

                if (productData.type === 'variable') {
                    if (state.activeVariant) {
                        const {
                            price,
                            sale_price
                        } = state.activeVariant;
                        if (sale_price && parseFloat(sale_price) < parseFloat(price)) {
                            html = `<div class="flex items-end gap-3">
                                        <span class="text-2xl line-through text-gray-500">${VND(price)}</span>
                                        <span class="text-4xl font-bold text-[#ff6c2f]">${VND(sale_price)}</span>
                                    </div>`;
                        } else {
                            html = `<span>${VND(price)}</span>`;
                        }
                    } else {
                        if (productData.variants.length > 0) {
                            const minPrice = Math.min(...productData.variants.map(v => v.price));
                            const maxPrice = Math.max(...productData.variants.map(v => v.price));
                            if (minPrice === maxPrice) {
                                html = `<span>${VND(minPrice)}</span>`;
                            } else {
                                html = `<span>${VND(minPrice)} - ${VND(maxPrice)}</span>`;
                            }
                        } else {
                            html = '<span class="text-3xl text-gray-500">Chọn thuộc tính</span>';
                        }
                    }
                } else {
                    if (productData.variants.length > 0) {
                        const {
                            price,
                            sale_price
                        } = productData.variants[0];
                        if (sale_price && parseFloat(sale_price) < parseFloat(price)) {
                            html = `<div class="flex items-end gap-3">
                                        <span class="text-2xl line-through text-gray-500">${VND(price)}</span>
                                        <span class="text-4xl font-bold text-[#ff6c2f]">${VND(sale_price)}</span>
                                    </div>`;
                        } else {
                            html = `<span>${VND(price)}</span>`;
                        }
                    } else {
                        html = '<span class="text-3xl text-gray-500">Tạm hết hàng</span>';
                    }
                }
                priceDisplay.innerHTML = html;
            }

            function updateInfoDisplay() {
                if (!infoContainer) return;
                infoContainer.innerHTML = '';
                let html = '';
                if (state.activeVariant) {
                    if (state.activeVariant.sku) html +=
                        `<span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full"><i class="fas fa-barcode mr-1"></i>SKU: ${state.activeVariant.sku}</span>`;
                    if (productData.is_featured) html +=
                        `<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full"><i class="fas fa-star mr-1"></i>Sản phẩm nổi bật</span>`;
                    html += state.activeVariant.stock > 0 ?
                        `<span class="text-green-600 font-semibold">Còn hàng: ${state.activeVariant.stock}</span>` :
                        `<span class="text-red-500 font-semibold">Phiên bản này tạm hết hàng</span>`;
                } else if (productData.type !== 'variable' && productData.variants.length > 0) {
                    const defaultVariant = productData.variants[0];
                    if (defaultVariant.sku) html +=
                        `<span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full"><i class="fas fa-barcode mr-1"></i>SKU: ${defaultVariant.sku}</span>`;
                    if (productData.is_featured) html +=
                        `<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full"><i class="fas fa-star mr-1"></i>Sản phẩm nổi bật</span>`;
                    html += defaultVariant.stock > 0 ?
                        `<span class="text-green-600 font-semibold">Còn hàng: ${defaultVariant.stock}</span>` :
                        `<span class="text-red-500 font-semibold">Sản phẩm tạm hết hàng</span>`;
                } else {
                    infoContainer.innerHTML =
                        '<span class="text-red-500 font-semibold">Vui lòng chọn thuộc tính</span>';
                    return;
                }
                infoContainer.innerHTML = html;
            }

            function updateActionButtons() {
                const isAvailable = state.activeVariant && state.activeVariant.stock > 0;
                actionButtons.forEach(btn => btn.disabled = !isAvailable);
            }

            function updateQuantityButtons() {
                const minusBtn = document.getElementById('quantity-minus-btn');
                const plusBtn = document.getElementById('quantity-plus-btn');
                
                if (minusBtn) {
                    if (state.quantity <= 1) {
                        minusBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        minusBtn.disabled = true;
                    } else {
                        minusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        minusBtn.disabled = false;
                    }
                }
                
                if (plusBtn) {
                    const maxStock = state.activeVariant ? state.activeVariant.stock : (productData.variants.length > 0 ? productData.variants[0].stock : 0);
                    if (maxStock > 0 && state.quantity >= maxStock) {
                        plusBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        plusBtn.disabled = true;
                        plusBtn.title = `Chỉ còn ${maxStock} sản phẩm trong kho`;
                    } else {
                        plusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        plusBtn.disabled = false;
                        plusBtn.title = '';
                    }
                }
            }

            function updateMainImage() {
                if (!mainImage) return;
                if (state.activeVariant && state.activeVariant.image) {
                    mainImage.src = state.activeVariant.image;
                } else {
                    mainImage.src = productData.thumbnail;
                }
                const productData = {
                    ...@json($jsProductData),
                    thumbnail: '{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}'
                };
            }

            function updateVariantOptionsUI() {
                if (!variantForm) return;

                const swatchSelectedClasses = ['ring-2', 'ring-offset-2', 'ring-[#ff6c2f]'];
                const buttonSelectedClasses = ['border-[#ff6c2f]', 'bg-[#ff6c2f]', 'text-white'];
                const buttonNormalClasses = ['border-gray-300'];

                variantForm.querySelectorAll('.variant-option-button').forEach(button => {
                    const {
                        attributeName,
                        attributeValue
                    } = button.dataset;
                    const isSelected = state.selectedOptions[attributeName] === attributeValue;

                    if (button.classList.contains('color-swatch')) {
                        button.classList.toggle(swatchSelectedClasses[0], isSelected);
                        button.classList.toggle(swatchSelectedClasses[1], isSelected);
                        button.classList.toggle(swatchSelectedClasses[2], isSelected);
                    } else {
                        button.classList.toggle(buttonSelectedClasses[0], isSelected);
                        button.classList.toggle(buttonSelectedClasses[1], isSelected);
                        button.classList.toggle(buttonSelectedClasses[2], isSelected);
                        button.classList.toggle(buttonNormalClasses[0], !isSelected);
                    }

                    const tempSelection = {
                        ...state.selectedOptions,
                        [attributeName]: attributeValue
                    };
                    const isPossible = productData.variants.some(variant =>
                        Object.entries(tempSelection).every(([key, val]) => variant.attributes[key] ===
                            val)
                    );

                    button.disabled = !isPossible;
                    button.classList.toggle('disabled:opacity-25', !isPossible);
                    button.classList.toggle('disabled:cursor-not-allowed', !isPossible);
                });
            }

            function handleVariantClick(button) {
                if (!button || button.disabled) return;
                const {
                    attributeName,
                    attributeValue
                } = button.dataset;
                if (state.selectedOptions[attributeName] === attributeValue) {
                    delete state.selectedOptions[attributeName];
                    document.querySelector(`[data-variant-name-display="${attributeName}"]`).textContent = '';
                } else {
                    state.selectedOptions[attributeName] = attributeValue;
                    document.querySelector(`[data-variant-name-display="${attributeName}"]`).textContent =
                        attributeValue;
                }
                render();
            }

            if (variantForm) {
                variantForm.addEventListener('click', (e) => {
                    const button = e.target.closest('.variant-option-button');
                    if (button) handleVariantClick(button);
                });
            }

            function render() {
                state.activeVariant = getActiveVariant();
                updatePriceDisplay();
                updateInfoDisplay();
                updateActionButtons();
                updateVariantOptionsUI();
                updateMainImage();
                updateQuantityButtons();
            }

            window.changeMainImage = (src) => {
                if (mainImage) mainImage.src = src;
            }

            window.updateQuantity = (change, productId) => {
                const maxStock = state.activeVariant ? state.activeVariant.stock : (productData.variants
                    .length > 0 ? productData.variants[0].stock : 0);
                let newQuantity = state.quantity + change;
                
                // Kiểm tra giới hạn tối thiểu
                if (newQuantity < 1) {
                    showNotification('Số lượng không thể nhỏ hơn 1', 'error');
                    return;
                }
                
                // Kiểm tra giới hạn tồn kho
                if (maxStock > 0 && newQuantity > maxStock) {
                    showNotification(`Chỉ còn ${maxStock} sản phẩm trong kho!`, 'error');
                    return;
                }
                
                state.quantity = newQuantity;
                quantityInput.value = state.quantity;
                updateQuantityButtons();
            }

            window.addProductToCart = () => {
                const attributeCount = variantForm ? variantForm.querySelectorAll('.attribute-options').length :
                    0;
                if (productData.type === 'variable' && Object.keys(state.selectedOptions).length !==
                    attributeCount) {
                    showNotification('Vui lòng chọn đầy đủ thuộc tính sản phẩm.', 'error');
                    return;
                }
                if (!state.activeVariant) {
                    showNotification('Không tìm thấy biến thể phù hợp.', 'error');
                    return;
                }
                if (state.activeVariant.stock <= 0) {
                    showNotification('Phiên bản này đã hết hàng.', 'error');
                    return;
                }

                fetch('{{ route('carts.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrf()
                        },
                        body: JSON.stringify({
                            product_id: {{ $product->id ?? 'null' }},
                            quantity: state.quantity,
                            variant_id: state.activeVariant.id
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message || 'Đã thêm vào giỏ hàng', 'success');
                            updateCartCount();
                            if (typeof reloadCartAjax === 'function') {
                                reloadCartAjax();
                            } else {
                                document.dispatchEvent(new CustomEvent('cart:updated'));
                            }
                        } else {
                            showNotification(data.message || 'Có lỗi xảy ra', 'error');
                        }
                    })
                    .catch((error) => {
                    console.error('BuyNow error:', error);
                    showNotification('Lỗi kết nối, vui lòng thử lại.', 'error');
                });
            }

            window.buyNow = () => {
                const attributeCount = variantForm ? variantForm.querySelectorAll('.attribute-options').length : 0;
                
                if (productData.type === 'variable' && Object.keys(state.selectedOptions).length !== attributeCount) {
                    showNotification('Vui lòng chọn đầy đủ thuộc tính sản phẩm.', 'error');
                    return;
                }
                
                if (!state.activeVariant) {
                    showNotification('Không tìm thấy biến thể phù hợp.', 'error');
                    return;
                }
                
                if (state.activeVariant.stock <= 0) {
                    showNotification('Phiên bản này đã hết hàng.', 'error');
                    return;
                }

                console.log('Sending buyNow request with data:', {
                    product_id: {{ $product->id ?? 'null' }},
                    quantity: state.quantity,
                    variant_id: state.activeVariant.id
                });
                
                // Set session buynow trước khi chuyển đến checkout
                fetch('{{ route('carts.setBuyNow') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrf()
                    },
                    body: JSON.stringify({
                        product_id: {{ $product->id ?? 'null' }},
                        quantity: state.quantity,
                        variant_id: state.activeVariant.id
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log('BuyNow response:', data);
                    if (data.success) {
                        // Chuyển đến trang checkout với buynow session
                        console.log('Redirecting to checkout...');
                        window.location.href = '{{ route('checkout.index') }}';
                    } else {
                        showNotification(data.message || 'Có lỗi xảy ra, không thể mua ngay.', 'error');
                    }
                })
                .catch((error) => {
                    console.error('BuyNow error:', error);
                    showNotification('Lỗi kết nối, vui lòng thử lại.', 'error');
                });
            }

            render();
        });
    </script>
@endpush

