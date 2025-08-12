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

        @if ($allNews->isEmpty())
            <div class="alert alert-warning">Không có bài viết nào có bình luận.</div>
        @else
            <div class="row">
                @foreach ($allNews as $news)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow rounded-4 border-0 hover-shadow" style="transition:box-shadow .2s;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="text-center mb-3">
                                    @if ($news->image)
                                        <img src="{{ asset($news->image) }}" alt="Ảnh bài viết" class="w-100 rounded-top shadow-sm mb-0" style="height:160px;object-fit:cover;">
                                    @else
                                        <div class="bg-light rounded-top d-flex align-items-center justify-content-center mb-0" style="height:160px;">
                                            <span class="text-muted">Ảnh bài viết</span>
                                        </div>
                                    @endif
                                    <h5 class="card-title fw-bold text-dark mb-2" style="font-size:1.15rem;">{{ $news->title }}</h5>
                                    <p class="card-text text-muted mb-1">ID: {{ $news->id }}</p>
                                </div>
                                <a href="{{ route('admin.news-comments.show', $news->id) }}" class="btn btn-warning btn-lg fw-semibold w-100 mt-auto" style="letter-spacing:1px;">
                                    Xem tất cả bình luận
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
@push('styles')
    <style>
        .card.hover-shadow:hover {
            box-shadow: 0 6px 24px rgba(44,62,80,0.13) !important;
        }
    </style>
@endpush
        @endif
    </div>
@endsection
