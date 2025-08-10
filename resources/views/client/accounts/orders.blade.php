@extends('client.layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-2xl fw-bold text-primary">Đơn hàng của bạn</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($orders->isEmpty())
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    @else
    <div class="row g-4">
        @foreach($orders as $order)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 border-0 order-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-primary">#{{ $order->id }}</span>
                        <span class="badge 
                            @if($order->status === 'pending') bg-warning text-dark
                            @elseif($order->status === 'processing') bg-info text-dark
                            @elseif($order->status === 'shipped') bg-primary
                            @elseif($order->status === 'delivered') bg-success
                            @elseif($order->status === 'cancelled') bg-danger
                            @elseif($order->status === 'returned') bg-secondary
                            @else bg-light text-dark @endif">
                            {{ $order->status_vietnamese ?? ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="mb-2 small text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</div>
                    <div class="mb-2">
                        <span class="fw-semibold">Tổng tiền:</span>
                        <span class="text-danger fw-bold">{{ number_format($order->final_total, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-semibold">Thanh toán:</span>
                        <span class="badge 
                            @if($order->payment_status === 'paid') bg-success
                            @elseif($order->payment_status === 'unpaid') bg-warning text-dark
                            @elseif($order->payment_status === 'failed') bg-danger
                            @elseif($order->payment_status === 'refunded') bg-secondary
                            @else bg-light text-dark @endif">
                            {{ $order->payment_status_vietnamese ?? ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('accounts.order-detail', $order->id) }}" class="btn btn-outline-primary btn-sm flex-fill">Xem chi tiết</a>
                        @if($order->status === 'pending')
                        <form action="{{ route('accounts.cancel-order', $order->id) }}" method="POST" class="d-inline-block flex-fill" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Hủy đơn</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@push('styles')
<style>
.order-card {
    transition: box-shadow 0.2s, transform 0.2s;
    border-radius: 1rem;
}
.order-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    transform: translateY(-4px) scale(1.02);
}
</style>
@endpush
@endsection
