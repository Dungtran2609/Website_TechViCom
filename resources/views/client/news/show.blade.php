@extends('client.layouts.app')

@section('title', $news->title . ' - TechViCom')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <!-- Breadcrumb -->
        <nav class="mb-2 text-sm flex items-center gap-2">
            <a href="{{ route('client.home') }}" class="text-[#0052cc] hover:underline">Trang chủ</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('client.news.index') }}" class="text-[#0052cc] hover:underline">Tin tức</a>
            <span class="text-gray-400">/</span>
            @if (!empty($news->category))
                <a href="{{ route('client.news.index', ['category' => $news->category->id]) }}" class="text-gray-700 hover:text-[#ff6c2f] font-semibold">{{ $news->category->name }}</a>
            @else
                <span class="text-gray-700">Bài viết</span>
            @endif
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-semibold">{{ $news->title }}</span>
        </nav>
        <!-- Horizontal category menu -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar: mục lục bài viết tự động -->
            <aside class="lg:col-span-3 hidden lg:block">
                <div class="bg-white rounded-xl shadow p-4 mb-6 sticky top-24">
                    <h3 class="text-lg font-bold mb-4 text-[#0052cc]">Nội dung bài viết</h3>
                    <ul class="space-y-2 text-sm" id="toc-list">
                        <!-- Mục lục sẽ được sinh tự động bằng JS -->
                    </ul>
                </div>
            </aside>
            <!-- Main content -->
            <div class="lg:col-span-9">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="mb-0 -mx-8">
                        <img src="{{ asset($news->image ?? 'client_css/images/placeholder.svg') }}"
                            alt="{{ $news->title }}" class="w-full h-80 object-cover mb-0"
                            style="border-radius:0;box-shadow:none;margin-top:0;">
                    </div>
                    <div></div>
                    <div class="flex items-center gap-4 mb-6">
                        @if (!empty($news->author->avatar))
                            <img src="{{ asset($news->author->avatar) }}" alt="{{ $news->author->name ?? '' }}"
                                class="w-12 h-12 rounded-full object-cover">
                        @else
                            <span
                                class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-xl font-bold text-gray-700">
                                <i class="fas fa-user"></i>
                            </span>
                        @endif
                        <div>
                            <div class="font-semibold text-gray-900">{{ $news->author->name ?? 'TechViCom' }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $news->published_at ? $news->published_at->format('d/m/Y') : '' }}</div>
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold text-[#ff6c2f] mb-4">{{ $news->title }}</h1>
                    <div class="mb-4 flex gap-2 items-center">
                        <span
                            class="px-3 py-1 bg-[#e6f4ea] text-[#1a7f37] rounded-full text-xs font-semibold">{{ $news->category->name ?? '' }}</span>
                        <span class="text-xs text-gray-500"><i class="fas fa-clock"></i>
                            {{ $news->published_at ? $news->published_at->format('l, d/m/Y') : '' }}</span>
                    </div>
                    <div class="mb-4 flex gap-2 items-center">
                        <span class="text-xs text-gray-500">Chia sẻ:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                            target="_blank" class="text-[#0052cc] hover:text-[#ff6c2f]">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    </div>
                    <div class="prose max-w-none mb-8" id="news-content">{!! $news->content !!}</div>
                    @push('scripts')
                        <script>
                            // Tự động sinh mục lục từ các thẻ heading trong nội dung bài viết
                            document.addEventListener('DOMContentLoaded', function() {
                                const tocList = document.getElementById('toc-list');
                                const content = document.getElementById('news-content');
                                if (tocList && content) {
                                    const headings = content.querySelectorAll('h1, h2, h3');
                                    tocList.innerHTML = '';
                                    headings.forEach(function(h, i) {
                                        if (!h.id) h.id = 'section-' + (i + 1);
                                        const li = document.createElement('li');
                                        li.innerHTML =
                                            `<a href="#${h.id}" class="text-gray-700 hover:text-[#ff6c2f]">${h.textContent}</a>`;
                                        tocList.appendChild(li);
                                    });
                                }
                            });
                        </script>
                    @endpush
                    <div class="border-t pt-6 mt-6">
                        <h2 class="text-xl font-bold mb-4 text-[#0052cc]">Bài viết liên quan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($related as $item)
                                <a href="{{ route('client.news.show', $item->id) }}"
                                    class="group block bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-xl transition duration-200 border border-gray-100">
                                    <div class="relative h-44 w-full overflow-hidden">
                                        <img src="{{ asset($item->image ?? 'client_css/images/placeholder.svg') }}"
                                            alt="{{ $item->title }}"
                                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </div>
                                    <div class="p-4">
                                        <div
                                            class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 group-hover:text-[#0052cc]">
                                            {{ $item->title }}</div>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                                            <i class="fas fa-clock"></i>
                                            {{ $item->published_at ? $item->published_at->format('d/m/Y') : '' }}
                                        </div>
                                        <span
                                            class="inline-block px-3 py-1 bg-[#e6f4ea] text-[#1a7f37] rounded-full text-xs font-semibold">{{ $item->category->name ?? '' }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="border-t pt-6 mt-6">
                        <h2 class="text-xl font-bold mb-4 text-[#0052cc]">Bình luận bài viết</h2>
                        <div class="mb-6">
                            <form method="POST" action="{{ route('client.news-comments.store', $news->id) }}">
                                @csrf
                                <textarea name="content" rows="3" maxlength="3000" class="w-full border rounded-lg p-3 mb-2"
                                    placeholder="Nội dung bình luận"></textarea>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">0/3000</span>
                                    <button type="submit"
                                        class="px-6 py-2 bg-[#ff6c2f] text-white rounded font-semibold shadow hover:bg-[#e55a28] transition">Gửi</button>
                                </div>
                            </form>
                        </div>
                        <div>
                            <h3 class="text-base font-bold mb-4">{{ $news->comments->count() }} Bình luận</h3>
                            @if ($news->comments->isEmpty())
                                <div class="bg-gray-100 rounded-lg p-4 text-gray-500">Chưa có bình luận nào.</div>
                            @else
                                <ul class="space-y-6" id="comments-list">
                                    @php $maxCommentShow = 3; @endphp
                                    @php $sortedComments = $news->comments->where('parent_id', null)->sortByDesc('created_at')->values(); @endphp
                                    @foreach ($sortedComments as $i => $comment)
                                        <li class="comment-item" style="{{ $i >= $maxCommentShow ? 'display:none;' : '' }}">
                                            <div class="flex gap-3 items-center mb-1">
                                                @if (!empty($comment->user->avatar))
                                                    <img src="{{ asset($comment->user->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover shadow">
                                                @else
                                                    <span class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#0052cc] to-[#ff6c2f] flex items-center justify-center font-bold text-white">{{ mb_substr($comment->user->name ?? 'U', 0, 1) }}</span>
                                                @endif
                                                <span class="font-semibold">{{ $comment->user->name ?? 'Ẩn danh' }}</span>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at ? $comment->created_at->locale('vi')->diffForHumans() : '' }}</span>
                                            </div>
                                            <div class="ml-11 bg-gray-100 rounded-lg p-3">{{ $comment->content }}</div>
                                            <div class="ml-11 flex gap-4 mt-2 text-xs">
                                                <form method="POST"
                                                    action="{{ route('client.news-comments.like', $comment->id) }}">
                                                    @csrf
                                                    @php
                                                        $sessionKey = 'liked_comment_' . $comment->id;
                                                        $liked = session()->has($sessionKey);
                                                    @endphp
                                                    <button type="submit"
                                                        class="flex items-center gap-1 font-semibold {{ $liked ? 'text-blue-600 cursor-not-allowed' : 'text-gray-400' }}"
                                                        {{ $liked ? 'disabled' : '' }}>
                                                        <i class="far fa-thumbs-up"></i> {{ $comment->likes_count ?? 0 }}
                                                    </button>
                                                </form>
                                                <button class="text-[#0052cc] font-semibold reply-toggle-btn" type="button"
                                                    data-comment-id="{{ $comment->id }}">Trả lời</button>
                                            </div>
                                            <div class="reply-form mt-3 ml-11" id="replyForm{{ $comment->id }}"
                                                style="display:none;">
                                                <form method="POST"
                                                    action="{{ route('client.news-comments.reply', $comment->id) }}">
                                                    @csrf
                                                    <textarea name="content" rows="2" class="w-full border rounded-lg p-2 mb-2" placeholder="Nhập phản hồi..."></textarea>
                                                    <button type="submit"
                                                        class="px-4 py-1 bg-[#0052cc] text-white rounded font-semibold shadow hover:bg-[#ff6c2f] transition">Gửi</button>
                                                </form>
                                            </div>
                                            @push('scripts')
                                                <script>
                                                    // Toggle reply form: chỉ hiện 1 form tại 1 thời điểm
                                                    document.querySelectorAll('.reply-toggle-btn').forEach(function(btn) {
                                                        btn.addEventListener('click', function() {
                                                            var id = btn.getAttribute('data-comment-id');
                                                            var form = document.getElementById('replyForm' + id);
                                                            document.querySelectorAll('.reply-form').forEach(function(f) {
                                                                if (f !== form) f.style.display = 'none';
                                                            });
                                                            if (form) {
                                                                form.style.display = 'block';
                                                                var textarea = form.querySelector('textarea');
                                                                if (textarea) textarea.focus();
                                                            }
                                                        });
                                                    });
                                                </script>
                                            @endpush

                                            @if ($comment->children->count())
                                                @php 
                                                    $maxReplyShow = 3; 
                                                    $visibleReplies = $comment->children->take($maxReplyShow);
                                                @endphp
                                                <ul class="mt-3 ml-16 space-y-4 border-l-2 border-[#0052cc] pl-4" id="replies-list-{{ $comment->id }}">
                                                    @foreach ($visibleReplies as $j => $reply)
                                                        <li class="reply-item-{{ $comment->id }}">
                                                            <div class="flex gap-2 items-center mb-1">
                                                                @if (!empty($reply->user->avatar))
                                                                    <img src="{{ asset($reply->user->avatar) }}" alt="Avatar" class="w-7 h-7 rounded-full object-cover shadow">
                                                                @else
                                                                    <span class="w-7 h-7 rounded-full bg-gray-400 flex items-center justify-center font-bold text-white">{{ mb_substr($reply->user->name ?? 'A', 0, 1) }}</span>
                                                                @endif
                                                                <span class="font-semibold">{{ $reply->user->name ?? 'Ẩn danh' }}</span>
                                                                <span class="text-xs text-gray-500">{{ $reply->created_at ? $reply->created_at->locale('vi')->diffForHumans() : '' }}</span>
                                                                <form method="POST"
                                                                    action="{{ route('client.news-comments.like', $reply->id) }}"
                                                                    class="ml-2">
                                                                    @csrf
                                                                    @php
                                                                        $sessionKey = 'liked_comment_' . $reply->id;
                                                                        $liked = session()->has($sessionKey);
                                                                    @endphp
                                                                    <button type="submit"
                                                                        class="flex items-center gap-1 font-semibold {{ $liked ? 'text-blue-600 cursor-not-allowed' : 'text-gray-400' }}"
                                                                        {{ $liked ? 'disabled' : '' }}>
                                                                        <i class="far fa-thumbs-up"></i>
                                                                        {{ $reply->likes_count ?? 0 }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div class="ml-8 bg-gray-50 rounded-lg p-2">
                                                                {{ $reply->content }}</div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @if ($comment->children->count() > $maxReplyShow)
                                                        <div class="text-center my-2">
                                                            <button id="btn-expand-replies-{{ $comment->id }}" class="btn btn-outline-secondary btn-sm px-4">Xem thêm phản hồi</button>
                                                            <button id="btn-collapse-replies-{{ $comment->id }}" class="btn btn-outline-secondary btn-sm px-4" style="display:none;">Ẩn bớt phản hồi</button>
                                                    </div>
                                                    <script>
                                                        document.getElementById('btn-expand-replies-{{ $comment->id }}').onclick = function() {
                                                            document.querySelectorAll('.reply-item-{{ $comment->id }}').forEach(function(el) {
                                                                el.style.display = '';
                                                            });
                                                            this.style.display = 'none';
                                                            document.getElementById('btn-collapse-replies-{{ $comment->id }}').style.display = '';
                                                        };
                                                        document.getElementById('btn-collapse-replies-{{ $comment->id }}').onclick = function() {
                                                            document.querySelectorAll('.reply-item-{{ $comment->id }}').forEach(function(el, idx) {
                                                                if (idx >= {{ $maxReplyShow }}) el.style.display = 'none';
                                                            });
                                                            this.style.display = 'none';
                                                            document.getElementById('btn-expand-replies-{{ $comment->id }}').style.display = '';
                                                        };
                                                    </script>
                                                @endif
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                @if ($news->comments->where('parent_id', null)->count() > $maxCommentShow)
                                        <div class="text-center my-2">
                                            <button id="btn-expand-comments" class="btn btn-outline-secondary btn-sm px-4">Xem thêm bình luận</button>
                                            <button id="btn-collapse-comments" class="btn btn-outline-secondary btn-sm px-4" style="display:none;">Ẩn bớt bình luận</button>
                                        </div>
                                        <script>
                                            const maxCommentShow = {{ $maxCommentShow }};
                                            const expandBtn = document.getElementById('btn-expand-comments');
                                            const collapseBtn = document.getElementById('btn-collapse-comments');
                                            expandBtn.onclick = function() {
                                                document.querySelectorAll('.comment-item').forEach(function(el, idx) {
                                                    el.style.display = '';
                                                });
                                                expandBtn.style.display = 'none';
                                                collapseBtn.style.display = '';
                                            };
                                            collapseBtn.onclick = function() {
                                                document.querySelectorAll('.comment-item').forEach(function(el, idx) {
                                                    el.style.display = idx < maxCommentShow ? '' : 'none';
                                                });
                                                expandBtn.style.display = '';
                                                collapseBtn.style.display = 'none';
                                            };
                                        </script>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
