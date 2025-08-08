@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fw-bold mb-1">
                                    <i class="fas fa-shopping-cart me-2"></i>Quản lý đơn hàng
                                </h1>
                                <p class="mb-0 opacity-75">Theo dõi và quản lý tất cả đơn hàng</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.orders.trashed') }}" class="btn btn-light btn-sm shadow-sm">
                                    <i class="fas fa-trash me-2"></i>Thùng rác
                                </a>
                                <a href="{{ route('admin.orders.returns') }}" class="btn btn-warning btn-sm shadow-sm">
                                    <i class="fas fa-undo me-2"></i>Yêu cầu trả hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h6 class="text-muted">Chờ xử lý</h6>
                        <h4 class="text-primary fw-bold">{{ $orders->where('status', 'pending')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-cog fa-2x"></i>
                        </div>
                        <h6 class="text-muted">Đang xử lý</h6>
                        <h4 class="text-info fw-bold">{{ $orders->where('status', 'processing')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                        <h6 class="text-muted">Đang giao</h6>
                        <h4 class="text-warning fw-bold">{{ $orders->where('status', 'shipped')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <h6 class="text-muted">Hoàn thành</h6>
                        <h4 class="text-success fw-bold">{{ $orders->where('status', 'delivered')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-0" 
                                           placeholder="Tìm theo mã đơn hoặc tên khách hàng..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select border-0 bg-light">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Tìm kiếm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (!isset($orders) || $orders->isEmpty())
            <div class="text-center py-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có đơn hàng nào</h5>
                        <p class="text-muted">Danh sách đơn hàng sẽ hiển thị tại đây khi có khách hàng đặt hàng.</p>
                    </div>
                </div>
            </div>
        @else
            <!-- Orders Grid -->
            <div class="row">
                @foreach ($orders as $order)
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100 order-card">
                            <!-- Order Image -->
                            <div class="position-relative">
                                @if ($order['image'])
                                    <img src="{{ asset($order['image']) }}" 
                                         class="card-img-top" 
                                         style="height: 200px; object-fit: cover;" 
                                         alt="Ảnh đơn {{ $order['id'] }}">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                         style="height: 200px;">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p class="mb-0">Chưa có ảnh</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['class' => 'bg-warning', 'text' => 'Chờ xử lý', 'icon' => 'clock'],
                                            'processing' => ['class' => 'bg-info', 'text' => 'Đang xử lý', 'icon' => 'cog'],
                                            'shipped' => ['class' => 'bg-primary', 'text' => 'Đang giao', 'icon' => 'truck'],
                                            'delivered' => ['class' => 'bg-success', 'text' => 'Hoàn thành', 'icon' => 'check-circle'],
                                            'cancelled' => ['class' => 'bg-danger', 'text' => 'Đã hủy', 'icon' => 'times-circle'],
                                            'returned' => ['class' => 'bg-secondary', 'text' => 'Đã trả', 'icon' => 'undo']
                                        ];
                                        $config = $statusConfig[$order['status']] ?? ['class' => 'bg-secondary', 'text' => $order['status'], 'icon' => 'question'];
                                    @endphp
                                    <span class="badge {{ $config['class'] }} shadow-sm">
                                        <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order Info -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="fw-bold text-primary mb-0">
                                        Đơn hàng #{{ $order['id'] }}
                                    </h6>
                                    <small class="text-muted">{{ $order['created_at'] }}</small>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-user me-2 text-muted"></i>
                                        <strong class="me-1">Khách hàng:</strong>
                                        <span class="text-truncate">{{ $order['user_name'] }}</span>
                                    </div>
                                    
                                    @if(isset($order['product_names']))
                                        <div class="d-flex align-items-start mb-2">
                                            <i class="fas fa-box me-2 text-muted mt-1"></i>
                                            <div class="flex-grow-1">
                                                <strong class="d-block">Sản phẩm:</strong>
                                                <small class="text-muted">{{ Str::limit($order['product_names'], 60) }}</small>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-credit-card me-2 text-muted"></i>
                                        <strong class="me-1">Thanh toán:</strong>
                                        <span class="text-capitalize">{{ $order['payment_method_vietnamese'] ?? $order['payment_method'] }}</span>
                                    </div>
                                </div>

                                <!-- Order Stats -->
                                <div class="row text-center bg-light rounded-3 py-2 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Số lượng</small>
                                        <strong class="text-primary">{{ $order['total_quantity'] ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Tổng tiền</small>
                                        <strong class="text-success">{{ number_format($order['final_total'], 0, ',', '.') }}₫</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="card-footer bg-transparent border-0">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.orders.show', $order['id']) }}" 
                                       class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="fas fa-eye me-1"></i>Xem
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order['id']) }}" 
                                       class="btn btn-outline-warning btn-sm flex-fill">
                                        <i class="fas fa-edit me-1"></i>Sửa
                                    </a>
                                    @if(in_array($order['status'], ['pending']))
                                        <form action="{{ route('admin.orders.updateOrders', $order['id']) }}" method="POST" class="flex-fill">
                                            @csrf
                                            <input type="hidden" name="status" value="processing">
                                            <button type="submit" class="btn btn-success btn-sm w-100"
                                                    onclick="return confirm('Xác nhận đơn hàng này?')">
                                                <i class="fas fa-check me-1"></i>Xác nhận
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $pagination->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .order-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .card-img-top {
            border-radius: 15px 15px 0 0;
        }

        .btn-outline-primary:hover {
            transform: scale(1.05);
        }

        .btn-outline-warning:hover {
            transform: scale(1.05);
        }

        .btn-success:hover {
            transform: scale(1.05);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .pagination .page-link {
            border-radius: 10px;
            margin: 0 3px;
            color: #667eea;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .pagination .page-link:hover {
            background-color: #f8f9fa;
            border-color: #667eea;
            transform: translateY(-1px);
        }

        .text-truncate {
            max-width: 150px;
        }

        /* Statistics Cards Animation */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        /* Status Badge Colors */
        .bg-warning { background-color: #ffc107 !important; }
        .bg-info { background-color: #17a2b8 !important; }
        .bg-primary { background-color: #007bff !important; }
        .bg-success { background-color: #28a745 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        .bg-secondary { background-color: #6c757d !important; }

        @media (max-width: 768px) {
            .order-card {
                margin-bottom: 1rem;
            }
            
            .card-footer .d-flex {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .text-truncate {
                max-width: 200px;
            }
        }

        /* Loading Animation */
        .order-card {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #764ba2;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate cards on load
            const cards = document.querySelectorAll('.order-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Add hover effects to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Add smooth scrolling to pagination
            const paginationLinks = document.querySelectorAll('.pagination .page-link');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    if (href && href !== '#') {
                        window.location.href = href;
                    }
                });
            });

            // Add loading state to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';
                        submitBtn.disabled = true;
                        
                        // Reset after 3 seconds if still on page
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 3000);
                    }
                });
            });

            // Add tooltip for truncated text
            const truncatedElements = document.querySelectorAll('.text-truncate');
            truncatedElements.forEach(element => {
                if (element.scrollWidth > element.clientWidth) {
                    element.setAttribute('title', element.textContent);
                }
            });

            // Add real-time search (optional)
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        // Could implement AJAX search here
                        console.log('Searching for:', this.value);
                    }, 500);
                });
            }

            // Add status filter change handler
            const statusSelect = document.querySelector('select[name="status"]');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    // Auto-submit form when status changes
                    this.closest('form').submit();
                });
            }
        });

        // Add notification for successful actions
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    </script>
@endpush
