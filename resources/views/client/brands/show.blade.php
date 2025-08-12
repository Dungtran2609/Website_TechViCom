@extends('client.layouts.app')

@section('title', $brand->name . ' - Thương hiệu')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Card thương hiệu --}}
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-10 flex flex-col md:flex-row items-center gap-6 transition hover:shadow-xl">
        {{-- Ảnh thương hiệu --}}
        <div class="w-32 h-32 flex-shrink-0 overflow-hidden rounded-xl border border-gray-100 shadow-sm">
            @if ($brand->image)
                <img src="{{ asset('storage/' . $brand->image) }}"
                     alt="{{ $brand->name }}"
                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                     onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}';">
            @else
                <img src="{{ asset('client_css/images/brand-default.jpg') }}"
                     alt="No image"
                     class="w-full h-full object-cover">
            @endif
        </div>

        {{-- Thông tin --}}
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $brand->name }}</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-8 text-gray-700">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-hashtag text-orange-500"></i>
                    <span class="font-semibold">ID:</span> <span>{{ $brand->id }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-link text-orange-500"></i>
                    <span class="font-semibold">Slug:</span> <span>{{ $brand->slug }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-eye text-orange-500"></i>
                    <span class="font-semibold">Trạng thái:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $brand->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $brand->status ? 'Hiển thị' : 'Ẩn' }}
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar-plus text-orange-500"></i>
                    <span class="font-semibold">Ngày tạo:</span>
                    <span>{{ $brand->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar-check text-orange-500"></i>
                    <span class="font-semibold">Ngày cập nhật:</span>
                    <span>{{ $brand->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            @if ($brand->description)
                <p class="mt-5 text-gray-600 leading-relaxed border-t pt-4">
                    {{ $brand->description }}
                </p>
            @endif
        </div>
    </div>

    {{-- Danh sách sản phẩm --}}
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Sản phẩm thuộc thương hiệu</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition cursor-pointer group overflow-hidden"
                onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                
                {{-- Ảnh sản phẩm --}}
                <div class="relative">
                    @if ($product->productAllImages->count() > 0)
                        <img src="{{ asset('uploads/products/' . $product->productAllImages->first()->image_url) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"
                             onerror="this.onerror=null; this.src='{{ asset('client_css/images/placeholder.svg') }}';">
                    @else
                        <img src="{{ asset('client_css/images/placeholder.svg') }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover">
                    @endif

                    {{-- Badge HOT --}}
                    <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-bold">HOT</span>

                    {{-- Yêu thích --}}
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                        <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50"
                            onclick="event.stopPropagation();">
                            <i class="fas fa-heart text-gray-400 hover:text-orange-500"></i>
                        </button>
                    </div>
                </div>

                {{-- Thông tin sản phẩm --}}
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400 text-sm">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <span class="text-gray-500 text-sm ml-2">(0)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            @if ($product->type === 'simple' && $product->variants->count() > 0)
                                @php $variant = $product->variants->first(); @endphp
                                <span class="text-lg font-bold text-orange-500">{{ number_format($variant->price) }}₫</span>
                            @elseif($product->type === 'variable' && $product->variants->count() > 0)
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
                            class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center text-gray-500">Không có sản phẩm nào thuộc thương hiệu này.</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection
