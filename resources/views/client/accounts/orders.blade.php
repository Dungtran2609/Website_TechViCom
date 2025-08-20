@extends('client.layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $statusList = [
        'all'        => 'Tất cả',
        'pending'    => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped'    => 'Đang giao',
        'delivered'  => 'Đã giao',
        'received'   => 'Đã nhận',
        'cancelled'  => 'Đã hủy',
        'returned'   => 'Trả hàng',
    ];
    $currentStatus = request('status', 'all');
    $search = request('q', '');
@endphp

<div class="container py-4">
    <h1 class="fw-bold text-primary mb-3">
        <i class="fas fa-shopping-bag me-2"></i>Đơn hàng của bạn
    </h1>

    {{-- Tabs trạng thái --}}
    <ul class="nav nav-pills gap-2 flex-wrap mb-3">
        @foreach($statusList as $key => $label)
            @php
                $qs = array_filter(['status' => $key, 'q' => $search]);
                $href = url()->current() . (count($qs) ? ('?' . http_build_query($qs)) : '');
            @endphp
            <li class="nav-item">
                <a href="{{ $href }}" class="nav-link {{ $currentStatus === $key ? 'active' : 'btn-outline-primary' }}">
                    {{ $label }}
                    @isset($counts[$key])
                        <span class="badge bg-light text-dark ms-1">{{ $counts[$key] }}</span>
                    @endisset
                    @if($key === 'all' && isset($counts['all']))
                        <span class="badge bg-light text-dark ms-1">{{ $counts['all'] }}</span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>

    {{-- Tìm kiếm + lọc --}}
    <form method="GET" action="{{ url()->current() }}" class="row g-2 mb-4">
        <div class="col-lg-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input name="q" value="{{ $search }}" type="text" class="form-control"
                       placeholder="Tìm theo mã đơn (VD: DH000123 hoặc ID) hoặc tên sản phẩm">
            </div>
        </div>

        <div class="col-lg-3">
            <select name="status" class="form-select">
                @foreach($statusList as $key => $label)
                    <option value="{{ $key }}" {{ $currentStatus === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-filter me-1"></i>Lọc
            </button>
            @if($search || ($currentStatus && $currentStatus !== 'all'))
                <a class="btn btn-outline-secondary" href="{{ url()->current() }}">Xóa lọc</a>
            @endif
        </div>
    </form>

    @if($orders->count() === 0)
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
                @endphp

                {{-- KHÔNG sortBy ở Blade: Controller đã sắp xếp & lọc --}}
                @foreach($orders as $order)
                    @php $return = $order->returns()->latest()->first(); @endphp
                    <tr>
                        <td class="fw-bold text-primary">
                            <span class="text-dark">
                                {{ $order->random_code ?? $order->code ?? ('DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)) }}
                            </span>
                        </td>

                        <td>
                            @foreach($order->orderItems as $item)
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-2" style="width:50px;height:50px;">
                                        @if($item->variant_id && $item->variant && $item->variant->image)
                                            <img src="{{ asset('storage/' . $item->variant->image) }}"
                                                 alt="{{ $item->name_product }}" class="img-fluid rounded"
                                                 style="width:100%;height:100%;object-fit:cover;">
                                        @elseif($item->image_product)
                                            <img src="{{ asset('storage/' . $item->image_product) }}"
                                                 alt="{{ $item->name_product }}" class="img-fluid rounded"
                                                 style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width:100%;height:100%;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-truncate" style="max-width:220px;">
                                            {{ $item->name_product }}
                                        </div>
                                        <div class="small text-muted">SL: {{ $item->quantity }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </td>

                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>

                        <td>
                            @php $key = $order->status ?? 'pending'; @endphp
                            <span class="badge bg-{{ $statusColor[$key] ?? 'secondary' }} px-3 py-2">
                                <i class="fas fa-circle me-1"></i>
                                @switch($order->status)
                                    @case('pending')    Chờ xử lý   @break
                                    @case('processing') Đang xử lý   @break
                                    @case('shipped')    Đang giao    @break
                                    @case('delivered')  Đã giao      @break
                                    @case('received')   Đã nhận hàng @break
                                    @case('cancelled')  Đã hủy       @break
                                    @case('returned')   Trả hàng     @break
                                    @default {{ $order->status }}
                                @endswitch
                            </span>

                            @if($return && in_array($order->status, ['returned','cancelled']))
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

        {{-- Giữ tham số khi phân trang: controller đã dùng withQueryString() --}}
        <div class="mt-3 d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.table th,.table td{vertical-align:middle!important;}
.badge{font-size:1em;border-radius:1rem;}
.btn-outline-primary{transition:box-shadow .2s;}
.btn-outline-primary:hover{box-shadow:0 2px 8px rgba(0,123,255,.15);}
.nav-pills .nav-link{border-radius:999px;}
</style>
@endpush


