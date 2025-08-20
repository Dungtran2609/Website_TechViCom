@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý bình luận bài viết</h1>
</div>

<form method="GET" action="{{ route('admin.news-comments.index') }}" class="mb-4">
    <div class="row g-3">
        <div class="col-md-3">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm theo tiêu đề bài viết...">
        </div>
        <div class="col-md-2">
            <select name="category" class="form-select">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" placeholder="Từ ngày">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" placeholder="Đến ngày">
        </div>
        <div class="col-md-2">
            <select name="sort_by" class="form-select">
                <option value="latest_comment_created_at" {{ request('sort_by') == 'latest_comment_created_at' ? 'selected' : '' }}>Ngày bình luận</option>
                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Tên bài viết</option>
                <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID bài viết</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    @if(request('search') || request('category') || request('date_from') || request('date_to') || request('sort_by'))
        <div class="mt-2">
            <a href="{{ route('admin.news-comments.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
    @endif
</form>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>STT</th>
                        <th>Ảnh bài viết</th>
                        <th>Tên bài viết</th>
                        <th>ID bài viết</th>
                        <th>Ngày bình luận mới nhất</th>
                        <th width="150px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allNews as $news)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($news->image)
                                    <img src="{{ asset($news->image) }}" alt="Ảnh bài viết" 
                                         class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 60px;">
                                        <span class="text-muted small">Không có ảnh</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <h6 class="mb-0 text-dark fw-medium">{{ $news->title }}</h6>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $news->id }}</span>
                            </td>
                            <td>
                                @if($news->latest_comment_created_at)
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($news->latest_comment_created_at)->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.news-comments.show', $news->id) }}" 
                                   class="btn btn-warning btn-sm" title="Xem tất cả bình luận">
                                    <i class="fas fa-comments me-1"></i>
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-comments fa-2x mb-3"></i>
                                    <p>Không có bài viết nào có bình luận</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $allNews->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
