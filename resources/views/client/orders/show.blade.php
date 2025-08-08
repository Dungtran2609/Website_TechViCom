@extends('client.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@push('styles')
<style>
    .order-detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        margin-bottom: 2rem;
        padding: 1rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.625rem;
        top: 1.25rem;
        width: 1rem;
        height: 1rem;
        background: #10b981;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #e5e7eb;
    }
    
    .timeline-item.current::before {
        background: #ff6c2f;
        box-shadow: 0 0 0 3px #ff6c2f, 0 0 0 6px white, 0 0 0 8px #e5e7eb;
    }
    
    .timeline-item.pending::before {
        background: #6b7280;
    }
    
    .product-item {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .product-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .summary-card {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 1px solid #e5e7eb;
    }
    
    @media (max-width: 768px) {
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
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="order-detail-header rounded-lg p-6 text-white mb-6">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('accounts.orders') }}" 
                           class="text-white/80 hover:text-white text-decoration-none me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold mb-0">Chi tiết đơn hàng #{{ $order->id }}</h1>
                    </div>
                    <p class="text-white/80 mb-0">
                        Đặt ngày {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </p>
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
                    
                    <span class="badge {{ $config['class'] }} fs-6 px-3 py-2">
                        <i class="fas fa-{{ $config['icon'] }} me-2"></i>
                        {{ $config['text'] }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Timeline -->
            <div class="col-lg-4 mb-6">
                <div class="bg-white rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-route me-2 text-orange-500"></i>
                        Trạng thái đơn hàng
                    </h3>
                    
                    <div class="order-timeline">
                        <div class="timeline-item {{ $order->status === 'pending' ? 'current' : ($order->created_at ? '' : 'pending') }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-receipt text-orange-500 me-3"></i>
                                <div>
                                    <h6 class="font-semibold mb-1">Đơn hàng đã được đặt</h6>
                                    <p class="text-sm text-gray-600 mb-0">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'current' : 'pending' }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cog text-blue-500 me-3"></i>
                                <div>
                                    <h6 class="font-semibold mb-1">Đang xử lý</h6>
                                    <p class="text-sm text-gray-600 mb-0">
                                        {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'Đã xử lý' : 'Chờ xử lý' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'current' : 'pending' }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck text-purple-500 me-3"></i>
                                <div>
                                    <h6 class="font-semibold mb-1">Đang vận chuyển</h6>
                                    <p class="text-sm text-gray-600 mb-0">
                                        {{ $order->shipped_at ? $order->shipped_at->format('d/m/Y H:i') : 'Chưa vận chuyển' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item {{ $order->status === 'delivered' ? 'current' : 'pending' }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-green-500 me-3"></i>
                                <div>
                                    <h6 class="font-semibold mb-1">Đã giao hàng</h6>
                                    <p class="text-sm text-gray-600 mb-0">
                                        {{ $order->status === 'delivered' ? 'Giao hàng thành công' : 'Chưa giao hàng' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="bg-white rounded-lg p-6 mt-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-map-marker-alt me-2 text-orange-500"></i>
                        Thông tin giao hàng
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <h6 class="font-semibold text-gray-700">Người nhận:</h6>
                            <p class="text-gray-600 mb-0">{{ $order->recipient_name }}</p>
                        </div>
                        <div>
                            <h6 class="font-semibold text-gray-700">Số điện thoại:</h6>
                            <p class="text-gray-600 mb-0">{{ $order->recipient_phone }}</p>
                        </div>
                        <div>
                            <h6 class="font-semibold text-gray-700">Địa chỉ:</h6>
                            <p class="text-gray-600 mb-0">{{ $order->recipient_address }}</p>
                        </div>
                        <div>
                            <h6 class="font-semibold text-gray-700">Phương thức thanh toán:</h6>
                            <p class="text-gray-600 mb-0">
                                @switch($order->payment_method)
                                    @case('cod')
                                        <i class="fas fa-money-bill-wave me-2"></i>Thanh toán khi nhận hàng
                                        @break
                                    @case('bank_transfer')
                                        <i class="fas fa-university me-2"></i>Chuyển khoản ngân hàng
                                        @break
                                    @case('credit_card')
                                        <i class="fas fa-credit-card me-2"></i>Thẻ tín dụng
                                        @break
                                    @case('vietqr')
                                        <i class="fas fa-qrcode me-2"></i>VietQR
                                        @break
                                    @default
                                        {{ ucfirst($order->payment_method) }}
                                @endswitch
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="col-lg-8">
                <div class="bg-white rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-box me-2 text-orange-500"></i>
                        Sản phẩm đã đặt ({{ $order->orderItems->count() }} sản phẩm)
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="product-item rounded-lg p-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-4">
                                        @if($item->product && $item->product->productAllImages->count() > 0)
                                            <img src="{{ asset('uploads/products/' . $item->product->productAllImages->first()->image_url) }}" 
                                                 alt="{{ $item->name_product }}" 
                                                 class="w-20 h-20 object-cover rounded-lg">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 rounded-lg d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <h5 class="font-semibold text-gray-800 mb-2">{{ $item->name_product }}</h5>
                                        
                                        @if($item->productVariant)
                                            <div class="text-sm text-gray-600 mb-2">
                                                @foreach($item->productVariant->attributeValues as $attributeValue)
                                                    <span class="badge bg-light text-dark me-1">
                                                        {{ $attributeValue->attribute->name }}: {{ $attributeValue->value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="text-sm text-gray-600">
                                                <span>Đơn giá: <strong>{{ number_format($item->price) }}₫</strong></span>
                                                <span class="mx-2">•</span>
                                                <span>Số lượng: <strong>{{ $item->quantity }}</strong></span>
                                            </div>
                                            <div class="text-xl font-bold text-orange-600">
                                                {{ number_format($item->total_price) }}₫
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="summary-card rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-calculator me-2 text-orange-500"></i>
                        Tóm tắt đơn hàng
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-semibold">{{ number_format($order->total_amount) }}₫</span>
                        </div>
                        
                        @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between text-green-600">
                                <span>Giảm giá:</span>
                                <span class="font-semibold">-{{ number_format($order->discount_amount) }}₫</span>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-semibold">
                                @if($order->shipping_fee > 0)
                                    {{ number_format($order->shipping_fee) }}₫
                                @else
                                    <span class="text-green-600">Miễn phí</span>
                                @endif
                            </span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <span class="text-lg font-bold text-gray-800">Tổng cộng:</span>
                            <span class="text-2xl font-bold text-orange-600">{{ number_format($order->final_total) }}₫</span>
                        </div>
                        
                        @if($order->coupon_code)
                            <div class="mt-3 p-3 bg-green-50 rounded-lg">
                                <div class="d-flex align-items-center text-green-700">
                                    <i class="fas fa-ticket-alt me-2"></i>
                                    <span class="font-semibold">Mã giảm giá: {{ $order->coupon_code }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 d-flex justify-content-between">
                    <a href="{{ route('accounts.orders') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Quay lại danh sách
                    </a>
                    
                    <div class="space-x-2">
                        @if($order->status === 'pending')
                            <button class="btn btn-outline-danger" onclick="cancelOrder({{ $order->id }})">
                                <i class="fas fa-times me-2"></i>
                                Hủy đơn hàng
                            </button>
                        @endif
                        
                        @if($order->status === 'delivered')
                            <button class="btn btn-outline-warning">
                                <i class="fas fa-star me-2"></i>
                                Đánh giá sản phẩm
                            </button>
                            <button class="btn btn-primary">
                                <i class="fas fa-redo me-2"></i>
                                Mua lại
                            </button>
                        @endif
                        
                        <button class="btn btn-outline-info" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>
                            In đơn hàng
                        </button>
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
    // Smooth animations
    const elements = document.querySelectorAll('.product-item, .timeline-item, .summary-card');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.5s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        fetch(`/accounts/orders/${orderId}/cancel`, {
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
