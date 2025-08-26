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
                            @if($order->guest_email)
                                <p><span class="font-medium">Khách hàng:</span> {{ $order->guest_name ?? 'Khách vãng lai' }} ({{ $order->guest_email }})</p>
                                <!-- Debug info -->
                                @if(config('app.debug'))
                                    <p class="text-xs text-gray-500">Debug: guest_name = "{{ $order->guest_name }}", recipient_name = "{{ $order->recipient_name }}"</p>
                                @endif
                            @elseif($order->user)
                                <p><span class="font-medium">Khách hàng:</span> {{ $order->user->name }} ({{ $order->user->email }})</p>
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
<<<<<<< HEAD
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
=======

                    <!-- Hủy đơn hàng - Chỉ hiển thị khi đơn hàng ở trạng thái có thể hủy -->
                    @if(in_array($order->status, ['pending', 'processing']))
                        @if($order->payment_method !== 'bank_transfer')
>>>>>>> e38b7b726e194298b0c6e63bd831392758ecec33
                        <button onclick="cancelOrder({{ $order->id }})" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>
                            Hủy đơn hàng
                        </button>
<<<<<<< HEAD
=======
                        @endif
>>>>>>> e38b7b726e194298b0c6e63bd831392758ecec33
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
<<<<<<< HEAD
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
=======
                    @if($order->status === 'delivered' && !$order->returns->where('type', 'return')->whereIn('status', ['pending', 'approved', 'processing'])->count())
                    <button onclick="requestReturn({{ $order->id }})" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                        <i class="fas fa-undo mr-2"></i>
                        Yêu cầu trả hàng
                    </button>
