@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="display-6 fw-bold mb-2">
                                <i class="fas fa-tachometer-alt me-3"></i>Dashboard Quản Trị
                            </h1>
                            <p class="lead mb-0 opacity-75">
                                Chào mừng {{ Auth::user()->name ?? 'Admin' }} - {{ Carbon\Carbon::now()->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('home') }}" class="btn btn-light btn-lg shadow-sm">
                                <i class="fas fa-home me-2"></i>Về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted fw-normal mb-1">Tổng đơn hàng</h6>
                            <h3 class="fw-bold text-primary mb-0">{{ number_format($totalOrders) }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                +{{ $orderStats['pending'] }} chờ xử lý
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-dollar-sign fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted fw-normal mb-1">Doanh thu</h6>
                            <h3 class="fw-bold text-success mb-0">{{ number_format($totalRevenue) }}₫</h3>
                            <small class="text-success">
                                <i class="fas fa-chart-line me-1"></i>
                                Đã thanh toán
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-box fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted fw-normal mb-1">Sản phẩm</h6>
                            <h3 class="fw-bold text-warning mb-0">{{ number_format($totalProducts) }}</h3>
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $stats['low_stock_products'] }} sắp hết hàng
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted fw-normal mb-1">Khách hàng</h6>
                            <h3 class="fw-bold text-info mb-0">{{ number_format($totalUsers) }}</h3>
                            <small class="text-info">
                                <i class="fas fa-user-plus me-1"></i>
                                Đã đăng ký
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        Doanh thu 7 ngày gần đây
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Pie Chart -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-pie me-2 text-success"></i>
                        Trạng thái đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper" style="height: 250px;">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="fas fa-tags fa-2x text-primary mb-3"></i>
                    <h4 class="fw-bold text-primary">{{ $stats['categories'] }}</h4>
                    <p class="text-muted mb-0">Danh mục</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="fas fa-certificate fa-2x text-warning mb-3"></i>
                    <h4 class="fw-bold text-warning">{{ $stats['brands'] }}</h4>
                    <p class="text-muted mb-0">Thương hiệu</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="fas fa-newspaper fa-2x text-info mb-3"></i>
                    <h4 class="fw-bold text-info">{{ $stats['news'] }}</h4>
                    <p class="text-muted mb-0">Tin tức</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="fas fa-ticket-alt fa-2x text-success mb-3"></i>
                    <h4 class="fw-bold text-success">{{ $stats['active_coupons'] }}</h4>
                    <p class="text-muted mb-0">Coupon đang hoạt động</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            Đơn hàng gần đây
                        </h5>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm">
                            Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-4">Mã ĐH</th>
                                    <th class="border-0">Khách hàng</th>
                                    <th class="border-0">Tổng tiền</th>
                                    <th class="border-0">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td class="px-4">
                                        <strong class="text-primary">#{{ $order['id'] }}</strong>
                                        <br><small class="text-muted">{{ $order['created_at'] }}</small>
                                    </td>
                                    <td>{{ $order['customer_name'] }}</td>
                                    <td>
                                        <strong class="text-success">{{ number_format($order['final_total']) }}₫</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'pending' => ['class' => 'warning', 'text' => 'Chờ xử lý'],
                                                'processing' => ['class' => 'info', 'text' => 'Đang xử lý'],
                                                'shipped' => ['class' => 'primary', 'text' => 'Đang giao'],
                                                'delivered' => ['class' => 'success', 'text' => 'Hoàn thành'],
                                                'cancelled' => ['class' => 'danger', 'text' => 'Đã hủy'],
                                                'returned' => ['class' => 'secondary', 'text' => 'Đã trả']
                                            ];
                                            $config = $statusConfig[$order['status']] ?? ['class' => 'secondary', 'text' => $order['status']];
                                        @endphp
                                        <span class="badge bg-{{ $config['class'] }}">{{ $config['text'] }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-star me-2 text-warning"></i>
                            Sản phẩm bán chạy
                        </h5>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-warning btn-sm">
                            Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-4">Sản phẩm</th>
                                    <th class="border-0">Đã bán</th>
                                    <th class="border-0">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                <tr>
                                    <td class="px-4">
                                        <strong>{{ Str::limit($product->name, 30) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $product->total_sold }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($product->total_revenue) }}₫</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slow Moving Products Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                            Sản phẩm bán chậm
                        </h5>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger btn-sm">
                            Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-4">Sản phẩm</th>
                                    <th class="border-0">Tồn kho</th>
                                    <th class="border-0">Đã bán</th>
                                    <th class="border-0">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($slowMovingProducts as $product)
                                <tr>
                                    <td class="px-4">
                                        <strong>{{ Str::limit($product->name, 40) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $product->total_stock }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $product->total_sold }}</span>
                                    </td>
                                    <td>
                                        @if($product->total_sold == 0)
                                            <span class="badge bg-danger">Chưa bán được</span>
                                        @elseif($product->total_sold < 5)
                                            <span class="badge bg-warning">Bán chậm</span>
                                        @else
                                            <span class="badge bg-info">Bình thường</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-bolt me-2 text-primary"></i>
                        Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary w-100 p-3">
                                <i class="fas fa-shopping-cart fa-lg mb-2 d-block"></i>
                                Quản lý đơn hàng
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-warning w-100 p-3">
                                <i class="fas fa-box fa-lg mb-2 d-block"></i>
                                Quản lý sản phẩm
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info w-100 p-3">
                                <i class="fas fa-users fa-lg mb-2 d-block"></i>
                                Quản lý người dùng
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('admin.products.categories.index') }}" class="btn btn-outline-success w-100 p-3">
                                <i class="fas fa-tags fa-lg mb-2 d-block"></i>
                                Danh mục
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary w-100 p-3">
                                <i class="fas fa-newspaper fa-lg mb-2 d-block"></i>
                                Tin tức
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-danger w-100 p-3">
                                <i class="fas fa-ticket-alt fa-lg mb-2 d-block"></i>
                                Coupon
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card {
            transition: all 0.3s ease;
            border-radius: 15px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .card {
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .btn {
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
        }

        /* Loading Animation */
        .stat-card {
            animation: fadeInUp 0.6s ease-out;
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

        /* Chart containers */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
            overflow: hidden;
        }

        .chart-wrapper canvas {
            position: absolute !important;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
        }

        /* Fix chart canvas sizing */
        #revenueChart, #orderStatusChart {
            max-width: 100% !important;
            max-height: 300px !important;
            width: 100% !important;
            height: 300px !important;
        }

        /* Custom scrollbar */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .display-6 {
                font-size: 1.75rem;
            }
            
            .stat-card .card-body {
                padding: 1.5rem;
            }
            
            .quick-action-btn {
                font-size: 0.875rem;
                padding: 1rem;
            }

            .chart-wrapper {
                height: 250px !important;
            }

            #revenueChart, #orderStatusChart {
                max-height: 250px !important;
                height: 250px !important;
            }
        }

        @media (max-width: 576px) {
            .chart-wrapper {
                height: 200px !important;
            }

            #revenueChart, #orderStatusChart {
                max-height: 200px !important;
                height: 200px !important;
            }
        }

        /* Animation delays for staggered effect */
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        /* Pulse animation for important numbers */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Đảm bảo container có kích thước trước khi khởi tạo biểu đồ
            const revenueContainer = document.getElementById('revenueChart').parentElement;
            const orderStatusContainer = document.getElementById('orderStatusChart').parentElement;
            
            // Thiết lập kích thước cố định cho container
            revenueContainer.style.height = '300px';
            revenueContainer.style.position = 'relative';
            orderStatusContainer.style.height = '250px';
            orderStatusContainer.style.position = 'relative';

            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueData = @json($revenueLastWeek);
            
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueData.map(item => item.date),
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: revenueData.map(item => item.revenue),
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: false,
                    resizeDelay: 0,
                    layout: {
                        padding: {
                            top: 20,
                            right: 20,
                            bottom: 20,
                            left: 20
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });

            // Order Status Pie Chart
            const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const orderStats = @json($orderStats);
            
            new Chart(orderStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Chờ xử lý', 'Đang xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy', 'Đã trả'],
                    datasets: [{
                        data: [
                            orderStats.pending,
                            orderStats.processing,
                            orderStats.shipped,
                            orderStats.delivered,
                            orderStats.cancelled,
                            orderStats.returned
                        ],
                        backgroundColor: [
                            '#ffc107',
                            '#17a2b8',
                            '#007bff',
                            '#28a745',
                            '#dc3545',
                            '#6c757d'
                        ],
                        borderWidth: 0,
                        cutout: '60%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: false,
                    resizeDelay: 0,
                    layout: {
                        padding: {
                            top: 10,
                            right: 10,
                            bottom: 10,
                            left: 10
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

            // Counter animation
            function animateCounters() {
                const counters = document.querySelectorAll('.stat-card h3');
                
                counters.forEach(counter => {
                    const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                    const increment = target / 100;
                    let current = 0;
                    
                    const updateCounter = () => {
                        if (current < target) {
                            current += increment;
                            counter.textContent = Math.ceil(current).toLocaleString('vi-VN');
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = target.toLocaleString('vi-VN');
                            if (counter.textContent.includes('₫')) {
                                counter.textContent += '₫';
                            }
                        }
                    };
                    
                    setTimeout(() => updateCounter(), 500);
                });
            }

            // Trigger animations
            setTimeout(animateCounters, 800);

            // Add hover effects to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Auto refresh data every 5 minutes
            setInterval(() => {
                location.reload();
            }, 300000);

            // Add loading states to buttons
            const quickActionBtns = document.querySelectorAll('.btn');
            quickActionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!this.classList.contains('no-loading')) {
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tải...';
                    }
                });
            });

            // Real-time clock
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleString('vi-VN');
                // Update time if element exists
                const timeElement = document.querySelector('.current-time');
                if (timeElement) {
                    timeElement.textContent = timeString;
                }
            }

            setInterval(updateClock, 1000);
        });

        // Notification system
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Welcome message
        setTimeout(() => {
            showNotification('Chào mừng bạn đến với Dashboard Admin!', 'success');
        }, 1000);
    </script>
@endpush
