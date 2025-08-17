<?php
/**
 * Test VNPAY Service sau khi fix
 * Kiểm tra việc tạo URL thanh toán và chữ ký
 */

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\VNPayService;
use App\Models\Order;

try {
    echo "=== TEST VNPAY SERVICE ===\n";
    
    // Tạo order test
    $order = new Order();
    $order->id = 999;
    $order->final_total = 100000; // 100,000 VND
    $order->recipient_name = 'Nguyen Van A';
    $order->recipient_phone = '0123456789';
    $order->recipient_address = '123 Test Street, Hanoi';
    
    echo "Order ID: {$order->id}\n";
    echo "Amount: {$order->final_total} VND\n";
    echo "Recipient: {$order->recipient_name}\n";
    
    // Khởi tạo VNPAY Service
    $vnpayService = new VNPayService();
    echo "VNPAY Service initialized successfully\n";
    
    // Tạo URL thanh toán
    $paymentUrl = $vnpayService->createPaymentUrl($order);
    echo "Payment URL created successfully\n";
    echo "URL: " . substr($paymentUrl, 0, 100) . "...\n";
    
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
        echo "\n=== SIGNATURE VERIFICATION ===\n";
        echo "Secure Hash: " . substr($queryParams['vnp_SecureHash'], 0, 20) . "...\n";
        echo "Hash Type: " . ($queryParams['vnp_SecureHashType'] ?? 'N/A') . "\n";
        
        // Kiểm tra hash data
        echo "Hash Data Length: " . strlen($queryParams['vnp_SecureHash']) . " characters\n";
        echo "Hash Algorithm: HMAC-SHA512\n";
        
        // Tạo test request với dữ liệu hợp lệ
        $testData = [
            'vnp_Amount' => '10000000',
            'vnp_BankCode' => 'NCB',
            'vnp_BankTranNo' => 'VNP14123456',
            'vnp_CardType' => 'ATM',
            'vnp_OrderInfo' => 'Thanh toan cho don hang #999',
            'vnp_PayDate' => '20250815124707',
            'vnp_ResponseCode' => '00',
            'vnp_TmnCode' => '2WZSC2P3',
            'vnp_TransactionNo' => '14123456',
            'vnp_TransactionStatus' => '00',
            'vnp_TxnRef' => '999',
            'vnp_SecureHash' => $queryParams['vnp_SecureHash'],
            'vnp_SecureHashType' => 'HmacSHA512'
        ];
        
        $testRequest = new \Illuminate\Http\Request();
        $testRequest->merge($testData);
        
        $result = $vnpayService->processReturn($testRequest);
        echo "Signature Valid: " . ($result['is_valid'] ? 'YES' : 'NO') . "\n";
        echo "Response Code: " . ($result['response_code'] ?? 'N/A') . "\n";
        echo "Message: " . ($result['message'] ?? 'N/A') . "\n";
    }
    
    echo "\n=== TEST COMPLETED ===\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
h1 { color: #28a745; text-align: center; }
h2 { color: #495057; margin-top: 30px; border-bottom: 2px solid #28a745; padding-bottom: 10px; }
textarea { border: 1px solid #ddd; border-radius: 5px; }
ul { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
li { margin: 10px 0; }
</style>
