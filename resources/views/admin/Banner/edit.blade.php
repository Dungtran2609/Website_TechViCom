@extends('admin.layouts.app')
@section('content')
<div class="container">
    <h2>Sửa Banner</h2>
    <form action="{{ route('admin.banner.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Hình ảnh hiện tại</label><br>
            <img src="{{ asset('storage/'.$banner->image) }}" width="150" onerror="this.src='https://via.placeholder.com/150x80?text=No+Image';">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Chọn hình ảnh mới (nếu muốn thay đổi)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @error('image') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="link" class="form-label">Đường dẫn</label>
            <input type="text" name="link" class="form-control" value="{{ old('link', $banner->link) }}">
            @error('link') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $banner->start_date) }}">
            @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $banner->end_date) }}">
            @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection