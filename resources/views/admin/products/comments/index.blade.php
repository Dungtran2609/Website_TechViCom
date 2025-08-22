@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý bình luận & đánh giá sản phẩm</h1>
    
    @if(request('product_id'))
        <a href="{{ route('admin.products.comments.products-with-comments') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách sản phẩm
        </a>
    @endif
</div>
<form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="text" name="product_name" class="form-control" value="{{ request('product_name') }}"
            placeholder="Nhập tên sản phẩm...">
    </div>
    <div class="col-md-2">
        <input type="text" name="user_name" class="form-control" value="{{ request('user_name') }}"
            placeholder="Nhập tên người dùng...">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">-- Tất cả --</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Đã xóa</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="rating" class="form-select">
            <option value="">-- Tất cả --</option>
            @for($i=1;$i<=5;$i++)
                <option value="{{$i}}" {{ request('rating')==$i?'selected':'' }}>{{$i}} sao</option>
            @endfor
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="col-md-1 d-flex gap-1">
        <button type="submit" class="btn btn-outline-primary" title="Tìm kiếm">
            <i class="fas fa-search"></i>
        </button>
        <a href="{{ route('admin.products.comments.index') }}" class="btn btn-outline-secondary" title="Làm mới">
            <i class="fas fa-times"></i>
        </a>
    </div>
</form>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>STT</th>
                        <th>Sản phẩm</th>
                        <th>Người dùng</th>
                        <th>Đơn hàng</th>
                        <th>Sản phẩm đã mua</th>
                        <th>Ngày gửi</th>
                        <th>Đánh giá</th>
                        <th>Bình luận</th>
                        <th>Phản hồi</th>
                        <th>Trạng thái</th>
                        <th>Ẩn/Hiện</th>
                        <th width="120px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($comments as $comment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="text-wrap" style="max-width: 200px;">
                                    <span class="text-dark fw-medium">{{ Str::limit($comment->product->name ?? '-', 50) }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $comment->user->name ?? '-' }}</span>
                            </td>
                            <td>
                                @if($comment->order)
                                    <div>
                                        <span class="text-dark">{{ $comment->order->random_code ?? $comment->order->code ?? ('DH' . str_pad($comment->order->id, 6, '0', STR_PAD_LEFT)) }}</span>
                                        <span class="badge bg-success ms-1">Đã nhận</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($comment->order)
                                    @php
                                        $orderItems = \App\Models\OrderItem::where('order_id', $comment->order->id)
                                            ->where('product_id', $comment->product_id)
                                            ->with(['productVariant.attributeValues.attribute'])
                                            ->get();
                                    @endphp
                                    @if($orderItems->count() > 0)
                                        @foreach($orderItems as $orderItem)
                                            <div class="mb-1">
                                                <div class="text-dark fw-medium">{{ $orderItem->name_product }}</div>
                                                @if($orderItem->productVariant && $orderItem->productVariant->attributeValues->count() > 0)
                                                    <div class="text-muted small">
                                                        @foreach($orderItem->productVariant->attributeValues as $attrValue)
                                                            <span class="badge bg-light text-dark me-1">
                                                                {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <div class="text-muted small">
                                                    SL: {{ $orderItem->quantity }} | {{ number_format($orderItem->price, 0, ',', '.') }}đ
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td>
                                @if($comment->rating)
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $comment->rating)
                                                <span class="text-warning">★</span>
                                            @else
                                                <span class="text-muted">★</span>
                                            @endif
                                        @endfor
                                        <span class="text-muted ms-1">({{ $comment->rating }}/5)</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark">{{ Str::limit($comment->content, 80) }}</span>
                            </td>
                            <td>
                                @if($comment->replies && $comment->replies->count())
                                    <span class="text-primary">{{ Str::limit($comment->replies->first()->content, 60) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($comment->status === 'approved')
                                    <span class="badge bg-success">Đã duyệt</span>
                                @elseif($comment->status === 'pending')
                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                @else
                                    <span class="badge bg-danger">Đã xóa</span>
                                @endif
                            </td>
                            <td>
                                @if($comment->is_hidden)
                                    <span class="badge bg-danger">Đã ẩn</span>
                                @else
                                    <span class="badge bg-success">Hiển thị</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.products.comments.show', $comment->id) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($comment->status === 'pending')
                                        <form action="{{ route('admin.products.comments.approve', $comment->id) }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Duyệt">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.products.comments.toggle-hidden', $comment->id) }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm" title="{{ $comment->is_hidden ? 'Hiện' : 'Ẩn' }}">
                                            <i class="fas {{ $comment->is_hidden ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.comments.destroy', $comment->id) }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn ẩn bình luận này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Ẩn vĩnh viễn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-comments fa-2x mb-3"></i>
                                    <p>Không có bình luận nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <!-- Pagination placeholder -->
    </div>
</div>
@endsection