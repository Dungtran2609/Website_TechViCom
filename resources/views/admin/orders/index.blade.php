@extends('admin.layouts.app')

@section('content')
    <div class="container py-5">
        <header class="mb-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h1 class="fw-bolder text-dark mb-3 mb-md-0" style="color: #343a40;">Quản lý đơn hàng</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.trashed') }}" class="btn btn-outline-danger d-flex align-items-center gap-2">
                        <i class="fas fa-trash-alt"></i>
                        Thùng rác
                    </a>
                </div>
            </div>
        </header>

        <!-- Form lọc nâng cao: chỉ trạng thái và ngày -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach ($statusMap as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Từ ngày</label>
                    <input type="date" name="created_from" class="form-control" value="{{ request('created_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Đến ngày</label>
                    <input type="date" name="created_to" class="form-control" value="{{ request('created_to') }}">
                </div>
                <div class="col-md-2 mt-2 mt-md-0">
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-filter me-1"></i> Lọc</button>
                </div>
                <div class="col-md-1 mt-2 mt-md-0">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-times me-1"></i></a>
                </div>
            </div>
        </form>
        <!-- End Form lọc nâng cao -->

        <!-- Form tìm kiếm -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
            <div class="input-group input-group-lg border rounded-pill shadow-sm p-2 bg-white">
                <span class="input-group-text bg-transparent border-0 text-primary pe-3">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 bg-transparent"
                    placeholder="Tìm theo mã đơn hoặc tên khách hàng..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary rounded-pill px-4 ms-2">Tìm kiếm</button>
            </div>
        </form>
        <!-- End Form tìm kiếm -->

        @if (!isset($orders) || $orders->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h4 class="fw-bold">Không có đơn hàng nào</h4>
                <p class="text-muted">Hiện tại chưa có đơn hàng nào trong hệ thống.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle bg-white rounded-4 overflow-hidden shadow-sm" style="min-width: 900px;">
                    <thead class="table-light sticky-top" style="z-index:1;">
                        <tr>
                            <th scope="col" class="text-center">Ảnh</th>
                            <th scope="col">Mã đơn</th>
                            <th scope="col">Tên khách hàng</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @php $orderModel = \App\Models\Order::find($order['id']); @endphp
                            <tr>
                                <td class="text-center">
                                    @if ($order['image'])
                                        <img src="{{ $order['image'] }}" alt="Ảnh sản phẩm" style="width: 48px; height: 48px; object-fit: cover; border-radius: 50%; border:2px solid #eee;">
                                    @else
                                        <span class="text-muted"><i class="fas fa-image fa-2x"></i></span>
                                    @endif
                                </td>
                                <td class="fw-bold text-primary">#{{ $order['id'] }}</td>
                                <td class="fw-bold">{{ $order['user_name'] }}</td>
                                <td class="fw-bold text-danger" style="font-size:1.1em;">{{ number_format($orderModel?->final_total ?? 0, 0, ',', '.') }} đ</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'secondary',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'received' => 'success',
                                            'cancelled' => 'danger',
                                            'returned' => 'warning',
                                        ];
                                        $orderStatus = $orderModel?->status ?? '';
                                        $badge = $statusColors[$orderStatus] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $badge }} px-3 py-2" style="font-size:0.95em;">{{ $statusMap[$orderStatus] ?? $orderStatus }}</span>
                                </td>
                                <td>{{ $orderModel?->created_at ? $orderModel->created_at->format('d/m/Y H:i') : '' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.orders.show', $order['id']) }}" class="btn btn-outline-warning btn-sm fw-bold px-3 shadow-sm">
                                        <i class="fas fa-eye me-1"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav class="mt-4 d-flex justify-content-center" aria-label="Page navigation">
                {{ $pagination->links('pagination::bootstrap-5') }}
            </nav>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Bảng đơn hàng */
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .badge.bg-secondary { background-color: #6c757d; }
        .badge.bg-info { background-color: #0dcaf0; }
        .badge.bg-primary { background-color: #0d6efd; }
        .badge.bg-success { background-color: #198754; }
        .badge.bg-danger { background-color: #dc3545; }
        .badge.bg-warning { background-color: #ffc107; color: #000; }
        .table-hover tbody tr:hover { background-color: #f1f3f5; }
        .btn-outline-warning { border-color: #ffc107; color: #ffc107; }
        .btn-outline-warning:hover { background: #ffc107; color: #000; }
        .fw-bold.text-danger { color: #dc3545 !important; }
        .fw-bold.text-primary { color: #0d6efd !important; }
        @media (max-width: 768px) {
            .table-responsive { font-size: 0.95em; }
            .table th, .table td { padding: 0.5rem; }
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }

        .input-group .form-control {
            border-right: 0;
        }

        .input-group .input-group-text {
            border-left: 0;
        }

        /* Tùy chỉnh phân trang */
        .pagination .page-item .page-link {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            border: none;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4);
        }

        .pagination .page-item .page-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        .pagination .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: transparent;
        }
    </style>
@endpush

@push('scripts')
    {{-- Không cần thêm Javascript cho hiệu ứng hover vì đã sử dụng CSS pseudo-class :hover --}}
@endpush