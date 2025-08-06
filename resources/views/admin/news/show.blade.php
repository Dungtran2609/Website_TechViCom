@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Tiêu đề trang --}}
        <div class="mb-4 border-bottom pb-2">
            <h1 class="h3 text-primary">Chi tiết bài viết</h1>
        </div>

        {{-- Nội dung bài viết --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                {{-- Tiêu đề --}}
                <h2 class="card-title mb-3 text-dark">{{ $news->title }}</h2>

                {{-- Danh mục --}}
                <p class="mb-3">
                    <strong>Danh mục:</strong>
                    <span class="badge bg-info text-dark">
                        {{ $news->category?->name ?? 'Không có' }}
                    </span>
                </p>

                {{-- Ảnh đại diện --}}
                <div class="mb-4">
                    <strong>Ảnh đại diện:</strong><br>
                    @if ($news->image)
                        <img src="{{ asset($news->image) }}" alt="Ảnh bài viết" class="img-fluid rounded shadow-sm mt-2"
                            style="max-width: 400px;">
                    @else
                        <p class="text-muted fst-italic mt-2">Chưa có ảnh</p>
                    @endif
                </div>

                {{-- Nội dung --}}
                <div class="mb-4">
                    <strong>Nội dung:</strong>
                    <div class="border p-3 rounded mt-2 bg-light">
                        {!! $news->content !!}
                    </div>
                </div>

                {{-- Trạng thái + Ngày đăng --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Trạng thái:</strong>
                        <span class="badge {{ $news->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $news->status === 'published' ? 'Đã đăng' : 'Nháp' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Ngày đăng:</strong>
                        <span class="text-muted">
                            {{ $news->published_at ? $news->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}
                        </span>
                    </div>
                </div>

                {{-- Nút quay lại --}}
                <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        {{-- Khu vực bình luận --}}
        <div class="card shadow-sm rounded-4 border-0 mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Bình luận</h5>
            </div>
            <div class="card-body">
                @if ($news->visibleComments->isEmpty())
                    <p class="text-muted">Chưa có bình luận nào.</p>
                @else
                    <ul class="list-unstyled">
                        @foreach ($news->visibleComments as $comment)
                            <li class="mb-4 pb-3 border-bottom">
                                {{-- Phần thông tin người dùng --}}
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-3"
                                        style="width: 44px; height: 44px; font-weight: bold; font-size: 1.2rem;">
                                        {{ mb_substr($comment->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong
                                            class="d-block text-dark">{{ $comment->user->name ?? 'Người dùng không xác định' }}</strong>
                                        <small class="text-muted">
                                            {{ $comment->created_at->format('d/m/Y H:i') }}
                                            @if ($comment->updated_at && $comment->updated_at != $comment->created_at)
                                                (Đã sửa: {{ $comment->updated_at->format('d/m/Y H:i') }})
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                {{-- Nội dung bình luận --}}
                                <div class="ms-5">
                                    <p class="mb-2">{{ $comment->content }}</p>

                                    {{-- Nút like--}}
                                    <div class="d-flex gap-3 align-items-center small text-muted">
                                        @php
                                            $sessionKey = 'liked_comment_' . $comment->id;
                                            $hasLiked = session()->has($sessionKey);
                                        @endphp

                                        <form method="POST"
                                            action="{{ route('admin.news-comments.like', $comment->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm d-flex align-items-center gap-1 {{ $hasLiked ? 'btn-secondary' : 'btn-outline-primary' }}"
                                                {{ $hasLiked ? 'disabled' : '' }} style="transition: 0.3s;"
                                                onmouseover="this.classList.remove('btn-outline-primary'); this.classList.add('btn-primary');"
                                                onmouseout="if(!{{ $hasLiked ? 'true' : 'false' }}){this.classList.remove('btn-primary'); this.classList.add('btn-outline-primary');}">
                                                👍 <span>{{ $comment->likes_count }}</span>
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Form trả lời (ẩn/hiện) --}}
                                    <div class="collapse mt-3" id="replyForm{{ $comment->id }}">
                                        <form method="POST"
                                            action="{{ route('admin.news-comments.reply', $comment->id) }}">
                                            @csrf
                                            <div class="mb-2">
                                                <textarea name="content" class="form-control rounded-3" rows="2" placeholder="Nhập phản hồi..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary">Gửi</button>
                                        </form>
                                    </div>

                                    {{-- Bình luận con (nếu có) --}}
                                    @if ($comment->replies && $comment->replies->count())
                                        <ul class="list-unstyled mt-3 ps-4 border-start border-2">
                                            @foreach ($comment->replies as $reply)
                                                <li class="mb-3">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <strong
                                                            class="text-dark">{{ $reply->user->name ?? 'Ẩn danh' }}</strong>
                                                        <small
                                                            class="text-muted">{{ $reply->created_at->format('d/m/Y H:i') }}</small>
                                                    </div>
                                                    <p class="mb-0 ms-2">{{ $reply->content }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>
@endsection
