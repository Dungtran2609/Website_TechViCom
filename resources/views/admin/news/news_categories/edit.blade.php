@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Chỉnh sửa danh mục: {{ $newsCategory->name }}</h2>

    

    <form action="{{ route('admin.news-categories.update', $newsCategory->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" class="form-control" id="name" name="name"
                value="{{ old('name', $newsCategory->name) }}" >
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.news-categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
