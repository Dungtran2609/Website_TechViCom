@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Chi tiết bình luận & đánh giá</h3>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <p><strong>Sản phẩm:</strong> {{ $comment->product->name ?? '-' }}</p>
            <p><strong>Người dùng:</strong> {{ $comment->user->name ?? '-' }}</p>
            <p><strong>Ngày gửi:</strong> {{ $comment->created_at->format('d/m/Y - H:i') }}</p>
            <p><strong>Đánh giá:</strong>
                @if($comment->rating)
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $comment->rating)
                            <span style="color: gold;">★</span>
                        @else
                            <span style="color: #555;">★</span>
                        @endif
                    @endfor
                @else
                    (Không đánh giá)
                @endif
            </p>
            <!-- Hiển thị bình luận gốc -->
            <div class="card mb-3">
                <div class="card-body">
                    <p><strong>Bình luận:</strong> {{ $comment->content }}</p>
                    <p><strong>Trạng thái:</strong>
                        @if($comment->status === 'approved')
                            <span class="badge bg-success">Đã duyệt</span>
                        @elseif($comment->status === 'pending')
                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                        @else
                            <span class="badge bg-danger">Đã xoá</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Hiển thị phản hồi (nếu có) -->
            @if($comment->replies->count())
                <div class="card mb-3 ms-4">
                    <div class="card-body bg-light">
                        <p><strong>Phản hồi của admin:</strong> {{ $comment->replies->first()->content }}</p>
                    </div>
                </div>
            @endif

            <!-- Form phản hồi -->
            @if($comment->status === 'pending')
                <div class="alert alert-warning ms-4 mt-2">Chỉ phản hồi được khi bình luận đã được duyệt.</div>
            @elseif(!$comment->replies->count())
                <form action="{{ route('products.comments.reply', $comment->id) }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" method="POST" class="ms-4">
                    @csrf
                    @if(request('product_id'))
                        <input type="hidden" name="product_id" value="{{ request('product_id') }}">
                    @endif
                    <div class="mb-2">
                        <textarea name="reply_content" class="form-control" rows="2" placeholder="Nhập phản hồi..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Gửi phản hồi</button>
                </form>
            @endif
        </div>
    </div>
    @php
        $backUrl = route('admin.products.comments.index', ['product_id' => request('product_id', $comment->product_id)]);
    @endphp
    <a href="{{ $backUrl }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
</div>
@endsection 