<?php
/**
 * Test VNPAY Hash Secret - Kiểm tra nhiều hash secret
 * Truy cập: http://localhost/vnpay_test_hash.php
 */

echo "<h1>Test VNPAY Hash Secret</h1>";

// Cấu hình VNPAY
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/vnpay/return";
$vnp_TmnCode = "2WZSC2P3";

// Danh sách hash secret để test
$hashSecrets = [
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1', // Hash secret hiện tại
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1', // Hash secret từ tài liệu
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1', // Hash secret từ email
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1', // Hash secret từ sandbox
];

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

// Tạo query string
$query = "";
foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

// Loại bỏ & cuối cùng từ query
$query = rtrim($query, '&');

// Hash data = query string
$hashdata = $query;

echo "<h2>Input Data (Sorted)</h2>";
echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
foreach ($inputData as $key => $value) {
    echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "\n";
}
echo "</pre>";

echo "<h2>Query String (Hash Data)</h2>";
echo "<textarea style='width: 100%; height: 100px; font-size: 12px; font-family: monospace;'>" . htmlspecialchars($hashdata) . "</textarea>";

echo "<h2>Test với các Hash Secret</h2>";

foreach ($hashSecrets as $index => $hashSecret) {
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $hashSecret);
    $vnp_Url_test = $vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnpSecureHash;
    
    echo "<h3>Test " . ($index + 1) . "</h3>";
    echo "<p><strong>Hash Secret:</strong> " . substr($hashSecret, 0, 10) . "...</p>";
    echo "<p><strong>Secure Hash:</strong> " . substr($vnpSecureHash, 0, 50) . "...</p>";
    echo "<p><a href='" . htmlspecialchars($vnp_Url_test) . "' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test VNPAY Payment " . ($index + 1) . "</a></p>";
    echo "<hr>";
}

// Test với hash secret từ tài liệu VNPAY mới nhất
echo "<h2>Test với Hash Secret từ Tài liệu VNPAY Mới nhất:</h2>";
$vnpay_new_hash = 'NWNXS265YSNAIGEH1L26KHKDIVET7QB1';
$vnpSecureHash_new = hash_hmac('sha512', $hashdata, $vnpay_new_hash);
$vnp_Url_new = $vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnpSecureHash_new;

echo "<p><strong>Hash Secret mới:</strong> " . substr($vnpay_new_hash, 0, 10) . "...</p>";
echo "<p><strong>Secure Hash:</strong> " . substr($vnpSecureHash_new, 0, 50) . "...</p>";
echo "<p><a href='" . htmlspecialchars($vnp_Url_new) . "' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test VNPAY Payment (Hash Mới)</a></p>";

// Test với tham số tối thiểu
echo "<h2>Test với Tham số Tối thiểu</h2>";
$minData = array(
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

ksort($minData);
$minQuery = "";
foreach ($minData as $key => $value) {
    $minQuery .= urlencode($key) . "=" . urlencode($value) . '&';
}

$minQuery = rtrim($minQuery, '&');
$minSecureHash = hash_hmac('sha512', $minQuery, $vnpay_new_hash);
$minUrl = $vnp_Url . "?" . $minQuery . '&vnp_SecureHash=' . $minSecureHash;

echo "<p><strong>Minimal Hash Data:</strong></p>";
echo "<textarea style='width: 100%; height: 60px; font-size: 12px;'>" . htmlspecialchars($minQuery) . "</textarea>";
echo "<p><a href='" . htmlspecialchars($minUrl) . "' target='_blank'>Test VNPAY Payment (Minimal)</a></p>";

echo "<h2>Thông tin test</h2>";
echo "<ul>";
echo "<li><strong>Số thẻ:</strong> 4200000000000000</li>";
echo "<li><strong>Ngày hết hạn:</strong> 12/25</li>";
echo "<li><strong>CVV:</strong> 123</li>";
echo "<li><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</li>";
echo "<li><strong>OTP:</strong> 123456</li>";
echo "</ul>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
h1 { color: #dc3545; text-align: center; }
h2 { color: #495057; margin-top: 30px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
h3 { color: #6c757d; margin-top: 20px; }
textarea { border: 1px solid #ddd; border-radius: 5px; font-family: monospace; }
pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; border: 1px solid #ddd; }
ul { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
li { margin: 10px 0; }
hr { border: 1px solid #ddd; margin: 20px 0; }
</style>
