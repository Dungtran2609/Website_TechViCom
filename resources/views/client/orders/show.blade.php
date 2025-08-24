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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="bg-gray-50 min-h-screen py-8">
    <div class="techvicom-container">
        <!-- Header -->
        <div class="order-detail-header rounded-lg p-6 text-white mb-6">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('accounts.orders') }}" 
                           class="text-white/80 hover:text-white text-decoration-none me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold mb-0">Chi tiết đơn hàng #{{ $order->random_code ?? $order->code ?? ('DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)) }}</h1>
                    </div>
                    <p class="text-white/80 mb-0">
                        Đặt ngày {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </p>
                </div>
                <div class="text-end">
                    @php
                        $statusConfig = [
                            'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Đang chờ xử lý', 'icon' => 'clock'],
                            'processing' => ['class' => 'bg-info', 'text' => 'Đang xử lý', 'icon' => 'cog'],
                            'shipped' => ['class' => 'bg-primary', 'text' => 'Đang giao', 'icon' => 'truck'],
                            'delivered' => ['class' => 'bg-success', 'text' => 'Đã giao hàng', 'icon' => 'check-circle'],
                            'received' => ['class' => 'bg-success', 'text' => 'Hoàn thành', 'icon' => 'check-double'],
                            'cancelled' => ['class' => 'bg-danger', 'text' => 'Đã hủy', 'icon' => 'times-circle'],
                            'returned' => ['class' => 'bg-secondary', 'text' => 'Đã trả', 'icon' => 'undo']
                        ];
                        $config = $statusConfig[$order->status] ?? ['class' => 'bg-secondary', 'text' => $order->status, 'icon' => 'question'];
                        $orderReturn = $order->returns()->latest()->first();
                    @endphp
                    <span class="badge {{ $config['class'] }} fs-6 px-3 py-2">
                        <i class="fas fa-{{ $config['icon'] }} me-2"></i>
                        {{ $config['text'] }}
                    </span>
                    
                    <!-- Trạng thái thanh toán -->
                    @php
                        $paymentStatusConfig = [
                            'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Chưa thanh toán', 'icon' => 'clock'],
                            'processing' => ['class' => 'bg-info', 'text' => 'Đang xử lý thanh toán', 'icon' => 'spinner'],
                            'paid' => ['class' => 'bg-success', 'text' => 'Đã thanh toán', 'icon' => 'check-circle'],
                            'failed' => ['class' => 'bg-danger', 'text' => 'Thanh toán thất bại', 'icon' => 'times-circle'],
                            'cancelled' => ['class' => 'bg-secondary', 'text' => 'Đã hủy thanh toán', 'icon' => 'ban']
                        ];
                        $paymentConfig = $paymentStatusConfig[$order->payment_status] ?? ['class' => 'bg-secondary', 'text' => $order->payment_status, 'icon' => 'question'];
                    @endphp
                    <div class="mt-2">
                        <span class="badge {{ $paymentConfig['class'] }} fs-6 px-3 py-2">
                            <i class="fas fa-{{ $paymentConfig['icon'] }} me-2"></i>
                            {{ $paymentConfig['text'] }}
                        </span>
                    </div>
                    
                    @if($order->status === 'returned' && $orderReturn && $orderReturn->status === 'pending')
                        <span class="badge bg-info ms-2">Chờ admin xác nhận trả hàng</span>
                    @endif
                    @php
                        $cancelRequest = $order->returns()->where('type', 'cancel')->whereIn('status', ['pending', 'approved'])->first();
                    @endphp
                    @if($cancelRequest)
                        @if($cancelRequest->status === 'pending')
                            <span class="badge bg-warning text-dark ms-2">Chờ admin xác nhận hủy đơn hàng</span>
                        @elseif($cancelRequest->status === 'approved')
                            <span class="badge bg-success ms-2">Yêu cầu hủy đã được phê duyệt</span>
                        @elseif($cancelRequest->status === 'rejected')
                            <span class="badge bg-danger ms-2">Yêu cầu hủy đã bị từ chối</span>
                        @endif
                    @endif
                    
                    @php
                        $returnRequest = $order->returns()->where('type', 'return')->whereIn('status', ['pending', 'approved', 'rejected'])->first();
                    @endphp
                    @if($returnRequest)
                        @if($returnRequest->status === 'pending')
                            <span class="badge bg-warning text-dark ms-2">Chờ admin xác nhận trả hàng</span>
                        @elseif($returnRequest->status === 'approved')
                            <span class="badge bg-success ms-2">Yêu cầu trả hàng đã được phê duyệt</span>
                        @elseif($returnRequest->status === 'rejected')
                            <span class="badge bg-danger ms-2">Yêu cầu trả hàng đã bị từ chối</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Timeline -->
            <div class="col-lg-4 mb-6">
                @php
                    $orderReturn = $order->returns()->latest()->first();
                @endphp
                @if($orderReturn && in_array($order->status, ['cancelled', 'returned']) && $orderReturn->admin_note)
                    <div class="alert alert-info mb-3">
                        <strong>Ghi chú từ admin:</strong> {{ $orderReturn->admin_note }}
                    </div>
                @endif
                
                @php
                    $cancelRequest = $order->returns()->where('type', 'cancel')->whereIn('status', ['pending', 'approved', 'rejected'])->first();
                @endphp
                @if($cancelRequest)
                    <div class="alert alert-warning mb-3">
                        <strong>Yêu cầu hủy đơn hàng:</strong>
                        <br>
                        <strong>Trạng thái:</strong> 
                        @if($cancelRequest->status === 'pending')
                            <span class="badge bg-warning text-dark">Đang chờ admin xử lý</span>
                        @elseif($cancelRequest->status === 'approved')
                            <span class="badge bg-success">Đã được phê duyệt</span>
                        @elseif($cancelRequest->status === 'rejected')
                            <span class="badge bg-danger">Đã bị từ chối</span>
                        @endif
                        <br>
                        <strong>Lý do:</strong> {{ $cancelRequest->client_note }}
                        @if($cancelRequest->admin_note)
                            <br>
                            <strong>Phản hồi từ admin:</strong> {{ $cancelRequest->admin_note }}
                        @endif
                    </div>
                @endif
                
                @php
                    $returnRequest = $order->returns()->where('type', 'return')->whereIn('status', ['pending', 'approved', 'rejected'])->first();
                @endphp
                @if($returnRequest)
                    <div class="alert alert-info mb-3">
                        <strong>Yêu cầu trả hàng:</strong>
                        <br>
                        <strong>Trạng thái:</strong> 
                        @if($returnRequest->status === 'pending')
                            <span class="badge bg-warning text-dark">Đang chờ admin xử lý</span>
                        @elseif($returnRequest->status === 'approved')
                            <span class="badge bg-success">Đã được phê duyệt</span>
                        @elseif($returnRequest->status === 'rejected')
                            <span class="badge bg-danger">Đã bị từ chối</span>
                        @endif
                        <br>
                        <strong>Lý do:</strong> {{ $returnRequest->client_note }}
                        @if($returnRequest->admin_note)
                            <br>
                            <strong>Phản hồi từ admin:</strong> {{ $returnRequest->admin_note }}
                        @endif
                    </div>
                @endif
                
                <!-- Thông tin thanh toán -->
                <div class="bg-white rounded-lg p-6 mb-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-credit-card me-2 text-orange-500"></i>
                        Thông tin thanh toán
                    </h3>
                    
                    <div class="space-y-3">
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
                        
                        <div>
                            <h6 class="font-semibold text-gray-700">Trạng thái thanh toán:</h6>
                            <p class="text-gray-600 mb-0">
                                <span class="badge {{ $paymentConfig['class'] }}">
                                    <i class="fas fa-{{ $paymentConfig['icon'] }} me-1"></i>
                                    {{ $paymentConfig['text'] }}
                                </span>
                            </p>
                        </div>
                        
                        @if($order->paid_at)
                            <div>
                                <h6 class="font-semibold text-gray-700">Thời gian thanh toán:</h6>
                                <p class="text-gray-600 mb-0">{{ $order->paid_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($order->vnpay_transaction_id)
                            <div>
                                <h6 class="font-semibold text-gray-700">Mã giao dịch VNPay:</h6>
                                <p class="text-gray-600 mb-0">{{ $order->vnpay_transaction_id }}</p>
                            </div>
                        @endif
                        
                        <!-- Nút thanh toán lại -->
                        @if($order->status === 'pending' && in_array($order->payment_status, ['pending', 'processing', 'failed']) && $order->payment_method !== 'cod')
                            <div class="mt-4">
                                <a href="{{ route('checkout.index') }}?order_id={{ $order->id }}" 
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-credit-card me-2"></i>
                                    @if($order->payment_status === 'processing')
                                        Chọn phương thức thanh toán khác
                                    @else
                                        Thanh toán lại
                                    @endif
                                </a>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    @if($order->payment_status === 'processing')
                                        Thanh toán đang xử lý, bạn có thể chọn phương thức khác
                                    @else
                                        Chọn phương thức thanh toán khác
                                    @endif
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
                
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
                                        @php
                                            $shippedAt = $order->shipped_at;
                                            if ($shippedAt instanceof \Carbon\Carbon) {
                                                echo $shippedAt->format('d/m/Y H:i');
                                            } elseif (!empty($shippedAt)) {
                                                echo \Carbon\Carbon::parse($shippedAt)->format('d/m/Y H:i');
                                            } else {
                                                echo 'Chưa vận chuyển';
                                            }
                                        @endphp
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
                                        @php
                                            $imageUrl = null;
                                            if ($item->productVariant && $item->productVariant->image) {
                                                $imageUrl = asset('storage/' . ltrim($item->productVariant->image, '/'));
                                            } elseif ($item->product && $item->product->thumbnail) {
                                                $imageUrl = asset('storage/' . ltrim($item->product->thumbnail, '/'));
                                            } elseif ($item->product && $item->product->productAllImages && $item->product->productAllImages->count() > 0) {
                                                $imgObj = $item->product->productAllImages->first();
                                                $imgField = $imgObj->image_path ?? $imgObj->image_url ?? $imgObj->image ?? null;
                                                if ($imgField) $imageUrl = asset('uploads/products/' . ltrim($imgField, '/'));
                                            }
                                        @endphp

                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}"
                                                 alt="{{ $item->name_product }}"
                                                 class="w-20 h-20 object-cover rounded-lg"
                                                 onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 rounded-lg d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <h5 class="font-semibold text-gray-800 mb-2">
                                            @if($item->product && $item->product->id)
                                                <a href="{{ route('products.show', $item->product->id) }}" class="hover:text-[#ff6c2f] transition-colors">
                                                    {{ $item->name_product }}
                                                </a>
                                            @else
                                                {{ $item->name_product }}
                                            @endif
                                        </h5>
                                        
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

                        @if($order->vnpay_discount > 0)
                            <div class="d-flex justify-content-between text-green-600">
                                <span>Giảm giá VNPay:</span>
                                <span class="font-semibold">-{{ number_format($order->vnpay_discount / 100) }}₫</span>
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
                            @php
                                $hasCancelRequest = $order->returns()->where('type', 'cancel')->exists();
                            @endphp
                            @if(!$hasCancelRequest)
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                    <i class="fas fa-times me-2"></i>
                                    Hủy đơn hàng
                                </button>
                            @else
                                @php
                                    $cancelRequest = $order->returns()->where('type', 'cancel')->first();
                                @endphp
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="fas fa-times me-2"></i>
                                    @if($cancelRequest->status === 'rejected')
                                        Yêu cầu hủy đã bị từ chối
                                    @else
                                        Đã gửi yêu cầu hủy đơn hàng
                                    @endif
                                </button>
                            @endif
                        @endif

                        {{-- Nếu đã giao nhưng chưa xác nhận nhận hàng thì hiện nút xác nhận nhận hàng và trả hàng --}}
                        @if($order->status === 'delivered')
                            @php
                                $hasReturnRequest = $order->returns()->where('type', 'return')->exists();
                            @endphp
                            @if(!$hasReturnRequest)
                                <button class="btn btn-outline-success" id="btn-confirm-received" onclick="confirmReceived({{ $order->id }})">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Xác nhận đã nhận hàng
                                </button>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#returnOrderModal">
                                    <i class="fas fa-undo me-2"></i>
                                    Yêu cầu trả hàng
                                </button>
                            @else
                                <button class="btn btn-outline-success" id="btn-confirm-received" onclick="confirmReceived({{ $order->id }})">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Xác nhận đã nhận hàng
                                </button>
                                @php
                                    $returnRequest = $order->returns()->where('type', 'return')->first();
                                @endphp
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="fas fa-undo me-2"></i>
                                    @if($returnRequest->status === 'rejected')
                                        Yêu cầu trả hàng đã bị từ chối
                                    @else
                                        Đã gửi yêu cầu trả hàng
                                    @endif
                                </button>
                            @endif
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
        <!-- Modal nhập lý do hủy đơn hàng -->
        @if($order->status === 'pending' && !$hasCancelRequest)
        <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="cancelOrderForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelOrderModalLabel">Lý do hủy đơn hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Vui lòng chọn lý do hủy đơn hàng: <span class="text-danger">*</span></label>
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Lưu ý:</strong> Bạn phải chọn lý do trước khi có thể xác nhận hủy đơn hàng.
                                </div>
                                <div class="cancel-reasons-list">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="cancelReason" id="cancel1" value="Đổi ý không muốn mua nữa" >
                                        <label class="form-check-label" for="cancel1">
                                            <i class="fas fa-times-circle text-danger me-2"></i>Đổi ý không muốn mua nữa
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="cancelReason" id="cancel2" value="Tìm thấy sản phẩm rẻ hơn ở nơi khác" >
                                        <label class="form-check-label" for="cancel2">
                                            <i class="fas fa-search-dollar text-warning me-2"></i>Tìm thấy sản phẩm rẻ hơn ở nơi khác
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="cancelReason" id="cancel3" value="Đặt nhầm sản phẩm" >
                                        <label class="form-check-label" for="cancel3">
                                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>Đặt nhầm sản phẩm
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="cancelReason" id="cancel4" value="Thời gian giao hàng quá lâu" >
                                        <label class="form-check-label" for="cancel4">
                                            <i class="fas fa-clock text-info me-2"></i>Thời gian giao hàng quá lâu
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="cancelReason" id="cancel5" value="Không còn nhu cầu sử dụng" >
                                        <label class="form-check-label" for="cancel5">
                                            <i class="fas fa-ban text-secondary me-2"></i>Không còn nhu cầu sử dụng
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="cancelReason" id="cancel6" value="Vấn đề về tài chính" >
                                        <label class="form-check-label" for="cancel6">
                                            <i class="fas fa-wallet text-danger me-2"></i>Vấn đề về tài chính
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="cancelReason" id="cancel7" value="Lý do khác" >
                                        <label class="form-check-label" for="cancel7">
                                            <i class="fas fa-ellipsis-h text-muted me-2"></i>Lý do khác
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mt-3" id="otherReasonDiv" style="display: none;">
                                    <label for="cancelReasonOther" class="form-label">Vui lòng mô tả lý do khác: <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="cancelReasonOther" name="client_note_other" rows="2" placeholder="Nhập lý do cụ thể..." ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-danger" id="btn-confirm-cancel" disabled>Xác nhận hủy</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Modal nhập lý do trả hàng -->
        @if($order->status === 'delivered' && !$hasReturnRequest)
        <div class="modal fade" id="returnOrderModal" tabindex="-1" aria-labelledby="returnOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="returnOrderForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="returnOrderModalLabel">Lý do trả hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Vui lòng chọn lý do trả hàng: <span class="text-danger">*</span></label>
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Lưu ý:</strong> Bạn phải chọn lý do trước khi có thể gửi yêu cầu trả hàng.
                                </div>
                                <div class="return-reasons-list">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="returnReason" id="return1" value="Sản phẩm bị lỗi/hỏng" >
                                        <label class="form-check-label" for="return1">
                                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>Sản phẩm bị lỗi/hỏng
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="returnReason" id="return2" value="Sản phẩm không đúng mô tả" >
                                        <label class="form-check-label" for="return2">
                                            <i class="fas fa-info-circle text-warning me-2"></i>Sản phẩm không đúng mô tả
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="returnReason" id="return3" value="Kích thước không phù hợp" >
                                        <label class="form-check-label" for="return3">
                                            <i class="fas fa-ruler text-info me-2"></i>Kích thước không phù hợp
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="returnReason" id="return4" value="Màu sắc không như mong đợi" >
                                        <label class="form-check-label" for="return4">
                                            <i class="fas fa-palette text-warning me-2"></i>Màu sắc không như mong đợi
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="returnReason" id="return5" value="Chất lượng không tốt" >
                                        <label class="form-check-label" for="return5">
                                            <i class="fas fa-thumbs-down text-danger me-2"></i>Chất lượng không tốt
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="returnReason" id="return6" value="Giao hàng sai sản phẩm" >
                                        <label class="form-check-label" for="return6">
                                            <i class="fas fa-box-open text-warning me-2"></i>Giao hàng sai sản phẩm
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="returnReason" id="return7" value="Không vừa ý với sản phẩm" >
                                        <label class="form-check-label" for="return7">
                                            <i class="fas fa-frown text-secondary me-2"></i>Không vừa ý với sản phẩm
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="returnReason" id="return8" value="Lý do khác" >
                                        <label class="form-check-label" for="return8">
                                            <i class="fas fa-ellipsis-h text-muted me-2"></i>Lý do khác
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mt-3" id="otherReturnReasonDiv" style="display: none;">
                                    <label for="returnReasonOther" class="form-label">Vui lòng mô tả lý do khác: <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="returnReasonOther" name="client_note_other" rows="2" placeholder="Nhập lý do cụ thể..." ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-danger" id="btn-confirm-return" disabled>Xác nhận yêu cầu trả hàng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
@endsection

@push('styles')
<style>
.cancel-reasons-list .form-check,
.return-reasons-list .form-check {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.cancel-reasons-list .form-check:hover,
.return-reasons-list .form-check:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.cancel-reasons-list .form-check:has(input:checked),
.return-reasons-list .form-check:has(input:checked) {
    background-color: #fff3cd;
    border-color: #ffc107;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.form-check-input:checked {
    background-color: #ff6c2f;
    border-color: #ff6c2f;
}

.form-check-label {
    cursor: pointer;
    font-weight: 500;
    color: #495057;
}

.form-check-label i {
    width: 16px;
    text-align: center;
}

#otherReasonDiv,
#otherReturnReasonDiv {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
    margin-top: 15px;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

/* Style cho button disabled */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-danger:disabled {
    background-color: #dc3545;
    border-color: #dc3545;
    opacity: 0.6;
}

/* Animation cho button khi enable */
.btn:not(:disabled) {
    transition: all 0.3s ease;
}

.btn:not(:disabled):hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Style cho form validation */
.form-check-input:invalid {
    border-color: #dc3545;
}

.form-control:invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Hiệu ứng khi chọn radio button */
.form-check:hover {
    background-color: #f8f9fa;
}

.form-check:has(input:checked) {
    background-color: #fff3cd;
    border-color: #ffc107;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}
</style>
@endpush

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





    // Xử lý radio buttons cho hủy đơn hàng - kiểm tra validation
    document.querySelectorAll('input[name="cancelReason"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var otherReasonDiv = document.getElementById('otherReasonDiv');
            var otherReasonTextarea = document.getElementById('cancelReasonOther');
            var confirmButton = document.getElementById('btn-confirm-cancel');
            
            if (this.value === 'Lý do khác') {
                otherReasonDiv.style.display = 'block';
                otherReasonTextarea.required = true;
                // Kiểm tra nếu đã có text trong textarea thì enable button
                if (otherReasonTextarea.value.trim()) {
                    confirmButton.disabled = false;
                } else {
                    confirmButton.disabled = true;
                }
            } else {
                otherReasonDiv.style.display = 'none';
                otherReasonTextarea.required = false;
                otherReasonTextarea.value = '';
                // Nếu chọn lý do khác "Lý do khác" thì enable button
                confirmButton.disabled = false;
            }
        });
    });

    // Xử lý textarea cho lý do khác
    var cancelReasonOther = document.getElementById('cancelReasonOther');
    if (cancelReasonOther) {
        cancelReasonOther.addEventListener('input', function() {
            var confirmButton = document.getElementById('btn-confirm-cancel');
            var selectedReason = document.querySelector('input[name="cancelReason"]:checked');
            
            if (selectedReason && selectedReason.value === 'Lý do khác') {
                confirmButton.disabled = !this.value.trim();
            }
        });
    }

    // Xử lý modal hủy đơn hàng khi mở
    var cancelOrderModal = document.getElementById('cancelOrderModal');
    if (cancelOrderModal) {
        cancelOrderModal.addEventListener('show.bs.modal', function() {
            // Reset form khi mở modal
            document.getElementById('cancelOrderForm').reset();
            document.getElementById('btn-confirm-cancel').disabled = true;
            document.getElementById('otherReasonDiv').style.display = 'none';
        });
    }

    // Xử lý nút xác nhận hủy đơn hàng
    var btnConfirmCancel = document.getElementById('btn-confirm-cancel');
    if (btnConfirmCancel) {
        btnConfirmCancel.addEventListener('click', function() {
            // Kiểm tra validation
            var selectedReason = document.querySelector('input[name="cancelReason"]:checked');
            if (!selectedReason) {
                alert('Vui lòng chọn lý do hủy đơn hàng');
                return;
            }
            
            var clientNote = selectedReason.value;
            
            // Nếu chọn "Lý do khác", lấy nội dung từ textarea
            if (clientNote === 'Lý do khác') {
                var otherReason = document.getElementById('cancelReasonOther').value.trim();
                if (!otherReason) {
                    alert('Vui lòng nhập lý do cụ thể');
                    document.getElementById('cancelReasonOther').focus();
                    return;
                }
                clientNote = otherReason;
            }
            
            // Hiển thị xác nhận cuối cùng
            if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?\n\nLý do: ' + clientNote + '\n\nHành động này không thể hoàn tác!')) {
                var orderId = {{ $order->id }};
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                var formData = new FormData();
                formData.append('return_reason', clientNote);
                formData.append('client_note', clientNote);
                
                fetch(`/client/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Hủy đơn hàng thành công!');
                        // Đóng modal
                        var modalEl = document.getElementById('cancelOrderModal');
                        if (modalEl && window.bootstrap && bootstrap.Modal) {
                            var instance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                            instance.hide();
                        }
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi hủy đơn hàng');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi hủy đơn hàng');
                });
            }
        });
    }

    // Xử lý radio buttons cho trả hàng - kiểm tra validation
    document.querySelectorAll('input[name="returnReason"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var otherReasonDiv = document.getElementById('otherReturnReasonDiv');
            var otherReasonTextarea = document.getElementById('returnReasonOther');
            var confirmButton = document.getElementById('btn-confirm-return');
            
            if (this.value === 'Lý do khác') {
                otherReasonDiv.style.display = 'block';
                otherReasonTextarea.required = true;
                // Kiểm tra nếu đã có text trong textarea thì enable button
                if (otherReasonTextarea.value.trim()) {
                    confirmButton.disabled = false;
                } else {
                    confirmButton.disabled = true;
                }
            } else {
                otherReasonDiv.style.display = 'none';
                otherReasonTextarea.required = false;
                otherReasonTextarea.value = '';
                // Nếu chọn lý do khác "Lý do khác" thì enable button
                confirmButton.disabled = false;
            }
        });
    });

    // Xử lý textarea cho lý do khác (trả hàng)
    var returnReasonOther = document.getElementById('returnReasonOther');
    if (returnReasonOther) {
        returnReasonOther.addEventListener('input', function() {
            var confirmButton = document.getElementById('btn-confirm-return');
            var selectedReason = document.querySelector('input[name="returnReason"]:checked');
            
            if (selectedReason && selectedReason.value === 'Lý do khác') {
                confirmButton.disabled = !this.value.trim();
            }
        });
    }

    // Xử lý modal trả hàng khi mở
    var returnOrderModal = document.getElementById('returnOrderModal');
    if (returnOrderModal) {
        returnOrderModal.addEventListener('show.bs.modal', function() {
            // Reset form khi mở modal
            document.getElementById('returnOrderForm').reset();
            document.getElementById('btn-confirm-return').disabled = true;
            document.getElementById('otherReturnReasonDiv').style.display = 'none';
        });
    }

    // Xử lý nút xác nhận trả hàng
    var btnConfirmReturn = document.getElementById('btn-confirm-return');
    if (btnConfirmReturn) {
        btnConfirmReturn.addEventListener('click', function() {
            // Kiểm tra validation
            var selectedReason = document.querySelector('input[name="returnReason"]:checked');
            if (!selectedReason) {
                alert('Vui lòng chọn lý do trả hàng');
                return;
            }
            
            var clientNote = selectedReason.value;
            
            // Nếu chọn "Lý do khác", lấy nội dung từ textarea
            if (clientNote === 'Lý do khác') {
                var otherReason = document.getElementById('returnReasonOther').value.trim();
                if (!otherReason) {
                    alert('Vui lòng nhập lý do cụ thể');
                    document.getElementById('returnReasonOther').focus();
                    return;
                }
                clientNote = otherReason;
            }
            
            // Hiển thị xác nhận cuối cùng
            if (confirm('Bạn có chắc chắn muốn gửi yêu cầu trả hàng này không?\n\nLý do: ' + clientNote + '\n\nYêu cầu sẽ được gửi đến admin để xử lý!')) {
                var orderId = {{ $order->id }};
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                var formData = new FormData();
                formData.append('cancel_reason', clientNote);
                formData.append('client_note', clientNote);
                
                fetch(`/client/orders/${orderId}/request-return`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Yêu cầu trả hàng đã được gửi!');
                        // Đóng modal
                        var modalEl = document.getElementById('returnOrderModal');
                        if (modalEl && window.bootstrap && bootstrap.Modal) {
                            var instance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                            instance.hide();
                        }
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi gửi yêu cầu trả hàng');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi gửi yêu cầu trả hàng');
                });
            }
        });
    }
});



function confirmReceived(orderId) {
    if (confirm('Bạn xác nhận đã nhận được hàng?')) {
        fetch(`/client/orders/${orderId}/confirm-receipt`, {
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
                alert(data.message || 'Có lỗi xảy ra khi xác nhận nhận hàng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xác nhận nhận hàng');
        });
    }
}
</script>
@endpush
