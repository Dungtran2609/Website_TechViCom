@extends('admin.layouts.app')
@section('content')
<div class="container">
    <h2>Thêm Banner</h2>
    <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
            @error('image') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
            @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
            @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="link" class="form-label">Đường dẫn</label>
            <input type="text" name="link" class="form-control" value="{{ old('link') }}">
            @error('link') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection