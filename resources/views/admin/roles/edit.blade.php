@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chỉnh sửa vai trò</h1>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Tên vai trò</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $role->slug) }}" required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Trạng thái</label>
        <select id="status" name="status" class="form-select">
            <option value="1" {{ $role->status ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ !$role->status ? 'selected' : '' }}>Ẩn</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>
@endsection
