@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thương hiệu sản phẩm</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.brands.trashed') }}" class="btn btn-outline-danger">
            <i class="fas fa-trash"></i> Thùng rác
        </a>
        <a href="{{ route('admin.products.brands.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm thương hiệu
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.brands.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm kiếm theo tên thương hiệu...">
            <button type="submit" class="btn btn-primary">Tìm</button>
            @if(request('search'))
                <a href="{{ route('admin.products.brands.index') }}" class="btn btn-secondary">Hủy</a>
            @endif
        </form>
    </div>
</div>

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

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>ID</th>
                        <th>Tên thương hiệu</th>
                        <th>Ảnh</th>
                        <th>Số sản phẩm</th>
                        <th>Trạng thái</th>
                        <th>Đường dẫn</th>
                        <th class="text-center" width="200px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $brand)
                        <tr>
                            <td>{{ $brand->id }}</td>
                            <td>
                                <p class="text-dark fw-medium fs-15 mb-0">{{ $brand->name }}</p>
                            </td>
                            <td>
                                @if ($brand->image && Storage::disk('public')->exists($brand->image))
                                    <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('storage/' . $brand->image) }}" alt="Image" class="avatar-md">
                                    </div>
                                @else
                                    <div class="avatar-md bg-light rounded d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image fa-lg text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info">{{ $brand->products_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $brand->status ? 'success-subtle text-success' : 'danger-subtle text-danger' }}">
                                    {{ $brand->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td>{{ $brand->slug }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.products.brands.show', $brand) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.products.brands.edit', $brand) }}" class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.products.brands.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn chuyển thương hiệu này vào thùng rác?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Không có thương hiệu nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $brands->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection