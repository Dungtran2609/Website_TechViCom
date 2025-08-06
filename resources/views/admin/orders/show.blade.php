@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Chi tiết đơn hàng #{{ $orderData['id'] }}</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                <a href="{{ route('admin.orders.edit', $orderData['id']) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Sửa
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Thông tin chính -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Thông tin đơn hàng</h5> <br>
                <dl class="row mb-0">
                    <dt class="col-sm-3">Trạng thái</dt>
                    <dd class="col-sm-9">{{ $orderData['status_vietnamese'] }}</dd>
                    <!-- thêm phần hiển thị trạng thái thanh toán -->
                    <dt class="col-sm-3">Trạng thái thanh toán</dt>
                    <dd class="col-sm-9">
                        {{ $orderData['payment_status_vietnamese'] ?? $orderData['payment_status'] }}
                    </dd> <br>
                    <dt class="col-sm-3">Ngày giao hàng</dt>
                    <dd class="col-sm-9">{{ $orderData['shipped_at'] }}</dd>
                    <dt class="col-sm-3">Người đặt hàng</dt>
                    <dd class="col-sm-9">{{ $orderData['user_name'] }}&nbsp;(&lt;{{ $orderData['user_email'] }}&gt;)</dd>

                    <dt class="col-sm-3">Người nhận</dt>
                    <dd class="col-sm-9">{{ $orderData['recipient_name'] }}</dd>

                    <dt class="col-sm-3">Điện thoại</dt>
                    <dd class="col-sm-9">{{ $orderData['recipient_phone'] }}</dd>

                    <dt class="col-sm-3">Địa chỉ giao</dt>
                    <dd class="col-sm-9">
                        {{ $orderData['address'] }}, phường {{ $orderData['ward'] }}, quận {{ $orderData['district'] }},
                        {{ $orderData['city'] }}
                    </dd>

                    <dt class="col-sm-3">Phí ship</dt>
                    <dd class="col-sm-9">{{ number_format($orderData['shipping_fee'], 0, ',', '.') }}&nbsp;VND</dd>

                    <dt class="col-sm-3">Vận chuyển</dt>
                    <dd class="col-sm-9">{{ $orderData['shipping_method_name'] ?? 'Chưa chọn'  }}</dd>

                    <!-- @if($orderData['coupon_discount'] > 0) -->
                    <!-- <dt class="col-sm-3">Giảm giá</dt> -->
                    <!-- <dd class="col-sm-9">-{{ number_format($orderData['coupon_discount'], 0, ',', '.') }}&nbsp;VND -->
                    <!-- ({{ $orderData['coupon_code'] }})</dd> -->
                    <!-- @endif -->
                    <dt class="col-sm-3">Mã coupon</dt>
                    <dd class="col-sm-9">{{ $orderData['coupon_code'] ?? 'Không có' }}</dd>
                    <dt class="col-sm-3">Giảm giá coupon</dt>
                    <dd class="col-sm-9">-{{ number_format($orderData['coupon_discount'] ?? 0, 0, ',', '.') }} VND</dd>
                    <dt class="col-sm-3">Tổng thanh toán</dt>
                    <dd class="col-sm-9 fw-bold text-danger">
                        {{ number_format($orderData['final_total'], 0, ',', '.') }}&nbsp;VND
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Chi tiết sản phẩm</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Sản phẩm</th>
                                <th scope="col">Thương hiệu</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Biến thể</th>
                                <th scope="col">Tồn kho</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderData['order_items'] as $item)
                                <tr>
                                    <!-- {{ dump($item['image_product_url']) }} -->

                                    <td style="width:100px;">
                                        @if(!empty($item['image_product_url']))
                                            <img src="{{ $item['image_product_url'] }}" class="img-fluid"
                                                style="max-height:80px; object-fit:cover;" alt="{{ $item['name_product'] }}">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light text-muted"
                                                style="height:80px;">
                                                <i class="fas fa-image fa-lg me-1"></i>Chưa có ảnh
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item['name_product'] }}</td>
                                    <td>{{ $item['brand_name'] }}</td>
                                    <td>{{ $item['category_name'] }}</td>
                                    <td>
                                        @foreach($item['attributes'] as $attr)
                                            <span class="badge bg-info text-dark me-1">{{ $attr['name'] }}:
                                                {{ $attr['value'] }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $item['stock'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>{{ number_format($item['price'], 0, ',', '.') }} VND</td>
                                    <td>{{ number_format($item['total'], 0, ',', '.') }} VND</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- Hành động -->
                <div class="mt-4">
                    @if($orderData['status'] === 'pending')
                        {{-- Xác nhận đơn --}}
                        <form action="{{ route('admin.orders.updateOrders', $orderData['id']) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Bạn có chắc muốn xác nhận đơn hàng này?');">
                            @csrf
                            <input type="hidden" name="status" value="processing">
                            <button type="submit" class="btn btn-success">Xác nhận đơn</button>
                        </form>

                        {{-- Xác nhận thanh toán --}}
                        @if($orderData['payment_status'] === 'pending')
                            <form action="{{ route('admin.orders.updateOrders', $orderData['id']) }}" method="POST"
                                class="d-inline ms-2"
                                onsubmit="return confirm('Bạn đã nhận thanh toán chưa? Chuyển trạng thái sang “Đã thanh toán”?');">
                                @csrf
                                <input type="hidden" name="payment_status" value="paid">
                                <button type="submit" class="btn btn-warning">
                                    Xác nhận thanh toán
                                </button>
                            </form>
                        @endif

                    @elseif($orderData['status'] === 'processing')
                        {{-- Xác nhận giao hàng --}}
                        <form action="{{ route('admin.orders.updateOrders', $orderData['id']) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Bạn có chắc muốn xác nhận giao hàng này?');">
                            @csrf
                            <input type="hidden" name="status" value="shipped">
                            <button type="submit" class="btn btn-success">Xác nhận giao hàng</button>
                        </form>
                    @endif

                    <a href="{{ route('admin.orders.edit', $orderData['id']) }}" class="btn btn-primary ms-2">Sửa</a>

                    <form action="{{ route('admin.orders.destroy', $orderData['id']) }}" method="POST" class="d-inline ms-2"
                        onsubmit="return confirm('Bạn có chắc muốn chuyển đơn hàng này vào thùng rác?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" @if(!in_array($orderData['status'], ['cancelled', 'returned', 'delivered'])) disabled @endif>
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>



            </div>
        </div>
    </div>
@endsection
