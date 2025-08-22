<?php

require_once 'vendor/autoload.php';

use App\Mail\DynamicMail;
use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST GỬI EMAIL ===\n\n";

// Kiểm tra cấu hình mail
echo "📧 Cấu hình Mail:\n";
echo "MAIL_MAILER: " . config('mail.default') . "\n";
echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? '***' : 'NULL') . "\n";
echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n\n";

// Test gửi email
try {
    $subject = 'Test Email - Techvicom';
    $content = '
    <html>
    <body>
        <h2>Test Email</h2>
        <p>Đây là email test từ hệ thống Techvicom.</p>
        <p>Thời gian: ' . now()->format('Y-m-d H:i:s') . '</p>
        <p>Nếu bạn nhận được email này, có nghĩa là cấu hình email đã hoạt động!</p>
    </body>
    </html>
    ';

    echo "📤 Đang gửi email test...\n";
    
    Mail::to('test@example.com')->send(new DynamicMail($subject, $content));
    
    echo "✅ Email đã được gửi thành công!\n";
    echo "📝 Kiểm tra log trong: storage/logs/laravel.log\n";
    
} catch (\Exception $e) {
    echo "❌ Lỗi gửi email: " . $e->getMessage() . "\n";
    echo "📝 Chi tiết lỗi: " . $e->getTraceAsString() . "\n";
}

echo "\n=== HƯỚNG DẪN CẤU HÌNH MAILTRAP ===\n";
echo "1. Tạo file .env trong thư mục gốc\n";
echo "2. Thêm cấu hình Mailtrap:\n";
echo "MAIL_MAILER=smtp\n";
echo "MAIL_HOST=sandbox.smtp.mailtrap.io\n";
echo "MAIL_PORT=2525\n";
echo "MAIL_USERNAME=40c4f37b913eea\n";
echo "MAIL_PASSWORD=your-password-from-mailtrap\n";
echo "MAIL_ENCRYPTION=tls\n";
echo "MAIL_FROM_ADDRESS=techvicom@gmail.com\n";
echo "MAIL_FROM_NAME=Techvicom\n";
echo "\n3. Chạy: php artisan config:clear\n";
echo "4. Test lại: php test_email.php\n";
