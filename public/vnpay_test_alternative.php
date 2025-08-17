<?php
/**
 * Test VNPAY Alternative - Logic tạo chữ ký khác
 * Truy cập: http://localhost/vnpay_test_alternative.php
 */

echo "<h1>Test VNPAY Alternative - Logic khác</h1>";

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

// PHƯƠNG PHÁP 1: Logic hiện tại (có thể sai)
echo "<h2>Phương pháp 1: Logic hiện tại</h2>";
$query1 = "";
$hashdata1 = "";

foreach ($inputData as $key => $value) {
    $query1 .= urlencode($key) . "=" . urlencode($value) . '&';
    if ($hashdata1 != "") {
        $hashdata1 .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata1 = urlencode($key) . "=" . urlencode($value);
    }
}

$query1 = rtrim($query1, '&');
$vnpSecureHash1 = hash_hmac('sha512', $hashdata1, $vnp_HashSecret);
$vnp_Url1 = $vnp_Url . "?" . $query1 . '&vnp_SecureHash=' . $vnpSecureHash1;

echo "<p><strong>Hash Data:</strong></p>";
echo "<textarea style='width: 100%; height: 80px; font-size: 12px;'>" . htmlspecialchars($hashdata1) . "</textarea>";
echo "<p><strong>Secure Hash:</strong> " . $vnpSecureHash1 . "</p>";
echo "<p><a href='" . htmlspecialchars($vnp_Url1) . "' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Method 1</a></p>";

// PHƯƠNG PHÁP 2: Logic theo tài liệu VNPAY chính thức
echo "<h2>Phương pháp 2: Logic theo tài liệu VNPAY</h2>";
$query2 = "";
foreach ($inputData as $key => $value) {
    $query2 .= urlencode($key) . "=" . urlencode($value) . '&';
}

$query2 = rtrim($query2, '&');
$hashdata2 = $query2; // Hash data = query string
$vnpSecureHash2 = hash_hmac('sha512', $hashdata2, $vnp_HashSecret);
$vnp_Url2 = $vnp_Url . "?" . $query2 . '&vnp_SecureHash=' . $vnpSecureHash2;

echo "<p><strong>Hash Data:</strong></p>";
echo "<textarea style='width: 100%; height: 80px; font-size: 12px;'>" . htmlspecialchars($hashdata2) . "</textarea>";
echo "<p><strong>Secure Hash:</strong> " . $vnpSecureHash2 . "</p>";
echo "<p><a href='" . htmlspecialchars($vnp_Url2) . "' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Method 2</a></p>";

// PHƯƠNG PHÁP 3: Logic đơn giản nhất
echo "<h2>Phương pháp 3: Logic đơn giản nhất</h2>";
$hashdata3 = "";
foreach ($inputData as $key => $value) {
    if ($hashdata3 != "") {
        $hashdata3 .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata3 = urlencode($key) . "=" . urlencode($value);
    }
}

$vnpSecureHash3 = hash_hmac('sha512', $hashdata3, $vnp_HashSecret);
$vnp_Url3 = $vnp_Url . "?" . $hashdata3 . '&vnp_SecureHash=' . $vnpSecureHash3;

echo "<p><strong>Hash Data:</strong></p>";
echo "<textarea style='width: 100%; height: 80px; font-size: 12px;'>" . htmlspecialchars($hashdata3) . "</textarea>";
echo "<p><strong>Secure Hash:</strong> " . $vnpSecureHash3 . "</p>";
echo "<p><a href='" . htmlspecialchars($vnp_Url3) . "' target='_blank' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Method 3</a></p>";

// So sánh các hash
echo "<h2>So sánh các Secure Hash</h2>";
echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
echo "<tr><th>Phương pháp</th><th>Secure Hash</th><th>Độ dài</th></tr>";
echo "<tr><td>Method 1</td><td>" . substr($vnpSecureHash1, 0, 50) . "...</td><td>" . strlen($vnpSecureHash1) . "</td></tr>";
echo "<tr><td>Method 2</td><td>" . substr($vnpSecureHash2, 0, 50) . "...</td><td>" . strlen($vnpSecureHash2) . "</td></tr>";
echo "<tr><td>Method 3</td><td>" . substr($vnpSecureHash3, 0, 50) . "...</td><td>" . strlen($vnpSecureHash3) . "</td></tr>";
echo "</table>";

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
textarea { border: 1px solid #ddd; border-radius: 5px; font-family: monospace; }
pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; border: 1px solid #ddd; }
table { background: white; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
th { background: #007bff; color: white; padding: 10px; }
td { padding: 10px; border: 1px solid #ddd; }
ul { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
li { margin: 10px 0; }
</style>