>>>>>>> e38b7b726e194298b0c6e63bd831392758ecec33
                    @endif

                                         <!-- Hiển thị trạng thái nếu đã có yêu cầu trả hàng -->
                     @if($order->status === 'delivered' && $order->returns->where('type', 'return')->whereIn('status', ['pending', 'approved', 'processing'])->count())
                     <div class="bg-blue-100 border border-blue-300 text-blue-800 px-4 py-3 rounded-xl">
                         <div class="flex items-center">
                             <i class="fas fa-info-circle mr-2"></i>
                             <span class="font-medium">
                                 @php
                                     $returnRequest = $order->returns->where('type', 'return')->whereIn('status', ['pending', 'approved', 'processing'])->first();
                                     $statusText = '';
                                     switch($returnRequest->status) {
                                         case 'pending':
                                             $statusText = 'Đang chờ xử lý';
                                             break;
                                         case 'approved':
                                             $statusText = 'Đã được chấp nhận';
                                             break;
                                         case 'processing':
                                             $statusText = 'Đang xử lý';
                                             break;
                                         default:
                                             $statusText = 'Đang xử lý';
                                     }
                                 @endphp
                                 Đã gửi yêu cầu trả hàng - {{ $statusText }}
                             </span>
                         </div>
                         @if($returnRequest->client_note)
                         <div class="mt-2 text-sm">
                             <strong>Ghi chú:</strong> {{ $returnRequest->client_note }}
                         </div>
                         @endif
                         <div class="mt-2 text-sm text-blue-600">
                             Ngày gửi: {{ $returnRequest->requested_at->format('d/m/Y H:i') }}
                         </div>
                     </div>

                     <!-- Hiển thị minh chứng yêu cầu trả hàng -->
                     @if($returnRequest)
                     <div class="bg-white rounded-xl p-6 mb-6 shadow-sm">
                         <h3 class="text-xl font-bold text-gray-900 mb-4">Minh chứng yêu cầu trả hàng</h3>

                         <!-- Minh chứng của khách hàng -->
                         <div class="mb-6">
                             <div class="flex items-center mb-4">
                                 <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                     <i class="fas fa-camera text-blue-600"></i>
                                 </div>
                                 <h4 class="font-medium text-gray-800">Minh chứng của bạn</h4>
                                 @if($returnRequest->images && is_array($returnRequest->images) && count($returnRequest->images) > 0)
                                     <span class="ml-auto text-sm text-gray-500">
                                         {{ count(array_merge(...array_values($returnRequest->images))) }} ảnh
                                     </span>
                                 @endif
                             </div>

                             @if($returnRequest->images && is_array($returnRequest->images) && count($returnRequest->images) > 0)
                                 <div class="space-y-4">
                                     @foreach($returnRequest->images as $productId => $productImages)
                                         @if(is_array($productImages))
                                             @foreach($productImages as $image)
                                                 <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                                     <div class="flex items-center">
                                                         <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden mr-4">
                                                             <img src="{{ asset('storage/' . ltrim($image, '/')) }}"
                                                                 alt="Minh chứng sản phẩm"
                                                                 class="w-full h-full object-cover cursor-pointer"
                                                                 onclick="openImageModal('{{ asset('storage/' . ltrim($image, '/')) }}')">
                                                         </div>
                                                         <div class="flex-1">
                                                             <h5 class="font-medium text-gray-900 mb-2">Ảnh minh chứng</h5>
                                                             <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                                 <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Ảnh sản phẩm</span>
                                                                 <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Đã upload</span>
                                                             </div>
                                                         </div>
                                                         <div class="text-right">
                                                             <button onclick="openImageModal('{{ asset('storage/' . ltrim($image, '/')) }}')"
                                                                 class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                                 <i class="fas fa-eye mr-1"></i>Xem
                                                             </button>
                                                         </div>
                                                     </div>
                                                 </div>
                                             @endforeach
                                         @endif
                                     @endforeach
                                 </div>
                             @else
                                 <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
                                     <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                         <i class="fas fa-image text-gray-400 text-xl"></i>
                                     </div>
                                     <p class="text-gray-500">Chưa có ảnh minh chứng</p>
                                 </div>
                             @endif

                             @if($returnRequest->video)
                                 <div class="mt-4">
                                     <div class="flex items-center mb-3">
                                         <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                             <i class="fas fa-video text-green-600"></i>
                                         </div>
                                         <h5 class="font-medium text-gray-800">Video chứng minh</h5>
                                     </div>
                                     <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                         <div class="flex items-center">
                                             <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden mr-4">
                                                 <video class="w-full h-full object-cover">
                                                     <source src="{{ asset('storage/' . ltrim($returnRequest->video, '/')) }}" type="video/mp4">
                                                 </video>
                                             </div>
                                             <div class="flex-1">
                                                 <h5 class="font-medium text-gray-900 mb-2">Video chứng minh</h5>
                                                 <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                     <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Video</span>
                                                     <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Đã upload</span>
                                                 </div>
                                             </div>
                                             <div class="text-right">
                                                 <button onclick="openVideoModal('{{ asset('storage/' . ltrim($returnRequest->video, '/')) }}')"
                                                     class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                                     <i class="fas fa-play mr-1"></i>Xem
                                                 </button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             @else
                                 <div class="mt-4">
                                     <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
                                         <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                             <i class="fas fa-video text-gray-400 text-xl"></i>
                                         </div>
                                         <p class="text-gray-500">Chưa có video chứng minh</p>
                                     </div>
                                 </div>
                             @endif
                         </div>

                         <!-- Minh chứng của admin -->
                         @if($returnRequest->admin_proof_images)
                             <div class="mb-6">
                                 <div class="flex items-center mb-4">
                                     <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                         <i class="fas fa-shield-alt text-green-600"></i>
                                     </div>
                                     <h4 class="font-medium text-gray-800">Minh chứng từ admin</h4>
                                     <span class="ml-auto text-sm text-gray-500">
                                         {{ count($returnRequest->admin_proof_images ?? []) }} ảnh
                                     </span>
                                 </div>

                                 <div class="space-y-4">
                                     @foreach($returnRequest->admin_proof_images ?? [] as $imagePath)
                                         <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                             <div class="flex items-center mb-3">
                                                 <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                     <i class="fas fa-image text-green-600 text-xs"></i>
                                                 </div>
                                                 <span class="text-sm text-gray-600">Minh chứng từ admin</span>
                                             </div>
                                             <div class="flex justify-center">
                                                 <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                                     alt="Minh chứng admin"
                                                     class="max-w-full h-48 object-contain rounded cursor-pointer hover:opacity-80 transition-opacity"
                                                     onclick="openImageModal('{{ asset('storage/' . ltrim($imagePath, '/')) }}')">
                                             </div>
                                         </div>
                                     @endforeach
                                 </div>
                             @endif

                         @if($returnRequest->admin_note)
                             <div class="mb-6">
                                 <div class="flex items-center mb-3">
                                     <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                         <i class="fas fa-comment text-purple-600"></i>
                                     </div>
                                     <h4 class="font-medium text-gray-800">Ghi chú từ admin</h4>
                                 </div>
                                 <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                     <div class="flex items-start">
                                         <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                             <i class="fas fa-user-shield text-purple-600"></i>
                                         </div>
                                         <div class="flex-1">
                                             <h5 class="font-medium text-gray-900 mb-2">Phản hồi từ admin</h5>
                                             <p class="text-gray-700 mb-2">{{ $returnRequest->admin_note }}</p>
                                             <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                 <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded">Ghi chú</span>
                                                 <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Đã phản hồi</span>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         @endif
                     </div>
                     @endif
                     @endif

                    <!-- Xác nhận nhận hàng - Chỉ hiển thị khi đơn hàng đã giao và chưa yêu cầu trả hàng -->
                    @if($order->status === 'delivered' && !$order->returns->where('type', 'return')->whereIn('status', ['pending', 'approved', 'processing'])->count())
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

    <!-- Modal hủy đơn hàng -->
    <div id="cancelOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Hủy đơn hàng</h3>
                    <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="cancelOrderForm">
                    <div class="mb-4">
                        <label for="cancel_reason" class="block text-sm font-medium text-gray-700 mb-2">Lý do hủy đơn hàng</label>
                        <select id="cancel_reason" name="cancel_reason" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Khách hủy">Khách hủy</option>
                            <option value="Thay đổi ý định">Thay đổi ý định</option>
                            <option value="Tìm thấy sản phẩm khác">Tìm thấy sản phẩm khác</option>
                            <option value="Giá cả không phù hợp">Giá cả không phù hợp</option>
                            <option value="Lý do khác">Lý do khác</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="client_note" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú thêm (không bắt buộc)</label>
                        <textarea id="client_note" name="client_note" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập ghi chú nếu có..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCancelModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Hủy
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                            Xác nhận hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal yêu cầu trả hàng -->
    <div id="returnOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Yêu cầu đổi/trả hàng</h3>
                    <button onclick="closeReturnModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="returnOrderForm">
                    <!-- Chọn sản phẩm cần đổi/trả -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn sản phẩm cần đổi/trả: <span class="text-red-500">*</span></label>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                                <div class="text-sm text-blue-800">
                                    <strong>Lưu ý:</strong> Vui lòng chọn sản phẩm cụ thể mà bạn muốn đổi/trả.
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($order->orderItems as $item)
                            <div class="border-2 rounded-lg p-3" id="productCard{{ $item->id }}">
                                <div class="flex items-start">
                                    <input class="product-checkbox mr-3 mt-1" type="checkbox" 
                                           name="selected_products[]" 
                                           value="{{ $item->id }}" 
                                           id="product{{ $item->id }}">
                                    <label class="flex-1 cursor-pointer" for="product{{ $item->id }}">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 mr-3">
                                                @php
                                                    $productImage = null;
                                                    $imageAlt = $item->name_product ?? 'N/A';
                                                    
                                                    // Ưu tiên 1: Ảnh của biến thể (variant)
                                                    if ($item->productVariant && $item->productVariant->image) {
                                                        $productImage = $item->productVariant->image;
                                                    }
                                                    // Ưu tiên 2: Ảnh của sản phẩm chính (product thumbnail)
                                                    elseif (isset($item->product) && $item->product && $item->product->thumbnail) {
                                                        $productImage = $item->product->thumbnail;
                                                    }
                                                    // Ưu tiên 3: Ảnh được lưu trong order item
                                                    elseif ($item->image_product) {
                                                        $productImage = $item->image_product;
                                                    }
                                                    // Ưu tiên 4: Ảnh đầu tiên từ gallery của sản phẩm
                                                    elseif (isset($item->product) && $item->product && $item->product->productAllImages && $item->product->productAllImages->count() > 0) {
                                                        $productImage = $item->product->productAllImages->first()->image_path;
                                                    }
                                                    // Ưu tiên 5: Ảnh đầu tiên từ gallery của biến thể
                                                    elseif ($item->productVariant && $item->productVariant->product && $item->productVariant->product->productAllImages && $item->productVariant->product->productAllImages->count() > 0) {
                                                        $productImage = $item->productVariant->product->productAllImages->first()->image_path;
                                                    }
                                                @endphp
                                                
                                                @if($productImage)
                                                    <img src="{{ asset('storage/' . ltrim($productImage, '/')) }}" 
                                                         alt="{{ $imageAlt }}" 
                                                         class="w-15 h-15 object-cover rounded shadow-sm hover:shadow-md transition-shadow duration-200"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="w-15 h-15 bg-gray-100 rounded flex items-center justify-center hidden">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @else
                                                    <div class="w-15 h-15 bg-gray-100 rounded flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h6 class="font-medium text-gray-900 mb-1">{{ $item->name_product ?? ($item->productVariant->product->name ?? 'N/A') }}</h6>
                                                <p class="text-sm text-gray-600 mb-1">
                                                    Số lượng: {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}₫
                                                </p>
                                                @if($item->productVariant && $item->productVariant->attributeValues->count() > 0)
                                                <p class="text-sm text-gray-600">
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
                            @endforeach
                        </div>
                    </div>

                    <!-- Lý do trả hàng -->
                    <div class="mb-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-question-circle text-gray-600 mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-1">Lý do trả hàng</h4>
                                    <p class="text-sm text-gray-600">Vui lòng chọn lý do chính xác để chúng tôi xử lý yêu cầu trả hàng.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input class="mr-3" type="radio" name="returnReason" id="return1" value="Sản phẩm bị lỗi/hỏng" required>
                                <label class="cursor-pointer" for="return1">Sản phẩm bị lỗi/hỏng</label>
                            </div>
                            <div class="flex items-center">
                                <input class="mr-3" type="radio" name="returnReason" id="return2" value="Sản phẩm không đúng mô tả" required>
                                <label class="cursor-pointer" for="return2">Sản phẩm không đúng mô tả</label>
                            </div>
                            <div class="flex items-center">
                                <input class="mr-3" type="radio" name="returnReason" id="return3" value="Kích thước không phù hợp" required>
                                <label class="cursor-pointer" for="return3">Kích thước không phù hợp</label>
                            </div>
                            <div class="flex items-center">
                                <input class="mr-3" type="radio" name="returnReason" id="return4" value="Màu sắc không như mong đợi" required>
                                <label class="cursor-pointer" for="return4">Màu sắc không như mong đợi</label>
                            </div>
                            <div class="flex items-center">
                                <input class="mr-3" type="radio" name="returnReason" id="return5" value="Chất lượng không tốt" required>
                                <label class="cursor-pointer" for="return5">Chất lượng không tốt</label>
                            </div>
                            <div class="flex items-center">
                                <input class="mr-3" type="radio" name="returnReason" id="return6" value="Giao hàng sai sản phẩm" required>
                                <label class="cursor-pointer" for="return6">Giao hàng sai sản phẩm</label>
                            </div>
                            <div class="flex items-center">
                                <input class="mr-3" type="radio" name="returnReason" id="return7" value="Không vừa ý với sản phẩm" required>
                                <label class="cursor-pointer" for="return7">Không vừa ý với sản phẩm</label>
                            </div>
                            <div class="flex items-center">
                                <input class="mr-3" type="radio" name="returnReason" id="return8" value="Lý do khác" required>
                                <label class="cursor-pointer" for="return8">Lý do khác</label>
                            </div>
                        </div>
                        
                        <div class="mt-3 hidden" id="otherReturnReasonDiv">
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-colors resize-none" 
                                      id="returnReasonOther" 
                                      name="client_note_other" 
                                      rows="2" 
                                      placeholder="Vui lòng mô tả chi tiết lý do trả hàng..."></textarea>
                        </div>
                    </div>

                    <!-- Upload ảnh cho từng sản phẩm -->
                    <div class="mt-6 hidden" id="productImagesSection">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-camera text-gray-600 mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-1">Hình ảnh chứng minh</h4>
                                    <p class="text-sm text-gray-600">Vui lòng chụp ảnh rõ ràng cho từng sản phẩm để chứng minh lý do đổi/trả.</p>
                                </div>
                            </div>
                        </div>
                        
                        @foreach($order->orderItems as $item)
                        <div class="product-images-container mb-4 hidden" id="productImages{{ $item->id }}">
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded mr-3 overflow-hidden">
                                            @php
                                                $uploadProductImage = null;
                                                $uploadImageAlt = $item->name_product ?? 'N/A';
                                                
                                                // Ưu tiên 1: Ảnh của biến thể (variant)
                                                if ($item->productVariant && $item->productVariant->image) {
                                                    $uploadProductImage = $item->productVariant->image;
                                                }
                                                // Ưu tiên 2: Ảnh của sản phẩm chính (product thumbnail)
                                                elseif (isset($item->product) && $item->product && $item->product->thumbnail) {
                                                    $uploadProductImage = $item->product->thumbnail;
                                                }
                                                // Ưu tiên 3: Ảnh được lưu trong order item
                                                elseif ($item->image_product) {
                                                    $uploadProductImage = $item->image_product;
                                                }
                                                // Ưu tiên 4: Ảnh đầu tiên từ gallery của sản phẩm
                                                elseif (isset($item->product) && $item->product && $item->product->productAllImages && $item->product->productAllImages->count() > 0) {
                                                    $uploadProductImage = $item->product->productAllImages->first()->image_path;
                                                }
                                                // Ưu tiên 5: Ảnh đầu tiên từ gallery của biến thể
                                                elseif ($item->productVariant && $item->productVariant->product && $item->productVariant->product->productAllImages && $item->productVariant->product->productAllImages->count() > 0) {
                                                    $uploadProductImage = $item->productVariant->product->productAllImages->first()->image_path;
                                                }
                                            @endphp
                                            
                                            @if($uploadProductImage)
                                                <img src="{{ asset('storage/' . ltrim($uploadProductImage, '/')) }}" 
                                                     alt="{{ $uploadImageAlt }}" 
                                                     class="w-8 h-8 object-cover rounded"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center hidden">
                                                    <i class="fas fa-image text-gray-400 text-xs"></i>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400 text-xs"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <h5 class="font-medium text-gray-900">{{ $item->name_product ?? ($item->productVariant->product->name ?? 'N/A') }}</h5>
                                    </div>
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Bắt buộc</span>
                                </div>
                                
                                <div class="space-y-3">
                                    <!-- File input -->
                                    <div class="relative">
                                        <input type="file" 
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer product-images-input" 
                                               name="product_images[{{ $item->id }}][]" 
                                               accept="image/*" multiple 
                                               data-product-id="{{ $item->id }}"
                                               id="fileInput{{ $item->id }}">
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors">
                                            <div class="space-y-2">
                                                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
                                                <div>
                                                    <p class="text-sm text-gray-700">Click để chọn ảnh hoặc kéo thả vào đây</p>
                                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG tối đa 5MB mỗi ảnh</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Preview ảnh -->
                                    <div class="product-image-preview space-y-2" id="productImagePreview{{ $item->id }}">
                                        <!-- Ảnh sẽ được hiển thị ở đây -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Upload video -->
                    <div class="mt-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-video text-gray-600 mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-1">Video chứng minh</h4>
                                    <p class="text-sm text-gray-600">Vui lòng quay video ngắn để chứng minh lý do đổi/trả.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                        <i class="fas fa-video text-gray-600"></i>
                                    </div>
                                    <h5 class="font-medium text-gray-900">Video chứng minh chung</h5>
                                </div>
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Bắt buộc</span>
                            </div>
                            
                            <div class="space-y-3">
                                <!-- File input -->
                                <div class="relative">
                                    <input type="file" 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                           id="returnVideo" 
                                           name="return_video" 
                                           accept="video/*" 
                                           required>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors">
                                        <div class="space-y-2">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
                                            <div>
                                                <p class="text-sm text-gray-700">Click để chọn video hoặc kéo thả vào đây</p>
                                                <p class="text-xs text-gray-500 mt-1">MP4, AVI, MOV - Tối đa 50MB</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Preview video -->
                                <div id="videoPreview" class="space-y-2">
                                    <!-- Video sẽ được hiển thị ở đây -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ghi chú -->
                    <div class="mt-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-edit text-gray-600 mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-1">Ghi chú bổ sung</h4>
                                    <p class="text-sm text-gray-600">Mô tả chi tiết về vấn đề gặp phải (tùy chọn).</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-xl p-4 hover:border-green-300 transition-all duration-300 hover:shadow-md">

                            
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-colors resize-none" 
                                      id="returnNote" 
                                      name="client_note" 
                                      rows="3" 
                                      placeholder="Mô tả chi tiết về vấn đề gặp phải..."></textarea>
                            

                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeReturnModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Đóng
                        </button>
                        <button type="button" id="btn-confirm-return" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors" disabled>
                            Xác nhận yêu cầu trả hàng
                        </button>
                    </div>
                </form>
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

