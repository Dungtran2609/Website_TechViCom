<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng - Techvicom</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            color: #2563eb;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #6b7280;
            font-size: 16px;
        }
        .order-info {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-number {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .info-item {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .info-label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }
        .info-value {
            color: #6b7280;
        }
        .products-section {
            margin: 30px 0;
        }
        .product-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: #ffffff;
        }
        .product-image {
            width: 60px;
            height: 60px;
            background-color: #f3f4f6;
            border-radius: 8px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-details {
            flex: 1;
        }
        .product-name {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .product-meta {
            color: #6b7280;
            font-size: 14px;
        }
        .product-price {
            text-align: right;
            font-weight: bold;
            color: #059669;
        }
        .total-section {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-final {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            border-top: 2px solid #e5e7eb;
            padding-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .contact-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .contact-info h4 {
            color: #1f2937;
            margin-bottom: 10px;
        }
        .contact-info p {
            margin: 5px 0;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">Xác nhận đơn hàng</h1>
            <p class="subtitle">Techvicom - Công nghệ viễn thông</p>
        </div>

        <p>Xin chào <strong>{{ $customerName }}</strong>,</p>
        
        <p>Cảm ơn bạn đã đặt hàng tại Techvicom. Chúng tôi đã nhận được đơn hàng của bạn và đang xử lý.</p>

        <div class="order-number">
            {{ $orderNumber }}
        </div>

        <div class="order-info">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Ngày đặt hàng</div>
                    <div class="info-value">{{ $orderDate }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phương thức thanh toán</div>
                    <div class="info-value">{{ $paymentMethod }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Địa chỉ giao hàng</div>
                    <div class="info-value">{{ $shippingAddress }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Trạng thái</div>
                    <div class="info-value">Đang xử lý</div>
                </div>
            </div>
        </div>

        <div class="products-section">
            <h3 style="color: #1f2937; margin-bottom: 20px;">Sản phẩm đã đặt</h3>
            @foreach($orderItems as $item)
            <div class="product-item">
                <div class="product-image">
                    <i class="fas fa-box text-gray-400"></i>
                </div>
                <div class="product-details">
                    <div class="product-name">{{ $item->name_product ?? 'N/A' }}</div>
                    <div class="product-meta">Số lượng: {{ $item->quantity }}</div>
                </div>
                <div class="product-price">
                    {{ number_format($item->price) }} ₫
                </div>
            </div>
            @endforeach
        </div>

        <div class="total-section">
            <div class="total-row">
                <span>Tạm tính:</span>
                <span>{{ number_format($order->total_amount) }} ₫</span>
            </div>
            <div class="total-row">
                <span>Phí vận chuyển:</span>
                <span>{{ number_format($order->shipping_fee) }} ₫</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span>Giảm giá:</span>
                <span>-{{ number_format($order->discount_amount) }} ₫</span>
            </div>
            @endif
            <div class="total-row total-final">
                <span>Tổng cộng:</span>
                <span>{{ $totalAmount }} ₫</span>
            </div>
        </div>

        <div class="contact-info">
            <h4>📞 Thông tin liên hệ hỗ trợ:</h4>
            <p><strong>Hotline:</strong> 1800.6601</p>
            <p><strong>Email:</strong> techvicom@gmail.com</p>
            <p><strong>Thời gian làm việc:</strong> 8:00 - 22:00 (Thứ 2 - Chủ nhật)</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Techvicom. Tất cả quyền được bảo lưu.</p>
            <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>
</html>
