@extends('client.layouts.app')

@section('title', 'Chi tiết đơn hàng - Techvicom')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen py-12">
    <div class="techvicom-container">
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
                            <p><span class="font-medium">Phương thức thanh toán:</span> {{ getPaymentMethodName($order->payment_method) }}</p>
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
                                 @if(isset($item->product) && $item->product && $item->product->type === 'simple' && $item->product->thumbnail)
                                     <img src="{{ asset('storage/' . ltrim($item->product->thumbnail, '/')) }}" alt="{{ $item->name_product ?? $item->product->name }}" class="w-full h-full object-cover">
                                 @elseif($item->productVariant && $item->productVariant->image)
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

            <!-- Order Actions -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Thao tác đơn hàng</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                    <!-- Hủy đơn hàng - Hiển thị khi đơn hàng pending và chưa thanh toán -->
                @if($order->status === 'pending' && $order->payment_status === 'pending')
                    @php
                        // Kiểm tra xem đã có yêu cầu hủy chưa
                        $hasCancelRequest = \App\Models\OrderReturn::where('order_id', $order->id)
                            ->where('type', 'cancel')
                            ->whereIn('status', ['pending', 'approved'])
                            ->exists();
                    @endphp
                    
                    @if($hasCancelRequest)
                        <button disabled class="bg-gray-400 text-white font-bold py-3 px-6 rounded-xl cursor-not-allowed opacity-60">
                            <i class="fas fa-times mr-2"></i>
                            Đã yêu cầu hủy
                        </button>
                    @else
                        <button onclick="cancelOrder({{ $order->id }})" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>
                            Hủy đơn hàng
                        </button>
                    @endif
                @endif

                    <!-- Thanh toán VNPay - Hiển thị khi đơn hàng đang xử lý thanh toán -->
                    @if($order->payment_status === 'processing')
                    <button onclick="payWithVnpay({{ $order->id }})" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                        <i class="fas fa-credit-card mr-2"></i>
                        Thanh toán VNPay
                    </button>
                    @endif

                    <!-- Yêu cầu trả hàng -->
                    @if($order->status === 'delivered')
                        @php
                            // Kiểm tra xem đã có yêu cầu trả hàng chưa
                            $hasReturnRequest = \App\Models\OrderReturn::where('order_id', $order->id)
                                ->where('type', 'return')
                                ->whereIn('status', ['pending', 'approved'])
                                ->exists();
                        @endphp
                        
                        @if($hasReturnRequest)
                            <button disabled class="bg-gray-400 text-white font-bold py-3 px-6 rounded-xl cursor-not-allowed opacity-60">
                                <i class="fas fa-undo mr-2"></i>
                                Đã yêu cầu trả hàng
                            </button>
                        @else
                            <button onclick="requestReturn({{ $order->id }})" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                                <i class="fas fa-undo mr-2"></i>
                                Yêu cầu trả hàng
                            </button>
                        @endif
                    @endif

                    <!-- Xác nhận nhận hàng -->
                    @if(in_array($order->status, ['delivered', 'shipped']))
                    <button onclick="confirmReceipt({{ $order->id }})" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                        <i class="fas fa-check mr-2"></i>
                        Xác nhận nhận hàng
                    </button>
                    @endif

                    <!-- Tải hóa đơn -->
                    <button onclick="downloadInvoice({{ $order->id }})" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                        <i class="fas fa-download mr-2"></i>
                        Tải hóa đơn
                    </button>
                </div>

                <!-- Thông báo trạng thái -->
                <div id="actionMessage" class="mt-4 hidden">
                    <div class="p-4 rounded-lg">
                        <p id="actionMessageText"></p>
                    </div>
                </div>

                <!-- Thông báo yêu cầu hủy đơn hàng -->
                @if($order->status === 'pending' && $order->payment_status === 'pending')
                    @php
                        $cancelRequest = \App\Models\OrderReturn::where('order_id', $order->id)
                            ->where('type', 'cancel')
                            ->whereIn('status', ['pending', 'approved'])
                            ->first();
                    @endphp
                    
                    @if($cancelRequest)
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-yellow-600 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-yellow-800">Yêu cầu hủy đơn hàng</h4>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        <strong>Lý do:</strong> {{ $cancelRequest->reason }}
                                        @if($cancelRequest->client_note)
                                            <br><strong>Ghi chú:</strong> {{ $cancelRequest->client_note }}
                                        @endif
                                        <br><strong>Trạng thái:</strong> 
                                        @if($cancelRequest->status === 'pending')
                                            <span class="text-yellow-600">Đang chờ xử lý</span>
                                        @elseif($cancelRequest->status === 'approved')
                                            <span class="text-green-600">Đã được duyệt</span>
                                        @endif
                                        <br><strong>Thời gian yêu cầu:</strong> {{ $cancelRequest->requested_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Thông báo yêu cầu trả hàng -->
                @if($order->status === 'delivered')
                    @php
                        $returnRequest = \App\Models\OrderReturn::where('order_id', $order->id)
                            ->where('type', 'return')
                            ->whereIn('status', ['pending', 'approved'])
                            ->first();
                    @endphp
                    
                    @if($returnRequest)
                        <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-undo text-orange-600 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-orange-800">Yêu cầu trả hàng</h4>
                                    <p class="text-sm text-orange-700 mt-1">
                                        <strong>Lý do:</strong> {{ $returnRequest->reason }}
                                        @if($returnRequest->client_note)
                                            <br><strong>Ghi chú:</strong> {{ $returnRequest->client_note }}
                                        @endif
                                        <br><strong>Trạng thái:</strong> 
                                        @if($returnRequest->status === 'pending')
                                            <span class="text-orange-600">Đang chờ xử lý</span>
                                        @elseif($returnRequest->status === 'approved')
                                            <span class="text-green-600">Đã được duyệt</span>
                                        @endif
                                        <br><strong>Thời gian yêu cầu:</strong> {{ $returnRequest->requested_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('client.invoice.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay lại
                    </a>
                    
                    <a href="{{ route('client.contacts.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300">
                        <i class="fas fa-headset mr-2"></i>
                        Liên hệ hỗ trợ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chọn lý do trả hàng -->
<div id="returnReasonModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" onclick="hideReturnReasonModal()">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto shadow-2xl transform transition-all" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Chọn lý do trả hàng</h3>
            <button onclick="hideReturnReasonModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mb-4">
            <label for="returnReason" class="block text-sm font-medium text-gray-700 mb-2">
                Lý do trả hàng <span class="text-red-500">*</span>
            </label>
            <select id="returnReason" onchange="toggleCustomReturnReason()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">-- Chọn lý do trả hàng --</option>
                <option value="Sản phẩm bị lỗi">Sản phẩm bị lỗi</option>
                <option value="Sản phẩm không đúng mô tả">Sản phẩm không đúng mô tả</option>
                <option value="Sản phẩm bị hỏng khi vận chuyển">Sản phẩm bị hỏng khi vận chuyển</option>
                <option value="Kích thước không phù hợp">Kích thước không phù hợp</option>
                <option value="Màu sắc không đúng">Màu sắc không đúng</option>
                <option value="Chất lượng không như mong đợi">Chất lượng không như mong đợi</option>
                <option value="Đổi ý không muốn mua">Đổi ý không muốn mua</option>
                <option value="Nhận nhầm sản phẩm">Nhận nhầm sản phẩm</option>
                <option value="custom">Lý do khác</option>
            </select>
        </div>
        
        <div id="customReturnReasonDiv" class="mb-4 hidden">
            <label for="customReturnReason" class="block text-sm font-medium text-gray-700 mb-2">
                Nhập lý do trả hàng <span class="text-red-500">*</span>
            </label>
            <textarea id="customReturnReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Vui lòng nhập lý do trả hàng..."></textarea>
        </div>
        
        <div class="flex justify-end space-x-3">
            <button onclick="hideReturnReasonModal()" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                Hủy
            </button>
            <button onclick="confirmReturnRequest()" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">
                Xác nhận trả hàng
            </button>
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

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.bg-white {
    animation: fadeInUp 0.6s ease-out;
}

/* Hover effects */
.bg-white:hover {
    transform: translateY(-2px);
}

/* Modal styles */
#cancelReasonModal, #returnReasonModal {
    backdrop-filter: blur(4px);
}

#cancelReasonModal .bg-white, #returnReasonModal .bg-white {
    animation: modalFadeIn 0.3s ease-out;
    max-height: 90vh;
    overflow-y: auto;
}

/* Responsive modal */
@media (max-width: 640px) {
    #cancelReasonModal .bg-white, #returnReasonModal .bg-white {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
}
</style>

<script>
// Hủy đơn hàng
function cancelOrder(orderId) {
    // Hiển thị modal chọn lý do hủy
    showCancelReasonModal(orderId);
}

// Hiển thị modal chọn lý do hủy
function showCancelReasonModal(orderId) {
    const modal = document.getElementById('cancelReasonModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.setAttribute('data-order-id', orderId);
        // Đảm bảo modal ở giữa màn hình
        modal.scrollTop = 0;
        document.body.style.overflow = 'hidden'; // Ngăn scroll background
    }
}

// Xác nhận hủy đơn hàng với lý do
function confirmCancelOrder() {
    const modal = document.getElementById('cancelReasonModal');
    const orderId = modal.getAttribute('data-order-id');
    const reasonSelect = document.getElementById('cancelReason');
    const customReason = document.getElementById('customCancelReason');
    
    let reason = reasonSelect.value;
    let clientNote = '';
    
    if (reason === 'custom') {
        reason = customReason.value.trim();
        if (!reason) {
            alert('Vui lòng nhập lý do hủy đơn hàng');
            return;
        }
    } else if (reason === '') {
        alert('Vui lòng chọn lý do hủy đơn hàng');
        return;
    }
    
    if (reason === 'other') {
        clientNote = prompt('Vui lòng mô tả chi tiết lý do hủy:') || '';
    }

    // Ẩn modal
    hideCancelReasonModal();

    fetch(`/invoice/order/${orderId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            cancel_reason: reason,
            client_note: clientNote
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            // Cập nhật trạng thái nút ngay lập tức
            updateCancelButtonStatus();
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Có lỗi xảy ra khi hủy đơn hàng', 'error');
    });
}

// Cập nhật trạng thái nút hủy đơn hàng
function updateCancelButtonStatus() {
    const cancelButton = document.querySelector('button[onclick^="cancelOrder"]');
    if (cancelButton) {
        cancelButton.disabled = true;
        cancelButton.className = 'bg-gray-400 text-white font-bold py-3 px-6 rounded-xl cursor-not-allowed opacity-60';
        cancelButton.innerHTML = '<i class="fas fa-times mr-2"></i>Đã yêu cầu hủy';
        cancelButton.removeAttribute('onclick');
    }
}

// Ẩn modal chọn lý do hủy
function hideCancelReasonModal() {
    const modal = document.getElementById('cancelReasonModal');
    if (modal) {
        modal.style.display = 'none';
        // Reset form
        document.getElementById('cancelReason').value = '';
        document.getElementById('customCancelReason').value = '';
        document.getElementById('customReasonDiv').style.display = 'none';
        // Khôi phục scroll background
        document.body.style.overflow = '';
    }
}

// Thanh toán VNPay
function payWithVnpay(orderId) {
    if (!confirm('Bạn có chắc chắn muốn thanh toán qua VNPay cho đơn hàng này?')) {
        return;
    }

    fetch(`/invoice/order/${orderId}/pay-vnpay`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            // Chuyển hướng đến trang thanh toán VNPay
            if (data.payment_url) {
                setTimeout(() => {
                    window.location.href = data.payment_url;
                }, 1500);
            }
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Có lỗi xảy ra khi tạo thanh toán VNPay', 'error');
    });
}



// Yêu cầu trả hàng
function requestReturn(orderId) {
    // Hiển thị modal chọn lý do trả hàng
    showReturnReasonModal(orderId);
}

// Hiển thị modal chọn lý do trả hàng
function showReturnReasonModal(orderId) {
    const modal = document.getElementById('returnReasonModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.setAttribute('data-order-id', orderId);
        // Đảm bảo modal ở giữa màn hình
        modal.scrollTop = 0;
        document.body.style.overflow = 'hidden'; // Ngăn scroll background
    }
}

// Xác nhận yêu cầu trả hàng với lý do
function confirmReturnRequest() {
    const modal = document.getElementById('returnReasonModal');
    const orderId = modal.getAttribute('data-order-id');
    const reasonSelect = document.getElementById('returnReason');
    const customReason = document.getElementById('customReturnReason');
    
    let reason = reasonSelect.value;
    let clientNote = '';
    
    if (reason === 'custom') {
        reason = customReason.value.trim();
        if (!reason) {
            alert('Vui lòng nhập lý do trả hàng');
            return;
        }
    } else if (reason === '') {
        alert('Vui lòng chọn lý do trả hàng');
        return;
    }
    
    if (reason === 'other') {
        clientNote = prompt('Vui lòng mô tả chi tiết lý do trả hàng:') || '';
    }

    // Ẩn modal
    hideReturnReasonModal();

    fetch(`/invoice/order/${orderId}/request-return`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            return_reason: reason,
            client_note: clientNote
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            // Cập nhật trạng thái nút ngay lập tức
            updateReturnButtonStatus();
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Có lỗi xảy ra khi yêu cầu trả hàng', 'error');
    });
}

// Ẩn modal chọn lý do trả hàng
function hideReturnReasonModal() {
    const modal = document.getElementById('returnReasonModal');
    if (modal) {
        modal.style.display = 'none';
        // Reset form
        document.getElementById('returnReason').value = '';
        document.getElementById('customReturnReason').value = '';
        document.getElementById('customReturnReasonDiv').style.display = 'none';
        // Khôi phục scroll background
        document.body.style.overflow = '';
    }
}

// Cập nhật trạng thái nút trả hàng
function updateReturnButtonStatus() {
    const returnButton = document.querySelector('button[onclick^="requestReturn"]');
    if (returnButton) {
        returnButton.disabled = true;
        returnButton.className = 'bg-gray-400 text-white font-bold py-3 px-6 rounded-xl cursor-not-allowed opacity-60';
        returnButton.innerHTML = '<i class="fas fa-undo mr-2"></i>Đã yêu cầu trả hàng';
        returnButton.removeAttribute('onclick');
    }
}

// Xác nhận nhận hàng
function confirmReceipt(orderId) {
    if (!confirm('Bạn có chắc chắn đã nhận hàng?')) {
        return;
    }

    fetch(`/invoice/order/${orderId}/confirm-receipt`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Có lỗi xảy ra khi xác nhận nhận hàng', 'error');
    });
}

// Tải hóa đơn
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

// Hiển thị thông báo
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

// Xử lý hiển thị/ẩn lý do tùy chỉnh
function toggleCustomReason() {
    const reasonSelect = document.getElementById('cancelReason');
    const customDiv = document.getElementById('customReasonDiv');
    
    if (reasonSelect.value === 'custom') {
        customDiv.style.display = 'block';
    } else {
        customDiv.style.display = 'none';
    }
}

// Xử lý hiển thị/ẩn lý do tùy chỉnh cho trả hàng
function toggleCustomReturnReason() {
    const reasonSelect = document.getElementById('returnReason');
    const customDiv = document.getElementById('customReturnReasonDiv');
    
    if (reasonSelect.value === 'custom') {
        customDiv.style.display = 'block';
    } else {
        customDiv.style.display = 'none';
    }
}
</script>

<!-- Modal chọn lý do hủy đơn hàng -->
<div id="cancelReasonModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" onclick="hideCancelReasonModal()">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto shadow-2xl transform transition-all" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Chọn lý do hủy đơn hàng</h3>
            <button onclick="hideCancelReasonModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mb-4">
            <label for="cancelReason" class="block text-sm font-medium text-gray-700 mb-2">
                Lý do hủy đơn hàng <span class="text-red-500">*</span>
            </label>
            <select id="cancelReason" onchange="toggleCustomReason()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">-- Chọn lý do hủy --</option>
                <option value="Đổi ý không muốn mua">Đổi ý không muốn mua</option>
                <option value="Tìm thấy sản phẩm rẻ hơn">Tìm thấy sản phẩm rẻ hơn</option>
                <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                <option value="Đặt nhầm số lượng">Đặt nhầm số lượng</option>
                <option value="Đặt nhầm địa chỉ giao hàng">Đặt nhầm địa chỉ giao hàng</option>
                <option value="Thời gian giao hàng quá lâu">Thời gian giao hàng quá lâu</option>
                <option value="Không còn nhu cầu">Không còn nhu cầu</option>
                <option value="custom">Lý do khác</option>
            </select>
        </div>
        
        <div id="customReasonDiv" class="mb-4 hidden">
            <label for="customCancelReason" class="block text-sm font-medium text-gray-700 mb-2">
                Nhập lý do hủy <span class="text-red-500">*</span>
            </label>
            <textarea id="customCancelReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Vui lòng nhập lý do hủy đơn hàng..."></textarea>
        </div>
        
        <div class="flex justify-end space-x-3">
            <button onclick="hideCancelReasonModal()" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                Hủy
            </button>
            <button onclick="confirmCancelOrder()" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                Xác nhận hủy
            </button>
        </div>
    </div>
</div>
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

// Helper function để lấy tên phương thức thanh toán
function getPaymentMethodName($method) {
    $methods = [
        'cod' => 'Thanh toán khi nhận hàng',
        'credit_card' => 'Thẻ tín dụng',
        'bank_transfer' => 'Chuyển khoản ngân hàng',
        'vnpay' => 'VNPay'
    ];
    return $methods[$method] ?? $method;
}
@endphp
