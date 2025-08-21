<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán thành công - Techvicom</title>
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
        .success-icon {
            width: 80px;
            height: 80px;
            background-color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .success-icon i {
            color: white;
            font-size: 40px;
        }
        .title {
            color: #10b981;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #6b7280;
            font-size: 16px;
        }
        .order-info {
            background-color: #f0fdf4;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-number {
            background: linear-gradient(135deg, #10b981, #059669);
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
            border: 1px solid #d1fae5;
        }
        .info-label {
            font-weight: bold;
            color: #065f46;
            margin-bottom: 5px;
        }
        .info-value {
            color: #047857;
        }
        .payment-details {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .payment-final {
            font-size: 18px;
            font-weight: bold;
            color: #10b981;
            border-top: 2px solid #e5e7eb;
            padding-top: 10px;
        }
        .next-steps {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .next-steps h3 {
            color: #1e40af;
            margin-bottom: 15px;
        }
        .step-list {
            list-style: none;
            padding: 0;
        }
        .step-list li {
            padding: 8px 0;
            color: #1e3a8a;
        }
        .step-list li:before {
            content: "✓";
            color: #10b981;
            font-weight: bold;
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
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="title">Thanh toán thành công!</h1>
            <p class="subtitle">Techvicom - Công nghệ viễn thông</p>
        </div>

        <p>Xin chào <strong>{{ $customerName }}</strong>,</p>
        
        <p>Cảm ơn bạn đã thanh toán thành công. Đơn hàng của bạn đang được xử lý và sẽ được giao trong thời gian sớm nhất.</p>

        <div class="order-info">
            <div class="order-number">
                {{ $orderNumber }}
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Ngày thanh toán</div>
                    <div class="info-value">{{ $paymentDate }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phương thức thanh toán</div>
                    <div class="info-value">{{ $paymentMethod }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Trạng thái</div>
                    <div class="info-value">Đã thanh toán</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Mã giao dịch</div>
                    <div class="info-value">{{ $order->vnpay_transaction_id ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="payment-details">
            <h3 style="color: #1f2937; margin-bottom: 15px;">Chi tiết thanh toán</h3>
            <div class="payment-row">
                <span>Số tiền thanh toán:</span>
                <span>{{ $totalAmount }} ₫</span>
            </div>
            <div class="payment-row payment-final">
                <span>Tổng cộng:</span>
                <span>{{ $totalAmount }} ₫</span>
            </div>
        </div>

        <div class="next-steps">
            <h3>📋 Các bước tiếp theo</h3>
            <ul class="step-list">
                <li>Đơn hàng của bạn đang được xử lý</li>
                <li>Chúng tôi sẽ liên hệ để xác nhận thông tin</li>
                <li>Đơn hàng sẽ được đóng gói và giao hàng</li>
                <li>Bạn sẽ nhận được thông báo khi hàng được giao</li>
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
