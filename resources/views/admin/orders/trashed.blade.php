@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-danger">Thùng rác đơn hàng</h1>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (!isset($orders) || $orders->isEmpty())
            <div class="text-center py-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-trash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Thùng rác trống</h5>
                        <p class="text-muted">Không có đơn hàng nào trong thùng rác.</p>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại quản lý đơn hàng
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Danh sách đơn hàng dạng card -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($orders as $order)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-danger border-opacity-25 transition-all hover:shadow-lg">
                            <!-- Order Image -->
                            @if ($order['image'])
                                <img src="{{ asset('storage/' . $order['image']) }}" 
                                     class="card-img-top object-fit-cover" 
                                     style="height: 180px;" 
                                     alt="Ảnh đơn {{ $order['id'] }}">
                            @else
                                <div class="card-img-top text-center py-5 bg-light text-muted border-bottom"
                                     style="height: 180px; display: flex; align-items: center; justify-content: center;">
                                    <div>
                                        <i class="fas fa-image fa-2x mb-2"></i>
                                        <p class="mb-0 small">Chưa có ảnh</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Order Info -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title fw-bold text-danger mb-0">
                                        Đơn hàng #{{ $order['id'] }}
                                    </h6>
                                    <span class="badge bg-danger">{{ $order['status_vietnamese'] }}</span>
                                </div>
                                
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-user me-1"></i>{{ $order['user_name'] }}
                                </div>
                                
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-box me-1"></i>{{ Str::limit($order['product_names'], 40) }}
                                </div>
                                
                                <div class="row text-center border-top pt-2 mt-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Số lượng</small>
                                        <strong class="text-primary">{{ $order['total_quantity'] }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Tổng tiền</small>
                                        <strong class="text-success">{{ number_format($order['final_total'], 0, ',', '.') }}₫</strong>
                                    </div>
                                </div>
                                
                                <div class="mt-2 pt-2 border-top">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>Tạo: {{ $order['created_at'] }}
                                    </small><br>
                                    <small class="text-danger">
                                        <i class="fas fa-trash me-1"></i>Xóa: {{ $order['deleted_at'] }}
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="card-footer bg-transparent border-danger border-opacity-25">
                                <div class="d-flex gap-2 justify-content-center">
                                    <form action="{{ route('admin.orders.restore', $order['id']) }}" method="POST" class="flex-fill">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-success btn-sm w-100"
                                                onclick="return confirm('Bạn có chắc muốn phục hồi đơn hàng này?')">
                                            <i class="fas fa-undo me-1"></i>Phục hồi
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.orders.forceDelete', $order['id']) }}" method="POST" class="flex-fill">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm w-100"
                                                onclick="return confirm('CẢNH BÁO: Bạn có chắc muốn xóa vĩnh viễn đơn hàng này? Hành động này KHÔNG THỂ hoàn tác!')">
                                            <i class="fas fa-trash me-1"></i>Xóa vĩnh viễn
                                        </button>
                                    </form>
                                </div>
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
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .btn-success:hover {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
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

        .border-danger.border-opacity-25 {
            border-color: rgba(220, 53, 69, 0.25) !important;
        }

        @media (max-width: 576px) {
            .card-footer .d-flex {
                flex-direction: column;
            }
            
            .card-footer .btn {
                margin-bottom: 0.25rem;
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

        // Auto dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endpush
