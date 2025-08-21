@extends('client.layouts.app')


@section('title', 'Danh mục sản phẩm')


@push('styles')
    <style>
        /* Card */
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


        /* Image: show FULL image (contain) */
        .category-img-wrap {
            width: 100%;
            height: 140px;
            background: #f7f7f7;
            border-bottom: 1px solid #f1f1f1;
        }


        .category-img-pro {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }


        /* Old orange badge (kept but unused) */
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


        /* NEW: subtle gray count badge placed UNDER the title */
        .category-count-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: #f3f4f6;
            /* gray-100 */
            border: 1px solid #e5e7eb;
            /* gray-200 */
            color: #6b7280;
            /* gray-500 */
            font-size: .8rem;
            font-weight: 600;
            padding: .2rem .6rem;
            border-radius: 9999px;
            opacity: .95;
        }


        .category-card-pro .category-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: .35rem;
            color: #222;
            text-align: center;
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


        /* Search bar (unused here but kept) */
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
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Tất cả danh mục</span>
                    </div>
                </li>
            </ol>
        </nav>


        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Tất cả danh mục</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Khám phá các danh mục sản phẩm công nghệ hàng đầu với chất
                lượng tốt nhất</p>
        </div>


        <!-- Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-7">
            @forelse($categories as $category)
                <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 border border-gray-100 hover:border-orange-400 flex flex-col items-center p-0 overflow-hidden"
                    style="min-height: 270px;">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="block w-full h-full">
                        <!-- Image: full (contain) -->
                        <div
                            class="category-img-wrap relative w-full h-[140px] flex items-center justify-center bg-gray-50 overflow-hidden p-2">
                            @php
                                $imgSrc = $category->image ? asset('storage/' . $category->image) : asset('client_css/images/placeholder.svg');
                            @endphp
                            <img src="{{ $imgSrc }}" alt="{{ $category->name }}"
                                class="category-img-pro object-contain w-full h-full transition-transform duration-300 group-hover:scale-105"
                                loading="lazy" decoding="async"
                                onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}';">
                            <!-- (ĐÃ BỎ) badge cam ở góc -->
                        </div>


                        <!-- Content -->
                        <div class="flex flex-col items-center px-3 pt-4 pb-3">
                            <div
                                class="category-title text-base font-bold text-gray-800 text-center group-hover:text-orange-500 transition">
                                {{ $category->name }}</div>


                            <!-- NEW: badge xám, đưa xuống dưới, kém nổi bật -->
                            <div class="mt-1">
                                <span class="category-count-badge" aria-label="Số danh mục con">
                                    <i class="fas fa-layer-group"></i>
                                    {{ number_format($category->children_count ?? 0) }} danh mục con
                                </span>
                            </div>


                            <!-- (ĐÃ XÓA) danh mục con liệt kê bên dưới -->
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



