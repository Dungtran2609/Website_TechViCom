@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chi tiết đơn hàng #{{ $orderData['id'] }}</h1>
    <div class="d-flex gap-2">
        {{-- Xác nhận thanh toán - CHỈ cho chuyển khoản khi đơn đang chờ xử lý --}}
        @if(
            ($orderData['payment_method'] === 'bank_transfer') &&
            ($orderData['status'] === 'pending') &&
            ($orderData['payment_status'] === 'pending')
        )
            <form action="{{ route('admin.orders.updateOrders', $orderData['id']) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Bạn đã nhận chuyển khoản? Chuyển trạng thái sang ĐÃ THANH TOÁN?');">
                @csrf
                <input type="hidden" name="payment_status" value="paid">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-credit-card me-1"></i> Xác nhận thanh toán
                </button>
            </form>
        @endif

        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
        <a href="{{ route('admin.orders.edit', $orderData['id']) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Sửa
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Thông tin chính -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Thông tin đơn hàng</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-medium">Trạng thái:</label>
                    <div>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'received' => 'success',
                                'cancelled' => 'danger',
                                'returned' => 'secondary',
                            ];
                            $badge = $statusColors[$orderData['status']] ?? 'light';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ $orderData['status_vietnamese'] }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Trạng thái thanh toán:</label>
                    <div>
                        @php
                            $paymentColors = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'paid' => 'success',
                                'failed' => 'danger',
                                'cancelled' => 'secondary',
                            ];
                            $paymentBadge = $paymentColors[$orderData['payment_status']] ?? 'light';
                        @endphp
                        <span class="badge bg-{{ $paymentBadge }}">{{ $orderData['payment_status_vietnamese'] ?? $orderData['payment_status'] }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Ngày giao hàng:</label>
                    <div class="text-muted">{{ $orderData['shipped_at'] ?: 'Chưa giao hàng' }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Người đặt hàng:</label>
                    <div class="text-dark">{{ $orderData['user_name'] }}</div>
                    <div class="text-muted">{{ $orderData['user_email'] }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-medium">Người nhận:</label>
                    <div class="text-dark">{{ $orderData['recipient_name'] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Điện thoại:</label>
                    <div class="text-muted">{{ $orderData['recipient_phone'] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Địa chỉ giao:</label>
                    <div class="text-muted">
                        @php
                            $parts = array_filter([
                                $orderData['address'],
                                $orderData['ward'] ? 'phường ' . $orderData['ward'] : null,
                                $orderData['district'] ? 'quận ' . $orderData['district'] : null,
                                $orderData['city']
                            ]);
                        @endphp
                        {{ implode(', ', $parts) }}
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Phương thức vận chuyển:</label>
                    <div class="text-muted">{{ $orderData['shipping_method_name'] ?? 'Chưa chọn' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thông tin thanh toán -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Thông tin thanh toán</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-medium">Phương thức thanh toán:</label>
                    <div class="text-muted">{{ $orderData['payment_method_vietnamese'] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Mã coupon:</label>
                    <div class="text-muted">{{ $orderData['coupon_code'] ?? 'Không có' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-medium">Phí vận chuyển:</label>
                    <div class="text-dark">{{ number_format($orderData['shipping_fee'], 0) }} VND</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Giảm giá coupon:</label>
                    <div class="text-danger">-{{ number_format($orderData['coupon_discount'] ?? 0, 0) }} VND</div>
                </div>
                @if(($orderData['vnpay_discount'] ?? 0) > 0)
                <div class="mb-3">
                    <label class="form-label fw-medium">Giảm giá VNPay:</label>
                    <div class="text-danger">-{{ number_format(($orderData['vnpay_discount'] ?? 0) / 100, 0) }} VND</div>
                </div>
                @endif
                <div class="mb-3">
                    <label class="form-label fw-medium">Tổng thanh toán:</label>
                    <div class="text-dark fw-bold fs-5">{{ number_format($orderData['final_total'], 0) }} VND</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chi tiết sản phẩm -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Chi tiết sản phẩm</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Thương hiệu</th>
                        <th>Danh mục</th>
                        <th>Biến thể</th>
                        <th>Tồn kho</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderData['order_items'] as $item)
                        <tr>
                            <td style="width:80px;">
                                @php
                                    $imageUrl = null;
                                    if (!empty($item['image_product_url'])) {
                                        $imageUrl = $item['image_product_url'];
                                    } elseif (!empty($item['product_thumbnail'])) {
                                        $imageUrl = asset('storage/' . ltrim($item['product_thumbnail'], '/'));
                                    }
                                @endphp
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" class="img-fluid rounded"
                                         style="width:60px; height:60px; object-fit:cover;" alt="{{ $item['name_product'] }}"
                                         onerror="this.onerror=null;this.src='{{ asset('client_css/images/placeholder.svg') }}'">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width:60px; height:60px;">
                                        <span class="text-muted small">Không có ảnh</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-0 text-dark fw-medium">{{ $item['name_product'] }}</h6>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $item['brand_name'] }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $item['category_name'] }}</span>
                            </td>
                            <td>
                                @foreach($item['attributes'] as $attr)
                                    <span class="badge bg-info text-dark me-1">{{ $attr['name'] }}: {{ $attr['value'] }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="text-muted">{{ $item['stock'] }}</span>
                            </td>
                            <td>
                                <span class="text-dark fw-medium">{{ $item['quantity'] }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ number_format($item['price'], 0) }} VND</span>
                            </td>
                            <td>
                                <span class="text-dark fw-medium">{{ number_format($item['total'], 0) }} VND</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


        
<!-- Hành động -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Hành động</h5>
    </div>
    <div class="card-body">
        <div class="d-flex gap-2">
            @if($orderData['status'] === 'pending')
                {{-- Xác nhận đơn - CHỈ cho phép khi đã thanh toán hoặc COD --}}
                @if($orderData['payment_status'] === 'paid' || $orderData['payment_method'] === 'cod')
                    <form action="{{ route('admin.orders.updateOrders', $orderData['id']) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Bạn có chắc muốn xác nhận đơn hàng này?');">
                        @csrf
                        <input type="hidden" name="status" value="processing">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Xác nhận đơn
                        </button>
                    </form>
                @else
                    <button type="button" class="btn btn-success" disabled title="Cần xác nhận thanh toán trước">
                        <i class="fas fa-check me-1"></i> Xác nhận đơn
                    </button>
                @endif

            @elseif($orderData['status'] === 'processing')
                {{-- Chuyển sang đang giao hàng --}}
                <form action="{{ route('admin.orders.updateOrders', $orderData['id']) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Bạn có chắc muốn chuyển sang trạng thái Đang giao hàng?');">
                    @csrf
                    <input type="hidden" name="status" value="shipped">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-shipping-fast me-1"></i> Chuyển sang Đang giao hàng
                    </button>
                </form>

            @elseif($orderData['status'] === 'shipped')
                {{-- Xác nhận đã giao hàng --}}
                <form action="{{ route('admin.orders.updateOrders', $orderData['id']) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Xác nhận đã giao hàng thành công?');">
                    @csrf
                    <input type="hidden" name="status" value="delivered">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i> Xác nhận đã giao hàng
                    </button>
                </form>

            @elseif($orderData['status'] === 'delivered')
                {{-- BƯỚC 1: khách đã nhận --}}

            @elseif($orderData['status'] === 'received')
                {{-- BƯỚC 2: chỉ cho xác nhận thanh toán khi COD và chưa thanh toán --}}
                @if($orderData['payment_method'] === 'cod' && $orderData['payment_status'] === 'pending')
                    <form action="{{ route('admin.orders.updateOrders', $orderData['id']) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Xác nhận khách đã thanh toán COD sau khi nhận hàng?');">
                        @csrf
                        <input type="hidden" name="payment_status" value="paid">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-credit-card me-1"></i> Xác nhận thanh toán (COD)
                        </button>
                    </form>
                @endif
            @endif

            <a href="{{ route('admin.orders.edit', $orderData['id']) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Sửa
            </a>

            {{-- Reset VNPay Counter - chỉ hiển thị nếu có vnpay_cancel_count > 0 --}}
            @if(isset($orderData['vnpay_cancel_count']) && $orderData['vnpay_cancel_count'] > 0)
                <form action="{{ route('admin.orders.reset-vnpay-counter', $orderData['id']) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Bạn có chắc muốn reset VNPay cancel counter cho đơn hàng này?');">
                    @csrf
                    <button type="submit" class="btn btn-warning" title="Reset VNPay cancel counter (hiện tại: {{ $orderData['vnpay_cancel_count'] }})">
                        <i class="fas fa-undo me-1"></i> Reset VNPay Counter
                    </button>
                </form>
            @endif

            {{-- Nút Xóa - CHỈ cho phép khi đã thanh toán và trạng thái là received --}}
            <form action="{{ route('admin.orders.destroy', $orderData['id']) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Bạn có chắc muốn chuyển đơn hàng này vào thùng rác?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                    @if(!in_array($orderData['status'], ['cancelled', 'returned', 'received'])) disabled @endif
                    title="@if(!in_array($orderData['status'], ['cancelled', 'returned', 'received'])) Chỉ có thể xóa khi đơn đã hủy, đã trả hoặc đã nhận hàng @endif">
                    <i class="fas fa-trash me-1"></i> Xóa
                </button>
            </form>
        </div>
    </div>
</div>
@endsection