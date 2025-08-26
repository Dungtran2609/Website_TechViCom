<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .invoice-number {
            font-size: 16px;
            color: #666;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-row {
            display: table-row;
        }
        .info-cell {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            width: 30%;
            background-color: #f8f9fa;
        }
        .info-value {
            width: 70%;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .products-table th,
        .products-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .products-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .products-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-row {
            margin-bottom: 8px;
        }
        .total-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .total-value {
            display: inline-block;
            width: 120px;
            text-align: right;
        }
        .final-total {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            border-top: 2px solid #2563eb;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-processing { background-color: #dbeafe; color: #1e40af; }
        .status-shipped { background-color: #e9d5ff; color: #7c3aed; }
        .status-delivered { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">TECHVICOM</div>
        <div class="invoice-title">HÓA ĐƠN BÁN HÀNG</div>
        <div class="invoice-number">Số: {{ 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div>Ngày: {{ $order->created_at->format('d/m/Y H:i') }}</div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">Thông tin khách hàng:</div>
                <div class="info-cell info-value">
                    <strong>{{ $order->recipient_name }}</strong><br>
                    SĐT: {{ $order->recipient_phone }}<br>
                    Email: {{ $order->guest_email ?? $order->user->email ?? 'N/A' }}<br>
                    Địa chỉ: {{ $order->recipient_address }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Trạng thái đơn hàng:</div>
                <div class="info-cell info-value">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->status_vietnamese }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Phương thức thanh toán:</div>
                <div class="info-cell info-value">
                    @if($order->payment_method === 'cod')
                        Thanh toán khi nhận hàng (COD)
                    @elseif($order->payment_method === 'bank_transfer')
                        Chuyển khoản ngân hàng
                    @else
                        {{ ucfirst($order->payment_method) }}
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Phương thức giao hàng:</div>
                <div class="info-cell info-value">
                    {{ $order->shippingMethod->name ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <table class="products-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th>Thông số</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->name_product ?? ($item->productVariant->product->name ?? 'N/A') }}</strong>
                </td>
                <td>
                    @if($item->productVariant && $item->productVariant->attributeValues->count() > 0)
                        @foreach($item->productVariant->attributeValues as $attrValue)
                            {{ $attrValue->attribute->name }}: {{ $attrValue->value }}<br>
                        @endforeach
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ number_format($item->price) }} ₫</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price * $item->quantity) }} ₫</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Tạm tính:</span>
            <span class="total-value">{{ number_format($order->total_amount) }} ₫</span>
        </div>
        <div class="total-row">
            <span class="total-label">Phí vận chuyển:</span>
            <span class="total-value">{{ number_format($order->shipping_fee) }} ₫</span>
        </div>
        @if($order->discount_amount > 0)
        <div class="total-row">
            <span class="total-label">Giảm giá:</span>
            <span class="total-value">-{{ number_format($order->discount_amount) }} ₫</span>
        </div>
        @endif
        <div class="total-row final-total">
            <span class="total-label">Tổng cộng:</span>
            <span class="total-value">{{ number_format($order->final_total) }} ₫</span>
        </div>
    </div>

    @if($order->order_notes)
    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">Ghi chú:</div>
                <div class="info-cell info-value">{{ $order->order_notes }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>Cảm ơn quý khách đã mua hàng!</strong></p>
        <p>Hóa đơn này được tạo tự động bởi hệ thống Techvicom</p>
        <p>Mọi thắc mắc vui lòng liên hệ: support@techvicom.com</p>
    </div>
</body>
</html>
