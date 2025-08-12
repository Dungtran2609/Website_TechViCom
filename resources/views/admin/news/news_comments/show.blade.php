@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Bình luận bài viết: <span class="text-primary">{{ $news->title }}</span></h2>
        
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
            <div>
                <a href="{{ route('admin.news-comments.index') }}" class="btn btn-outline-secondary">← Quay lại danh sách bình luận</a>
                <a href="{{ route('admin.news.show', $news->id) }}" class="btn btn-outline-info ms-2">Xem bài viết</a>
            </div>
            
            <!-- Form tìm kiếm -->
            <form action="{{ route('admin.news-comments.show', $news->id) }}" method="GET" class="d-flex gap-2">
                <div class="input-group" style="width: 500px;">
                    <input type="text" name="search" class="form-control form-control-lg" placeholder="Tìm kiếm bình luận..." value="{{ request('search') }}" style="font-size: 1rem;">
                    <select name="status" class="form-select form-select-lg" style="max-width: 150px; font-size: 1rem;">
                        <option value="">- Trạng thái -</option>
                        <option value="visible" {{ request('status') === 'visible' ? 'selected' : '' }}>Hiện</option>
                        <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    <button class="btn btn-outline-primary btn-lg px-4" type="submit" style="font-size: 1rem;">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.news-comments.show', $news->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Xóa bộ lọc
                    </a>
                @endif
            </form>
        </div>
        
        @if(request()->hasAny(['search', 'status']))
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="fas fa-filter me-2"></i>
                    <div>
                        Đang lọc:
                        @if(request('search'))
                            <strong class="me-2">Từ khóa "{{ request('search') }}"</strong>
                        @endif
                        @if(request('status'))
                            <strong>Trạng thái: {{ request('status') === 'hidden' ? 'Ẩn' : 'Hiện' }}</strong>
                        @endif
                        <div class="mt-1">
                            @if($news->comments->isEmpty())
                                Không tìm thấy bình luận nào phù hợp.
                            @else
                                Tìm thấy {{ $news->comments->count() }} bình luận phù hợp.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                @php $sortedComments = $news->comments->where('parent_id', null)->sortByDesc('created_at')->values(); @endphp
                @foreach ($sortedComments as $i => $comment)
                    <div class="mb-4 comment-item" style="{{ $i >= $maxShow ? 'display:none;' : '' }}">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            @if (!empty($comment->user->avatar))
                                <img src="{{ asset($comment->user->avatar) }}" alt="Avatar" class="rounded-circle shadow" style="width:44px;height:44px;object-fit:cover;">
                            @else
                                <span class="rounded-circle bg-gradient bg-primary text-white d-flex justify-content-center align-items-center shadow" style="width:44px;height:44px;font-weight:bold;font-size:1.2rem;">
                                    {{ mb_substr($comment->user->name ?? 'U', 0, 1) }}
                                </span>
                            @endif
                            <div class="flex-grow-1">
                                <span class="fw-bold text-dark">{{ $comment->user->name ?? 'Ẩn danh' }}</span>
                                <span class="text-muted ms-2 small">{{ $comment->created_at ? $comment->created_at->locale('vi')->diffForHumans() : '' }}</span>
                            </div>
                        </div>
                        <div class="ms-5">
                            <div class="bg-light rounded-3 p-3 mb-2 text-dark">{{ $comment->content }}</div>
                            <div class="d-flex gap-3 align-items-center small mb-2">
                                <form method="POST" action="{{ route('admin.news-comments.toggle', $comment->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-link text-warning px-2 py-0"><i class="fas fa-eye{{ $comment->is_hidden ? '' : '-slash' }}"></i> {{ $comment->is_hidden ? 'Hiện' : 'Ẩn' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.news-comments.destroy', $comment->id) }}" onsubmit="return confirm('Xoá bình luận này và các phản hồi?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-link text-danger px-2 py-0"><i class="fas fa-trash-alt"></i> Xoá</button>
                                </form>
                                <form method="POST" action="{{ route('admin.news-comments.like', $comment->id) }}">
                                    @csrf
                                    @php $sessionKey = 'liked_comment_' . $comment->id; $liked = session()->has($sessionKey); @endphp
                                    <button type="submit" class="btn btn-link px-2 py-0 {{ $liked ? 'text-info' : 'text-secondary' }}" {{ $liked ? 'disabled' : '' }}>
                                        <i class="far fa-thumbs-up"></i> {{ $comment->likes_count ?? 0 }}
                                    </button>
                                </form>
                            </div>
                            <form method="POST" action="{{ route('admin.news-comments.reply', $comment->id) }}" class="mt-2 d-flex gap-2">
                                @csrf
                                <input type="text" name="content" class="form-control" placeholder="Nhập phản hồi...">
                                <button class="btn btn-sm btn-outline-primary">Trả lời</button>
                            </form>
                            @if ($comment->children->count())
                                <h5 class="mt-3 mb-2">Phản hồi</h5>
                                @php $maxReplyShow = 3; @endphp
                                <ul class="list-unstyled mt-3 ps-4 border-start border-primary" style="border-width:2px !important;" id="replies-list-{{ $comment->id }}">
                                    @foreach ($comment->children as $j => $child)
                                        <li class="mb-3 reply-item-{{ $comment->id }}">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                @if (!empty($child->user->avatar))
                                                    <img src="{{ asset($child->user->avatar) }}" alt="Avatar" class="rounded-circle shadow" style="width:32px;height:32px;object-fit:cover;">
                                                @else
                                                    <span class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center" style="width:32px;height:32px;font-weight:bold;font-size:1rem;">
                                                        {{ mb_substr($child->user->name ?? 'A', 0, 1) }}
                                                    </span>
                                                @endif
                                                <span class="fw-bold text-dark">{{ $child->user->name ?? 'Ẩn danh' }}</span>
                                                <span class="text-muted ms-2 small">{{ $child->created_at ? $child->created_at->locale('vi')->diffForHumans() : '' }}</span>
                                                <form method="POST" action="{{ route('admin.news-comments.toggle', $child->id) }}">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-link text-warning px-2 py-0"><i class="fas fa-eye{{ $child->is_hidden ? '' : '-slash' }}"></i></button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.news-comments.destroy', $child->id) }}" onsubmit="return confirm('Xoá phản hồi này?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-link text-danger px-2 py-0"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.news-comments.like', $child->id) }}">
                                                    @csrf
                                                    @php $sessionKey = 'liked_comment_' . $child->id; $liked = session()->has($sessionKey); @endphp
                                                    <button type="submit" class="btn btn-link px-2 py-0 {{ $liked ? 'text-info' : 'text-secondary' }}" {{ $liked ? 'disabled' : '' }}>
                                                        <i class="far fa-thumbs-up"></i> {{ $child->likes_count ?? 0 }}
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="bg-light rounded-3 p-2 ms-4 text-dark">{{ $child->content }}</div>
                                        </li>
                                    @endforeach
                                </ul>
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
@endsection
