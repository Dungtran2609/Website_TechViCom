@extends('client.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@push('styles')
<style>
    .order-detail-header {
        background: linear-gradient(135deg, #ff6c2f 0%, #e55a28 100%);
        position: relative;
        overflow: hidden;
    }
    
    .order-detail-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .status-badge {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .status-badge:hover::before {
        left: 100%;
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
        width: 3px;
        background: linear-gradient(180deg, #ff6c2f 0%, #e55a28 100%);
        border-radius: 2px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 108, 47, 0.1);
    }
    
    .timeline-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.75rem;
        top: 1.5rem;
        width: 1.25rem;
        height: 1.25rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 0 0 3px #e5e7eb, 0 4px 12px rgba(16, 185, 129, 0.3);
        transition: all 0.3s ease;
    }
    
    .timeline-item.current::before {
        background: linear-gradient(135deg, #ff6c2f 0%, #e55a28 100%);
        box-shadow: 0 0 0 3px #ff6c2f, 0 0 0 6px white, 0 0 0 8px #e5e7eb, 0 4px 12px rgba(255, 108, 47, 0.4);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 3px #ff6c2f, 0 0 0 6px white, 0 0 0 8px #e5e7eb, 0 4px 12px rgba(255, 108, 47, 0.4); }
        50% { box-shadow: 0 0 0 3px #ff6c2f, 0 0 0 6px white, 0 0 0 8px #e5e7eb, 0 4px 20px rgba(255, 108, 47, 0.6); }
        100% { box-shadow: 0 0 0 3px #ff6c2f, 0 0 0 6px white, 0 0 0 8px #e5e7eb, 0 4px 12px rgba(255, 108, 47, 0.4); }
    }
    
    .timeline-item.pending::before {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        box-shadow: 0 0 0 3px #6b7280, 0 4px 12px rgba(107, 114, 128, 0.3);
    }
    
    .product-item {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        overflow: hidden;
        background: white;
    }
    
    .product-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        border-color: #ff6c2f;
    }
    
    .product-image {
        transition: all 0.3s ease;
    }
    
    .product-item:hover .product-image {
        transform: scale(1.05);
    }
    
    .summary-card {
        background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .action-button {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        border: none;
        padding: 0.75rem 1.5rem;
    }
    
    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #ff6c2f 0%, #e55a28 100%);
        color: white;
    }
    
    .btn-danger-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .btn-success-custom {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .alert-custom {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 1.25rem;
    }
    
    .alert-info-custom {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }
    
    .alert-warning-custom {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    
    .alert-success-custom {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
    
    .alert-danger-custom {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }
    
    @media (max-width: 768px) {
        .order-timeline {
            padding-left: 1.5rem;
        }
        
        .timeline-item::before {
            left: -1.5rem;
        }
        
        .order-detail-header {
            padding: 1.5rem !important;
        }
        
        .status-badge {
            font-size: 0.875rem !important;
            padding: 0.5rem 1rem !important;
        }
    }
</style>
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="bg-gray-50 min-h-screen py-8">
    <div class="techvicom-container">
        <!-- Header -->
        <div class="order-detail-header rounded-2xl p-8 text-white mb-8">
            <div class="d-flex align-items-center justify-content-between position-relative">
                <div>
                    <div class="d-flex align-items-center mb-4">
                        <a href="{{ route('accounts.orders') }}" 
                           class="text-white/80 hover:text-white text-decoration-none me-4 transition-all duration-300 hover:scale-110">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <h1 class="text-3xl font-bold mb-0">Chi tiết đơn hàng #{{ $order->random_code ?? $order->code ?? ('DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)) }}</h1>
                    </div>
                    <p class="text-white/90 mb-0 text-lg">
                        <i class="fas fa-calendar-alt me-2"></i>
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
                    <span class="status-badge badge {{ $config['class'] }} fs-6 px-4 py-3 rounded-pill">
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
                    <div class="mt-3">
                        <span class="status-badge badge {{ $paymentConfig['class'] }} fs-6 px-4 py-3 rounded-pill">
                            <i class="fas fa-{{ $paymentConfig['icon'] }} me-2"></i>
                            {{ $paymentConfig['text'] }}
                        </span>
                    </div>
                    
                    @if($order->status === 'returned' && $orderReturn && $orderReturn->status === 'pending')
                        <span class="status-badge badge bg-info ms-2 mt-2 rounded-pill">Chờ admin xác nhận trả hàng</span>
                    @endif
                    @php
                        $cancelRequest = $order->returns()->where('type', 'cancel')->whereIn('status', ['pending', 'approved'])->first();
                    @endphp
                    @if($cancelRequest)
                        @if($cancelRequest->status === 'pending')
                            <span class="status-badge badge bg-warning text-dark ms-2 mt-2 rounded-pill">Chờ admin xác nhận hủy đơn hàng</span>
                        @elseif($cancelRequest->status === 'approved')
                            <span class="status-badge badge bg-success ms-2 mt-2 rounded-pill">Yêu cầu hủy đã được phê duyệt</span>
                        @elseif($cancelRequest->status === 'rejected')
                            <span class="status-badge badge bg-danger ms-2 mt-2 rounded-pill">Yêu cầu hủy đã bị từ chối</span>
                        @endif
                    @endif
                    
                    @php
                        $returnRequest = $order->returns()->where('type', 'return')->whereIn('status', ['pending', 'approved', 'rejected'])->first();
                    @endphp
                    @if($returnRequest)
                        @if($returnRequest->status === 'pending')
                            <span class="status-badge badge bg-warning text-dark ms-2 mt-2 rounded-pill">Chờ admin xác nhận trả hàng</span>
                        @elseif($returnRequest->status === 'approved')
                            <span class="status-badge badge bg-success ms-2 mt-2 rounded-pill">Yêu cầu trả hàng đã được phê duyệt</span>
                        @elseif($returnRequest->status === 'rejected')
                            <span class="status-badge badge bg-danger ms-2 mt-2 rounded-pill">Yêu cầu trả hàng đã bị từ chối</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Thông báo cho đơn hàng VNPay -->
        @if($order->status === 'pending' && $order->payment_method === 'bank_transfer')
            <div class="alert alert-warning-custom alert-custom mb-4">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-triangle me-3 mt-1 text-xl"></i>
                    <div>
                        <strong>Lưu ý quan trọng:</strong><br>
                        Đơn hàng thanh toán VNPay không thể hủy. Nếu bạn cần hỗ trợ, vui lòng liên hệ bộ phận hỗ trợ để được hỗ trợ!
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Order Timeline -->
            <div class="col-lg-4 mb-6">
                @php
                    $orderReturn = $order->returns()->latest()->first();
                @endphp
                @if($orderReturn && in_array($order->status, ['cancelled', 'returned']) && $orderReturn->admin_note)
                    <div class="alert alert-info-custom alert-custom mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle me-3 mt-1 text-xl"></i>
                            <div>
                                <strong>Ghi chú từ admin:</strong><br>
                                {{ $orderReturn->admin_note }}
                            </div>
                        </div>
                    </div>
                @endif
                
                @php
                    $cancelRequest = $order->returns()->where('type', 'cancel')->whereIn('status', ['pending', 'approved', 'rejected'])->first();
                @endphp
                @if($cancelRequest)
                    <div class="alert alert-warning-custom alert-custom mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle me-3 mt-1 text-xl"></i>
                            <div>
                                <strong>Yêu cầu hủy đơn hàng:</strong><br>
                                <strong>Trạng thái:</strong> 
                                @if($cancelRequest->status === 'pending')
                                    <span class="status-badge badge bg-warning text-dark">Đang chờ admin xử lý</span>
                                @elseif($cancelRequest->status === 'approved')
                                    <span class="status-badge badge bg-success">Đã được phê duyệt</span>
                                @elseif($cancelRequest->status === 'rejected')
                                    <span class="status-badge badge bg-danger">Đã bị từ chối</span>
                                @endif
                                <br>
                                <strong>Lý do:</strong> {{ $cancelRequest->client_note }}
                                @if($cancelRequest->admin_note)
                                    <br>
                                    <strong>Phản hồi từ admin:</strong> {{ $cancelRequest->admin_note }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                @php
                    $returnRequest = $order->returns()->where('type', 'return')->whereIn('status', ['pending', 'approved', 'rejected'])->first();
                @endphp
                @if($returnRequest)
                    <div class="alert alert-info-custom alert-custom mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-undo me-3 mt-1 text-xl"></i>
                            <div class="w-100">
                                <strong>Yêu cầu trả hàng:</strong><br>
                                <strong>Trạng thái:</strong> 
                                @if($returnRequest->status === 'pending')
                                    <span class="status-badge badge bg-warning text-dark">Đang chờ admin xử lý</span>
                                @elseif($returnRequest->status === 'approved')
                                    <span class="status-badge badge bg-success">Đã được phê duyệt</span>
                                @elseif($returnRequest->status === 'rejected')
                                    <span class="status-badge badge bg-danger">Đã bị từ chối</span>
                                @endif
                                <br>
                                <strong>Lý do:</strong> {{ $returnRequest->client_note }}
                                @if($returnRequest->admin_note)
                                    <br>
                                    <strong>Phản hồi từ admin:</strong> {{ $returnRequest->admin_note }}
                                @endif
                                
                                <!-- Hiển thị ảnh chứng minh từ admin -->
                                @if($returnRequest->admin_proof_images && is_array($returnRequest->admin_proof_images) && count($returnRequest->admin_proof_images) > 0)
                                    <div class="mt-3">
                                        <strong>Ảnh chứng minh từ admin:</strong>
                                        <div class="row mt-2">
                                            @foreach($returnRequest->admin_proof_images as $image)
                                                <div class="col-4 mb-2">
                                                    <img src="{{ asset('storage/' . $image) }}" 
                                                         alt="Chứng minh admin" 
                                                         class="img-fluid rounded border" 
                                                         style="max-height: 100px; object-fit: cover; cursor: pointer;"
                                                         onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Hiển thị ảnh chứng minh từ client -->
                                @if($returnRequest->images && is_array($returnRequest->images) && count($returnRequest->images) > 0)
                                    <div class="mt-3">
                                        <strong>Ảnh chứng minh của bạn:</strong>
                                        <div class="row mt-2">
                                            @foreach($returnRequest->images as $productId => $productImages)
                                                @if(is_array($productImages))
                                                    @foreach($productImages as $image)
                                                        <div class="col-4 mb-2">
                                                            <img src="{{ asset('storage/' . $image) }}" 
                                                                 alt="Chứng minh client" 
                                                                 class="img-fluid rounded border" 
                                                                 style="max-height: 100px; object-fit: cover; cursor: pointer;"
                                                                 onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Hiển thị video chứng minh từ client -->
                                @if($returnRequest->video)
                                    <div class="mt-3">
                                        <strong>Video chứng minh của bạn:</strong>
                                        <div class="mt-2">
                                            <video controls class="w-100 rounded border" style="max-height: 200px;">
                                                <source src="{{ asset('storage/' . $returnRequest->video) }}" type="video/mp4">
                                                Trình duyệt của bạn không hỗ trợ video.
                                            </video>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
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
                                @if($order->payment_method === 'bank_transfer')
                                    <button class="btn btn-outline-secondary" disabled title="Đơn hàng VNPay không thể hủy">
                                        <i class="fas fa-times me-2"></i>
                                        Không thể hủy (VNPay)
                                    </button>
                                @else
                                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                        <i class="fas fa-times me-2"></i>
                                        Hủy đơn hàng
                                    </button>
                                @endif
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
        @php
            $hasCancelRequestForModal = $order->returns()->where('type', 'cancel')->exists();
        @endphp
        @if($order->status === 'pending' && !$hasCancelRequestForModal && $order->payment_method !== 'bank_transfer')
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
                                <div class="alert alert-info-custom alert-custom mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle me-3 mt-1 text-lg"></i>
                                        <div>
                                            <strong>Lưu ý:</strong> Bạn phải chọn lý do trước khi có thể xác nhận hủy đơn hàng.
                                        </div>
                                    </div>
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
            <div class="modal-dialog modal-lg">
                <form id="returnOrderForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="returnOrderModalLabel">Yêu cầu đổi/trả hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Chọn sản phẩm cần đổi/trả -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Chọn sản phẩm cần đổi/trả: <span class="text-danger">*</span></label>
                                <div class="alert alert-info-custom alert-custom mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle me-3 mt-1 text-lg"></i>
                                        <div>
                                            <strong>Lưu ý:</strong> Vui lòng chọn sản phẩm cụ thể mà bạn muốn đổi/trả.
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach($order->orderItems as $item)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-2" id="productCard{{ $item->id }}">
                                            <div class="card-body p-3">
                                                <div class="form-check">
                                                    <input class="form-check-input product-checkbox" type="checkbox" 
                                                           name="selected_products[]" 
                                                           value="{{ $item->id }}" 
                                                           id="product{{ $item->id }}">
                                                    <label class="form-check-label w-100" for="product{{ $item->id }}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-3">
                                                                @if($item->productVariant && $item->productVariant->image)
                                                                    <img src="{{ asset('storage/' . ltrim($item->productVariant->image, '/')) }}" 
                                                                         alt="{{ $item->name_product }}" 
                                                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                                @elseif($item->image_product)
                                                                    <img src="{{ asset('storage/' . ltrim($item->image_product, '/')) }}" 
                                                                         alt="{{ $item->name_product }}" 
                                                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                                @elseif($item->productVariant && $item->productVariant->product && $item->productVariant->product->thumbnail)
                                                                    <img src="{{ asset('storage/' . ltrim($item->productVariant->product->thumbnail, '/')) }}" 
                                                                         alt="{{ $item->name_product }}" 
                                                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                                         style="width: 60px; height: 60px;">
                                                                        <i class="fas fa-image text-muted"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1 fw-medium">{{ $item->name_product }}</h6>
                                                                <p class="mb-1 text-muted small">
                                                                    Số lượng: {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}₫
                                                                </p>
                                                                @if($item->productVariant && $item->productVariant->attributeValues->count() > 0)
                                                                <p class="mb-0 text-muted small">
                                                                    @foreach($item->productVariant->attributeValues as $attrValue)
                                                                        {{ $attrValue->attribute->name }}: {{ $attrValue->value }}@if (!$loop->last), @endif
                                                                    @endforeach
                                                                </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Vui lòng chọn lý do trả hàng: <span class="text-danger">*</span></label>
                                <div class="alert alert-info-custom alert-custom mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle me-3 mt-1 text-lg"></i>
                                        <div>
                                            <strong>Lưu ý:</strong> Bạn phải chọn lý do trước khi có thể gửi yêu cầu trả hàng.
                                        </div>
                                    </div>
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
                                
                                <!-- Upload ảnh và video cho từng sản phẩm -->
                                <div class="mt-4" id="productImagesSection" style="display: none;">
                                    <label class="form-label fw-bold">Hình ảnh chứng minh cho từng sản phẩm: <span class="text-danger">*</span></label>
                                    <div class="alert alert-warning-custom alert-custom mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-exclamation-triangle me-3 mt-1 text-lg"></i>
                                            <div>
                                                <strong>Bắt buộc:</strong> Vui lòng chụp ảnh cho từng sản phẩm để chứng minh lý do đổi/trả.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @foreach($order->orderItems as $item)
                                    <div class="product-images-container mb-3" id="productImages{{ $item->id }}" style="display: none;">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">{{ $item->name_product }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <input type="file" class="form-control product-images-input" 
                                                       name="product_images[{{ $item->id }}][]" 
                                                       accept="image/*" multiple 
                                                       data-product-id="{{ $item->id }}">
                                                <div class="form-text">Có thể chọn nhiều ảnh cho sản phẩm này</div>
                                                <div class="product-image-preview mt-2 d-flex flex-wrap gap-2" id="productImagePreview{{ $item->id }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-3">
                                    <label class="form-label fw-bold">Video chứng minh chung: <span class="text-danger">*</span></label>
                                    <div class="alert alert-warning-custom alert-custom mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-video me-3 mt-1 text-lg"></i>
                                            <div>
                                                <strong>Bắt buộc:</strong> Vui lòng quay video ngắn để chứng minh lý do đổi/trả.
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control" id="returnVideo" name="return_video" accept="video/*" required>
                                    <div class="form-text">Video ngắn (MP4, AVI, MOV) - Tối đa 50MB</div>
                                    <div id="videoPreview" class="mt-2"></div>
                                </div>
                                
                                <div class="mt-3">
                                    <label for="returnNote" class="form-label">Ghi chú thêm:</label>
                                    <textarea class="form-control" id="returnNote" name="client_note" rows="3" placeholder="Mô tả chi tiết về vấn đề gặp phải..."></textarea>
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

        <!-- Modal hiển thị minh chứng trước khi xác nhận đổi trả -->
        <div class="modal fade" id="proofConfirmationModal" tabindex="-1" aria-labelledby="proofConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="proofConfirmationModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Xác nhận minh chứng đổi/trả hàng
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Vui lòng kiểm tra lại tất cả minh chứng trước khi gửi yêu cầu đổi/trả hàng!</strong>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-list me-2"></i>
                                    Thông tin yêu cầu
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Lý do đổi/trả:</label>
                                    <div id="proofReason" class="form-control-plaintext border rounded p-2 bg-light"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ghi chú:</label>
                                    <div id="proofNote" class="form-control-plaintext border rounded p-2 bg-light"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Sản phẩm được chọn:</label>
                                    <div id="proofProducts" class="form-control-plaintext border rounded p-2 bg-light"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="fas fa-images me-2"></i>
                                    Minh chứng đã upload
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ảnh chứng minh:</label>
                                    <div id="proofImages" class="border rounded p-2 bg-light"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Video chứng minh:</label>
                                    <div id="proofVideo" class="border rounded p-2 bg-light"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Lưu ý:</strong> Sau khi gửi yêu cầu, bạn không thể chỉnh sửa thông tin. Hãy kiểm tra kỹ trước khi xác nhận!
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-edit me-2"></i>
                            Chỉnh sửa
                        </button>
                        <button type="button" class="btn btn-success" id="btn-final-confirm">
                            <i class="fas fa-check me-2"></i>
                            Xác nhận và gửi yêu cầu
                        </button>
                    </div>
                </div>
            </div>
        </div>
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

.product-checkbox:checked + .form-check-label .card {
    border-color: #007bff !important;
    background-color: #f8f9ff;
}

.product-checkbox:checked + .form-check-label .card .card-body {
    background-color: #f8f9ff;
}

.product-images-container .card {
    transition: all 0.3s ease;
}

.product-images-container .card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
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

    // Xử lý chọn sản phẩm
    var productCheckboxes = document.querySelectorAll('.product-checkbox');
    var productImagesSection = document.getElementById('productImagesSection');
    
    productCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var productId = this.value;
            var productImagesContainer = document.getElementById('productImages' + productId);
            
            if (this.checked) {
                productImagesContainer.style.display = 'block';
                productImagesSection.style.display = 'block';
            } else {
                productImagesContainer.style.display = 'none';
            }
            
            // Kiểm tra xem có sản phẩm nào được chọn không
            var checkedProducts = document.querySelectorAll('.product-checkbox:checked');
            if (checkedProducts.length === 0) {
                productImagesSection.style.display = 'none';
            }
        });
    });
    
    // Xử lý preview ảnh cho từng sản phẩm
    var productImagesInputs = document.querySelectorAll('.product-images-input');
    productImagesInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            var productId = this.getAttribute('data-product-id');
            var previewContainer = document.getElementById('productImagePreview' + productId);
            previewContainer.innerHTML = '';
            
            var files = this.files;
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (file.type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '80px';
                        img.style.height = '80px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '6px';
                        img.style.border = '2px solid #ddd';
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    });
    
    // Xử lý preview video
    var returnVideo = document.getElementById('returnVideo');
    var videoPreview = document.getElementById('videoPreview');
    if (returnVideo) {
        returnVideo.addEventListener('change', function() {
            videoPreview.innerHTML = '';
            var file = this.files[0];
            
            if (file && file.type.startsWith('video/')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var video = document.createElement('video');
                    video.src = e.target.result;
                    video.controls = true;
                    video.style.width = '100%';
                    video.style.maxWidth = '400px';
                    video.style.borderRadius = '8px';
                    videoPreview.appendChild(video);
                };
                reader.readAsDataURL(file);
            }
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
            
            // Kiểm tra chọn sản phẩm
            var selectedProducts = document.querySelectorAll('.product-checkbox:checked');
            if (selectedProducts.length === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm để đổi/trả');
                return;
            }
            
            // Kiểm tra upload ảnh cho từng sản phẩm được chọn
            var hasImages = true;
            selectedProducts.forEach(function(checkbox) {
                var productId = checkbox.value;
                var imageInput = document.querySelector('input[name="product_images[' + productId + '][]"]');
                if (!imageInput.files || imageInput.files.length === 0) {
                    alert('Vui lòng chọn ảnh chứng minh cho sản phẩm: ' + checkbox.closest('.card').querySelector('h6').textContent);
                    imageInput.focus();
                    hasImages = false;
                    return;
                }
            });
            
            if (!hasImages) {
                return;
            }
            
            // Kiểm tra upload video
            var returnVideo = document.getElementById('returnVideo');
            if (!returnVideo.files || returnVideo.files.length === 0) {
                alert('Vui lòng chọn video chứng minh');
                returnVideo.focus();
                return;
            }
            
            // Kiểm tra kích thước video (50MB)
            var videoFile = returnVideo.files[0];
            if (videoFile.size > 50 * 1024 * 1024) {
                alert('Video quá lớn. Vui lòng chọn video nhỏ hơn 50MB');
                return;
            }
            
            // Hiển thị xác nhận cuối cùng
            if (confirm('Bạn có chắc chắn muốn gửi yêu cầu trả hàng này không?\n\nLý do: ' + clientNote + '\n\nYêu cầu sẽ được gửi đến admin để xử lý!')) {
                var orderId = {{ $order->id }};
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                var formData = new FormData();
                formData.append('cancel_reason', clientNote);
                
                // Thêm sản phẩm được chọn
                selectedProducts.forEach(function(checkbox) {
                    formData.append('selected_products[]', checkbox.value);
                });
                
                // Thêm ảnh cho từng sản phẩm
                selectedProducts.forEach(function(checkbox) {
                    var productId = checkbox.value;
                    var imageInput = document.querySelector('input[name="product_images[' + productId + '][]"]');
                    for (var i = 0; i < imageInput.files.length; i++) {
                        formData.append('product_images[' + productId + '][]', imageInput.files[i]);
                    }
                });
                
                // Thêm video
                formData.append('return_video', returnVideo.files[0]);
                
                // Thêm ghi chú
                var returnNote = document.getElementById('returnNote').value;
                formData.append('client_note', returnNote || '');
                
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

// Hàm mở modal xem ảnh to
function openImageModal(imageSrc) {
    // Tạo modal nếu chưa có
    var existingModal = document.getElementById('imageModal');
    if (!existingModal) {
        var modalHTML = `
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel">Xem ảnh</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="modalImage" src="" alt="Ảnh chứng minh" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
    
    // Cập nhật ảnh và hiển thị modal
    document.getElementById('modalImage').src = imageSrc;
    var modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endpush
