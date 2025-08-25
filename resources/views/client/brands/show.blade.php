@extends('client.layouts.app')

@section('title', $brand->name . ' - Thương hiệu')

@section('content')
    <div class="techvicom-container py-6">

        <!-- Breadcrumb -->
        <nav class="bg-white border-b border-gray-200 py-3 mb-6">
            <div class="techvicom-container">
                <div class="flex items-center space-x-2 text-sm">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#ff6c2f]">Trang chủ</a>
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <a href="{{ route('brands.index') }}" class="text-gray-500 hover:text-[#ff6c2f]">Thương hiệu</a>
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <span class="text-gray-900 font-medium">{{ $brand->name }}</span>
                </div>
            </div>
        </nav>

        {{-- Card thương hiệu --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 flex flex-col md:flex-row items-center gap-8 hover:shadow-xl transition">
            <div class="w-40 h-40 flex-shrink-0 overflow-hidden rounded-2xl border border-gray-200 shadow-md bg-white flex items-center justify-center">
                @if ($brand->image)
                    <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}"
                         class="w-full h-full object-contain transition-transform duration-300 hover:scale-105"
                         onerror="this.onerror=null; this.src='{{ asset('client_css/images/brand-default.jpg') }}';">
                @else
                    <img src="{{ asset('client_css/images/brand-default.jpg') }}" alt="No image" class="w-full h-full object-contain">
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">{{ $brand->name }}</h1>
                @if ($brand->description)
                    <p class="mt-3 text-gray-600 leading-relaxed border-t pt-4 text-lg">{{ $brand->description }}</p>
                @endif
            </div>
        </div>



        {{-- Danh sách sản phẩm --}}
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Sản phẩm thuộc thương hiệu 
            @if($products->total() > 0)
                <span class="text-sm font-normal text-gray-500">({{ $products->total() }} sản phẩm)</span>
            @endif
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse ($products as $product)
                <div class="bg-white rounded-xl shadow group overflow-hidden flex flex-col hover:shadow-lg transition cursor-pointer"
                     onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                    {{-- Ảnh sản phẩm --}}
                    <div class="relative w-full h-56 bg-gray-50 flex items-center justify-center">
                        <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-contain rounded-t-lg transition-transform duration-300 group-hover:scale-105"
                             onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}';">
                        {{-- Icon tim (wishlist) góc phải --}}
                        <button
                            class="absolute top-2 right-2 w-9 h-9 rounded-full bg-white/90 backdrop-blur shadow flex items-center justify-center hover:bg-white favorite-btn"
                            data-product-id="{{ $product->id }}"
                            aria-label="Yêu thích"
                            onclick="event.stopPropagation(); toggleFavorite({{ $product->id }}, this)">
                            <i class="far fa-heart text-gray-700 group-hover:text-rose-600 favorite-icon-{{ $product->id }}" id="favorite-icon-{{ $product->id }}"></i>
                        </button>
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 text-base">{{ $product->name }}</h3>
                        <div class="text-lg font-bold text-orange-500 mb-2">
                            @if ($product->type === 'simple' && $product->variants->count() > 0)
                                @php $variant = $product->variants->first(); @endphp
                                {{ number_format($variant->price) }}₫
                            @elseif ($product->type === 'variable' && $product->variants->count() > 0)
                                @php
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');
                                @endphp
                                @if ($minPrice === $maxPrice)
                                    {{ number_format($minPrice) }}₫
                                @else
                                    {{ number_format($minPrice) }} - {{ number_format($maxPrice) }}₫
                                @endif
                            @else
                                Liên hệ
                            @endif
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="flex text-yellow-400">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                            <span class="text-gray-500 ml-2">({{ $product->reviews_count ?? 0 }})</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-5">
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-box-open text-6xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Chưa có sản phẩm</h3>
                        <p class="text-gray-500 mb-4">Hiện tại thương hiệu <strong>{{ $brand->name }}</strong> chưa có sản phẩm nào.</p>
                        <a href="{{ route('brands.index') }}" class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Xem tất cả thương hiệu
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>

    <script>
        // Hàm toggle favorite
        function toggleFavorite(productId, button) {
            console.log('Toggle favorite for product:', productId);
            
            // Hiển thị loading
            const icon = button.querySelector('i');
            const originalClass = icon.className;
            icon.className = 'fas fa-spinner fa-spin text-gray-700';
            
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
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    // Cập nhật icon bằng ID
                    const favoriteIcon = document.getElementById(`favorite-icon-${productId}`);
                    if (favoriteIcon) {
                        if (data.is_favorite) {
                            favoriteIcon.className = 'fas fa-heart text-rose-600';
                            console.log('Set icon to filled heart');
                        } else {
                            favoriteIcon.className = 'far fa-heart text-gray-700 group-hover:text-rose-600';
                            console.log('Set icon to empty heart');
                        }
                    } else {
                        console.error('Icon not found for product:', productId);
                    }
                    
                    // Hiển thị thông báo
                    showNotification(data.message, 'success');
                } else {
                    // Khôi phục icon
                    icon.className = originalClass;
                    
                    if (data.redirect) {
                        // Redirect đến trang login
                        showNotification('Vui lòng đăng nhập để sử dụng chức năng yêu thích', 'error');
                        setTimeout(() => {
                            openAuthModal();
                        }, 2000);
                    } else {
                        showNotification(data.message, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Khôi phục icon
                icon.className = originalClass;
                showNotification('Có lỗi xảy ra, vui lòng thử lại', 'error');
            });
        }

        // Hàm hiển thị thông báo
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Tự động ẩn sau 3 giây
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Kiểm tra trạng thái yêu thích khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, checking favorites...');
            
            const productIds = Array.from(document.querySelectorAll('.favorite-btn'))
                .map(btn => btn.dataset.productId);
            
            console.log('Product IDs found:', productIds);
            
            if (productIds.length > 0) {
                fetch('{{ route("accounts.favorites.check") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_ids: productIds
                    })
                })
                .then(response => {
                    console.log('Check favorites response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Check favorites response data:', data);
                    
                    if (data.success) {
                        data.favorites.forEach(productId => {
                            const icon = document.getElementById(`favorite-icon-${productId}`);
                            if (icon) {
                                icon.className = 'fas fa-heart text-rose-600';
                                console.log('Set initial favorite for product:', productId);
                            } else {
                                console.error('Icon not found for initial favorite check:', productId);
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error checking favorites:', error);
                    // Nếu lỗi 401 (unauthorized), user chưa đăng nhập
                    if (error.message && error.message.includes('401')) {
                        console.log('User not logged in, skipping favorite check');
                    }
                });
            }
        });
    </script>
@endsection