<<<<<<< HEAD
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
=======
/* Return modal styles */
.product-checkbox:checked + label .border-2 {
    border-color: #3b82f6 !important;
    background-color: #eff6ff;
}

.product-checkbox:checked + label {
    background-color: #eff6ff;
}

.product-checkbox:checked + label .border-2 {
    background-color: #eff6ff;
}

.return-reasons-list .flex {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.return-reasons-list .flex:hover {
    background-color: #f9fafb;
    border-color: #d1d5db;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.return-reasons-list input[type="radio"]:checked + label {
    background-color: #fef3c7;
    border-color: #f59e0b;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

input[type="radio"]:checked {
    background-color: #ef4444;
    border-color: #ef4444;
}

label {
    cursor: pointer;
    font-weight: 500;
    color: #374151;
}

label i {
    width: 16px;
    text-align: center;
}

#otherReturnReasonDiv {
    border-top: 1px solid #e5e7eb;
    padding-top: 15px;
    margin-top: 15px;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}
>>>>>>> e38b7b726e194298b0c6e63bd831392758ecec33
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
<<<<<<< HEAD
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
=======
    console.log('Opening return modal for order:', orderId);
    
    // Hiển thị modal
    document.getElementById('returnOrderModal').classList.remove('hidden');
    
    // Reset form
    document.getElementById('returnOrderForm').reset();
    
    // Ẩn tất cả product images containers
    document.querySelectorAll('.product-images-container').forEach(container => {
        container.classList.add('hidden');
>>>>>>> e38b7b726e194298b0c6e63bd831392758ecec33
    });
    
    // Ẩn product images section
    document.getElementById('productImagesSection').classList.add('hidden');
    
    // Reset button state
    const confirmBtn = document.getElementById('btn-confirm-return');
    if (confirmBtn) {
        confirmBtn.disabled = true;
        console.log('Confirm button disabled:', confirmBtn.disabled);
    }
    
    // Validate form sau khi reset
    setTimeout(() => {
        validateReturnForm();
    }, 100);
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
    // Tạo URL tải PDF
    const downloadUrl = `/invoice/download/${orderId}`;
    
    // Tạo link ẩn để tải file
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = `Hoa_don_${String(orderId).padStart(6, '0')}.pdf`;
    link.style.display = 'none';
    
    // Thêm vào DOM và click
    document.body.appendChild(link);
    link.click();
    
    // Xóa link sau khi click
    document.body.removeChild(link);
    
    // Hiển thị thông báo
    showAlert('Đang tải hóa đơn PDF...', 'info');
}

