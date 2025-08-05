@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold text-primary">Sửa giá trị: {{ $value->value }}</h1>
        <a href="{{ route('admin.products.attributes.values.index', $attribute->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-semibold">
            <i class="fas fa-edit"></i> Chỉnh sửa giá trị
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.attributes.values.update', $value->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="value" class="form-label">Tên giá trị</label>
                    <input type="text" name="value" id="value" class="form-control @error('value') is-invalid @enderror"
                           value="{{ old('value', $value->value) }}" placeholder="Ví dụ: Xanh, 32GB, Lớn">
                    @error('value')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @if ($attribute->type === 'color')
                <div class="col-md-2">
                    <label for="color_code" class="form-label">Mã màu</label>
                    <input type="color" name="color_code" id="color_code"
                           class="form-control form-control-color @error('color_code') is-invalid @enderror"
                           value="{{ old('color_code', $value->color_code ?? '#000000') }}">
                    @error('color_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                @endif

                <div class="col-md-4 d-flex flex-column">
                    <button type="submit" class="btn btn-success px-4 mt-auto">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection