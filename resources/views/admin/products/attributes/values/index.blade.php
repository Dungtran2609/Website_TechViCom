@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Giá trị thuộc tính: {{ $attribute->name }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.attributes.values.trashed', $attribute->id) }}" class="btn btn-danger">
            <i class="fas fa-trash"></i> Thùng rác
        </a>
        <a href="{{ route('admin.products.attributes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách thuộc tính
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.products.attributes.values.index', $attribute->id) }}" class="mb-4 d-flex gap-2 align-items-center">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Tìm theo tên giá trị...">
    <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
    @if (request('search'))
    <a href="{{ route('admin.products.attributes.values.index', $attribute->id) }}" class="btn btn-secondary">
        <i class="fas fa-undo"></i> Xoá bộ lọc
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

<div class="card mb-4">
    <div class="card-header bg-light fw-bold">Thêm giá trị mới</div>
    <div class="card-body">
        <form action="{{ route('admin.products.attributes.values.store', $attribute->id) }}" method="POST">
            @csrf
            
            @if ($attribute->type === 'color')
            <div class="row g-3">
                <div class="col-md-5">
                    <label for="value" class="form-label">Tên giá trị</label>
                    <input type="text" name="value" id="value" value="{{ old('value') }}" class="form-control @error('value') is-invalid @enderror" placeholder="Nhập tên màu...">
                    @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="color_code" class="form-label">Chọn mã màu</label>
                    <input type="color" name="color_code" id="color_code" value="{{ old('color_code', '#000000') }}" class="form-control form-control-color w-100 @error('color_code') is-invalid @enderror">
                    @error('color_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label"> </label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> Thêm
                    </button>
                </div>
            </div>

            @else
            <div>
                <label for="value" class="form-label">Tên giá trị</label>
                <div class="input-group @error('value') has-validation @enderror">
                    <input type="text" name="value" id="value" value="{{ old('value') }}" class="form-control @error('value') is-invalid @enderror" placeholder="Nhập tên giá trị...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm
                    </button>
                    @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-bordered table-hover text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên giá trị</th>
                        <th>Mã màu</th>
                        <th width="120px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($values as $val)
                    <tr>
                        <td>{{ $val->id }}</td>
                        <td class="fw-medium">{{ $val->value }}</td>
                        <td>
                            @if ($attribute->type === 'color')
                            <div style="width: 30px; height: 30px; border-radius: 50%; background-color: {{ $val->color_code }}; border: 1px solid #ccc; display: inline-block;" title="{{ $val->color_code }}"></div>
                            <div><small class="text-muted">{{ $val->color_code }}</small></div>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.products.attributes.values.edit', ['attribute' => $attribute->id, 'value' => $val->id]) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                </a>
                                <form action="{{ route('admin.products.attributes.values.destroy', ['attribute' => $attribute->id, 'value' => $val->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xoá">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Không có giá trị nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $values->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection