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
    <div class="btn-group mb-3" role="group">
        @foreach($statusList as $key => $label)
            @php
                $qs = array_filter(['status' => $key, 'q' => $search]);
                $href = url()->current() . (count($qs) ? ('?' . http_build_query($qs)) : '');
                $isActive = $currentStatus === $key;
            @endphp
            <input type="radio" class="btn-check" name="orderFilter" id="{{ $key }}" value="{{ $key }}" {{ $isActive ? 'checked' : '' }}>
            <label class="btn btn-outline-primary btn-sm" for="{{ $key }}">
                {{ $label }}
                @isset($counts[$key])
                    <span class="badge bg-light text-dark ms-1">{{ $counts[$key] }}</span>
                @endisset
                @if($key === 'all' && isset($counts['all']))
                    <span class="badge bg-light text-dark ms-1">{{ $counts['all'] }}</span>
                @endif
            </label>
        @endforeach
    </div>

    {{-- Tìm kiếm + lọc --}}
    <div class="row g-2 mb-4">
        <div class="col-lg-6">
            <div class="input-group">
                <input id="searchInput" value="{{ $search }}" type="text" class="form-control"
                       placeholder="Tìm theo mã đơn (VD: DH000123 hoặc ID) hoặc tên sản phẩm">
                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <div class="col-lg-3">
            <select id="statusFilter" class="form-select">
                <option value="">Tất cả trạng thái</option>
                @foreach($statusList as $key => $label)
                    @if($key !== 'all')
                        <option value="{{ $key }}" {{ $currentStatus === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 d-flex gap-2">
            <button class="btn btn-primary" type="button" id="filterBtn">
                <i class="fas fa-filter me-1"></i>Lọc
            </button>
            @if($search || ($currentStatus && $currentStatus !== 'all'))
                <a class="btn btn-outline-secondary" href="{{ url()->current() }}" id="clearFilterBtn">Xóa lọc</a>
            @endif
        </div>
    </div>

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
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'received' => 'success',
                        'cancelled' => 'danger',
                        'returned' => 'secondary',
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
                                @switch($order->status)
                                    @case('pending')    <i class="fas fa-clock me-1"></i>Chờ xử lý   @break
                                    @case('processing') <i class="fas fa-cog me-1"></i>Đang xử lý   @break
                                    @case('shipped')    <i class="fas fa-truck me-1"></i>Đang giao    @break
                                    @case('delivered')  <i class="fas fa-check-circle me-1"></i>Đã giao      @break
                                    @case('received')   <i class="fas fa-check-double me-1"></i>Đã nhận hàng @break
                                    @case('cancelled')  <i class="fas fa-times-circle me-1"></i>Đã hủy       @break
                                    @case('returned')   <i class="fas fa-undo me-1"></i>Trả hàng     @break
                                    @default <i class="fas fa-question me-1"></i>{{ $order->status }}
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
                            <a href="{{ route('client.orders.show', $order->id) }}"
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all filter controls
    const filterButtons = document.querySelectorAll('input[name="orderFilter"]');
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const statusFilter = document.getElementById('statusFilter');
    const filterBtn = document.getElementById('filterBtn');
    const clearFilterBtn = document.getElementById('clearFilterBtn');
    
    // Function to update URL and redirect
    function updateUrlAndRedirect() {
        const currentUrl = new URL(window.location);
        
        // Get current values
        const searchTerm = searchInput ? searchInput.value.trim() : '';
        const statusValue = statusFilter ? statusFilter.value : '';
        
        // Update URL parameters
        if (searchTerm) {
            currentUrl.searchParams.set('q', searchTerm);
        } else {
            currentUrl.searchParams.delete('q');
        }
        
        if (statusValue) {
            currentUrl.searchParams.set('status', statusValue);
        } else {
            currentUrl.searchParams.delete('status');
        }
        
                    window.location.href = currentUrl.toString();
    }
    
    // Filter buttons event - Server-side filtering
    filterButtons.forEach((button, index) => {
        button.addEventListener('change', function() {
            const filterValue = this.value;
            
            // Update status filter dropdown to match
            if (statusFilter) {
                statusFilter.value = filterValue === 'all' ? '' : filterValue;
            }
            
            updateUrlAndRedirect();
        });
    });
    
    // Search button event
    if (searchBtn) {
        searchBtn.addEventListener('click', updateUrlAndRedirect);
    }
    
    // Search input enter key
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                updateUrlAndRedirect();
            }
        });
    }
    
    // Status filter change
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const filterValue = this.value;
            
            // Update radio buttons to match
            filterButtons.forEach(button => {
                if (filterValue === '' && button.value === 'all') {
                    button.checked = true;
                } else if (button.value === filterValue) {
                    button.checked = true;
                } else {
                    button.checked = false;
                }
            });
            
            updateUrlAndRedirect();
        });
    }
    
    // Filter button event
    if (filterBtn) {
        filterBtn.addEventListener('click', updateUrlAndRedirect);
    }
    
    // Clear filter button
    if (clearFilterBtn) {
        clearFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = window.location.pathname;
        });
    }
    
    // Debug: Log current URL parameters
    // const urlParams = new URLSearchParams(window.location.search);
    // console.log('Current URL params:', Object.fromEntries(urlParams));
});
</script>
@endpush


