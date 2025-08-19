@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
    <h3>Thêm logo mới</h3>
    <form action="{{ route('admin.logos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Loại logo</label>
            <select name="type" class="form-select" required>
                <option value="client">Logo trang chủ (client)</option>
                <option value="admin">Logo admin/sidebar</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Chọn ảnh logo</label>
            <input type="file" name="logo" class="form-control" required accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Alt (mô tả ảnh, tuỳ chọn)</label>
            <input type="text" name="alt" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.logos.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
