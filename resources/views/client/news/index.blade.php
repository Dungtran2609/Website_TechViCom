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
        <hr class="mb-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left: News List -->
            <div class="lg:col-span-3">
                <div class="flex flex-col gap-10">
                    @php $maxNewsShow = 6; @endphp
                    @foreach ($news as $i => $item)
                        <div class="flex flex-col md:flex-row bg-white rounded-xl  news-item"
                            style="{{ $i >= $maxNewsShow ? 'display:none;' : '' }}">
                            <a href="{{ route('client.news.show', $item->id) }}"
                                class="block w-full md:w-[400px] h-[240px] md:h-[180px] overflow-hidden flex-shrink-0">
                                <img src="{{ asset($item->image ?? 'client_css/images/placeholder.svg') }}"
                                    alt="{{ $item->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </a>
                            <div class="flex-1 p-6 flex flex-col justify-center">
                                <a href="{{ route('client.news.show', $item->id) }}"
                                    class="text-2xl font-extrabold text-gray-900 mb-2 hover:text-[#ff6c2f] line-clamp-2 transition-colors duration-200">{{ $item->title }}</a>
                                <div class="flex items-center gap-4 mb-2 text-base text-gray-600">
                                    <span class="flex items-center gap-1"><i class="fas fa-clock"></i>
                                        {{ $item->published_at ? $item->published_at->format('l, d/m/Y') : '' }}</span>
                                    <span class="flex items-center gap-1"><i class="fas fa-user"></i>
                                        {{ $item->author->name ?? 'TechViCom' }}</span>
                                </div>
                                <p class="text-gray-700 mb-2 line-clamp-2">{{ Str::limit(strip_tags($item->content), 160) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                    @if ($news->count() > $maxNewsShow)
                        @if ($news->count() > 0)
                            <div class="text-center my-4">
                                <button id="btn-expand-news" class="btn btn-outline-secondary btn-sm px-4">Xem thêm bài
                                    viết</button>
                                <button id="btn-collapse-news" class="btn btn-outline-secondary btn-sm px-4"
                                    style="display:none;">Ẩn bớt bài viết</button>
                            </div>
                            <script>
                                const maxNewsShow = {{ $maxNewsShow }};
                                const expandNewsBtn = document.getElementById('btn-expand-news');
                                const collapseNewsBtn = document.getElementById('btn-collapse-news');
                                expandNewsBtn.onclick = function() {
                                    document.querySelectorAll('.news-item').forEach(function(el) {
                                        el.style.display = '';
                                    });
                                    expandNewsBtn.style.display = 'none';
                                    collapseNewsBtn.style.display = '';
                                };
                                collapseNewsBtn.onclick = function() {
                                    document.querySelectorAll('.news-item').forEach(function(el, i) {
                                        el.style.display = i < maxNewsShow ? '' : 'none';
                                    });
                                    expandNewsBtn.style.display = '';
                                    collapseNewsBtn.style.display = 'none';
                                };
                            </script>
                        @endif
                    @endif
                </div>

            </div>
            <!-- Right: Sidebar -->
            <div class="lg:col-span-1 flex flex-col gap-8">
                <div class="bg-green-50 rounded-xl p-5 shadow">
                    <h2 class="text-lg font-bold mb-4 text-gray-700">Danh mục tin tức</h2>
                    <ul class="space-y-2">
                        @php $maxCatShow = 8; @endphp
                        @foreach ($categories as $i => $cat)
                            <li class="cat-item" style="{{ $i >= $maxCatShow ? 'display:none;' : '' }}">
                                <a href="{{ route('client.news.index', ['category' => $cat->id]) }}"
                                    class="text-gray-700 hover:text-[#ff6c2f] font-semibold">{{ $cat->name }}</a>
                            </li>
                        @endforeach
                        @if ($categories->count() > $maxCatShow)
                            <div class="text-center my-2">
                                <button id="btn-expand-cat" class="btn btn-outline-secondary btn-sm px-4">Xem thêm danh
                                    mục</button>
                                <button id="btn-collapse-cat" class="btn btn-outline-secondary btn-sm px-4"
                                    style="display:none;">Ẩn bớt danh mục</button>
                            </div>
                            <script>
                                const maxCatShow = {{ $maxCatShow }};
                                const expandCatBtn = document.getElementById('btn-expand-cat');
                                const collapseCatBtn = document.getElementById('btn-collapse-cat');
                                expandCatBtn.onclick = function() {
                                    document.querySelectorAll('.cat-item').forEach(function(el) {
                                        el.style.display = '';
                                    });
                                    expandCatBtn.style.display = 'none';
                                    collapseCatBtn.style.display = '';
                                };
                                collapseCatBtn.onclick = function() {
                                    document.querySelectorAll('.cat-item').forEach(function(el, i) {
                                        el.style.display = i < maxCatShow ? '' : 'none';
                                    });
                                    expandCatBtn.style.display = '';
                                    collapseCatBtn.style.display = 'none';
                                };
                            </script>
                        @endif
                    </ul>

                </div>
                <div class="bg-gray-50 rounded-xl p-5 shadow">
                    <h2 class="text-lg font-bold mb-4 text-gray-700">Tin tức nổi bật</h2>
                    <ul class="space-y-3">
                        @foreach ($featuredNews as $fitem)
                            <li class="flex items-center gap-3">
                                <a href="{{ route('client.news.show', $fitem->id) }}"
                                    class="block w-16 h-12 overflow-hidden rounded-lg">
                                    <img src="{{ asset($fitem->image ?? 'client_css/images/placeholder.svg') }}"
                                        alt="{{ $fitem->title }}" class="w-full h-full object-cover">
                                </a>
                                <a href="{{ route('client.news.show', $fitem->id) }}"
                                    class="text-sm font-semibold text-gray-800 hover:text-[#ff6c2f] line-clamp-2">{{ $fitem->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
