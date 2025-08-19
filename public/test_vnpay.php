<?php
/**
 * Test VNPAY Payment
 * Truy cập: http://localhost/test_vnpay.php
 */

require_once '../vendor/autoload.php';

// Load Laravel
$app = require_once '../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Services\VNPayService;

echo "<h1>Test VNPAY Payment</h1>";

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
    echo "<p><strong>Customer:</strong> {$order->recipient_name}</p>";
    echo "<p><strong>Phone:</strong> {$order->recipient_phone}</p>";
    
    // Tạo VNPAY Service
    $vnpayService = new VNPayService();
    
    // Tạo payment URL
    $paymentUrl = $vnpayService->createPaymentUrl($order);
    
    echo "<h2>VNPAY Payment URL</h2>";
    echo "<p><a href='{$paymentUrl}' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Thanh toán VNPAY</a></p>";
    
    echo "<h2>Test Information</h2>";
    echo "<ul>";
    echo "<li><strong>Số thẻ:</strong> 4200000000000000</li>";
    echo "<li><strong>Ngày hết hạn:</strong> 12/25</li>";
    echo "<li><strong>CVV:</strong> 123</li>";
    echo "<li><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</li>";
    echo "<li><strong>OTP:</strong> 123456</li>";
    echo "</ul>";
    
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
    color: #007bff; 
    text-align: center; 
    border-bottom: 3px solid #007bff;
    padding-bottom: 10px;
}
h2 { 
    color: #495057; 
    margin-top: 30px; 
    border-bottom: 2px solid #007bff; 
    padding-bottom: 10px; 
}
ul { 
    background: white; 
    padding: 20px; 
    border-radius: 5px; 
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
}
li { 
    margin: 10px 0; 
}
</style>
