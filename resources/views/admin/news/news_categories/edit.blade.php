@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Chỉnh sửa danh mục: {{ $newsCategory->name }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong> Vui lòng kiểm tra lại dữ liệu nhập vào.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.news-categories.update', $newsCategory->category_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" class="form-control" id="name" name="name"
                value="{{ old('name', $newsCategory->name) }}" required>
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.news-categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
