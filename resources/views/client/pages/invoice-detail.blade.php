@extends('client.layouts.app')

@section('title', 'Chi tiết đơn hàng - Techvicom')

@php
// Helper functions moved to the top for better organization
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
        'processing' => 'bg-blue-100 text-blue-800',
        'paid' => 'bg-green-100 text-green-800',
        'failed' => 'bg-red-100 text-red-800'
    ];
    return $colors[$status] ?? 'bg-gray-100 text-gray-800';
}

function getPaymentMethodName($method) {
    $methods = [
        'cod' => 'Thanh toán khi nhận hàng',
        'credit_card' => 'Thẻ tín dụng',
        'bank_transfer' => 'Chuyển khoản ngân hàng',
        'vnpay' => 'VNPay'
    ];
    return $methods[$method] ?? $method;
}

// Ensure order data exists and has required properties
if (!isset($order) || !$order) {
    abort(404, 'Đơn hàng không tồn tại');
}
@endphp

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen py-12">
    <div class="techvicom-container">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                <i class="fas fa-file-invoice text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Chi tiết đơn hàng</h1>
            <p class="text-lg text-gray-600">Thông tin chi tiết đơn hàng #{{ 'DH' . str_pad($order->id ?? 0, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="max-w-6xl mx-auto">
            <!-- VNPay Environment Notice -->
            @if($order->payment_method === 'bank_transfer' && $order->status === 'pending')
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            💳 Thanh toán VNPay
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Đơn hàng này sử dụng thanh toán VNPay. Vui lòng hoàn tất thanh toán để xử lý đơn hàng:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>✅ Thanh toán an toàn qua cổng VNPay</li>
                                <li>✅ Hỗ trợ nhiều ngân hàng và thẻ</li>
                                <li>✅ Xác nhận thanh toán ngay lập tức</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Summary -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Thông tin đơn hàng</h2>
                    <div class="flex items-center space-x-4">
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ getStatusColor($order->status ?? 'pending') }}">
                            {{ $order->status_vietnamese ?? 'Đang chờ xử lý' }}
                        </div>
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ getPaymentStatusColor($order->payment_status ?? 'pending') }}">
                            {{ $order->payment_status_vietnamese ?? 'Đang chờ xử lý' }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Thông tin đơn hàng</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Mã đơn hàng:</span> {{ 'DH' . str_pad($order->id ?? 0, 6, '0', STR_PAD_LEFT) }}</p>
                            <p><span class="font-medium">Ngày đặt:</span> {{ isset($order->created_at) ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                            <p><span class="font-medium">Phương thức thanh toán:</span> {{ getPaymentMethodName($order->payment_method ?? 'cod') }}</p>
                            @if(isset($order->shippingMethod) && $order->shippingMethod)
                                <p><span class="font-medium">Phương thức giao hàng:</span> {{ $order->shippingMethod->name ?? 'N/A' }}</p>
                            @endif
                            @if(isset($order->guest_email) && $order->guest_email)
                                <p><span class="font-medium">Khách hàng:</span> {{ $order->guest_name ?? 'Khách vãng lai' }} ({{ $order->guest_email }})</p>
                            @elseif(isset($order->user) && $order->user)
                                <p><span class="font-medium">Khách hàng:</span> {{ $order->user->name ?? 'N/A' }} ({{ $order->user->email ?? 'N/A' }})</p>
                            @else
                                <p><span class="font-medium">Khách hàng:</span> Khách vãng lai</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Thông tin giao hàng</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Người nhận:</span> {{ $order->recipient_name ?? 'N/A' }}</p>
                            <p><span class="font-medium">Số điện thoại:</span> {{ $order->recipient_phone ?? 'N/A' }}</p>
                            <p><span class="font-medium">Địa chỉ:</span> {{ $order->recipient_address ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Tổng tiền</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Tạm tính:</span> {{ number_format($order->total_amount ?? 0) }} ₫</p>
                            <p><span class="font-medium">Phí vận chuyển:</span> {{ number_format($order->shipping_fee ?? 0) }} ₫</p>
                            @if(isset($order->discount_amount) && $order->discount_amount > 0)
                                <p><span class="font-medium">Giảm giá:</span> -{{ number_format($order->discount_amount) }} ₫</p>
                            @endif
                            <p class="text-lg font-bold text-blue-600"><span class="font-medium">Tổng cộng:</span> {{ number_format($order->final_total ?? 0) }} ₫</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Sản phẩm đã đặt</h2>
                <div class="space-y-4">
                    @if(isset($order->orderItems) && $order->orderItems->count() > 0)
                        @foreach($order->orderItems as $item)
                        <div class="border border-gray-200 rounded-xl p-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                    @if(isset($item->product) && $item->product && $item->product->type === 'simple' && $item->product->thumbnail)
                                        <img src="{{ asset('storage/' . ltrim($item->product->thumbnail, '/')) }}" alt="{{ $item->name_product ?? $item->product->name ?? 'Sản phẩm' }}" class="w-full h-full object-cover">
                                    @elseif($item->productVariant && $item->productVariant->image)
                                        <img src="{{ asset('storage/' . ltrim($item->productVariant->image, '/')) }}" alt="{{ $item->name_product ?? 'Sản phẩm' }}" class="w-full h-full object-cover">
                                    @elseif($item->image_product)
                                        <img src="{{ asset('storage/' . ltrim($item->image_product, '/')) }}" alt="{{ $item->name_product ?? 'Sản phẩm' }}" class="w-full h-full object-cover">
                                    @elseif($item->productVariant && $item->productVariant->product && $item->productVariant->product->images && $item->productVariant->product->images->first())
                                        <img src="{{ asset('storage/' . ltrim($item->productVariant->product->images->first()->image_path, '/')) }}" alt="{{ $item->name_product ?? 'Sản phẩm' }}" class="w-full h-full object-cover">
                                    @elseif($item->productVariant && $item->productVariant->product && $item->productVariant->product->thumbnail)
                                        <img src="{{ asset('storage/' . ltrim($item->productVariant->product->thumbnail, '/')) }}" alt="{{ $item->name_product ?? 'Sản phẩm' }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-image text-xl mb-1"></i>
                                            <span class="text-xs">No Image</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-2">{{ $item->name_product ?? (isset($item->productVariant->product) ? $item->productVariant->product->name : (isset($item->product) ? $item->product->name : 'Sản phẩm #' . $item->id)) }}</h3>
                                    @if($item->productVariant && $item->productVariant->attributeValues && $item->productVariant->attributeValues->count() > 0)
                                        <div class="text-sm text-gray-600 mb-2">
                                            @foreach($item->productVariant->attributeValues as $attrValue)
                                                @if(isset($attrValue->attribute))
                                                <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-2 mb-1">
                                                    {{ $attrValue->attribute->name ?? 'Thuộc tính' }}: {{ $attrValue->value ?? 'Giá trị' }}
                                                </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">Số lượng:</span> {{ $item->quantity ?? 1 }}
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-900">{{ number_format($item->price ?? 0) }} ₫</div>
                                            <div class="text-sm text-gray-600">Tổng: {{ number_format($item->total_price ?? (($item->price ?? 0) * ($item->quantity ?? 1))) }} ₫</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Không có sản phẩm nào trong đơn hàng này.</p>
                        </div>
                    @endif
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
                            <p class="text-sm text-gray-600">{{ isset($order->created_at) ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>

                    @if(isset($order->status) && $order->status !== 'pending')
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-cog text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Đơn hàng đang được xử lý</p>
                            <p class="text-sm text-gray-600">{{ isset($order->updated_at) ? $order->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                    @endif

                    @if(isset($order->shipped_at) && $order->shipped_at)
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-shipping-fast text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Đơn hàng đã được gửi đi</p>
                            <p class="text-sm text-gray-600">{{ $order->shipped_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(isset($order->received_at) && $order->received_at)
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

                    @if(isset($order->status) && $order->status === 'cancelled')
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-times-circle text-red-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Đơn hàng đã bị hủy</p>
                            @php
                                $cancelReturn = isset($order->returns) ? $order->returns()->where('type', 'cancel')->first() : null;
                            @endphp
                            @if($cancelReturn)
                                <p class="text-sm text-gray-600">
                                    Lý do: {{ $cancelReturn->client_note ?: $cancelReturn->reason }}
                                </p>
                                
                                {{-- Hiển thị minh chứng từ client nếu có --}}
                                @if($cancelReturn->images)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng của bạn:</strong></p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($cancelReturn->images as $productId => $images)
                                            @if(is_array($images))
                                                @foreach($images as $image)
                                                <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng client" class="w-16 h-16 object-cover rounded border cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng của bạn')">
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if($cancelReturn->video)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Video minh chứng:</strong></p>
                                    <video controls class="w-32 h-24 object-cover rounded border">
                                        <source src="{{ asset('storage/' . $cancelReturn->video) }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                </div>
                                @endif
                                
                                {{-- Hiển thị minh chứng từ admin nếu có --}}
                                @if($cancelReturn->admin_proof_images)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng từ admin:</strong></p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($cancelReturn->admin_proof_images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng admin" class="w-16 h-16 object-cover rounded border cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng từ admin')">
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-900">Đơn hàng đã bị hủy</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(isset($order->status) && $order->status === 'returned')
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-undo text-orange-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Đơn hàng đã được trả hàng</p>
                            @php
                                $returnReturn = isset($order->returns) ? $order->returns()->where('type', 'return')->where('status', 'approved')->first() : null;
                            @endphp
                            @if($returnReturn)
                                <p class="text-sm text-gray-600">
                                    Lý do: {{ $returnReturn->client_note ?: $returnReturn->reason }}
                                </p>
                                
                                {{-- Hiển thị minh chứng từ client --}}
                                @if($returnReturn->images)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng của bạn:</strong></p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($returnReturn->images as $productId => $images)
                                            @if(is_array($images))
                                                @foreach($images as $image)
                                                <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng client" class="w-16 h-16 object-cover rounded border cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng của bạn')">
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if($returnReturn->video)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Video minh chứng:</strong></p>
                                    <video controls class="w-32 h-24 object-cover rounded border">
                                        <source src="{{ asset('storage/' . $returnReturn->video) }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                </div>
                                @endif
                                
                                {{-- Hiển thị minh chứng từ admin --}}
                                @if($returnReturn->admin_proof_images)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng từ admin:</strong></p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($returnReturn->admin_proof_images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng admin" class="w-16 h-16 object-cover rounded border cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng từ admin')">
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-600">Đơn hàng đã được trả hàng</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @php
                        $pendingCancelRequest = isset($order->returns) ? $order->returns()->where('type', 'cancel')->where('status', 'pending')->first() : null;
                    @endphp
                    @if($pendingCancelRequest)
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-clock text-yellow-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Yêu cầu hủy đơn hàng đang chờ phê duyệt</p>
                            <p class="text-sm text-gray-600">
                                Lý do: {{ $pendingCancelRequest->client_note ?: $pendingCancelRequest->reason }}
                            </p>
                            
                                                            {{-- Hiển thị minh chứng từ client nếu có --}}
                                @if($pendingCancelRequest->images)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng của bạn:</strong></p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($pendingCancelRequest->images as $productId => $images)
                                            @if(is_array($images))
                                                @foreach($images as $image)
                                                <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng client" class="w-16 h-16 object-cover rounded border cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng của bạn')">
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            
                            @if($pendingCancelRequest->video)
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 mb-2"><strong>Video minh chứng:</strong></p>
                                <video controls class="w-32 h-24 object-cover rounded border">
                                    <source src="{{ asset('storage/' . $pendingCancelRequest->video) }}" type="video/mp4">
                                    Trình duyệt không hỗ trợ video.
                                </video>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @php
                        $returnRequest = isset($order->returns) ? $order->returns()->where('type', 'return')->first() : null;
                    @endphp
                    @if($returnRequest)
                        @if($returnRequest->status === 'pending')
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-yellow-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Yêu cầu trả hàng đang chờ phê duyệt</p>
                                <p class="text-sm text-gray-600">
                                    Lý do: {{ $returnRequest->client_note ?: $returnRequest->reason }}
                                </p>
                                
                                {{-- Hiển thị minh chứng từ client --}}
                                @if($returnRequest->images)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng của bạn:</strong></p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($returnRequest->images as $productId => $images)
                                            @if(is_array($images))
                                                @foreach($images as $image)
                                                <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng client" class="w-16 h-16 object-cover rounded border cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng của bạn')">
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if($returnRequest->video)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Video minh chứng:</strong></p>
                                    <video controls class="w-32 h-24 object-cover rounded border">
                                        <source src="{{ asset('storage/' . $returnRequest->video) }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                </div>
                                @endif
                            </div>
                        </div>
                        @elseif($returnRequest->status === 'approved')
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check-circle text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Yêu cầu trả hàng đã được chấp nhận</p>
                                <p class="text-sm text-gray-600">
                                    Lý do: {{ $returnRequest->client_note ?: $returnRequest->reason }}
                                </p>
                                
                                {{-- Hiển thị minh chứng từ client --}}
                                @if($returnRequest->images)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng của bạn:</strong></p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($returnRequest->images as $productId => $images)
                                            @if(is_array($images))
                                                @foreach($images as $image)
                                                <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng client" class="w-16 h-16 object-cover rounded border cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng của bạn')">
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if($returnRequest->video)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Video minh chứng:</strong></p>
                                    <video controls class="w-32 h-24 object-cover rounded border">
                                        <source src="{{ asset('storage/' . $returnRequest->video) }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                </div>
                                @endif
                                
                                @if($returnRequest->admin_note)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Phản hồi từ admin:</strong></p>
                                    <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded">{{ $returnRequest->admin_note }}</p>
                                </div>
                                @endif
                                
                                @if($returnRequest->admin_proof_images)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng từ admin:</strong></p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($returnRequest->admin_proof_images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng admin" class="w-16 h-16 object-cover rounded border cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng từ admin')">
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @elseif($returnRequest->status === 'rejected')
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-times-circle text-red-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Yêu cầu trả hàng đã bị từ chối</p>
                                <p class="text-sm text-gray-600">
                                    Lý do: {{ $returnRequest->client_note ?: $returnRequest->reason }}
                                </p>
                                
                                {{-- Hiển thị minh chứng từ client --}}
                                @if($returnRequest->images)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng của bạn:</strong></p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($returnRequest->images as $productId => $images)
                                            @if(is_array($images))
                                                @foreach($images as $image)
                                                <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng client" class="w-16 h-16 object-cover rounded border cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng của bạn')">
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if($returnRequest->video)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Video minh chứng:</strong></p>
                                    <video controls class="w-32 h-24 object-cover rounded border">
                                        <source src="{{ asset('storage/' . $returnRequest->video) }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                </div>
                                @endif
                                
                                @if($returnRequest->admin_note)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Lý do từ chối:</strong></p>
                                    <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded">{{ $returnRequest->admin_note }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Order Actions -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Thao tác đơn hàng</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    {{-- Hủy đơn hàng: chỉ khi pending, không phải VNPay và chưa có yêu cầu hủy --}}
                    @php
                        $hasCancelRequest = isset($order->returns) ? $order->returns()->where('type', 'cancel')->exists() : false;
                    @endphp
                    @if(isset($order->status) && $order->status === 'pending' && isset($order->payment_method) && $order->payment_method !== 'bank_transfer' && !$hasCancelRequest)
                        <button onclick="showCancelModal()" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>
                            Hủy đơn hàng
                        </button>
                    @elseif($order->status === 'pending' && $order->payment_method === 'bank_transfer')
                        <button class="bg-gray-400 text-white font-bold py-3 px-6 rounded-xl cursor-not-allowed" disabled title="Đơn hàng VNPay không thể hủy">
                            <i class="fas fa-times mr-2"></i>
                            Không thể hủy (VNPay)
                        </button>
                    @elseif($order->status === 'pending' && $hasCancelRequest)
                        <button class="bg-yellow-400 text-white font-bold py-3 px-6 rounded-xl cursor-not-allowed" disabled title="Đã có yêu cầu hủy đơn hàng">
                            <i class="fas fa-clock mr-2"></i>
                            Đã yêu cầu hủy
                        </button>
                    @endif

                    {{-- Thanh toán VNPay: cho đơn hàng pending với phương thức bank_transfer --}}
                    @if($order->status === 'pending' && $order->payment_method === 'bank_transfer' && $order->payment_status === 'pending')
                        <button onclick="payWithVnpay({{ $order->id }})" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                            <i class="fas fa-credit-card mr-2"></i>
                            Thanh toán VNPay
                        </button>
                    @endif

                    {{-- Thanh toán VNPay: khi đang xử lý thanh toán --}}
                    @if($order->payment_status === 'processing')
                        <button onclick="payWithVnpay({{ $order->id }})" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                            <i class="fas fa-credit-card mr-2"></i>
                            Thanh toán VNPay
                        </button>
                    @endif

                    {{-- Xác nhận đã nhận hàng: khi đã giao --}}
                    @if($order->status === 'delivered')
                        <button onclick="confirmReceipt({{ $order->id }})" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                            <i class="fas fa-check mr-2"></i>
                            Xác nhận nhận hàng
                        </button>
                    @endif

                    {{-- Yêu cầu trả hàng: khi đã giao và chưa có yêu cầu trả hàng --}}
                    @php
                        $hasReturnRequest = $order->returns()->where('type', 'return')->exists();
                    @endphp
                    @if($order->status === 'delivered' && !$hasReturnRequest)
                        <button onclick="showReturnModal()" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                            <i class="fas fa-undo mr-2"></i>
                            Yêu cầu trả hàng
                        </button>
                    @elseif($order->status === 'delivered' && $hasReturnRequest)
                        <button class="bg-yellow-400 text-white font-bold py-3 px-6 rounded-xl cursor-not-allowed" disabled title="Đã có yêu cầu trả hàng">
                            <i class="fas fa-clock mr-2"></i>
                            Đã yêu cầu trả hàng
                        </button>
                    @endif

                    <button onclick="downloadInvoice({{ $order->id }})" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                        <i class="fas fa-download mr-2"></i>
                        Tải hóa đơn
                    </button>
                </div>
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

<!-- Modal hủy đơn hàng -->
@if($order->status === 'pending' && $order->payment_method !== 'bank_transfer' && !$hasCancelRequest)
<div id="cancelOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Lý do hủy đơn hàng</h3>
                <button onclick="hideCancelModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Vui lòng chọn lý do hủy:</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="cancelReason" value="Đổi ý không muốn mua nữa" class="mr-2">
                        <span class="text-sm">Đổi ý không muốn mua nữa</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="cancelReason" value="Tìm thấy sản phẩm rẻ hơn ở nơi khác" class="mr-2">
                        <span class="text-sm">Tìm thấy sản phẩm rẻ hơn ở nơi khác</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="cancelReason" value="Đặt nhầm sản phẩm" class="mr-2">
                        <span class="text-sm">Đặt nhầm sản phẩm</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="cancelReason" value="Thay đổi địa chỉ giao hàng" class="mr-2">
                        <span class="text-sm">Thay đổi địa chỉ giao hàng</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="cancelReason" value="Lý do khác" class="mr-2">
                        <span class="text-sm">Lý do khác</span>
                    </label>
                </div>
                
                <div id="otherReasonDiv" class="mt-3 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vui lòng mô tả lý do cụ thể:</label>
                    <textarea id="otherReasonText" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập lý do cụ thể..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="hideCancelModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Đóng
                </button>
                <button onclick="confirmCancel()" id="confirmCancelBtn" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Xác nhận hủy
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal trả hàng -->
@if($order->status === 'delivered' && !$hasReturnRequest)
<div id="returnOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Yêu cầu trả hàng</h3>
                <button onclick="hideReturnModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="returnOrderForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn sản phẩm cần trả:</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($order->orderItems as $item)
                        <div class="border rounded-lg p-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="selected_products[]" value="{{ $item->productVariant ? $item->productVariant->id : ($item->product ? $item->product->id : $item->id) }}" class="mr-2 product-checkbox">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded overflow-hidden">
                                            @if($item->image_product)
                                                <img src="{{ asset('storage/' . ltrim($item->image_product, '/')) }}" alt="Ảnh sản phẩm" class="w-full h-full object-cover">
                                            @elseif(isset($item->product) && $item->product && $item->product->type === 'simple' && $item->product->thumbnail)
                                                <img src="{{ asset('storage/' . ltrim($item->product->thumbnail, '/')) }}" alt="{{ $item->name_product ?? $item->product->name }}" class="w-full h-full object-cover">
                                            @elseif($item->productVariant && $item->productVariant->image)
                                                <img src="{{ asset('storage/' . ltrim($item->productVariant->image, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                            @elseif($item->productVariant && $item->productVariant->product && $item->productVariant->product->images->first())
                                                <img src="{{ asset('storage/' . ltrim($item->productVariant->product->images->first()->image_path, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                            @elseif($item->productVariant && $item->productVariant->product && $item->productVariant->product->thumbnail)
                                                <img src="{{ asset('storage/' . ltrim($item->productVariant->product->thumbnail, '/')) }}" alt="{{ $item->name_product }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center text-gray-400 h-full">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm">
                                                @if($item->name_product)
                                                    {{ $item->name_product }}
                                                @elseif($item->productVariant && $item->productVariant->product)
                                                    {{ $item->productVariant->product->name }}
                                                @elseif($item->product)
                                                    {{ $item->product->name }}
                                                @else
                                                    Sản phẩm #{{ $item->id }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500">Số lượng: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lý do trả hàng:</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="return_reason" value="Sản phẩm bị lỗi" class="mr-2">
                            <span class="text-sm">Sản phẩm bị lỗi</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="return_reason" value="Sản phẩm không đúng mô tả" class="mr-2">
                            <span class="text-sm">Sản phẩm không đúng mô tả</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="return_reason" value="Sản phẩm bị hỏng khi giao hàng" class="mr-2">
                            <span class="text-sm">Sản phẩm bị hỏng khi giao hàng</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="return_reason" value="Không vừa size" class="mr-2">
                            <span class="text-sm">Không vừa size</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="return_reason" value="Lý do khác" class="mr-2">
                            <span class="text-sm">Lý do khác</span>
                        </label>
                    </div>
                    
                    <div class="mt-3 hidden" id="otherReturnReasonDiv">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vui lòng mô tả lý do cụ thể:</label>
                        <textarea id="otherReturnReasonText" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập lý do cụ thể..."></textarea>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hình ảnh minh chứng:</label>
                    <div class="alert alert-warning-custom alert-custom mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-camera me-3 mt-1 text-lg"></i>
                            <div>
                                <strong>Bắt buộc:</strong> Vui lòng chụp ảnh để chứng minh lý do trả hàng.
                            </div>
                        </div>
                    </div>
                    <input type="file" class="form-control" id="returnImages" name="product_images[]" accept="image/*" multiple required>
                    <div class="form-text">Chọn nhiều ảnh (JPG, PNG) - Tối đa 10MB mỗi ảnh</div>
                    <div id="imagePreview" class="mt-2 grid grid-cols-4 gap-2"></div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Video minh chứng:</label>
                    <div class="alert alert-warning-custom alert-custom mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-video me-3 mt-1 text-lg"></i>
                            <div>
                                <strong>Bắt buộc:</strong> Vui lòng quay video ngắn để chứng minh lý do trả hàng.
                            </div>
                        </div>
                    </div>
                    <input type="file" class="form-control" id="returnVideo" name="return_video" accept="video/*" required>
                    <div class="form-text">Video ngắn (MP4, AVI, MOV) - Tối đa 50MB</div>
                    <div id="videoPreview" class="mt-2"></div>
                </div>
                
                <div class="mb-4">
                    <label for="returnNote" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú thêm:</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="returnNote" name="client_note" rows="3" placeholder="Mô tả chi tiết về vấn đề gặp phải..."></textarea>
                </div>
            </form>
            
            <div class="flex justify-end space-x-3">
                <button onclick="hideReturnModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Đóng
                </button>
                <button onclick="confirmReturn()" id="confirmReturnBtn" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Xác nhận yêu cầu trả hàng
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal xem ảnh to -->
<div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="imageModalTitle">Xem ảnh</h3>
                <button onclick="hideImageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="text-center">
                <img id="imageModalImage" src="" alt="Ảnh" class="max-w-full max-h-96 mx-auto rounded">
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeInUp { 
    from {opacity:0; transform:translateY(30px);} 
    to {opacity:1; transform:translateY(0);} 
}
.bg-white { 
    animation: fadeInUp 0.6s ease-out; 
}
.bg-white:hover { 
    transform: translateY(-2px); 
}
</style>

<script>
// ======= VNPay =======
function payWithVnpay(orderId) {
    if(!confirm('Bạn có chắc chắn muốn thanh toán qua VNPay cho đơn hàng này?')) return;
    
    // Kiểm tra CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showAlert('❌ Không tìm thấy CSRF token. Vui lòng tải lại trang.', 'error');
        return;
    }
    
    // Hiển thị loading
    showAlert('Đang tạo thanh toán VNPay...', 'info');
    
    fetch(`/invoice/order/${orderId}/pay-vnpay`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if(data.success) {
            showAlert('✅ ' + data.message + '\n\n🔄 Đang chuyển hướng đến trang thanh toán VNPay...', 'success');
            if(data.payment_url) {
                setTimeout(() => {
                    window.location.href = data.payment_url;
                }, 2000);
            }
        } else {
            showAlert('❌ ' + (data.message || 'Có lỗi xảy ra'), 'error');
        }
    })
    .catch(error => {
        console.error('VNPay payment error:', error);
        showAlert('❌ Có lỗi xảy ra khi tạo thanh toán VNPay: ' + error.message, 'error');
    });
}

// ======= XÁC NHẬN NHẬN HÀNG =======
function confirmReceipt(orderId) {
    if(!confirm('Bạn có chắc chắn đã nhận hàng?')) return;
    
    // Kiểm tra CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showAlert('❌ Không tìm thấy CSRF token. Vui lòng tải lại trang.', 'error');
        return;
    }
    
    fetch(`/invoice/order/${orderId}/confirm-receipt`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if(data.success) {
            showAlert(data.message || 'Xác nhận nhận hàng thành công!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert(data.message || 'Có lỗi xảy ra khi xác nhận nhận hàng', 'error');
        }
    })
    .catch(error => {
        console.error('Confirm receipt error:', error);
        showAlert('❌ Có lỗi xảy ra khi xác nhận nhận hàng: ' + error.message, 'error');
    });
}

// ======= TẢI HÓA ĐƠN =======
function downloadInvoice(orderId) {
    const link = document.createElement('a');
    link.href = `/invoice/download/${orderId}`;
    link.download = `Hoa_don_${String(orderId).padStart(6, '0')}.pdf`;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showAlert('Đang tải hóa đơn PDF...', 'info');
}

// ======= HỦY ĐƠN HÀNG =======
function showCancelModal() {
    document.getElementById('cancelOrderModal').classList.remove('hidden');
    // Reset form
    document.querySelectorAll('input[name="cancelReason"]').forEach(radio => radio.checked = false);
    document.getElementById('otherReasonDiv').classList.add('hidden');
    document.getElementById('otherReasonText').value = '';
    document.getElementById('confirmCancelBtn').disabled = true;
}

function hideCancelModal() {
    document.getElementById('cancelOrderModal').classList.add('hidden');
}

function confirmCancel() {
    const selectedReason = document.querySelector('input[name="cancelReason"]:checked');
    if (!selectedReason) {
        showAlert('Vui lòng chọn lý do hủy đơn hàng', 'error');
        return;
    }
    
    let cancelReason = selectedReason.value;
    let clientNote = cancelReason;
    
    if (cancelReason === 'Lý do khác') {
        const otherReason = document.getElementById('otherReasonText').value.trim();
        if (!otherReason) {
            showAlert('Vui lòng nhập lý do cụ thể', 'error');
            document.getElementById('otherReasonText').focus();
            return;
        }
        clientNote = otherReason;
    }
    
    if (confirm(`Bạn có chắc chắn muốn hủy đơn hàng này?\n\nLý do: ${clientNote}\n\nHành động này không thể hoàn tác!`)) {
        const orderId = {{ $order->id ?? 0 }};
        if (!orderId) {
            showAlert('❌ Không tìm thấy ID đơn hàng', 'error');
            return;
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const formData = new FormData();
        formData.append('cancel_reason', cancelReason);
        formData.append('client_note', clientNote);
        
        fetch(`/invoice/order/${orderId}/cancel`, {
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
                showAlert(data.message, 'success');
                hideCancelModal();
                setTimeout(() => location.reload(), 2000);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi hủy đơn hàng', 'error');
        });
    }
}

// Xử lý radio buttons cho lý do hủy
document.addEventListener('DOMContentLoaded', function() {
    const reasonRadios = document.querySelectorAll('input[name="cancelReason"]');
    const otherReasonDiv = document.getElementById('otherReasonDiv');
    const otherReasonText = document.getElementById('otherReasonText');
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    
    if (reasonRadios.length > 0) {
        reasonRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'Lý do khác') {
                    otherReasonDiv.classList.remove('hidden');
                    otherReasonText.required = true;
                    // Kiểm tra nếu đã có text thì enable button
                    if (otherReasonText.value.trim()) {
                        confirmCancelBtn.disabled = false;
                    } else {
                        confirmCancelBtn.disabled = true;
                    }
                } else {
                    otherReasonDiv.classList.add('hidden');
                    otherReasonText.required = false;
                    otherReasonText.value = '';
                    confirmCancelBtn.disabled = false;
                }
            });
        });
        
        // Xử lý textarea cho lý do khác
        if (otherReasonText) {
            otherReasonText.addEventListener('input', function() {
                const selectedReason = document.querySelector('input[name="cancelReason"]:checked');
                if (selectedReason && selectedReason.value === 'Lý do khác') {
                    confirmCancelBtn.disabled = !this.value.trim();
                }
            });
        }
    }
});

// ======= TRẢ HÀNG =======
function showReturnModal() {
    document.getElementById('returnOrderModal').classList.remove('hidden');
    // Reset form
    document.getElementById('returnOrderForm').reset();
    document.getElementById('confirmReturnBtn').disabled = true;
    document.getElementById('otherReturnReasonDiv').classList.add('hidden');
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('videoPreview').innerHTML = '';
}

function hideReturnModal() {
    document.getElementById('returnOrderModal').classList.add('hidden');
}

function confirmReturn() {
    // Kiểm tra validation
    const selectedProducts = document.querySelectorAll('input[name="selected_products[]"]:checked');
    if (selectedProducts.length === 0) {
        showAlert('Vui lòng chọn ít nhất một sản phẩm để trả hàng', 'error');
        return;
    }
    
    const selectedReason = document.querySelector('input[name="return_reason"]:checked');
    if (!selectedReason) {
        showAlert('Vui lòng chọn lý do trả hàng', 'error');
        return;
    }
    
    let returnReason = selectedReason.value;
    let clientNote = returnReason;
    
    if (returnReason === 'Lý do khác') {
        const otherReason = document.getElementById('otherReturnReasonText').value.trim();
        if (!otherReason) {
            showAlert('Vui lòng nhập lý do cụ thể', 'error');
            document.getElementById('otherReturnReasonText').focus();
            return;
        }
        clientNote = otherReason;
    }
    
    const returnImages = document.getElementById('returnImages').files;
    if (returnImages.length === 0) {
        showAlert('Vui lòng chọn ít nhất một ảnh minh chứng', 'error');
        return;
    }
    
    const returnVideo = document.getElementById('returnVideo').files[0];
    if (!returnVideo) {
        showAlert('Vui lòng chọn video minh chứng', 'error');
        return;
    }
    
    // Hiển thị xác nhận cuối cùng
    if (confirm(`Bạn có chắc chắn muốn gửi yêu cầu trả hàng?\n\nLý do: ${clientNote}\nSản phẩm: ${selectedProducts.length} sản phẩm\n\nYêu cầu này sẽ được gửi đến admin để xem xét.`)) {
        const orderId = {{ $order->id ?? 0 }};
        if (!orderId) {
            showAlert('❌ Không tìm thấy ID đơn hàng', 'error');
            return;
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const formData = new FormData();
        formData.append('return_reason', returnReason);
        
        // Thêm sản phẩm được chọn
        const selectedProductIds = Array.from(selectedProducts).map(cb => cb.value);
        formData.append('selected_products', JSON.stringify(selectedProductIds));
        
        // Thêm ảnh
        for (let i = 0; i < returnImages.length; i++) {
            formData.append('product_images[]', returnImages[i]);
        }
        
        // Thêm video
        formData.append('return_video', returnVideo);
        
        // Thêm ghi chú (kết hợp lý do và ghi chú thêm)
        const returnNote = document.getElementById('returnNote').value;
        const finalNote = returnNote ? `${clientNote}\n\nGhi chú thêm: ${returnNote}` : clientNote;
        formData.append('client_note', finalNote);
        
        fetch(`/invoice/order/${orderId}/request-return`, {
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
                showAlert(data.message, 'success');
                hideReturnModal();
                setTimeout(() => location.reload(), 2000);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi gửi yêu cầu trả hàng', 'error');
        });
    }
}

// Xử lý radio buttons cho lý do trả hàng
document.addEventListener('DOMContentLoaded', function() {
    const reasonRadios = document.querySelectorAll('input[name="return_reason"]');
    const otherReasonDiv = document.getElementById('otherReturnReasonDiv');
    const otherReasonText = document.getElementById('otherReturnReasonText');
    const confirmReturnBtn = document.getElementById('confirmReturnBtn');
    
    if (reasonRadios.length > 0) {
        reasonRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'Lý do khác') {
                    otherReasonDiv.classList.remove('hidden');
                    otherReasonText.required = true;
                    // Kiểm tra nếu đã có text thì enable button
                    if (otherReasonText.value.trim()) {
                        confirmReturnBtn.disabled = false;
                    } else {
                        confirmReturnBtn.disabled = true;
                    }
                } else {
                    otherReasonDiv.classList.add('hidden');
                    otherReasonText.required = false;
                    otherReasonText.value = '';
                    confirmReturnBtn.disabled = false;
                }
            });
        });
        
        // Xử lý textarea cho lý do khác
        if (otherReasonText) {
            otherReasonText.addEventListener('input', function() {
                const selectedReason = document.querySelector('input[name="return_reason"]:checked');
                if (selectedReason && selectedReason.value === 'Lý do khác') {
                    confirmReturnBtn.disabled = !this.value.trim();
                }
            });
        }
    }
    
    // Xử lý chọn sản phẩm
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    productCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // Kiểm tra xem có sản phẩm nào được chọn không
            const checkedProducts = document.querySelectorAll('.product-checkbox:checked');
            if (checkedProducts.length === 0) {
                confirmReturnBtn.disabled = true;
            } else {
                confirmReturnBtn.disabled = false;
            }
        });
    });
    
    // Xử lý preview ảnh
    const returnImages = document.getElementById('returnImages');
    if (returnImages) {
        returnImages.addEventListener('change', function() {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-16 h-16 object-cover rounded border';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    }
    
    // Xử lý preview video
    const returnVideo = document.getElementById('returnVideo');
    if (returnVideo) {
        returnVideo.addEventListener('change', function() {
            const preview = document.getElementById('videoPreview');
            preview.innerHTML = '';
            
            if (this.files[0]) {
                const file = this.files[0];
                if (file.type.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = URL.createObjectURL(file);
                    video.className = 'w-full max-w-md rounded border';
                    video.controls = true;
                    preview.appendChild(video);
                }
            }
        });
    }
});

// ======= XEM ẢNH TO =======
function openImageModal(imageSrc, title) {
    const imageModal = document.getElementById('imageModal');
    const imageModalImage = document.getElementById('imageModalImage');
    const imageModalTitle = document.getElementById('imageModalTitle');
    
    if (!imageModal || !imageModalImage || !imageModalTitle) {
        showAlert('❌ Không thể mở modal xem ảnh', 'error');
        return;
    }
    
    if (!imageSrc) {
        showAlert('❌ Không có ảnh để hiển thị', 'error');
        return;
    }
    
    imageModalImage.src = imageSrc;
    imageModalTitle.textContent = title || 'Xem ảnh';
    imageModal.classList.remove('hidden');
}

function hideImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// ======= THÔNG BÁO =======
function showAlert(message, type = 'info') {
    if (!message) return;
    
    // Xóa các alert cũ trước khi tạo mới
    const existingAlerts = document.querySelectorAll('.alert-notification');
    existingAlerts.forEach(alert => alert.remove());
    
    const alert = document.createElement('div');
    alert.className = `alert-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    
    const iconClass = type === 'success' ? 'fa-check-circle' : 
                     type === 'error' ? 'fa-exclamation-circle' : 
                     'fa-info-circle';
    
    alert.innerHTML = `<div class="flex items-center">
        <i class="fas ${iconClass} mr-3"></i>
        <span class="break-words">${message}</span>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>`;
    
    document.body.appendChild(alert);
    
    // Tự động ẩn sau 5 giây
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 5000);
}
</script>

@endsection


