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

        <!-- Form tìm kiếm được cải tiến -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-5">
            <div class="input-group input-group-lg border rounded-pill shadow-sm p-2 bg-white">
                <span class="input-group-text bg-transparent border-0 text-primary pe-3">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 bg-transparent"
                    placeholder="Tìm theo mã đơn hoặc tên khách hàng..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary rounded-pill px-4 ms-2">Tìm kiếm</button>
            </div>
        </form>

        @if (!isset($orders) || $orders->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h4 class="fw-bold">Không có đơn hàng nào</h4>
                <p class="text-muted">Hiện tại chưa có đơn hàng nào trong hệ thống.</p>
            </div>
        @else
            <!-- Danh sách đơn hàng dạng card hiện đại -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($orders as $order)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 card-hover">
                            @if ($order['image'])
                                <img src="{{ asset($order['image']) }}" class="card-img-top rounded-top-4 object-fit-cover"
                                    style="height: 200px;" alt="Ảnh đơn {{ $order['id'] }}">
                            @else
                                <div class="card-img-top rounded-top-4 bg-light d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p>Chưa có ảnh</p>
                                    </div>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold text-dark mb-1">Mã đơn: {{ $order['id'] }}</h5>
                                <p class="card-text text-muted">Khách hàng: <span class="fw-medium">{{ $order['user_name'] }}</span></p>
                            </div>
                            <div class="card-footer bg-white border-0 p-4 pt-0">
                                <a href="{{ route('admin.orders.show', $order['id']) }}" class="btn btn-warning w-100 fw-bold">
                                    <i class="fas fa-eye me-2"></i>Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Phân trang được tùy chỉnh -->
            <nav class="mt-5 d-flex justify-content-center" aria-label="Page navigation">
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

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        
        .btn-warning {
             background-color: #ffc107;
             border-color: #ffc107;
             color: #000;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
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