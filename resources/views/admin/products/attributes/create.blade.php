@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thêm thuộc tính</h1>
    <a href="{{ route('admin.products.attributes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.attributes.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Tên thuộc tính</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Loại</label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Văn bản</option>
                    <option value="color" {{ old('type') == 'color' ? 'selected' : '' }}>Màu sắc</option>
                    <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>Số</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu thuộc tính
            </button>
        </form>
    </div>
</div>
@endsection
