@extends('client.layouts.app')

@section('title', isset($product) ? $product->name . ' - Techvicom' : 'Chi tiết sản phẩm - Techvicom')

@section('content')
<!-- Breadcrumb -->
<nav class="bg-white border-b border-gray-200 py-3">
    <div class="container mx-auto px-4">
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <a href="{{ route('client.products.index') }}" class="text-gray-500 hover:text-[#ff6c2f]">Sản phẩm</a>
            @if(isset($product))
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $product->name }}</span>
            @endif
        </div>
    </div>
</nav>

@if(isset($product))
<!-- Product Detail -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div class="space-y-4">
                <div class="aspect-square bg-white rounded-lg border border-gray-200 overflow-hidden">
                    @if($product->productAllImages->count() > 0)
                        <img id="main-image" src="{{ asset($product->productAllImages->first()->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <img id="main-image" src="{{ asset('admin_css/images/placeholder.jpg') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @endif
                </div>
                
                @if($product->productAllImages->count() > 1)
                <div class="grid grid-cols-4 gap-4">
                    @foreach($product->productAllImages as $image)
                    <div class="aspect-square bg-white rounded-lg border border-gray-200 overflow-hidden cursor-pointer thumbnail"
                         onclick="changeMainImage('{{ asset($image->image_path) }}')">
                        <img src="{{ asset($image->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="flex items-center space-x-4 text-gray-600 mb-4">
                        @if($product->brand)
                            <span class="flex items-center">
                                <i class="fas fa-tag mr-2"></i>
                                <strong>Thương hiệu:</strong> 
                                <span class="ml-1 text-blue-600 font-semibold">{{ $product->brand->name }}</span>
                            </span>
                        @endif
                        
                        @if($product->category)
                            <span class="flex items-center">
                                <i class="fas fa-folder mr-2"></i>
                                <strong>Danh mục:</strong> 
                                <span class="ml-1">{{ $product->category->name }}</span>
                            </span>
                        @endif
                    </div>
                    
                    @if($product->short_description)
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg mb-4">
                            <p class="text-gray-700 italic">{{ $product->short_description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Price and Stock Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-4">
                            @if($product->type === 'simple' && $product->variants->count() > 0)
                                @php $variant = $product->variants->first(); @endphp
                                <span class="text-3xl font-bold text-[#ff6c2f]">
                                    {{ number_format($variant->price, 0, ',', '.') }}₫
                                </span>
                                @if($variant->sale_price && $variant->sale_price < $variant->price)
                                    <span class="text-xl text-gray-500 line-through">
                                        {{ number_format($variant->price, 0, ',', '.') }}₫
                                    </span>
                                    @php
                                        $discount = round((($variant->price - $variant->sale_price) / $variant->price) * 100);
                                    @endphp
                                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm font-semibold">
                                        -{{ $discount }}%
                                    </span>
                                @endif
                            @elseif($product->type === 'variable' && $product->variants->count() > 0)
                                @php
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');
                                @endphp
                                @if($minPrice === $maxPrice)
                                    <span class="text-3xl font-bold text-[#ff6c2f]">
                                        {{ number_format($minPrice, 0, ',', '.') }}₫
                                    </span>
                                @else
                                    <span class="text-3xl font-bold text-[#ff6c2f]">
                                        {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}₫
                                    </span>
                                @endif
                            @else
                                <span class="text-3xl font-bold text-[#ff6c2f]">Liên hệ</span>
                            @endif
                        </div>
                        
                        <!-- Stock Status -->
                        @if($product->type === 'simple' && $product->variants->count() > 0)
                            @php $variant = $product->variants->first(); @endphp
                            <div class="text-right stock-info">
                                <div class="flex items-center justify-end mb-1">
                                    @if($variant->stock > 0)
                                        <div class="flex items-center text-green-600">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            <span class="font-semibold">Còn hàng</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-red-600">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            <span class="font-semibold">Hết hàng</span>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">Kho: {{ $variant->stock }} sản phẩm</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product Info Tags -->
                    <div class="flex flex-wrap gap-2 text-sm">
                        @if($product->variants->first() && $product->variants->first()->sku)
                            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full">
                                <i class="fas fa-barcode mr-1"></i>SKU: {{ $product->variants->first()->sku }}
                            </span>
                        @endif
                        
                        @if($product->status == 1)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                <i class="fas fa-eye mr-1"></i>Đang bán
                            </span>
                        @endif
                        
                        @if($product->is_featured)
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                                <i class="fas fa-star mr-1"></i>Sản phẩm nổi bật
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Product Variants -->
                @if($product->variants->count() > 0)
                <div class="space-y-4">
                    @if($product->type === 'variable')
                        @php
                            $groupedVariants = $product->variants->groupBy(function($variant) {
                                return $variant->attributeValues->first()->attribute->name ?? 'Default';
                            });
                        @endphp
                        
                        @foreach($groupedVariants as $attributeName => $variants)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $attributeName }}:</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($variants as $variant)
                                <button type="button" 
                                        class="variant-option px-4 py-2 border border-gray-300 rounded-lg hover:border-[#ff6c2f] focus:border-[#ff6c2f] focus:ring-1 focus:ring-[#ff6c2f] transition"
                                        data-variant-id="{{ $variant->id }}"
                                        data-price="{{ $variant->price }}"
                                        data-stock="{{ $variant->stock }}">
                                    <div class="text-center">
                                        <div class="font-medium">
                                            @foreach($variant->attributeValues as $attrValue)
                                                {{ $attrValue->value }}{{ !$loop->last ? ' / ' : '' }}
                                            @endforeach
                                        </div>
                                        <div class="text-xs text-gray-500">{{ number_format($variant->price) }}₫</div>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                        <!-- Simple Product - Show single variant info -->
                        @php $variant = $product->variants->first(); @endphp
                        @if($variant && $variant->attributeValues->count() > 0)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-700 mb-3">Thông số sản phẩm:</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach($variant->attributeValues as $attrValue)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">{{ $attrValue->attribute->name }}:</span>
                                            <span class="font-medium">{{ $attrValue->value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                @endif

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng:</label>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="updateQuantity(-1)" 
                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <input type="number" id="quantity" value="1" min="1" 
                               class="w-20 text-center border border-gray-300 rounded-lg py-2 focus:outline-none focus:border-[#ff6c2f]">
                        <button type="button" onclick="updateQuantity(1)" 
                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <button type="button" onclick="addProductToCart()" 
                            class="w-full bg-[#ff6c2f] text-white py-3 px-6 rounded-lg hover:bg-[#e55a28] transition font-medium">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Thêm vào giỏ hàng
                    </button>
                    <button type="button" onclick="buyNow()" 
                            class="w-full bg-gray-900 text-white py-3 px-6 rounded-lg hover:bg-gray-800 transition font-medium">
                        Mua ngay
                    </button>
                    
                    
                </div>

                <!-- Product Features -->
                @if($product->long_description || $product->short_description)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Mô tả sản phẩm</h3>
                    
                    @if($product->short_description)
                        <div class="mb-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                            <h4 class="font-medium text-blue-900 mb-2">Mô tả ngắn:</h4>
                            <p class="text-blue-800">{{ $product->short_description }}</p>
                        </div>
                    @endif
                    
                    @if($product->long_description)
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            <h4 class="font-medium text-gray-900 mb-3">Mô tả chi tiết:</h4>
                            <div class="whitespace-pre-line">{{ $product->long_description }}</div>
                        </div>
                    @endif
                </div>
                @endif
                
                <!-- Shipping Information -->
                @if($product->variants->first() && ($product->variants->first()->weight || $product->variants->first()->length))
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin vận chuyển</h3>
                    @php $variant = $product->variants->first(); @endphp
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        @if($variant->weight)
                            <div class="flex items-center">
                                <i class="fas fa-weight-hanging text-gray-400 mr-2"></i>
                                <span class="text-gray-600">Cân nặng:</span>
                                <span class="ml-1 font-medium">{{ $variant->weight }} kg</span>
                            </div>
                        @endif
                        
                        @if($variant->length)
                            <div class="flex items-center">
                                <i class="fas fa-ruler-horizontal text-gray-400 mr-2"></i>
                                <span class="text-gray-600">Dài:</span>
                                <span class="ml-1 font-medium">{{ $variant->length }} cm</span>
                            </div>
                        @endif
                        
                        @if($variant->width)
                            <div class="flex items-center">
                                <i class="fas fa-ruler-vertical text-gray-400 mr-2"></i>
                                <span class="text-gray-600">Rộng:</span>
                                <span class="ml-1 font-medium">{{ $variant->width }} cm</span>
                            </div>
                        @endif
                        
                        @if($variant->height)
                            <div class="flex items-center">
                                <i class="fas fa-ruler-combined text-gray-400 mr-2"></i>
                                <span class="text-gray-600">Cao:</span>
                                <span class="ml-1 font-medium">{{ $variant->height }} cm</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
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
                <a href="{{ route('client.products.show', $relatedProduct->id) }}">
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
        <a href="{{ route('client.products.index') }}" 
           class="inline-flex items-center bg-[#ff6c2f] text-white px-6 py-3 rounded-lg hover:bg-[#e55a28] transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại danh sách sản phẩm
        </a>
    </div>
</section>

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
                        
                        <form action="{{ route('client.products.comments.store', $product->id) }}" method="POST">
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
                                                <form action="{{ route('client.products.comments.reply', $comment->id) }}" method="POST">
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
@endif


@endsection

@push('scripts')
<script>
let selectedVariantId = null;
let currentPrice = {{ $product->variants->first()->price ?? 0 }};
let currentStock = {{ $product->variants->first()->stock ?? 0 }};

function changeMainImage(imageSrc) {
    document.getElementById('main-image').src = imageSrc;
}

function updateQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    let currentQuantity = parseInt(quantityInput.value);
    let newQuantity = currentQuantity + change;
    
    if (newQuantity >= 1 && newQuantity <= currentStock) {
        quantityInput.value = newQuantity;
    }
}

// Variant selection
document.querySelectorAll('.variant-option').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all variants
        document.querySelectorAll('.variant-option').forEach(btn => {
            btn.classList.remove('border-[#ff6c2f]', 'bg-[#ff6c2f]', 'text-white');
            btn.classList.add('border-gray-300');
        });
        
        // Add active class to selected variant
        this.classList.remove('border-gray-300');
        this.classList.add('border-[#ff6c2f]', 'bg-[#ff6c2f]', 'text-white');
        
        // Update selected variant
        selectedVariantId = this.dataset.variantId;
        currentPrice = parseFloat(this.dataset.price);
        currentStock = parseInt(this.dataset.stock);
        
        // Update price and stock display
        updatePriceDisplay();
        updateStockDisplay();
        
        // Reset quantity to 1 when variant changes
        document.getElementById('quantity').value = 1;
    });
});

