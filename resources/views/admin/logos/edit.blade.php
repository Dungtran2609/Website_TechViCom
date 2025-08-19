@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
    <h3>Sửa logo</h3>
    <form action="{{ route('admin.logos.update', $logo->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Loại logo</label>
            <select name="type" class="form-select" required>
                <option value="client" {{ $logo->type == 'client' ? 'selected' : '' }}>Logo trang chủ (client)</option>
                <option value="admin" {{ $logo->type == 'admin' ? 'selected' : '' }}>Logo admin/sidebar</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Chọn ảnh logo mới (nếu muốn thay)</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
            <div class="mt-2">
                <img src="{{ asset('storage/' . $logo->path) }}" alt="{{ $logo->alt }}" style="max-height:60px;">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Alt (mô tả ảnh, tuỳ chọn)</label>
            <input type="text" name="alt" class="form-control" value="{{ $logo->alt }}">
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.logos.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
