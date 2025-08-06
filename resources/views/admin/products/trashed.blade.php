@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thùng rác sản phẩm</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Trở về danh sách
    </a>
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
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Loại</th>
                        <th>Thương hiệu / Danh mục</th>
                        <th>Ngày xoá</th>
                        <th width="250px" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <p class="text-dark fw-medium fs-15 mb-0">{{ $product->name }}</p>
                            </td>
                            <td>
                                @if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail))
                                    <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="avatar-md">
                                    </div>
                                @else
                                    Không có ảnh.
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $product->type === 'simple' ? 'bg-secondary' : 'bg-info text-dark' }} text-capitalize">
                                    {{ $product->type === 'simple' ? 'Sản phẩm đơn' : 'Sản phẩm biến thể' }}
                                </span>
                            </td>
                            <td>
                                {{ $product->brand->name ?? 'Không có' }} <br>
                                <small class="text-muted">{{ $product->category->name ?? 'Không có' }}</small>
                            </td>
                            <td>{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-undo"></i> Khôi phục
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hành động này không thể hoàn tác! Bạn có chắc chắn muốn XOÁ VĨNH VIỄN sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Xoá vĩnh viễn
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Thùng rác trống.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
             @if($products->count() > 0)
            <div class="mt-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection