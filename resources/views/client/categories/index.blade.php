@extends('client.layouts.app')

@section('title', 'Danh mục sản phẩm')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-orange-600">
                    <i class="fas fa-home mr-2"></i>
                    Trang chủ
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Danh mục sản phẩm</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Danh mục sản phẩm</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Khám phá các danh mục sản phẩm công nghệ hàng đầu với chất lượng tốt nhất
        </p>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <a href="{{ route('categories.show', $category->slug) }}" class="block">
                    <div class="relative">
                        @if($category->image)
                            <img src="{{ asset('uploads/categories/' . $category->image) }}" 
                                 alt="{{ $category->name }}" 
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                                <i class="fas fa-tag text-4xl text-orange-500"></i>
                            </div>
                        @endif
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                        
                        <!-- Category Badge -->
                        <div class="absolute top-4 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            {{ $category->children->count() }} danh mục con
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2 group-hover:text-orange-600 transition-colors">
                            {{ $category->name }}
                        </h3>
                        
                        @if($category->children->count() > 0)
                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach($category->children->take(3) as $child)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                        {{ $child->name }}
                                    </span>
                                @endforeach
                                @if($category->children->count() > 3)
                                    <span class="text-xs text-orange-600 font-medium">
                                        +{{ $category->children->count() - 3 }} khác
                                    </span>
                                @endif
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                {{ $category->products_count ?? 0 }} sản phẩm
                            </span>
                            <i class="fas fa-arrow-right text-orange-500 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="bg-gray-100 rounded-xl p-8">
                    <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Chưa có danh mục nào</h3>
                    <p class="text-gray-500">Hệ thống đang cập nhật danh mục sản phẩm.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Call to Action -->
    @if($categories->count() > 0)
        <div class="text-center mt-12">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-8 text-white">
                <h2 class="text-2xl font-bold mb-4">Không tìm thấy danh mục mong muốn?</h2>
                <p class="mb-6">Liên hệ với chúng tôi để được tư vấn sản phẩm phù hợp nhất</p>
                <a href="{{ route('client.contacts.create') }}" 
                   class="inline-flex items-center bg-white text-orange-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <i class="fas fa-phone mr-2"></i>
                    Liên hệ tư vấn
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .category-card {
        transition: all 0.3s ease;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
