@extends('client.layouts.app')

@section('title', 'Thông tin tài khoản')

@push('styles')
<style>
    .account-sidebar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .account-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .account-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .quick-stats {
        background: linear-gradient(135deg, #ff6c2f 0%, #ff8a50 100%);
    }
    
    .order-status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-weight: 600;
    }
    
    .avatar-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: bold;
    }
    
    @media (max-width: 768px) {
        .account-container {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 account-container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="account-sidebar rounded-lg p-6 text-white mb-4">
                    <div class="text-center mb-6">
                        <div class="avatar-placeholder mx-auto mb-3">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h4 class="font-bold text-lg">{{ Auth::user()->name }}</h4>
                        <p class="text-white/80 text-sm">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <nav class="space-y-2">
                        <a href="{{ route('accounts.index') }}" class="flex items-center p-3 rounded-lg bg-white/20 text-white">
                            <i class="fas fa-user mr-3"></i>
                            Thông tin tài khoản
                        </a>
                        <a href="{{ route('accounts.orders') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                            <i class="fas fa-shopping-bag mr-3"></i>
                            Đơn hàng của tôi
                        </a>
                        <a href="{{ route('accounts.edit') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                            <i class="fas fa-edit mr-3"></i>
                            Chỉnh sửa thông tin
                        </a>
                        <a href="{{ route('accounts.addresses') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            Sổ địa chỉ
                        </a>
                        <a href="{{ route('accounts.change-password') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                            <i class="fas fa-lock mr-3"></i>
                            Đổi mật khẩu
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Welcome Section -->
                <div class="account-card bg-white rounded-lg p-6 mb-6">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                Chào mừng trở lại, {{ Auth::user()->name }}! 👋
                            </h2>
                            <p class="text-gray-600">
                                Quản lý thông tin tài khoản và theo dõi đơn hàng của bạn tại đây.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('accounts.edit') }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row mb-6">
                    <div class="col-md-4 mb-3">
                        <div class="account-card bg-white rounded-lg p-4">
                            <div class="d-flex align-items-center">
                                <div class="quick-stats rounded-circle p-3 me-3">
                                    <i class="fas fa-shopping-bag text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-gray-800 mb-0">{{ $recentOrders->count() }}</h4>
                                    <p class="text-gray-600 text-sm mb-0">Đơn hàng gần đây</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="account-card bg-white rounded-lg p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-green-500 rounded-circle p-3 me-3">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-gray-800 mb-0">
                                        {{ $recentOrders->where('status', 'delivered')->count() }}
                                    </h4>
                                    <p class="text-gray-600 text-sm mb-0">Đã hoàn thành</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="account-card bg-white rounded-lg p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-blue-500 rounded-circle p-3 me-3">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-gray-800 mb-0">{{ $addresses->count() }}</h4>
                                    <p class="text-gray-600 text-sm mb-0">Địa chỉ đã lưu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="account-card bg-white rounded-lg p-6 mb-6">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-0">
                            <i class="fas fa-clock me-2 text-orange-500"></i>
                            Đơn hàng gần đây
                        </h3>
                        <a href="{{ route('accounts.orders') }}" class="btn btn-outline-primary btn-sm">
                            Xem tất cả
                        </a>
                    </div>
                    
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>
                                                <strong class="text-primary">#{{ $order->id }}</strong>
                                            </td>
                                            <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>
                                                <strong class="text-success">{{ number_format($order->final_total) }}₫</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $statusConfig = [
                                                        'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Chờ xử lý'],
                                                        'processing' => ['class' => 'bg-info', 'text' => 'Đang xử lý'],
                                                        'shipped' => ['class' => 'bg-primary', 'text' => 'Đang giao'],
                                                        'delivered' => ['class' => 'bg-success', 'text' => 'Hoàn thành'],
                                                        'cancelled' => ['class' => 'bg-danger', 'text' => 'Đã hủy'],
                                                        'returned' => ['class' => 'bg-secondary', 'text' => 'Đã trả']
                                                    ];
                                                    $config = $statusConfig[$order->status] ?? ['class' => 'bg-secondary', 'text' => $order->status];
                                                @endphp
                                                <span class="order-status-badge {{ $config['class'] }}">
                                                    {{ $config['text'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('accounts.order-detail', $order->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                            <h4 class="text-gray-500 mb-2">Chưa có đơn hàng nào</h4>
                            <p class="text-gray-400 mb-4">Hãy khám phá và mua sắm ngay!</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Account Information -->
                <div class="account-card bg-white rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-user me-2 text-orange-500"></i>
                        Thông tin tài khoản
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="border rounded-lg p-4">
                                <h5 class="font-semibold text-gray-700 mb-2">Thông tin cá nhân</h5>
                                <div class="space-y-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-gray-600">Họ tên:</span>
                                        <strong>{{ Auth::user()->name }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-gray-600">Email:</span>
                                        <strong>{{ Auth::user()->email }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-gray-600">Số điện thoại:</span>
                                        <strong>{{ Auth::user()->phone ?? 'Chưa cập nhật' }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-gray-600">Ngày sinh:</span>
                                        <strong>{{ Auth::user()->birthday ? Auth::user()->birthday->format('d/m/Y') : 'Chưa cập nhật' }}</strong>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('accounts.profile') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Chỉnh sửa
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="border rounded-lg p-4">
                                <h5 class="font-semibold text-gray-700 mb-2">Địa chỉ mặc định</h5>
                                @if($addresses->where('is_default', true)->first())
                                    @php $defaultAddress = $addresses->where('is_default', true)->first() @endphp
                                    <div class="space-y-2">
                                        <div>
                                            <strong>{{ $defaultAddress->recipient_name }}</strong>
                                        </div>
                                        <div class="text-gray-600">
                                            {{ $defaultAddress->phone }}
                                        </div>
                                        <div class="text-gray-600">
                                            {{ $defaultAddress->address }}
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-500 mb-3">Chưa có địa chỉ mặc định</p>
                                @endif
                                <div class="mt-3">
                                    <a href="{{ route('accounts.addresses') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-map-marker-alt me-1"></i>Quản lý địa chỉ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations
    const cards = document.querySelectorAll('.account-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
