@extends('client.layouts.app')

@section('title', 'Đơn hàng của tôi')

@push('styles')
<style>
    .account-sidebar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .order-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
    
    .order-timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .order-timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 1rem;
        bottom: 1rem;
        width: 2px;
        background: #e5e7eb;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.625rem;
        top: 0.375rem;
        width: 0.75rem;
        height: 0.75rem;
        background: #10b981;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #e5e7eb;
    }
    
    .timeline-item.pending::before {
        background: #f59e0b;
    }
    
    .timeline-item.processing::before {
        background: #3b82f6;
    }
    
    .timeline-item.shipped::before {
        background: #8b5cf6;
    }
    
    .timeline-item.cancelled::before {
        background: #ef4444;
    }
    
    @media (max-width: 768px) {
        .account-container {
            padding: 1rem;
        }
        
        .order-timeline {
            padding-left: 1.5rem;
        }
        
        .timeline-item::before {
            left: -1.375rem;
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
                        <a href="{{ route('accounts.index') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                            <i class="fas fa-user mr-3"></i>
                            Thông tin tài khoản
                        </a>
                        <a href="{{ route('accounts.orders') }}" class="flex items-center p-3 rounded-lg bg-white/20 text-white">
                            <i class="fas fa-shopping-bag mr-3"></i>
                            Đơn hàng của tôi
                        </a>
                        <a href="{{ route('accounts.profile') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
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
                <div class="d-flex align-items-center justify-content-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-0">
                        <i class="fas fa-shopping-bag me-2 text-orange-500"></i>
                        Đơn hàng của tôi
                    </h2>
                    
                    <!-- Filter buttons -->
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="orderFilter" id="all" value="all" checked>
                        <label class="btn btn-outline-primary btn-sm" for="all">Tất cả</label>
                        
                        <input type="radio" class="btn-check" name="orderFilter" id="pending" value="pending">
                        <label class="btn btn-outline-warning btn-sm" for="pending">Chờ xử lý</label>
                        
                        <input type="radio" class="btn-check" name="orderFilter" id="processing" value="processing">
                        <label class="btn btn-outline-info btn-sm" for="processing">Đang xử lý</label>
                        
                        <input type="radio" class="btn-check" name="orderFilter" id="delivered" value="delivered">
                        <label class="btn btn-outline-success btn-sm" for="delivered">Hoàn thành</label>
                    </div>
                </div>

                @if($orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="order-card bg-white rounded-lg p-6 mb-4" data-status="{{ $order->status }}">
                                <!-- Order Header -->
                                <div class="d-flex justify-content-between align-items-start mb-4 pb-4 border-bottom">
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-800 mb-2">
                                            Đơn hàng #{{ $order->id }}
                                        </h4>
                                        <div class="text-sm text-gray-600 space-y-1">
                                            <div>
                                                <i class="fas fa-calendar-alt me-2"></i>
                                                Ngày đặt: {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </div>
                                            <div>
                                                <i class="fas fa-box me-2"></i>
                                                {{ $order->orderItems->count() }} sản phẩm
                                            </div>
                                            <div>
                                                <i class="fas fa-credit-card me-2"></i>
                                                {{ ucfirst($order->payment_method) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-end">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Chờ xử lý', 'icon' => 'clock'],
                                                'processing' => ['class' => 'bg-info', 'text' => 'Đang xử lý', 'icon' => 'cog'],
                                                'shipped' => ['class' => 'bg-primary', 'text' => 'Đang giao', 'icon' => 'truck'],
                                                'delivered' => ['class' => 'bg-success', 'text' => 'Hoàn thành', 'icon' => 'check-circle'],
                                                'cancelled' => ['class' => 'bg-danger', 'text' => 'Đã hủy', 'icon' => 'times-circle'],
                                                'returned' => ['class' => 'bg-secondary', 'text' => 'Đã trả', 'icon' => 'undo']
                                            ];
                                            $config = $statusConfig[$order->status] ?? ['class' => 'bg-secondary', 'text' => $order->status, 'icon' => 'question'];
                                        @endphp
                                        
                                        <span class="order-status-badge {{ $config['class'] }} mb-2 d-inline-block">
                                            <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                            {{ $config['text'] }}
                                        </span>
                                        
                                        <div class="text-xl font-bold text-orange-600">
                                            {{ number_format($order->final_total) }}₫
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                <div class="mb-4">
                                    <h6 class="font-semibold text-gray-700 mb-3">Sản phẩm đã đặt:</h6>
                                    <div class="space-y-3">
                                        @foreach($order->orderItems->take(3) as $item)
                                            <div class="d-flex align-items-center p-3 bg-gray-50 rounded-lg">
                                                <div class="flex-shrink-0 me-3">
                                                    @if($item->product && $item->product->productAllImages->count() > 0)
                                                        <img src="{{ asset('uploads/products/' . $item->product->productAllImages->first()->image_url) }}" 
                                                             alt="{{ $item->name_product }}" 
                                                             class="w-16 h-16 object-cover rounded">
                                                    @else
                                                        <div class="w-16 h-16 bg-gray-200 rounded d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="font-medium text-gray-800 mb-1">{{ $item->name_product }}</h6>
                                                    <div class="text-sm text-gray-600">
                                                        <span>Số lượng: {{ $item->quantity }}</span>
                                                        <span class="mx-2">•</span>
                                                        <span>Đơn giá: {{ number_format($item->price) }}₫</span>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="font-semibold text-orange-600">
                                                        {{ number_format($item->total_price) }}₫
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @if($order->orderItems->count() > 3)
                                            <div class="text-center">
                                                <span class="text-gray-500 text-sm">
                                                    Và {{ $order->orderItems->count() - 3 }} sản phẩm khác...
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Order Actions -->
                                <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        Giao đến: {{ $order->recipient_name }} - {{ $order->recipient_phone }}
                                    </div>
                                    
                                    <div class="space-x-2">
                                        <a href="{{ route('accounts.order-detail', $order->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            Chi tiết
                                        </a>
                                        
                                        @if($order->status === 'pending')
                                            <button class="btn btn-outline-danger btn-sm" 
                                                    onclick="cancelOrder({{ $order->id }})">
                                                <i class="fas fa-times me-1"></i>
                                                Hủy đơn
                                            </button>
                                        @endif
                                        
                                        @if($order->status === 'delivered')
                                            <button class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-star me-1"></i>
                                                Đánh giá
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-redo me-1"></i>
                                                Mua lại
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-6">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg p-8 text-center">
                        <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-600 mb-2">Chưa có đơn hàng nào</h4>
                        <p class="text-gray-500 mb-6">Hãy khám phá và mua sắm các sản phẩm yêu thích của bạn</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Bắt đầu mua sắm
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter orders by status
    const filterButtons = document.querySelectorAll('input[name="orderFilter"]');
    const orderCards = document.querySelectorAll('.order-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            const filterValue = this.value;
            
            orderCards.forEach(card => {
                const orderStatus = card.getAttribute('data-status');
                
                if (filterValue === 'all' || orderStatus === filterValue) {
                    card.style.display = 'block';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Smooth animations for cards
    orderCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        // Here you would make an AJAX call to cancel the order
        fetch(`/client/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi hủy đơn hàng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi hủy đơn hàng');
        });
    }
}
</script>
@endpush
