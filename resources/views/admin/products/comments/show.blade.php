@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chi tiết bình luận & đánh giá</h1>
    <a href="{{ route('admin.products.comments.index') }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <p><strong>Sản phẩm:</strong> {{ $comment->product->name ?? '-' }}</p>
            <p><strong>Người dùng:</strong> {{ $comment->user->name ?? '-' }}</p>
            <p><strong>Đơn hàng:</strong> 
                @if($comment->order)
                    {{ $comment->order->random_code ?? $comment->order->code ?? ('DH' . str_pad($comment->order->id, 6, '0', STR_PAD_LEFT)) }}
                    <span class="badge bg-success ms-2">Đã nhận hàng</span>
                @else
                    <span class="text-muted">Không có thông tin đơn hàng</span>
                @endif
            </p>
            
            <!-- Hiển thị chi tiết sản phẩm đã mua -->
            @if($comment->order)
                @php
                    $orderItems = \App\Models\OrderItem::where('order_id', $comment->order->id)
                        ->where('product_id', $comment->product_id)
                        ->with(['productVariant.attributeValues.attribute', 'order'])
                        ->get();
                @endphp
                @if($orderItems->count() > 0)
                    <div class="mt-3">
                        <h6 class="fw-bold text-primary mb-2">Chi tiết sản phẩm đã mua:</h6>
                        <div class="row">
                            @foreach($orderItems as $orderItem)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-primary">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-light rounded" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                        @if($orderItem->productVariant && $orderItem->productVariant->image)
                                                            <img src="{{ asset('storage/' . ltrim($orderItem->productVariant->image, '/')) }}" alt="{{ $orderItem->name_product }}" class="w-100 h-100 object-fit-cover">
                                                        @elseif($orderItem->image_product)
                                                            <img src="{{ asset('storage/' . ltrim($orderItem->image_product, '/')) }}" alt="{{ $orderItem->name_product }}" class="w-100 h-100 object-fit-cover">
                                                        @elseif($orderItem->productVariant && $orderItem->productVariant->product && $orderItem->productVariant->product->thumbnail)
                                                            <img src="{{ asset('storage/' . ltrim($orderItem->productVariant->product->thumbnail, '/')) }}" alt="{{ $orderItem->name_product }}" class="w-100 h-100 object-fit-cover">
                                                        @else
                                                            <div class="text-muted small">IMG</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{ $orderItem->name_product }}</h6>
                                                    @if($orderItem->productVariant && $orderItem->productVariant->attributeValues->count() > 0)
                                                        <div class="mb-2">
                                                            @foreach($orderItem->productVariant->attributeValues as $attrValue)
                                                                <span class="badge bg-light text-dark border me-1 mb-1">
                                                                    {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-muted small">Số lượng: {{ $orderItem->quantity }}</span>
                                                        <span class="text-primary fw-bold">{{ number_format($orderItem->price, 0, ',', '.') }}đ</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
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
                <form action="{{ route('admin.products.comments.reply', $comment->id) }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" method="POST" class="ms-4">
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
    </div>
</div>
@endsection 