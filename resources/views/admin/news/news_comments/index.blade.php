@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Quản lý bình luận bài viết</h2>
        <div class="mb-3" >
            <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
                {{-- Ô tìm kiếm nội dung, người dùng, bài viết --}}
                <input type="text" name="keyword" class="form-control" style="max-width: 300px;"
                    placeholder="Tìm theo nội dung, người dùng, bài viết..." value="{{ request('keyword') }}">

                {{-- Lọc theo bài viết --}}
                <select name="news_id" class="form-select" style="max-width: 300px;">
                    <option value="">-- Tất cả bài viết --</option>
                    @foreach ($allNews as $news)
                        <option value="{{ $news->id }}" {{ request('news_id') == $news->id ? 'selected' : '' }}>
                            {{ $news->title }}
                        </option>
                    @endforeach
                </select>

                {{-- Nút tìm kiếm --}}
                <button class="btn btn-primary">Tìm kiếm</button>

                {{-- Nút huỷ --}}
                @if (request()->hasAny(['keyword', 'status', 'news_id', 'from_date', 'to_date']))
                    <a href="{{ route('admin.news-comments.index') }}" class="btn btn-secondary">Hủy</a>
                @endif
            </form>


        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($comments->isEmpty())
            <div class="alert alert-warning">Không có bình luận nào.</div>
        @else
            <div class="accordion" id="commentAccordion">
                @foreach ($comments as $comment)
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="heading{{ $comment->id }}">
                            <button class="accordion-button collapsed fs-5 w-100 text-start" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $comment->id }}"
                                aria-expanded="false" aria-controls="collapse{{ $comment->id }}">
                                <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong> -
                                {{ Str::limit($comment->content, 60) }}
                            </button>
                        </h2>
                        <div id="collapse{{ $comment->id }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $comment->id }}" data-bs-parent="#commentAccordion">
                            <div class="accordion-body">
                                {{-- Thông tin bài viết --}}
                                <div class="mb-2">
                                    <strong>Bài viết:</strong>
                                    <a href="{{ route('admin.news.show', $comment->news->id) }}" target="_blank">
                                        {{ $comment->news->title }}
                                    </a>
                                    <span class="text-muted">-
                                        {{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}</span>
                                </div>

                                {{-- Nội dung comment --}}
                                <p class="{{ $comment->is_hidden ? 'text-muted fst-italic' : '' }}">
                                    {{ $comment->is_hidden ? '[Đã ẩn] ' : '' }}{{ $comment->content }}
                                </p>

                                {{-- Hành động --}}
                                <div class="mt-3 d-flex flex-wrap gap-2">
                                    <form action="{{ route('admin.news-comments.toggle', $comment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            class="btn btn-sm {{ $comment->is_hidden ? 'btn-success' : 'btn-warning' }}">
                                            <i class="fas fa-eye{{ $comment->is_hidden ? '' : '-slash' }}"></i>
                                            {{ $comment->is_hidden ? 'Hiện' : 'Ẩn' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.news-comments.destroy', $comment->id) }}" method="POST"
                                        onsubmit="return confirm('Xoá bình luận này và các phản hồi?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i> Xoá
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.news-comments.like', $comment->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-thumbs-up"></i> Like ({{ $comment->likes_count ?? 0 }})
                                        </button>
                                    </form>
                                </div>

                                {{-- Form trả lời --}}
                                <form action="{{ route('admin.news-comments.reply', $comment->id) }}" method="POST"
                                    class="mt-3 d-flex gap-2">
                                    @csrf
                                    <input type="text" name="content" class="form-control"
                                        placeholder="Nhập phản hồi...">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-reply"></i> Trả lời
                                    </button>
                                </form>

                                {{-- Phản hồi con --}}
                                @foreach ($comment->children as $child)
                                    <div class="mt-3 ps-3 border-start border-primary">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $child->user->name ?? 'Ẩn danh' }}</strong>
                                            <small>{{ $child->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <p class="{{ $child->is_hidden ? 'text-muted fst-italic' : '' }}">
                                            {!! $child->is_hidden ? '<span class="text-warning">[Đã ẩn]</span> ' : '' !!}{{ $child->content }}
                                        </p>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('admin.news-comments.toggle', $child->id) }}"
                                                method="POST">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-eye{{ $child->is_hidden ? '' : '-slash' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.news-comments.destroy', $child->id) }}"
                                                method="POST" onsubmit="return confirm('Xoá phản hồi này?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>

                                            {{-- 👍 Nút Like cho bình luận con --}}
                                            <form action="{{ route('admin.news-comments.like', $child->id) }}"
                                                method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-thumbs-up"></i> Like ({{ $child->likes_count ?? 0 }})
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
