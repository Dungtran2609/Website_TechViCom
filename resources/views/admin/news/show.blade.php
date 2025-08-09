@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Tiêu đề trang --}}
        <div class="mb-4 border-bottom pb-2">
            <h1 class="h3 text-primary">Chi tiết bài viết</h1>
        </div>

        {{-- Nội dung bài viết --}}
        <div class="card shadow-lg rounded-4 border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3 gap-3">
                    @if ($news->image)
                        <img src="{{ asset($news->image) }}" alt="Ảnh đại diện" class="img-fluid rounded-3 shadow"
                            style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 120px; height: 120px;">
                            <i class="fas fa-image fa-2x text-secondary"></i>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <h2 class="card-title mb-2 text-dark fw-bold" style="font-size: 1.6rem;">{{ $news->title }}</h2>
                        <div class="mb-2">
                            <span class="badge bg-info text-dark me-2">{{ $news->category?->name ?? 'Không có' }}</span>
                            <span
                                class="badge {{ $news->status === 'published' ? 'bg-success' : 'bg-secondary' }} me-2">{{ $news->status === 'published' ? 'Đã xuất bản' : 'Nháp' }}</span>
                        </div>
                        <div class="mb-1 text-muted small">
                            <i class="fas fa-user-edit me-1"></i> <span
                                class="text-primary">{{ $news->author?->name ?? 'Không xác định' }}</span>
                            <span class="mx-2">|</span>
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $news->published_at ? $news->published_at->format('d/m/Y H:i') : 'Chưa xuất bản' }}
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <strong class="d-block mb-2 text-dark">Nội dung bài viết</strong>
                    <div class="news-content border rounded-3 p-4 bg-light" style="min-height: 120px;">
                        {!! $news->content !!}
                    </div>
                </div>

                {{-- quay lại trang danh sách bài viết--}}
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách bài viết
                    </a>
                    <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Sửa bài viết
                    </a>
                </div>

                {{-- Khu vực bình luận --}}
                <div class="card shadow-lg rounded-4 border-0 mt-4">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-comments me-2 text-primary"></i>Bình luận bài viết</h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($news->comments->isEmpty())
                            <div class="alert alert-secondary rounded-3">Chưa có bình luận nào.</div>
                        @else
                            @php $maxShow = 5; $sortedComments = $news->comments->where('parent_id', null)->sortByDesc('created_at')->values(); @endphp
                            <ul class="list-unstyled" id="comments-list">
                                @foreach ($sortedComments as $i => $comment)
                                    <li class="mb-4 pb-3 comment-item" style="{{ $i >= $maxShow ? 'display:none;' : '' }}">
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <span class="rounded-circle bg-gradient bg-primary text-white d-flex justify-content-center align-items-center shadow" style="width:44px;height:44px;font-weight:bold;font-size:1.2rem;">
                                                {{ mb_substr($comment->user->name ?? 'U', 0, 1) }}
                                            </span>
                                            <div class="flex-grow-1">
                                                <span class="fw-bold text-dark">{{ $comment->user->name ?? 'Ẩn danh' }}</span>
                                                <span class="text-muted ms-2 small">{{ $comment->created_at ? $comment->created_at->locale('vi')->diffForHumans() : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="ms-5">
                                            <div class="bg-light rounded-3 p-3 mb-2 text-dark">{{ $comment->content }}</div>
                                            <div class="d-flex gap-3 align-items-center small mb-2">
                                                <form method="POST" action="{{ route('admin.news-comments.like', $comment->id) }}">
                                                    @csrf
                                                    @php $sessionKey = 'liked_comment_' . $comment->id; $liked = session()->has($sessionKey); @endphp
                                                    <button type="submit" class="btn btn-link px-2 py-0 {{ $liked ? 'text-info' : 'text-secondary' }}" {{ $liked ? 'disabled' : '' }}>
                                                        <i class="far fa-thumbs-up"></i> {{ $comment->likes_count }}
                                                    </button>
                                                </form>
                                                <button class="btn btn-link text-secondary px-2 py-0" type="button" data-bs-toggle="collapse" data-bs-target="#replyForm{{ $comment->id }}">
                                                    <i class="fas fa-reply"></i> Trả lời
                                                </button>
                                            </div>
                                            <div class="collapse mt-2" id="replyForm{{ $comment->id }}">
                                                <form method="POST" action="{{ route('admin.news-comments.reply', $comment->id) }}">
                                                    @csrf
                                                    <textarea name="content" class="form-control rounded-3 mb-2" rows="2" placeholder="Nhập phản hồi..."></textarea>
                                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Gửi</button>
                                                </form>
                                            </div>
                                            @if ($comment->children->count())
                                                @php $maxReplyShow = 3; @endphp
                                                <ul class="list-unstyled mt-3 ps-4 border-start border-primary" style="border-width:2px !important;" id="replies-list-{{ $comment->id }}">
                                                    @foreach ($comment->children as $j => $reply)
                                                        <li class="mb-3 reply-item-{{ $comment->id }}" style="{{ $j >= $maxReplyShow ? 'display:none;' : '' }}">
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <span class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center" style="width:32px;height:32px;font-weight:bold;font-size:1rem;">
                                                                    {{ mb_substr($reply->user->name ?? 'A', 0, 1) }}
                                                                </span>
                                                                <span class="fw-bold text-dark">{{ $reply->user->name ?? 'Ẩn danh' }}</span>
                                                                <span class="text-muted ms-2 small">{{ $reply->created_at ? $reply->created_at->locale('vi')->diffForHumans() : '' }}</span>
                                                                <form method="POST" action="{{ route('admin.news-comments.like', $reply->id) }}" class="ms-2">
                                                                    @csrf
                                                                    @php $sessionKey = 'liked_comment_' . $reply->id; $liked = session()->has($sessionKey); @endphp
                                                                    <button type="submit" class="btn btn-link px-2 py-0 {{ $liked ? 'text-info' : 'text-secondary' }}" {{ $liked ? 'disabled' : '' }}>
                                                                        <i class="far fa-thumbs-up"></i> {{ $reply->likes_count ?? 0 }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div class="bg-light rounded-3 p-2 ms-4 text-dark">{{ $reply->content }}</div>
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
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @if ($sortedComments->count() > $maxShow)
                                <div class="text-center my-3">
                                    <button id="btn-expand-comments" class="btn btn-outline-primary btn-sm px-4">Xem thêm bình luận</button>
                                    <button id="btn-collapse-comments" class="btn btn-outline-secondary btn-sm px-4" style="display:none;">Ẩn bớt bình luận</button>
                                </div>
                                <script>
                                    const maxShow = {{ $maxShow }};
                                    const expandBtn = document.getElementById('btn-expand-comments');
                                    const collapseBtn = document.getElementById('btn-collapse-comments');
                                    expandBtn.onclick = function() {
                                        document.querySelectorAll('.comment-item').forEach(function(el) {
                                            el.style.display = '';
                                        });
                                        expandBtn.style.display = 'none';
                                        collapseBtn.style.display = '';
                                    };
                                    collapseBtn.onclick = function() {
                                        document.querySelectorAll('.comment-item').forEach(function(el, i) {
                                            el.style.display = i < maxShow ? '' : 'none';
                                        });
                                        expandBtn.style.display = '';
                                        collapseBtn.style.display = 'none';
                                        window.scrollTo({ top: document.getElementById('comments-list').offsetTop - 80, behavior: 'smooth' });
                                    };
                                </script>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endsection

@push('styles')
    <style>
        .news-content {
            font-size: 1.08rem;
            line-height: 1.7;
            color: #222;
            word-break: break-word;
        }
        .news-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 16px auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .news-content h1, .news-content h2, .news-content h3 {
            margin-top: 1.2em;
            margin-bottom: 0.7em;
            font-weight: bold;
            color: #1565c0;
        }
        .news-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1em 0;
        }
        .news-content th, .news-content td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .news-content blockquote {
            border-left: 4px solid #90caf9;
            background: #f5f5f5;
            padding: 0.7em 1em;
            margin: 1em 0;
            font-style: italic;
        }
        .news-content ul, .news-content ol {
            margin-left: 2em;
        }
        .news-content iframe {
            max-width: 100%;
            border-radius: 8px;
            margin: 1em auto;
            display: block;
        }
    </style>
@endpush
