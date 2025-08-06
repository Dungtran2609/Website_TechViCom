@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-primary">Quản lý đơn hàng</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.orders.trashed') }}" class="btn btn-danger shadow-sm">
                    <i class="fas fa-trash me-2"></i> Thùng rác
                </a>
            </div>
        </div>

        <!-- Form tìm kiếm -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
            <div class="input-group shadow-sm rounded">
                <input type="text" name="search" class="form-control border-0 py-2"
                    placeholder="Tìm theo mã đơn hoặc tên khách hàng" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-search me-2"></i>Tìm kiếm
                </button>
            </div>
        </form>

        @if (!isset($orders) || $orders->isEmpty())
            <div class="alert alert-info rounded-3 shadow-sm">
                <i class="fas fa-info-circle me-2"></i>Không có đơn hàng nào.
            </div>
        @else
            <!-- Danh sách đơn hàng dạng card -->
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($orders as $order)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 transition-all hover:shadow-lg">
                            @if ($order['image'])
                                <!-- <img src="{{ asset($order['image']) }}" class="card-img-top object-fit-cover" style="height: 180px;" -->
                                    <!-- alt="Ảnh đơn {{ $order['id'] }}"> -->
                                    <img src="{{ asset($order['image']) }}" class="card-img-top object-fit-cover" style="height: 180px;"
                                        alt="Ảnh đơn {{ $order['id'] }}">
                            @else
                                <div class="card-img-top text-center py-5 bg-light text-muted"
                                    style="height: 180px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image fa-2x me-2"></i>Chưa có ảnh
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-primary">Mã đơn: {{ $order['id'] }}</h5>
                                <p class="card-text">Khách: {{ $order['user_name'] }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 d-flex justify-content-between">
                                <a href="{{ route('admin.orders.show', $order['id']) }}" class="btn btn-warning btn-sm px-3">
                                    <i class="fas fa-edit me-2"></i>Xem
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Phân trang -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $pagination->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .transition-all {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .pagination .page-link {
            border-radius: 0.25rem;
            margin: 0 2px;
            color: #007bff;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
        }

        @media (max-width: 576px) {
            .input-group {
                flex-direction: column;
            }

            .input-group .btn {
                width: 100%;
                margin-top: 0.5rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Thêm hiệu ứng hover cho card
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-3px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>
@endpush