// Hủy đơn hàng
function cancelOrder(orderId) {
    // Hiển thị modal
    document.getElementById('cancelOrderModal').classList.remove('hidden');
}

// Đóng modal hủy đơn hàng
function closeCancelModal() {
    document.getElementById('cancelOrderModal').classList.add('hidden');
    document.getElementById('cancelOrderForm').reset();
}

// Đóng modal trả hàng
function closeReturnModal() {
    document.getElementById('returnOrderModal').classList.add('hidden');
    document.getElementById('returnOrderForm').reset();
}

// Xử lý form hủy đơn hàng
document.addEventListener('DOMContentLoaded', function() {
    const cancelForm = document.getElementById('cancelOrderForm');
    if (cancelForm) {
        cancelForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(cancelForm);
            const cancelData = {
                cancel_reason: formData.get('cancel_reason'),
                client_note: formData.get('client_note')
            };
            
            // Lấy order ID từ button
            const orderId = document.querySelector('button[onclick*="cancelOrder"]').getAttribute('onclick').match(/\d+/)[0];
            
            // Gửi yêu cầu hủy đơn hàng
            fetch(`/invoice/order/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(cancelData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    closeCancelModal();
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
        });
    }

    // Xử lý form trả hàng
    const returnForm = document.getElementById('returnOrderForm');
    if (returnForm) {
        // Xử lý checkbox sản phẩm
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        productCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const productId = this.value;
                const productImagesContainer = document.getElementById(`productImages${productId}`);
                
                if (this.checked) {
                    productImagesContainer.classList.remove('hidden');
                } else {
                    productImagesContainer.classList.add('hidden');
                }
                
                updateProductImagesSection();
                validateReturnForm(); // Validate ngay khi thay đổi
            });
        });

        // Xử lý radio button lý do trả hàng
        const returnReasons = document.querySelectorAll('input[name="returnReason"]');
        returnReasons.forEach(radio => {
            radio.addEventListener('change', function() {
                const otherReasonDiv = document.getElementById('otherReturnReasonDiv');
                if (this.value === 'Lý do khác') {
                    otherReasonDiv.classList.remove('hidden');
                } else {
                    otherReasonDiv.classList.add('hidden');
                }
                
                validateReturnForm(); // Validate ngay khi thay đổi
            });
        });

        // Xử lý nút xác nhận trả hàng
        const confirmReturnBtn = document.getElementById('btn-confirm-return');
        confirmReturnBtn.addEventListener('click', function() {
            submitReturnRequest();
        });

        // Xử lý textarea lý do khác
        const otherReasonTextarea = document.getElementById('returnReasonOther');
        if (otherReasonTextarea) {
            otherReasonTextarea.addEventListener('input', function() {
                validateReturnForm(); // Validate ngay khi gõ
            });
        }

        // Xử lý upload ảnh sản phẩm
        const productImageInputs = document.querySelectorAll('.product-images-input');
        productImageInputs.forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.getAttribute('data-product-id');
                const previewContainer = document.getElementById(`productImagePreview${productId}`);
                previewContainer.innerHTML = '';
                
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-20 h-20 object-cover rounded';
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
                
                validateReturnForm(); // Validate ngay khi upload
            });
        });

        // Xử lý upload video
        const returnVideo = document.getElementById('returnVideo');
        returnVideo.addEventListener('change', function() {
            const previewContainer = document.getElementById('videoPreview');
            previewContainer.innerHTML = '';
            
            if (this.files.length > 0) {
                const file = this.files[0];
                const video = document.createElement('video');
                video.src = URL.createObjectURL(file);
                video.className = 'w-full max-w-md rounded';
                video.controls = true;
                previewContainer.appendChild(video);
            }
            
            validateReturnForm(); // Validate ngay khi upload
        });
    }
});

// Cập nhật hiển thị section ảnh sản phẩm
function updateProductImagesSection() {
    const checkedProducts = document.querySelectorAll('.product-checkbox:checked');
    const productImagesSection = document.getElementById('productImagesSection');
    
    if (checkedProducts.length > 0) {
        productImagesSection.classList.remove('hidden');
    } else {
        productImagesSection.classList.add('hidden');
    }
}

// Validate form trả hàng
function validateReturnForm() {
    const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
    const selectedReason = document.querySelector('input[name="returnReason"]:checked');
    const returnVideo = document.getElementById('returnVideo').files.length > 0;
    const confirmBtn = document.getElementById('btn-confirm-return');
    
    // Kiểm tra cơ bản
    let isValid = selectedProducts.length > 0 && selectedReason && returnVideo;
    
    // Kiểm tra ảnh sản phẩm (chỉ khi có sản phẩm được chọn)
    if (selectedProducts.length > 0) {
        selectedProducts.forEach(checkbox => {
            const productId = checkbox.value;
            const imageInput = document.querySelector(`input[data-product-id="${productId}"]`);
            if (imageInput && imageInput.files.length === 0) {
                isValid = false;
            }
        });
    }
    
    // Kiểm tra lý do khác
    if (selectedReason && selectedReason.value === 'Lý do khác') {
        const otherReason = document.getElementById('returnReasonOther');
        if (otherReason && !otherReason.value.trim()) {
            isValid = false;
        }
    }
    
    // Cập nhật trạng thái nút
    if (confirmBtn) {
        confirmBtn.disabled = !isValid;
    }
    
    console.log('Form validation:', {
        selectedProducts: selectedProducts.length,
        selectedReason: selectedReason ? selectedReason.value : null,
        returnVideo: returnVideo,
        isValid: isValid
    });
}

// Gửi yêu cầu trả hàng
function submitReturnRequest() {
    const form = document.getElementById('returnOrderForm');
    const formData = new FormData(form);
    
    // Lấy order ID từ button
    const orderId = document.querySelector('button[onclick*="requestReturn"]').getAttribute('onclick').match(/\d+/)[0];
    
    // Thêm selected products
    const selectedProducts = [];
    document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
        selectedProducts.push(checkbox.value);
    });
    formData.append('selected_products', JSON.stringify(selectedProducts));
    
    // Thêm lý do trả hàng
    const selectedReason = document.querySelector('input[name="returnReason"]:checked');
    if (selectedReason) {
        formData.append('return_reason', selectedReason.value);
    }
    
    // Thêm ghi chú lý do khác nếu có
    if (selectedReason && selectedReason.value === 'Lý do khác') {
        const otherReason = document.getElementById('returnReasonOther').value;
        formData.append('client_note_other', otherReason);
    }
    
    // Gửi yêu cầu
    fetch(`/invoice/order/${orderId}/request-return`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Yêu cầu trả hàng đã được gửi!', 'success');
            closeReturnModal();
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showAlert(data.message || 'Có lỗi xảy ra khi gửi yêu cầu trả hàng', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Có lỗi xảy ra khi gửi yêu cầu trả hàng', 'error');
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

<<<<<<< HEAD
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
=======
// Mở modal xem ảnh lớn
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    modalImage.src = imageSrc;
    modal.classList.remove('hidden');

    // Đóng modal khi click bên ngoài
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeImageModal();
        }
    });
}

// Đóng modal xem ảnh
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
}

// Mở modal xem video
function openVideoModal(videoSrc) {
    const modal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');
    const videoSource = modalVideo.querySelector('source');

    videoSource.src = videoSrc;
    modalVideo.load();
    modal.classList.remove('hidden');

    // Đóng modal khi click bên ngoài
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeVideoModal();
        }
    });
}

// Đóng modal xem video
function closeVideoModal() {
    const modal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');
    
    modalVideo.pause();
    modalVideo.currentTime = 0;
    modal.classList.add('hidden');
}
</script>

<!-- Modal xem ảnh lớn -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="relative max-w-4xl max-h-full p-4">
        <button onclick="closeImageModal()"
            class="absolute top-2 right-2 text-white text-2xl hover:text-gray-300 z-10">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Ảnh lớn" class="max-w-full max-h-full object-contain">
    </div>
</div>

<!-- Modal xem video -->
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="relative max-w-4xl max-h-full p-4">
        <button onclick="closeVideoModal()"
            class="absolute top-2 right-2 text-white text-2xl hover:text-gray-300 z-10">
            <i class="fas fa-times"></i>
        </button>
        <video id="modalVideo" controls class="max-w-full max-h-full">
            <source src="" type="video/mp4">
            Trình duyệt không hỗ trợ video.
        </video>
    </div>
</div>

>>>>>>> e38b7b726e194298b0c6e63bd831392758ecec33
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
