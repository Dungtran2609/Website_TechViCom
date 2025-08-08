@extends('client.layouts.app')

@section('title', 'Tin tức - TechViCom')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-lg">
        <a href="{{ route('client.home') }}" class="text-gray-700 hover:text-[#ff6c2f]">Trang chủ</a> 
        <p class="text-gray-700">></p>
        <span class="text-green-700 font-semibold">Tin tức</span>
    </div>
    <h1 class="text-4xl font-extrabold text-[#ff6c2f] mb-10 text-left">Tất cả bài viết</h1>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Left: News List -->
        <div class="lg:col-span-3">
            <div class="flex flex-col gap-10">
                @foreach ($news as $item)
                     <hr>
                    <div class="flex flex-col md:flex-row bg-white rounded-xl  ">
                        
                        <a href="{{ route('client.news.show', $item->id) }}" class="block w-full md:w-[400px] h-[240px] md:h-[180px] overflow-hidden flex-shrink-0">
                            <img src="{{ asset($item->image ?? 'client_css/images/placeholder.svg') }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </a>
                        <div class="flex-1 p-6 flex flex-col justify-center">
                            <a href="{{ route('client.news.show', $item->id) }}" class="text-2xl font-extrabold text-gray-900 mb-2 hover:text-[#ff6c2f] line-clamp-2 transition-colors duration-200">{{ $item->title }}</a>
                            <div class="flex items-center gap-4 mb-2 text-base text-gray-600">
                                <span class="flex items-center gap-1"><i class="fas fa-clock"></i> {{ $item->published_at ? $item->published_at->format('l, d/m/Y') : '' }}</span>
                                <span class="flex items-center gap-1"><i class="fas fa-user"></i> {{ $item->author->name ?? 'TechViCom' }}</span>
                            </div>
                            <p class="text-gray-700 mb-2 line-clamp-2">{{ Str::limit(strip_tags($item->content), 160) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
        <!-- Right: Sidebar -->
        <div class="lg:col-span-1 flex flex-col gap-8">
            <div class="bg-green-50 rounded-xl p-5 shadow">
                <h2 class="text-lg font-bold mb-4 text-gray-700">Danh mục tin tức</h2>
                <ul class="space-y-2">
                    @foreach ($categories as $cat)
                        <li>
                            <a href="{{ route('client.news.index', ['category' => $cat->id]) }}" class="text-gray-700 hover:text-[#ff6c2f] font-semibold">{{ $cat->name }}</a>
                        </li>
                    @endforeach
                </ul>
                @if(!request()->input('show_all_categories') && $allCategoriesCount > 10)
                    <div class="mt-4 flex justify-center">
                        <a href="?show_all_categories=1" class="px-2 py-1 text-xs bg-[#ff6c2f] text-white rounded hover:bg-[#e55a28] transition">Xem thêm</a>
                    </div>
                @endif
            </div>
            <div class="bg-gray-50 rounded-xl p-5 shadow">
                <h2 class="text-lg font-bold mb-4 text-gray-700">Tin tức nổi bật</h2>
                <ul class="space-y-3">
                    @foreach ($featuredNews as $fitem)
                        <li class="flex items-center gap-3">
                            <a href="{{ route('client.news.show', $fitem->id) }}" class="block w-16 h-12 overflow-hidden rounded-lg">
                                <img src="{{ asset($fitem->image ?? 'client_css/images/placeholder.svg') }}" alt="{{ $fitem->title }}" class="w-full h-full object-cover">
                            </a>
                            <a href="{{ route('client.news.show', $fitem->id) }}" class="text-sm font-semibold text-gray-800 hover:text-[#ff6c2f] line-clamp-2">{{ $fitem->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
