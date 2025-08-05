@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Quản lý bình luận & đánh giá sản phẩm</h2>
    @if(request('product_id'))
        <a href="{{ route('admin.products.comments.products-with-comments') }}" class="btn btn-secondary mb-3">← Quay lại danh sách sản phẩm</a>
    @endif
    <form method="GET" class="row g-2 mb-3">
        <div class="col">
            <input type="text" name="product_name" class="form-control" placeholder="Tên sản phẩm" value="{{ request('product_name') }}">
        </div>
        <div class="col">
            <input type="text" name="user_name" class="form-control" placeholder="Người dùng" value="{{ request('user_name') }}">
        </div>
        <div class="col">
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Đã duyệt</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Chờ duyệt</option>
                <option value="deleted" {{ request('status')=='deleted'?'selected':'' }}>Đã xoá</option>
            </select>
        </div>
        <div class="col">
            <select name="rating" class="form-select">
                <option value="">-- Số sao --</option>
                @for($i=1;$i<=5;$i++)
                    <option value="{{$i}}" {{ request('rating')==$i?'selected':'' }}>{{$i}} sao</option>
                @endfor
            </select>
        </div>
        <div class="col">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col d-flex align-items-center gap-2">
            <button type="submit" class="btn btn-primary" title="Tìm kiếm">
                <i class="bi bi-search"></i>
            </button>
            <a href="{{ route('admin.products.comments.index') }}" class="btn btn-secondary" title="Reset">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
        </div>
    </form>
    <table class="table table-dark table-bordered align-middle text-center">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Người dùng</th>
                <th>Ngày - Giờ gửi</th>
                <th>Đánh giá</th>
                <th>Bình luận</th>
                <th>Phản hồi</th>
                <th>Trạng thái</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
            <tr>
                <td>{{ $comment->product->name ?? '-' }}</td>
                <td>{{ $comment->user->name ?? '-' }}</td>
                <td>{{ $comment->created_at->format('d/m/Y - H:i') }}</td>
                <td>
                    @if($comment->rating)
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $comment->rating)
                                <span style="color: gold;">★</span>
                            @else
                                <span style="color: #555;">★</span>
                            @endif
                        @endfor
                    @else
                        <span>(Không đánh giá)</span>
                    @endif
                </td>
                <td class="text-start">{{ $comment->content }}</td>
                <td class="text-start">
                    @if($comment->replies && $comment->replies->count())
                        {{ $comment->replies->first()->content }}
                    @else
                        <span class="text-muted">Chưa phản hồi</span>
                    @endif
                </td>
                <td>
                    @if($comment->status === 'approved')
                        <span class="badge bg-success">Đã duyệt</span>
                    @elseif($comment->status === 'pending')
                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                    @else
                        <span class="badge bg-danger">Đã xoá</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-center align-items-center gap-2" style="height: 100%;">
                    <a href="{{ route('admin.products.comments.show', $comment->id) }}" title="Xem"><i class="bi bi-eye"></i></a>
                    @if($comment->status === 'pending')
                        <form action="{{ route('admin.products.comments.approve', $comment->id) }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Duyệt"><i class="bi bi-check2-square text-success"></i></button>
                        </form>
                    @endif
                    <form action="{{ route('admin.products.comments.destroy', $comment->id) }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xoá bình luận này?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger p-0 m-0 align-baseline" title="Xoá"><i class="bi bi-x-lg"></i></button>
                    </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 