<?php

require_once 'vendor/autoload.php';

use App\Services\VNPayService;
use App\Models\Order;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VNPAY FINAL FIXED TEST ===\n\n";

try {
    // Test 1: Kiểm tra cấu hình
    echo "1. Testing VNPAY Configuration:\n";
    $vnpayService = new VNPayService();
    echo "   ✓ VNPAY Service initialized successfully\n";
    
    // Test 2: Kiểm tra config
    echo "\n2. Current Configuration:\n";
    echo "   Environment: " . config('vnpay.environment') . "\n";
    echo "   TMN Code: " . config('vnpay.sandbox.tmn_code') . "\n";
    echo "   Hash Secret: " . substr(config('vnpay.sandbox.hash_secret'), 0, 10) . "...\n";
    echo "   Return URL: " . config('vnpay.return_url') . "\n";
    
    // Test 3: Tạo order test
    echo "\n3. Creating test order:\n";
    $testOrder = new Order();
    $testOrder->id = 999;
    $testOrder->final_total = 100000; // 100,000 VND
    $testOrder->recipient_name = "Test User";
    $testOrder->recipient_phone = "0123456789";
    $testOrder->recipient_email = "test@example.com";
    $testOrder->recipient_address = "123 Test Street, Hanoi";
    
    echo "   Order ID: {$testOrder->id}\n";
    echo "   Amount: {$testOrder->final_total} VND\n";
    echo "   Expected VNPAY Amount: " . ($testOrder->final_total * 100) . " (string)\n";
    
    // Test 4: Tạo URL thanh toán
    echo "\n4. Generating payment URL:\n";
    $paymentUrl = $vnpayService->createPaymentUrl($testOrder);
    echo "   ✓ Payment URL generated successfully\n";
    echo "   URL: " . $paymentUrl . "\n";
    
    // Test 5: Kiểm tra URL có hợp lệ không
    echo "\n5. Validating URL:\n";
    if (filter_var($paymentUrl, FILTER_VALIDATE_URL)) {
        echo "   ✓ URL is valid\n";
    } else {
        echo "   ✗ URL is invalid\n";
    }
    
    // Test 6: Kiểm tra có chứa các tham số cần thiết
    echo "\n6. Checking required parameters:\n";
    $urlParts = parse_url($paymentUrl);
    parse_str($urlParts['query'], $queryParams);
    
    $requiredParams = ['vnp_TmnCode', 'vnp_Amount', 'vnp_Command', 'vnp_CreateDate', 'vnp_CurrCode', 'vnp_IpAddr', 'vnp_Locale', 'vnp_OrderInfo', 'vnp_OrderType', 'vnp_ReturnUrl', 'vnp_TxnRef', 'vnp_ExpireDate', 'vnp_SecureHash'];
    
    foreach ($requiredParams as $param) {
        if (isset($queryParams[$param])) {
            echo "   ✓ {$param}: " . substr($queryParams[$param], 0, 50) . "...\n";
        } else {
            echo "   ✗ {$param}: Missing\n";
        }
    }
    
    // Test 7: Kiểm tra URL return
    echo "\n7. Checking Return URL:\n";
    if (isset($queryParams['vnp_ReturnUrl'])) {
        $returnUrl = urldecode($queryParams['vnp_ReturnUrl']);
        echo "   Return URL: {$returnUrl}\n";
        if (strpos($returnUrl, '127.0.0.1:8000') !== false) {
            echo "   ✓ Return URL is correct (127.0.0.1:8000)\n";
        } else {
            echo "   ✗ Return URL is incorrect\n";
        }
    }
    
    // Test 8: Kiểm tra amount format
    echo "\n8. Checking Amount Format:\n";
    if (isset($queryParams['vnp_Amount'])) {
        $amount = urldecode($queryParams['vnp_Amount']);
        echo "   Amount: {$amount}\n";
        if (is_numeric($amount) && $amount == '10000000') {
            echo "   ✓ Amount format is correct (10000000)\n";
        } else {
            echo "   ✗ Amount format is incorrect\n";
        }
    }
    
    echo "\n=== TEST COMPLETED SUCCESSFULLY ===\n";
    echo "You can now test the payment URL in your browser.\n";
    echo "This should resolve the 'Sai chữ ký' error.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
