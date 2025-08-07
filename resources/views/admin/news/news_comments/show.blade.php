@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Bình luận bài viết: <span class="text-primary">{{ $news->title }}</span></h2>
        <a href="{{ route('admin.news.show', $news->id) }}" class="btn btn-outline-info mb-3">Xem bài viết</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($news->comments->isEmpty())
            <div class="alert alert-secondary">Chưa có bình luận nào cho bài viết này.</div>
        @else
            @php $maxShow = 5; @endphp
            <div id="comments-list">
                @foreach ($news->comments->where('parent_id', null)->sortByDesc('created_at') as $i => $comment)
                    <div class="card mb-4 comment-item" style="{{ $i >= $maxShow ? 'display:none;' : '' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong>
                                <small>
                                    {{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : '' }}
                                </small>
                            </div>
                            <p class="mb-2 {{ $comment->is_hidden ? 'text-muted fst-italic' : '' }}">
                                {!! $comment->is_hidden ? '<span class="text-warning">[Đã ẩn]</span> ' : '' !!}{{ $comment->content }}
                            </p>
                            <div class="d-flex gap-2 mb-2">
                                <form action="{{ route('admin.news-comments.toggle', $comment->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm {{ $comment->is_hidden ? 'btn-success' : 'btn-warning' }}">
                                        <i class="fas fa-eye{{ $comment->is_hidden ? '' : '-slash' }}"></i>
                                        {{ $comment->is_hidden ? 'Hiện' : 'Ẩn' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.news-comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Xoá bình luận này và các phản hồi?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i> Xoá
                                    </button>
                                </form>
                                <form action="{{ route('admin.news-comments.like', $comment->id) }}" method="POST">
                                    @csrf
                                <button class="btn btn-sm btn-outline-info">
                                    Like ({{ $comment->likes_count ?? 0 }})
                                </button>
                                </form>
                            </div>
                            {{-- Form trả lời --}}
                            <form action="{{ route('admin.news-comments.reply', $comment->id) }}" method="POST" class="mt-2 d-flex gap-2">
                                @csrf
                                <input type="text" name="content" class="form-control" placeholder="Nhập phản hồi...">
                            <button class="btn btn-sm btn-outline-primary">
                                Trả lời
                            </button>
                            </form>
                            {{-- Phản hồi con --}}
                            @if ($comment->children->count())
                                <h5 class="mt-3 mb-2">Phản hồi</h5>
                                @php $maxReplyShow = 3; @endphp
                                <div id="replies-list-{{ $comment->id }}">
                                    @foreach ($comment->children as $j => $child)
                                        <div class="card mb-2 ms-4 border-start border-primary reply-item-{{ $comment->id }}" style="{{ $j >= $maxReplyShow ? 'display:none;' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong>{{ $child->user->name ?? 'Ẩn danh' }}</strong>
                                                    <small>{{ $child->created_at ? $child->created_at->format('d/m/Y H:i') : '' }}</small>
                                                </div>
                                                <p class="mb-2 {{ $child->is_hidden ? 'text-muted fst-italic' : '' }}">
                                                    {!! $child->is_hidden ? '<span class="text-warning">[Đã ẩn]</span> ' : '' !!}{{ $child->content }}
                                                </p>
                                                <div class="d-flex gap-2">
                                                    <form action="{{ route('admin.news-comments.toggle', $child->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <button class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-eye{{ $child->is_hidden ? '' : '-slash' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.news-comments.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Xoá phản hồi này?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.news-comments.like', $child->id) }}" method="POST">
                                                        @csrf
                                                <button class="btn btn-sm btn-outline-info">
                                                    Like ({{ $child->likes_count ?? 0 }})
                                                </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if ($comment->children->count() > $maxReplyShow)
                                    <div class="text-center my-2">
                                        <button id="btn-expand-replies-{{ $comment->id }}" class="btn btn-outline-secondary btn-sm px-4">Xem thêm phản hồi</button>
                                    </div>
                                    <script>
                                        document.getElementById('btn-expand-replies-{{ $comment->id }}').onclick = function() {
                                            document.querySelectorAll('.reply-item-{{ $comment->id }}').forEach(function(el) {
                                                el.style.display = '';
                                            });
                                            this.style.display = 'none';
                                        };
                                    </script>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($news->comments->count() > $maxShow)
                <div class="text-center my-3">
                    <button id="btn-expand-comments" class="btn btn-outline-primary btn-sm px-4">Xem thêm bình luận</button>
                </div>
                <script>
                    document.getElementById('btn-expand-comments').onclick = function() {
                        document.querySelectorAll('.comment-item').forEach(function(el) {
                            el.style.display = '';
                        });
                        this.style.display = 'none';
                    };
                </script>
            @endif
        @endif
    </div>
@endsection
