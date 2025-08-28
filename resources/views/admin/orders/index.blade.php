@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý đơn hàng</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.returns') }}" class="btn btn-outline-warning">
            <i class="fas fa-exchange-alt me-1"></i> Hủy/Đổi trả
        </a>
        {{-- <a href="{{ route('admin.orders.trashed') }}" class="btn btn-outline-danger">
            <i class="fas fa-trash-alt me-1"></i> Thùng rác
        </a> --}}
    </div>
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

<form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
    <div class="row g-3">
        <div class="col-md-3">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm theo mã đơn, tên khách hàng...">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                @foreach ($statusMap as $key => $label)
                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="created_from" value="{{ request('created_from') }}" class="form-control" placeholder="Từ ngày">
        </div>
        <div class="col-md-2">
            <input type="date" name="created_to" value="{{ request('created_to') }}" class="form-control" placeholder="Đến ngày">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="col-md-1">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
    @if(request('search') || request('status') || request('created_from') || request('created_to'))
        <div class="mt-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
    @endif
</form>

@if (!isset($orders) || $orders->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="text-muted">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <h4>Không có đơn hàng nào</h4>
                <p>Hiện tại chưa có đơn hàng nào trong hệ thống.</p>
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
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th width="120px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @php $orderModel = \App\Models\Order::find($order['id']); @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $order['id'] }}</span>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0 text-dark fw-medium">{{ $order['user_name'] }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark fw-medium">{{ number_format($orderModel?->final_total ?? 0, 0) }} VND</span>
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
                                        $orderStatus = $orderModel?->status ?? '';
                                        $badge = $statusColors[$orderStatus] ?? 'light';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ $statusMap[$orderStatus] ?? $orderStatus }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $orderModel?->created_at ? $orderModel->created_at->format('d/m/Y H:i') : '' }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.orders.show', $order['id']) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order['id']) }}" class="btn btn-light btn-sm" title="Sửa đơn hàng">
                                            <iconify-icon icon="solar:pen-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $pagination->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
@endsection

