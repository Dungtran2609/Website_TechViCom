@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh mục bài viết</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.news-categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm danh mục
            </a>
        </div>
    </div>

    <form action="{{ route('admin.news-categories.index') }}" method="GET" class="mb-3 d-flex" style="max-width: 400px;">
        <input type="text" name="keyword" class="form-control me-2" value="{{ request('keyword') }}"
            placeholder="Tìm theo tên danh mục...">
        <button type="submit" class="btn btn-outline-primary">Tìm</button>
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
                            <th>Tên danh mục</th>
                            <th>Ngày tạo</th>
                            <th width="200px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->id }}</td>
                                <td class="text-dark fw-medium fs-15 mb-0">{{ $category->name }}</td>
                                <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.news-categories.edit', $category->id) }}"
                                            class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                            <iconify-icon icon="solar:pen-2-broken"
                                                class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.news-categories.destroy', $category->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn xoá danh mục này?')"
                                                title="Xoá">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
