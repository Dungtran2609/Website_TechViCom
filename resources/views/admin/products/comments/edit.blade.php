@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chỉnh sửa bình luận & đánh giá</h1>
    <a href="{{ route('admin.products.comments.index') }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
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
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Cập nhật
                </button>
                <a href="{{ route('admin.products.comments.index') }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Huỷ
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 