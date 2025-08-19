@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Sản phẩm</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.trashed') }}" class="btn btn-outline-danger">
                <i class="fas fa-trash"></i> Thùng rác
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}"
                class="d-flex flex-nowrap align-items-end gap-2 justify-content-center" style="overflow-x:auto;">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    style="min-width:220px;max-width:260px;" placeholder="Tìm tên sản phẩm...">
                <select name="type" class="form-select" style="min-width:120px;">
                    <option value="">Tất cả loại</option>
                    <option value="simple" @if (request('type') == 'simple') selected @endif>Đơn giản</option>
                    <option value="variable" @if (request('type') == 'variable') selected @endif>Biến thể</option>
                </select>
                <select name="sort_price" class="form-select" style="min-width:130px;">
                    <option value="">Sắp xếp giá</option>
                    <option value="asc" @if (request('sort_price') == 'asc') selected @endif>Giá tăng dần</option>
                    <option value="desc" @if (request('sort_price') == 'desc') selected @endif>Giá giảm dần</option>
                </select>
                <select name="stock" class="form-select" style="min-width:110px;">
                    <option value="">Tồn kho</option>
                    <option value="in" @if (request('stock') == 'in') selected @endif>Còn hàng</option>
                    <option value="out" @if (request('stock') == 'out') selected @endif>Hết hàng</option>
                </select>
                <select name="status" class="form-select" style="min-width:110px;">
                    <option value="">Trạng thái</option>
                    <option value="active" @if (request('status') == 'active') selected @endif>Hiển thị</option>
                    <option value="inactive" @if (request('status') == 'inactive') selected @endif>Ẩn</option>
                </select>
                <button type="submit" class="btn btn-primary">Tìm</button>
                @if (request()->except('page'))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary ms-1">Hủy</a>
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Loại</th>
                            <th>Giá</th>
                            <th>Tổng tồn kho</th>
                            <th>Trạng thái</th>
                            <th class="text-center" width="200px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    @if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail))
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                                            class="avatar-md rounded">
                                    @else
                                        <div
                                            class="avatar-md bg-light rounded d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image fa-lg text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong><br>
                                    <small class="text-muted">
                                        {{ $product->category?->name ?? 'Không có' }} /
                                        {{ $product->brand?->name ?? 'Không có' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge {{ $product->type === 'simple' ? 'bg-secondary' : 'bg-info' }}">
                                        {{ $product->type === 'simple' ? 'Đơn giản' : 'Biến thể' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $product->price_range }}
                                </td>
                                <td>{{ $product->total_stock }}</td>
                                <td>
                                    @if ($product->status === 'active')
                                        <span class="badge bg-success-subtle text-success">Hiển thị</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Ẩn</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-light btn-sm"
                                            title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                            <iconify-icon icon="solar:pen-2-broken"
                                                class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn chuyển sản phẩm này vào thùng rác?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Không có sản phẩm nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
