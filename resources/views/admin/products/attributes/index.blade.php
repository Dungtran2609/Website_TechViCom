@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thuộc tính sản phẩm</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.attributes.trashed') }}" class="btn btn-danger">
            <i class="fas fa-trash"></i> Thùng rác
        </a>
        <a href="{{ route('admin.products.attributes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm thuộc tính
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.products.attributes.index') }}" class="mb-4 d-flex gap-2 align-items-center">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25"
        placeholder="Tìm thuộc tính...">
    <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>

    @if (request('search'))
        <a href="{{ route('admin.products.attributes.index') }}" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Quay lại danh sách đầy đủ
        </a>
    @endif
</form>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên thuộc tính</th>
                        <th>Loại</th>
                        <th>Đường dẫn</th>
                        <th>Mô tả</th>
                        <th width="180px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attributes as $attribute)
                        <tr>
                            <td>{{ $attribute->id }}</td>
                            <td class="fw-semibold">{{ $attribute->name }}</td>
                            <td>
                                @switch($attribute->type)
                                    @case('text')
                                        Văn bản
                                        @break
                                    @case('number')
                                        Số
                                        @break
                                    @case('color')
                                        Màu sắc
                                        @break
                                    @default
                                        {{ ucfirst($attribute->type) }}
                                @endswitch
                            </td>
                            <td>{{ $attribute->slug }}</td>
                            <td>{{ $attribute->description ?? 'Chưa có' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.attributes.values.index', $attribute->id) }}"
                                        class="btn btn-light btn-sm" title="Xem giá trị">
                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.products.attributes.edit', $attribute) }}"
                                        class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                        <iconify-icon icon="solar:pen-2-broken"
                                            class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.products.attributes.destroy', $attribute) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc muốn xoá thuộc tính này?')"
                                            title="Xoá">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Không có thuộc tính nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $attributes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
