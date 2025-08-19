@extends('client.layouts.app')

@section('title', 'Danh mục sản phẩm')

@push('styles')
    <style>
        .category-card-pro {
            transition: transform .28s cubic-bezier(.4, 2, .6, 1), box-shadow .28s ease;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
            border-radius: 1.25rem;
            overflow: hidden;
            background: #fff;
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
            will-change: transform;
            backface-visibility: hidden;
            border: 1px solid #f1f1f1;
        }

        .category-card-pro:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 8px 32px rgba(255, 108, 47, .15);
            border-color: #ff6c2f;
        }

        /* === Khung ảnh giống FPT === */
        .category-img-wrap {
            width: 100%;
            height: 170px;
            /* desktop */
            background: #f7f7f7;
            border-bottom: 1px solid #f1f1f1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            /* tránh sát mép */
        }

        .category-img-pro {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            /* không cắt ảnh, giống FPT */
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            transform: translateZ(0);
        }

        @media (max-width: 640px) {
            .category-img-wrap {
                height: 140px;
            }

            /* mobile */
        }

        .category-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: .5rem;
            color: #222;
            text-align: center;
        }

        .category-footer {
            margin-top: auto;
            padding: 1rem 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-orange-600">
                        <i class="fas fa-home mr-2"></i>Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Tất cả danh mục</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Tất cả danh mục</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Khám phá các danh mục sản phẩm công nghệ hàng đầu với chất lượng tốt nhất
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-7">
            @forelse($categories as $category)
                <div class="category-card-pro group relative">
                    <a href="{{ route('categories.show', $category->slug) }}" class="block h-full"
                        aria-label="Mở danh mục {{ $category->name }}">
                        <div class="category-img-wrap">
                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                    class="category-img-pro" loading="lazy" decoding="async"
                                    onerror="this.onerror=null;this.src='{{ asset('client_css/images/category-default.jpg') }}';">
                            @else
                                <img src="{{ asset('client_css/images/category-default.jpg') }}" alt="{{ $category->name }}"
                                    class="category-img-pro">
                            @endif
                        </div>

                        <div class="px-4 pt-4 pb-2">
                            <div class="category-title group-hover:text-[#ff6c2f] transition">{{ $category->name }}</div>
                        </div>

                        <div class="category-footer px-4 pb-3 pt-1">
                            <span class="text-xs text-gray-400">{{ number_format($category->children_count) }} danh mục
                                con</span>
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

        <!-- CTA giữ nguyên -->
        @if ($categories->count() > 0)
            <div class="text-center mt-12">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-8 text-white">
                    <h2 class="text-2xl font-bold mb-4">Không tìm thấy danh mục mong muốn?</h2>
                    <p class="mb-6">Liên hệ với chúng tôi để được tư vấn sản phẩm phù hợp nhất</p>
                    <a href="{{ route('client.contacts.create') }}"
                        class="inline-flex items-center bg-white text-orange-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        <i class="fas fa-phone mr-2"></i>Liên hệ tư vấn
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
