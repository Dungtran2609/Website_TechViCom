@extends('client.layouts.app')

@section('title', 'Danh mục sản phẩm')

@push('styles')
    <style>
        .category-card-pro {
            transition: all 0.3s cubic-bezier(.4, 2, .6, 1);
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.07);
            border-radius: 1.25rem;
            overflow: hidden;
            background: #fff;
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
        }

        .category-card-pro:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 8px 32px 0 rgba(255, 108, 47, 0.15);
            border-color: #ff6c2f;
        }

        .category-img-pro {
            width: 100%;
            height: 140px;
            object-fit: cover;
            background: #f7f7f7;
            border-bottom: 1px solid #f1f1f1;
        }

        .category-badge-pro {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: #ff6c2f;
            color: #fff;
            font-size: 0.85rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            z-index: 2;
        }

        .category-products-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #0052cc;
            color: #fff;
            font-size: 0.85rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            z-index: 2;
        }

        .category-card-pro .category-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #222;
            text-align: center;
        }

        .category-card-pro .category-children {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .category-card-pro .category-children span {
            background: #f3f4f6;
            color: #555;
            font-size: 0.85rem;
            padding: 0.15rem 0.6rem;
            border-radius: 9999px;
        }

        .category-card-pro .category-footer {
            margin-top: auto;
            padding: 1rem 1.5rem 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .category-card-pro .category-link {
            color: #ff6c2f;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: color 0.2s;
        }

        .category-card-pro .category-link:hover {
            color: #0052cc;
        }

        .category-search-bar {
            max-width: 420px;
            margin: 0 auto 2rem auto;
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 9999px;
            box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.04);
            padding: 0.5rem 1.25rem;
            border: 1px solid #eee;
        }

        .category-search-bar input {
            border: none;
            outline: none;
            background: transparent;
            flex: 1;
            font-size: 1rem;
            padding: 0.5rem 0;
        }

        .category-search-bar button {
            background: none;
            border: none;
            color: #ff6c2f;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0 0.5rem;
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
                        <i class="fas fa-hevron-right text-gray-400 mx-2"></i>
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
                    <a href="{{ route('categories.show', $category->slug) }}" class="block h-full">
                        @if ($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                class="category-img-pro">
                        @else
                            <img src="{{ asset('client_css/images/category-default.jpg') }}" alt="{{ $category->name }}"
                                class="category-img-pro">
                        @endif
                        <span class="category-badge-pro">{{ $category->children_count }} danh mục con</span>

                        <div class="px-4 pt-4 pb-2">
                            <div class="category-title group-hover:text-[#ff6c2f] transition">{{ $category->name }}</div>
                            @if ($category->children_count > 0)
                                <div class="category-children mb-2">
                                    @foreach ($category->children->take(3) as $child)
                                        <span>{{ $child->name }}</span>
                                    @endforeach
                                </div>
                            @endif
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
