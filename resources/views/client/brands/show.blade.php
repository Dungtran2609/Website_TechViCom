@extends('client.layouts.app')

@section('title', $brand->name . ' - Thương hiệu')

@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- BREADCRUMB ngay dưới header --}}
        <nav class="text-sm mb-4">
            <ol class="flex items-center gap-2 text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-orange-600">Trang chủ</a></li>
                <li>/</li>
                <li><a href="{{ route('brands.index') }}" class="hover:text-orange-600">Thương hiệu</a></li>
                <li>/</li>
                <li class="text-gray-800 font-medium">{{ $brand->name }}</li>
            </ol>
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
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Sản phẩm thuộc thương hiệu</h2>
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
                            class="absolute top-2 right-2 w-9 h-9 rounded-full bg-white/90 backdrop-blur shadow flex items-center justify-center hover:bg-white wishlist-btn transition group"
                            aria-label="Yêu thích"
                            onclick="event.stopPropagation(); toggleWishlist(this, {{ $product->id }})"
                            data-tooltip="Thêm vào yêu thích">
                            <i class="far fa-heart text-gray-700 group-hover:text-rose-600 transition-all text-xl"></i>
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
                <div class="col-span-5 text-center text-gray-500">Không có sản phẩm nào thuộc thương hiệu này.</div>
            @endforelse
        </div>
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleWishlist(btn, productId) {
        // Toggle trạng thái tim
        const icon = btn.querySelector('i');
        const tooltip = btn.querySelector('.wishlist-tooltip');
        if (btn.classList.contains('active')) {
            btn.classList.remove('active');
            icon.classList.remove('fa-solid', 'text-rose-600');
            icon.classList.add('fa-regular', 'text-gray-700');
            if (tooltip) tooltip.classList.add('hidden');
        } else {
            btn.classList.add('active');
            icon.classList.remove('fa-regular', 'text-gray-700');
            icon.classList.add('fa-solid', 'text-rose-600');
            if (tooltip) tooltip.classList.remove('hidden');
            setTimeout(() => { if (tooltip) tooltip.classList.add('hidden'); }, 1200);
        }
        // TODO: Gửi AJAX thêm/xóa wishlist nếu muốn
    }
    // Tooltip hover
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                if (!btn.classList.contains('active')) {
                    btn.querySelector('i').classList.add('text-rose-600');
                }
            });
            btn.addEventListener('mouseleave', function() {
                if (!btn.classList.contains('active')) {
                    btn.querySelector('i').classList.remove('text-rose-600');
                }
            });
        });
    });
</script>
@endpush


