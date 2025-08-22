<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng đã được giao - Techvicom</title>
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
        .shipping-icon {
            width: 80px;
            height: 80px;
            background-color: #8b5cf6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .shipping-icon i {
            color: white;
            font-size: 40px;
        }
        .title {
            color: #8b5cf6;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #6b7280;
            font-size: 16px;
        }
        .order-info {
            background-color: #faf5ff;
            border: 2px solid #8b5cf6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-number {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
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
            border: 1px solid #e9d5ff;
        }
        .info-label {
            font-weight: bold;
            color: #581c87;
            margin-bottom: 5px;
        }
        .info-value {
            color: #7c3aed;
        }
        .shipping-details {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .shipping-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .shipping-address {
            background-color: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
        }
        .shipping-address h4 {
            color: #0c4a6e;
            margin-bottom: 10px;
        }
        .shipping-address p {
            color: #0369a1;
            margin: 5px 0;
        }
        .delivery-info {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .delivery-info h3 {
            color: #92400e;
            margin-bottom: 15px;
        }
        .delivery-list {
            list-style: none;
            padding: 0;
        }
        .delivery-list li {
            padding: 8px 0;
            color: #92400e;
        }
        .delivery-list li:before {
            content: "🚚";
            margin-right: 10px;
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
            <div class="shipping-icon">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <h1 class="title">Đơn hàng đã được giao!</h1>
            <p class="subtitle">Techvicom - Công nghệ viễn thông</p>
        </div>

        <p>Xin chào <strong>{{ $customerName }}</strong>,</p>
        
        <p>Đơn hàng của bạn đã được giao và đang trên đường đến với bạn. Vui lòng chuẩn bị nhận hàng!</p>

        <div class="order-info">
            <div class="order-number">
                {{ $orderNumber }}
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Ngày giao hàng</div>
                    <div class="info-value">{{ $shippedDate }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Trạng thái</div>
                    <div class="info-value">Đang giao hàng</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Dự kiến nhận</div>
                    <div class="info-value">1-3 ngày tới</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phương thức giao</div>
                    <div class="info-value">Giao hàng tận nơi</div>
                </div>
            </div>
        </div>

        <div class="shipping-address">
            <h4>📍 Địa chỉ giao hàng</h4>
            <p><strong>Người nhận:</strong> {{ $customerName }}</p>
            <p><strong>Địa chỉ:</strong> {{ $shippingAddress }}</p>
        </div>

        <div class="delivery-info">
            <h3>📦 Thông tin giao hàng</h3>
            <ul class="delivery-list">
                <li>Đơn hàng đã được đóng gói cẩn thận</li>
                <li>Đang được vận chuyển đến địa chỉ của bạn</li>
                <li>Shipper sẽ liên hệ trước khi giao hàng</li>
                <li>Vui lòng chuẩn bị nhận hàng và kiểm tra kỹ</li>
            </ul>
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
