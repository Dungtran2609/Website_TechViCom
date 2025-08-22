<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng đã được giao thành công - Techvicom</title>
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
        .delivered-icon {
            width: 80px;
            height: 80px;
            background-color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .delivered-icon i {
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
        .thank-you {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .thank-you h3 {
            color: #92400e;
            margin-bottom: 15px;
        }
        .thank-you p {
            color: #92400e;
            margin-bottom: 10px;
        }
        .feedback-section {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .feedback-section h3 {
            color: #1e40af;
            margin-bottom: 15px;
        }
        .feedback-list {
            list-style: none;
            padding: 0;
        }
        .feedback-list li {
            padding: 8px 0;
            color: #1e3a8a;
        }
        .feedback-list li:before {
            content: "💬";
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
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 10px 5px;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="delivered-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="title">Đơn hàng đã được giao thành công!</h1>
            <p class="subtitle">Techvicom - Công nghệ viễn thông</p>
        </div>

        <p>Xin chào <strong>{{ $customerName }}</strong>,</p>
        
        <p>Chúc mừng! Đơn hàng của bạn đã được giao thành công. Cảm ơn bạn đã tin tưởng và mua sắm tại Techvicom!</p>

        <div class="order-info">
            <div class="order-number">
                {{ $orderNumber }}
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Ngày nhận hàng</div>
                    <div class="info-value">{{ $deliveredDate }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Trạng thái</div>
                    <div class="info-value">Đã nhận hàng</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Thời gian giao</div>
                    <div class="info-value">Thành công</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Đánh giá</div>
                    <div class="info-value">Chưa đánh giá</div>
                </div>
            </div>
        </div>

        <div class="thank-you">
            <h3>🎉 Cảm ơn bạn!</h3>
            <p>Chúng tôi rất vui khi được phục vụ bạn. Hy vọng bạn hài lòng với sản phẩm và dịch vụ của Techvicom.</p>
            <p>Nếu có bất kỳ vấn đề gì với sản phẩm, vui lòng liên hệ ngay với chúng tôi để được hỗ trợ.</p>
        </div>

        <div class="feedback-section">
            <h3>💬 Chia sẻ trải nghiệm của bạn</h3>
            <p>Đánh giá của bạn rất quan trọng với chúng tôi. Hãy chia sẻ trải nghiệm mua sắm của bạn:</p>
            <ul class="feedback-list">
                <li>Đánh giá sản phẩm bạn đã mua</li>
                <li>Chia sẻ trải nghiệm dịch vụ</li>
                <li>Đề xuất cải thiện cho chúng tôi</li>
                <li>Giới thiệu Techvicom cho bạn bè</li>
            </ul>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="#" class="cta-button">Đánh giá sản phẩm</a>
                <a href="#" class="cta-button">Mua sắm thêm</a>
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
