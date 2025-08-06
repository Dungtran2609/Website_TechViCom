@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">Chỉnh sửa thuộc tính</h1>
    <a href="{{ route('admin.products.attributes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.products.attributes.update', $attribute) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Tên thuộc tính <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $attribute->name) }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Nhập tên thuộc tính..."
                >
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Loại <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="text" @selected(old('type', $attribute->type) === 'text')>Văn bản (Text)</option>
                    <option value="color" @selected(old('type', $attribute->type) === 'color')>Màu sắc (Color)</option>
                    <option value="number" @selected(old('type', $attribute->type) === 'number')>Số (Number)</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea
                    name="description"
                    id="description"
                    rows="3"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Thêm mô tả cho thuộc tính (nếu có)..."
                >{{ old('description', $attribute->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
