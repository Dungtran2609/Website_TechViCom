@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thêm vai trò</h1>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Tên vai trò</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên vai trò" required>
    </div>

    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" id="slug" name="slug" class="form-control" placeholder="vd: admin" required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Trạng thái</label>
        <select id="status" name="status" class="form-select">
            <option value="1">Hiển thị</option>
            <option value="0">Ẩn</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Thêm vai trò</button>
</form>
@endsection
