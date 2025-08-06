@extends('admin.layouts.app')

@section('content')

<div class="container mt-4">
    <h2 class="mb-4 text-primary fw-bold">Quản lý bình luận & đánh giá sản phẩm</h2>
    @if(request('product_id'))
        <a href="{{ route('admin.products.comments.products-with-comments') }}" class="btn btn-outline-secondary mb-3 fw-bold px-4" style="border-radius: 20px;">
            ← Quay lại danh sách sản phẩm
        </a>
    @endif
    <form method="GET" class="row g-2 mb-3">
        <div class="col">
            <input type="text" name="product_name" class="form-control shadow-sm" placeholder="Tên sản phẩm" value="{{ request('product_name') }}">
        </div>
        <div class="col">
            <input type="text" name="user_name" class="form-control shadow-sm" placeholder="Người dùng" value="{{ request('user_name') }}">
        </div>
        <div class="col">
            <select name="status" class="form-select shadow-sm">
                <option value="">-- Trạng thái --</option>
                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Đã duyệt</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Chờ duyệt</option>
                <option value="deleted" {{ request('status')=='deleted'?'selected':'' }}>Đã xoá</option>
            </select>
        </div>
        <div class="col">
            <select name="rating" class="form-select shadow-sm">
                <option value="">-- Số sao --</option>
                @for($i=1;$i<=5;$i++)
                    <option value="{{$i}}" {{ request('rating')==$i?'selected':'' }}>{{$i}} sao</option>
                @endfor
            </select>
        </div>
        <div class="col">
            <input type="date" name="date_from" class="form-control shadow-sm" value="{{ request('date_from') }}">
        </div>
        <div class="col">
            <input type="date" name="date_to" class="form-control shadow-sm" value="{{ request('date_to') }}">
        </div>
        <div class="col d-flex align-items-center gap-2">
            <button type="submit" class="btn btn-primary fw-bold px-3" title="Tìm kiếm" style="border-radius: 20px;">
                <i class="bi bi-search"></i>
            </button>
            <a href="{{ route('admin.products.comments.index') }}" class="btn btn-outline-secondary fw-bold px-3" title="Reset" style="border-radius: 20px;">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
        </div>
    </form>
    <div class="table-responsive" style="overflow-x:auto;">
        <table class="table table-bordered align-middle text-center shadow rounded" style="background: #fff; min-width: 900px;">
            <thead style="background: #e9ecef;">
                <tr>
                    <th style="color: #007bff; font-weight: 600; min-width: 120px;">Sản phẩm</th>
                    <th style="color: #007bff; font-weight: 600; min-width: 110px;">Người dùng</th>
                    <th style="color: #007bff; font-weight: 600; min-width: 130px;">Ngày - Giờ gửi</th>
                    <th style="color: #007bff; font-weight: 600; min-width: 110px;">Đánh giá</th>
                    <th style="color: #007bff; font-weight: 600; min-width: 180px;">Bình luận</th>
                    <th style="color: #007bff; font-weight: 600; min-width: 150px;">Phản hồi</th>
                    <th style="color: #007bff; font-weight: 600; min-width: 110px;">Trạng thái</th>
                    <th style="color: #007bff; font-weight: 600; min-width: 110px;">Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comments as $comment)
                <tr style="transition: background 0.2s;">
                    <td style="background: #f8f9fa; color: #212529; border-radius: 8px 0 0 8px; font-size: 1em;">{{ $comment->product->name ?? '-' }}</td>
                    <td style="background: #f8f9fa; color: #212529; font-size: 1em;">{{ $comment->user->name ?? '-' }}</td>
                    <td style="background: #f8f9fa; color: #212529; font-size: 0.98em;">{{ $comment->created_at->format('d/m/Y - H:i') }}</td>
                    <td style="background: #f8f9fa; font-size: 1.1em;">
                        @if($comment->rating)
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $comment->rating)
                                    <span style="color: #ffc107;">★</span>
                                @else
                                    <span style="color: #dee2e6;">★</span>
                                @endif
                            @endfor
                        @else
                            <span style="color: #6c757d; font-size: 0.95em;">(Không đánh giá)</span>
                        @endif
                    </td>
                    <td class="text-start" style="background: #f8f9fa; color: #212529; font-size: 0.98em;">{{ $comment->content }}</td>
                    <td class="text-start" style="background: #f8f9fa; color: #212529; font-size: 0.98em;">
                        @if($comment->replies && $comment->replies->count())
                            <span style="color: #007bff;">{{ $comment->replies->first()->content }}</span>
                        @else
                            <span style="color: #adb5bd;">Chưa phản hồi</span>
                        @endif
                    </td>
                    <td style="background: #f8f9fa; font-size: 0.98em;">
                        @if($comment->status === 'approved')
                            <span class="badge rounded-pill" style="background: #28a745; color: #fff; font-size: 0.98em;">Đã duyệt</span>
                        @elseif($comment->status === 'pending')
                            <span class="badge rounded-pill" style="background: #ffc107; color: #212529; font-size: 0.98em;">Chờ duyệt</span>
                        @else
                            <span class="badge rounded-pill" style="background: #dc3545; color: #fff; font-size: 0.98em;">Đã xoá</span>
                        @endif
                    </td>
                    <td style="background: #f8f9fa; border-radius: 0 8px 8px 0; font-size: 1em;">
                        <div class="d-flex justify-content-center align-items-center gap-2" style="height: 100%;">
                        <a href="{{ route('admin.products.comments.show', $comment->id) }}" title="Xem"><i class="bi bi-eye" style="color: #007bff; font-size: 1.1em;"></i></a>
                        @if($comment->status === 'pending')
                            <form action="{{ route('admin.products.comments.approve', $comment->id) }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Duyệt"><i class="bi bi-check2-square" style="color: #28a745; font-size: 1.1em;"></i></button>
                            </form>
                        @endif
                        <form action="{{ route('admin.products.comments.destroy', $comment->id) }}{{ request('product_id') ? '?product_id='.request('product_id') : '' }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xoá bình luận này?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Xoá"><i class="bi bi-x-lg" style="color: #dc3545; font-size: 1.1em;"></i></button>
                        </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@push('styles')
<style>
    .table tbody tr:hover {
        background: #e3f0ff !important;
        transition: background 0.2s;
    }
    @media (max-width: 900px) {
        .table-responsive { overflow-x: auto; }
        .table th, .table td {
            font-size: 0.95em;
            padding: 0.5rem;
            min-width: 90px;
        }
        .btn, .badge { font-size: 0.95em; }
    }
</style>
@endpush
@endsection