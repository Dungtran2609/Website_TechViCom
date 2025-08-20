@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thùng rác đơn hàng</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
</div>

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

@if($orders->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="text-muted">
                <i class="fas fa-trash fa-3x mb-3"></i>
                <h4>Thùng rác trống</h4>
                <p>Không có đơn hàng nào trong thùng rác.</p>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>STT</th>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Tổng số lượng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Ngày xóa</th>
                            <th width="200px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $order['id'] }}</span>
                                </td>
                                <td>
                                    @if ($order['image'])
                                        <img src="{{ asset('storage/' . $order['image']) }}" alt="Ảnh sản phẩm" 
                                             class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <span class="text-muted small">Không có ảnh</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0 text-dark fw-medium">{{ $order['user_name'] }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 200px;">
                                        <span class="text-muted">{{ Str::limit($order['product_names'], 50) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark fw-medium">{{ $order['total_quantity'] }}</span>
                                </td>
                                <td>
                                    <span class="text-dark fw-medium">{{ number_format($order['final_total'], 0) }} VND</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'received' => 'success',
                                            'cancelled' => 'danger',
                                            'returned' => 'secondary',
                                        ];
                                        $badge = $statusColors[$order['status']] ?? 'light';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ $order['status_vietnamese'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $order['created_at'] }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $order['deleted_at'] }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('admin.orders.restore', $order['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn phục hồi đơn hàng này?')" title="Phục hồi">
                                                <iconify-icon icon="solar:restart-bold" class="align-middle fs-18"></iconify-icon>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.orders.forceDelete', $order['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn đơn hàng này?')" title="Xóa vĩnh viễn">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
@endsection