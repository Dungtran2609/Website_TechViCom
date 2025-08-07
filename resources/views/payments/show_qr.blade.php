@extends('admin.layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title">Thanh toán đơn hàng #{{ $order->id }}</h2>
                    <p class="text-muted">Sử dụng ứng dụng ngân hàng hoặc ví điện tử để quét mã VietQR bên dưới.</p>

                    <img src="{{ $qrImage }}" alt="VietQR Code" class="img-fluid my-3">

                    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }} VNĐ</p>
                    <p><strong>Nội dung:</strong> Thanh toan don hang #{{ $order->id }}</p>

                    <div id="payment-status" class="mt-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Đang chờ thanh toán...</p>
                    </div>

                    <a href="{{ $paymentLink }}" class="btn btn-link">Không quét được mã?</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const orderId = {{ $order->id }};
    const successUrl = "{{ route('payment.success', ['order_id' => $order->id]) }}";

    // Bắt đầu quá trình "hỏi dò" (Polling)
    const pollingInterval = setInterval(function () {
        fetch("{{ route('payment.check_status', '') }}/" + orderId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log("Checking status: ", data.status);
                if (data.status === 'paid') {
                    // Nếu đã thanh toán, dừng hỏi và chuyển hướng
                    clearInterval(pollingInterval);
                    window.location.href = successUrl;
                }
            })
            .catch(error => {
                // Nếu có lỗi mạng hoặc lỗi server, dừng lại để tránh spam
                console.error('Error during polling:', error);
                clearInterval(pollingInterval);
            });
    }, 5000); // Lặp lại sau mỗi 5 giây

    // Dừng polling sau 5 phút để tránh chạy vô hạn
    setTimeout(() => {
        clearInterval(pollingInterval);
    }, 300000);
});
</script>
@endpush
