@extends('client.layouts.app')

@section('title', 'Đặt hàng thành công - Techvicom')

@section('content')
    <!-- Success Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="mb-6">
                    <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Đặt hàng thành công!</h1>
                    <p class="text-gray-600">Cảm ơn bạn đã mua hàng tại Techvicom</p>
                </div>

                <!-- Order Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Mã đơn hàng:</span>
                            <span class="font-medium">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tổng tiền:</span>
                            <span class="font-medium text-orange-600">{{ number_format($order->final_total) }}₫</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phương thức thanh toán:</span>
                            <span class="font-medium">
                                @if($order->payment_method === 'cod')
                                    Thanh toán khi nhận hàng
                                @else
                                    Thanh toán online
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Trạng thái:</span>
                            <span class="font-medium text-blue-600">Đang xử lý</span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="text-left mb-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Sản phẩm đã đặt</h3>
                    <div class="space-y-3">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="text-sm">
                                        <div class="font-medium">{{ $item->product->name }}</div>
                                        <div class="text-gray-500">Số lượng: {{ $item->quantity }}</div>
                                    </div>
                                </div>
                                <div class="text-sm font-medium">
                                    {{ number_format($item->total) }}₫
                                </div>
                            </div>
                        @endforeach
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
