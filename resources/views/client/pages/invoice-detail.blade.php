@extends('client.layouts.app')

@section('title', 'Chi tiết đơn hàng - Techvicom')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                <i class="fas fa-file-invoice text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Chi tiết đơn hàng</h1>
            <p class="text-lg text-gray-600">Thông tin chi tiết đơn hàng #{{ 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="max-w-6xl mx-auto">
            <!-- Order Summary -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Thông tin đơn hàng</h2>
                    <div class="flex items-center space-x-4">
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ getStatusColor($order->status) }}">
                            {{ $order->status_vietnamese }}
                        </div>
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ getPaymentStatusColor($order->payment_status) }}">
                            {{ $order->payment_status_vietnamese }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Thông tin đơn hàng</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Mã đơn hàng:</span> {{ 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p><span class="font-medium">Ngày đặt:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><span class="font-medium">Phương thức thanh toán:</span> {{ $order->payment_method_vietnamese }}</p>
                            @if($order->shippingMethod)
                                <p><span class="font-medium">Phương thức giao hàng:</span> {{ $order->shippingMethod->name }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Thông tin giao hàng</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Người nhận:</span> {{ $order->recipient_name }}</p>
                            <p><span class="font-medium">Số điện thoại:</span> {{ $order->recipient_phone }}</p>
                            <p><span class="font-medium">Địa chỉ:</span> {{ $order->recipient_address }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Tổng tiền</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Tạm tính:</span> {{ number_format($order->total_amount) }} ₫</p>
                            <p><span class="font-medium">Phí vận chuyển:</span> {{ number_format($order->shipping_fee) }} ₫</p>
                            @if($order->discount_amount > 0)
                                <p><span class="font-medium">Giảm giá:</span> -{{ number_format($order->discount_amount) }} ₫</p>
                            @endif
                            <p class="text-lg font-bold text-blue-600"><span class="font-medium">Tổng cộng:</span> {{ number_format($order->final_total) }} ₫</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Sản phẩm đã đặt</h2>
                
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center space-x-4">
                                                         <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                 @if($item->productVariant && $item->productVariant->image)
                                     <img src="{{ asset('storage/' . ltrim($item->productVariant->image, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                 @elseif($item->image_product)
                                     <img src="{{ asset('storage/' . ltrim($item->image_product, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                 @elseif($item->productVariant && $item->productVariant->product && $item->productVariant->product->images->first())
                                     <img src="{{ asset('storage/' . ltrim($item->productVariant->product->images->first()->image_path, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                 @elseif($item->productVariant && $item->productVariant->product && $item->productVariant->product->thumbnail)
                                     <img src="{{ asset('storage/' . ltrim($item->productVariant->product->thumbnail, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                 @else
                                     <div class="flex flex-col items-center justify-center text-gray-400">
                                         <i class="fas fa-image text-xl mb-1"></i>
                                         <span class="text-xs">No Image</span>
                                     </div>
                                 @endif
                             </div>
                            
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $item->name_product ?? ($item->productVariant->product->name ?? 'N/A') }}</h3>
                                
                                @if($item->productVariant && $item->productVariant->attributeValues->count() > 0)
                                    <div class="text-sm text-gray-600 mb-2">
                                        @foreach($item->productVariant->attributeValues as $attrValue)
                                            <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-2 mb-1">
                                                {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">Số lượng:</span> {{ $item->quantity }}
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-gray-900">{{ number_format($item->price) }} ₫</div>
                                        <div class="text-sm text-gray-600">Tổng: {{ number_format($item->total_price ?? ($item->price * $item->quantity)) }} ₫</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Lịch sử đơn hàng</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Đơn hàng đã được tạo</p>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($order->status !== 'pending')
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-cog text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Đơn hàng đang được xử lý</p>
                            <p class="text-sm text-gray-600">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($order->shipped_at)
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-shipping-fast text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Đơn hàng đã được giao</p>
                            <p class="text-sm text-gray-600">{{ $order->shipped_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($order->received_at)
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check-circle text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Đã nhận hàng</p>
                            <p class="text-sm text-gray-600">{{ $order->received_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('client.invoice.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay lại
                    </a>
                    
                    <button onclick="downloadInvoice({{ $order->id }})" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300">
                        <i class="fas fa-download mr-2"></i>
                        Tải hóa đơn PDF
                    </button>
                    
                    <a href="{{ route('client.contacts.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300">
                        <i class="fas fa-headset mr-2"></i>
                        Liên hệ hỗ trợ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom animations */
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

.bg-white {
    animation: fadeInUp 0.6s ease-out;
}

/* Hover effects */
.bg-white:hover {
    transform: translateY(-2px);
}
</style>

<script>
function downloadInvoice(orderId) {
    fetch(`/invoice/download/${orderId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        showAlert(data.message, 'info');
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Có lỗi xảy ra khi tải hóa đơn', 'error');
    });
}

function showAlert(message, type) {
    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    alert.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                           type === 'error' ? 'fa-exclamation-circle' : 
                           'fa-info-circle'} mr-3"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}
</script>
@endsection

@php
function getStatusColor($status) {
    $colors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'processing' => 'bg-blue-100 text-blue-800',
        'shipped' => 'bg-purple-100 text-purple-800',
        'delivered' => 'bg-green-100 text-green-800',
        'received' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
        'returned' => 'bg-gray-100 text-gray-800'
    ];
    return $colors[$status] ?? 'bg-gray-100 text-gray-800';
}

function getPaymentStatusColor($status) {
    $colors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'paid' => 'bg-green-100 text-green-800',
        'failed' => 'bg-red-100 text-red-800'
    ];
    return $colors[$status] ?? 'bg-gray-100 text-gray-800';
}
@endphp
