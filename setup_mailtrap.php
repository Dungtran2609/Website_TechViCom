<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Config;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CẤU HÌNH MAILTRAP ===\n\n";

// Cấu hình Mailtrap trực tiếp
Config::set('mail.default', 'smtp');
Config::set('mail.mailers.smtp.host', 'sandbox.smtp.mailtrap.io');
Config::set('mail.mailers.smtp.port', 2525);
Config::set('mail.mailers.smtp.username', '40c4f37b913eea');
Config::set('mail.mailers.smtp.password', 'your-password-here'); // Bạn cần thay bằng password thật
Config::set('mail.mailers.smtp.encryption', 'tls');
Config::set('mail.from.address', 'techvicom@gmail.com');
Config::set('mail.from.name', 'Techvicom');

echo "✅ Đã cấu hình Mailtrap\n";
echo "📧 Host: sandbox.smtp.mailtrap.io\n";
echo "📧 Port: 2525\n";
echo "📧 Username: 40c4f37b913eea\n";
echo "📧 Password: [CẦN THAY BẰNG PASSWORD THẬT]\n\n";

echo "⚠️  LƯU Ý: Bạn cần thay 'your-password-here' bằng password thật từ Mailtrap\n";
echo "📝 Password có thể tìm thấy trong Mailtrap > Sandboxes > TechviCom > Integration > SMTP\n\n";

echo "Sau khi thay password, chạy:\n";
echo "php test_email.php\n";
