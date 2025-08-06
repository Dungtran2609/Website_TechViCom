@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Chỉnh sửa bình luận & đánh giá</h3>
    <form action="{{ route('admin.products.comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nội dung bình luận</label>
            <textarea name="content" class="form-control" rows="3" required>{{ old('content', $comment->content) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Đánh giá (số sao)</label>
            <select name="rating" class="form-select">
                <option value="">Không đánh giá</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" @if($comment->rating == $i) selected @endif>{{ $i }} sao</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.products.comments.index') }}" class="btn btn-secondary">Huỷ</a>
    </form>
</div>
@endsection 