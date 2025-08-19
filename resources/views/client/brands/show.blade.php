@extends('client.layouts.app')

@section('title', $brand->name . ' - Thương hiệu')

@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- BREADCRUMB ngay dưới header --}}
        <nav class="text-sm mb-4">
            <ol class="flex items-center gap-2 text-gray-500">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-orange-600">Trang chủ</a>
                </li>
                <li>/</li>
                <li>
                    <a href="{{ route('brands.index') }}" class="hover:text-orange-600">Thương hiệu</a>
                </li>
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
                    <img src="{{ asset('client_css/images/brand-default.jpg') }}" alt="No image"
                         class="w-full h-full object-contain">
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

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($products as $product)
                <div class="bg-white rounded-xl shadow group overflow-hidden flex flex-col hover:shadow-lg transition cursor-pointer"
                     onclick="window.location.href='{{ route('products.show', $product->id) }}'">

                    {{-- Ảnh sản phẩm --}}
                    <div class="relative w-full h-52 bg-gray-50 flex items-center justify-center">
                        <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('client_css/images/placeholder.svg') }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-contain rounded-t-lg transition-transform duration-300 group-hover:scale-105"
                             onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}';">
                        <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-bold">HOT</span>

                        {{-- nút yêu thích giống icon trái tim trên ảnh mẫu (tùy chọn) --}}
                        <button class="absolute top-2 right-2 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center hover:bg-white"
                                onclick="event.stopPropagation(); /* TODO: handle wishlist */">
                            <i class="far fa-heart text-gray-600 group-hover:text-orange-500"></i>
                        </button>
                    </div>

                    {{-- Nội dung --}}
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 text-lg">
                                {{ $product->name }}
                            </h3>

                            {{-- rating + lượt xem giống ảnh mẫu --}}
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                    <span class="text-gray-500 ml-2">( {{ $product->reviews_count ?? 0 }} )</span>
                                </div>
                                <div class="flex items-center gap-1 text-gray-500">
                                    <i class="far fa-eye"></i>
                                    <span>{{ number_format($product->views ?? 0) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Giá + nút giỏ --}}
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                @if ($product->type === 'simple' && $product->variants->count() > 0)
                                    @php $variant = $product->variants->first(); @endphp
                                    <span class="text-lg font-bold text-orange-500">{{ number_format($variant->price) }}₫</span>
                                @elseif ($product->type === 'variable' && $product->variants->count() > 0)
                                    @php
                                        $minPrice = $product->variants->min('price');
                                        $maxPrice = $product->variants->max('price');
                                    @endphp
                                    @if ($minPrice === $maxPrice)
                                        <span class="text-lg font-bold text-orange-500">{{ number_format($minPrice) }}₫</span>
                                    @else
                                        <span class="text-lg font-bold text-orange-500">{{ number_format($minPrice) }} - {{ number_format($maxPrice) }}₫</span>
                                    @endif
                                @else
                                    <span class="text-lg font-bold text-orange-500">Liên hệ</span>
                                @endif
                            </div>

                            <button onclick="event.stopPropagation(); addToCart({{ $product->id }}, null, 1)"
                                    class="bg-orange-500 text-white px-3 py-2 rounded-lg hover:bg-orange-600 transition flex items-center gap-2">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center text-gray-500">
                    Không có sản phẩm nào thuộc thương hiệu này.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
@endsection


