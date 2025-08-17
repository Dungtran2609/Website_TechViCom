<?php
require_once 'vendor/autoload.php';

use App\Services\VNPayService;
use App\Models\Order;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h1>VNPAY Debug Test</h1>";

// Test 1: Kiểm tra cấu hình
echo "<h2>1. Kiểm tra cấu hình VNPAY</h2>";
$vnpayService = new VNPayService();

// Lấy cấu hình hiện tại
$environment = config('vnpay.environment');
$config = config("vnpay.{$environment}");

echo "<p><strong>Environment:</strong> {$environment}</p>";
echo "<p><strong>URL:</strong> {$config['url']}</p>";
echo "<p><strong>TMN Code:</strong> {$config['tmn_code']}</p>";
echo "<p><strong>Hash Secret:</strong> " . substr($config['hash_secret'], 0, 10) . "...</p>";

// Test 2: Tạo order test
echo "<h2>2. Tạo order test</h2>";
$order = new Order();
$order->id = 999;
$order->final_total = 100000; // 100,000 VND
$order->recipient_phone = '0123456789';
$order->recipient_email = 'test@example.com';
$order->recipient_name = 'Nguyen Van A';
$order->recipient_address = '123 Test Street, Hanoi';

// Test 3: Tạo payment URL
echo "<h2>3. Tạo payment URL</h2>";
try {
    $paymentUrl = $vnpayService->createPaymentUrl($order);
    echo "<p><strong>Payment URL:</strong></p>";
    echo "<textarea style='width: 100%; height: 100px;'>" . htmlspecialchars($paymentUrl) . "</textarea>";
    
    // Parse URL để xem các tham số
    $parsedUrl = parse_url($paymentUrl);
    parse_str($parsedUrl['query'], $params);
    
    echo "<h3>Các tham số được gửi:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Parameter</th><th>Value</th></tr>";
    foreach ($params as $key => $value) {
        echo "<tr><td>{$key}</td><td>" . htmlspecialchars($value) . "</td></tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}

// Test 4: Test hash generation
echo "<h2>4. Test hash generation</h2>";
$testData = [
    'vnp_Amount' => '10000000',
    'vnp_Command' => 'pay',
    'vnp_CreateDate' => '20250815120000',
    'vnp_CurrCode' => 'VND',
    'vnp_IpAddr' => '127.0.0.1',
    'vnp_Locale' => 'vn',
    'vnp_OrderInfo' => 'Thanh toan don hang #999',
    'vnp_OrderType' => 'other',
    'vnp_ReturnUrl' => 'http://localhost/vnpay/return',
    'vnp_TmnCode' => $config['tmn_code'],
    'vnp_TxnRef' => '999',
    'vnp_Version' => '2.1.0'
];

ksort($testData);
$hashdata = "";
foreach ($testData as $key => $value) {
    if ($hashdata != "") {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata = urlencode($key) . "=" . urlencode($value);
    }
}

$secureHash = hash_hmac('sha512', $hashdata, $config['hash_secret']);

echo "<p><strong>Hash Data:</strong></p>";
echo "<textarea style='width: 100%; height: 60px;'>" . htmlspecialchars($hashdata) . "</textarea>";

echo "<p><strong>Secure Hash:</strong> {$secureHash}</p>";

echo "<h2>5. Test Links</h2>";
echo "<p><a href='/vnpay/payment/999' target='_blank'>Test VNPAY Payment</a></p>";
echo "<p><a href='/vnpay/return?vnp_Amount=10000000&vnp_BankCode=NCB&vnp_BankTranNo=VNP14123456&vnp_CardType=ATM&vnp_OrderInfo=Thanh+toan+don+hang+%23999&vnp_PayDate=20250815120000&vnp_ResponseCode=00&vnp_TmnCode={$config['tmn_code']}&vnp_TransactionNo=14123456&vnp_TransactionStatus=00&vnp_TxnRef=999&vnp_SecureHash={$secureHash}' target='_blank'>Test VNPAY Return (Success)</a></p>";
?>
