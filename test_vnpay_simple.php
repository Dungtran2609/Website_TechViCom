<?php
/**
 * Test VNPAY đơn giản - Kiểm tra URL thanh toán
 */

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\VNPayService;
use App\Models\Order;

try {
    echo "=== TEST VNPAY SIMPLE ===\n";
    
    // Tạo order test
    $order = new Order();
    $order->id = 999;
    $order->final_total = 100000; // 100,000 VND
    $order->recipient_name = 'Nguyen Van A';
    $order->recipient_phone = '0123456789';
    $order->recipient_address = '123 Test Street, Hanoi';
    
    echo "Order ID: {$order->id}\n";
    echo "Amount: {$order->final_total} VND\n";
    
    // Khởi tạo VNPAY Service
    $vnpayService = new VNPayService();
    
    // Tạo URL thanh toán
    $paymentUrl = $vnpayService->createPaymentUrl($order);
    
    echo "\n✅ VNPAY Payment URL created successfully!\n";
    echo "URL Length: " . strlen($paymentUrl) . " characters\n";
    echo "URL: " . $paymentUrl . "\n";
    
    // Kiểm tra URL có chứa các tham số cần thiết
    $urlParts = parse_url($paymentUrl);
    parse_str($urlParts['query'], $queryParams);
    
    echo "\n=== URL PARAMETERS ===\n";
    foreach ($queryParams as $key => $value) {
        if (strlen($value) > 50) {
            $value = substr($value, 0, 50) . "...";
        }
        echo "{$key}: {$value}\n";
    }
    
    // Kiểm tra chữ ký
    if (isset($queryParams['vnp_SecureHash'])) {
        echo "\n=== SIGNATURE INFO ===\n";
        echo "Secure Hash: " . substr($queryParams['vnp_SecureHash'], 0, 20) . "...\n";
        echo "Hash Type: " . ($queryParams['vnp_SecureHashType'] ?? 'N/A') . "\n";
        echo "Hash Length: " . strlen($queryParams['vnp_SecureHash']) . " characters\n";
        
        // Tạo link test
        echo "\n=== TEST LINK ===\n";
        echo "Click link below to test VNPAY payment:\n";
        echo $paymentUrl . "\n";
    }
    
    echo "\n=== TEST COMPLETED ===\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>
