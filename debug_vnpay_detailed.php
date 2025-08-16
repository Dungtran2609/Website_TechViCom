<?php
/**
 * Debug VNPAY chi tiết - Kiểm tra từng bước tạo chữ ký
 */

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\VNPayService;
use App\Models\Order;

try {
    echo "=== DEBUG VNPAY DETAILED ===\n";
    
    // 1. Kiểm tra config
    echo "\n1. VNPAY CONFIG:\n";
    echo "Environment: " . config('vnpay.environment') . "\n";
    echo "TMN Code: " . config('vnpay.sandbox.tmn_code') . "\n";
    echo "Hash Secret: " . config('vnpay.sandbox.hash_secret') . "\n";
    echo "Hash Secret Length: " . strlen(config('vnpay.sandbox.hash_secret')) . "\n";
    
    // 2. Tạo order test
    $order = new Order();
    $order->id = 999;
    $order->final_total = 100000; // 100,000 VND
    $order->recipient_name = 'Nguyen Van A';
    $order->recipient_phone = '0123456789';
    $order->recipient_address = '123 Test Street, Hanoi';
    
    echo "\n2. ORDER INFO:\n";
    echo "Order ID: {$order->id}\n";
    echo "Amount: {$order->final_total} VND\n";
    
    // 3. Khởi tạo VNPAY Service
    $vnpayService = new VNPayService();
    echo "\n3. VNPAY SERVICE:\n";
    echo "Service initialized: OK\n";
    
    // 4. Tạo input data (copy từ VNPayService)
    echo "\n4. INPUT DATA CREATION:\n";
    
    $vnp_TxnRef = (string) $order->id;
    $vnp_OrderInfo = "Thanh toan cho don hang #{$order->id}";
    $vnp_OrderType = (string) config('vnpay.order_type', 'other');
    $vnp_Amount = (string) (int) round(((float) $order->final_total) * 100);
    $vnp_Locale = (string) config('vnpay.locale', 'vn');
    $vnp_IpAddr = request()->ip();
    $vnp_CreateDate = date('YmdHis');
    $expireMinutes = (int) config('vnpay.expire_time', 15);
    $vnp_ExpireDate = date('YmdHis', strtotime("+{$expireMinutes} minutes", strtotime($vnp_CreateDate)));
    
    $inputData = [
        "vnp_Version" => (string) config('vnpay.version', '2.1.0'),
        "vnp_TmnCode" => (string) config('vnpay.sandbox.tmn_code'),
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => (string) config('vnpay.command', 'pay'),
        "vnp_CreateDate" => $vnp_CreateDate,
        "vnp_CurrCode" => (string) config('vnpay.currency', 'VND'),
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => route('vnpay.return'),
        "vnp_TxnRef" => $vnp_TxnRef,
        "vnp_ExpireDate" => $vnp_ExpireDate,
        "vnp_Bill_FirstName" => "A",
        "vnp_Bill_LastName" => "Nguyen Van",
        "vnp_Bill_Mobile" => "0123456789",
        "vnp_Bill_Address" => "123 Test Street, Hanoi",
        "vnp_Bill_City" => "Hanoi",
        "vnp_Bill_Country" => "VN",
    ];
    
    echo "Input Data Count: " . count($inputData) . "\n";
    foreach ($inputData as $key => $value) {
        echo "  {$key}: {$value}\n";
    }
    
    // 5. Build hash data (copy từ VNPayService)
    echo "\n5. HASH DATA BUILDING:\n";
    
    // Bỏ 2 trường hash nếu có
    unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);
    
    // Chuỗi ký: URL-encode + sort + bỏ rỗng
    $data = array_filter($inputData, static function ($v) {
        return $v !== '' && $v !== null;
    });
    ksort($data);
    
    $pairs = [];
    foreach ($data as $k => $v) {
        $pairs[] = urlencode($k) . '=' . urlencode((string) $v);
    }
    $hashData = implode('&', $pairs);
    
    echo "Hash Data: " . $hashData . "\n";
    echo "Hash Data Length: " . strlen($hashData) . "\n";
    
    // 6. Tạo chữ ký
    echo "\n6. SIGNATURE CREATION:\n";
    
    $hashSecret = config('vnpay.sandbox.hash_secret');
    echo "Hash Secret: {$hashSecret}\n";
    echo "Hash Secret Length: " . strlen($hashSecret) . "\n";
    
    $secureHash = hash_hmac('sha512', $hashData, $hashSecret);
    echo "Secure Hash: {$secureHash}\n";
    echo "Secure Hash Length: " . strlen($secureHash) . "\n";
    
    // 7. Build final URL
    echo "\n7. FINAL URL BUILDING:\n";
    
    $inputData['vnp_SecureHashType'] = 'HmacSHA512';
    $inputData['vnp_SecureHash'] = $secureHash;
    
    $finalData = array_filter($inputData, static function ($v) {
        return $v !== '' && $v !== null;
    });
    ksort($finalData);
    
    $finalPairs = [];
    foreach ($finalData as $k => $v) {
        $finalPairs[] = urlencode($k) . '=' . urlencode((string) $v);
    }
    $finalQuery = implode('&', $finalPairs);
    
    $vnp_Url = config('vnpay.sandbox.url') . '?' . $finalQuery;
    
    echo "Final URL Length: " . strlen($vnp_Url) . "\n";
    echo "Final URL (first 200 chars): " . substr($vnp_Url, 0, 200) . "...\n";
    
    // 8. Test với VNPayService
    echo "\n8. TEST WITH VNPAY SERVICE:\n";
    
    try {
        $paymentUrl = $vnpayService->createPaymentUrl($order);
        echo "VNPayService URL Length: " . strlen($paymentUrl) . "\n";
        echo "VNPayService URL (first 200 chars): " . substr($paymentUrl, 0, 200) . "...\n";
        
        // So sánh 2 URL
        if ($paymentUrl === $vnp_Url) {
            echo "✅ URLs match exactly!\n";
        } else {
            echo "❌ URLs do not match!\n";
            echo "Manual URL: " . substr($vnp_Url, 0, 100) . "...\n";
            echo "Service URL: " . substr($paymentUrl, 0, 100) . "...\n";
        }
    } catch (Exception $e) {
        echo "❌ VNPayService Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== DEBUG COMPLETED ===\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
?>
