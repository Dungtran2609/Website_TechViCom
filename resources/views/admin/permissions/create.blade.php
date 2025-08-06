@extends('admin.layouts.app')

@section('content')
    <h1>Thêm quyền mới</h1>

    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên quyền</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả (tuỳ chọn)</label>
            <textarea class="form-control" name="description">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.permissions.list') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
