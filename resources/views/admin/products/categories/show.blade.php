@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi tiết danh mục</h1>
        <div>
            <a href="{{ route('admin.products.categories.edit', $category) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Sửa danh mục
            </a>
            <a href="{{ route('admin.products.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Trở về trang danh sách
            </a>
        </div>
    </div>

    <div class="row">
        {{-- LEFT COLUMN: Basic Info --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">ID:</th>
                            <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                            <th>Tên danh mục:</th>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <th>Chuỗi ký tự:</th>
                            <td>{{ $category->slug }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                                    {{ $category->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Danh mục cấp trên:</th>
                            <td>
                                @if($category->parent)
                                    <a href="{{ route('admin.products.categories.show', $category->parent) }}">
                                        {{ $category->parent->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ảnh:</th>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" width="120" alt="Category Image">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Subcategories --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"> Danh mục con</h5>
                    <a href="{{ route('admin.products.categories.create') }}?parent_id={{ $category->id }}"
                       class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Thêm danh mục con
                    </a>
                </div>
                <div class="card-body">
                    @if($category->children->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->children as $child)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.products.categories.show', $child) }}">
                                                    {{ $child->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $child->status ? 'success' : 'secondary' }}">
                                                    {{ $child->status ? 'Hiển thị' : 'Ẩn' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.categories.edit', $child) }}"
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.products.categories.destroy', $child) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this subcategory?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Không tìm thấy danh mục con n.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
