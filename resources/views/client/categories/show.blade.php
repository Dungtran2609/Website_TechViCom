@extends('client.layouts.app')

@section('title', $category->name . ' - Techvicom')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Trang chủ</a></li>
                <li><span class="text-gray-400">></span></li>
                @if($category->parent)
                    <li><a href="{{ route('categories.show', $category->parent->slug) }}" class="text-gray-500 hover:text-gray-700">{{ $category->parent->name }}</a></li>
                    <li><span class="text-gray-400">></span></li>
                @endif
                <li><span class="text-gray-900 font-medium">{{ $category->name }}</span></li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Danh mục</h3>
                    
                    <!-- All Categories -->
                    <div class="space-y-3">
                        @foreach($parentCategories as $parentCategory)
                            <div>
                                <a href="{{ route('categories.show', $parentCategory->slug) }}" 
                                   class="flex items-center justify-between py-2 px-3 rounded {{ $parentCategory->id == $category->id || ($category->parent && $category->parent->id == $parentCategory->id) ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span>{{ $parentCategory->name }}</span>
                                    @if($parentCategory->children->count() > 0)
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    @endif
                                </a>
                                
                                <!-- Subcategories -->
                                @if($parentCategory->children->count() > 0 && ($parentCategory->id == $category->id || ($category->parent && $category->parent->id == $parentCategory->id)))
                                    <div class="ml-4 mt-2 space-y-1">
                                        @foreach($parentCategory->children as $child)
                                            <a href="{{ route('categories.show', $child->slug) }}" 
                                               class="block py-1 px-2 text-sm rounded {{ $child->id == $category->id ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Filter Section -->
                    <div class="mt-8">
                        <h4 class="font-semibold mb-4">Bộ lọc</h4>
                        
                        <!-- Price Range -->
                        <div class="mb-6">
                            <h5 class="text-sm font-medium mb-3">Khoảng giá</h5>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" value="0-5000000">
                                    <span class="text-sm">Dưới 5 triệu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" value="5000000-10000000">
                                    <span class="text-sm">5 - 10 triệu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" value="10000000-20000000">
                                    <span class="text-sm">10 - 20 triệu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" value="20000000-50000000">
                                    <span class="text-sm">20 - 50 triệu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" value="50000000-999999999">
                                    <span class="text-sm">Trên 50 triệu</span>
                                </label>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-6">
                            <h5 class="text-sm font-medium mb-3">Thương hiệu</h5>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" value="apple">
                                    <span class="text-sm">Apple</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" value="samsung">
                                    <span class="text-sm">Samsung</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" value="xiaomi">
                                    <span class="text-sm">Xiaomi</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-lg shadow-md">
                    <!-- Category Header -->
                    <div class="p-6 border-b">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                                <p class="text-gray-600 mt-1">{{ $products->total() }} sản phẩm</p>
                            </div>
                            
                            <!-- Sort Options -->
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">Sắp xếp:</span>
                                <select class="border border-gray-300 rounded px-3 py-1 text-sm">
                                    <option value="newest">Mới nhất</option>
                                    <option value="price-asc">Giá thấp đến cao</option>
                                    <option value="price-desc">Giá cao đến thấp</option>
                                    <option value="popular">Phổ biến</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="p-6">
                        @if($products->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                @foreach($products as $product)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition cursor-pointer group"
                                         onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                                        <div class="relative">
                                            @if($product->productAllImages->count() > 0)
                                                <img src="{{ asset('uploads/products/' . $product->productAllImages->first()->image_url) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-48 object-cover rounded-t-lg">
                                            @else
                                                <img src="{{ asset('client_css/images/placeholder.svg') }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-48 object-cover rounded-t-lg">
                                            @endif
                                            
                                            {{-- Discount badge will be added later based on variants --}}
                                            
                                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                                <button class="bg-white rounded-full p-2 shadow-md hover:bg-gray-50">
                                                    <i class="fas fa-heart text-gray-400 hover:text-red-500"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                                            
                                            <div class="flex items-center mb-2">
                                                <div class="flex text-yellow-400 text-sm">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                </div>
                                                <span class="text-gray-500 text-sm ml-2">(0)</span>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    @if($product->type === 'simple' && $product->variants->count() > 0)
                                                        @php
                                                            $variant = $product->variants->first();
                                                        @endphp
                                                        <span class="text-lg font-bold text-gray-900">{{ number_format($variant->price) }}₫</span>
                                                    @elseif($product->type === 'variable' && $product->variants->count() > 0)
                                                        @php
                                                            $minPrice = $product->variants->min('price');
                                                            $maxPrice = $product->variants->max('price');
                                                        @endphp
                                                        @if($minPrice === $maxPrice)
                                                            <span class="text-lg font-bold text-gray-900">{{ number_format($minPrice) }}₫</span>
                                                        @else
                                                            <span class="text-lg font-bold text-gray-900">{{ number_format($minPrice) }} - {{ number_format($maxPrice) }}₫</span>
                                                        @endif
                                                    @else
                                                        <span class="text-lg font-bold text-gray-900">Liên hệ</span>
                                                    @endif
                                                </div>
                                                
                                                <button class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition text-sm"
                                                        onclick="event.stopPropagation(); addToCart({{ $product->id }}, null, 1)">
                                                    <i class="fas fa-cart-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-8">
                                {{ $products->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-box-open text-gray-400 text-6xl mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">Không có sản phẩm nào</h3>
                                <p class="text-gray-500">Hiện tại chưa có sản phẩm nào trong danh mục này.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
