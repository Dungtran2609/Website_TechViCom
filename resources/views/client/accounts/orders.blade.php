@extends('client.layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container py-4">
        <h1 class="fw-bold text-primary mb-4">
            <i class="fas fa-shopping-bag me-2"></i>Đơn hàng của bạn
        </h1>

        @if($orders->isEmpty())
            <div class="alert alert-info rounded shadow-sm">
                <i class="fas fa-info-circle me-2"></i>Bạn chưa có đơn hàng nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle shadow-sm rounded">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Mã đơn</th>
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Ngày đặt</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="fw-bold text-primary">
                                    <span class="text-dark">
                                        {{ $order->random_code ?? $order->code ?? ('DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)) }}
                                    </span>
                                </td>
                                <td>
                                    @foreach($order->orderItems as $item)
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-2" style="width: 50px; height: 50px;">
                                                @if($item->variant_id && $item->variant && $item->variant->image)
                                                    <img src="{{ asset($item->variant->image) }}" 
                                                        alt="{{ $item->name_product }}"
                                                        class="img-fluid rounded"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @elseif($item->image_product)
                                                    <img src="{{ asset('storage/' . $item->image_product) }}"
                                                        alt="{{ $item->name_product }}"
                                                        class="img-fluid rounded"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                        style="width: 100%; height: 100%;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-truncate" style="max-width: 200px;">
                                                    {{ $item->name_product }}
                                                </div>
                                                <div class="small text-muted">
                                                    SL: {{ $item->quantity }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @php
                                        $statusColor = [
                                            'pending' => 'secondary',
                                            'processing' => 'info',
                                            'shipped' => 'warning',
                                            'delivered' => 'success',
                                            'received' => 'success',
                                            'cancelled' => 'danger',
                                            'returned' => 'dark',
                                        ];
                                        $statusKey = $order->status ?? 'pending';
                                        $return = $order->returns()->latest()->first();
                                    @endphp

                                    <span class="badge bg-{{ $statusColor[$statusKey] ?? 'secondary' }} px-3 py-2">
                                        <i class="fas fa-circle me-1"></i>
                                        @if($order->status === 'delivered')
                                            Đã giao
                                        @elseif($order->status === 'received')
                                            Đã nhận hàng
                                        @else
                                            {{ $order->status_vietnamese ?? $order->status }}
                                        @endif
                                    </span>

                                    @if($return && in_array($order->status, ['returned', 'cancelled']))
                                        <div class="mt-1 small text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <span>Lý do khách: {{ $return->client_note ?? $return->reason }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold text-danger">
                                    {{ number_format($order->final_total, 0, ',', '.') }} VND
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('accounts.order-detail', $order->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>


        @endif
    </div>
@endsection

@push('styles')
    <style>
        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .badge {
            font-size: 1em;
            border-radius: 1rem;
        }

        .btn-outline-primary {
            transition: box-shadow 0.2s;
        }

        .btn-outline-primary:hover {
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
        }
    </style>
@endpush

