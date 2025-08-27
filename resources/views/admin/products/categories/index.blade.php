@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh mục sản phẩm</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.categories.trashed') }}" class="btn btn-danger">
                <i class="fas fa-trash"></i> Thùng rác
            </a>
            <a href="{{ route('admin.products.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm danh mục
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.products.categories.index') }}"
        class="mb-4 d-flex gap-2 align-items-center flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25"
            placeholder="Tìm danh mục...">

        <select name="status" class="form-select w-auto">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status', '') === '1' ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ request('status', '') === '0' ? 'selected' : '' }}>Ẩn</option>
        </select>

        <select name="parent_id" class="form-select w-auto">
            <option value="">-- Danh mục cha --</option>
            @isset($parentCategories)
                @foreach ($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}</option>
                @endforeach
            @endisset
        </select>

        <select name="child_id" class="form-select w-auto">
            <option value="">-- Danh mục con --</option>
            @isset($childCategories)
                @foreach ($childCategories as $child)
                    <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>
                        {{ $child->name }}</option>
                @endforeach
            @endisset
        </select>

        <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>

        @if (request('search') || request('status') !== null || request('parent_id') !== null || request('child_id') !== null)
            <a href="{{ route('admin.products.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-undo"></i> Quay lại
            </a>
        @endif
    </form>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Ảnh</th>
                            <th>Trạng thái</th>
                            <th>Chuỗi ký tự</th>
                            <th>Danh mục cấp trên</th>
                            <th width="200px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    <p class="text-dark fw-medium fs-15 mb-0">{{ $category->name }}</p>
                                </td>
                                <td>
                                    @if ($category->image)
                                        <div
                                            class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="Image"
                                                class="avatar-md">
                                        </div>
                                    @else
                                        Không có ảnh.
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                                        {{ $category->status ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->parent ? $category->parent->name : 'Không có' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.products.categories.show', $category) }}"
                                            class="btn btn-light btn-sm" title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.products.categories.edit', $category) }}"
                                            class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                            <iconify-icon icon="solar:pen-2-broken"
                                                class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.products.categories.destroy', $category) }}"
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
