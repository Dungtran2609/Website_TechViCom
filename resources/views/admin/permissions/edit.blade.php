@extends('admin.layouts.app')

@section('content')
    <h1>Chỉnh sửa quyền: {{ $permission->name }}</h1>

    <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên quyền</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $permission->name) }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" name="description">{{ old('description', $permission->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.permissions.list') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
