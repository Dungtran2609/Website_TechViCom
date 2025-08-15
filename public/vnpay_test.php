<?php
/**
 * Test VNPAY - File test đơn giản
 * Truy cập: http://localhost/vnpay_test.php
 */

echo "<h1>Test VNPAY - Kiểm tra chữ ký</h1>";

// Cấu hình VNPAY
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/vnpay/return";
$vnp_TmnCode = "2WZSC2P3";
$vnp_HashSecret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1";

// Tạo dữ liệu thanh toán
$vnp_TxnRef = "TEST_" . time();
$vnp_OrderInfo = "Thanh toan don hang test";
$vnp_OrderType = "other";
$vnp_Amount = 20000 * 100;
$vnp_Locale = "vn";
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'] ?? "127.0.0.1";

// Tạo input data
$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef,
);

// Loại bỏ các tham số rỗng
$inputData = array_filter($inputData, function($value) {
    return $value !== null && $value !== '';
});

// Sắp xếp theo thứ tự alphabet
ksort($inputData);

echo "<h2>Input Data (Sorted)</h2>";
echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
foreach ($inputData as $key => $value) {
    echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "\n";
}
echo "</pre>";

// Tạo query string
$query = "";
foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

// Loại bỏ & cuối cùng từ query
$query = rtrim($query, '&');

// Hash data = query string (theo tài liệu VNPAY chính thức)
$hashdata = $query;

// Tạo URL thanh toán
$vnp_Url = $vnp_Url . "?" . $query;
$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

echo "<h2>Query String (Hash Data)</h2>";
echo "<textarea style='width: 100%; height: 100px; font-size: 12px; font-family: monospace;'>" . htmlspecialchars($hashdata) . "</textarea>";

echo "<h2>Secure Hash</h2>";
echo "<p><strong>Hash:</strong> " . substr($vnpSecureHash, 0, 50) . "...</p>";
echo "<p><strong>Full Hash:</strong> <code>" . $vnpSecureHash . "</code></p>";

echo "<h2>Final URL</h2>";
echo "<p style='word-break: break-all; background: #f0f0f0; padding: 15px; font-size: 11px; font-family: monospace;'>";
echo htmlspecialchars($vnp_Url);
echo "</p>";

echo "<p><a href='" . htmlspecialchars($vnp_Url) . "' target='_blank' style='background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold;'>Test VNPAY Payment</a></p>";

echo "<h2>Thông tin test</h2>";
echo "<ul>";
echo "<li><strong>Số thẻ:</strong> 4200000000000000</li>";
echo "<li><strong>Ngày hết hạn:</strong> 12/25</li>";
echo "<li><strong>CVV:</strong> 123</li>";
echo "<li><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</li>";
echo "<li><strong>OTP:</strong> 123456</li>";
echo "</ul>";

echo "<h2>Debug Information</h2>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Hash Algorithm:</strong> SHA512</p>";
echo "<p><strong>Hash Secret Length:</strong> " . strlen($vnp_HashSecret) . " characters</p>";
echo "<p><strong>Hash Data Length:</strong> " . strlen($hashdata) . " characters</p>";
echo "<p><strong>Secure Hash Length:</strong> " . strlen($vnpSecureHash) . " characters</p>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
h1 { color: #28a745; text-align: center; }
h2 { color: #495057; margin-top: 30px; border-bottom: 2px solid #28a745; padding-bottom: 10px; }
textarea { border: 1px solid #ddd; border-radius: 5px; }
ul { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
li { margin: 10px 0; }
pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
code { background: #e9ecef; padding: 2px 4px; border-radius: 3px; }
</style>
