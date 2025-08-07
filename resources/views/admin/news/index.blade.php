@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý bài viết</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm bài viết
        </a>
        <a href="{{ route('admin.news.trash') }}" class="btn btn-outline-danger">
            <i class="fas fa-trash"></i> Thùng rác
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.news.index') }}" class="mb-4 d-flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Tìm bài viết...">
    <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
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
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Danh mục</th>
                        <th>Tác giả</th>
                        <th>Trạng thái</th>
                        <th>Ngày đăng</th>
                        <th width="200px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($news as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->id }}</td>
                            <td>
                                <p class="text-dark fw-medium fs-15 mb-0">{{ $item->title }}</p>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-dark">
                                    {{ $item->category?->name ?? 'Không có' }}
                                </span>
                            </td>
                            <td>{{ $item->author?->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status === 'published' ? 'success' : 'secondary' }}">
                                    {{ $item->status === 'published' ? 'Đã đăng' : 'Nháp' }}
                                </span>
                            </td>
                            <td>{{ $item->published_at ? $item->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.news.show', $item) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá bài viết này?')" title="Xoá">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $news->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
