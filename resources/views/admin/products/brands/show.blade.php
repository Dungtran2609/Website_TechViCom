@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi tiết thương hiệu</h1>
        <div>
            <a href="{{ route('admin.products.brands.edit', $brand) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.products.brands.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Thông tin cơ bản --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">ID thương hiệu:</th>
                            <td>{{ $brand->id }}</td>
                        </tr>
                        <tr>
                            <th>Tên thương hiệu:</th>
                            <td>{{ $brand->name }}</td>
                        </tr>
                        <tr>
                            <th>Chuỗi ký tự (slug):</th>
                            <td>{{ $brand->slug }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge bg-{{ $brand->status ? 'success' : 'secondary' }}">
                                    {{ $brand->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Mô tả --}}
            @if($brand->description)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mô tả</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $brand->description }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Ảnh + Thao tác nhanh --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ảnh thương hiệu</h5>
                </div>
                <div class="card-body text-center">
                    @if($brand->image)
                        <img src="{{ asset('storage/' . $brand->image) }}" 
                             alt="{{ $brand->name }}" 
                             class="img-fluid rounded mb-3" 
                             style="max-height: 200px;">
                    @else
                        <p class="text-muted">Chưa có ảnh.</p>
                    @endif
                </div>
            </div>

            {{-- Thao tác nhanh --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.brands.edit', $brand) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.products.brands.destroy', $brand) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Bạn có chắc chắn muốn xoá thương hiệu này?')">
                                <i class="fas fa-trash"></i> Xoá thương hiệu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