function updatePriceDisplay() {
    const priceElement = document.querySelector('.text-3xl.font-bold.text-\\[\\#ff6c2f\\]');
    if (priceElement) {
        priceElement.textContent = new Intl.NumberFormat('vi-VN').format(currentPrice) + '₫';
    }
}

function updateStockDisplay() {
    const stockElement = document.querySelector('.stock-info');
    if (stockElement) {
        if (currentStock > 0) {
            stockElement.innerHTML = `
                <div class="flex items-center text-green-600">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-semibold">Còn hàng</span>
                </div>
                <p class="text-sm text-gray-500">Kho: ${currentStock} sản phẩm</p>
            `;
        } else {
            stockElement.innerHTML = `
                <div class="flex items-center text-red-600">
                    <i class="fas fa-times-circle mr-2"></i>
                    <span class="font-semibold">Hết hàng</span>
                </div>
                <p class="text-sm text-gray-500">Kho: 0 sản phẩm</p>
            `;
        }
    }
}

function addProductToCart() {
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    const productId = {{ $product->id ?? 'null' }};
    
    console.log('Add to cart - Product:', productId, 'Quantity:', quantity);
    
    if (!productId) {
        showNotification('Có lỗi xảy ra', 'error');
        return;
    }
    
    // Use proper API endpoint
    const cartData = {
        product_id: productId,
        quantity: quantity,
        variant_id: selectedVariantId || null
    };
    
    fetch('{{ route("carts.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(cartData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showNotification(data.message || 'Đã thêm sản phẩm vào giỏ hàng', 'success');
            updateCartCount();
        } else {
            showNotification(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng', 'error');
    });
}

function buyNow() {
    addProductToCart();
    
    // Redirect to checkout after a short delay
    setTimeout(() => {
        window.location.href = '{{ route("checkout.index") }}';
    }, 1000);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500';
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function updateCartCount() {
    // Update cart count in header if it exists
    fetch('{{ route("carts.count") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartCountElement = document.querySelector('#cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = data.count;
                    cartCountElement.style.display = data.count > 0 ? 'flex' : 'none';
                }
                
                // Also reload cart sidebar items if function exists
                if (typeof window.loadCartItems === 'function') {
                    window.loadCartItems();
                }
            }
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
}

// Update quantity input max value based on stock
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.setAttribute('max', currentStock);
    }
    
    // For simple products, set default variant if available
    @if($product->type === 'simple' && $product->variants->count() > 0)
        selectedVariantId = {{ $product->variants->first()->id }};
    @endif
});

},
        quantity: 1,
        variant_id: null
    };
    
    console.log('Test data:', testData);
    
    fetch('{{ route("carts.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(testData)
    })
    .then(response => {
        console.log('Test Response status:', response.status);
        console.log('Test Response headers:', response.headers);
        return response.text(); // Get as text first to see raw response
    })
    .then(text => {
        console.log('Test Raw response:', text);
        try {
            const data = JSON.parse(text);
            console.log('Test Parsed response:', data);
            alert('Test Response: ' + JSON.stringify(data));
        } catch (e) {
            console.error('Test JSON parse error:', e);
            alert('Test Raw Response: ' + text);
        }
    })
    .catch(error => {
        console.error('Test Error:', error);
        alert('Test Error: ' + error);
    });
}

}')
        .then(response => response.json())
        .then(data => {
            alert(`User ID: ${data.user_id}\nProduct ID: ${data.product_id}\nCan Comment: ${data.can_comment}\nMessage: ${data.message}`);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error checking comment permission');
        });
}
</script>
@endpush
