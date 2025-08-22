<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã xác nhận tra cứu hóa đơn</title>
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
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
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
        .verification-code {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            letter-spacing: 5px;
        }
        .info-box {
            background-color: #f3f4f6;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
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
            <h1 class="title">Mã xác nhận tra cứu hóa đơn</h1>
            <p class="subtitle">Techvicom - Công nghệ viễn thông</p>
        </div>

        <p>Xin chào,</p>
        
        <p>Bạn đã yêu cầu tra cứu hóa đơn với email: <strong>{{ $email }}</strong></p>
        
        <p>Vui lòng sử dụng mã xác nhận dưới đây để hoàn tất quá trình tra cứu:</p>

        <div class="verification-code">
            {{ $verification_code }}
        </div>

        <div class="info-box">
            <strong>Lưu ý:</strong>
            <ul>
                <li>Mã xác nhận có hiệu lực trong {{ $expires_in }}</li>
                <li>Không chia sẻ mã này với bất kỳ ai</li>
                <li>Mã chỉ có thể sử dụng một lần</li>
            </ul>
        </div>

        <div class="warning">
            <strong>⚠️ Cảnh báo bảo mật:</strong>
            <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này và liên hệ ngay với chúng tôi.</p>
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
