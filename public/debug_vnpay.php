<?php
/**
 * Debug VNPAY Signature
 * Truy cập: http://localhost/debug_vnpay.php
 */

require_once '../vendor/autoload.php';

// Load Laravel
$app = require_once '../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Services\VNPayService;

echo "<h1>Debug VNPAY Signature</h1>";

try {
    // Lấy order mới nhất
    $order = Order::latest()->first();
    
    if (!$order) {
        echo "<p style='color: red;'>Không tìm thấy order nào</p>";
        exit;
    }
    
    echo "<h2>Order Information</h2>";
    echo "<p><strong>Order ID:</strong> {$order->id}</p>";
    echo "<p><strong>Amount:</strong> " . number_format($order->final_total) . " VND</p>";
    
    // Tạo VNPAY Service
    $vnpayService = new VNPayService();
    
    // Tạo payment URL
    $paymentUrl = $vnpayService->createPaymentUrl($order);
    
    echo "<h2>VNPAY Payment URL</h2>";
    echo "<p><a href='{$paymentUrl}' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test VNPAY</a></p>";
    
    // Parse URL để xem parameters
    $urlParts = parse_url($paymentUrl);
    parse_str($urlParts['query'], $params);
    
    echo "<h2>Parameters</h2>";
    echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
    echo "<tr><th>Parameter</th><th>Value</th></tr>";
    foreach ($params as $key => $value) {
        echo "<tr><td>{$key}</td><td>{$value}</td></tr>";
    }
    echo "</table>";
    
    // Test signature verification
    echo "<h2>Signature Verification</h2>";
    
    $testParams = $params;
    $receivedHash = $testParams['vnp_SecureHash'] ?? null;
    unset($testParams['vnp_SecureHash']);
    
    ksort($testParams);
    
    $hashData = "";
    $i = 0;
    foreach ($testParams as $key => $value) {
        if ($i == 1) {
            $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashData .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
    }
    
    $config = config('vnpay.sandbox');
    $calculatedHash = hash_hmac('sha512', $hashData, $config['hash_secret']);
    
    echo "<p><strong>Hash Data:</strong></p>";
    echo "<textarea style='width: 100%; height: 100px;'>{$hashData}</textarea>";
    echo "<p><strong>Received Hash:</strong> {$receivedHash}</p>";
    echo "<p><strong>Calculated Hash:</strong> {$calculatedHash}</p>";
    echo "<p><strong>Match:</strong> " . ($receivedHash === $calculatedHash ? 'YES' : 'NO') . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 40px; 
    background: #f8f9fa; 
}
h1 { 
    color: #dc3545; 
    text-align: center; 
    border-bottom: 3px solid #dc3545;
    padding-bottom: 10px;
}
h2 { 
    color: #495057; 
    margin-top: 30px; 
    border-bottom: 2px solid #007bff; 
    padding-bottom: 10px; 
}
table {
    background: white;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
th {
    background: #007bff;
    color: white;
    padding: 10px;
}
td {
    padding: 10px;
    border: 1px solid #ddd;
}
textarea {
    border: 1px solid #ddd;
    border-radius: 5px;
    font-family: monospace;
    font-size: 12px;
}
</style>
