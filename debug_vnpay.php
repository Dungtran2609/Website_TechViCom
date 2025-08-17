<?php
// Simple VNPAY Debug
echo "<h1>VNPAY Configuration Debug</h1>";

// Load Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check configuration
$environment = config('vnpay.environment');
$config = config("vnpay.{$environment}");

echo "<h2>Current Configuration:</h2>";
echo "<p><strong>Environment:</strong> {$environment}</p>";
echo "<p><strong>URL:</strong> {$config['url']}</p>";
echo "<p><strong>TMN Code:</strong> {$config['tmn_code']}</p>";
echo "<p><strong>Hash Secret:</strong> " . substr($config['hash_secret'], 0, 10) . "...</p>";

// Test hash generation
echo "<h2>Test Hash Generation:</h2>";
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

// Remove empty values
$testData = array_filter($testData, function($value) {
    return $value !== null && $value !== '';
});

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

// Test URL generation
$query = "";
foreach ($testData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $config['url'] . "?" . $query . 'vnp_SecureHash=' . $secureHash;

echo "<p><strong>Generated URL:</strong></p>";
echo "<textarea style='width: 100%; height: 100px;'>" . htmlspecialchars($vnp_Url) . "</textarea>";

echo "<h2>Test Links:</h2>";
echo "<p><a href='{$vnp_Url}' target='_blank'>Test VNPAY Payment</a></p>";
?>
