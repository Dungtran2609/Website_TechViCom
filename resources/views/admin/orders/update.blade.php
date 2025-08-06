@extends('admin.layouts.app')

@section('content')
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Sửa đơn hàng #{{ $orderData['id'] }}</h1>
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <a href="{{ route('admin.orders.show', $orderData['id']) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-eye"></i> Xem
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('admin.orders.updateOrders', $orderData['id']) }}" method="POST">
                @csrf

                {{-- Thông tin đơn hàng --}}
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">1. Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">

                                <label for="status" class="form-label">Trạng thái:</label>
                                <select name="status" class="form-control" @if (in_array($orderData['status'], ['cancelled', 'returned'])) disabled @endif>
                                    <option value="" disabled {{ !old('status') && !in_array($orderData['status'], ['pending', 'processing', 'shipped', 'delivered']) ? 'selected' : '' }}>Chọn trạng thái</option>
                                    @if ($orderData['status'] === 'pending')
                                        <option value="pending" {{ old('status', $orderData['status']) === 'pending' ? 'selected' : '' }}>Đang chờ xử lý
                                        </option>
                                        <option value="processing" {{ old('status') === 'processing' ? 'selected' : '' }}>Đang xử lý
                                        </option>
                                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy
                                        </option>
                                    @elseif ($orderData['status'] === 'processing')
                                        <option value="processing" {{ old('status', $orderData['status']) === 'processing' ? 'selected' : '' }}>Đang xử lý
                                        </option>
                                        <option value="shipped" {{ old('status') === 'shipped' ? 'selected' : '' }}>Đã giao</option>
                                    @elseif ($orderData['status'] === 'shipped')
                                        <option value="shipped" {{ old('status', $orderData['status']) === 'shipped' ? 'selected' : '' }}>Đã giao</option>
                                        <option value="delivered" {{ old('status') === 'delivered' ? 'selected' : '' }}>Đã nhận
                                        </option>
                                    @elseif ($orderData['status'] === 'delivered')
                                        <option value="delivered" {{ old('status', $orderData['status']) === 'delivered' ? 'selected' : '' }}>Đã nhận</option>
                                        <option value="returned" {{ old('status') === 'returned' ? 'selected' : '' }}>Đã trả hàng
                                        </option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                                <select id="payment_method" name="payment_method" class="form-select" @if($orderData['status'] !== 'pending') disabled @endif>
                                    <option value="credit_card" {{ $orderData['payment_method'] === 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng/ghi nợ</option>
                                    <option value="bank_transfer" {{ $orderData['payment_method'] === 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                                    <option value="cod" {{ $orderData['payment_method'] === 'cod' ? 'selected' : '' }}>COD
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="shipping_method_id" class="form-label">Phương thức vận chuyển</label>
                                <select id="shipping_method_id" name="shipping_method_id" class="form-select"
                                    @if($orderData['status'] !== 'pending') disabled @endif>
                                    @foreach($orderData['shipping_methods'] as $method)
                                        <option value="{{ $method->id }}" {{ $orderData['shipping_method_id'] == $method->id ? 'selected' : '' }}>
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Địa chỉ giao hàng --}}
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">2. Địa chỉ giao hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="recipient_name" class="form-label">Tên người nhận</label>
                                <input id="recipient_name" type="text" name="recipient_name" class="form-control"
                                    value="{{ old('recipient_name', $orderData['recipient_name']) }}" @if($orderData['status'] !== 'pending') readonly @endif>
                            </div>
                            <div class="col-md-4">
                                <label for="recipient_phone" class="form-label">Số điện thoại</label>
                                <input id="recipient_phone" type="text" name="recipient_phone" class="form-control"
                                    value="{{ old('recipient_phone', $orderData['recipient_phone']) }}" @if($orderData['status'] !== 'pending') readonly @endif>
                            </div>
                            <div class="col-md-4">
                                <label for="recipient_address" class="form-label">Địa chỉ</label>
                                <input id="recipient_address" type="text" name="recipient_address" class="form-control"
                                    value="{{ old('recipient_address', $orderData['recipient_address']) }}"
                                    @if($orderData['status'] !== 'pending') readonly @endif>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Coupon --}}
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">3. Áp dụng Coupon</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <select name="coupon_id" class="form-select" @if($orderData['status'] !== 'pending') disabled @endif>
                                <option value="">Không dùng coupon</option>
                                @foreach($orderData['coupons'] as $cp)
                                                            <option value="{{ $cp->id }}" {{ $orderData['coupon_id'] == $cp->id ? 'selected' : '' }}>
                                                                {{ $cp->code }} –
                                                                {{ $cp->discount_type == 'percent'
                                    ? $cp->value . '%'
                                    : number_format($cp->value, 0, ',', '.') . ' VND' }}
                                                            </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

    {{-- Shipping --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">4. Phí vận chuyển</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="shipping_fee" class="form-label">Phí vận chuyển (VND)</label>
                <input type="number" id="shipping_fee" name="shipping_fee" class="form-control"
                    value="{{ old('shipping_fee', $orderData['shipping_fee'] ?? 0) }}" min="0" @if($orderData['status'] !== 'pending') readonly @endif>
            </div>
        </div>
    </div>


                {{-- Chi tiết sản phẩm --}}
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">4. Chi tiết sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderData['order_items'] as $index => $item)
                                        @php $itTotal = $item['quantity'] * $item['price']; @endphp
                                        <tr>
                                            <td>{{ $item['name_product'] }}</td>
                                            <td>
                                                <input type="number" name="order_items[{{ $index }}][quantity]"
                                                    value="{{ old("order_items.$index.quantity", $item['quantity']) }}"
                                                    class="form-control form-control-sm" min="1" @if($orderData['status'] !== 'pending') readonly @endif>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="order_items[{{ $index }}][price]"
                                                    value="{{ old("order_items.$index.price", $item['price']) }}"
                                                    class="form-control form-control-sm" min="0" @if($orderData['status'] !== 'pending') readonly @endif>
                                            </td>
                                            <td>{{ number_format($itTotal, 0, ',', '.') }} VND</td>
                                            <input type="hidden" name="order_items[{{ $index }}][id]" value="{{ $item['id'] }}">
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <label class="form-label">Tổng sản phẩm</label>
                                <input class="form-control-plaintext fw-bold" readonly
                                    value="{{ number_format($orderData['subtotal'] ?? 0, 0, ',', '.') }} VND">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phí vận chuyển</label>
                                <input class="form-control-plaintext fw-bold" readonly
                                    value="{{ number_format($orderData['shipping_fee'] ?? 0, 0, ',', '.') }} VND">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Giảm giá coupon</label>
                                <input class="form-control-plaintext text-danger fw-bold" readonly
                                    value="-{{ number_format($orderData['coupon_discount'] ?? 0, 0, ',', '.') }} VND">
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <h4>Tổng thanh toán: <span class="text-primary">
                                    {{ number_format($orderData['final_total'] ?? 0, 0, ',', '.') }} VND
                                </span></h4>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-5">
                    <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
                    <a href="{{ route('admin.orders.show', $orderData['id']) }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
@endsection
