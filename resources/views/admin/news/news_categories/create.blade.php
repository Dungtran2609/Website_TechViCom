@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Thêm danh mục bài viết</h2>

    <form action="{{ route('admin.news-categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" class="form-control" id="name" name="name"
                value="{{ old('name') }}">
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.news-categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
