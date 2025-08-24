@extends('client.layouts.app')

@section('title', 'Thanh toán đơn hàng #' . $order->id)

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="techvicom-container">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Thanh toán đơn hàng #{{ $order->random_code ?? ('DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)) }}
                </h1>
                <p class="text-gray-600">Vui lòng quét mã QR để thanh toán</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- QR Code Section -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-qrcode text-blue-500 me-2"></i>
                        Mã QR Thanh toán
                    </h2>
                    
                    <!-- QR Code Image -->
                    <div class="bg-white p-4 rounded-lg border-2 border-gray-200 inline-block mb-6">
                        <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" 
                             alt="QR Code" 
                             class="w-64 h-64 mx-auto">
                    </div>

                    <!-- Payment Info -->
                    <div class="space-y-3 text-left max-w-md mx-auto">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="font-semibold text-gray-700">Số tiền:</span>
                            <span class="text-xl font-bold text-red-600">{{ number_format($paymentInfo['amount']) }}₫</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="font-semibold text-gray-700">Mã đơn hàng:</span>
                            <span class="font-mono text-blue-600">{{ $paymentInfo['orderCode'] }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="font-semibold text-gray-700">Nội dung:</span>
                            <span class="text-sm text-gray-600">{{ $paymentInfo['description'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Info Section -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-university text-green-500 me-2"></i>
                    Thông tin tài khoản
                </h2>
                
                <div class="space-y-4">
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-building-columns text-green-600 me-2"></i>
                            <span class="font-semibold text-green-800">Ngân hàng</span>
                        </div>
                        <p class="text-green-700">{{ $this->getBankName($paymentInfo['bankInfo']['bankCode']) }}</p>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-credit-card text-blue-600 me-2"></i>
                            <span class="font-semibold text-blue-800">Số tài khoản</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="font-mono text-lg text-blue-700">{{ $paymentInfo['bankInfo']['accountNumber'] }}</p>
                            <button onclick="copyToClipboard('{{ $paymentInfo['bankInfo']['accountNumber'] }}')" 
                                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>

                    <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-user text-purple-600 me-2"></i>
                            <span class="font-semibold text-purple-800">Tên tài khoản</span>
                        </div>
                        <p class="text-purple-700">{{ $paymentInfo['bankInfo']['accountName'] }}</p>
                    </div>

                    <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-info-circle text-orange-600 me-2"></i>
                            <span class="font-semibold text-orange-800">Hướng dẫn</span>
                        </div>
                        <ul class="text-sm text-orange-700 space-y-2">
                            <li>• Mở ứng dụng ngân hàng trên điện thoại</li>
                            <li>• Chọn tính năng "Quét mã QR"</li>
                            <li>• Quét mã QR bên trái</li>
                            <li>• Kiểm tra thông tin và xác nhận thanh toán</li>
                            <li>• Đơn hàng sẽ được xử lý tự động</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-receipt text-gray-600 me-2"></i>
                Chi tiết đơn hàng
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $order->orderItems->count() }}</div>
                    <div class="text-gray-600">Sản phẩm</div>
                </div>
                
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($order->final_total) }}₫</div>
                    <div class="text-gray-600">Tổng tiền</div>
                </div>
                
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">{{ $order->created_at->format('d/m/Y') }}</div>
                    <div class="text-gray-600">Ngày đặt</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-8 space-x-4">
            <a href="{{ route('accounts.orders') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                <i class="fas fa-arrow-left me-2"></i>
                Quay lại danh sách
            </a>
            
            <button onclick="checkPaymentStatus()" 
                    class="inline-flex items-center px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                <i class="fas fa-sync-alt me-2"></i>
                Kiểm tra thanh toán
            </button>
        </div>
    </div>
</div>

<!-- Payment Status Modal -->
<div class="modal fade" id="paymentStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trạng thái thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="paymentStatusContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        button.classList.add('bg-green-500', 'hover:bg-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-500', 'hover:bg-green-600');
            button.classList.add('bg-blue-500', 'hover:bg-blue-600');
        }, 2000);
    });
}

function checkPaymentStatus() {
    const orderId = {{ $order->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/client/payments/${orderId}/check-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const modal = new bootstrap.Modal(document.getElementById('paymentStatusModal'));
        const content = document.getElementById('paymentStatusContent');
        
        if (data.paid) {
            content.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-success">Thanh toán thành công!</h4>
                    <p class="text-muted">Đơn hàng của bạn đã được thanh toán và đang được xử lý.</p>
                    <button onclick="window.location.reload()" class="btn btn-success">Cập nhật trang</button>
                </div>
            `;
        } else {
            content.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-clock text-warning" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-warning">Chưa thanh toán</h4>
                    <p class="text-muted">Vui lòng hoàn tất thanh toán và thử lại.</p>
                </div>
            `;
        }
        
        modal.show();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi kiểm tra trạng thái thanh toán');
    });
}

// Auto check payment status every 30 seconds
setInterval(checkPaymentStatus, 30000);
</script>
@endpush
