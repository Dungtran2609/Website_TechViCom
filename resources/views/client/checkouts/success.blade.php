@extends('client.layouts.app')

@section('title', 'Đặt hàng thành công - Techvicom')

@section('content')
@if(session('notification'))
    <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300
        @if(session('notification.type') === 'success') bg-green-500
        @elseif(session('notification.type') === 'error') bg-red-500
        @else bg-yellow-500 @endif">
        {{ session('notification.message') }}
    </div>
@endif
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Message -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="mb-6">
                @if($order->payment_method === 'bank_transfer' && $order->payment_status === 'cancelled')
                    <i class="fas fa-times-circle text-orange-500 text-6xl mb-4"></i>
                @elseif($order->payment_method === 'bank_transfer' && $order->payment_status === 'failed')
                    <i class="fas fa-exclamation-triangle text-red-500 text-6xl mb-4"></i>
                @else
                    <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                @endif

                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    @if($order->payment_method === 'cod')
                        Đặt hàng thành công!
                    @elseif($order->payment_method === 'bank_transfer' && $order->payment_status === 'paid')
                        Thanh toán thành công!
                    @elseif($order->payment_method === 'bank_transfer' && $order->payment_status === 'cancelled')
                        Đơn hàng đã được hủy thanh toán
                    @elseif($order->payment_method === 'bank_transfer' && $order->payment_status === 'failed')
                        Thanh toán thất bại
                    @else
                        Đặt hàng thành công!
                    @endif
                </h1>

                <p class="text-gray-600">
                    @if($order->payment_method === 'cod')
                        Cảm ơn bạn đã mua hàng tại Techvicom. Chúng tôi sẽ liên hệ sớm nhất để xác nhận và giao hàng!
                    @elseif($order->payment_method === 'bank_transfer' && $order->payment_status === 'paid')
                        Cảm ơn bạn đã thanh toán thành công. Đơn hàng đang được xử lý!
                    @elseif($order->payment_method === 'bank_transfer' && $order->payment_status === 'cancelled')
                        Bạn đã hủy thanh toán. Đơn hàng vẫn được giữ lại và có thể thanh toán lại sau.
                    @elseif($order->payment_method === 'bank_transfer' && $order->payment_status === 'failed')
                        Thanh toán không thành công. Vui lòng thử lại hoặc liên hệ hỗ trợ.
                    @else
                        Cảm ơn bạn đã mua hàng tại Techvicom
                    @endif
                </p>
            </div>

            <!-- Order Info -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Mã đơn hàng:</span>
                        <span class="font-medium">#{{ $order->id }}</span>
                    </div>

                    {{-- Tạm tính: tổng giá sản phẩm --}}
                    <div class="flex justify-between py-1">
                        <span>Tạm tính:</span>
                        <span class="font-medium">{{ number_format($order->total_amount ?? 0) }}₫</span>
                    </div>

                    {{-- Giảm giá: nếu có --}}
                    @if(($order->discount_amount ?? 0) > 0)
                        <div class="flex justify-between py-1 text-green-600">
                            <span>Giảm giá:</span>
                            <span class="font-medium">-{{ number_format($order->discount_amount) }}₫</span>
                        </div>
                    @endif

                    {{-- Phí vận chuyển --}}
                    <div class="flex justify-between">
                        <span>Phí vận chuyển:</span>
                        <span class="font-medium">{{ number_format($order->shipping_fee ?? 0) }}₫</span>
                    </div>

                    {{-- Tổng cộng: tạm tính + phí ship --}}
                    <div class="flex justify-between py-2 border-t border-gray-300 mt-2">
                        <span class="font-semibold">Tổng cộng:</span>
                        <span class="font-bold text-orange-600 text-lg">{{ number_format($order->final_total ?? 0) }}₫</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Phương thức thanh toán:</span>
                        <span class="font-medium">
                            @if($order->payment_method === 'cod')
                                Thanh toán khi nhận hàng
                            @elseif($order->payment_method === 'bank_transfer')
                                Thanh toán VNPAY
                            @else
                                Thanh toán online
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Trạng thái thanh toán:</span>
                        <span class="font-medium
                            @if($order->payment_status === 'paid') text-green-600
                            @elseif($order->payment_status === 'failed') text-red-600
                            @else text-blue-600
                            @endif">
                            @if($order->payment_status === 'paid')
                                Đã thanh toán
                            @elseif($order->payment_status === 'failed')
                                Thanh toán thất bại
                            @else
                                Chưa thanh toán
                            @endif
                        </span>
                    </div>

                    @if($order->payment_method === 'bank_transfer' && $order->vnpay_transaction_id)
                        <div class="flex justify-between">
                            <span>Mã giao dịch VNPAY:</span>
                            <span class="font-medium">{{ $order->vnpay_transaction_id }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span>Trạng thái đơn hàng:</span>
                        <span class="font-medium text-blue-600">
                            @if($order->status === 'pending')
                                Đang chờ xử lý
                            @elseif($order->status === 'processing')
                                Đang xử lý
                            @elseif($order->status === 'shipped')
                                Đang vận chuyển
                            @elseif($order->status === 'delivered')
                                Đã giao hàng
                            @elseif($order->status === 'received')
                                Hoàn thành
                            @elseif($order->status === 'cancelled')
                                Đã hủy
                            @elseif($order->status === 'returned')
                                Đã trả hàng
                            @else
                                {{ $order->status }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="text-left mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">
                    Sản phẩm đã đặt ({{ $order->orderItems->count() }} sản phẩm)
                </h3>
                <div class="space-y-3">
                    @if($order->orderItems->count() > 0)
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="text-sm">
                                        <div class="font-medium">{{ $item->name_product }}</div>
                                        <div class="text-gray-500">Số lượng: {{ $item->quantity }}</div>
                                    </div>
                                </div>
                                <div class="text-sm font-medium">
                                    @php $price = $item->price ?? 0; @endphp
                                    {{ number_format($price) }}₫ x {{ $item->quantity }}
                                    = {{ number_format($price * $item->quantity) }}₫
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-gray-500">
                            <p>Không có sản phẩm nào trong đơn hàng</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col space-y-4">
                <div class="text-sm text-gray-600">
                    <p>📧 Chúng tôi đã gửi email xác nhận đến địa chỉ của bạn</p>
                    <p>📞 Hotline: 1900-xxxx (hỗ trợ 24/7)</p>
                </div>

                <div class="flex space-x-4 justify-center">
                    <a href="{{ route('home') }}" class="px-6 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        <i class="fas fa-home mr-2"></i>Về trang chủ
                    </a>

                    @if($order->payment_method === 'bank_transfer' && $order->payment_status === 'cancelled')
                        <a href="{{ route('vnpay.payment', ['order_id' => $order->id]) }}" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            <i class="fas fa-credit-card mr-2"></i>Thanh toán lại VNPAY
                        </a>
                    @endif

                    @auth
                        <a href="{{ route('accounts.orders') }}" class="px-6 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                            <i class="fas fa-list mr-2"></i>Xem đơn hàng
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
