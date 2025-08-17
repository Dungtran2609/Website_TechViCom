<?php
// Test VNPAY với cấu hình đúng
echo "<h1>VNPAY Test với Cấu hình Đúng</h1>";

// Cấu hình VNPAY
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/vnpay/return"; // URL return của Laravel
$vnp_TmnCode = "2WZSC2P3"; // Mã website tại VNPAY
$vnp_HashSecret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1"; // Chuỗi bí mật

echo "<h2>1. Cấu hình VNPAY</h2>";
echo "<p><strong>URL:</strong> {$vnp_Url}</p>";
echo "<p><strong>Return URL:</strong> {$vnp_Returnurl}</p>";
echo "<p><strong>TMN Code:</strong> {$vnp_TmnCode}</p>";
echo "<p><strong>Hash Secret:</strong> " . substr($vnp_HashSecret, 0, 10) . "...</p>";

// Tạo dữ liệu thanh toán
$vnp_TxnRef = "TEST_" . time(); // Mã giao dịch
$vnp_OrderInfo = "Thanh toan don hang test"; // Thông tin đơn hàng
$vnp_OrderType = "other"; // Loại hàng hóa
$vnp_Amount = 20000 * 100; // Số tiền (VNPAY yêu cầu nhân 100)
$vnp_Locale = "vn"; // Ngôn ngữ
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'] ?? "127.0.0.1"; // IP khách hàng
$vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes')); // Thời gian hết hạn

echo "<h2>2. Dữ liệu thanh toán</h2>";
echo "<p><strong>Mã giao dịch:</strong> {$vnp_TxnRef}</p>";
echo "<p><strong>Thông tin đơn hàng:</strong> {$vnp_OrderInfo}</p>";
echo "<p><strong>Số tiền:</strong> " . number_format($vnp_Amount/100) . " VND</p>";
echo "<p><strong>IP khách hàng:</strong> {$vnp_IpAddr}</p>";
echo "<p><strong>Thời gian hết hạn:</strong> {$vnp_ExpireDate}</p>";

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
    "vnp_ExpireDate" => $vnp_ExpireDate,
);

// Loại bỏ các tham số rỗng
$inputData = array_filter($inputData, function($value) {
    return $value !== null && $value !== '';
});

// Sắp xếp theo thứ tự alphabet
ksort($inputData);

echo "<h2>3. Tham số sau khi xử lý</h2>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Parameter</th><th>Value</th></tr>";
foreach ($inputData as $key => $value) {
    echo "<tr><td>{$key}</td><td>" . htmlspecialchars($value) . "</td></tr>";
}
echo "</table>";

// Tạo hash data
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($hashdata != "") {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata = urlencode($key) . "=" . urlencode($value);
    }
}

// Tạo secure hash
$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

echo "<h2>4. Hash Data</h2>";
echo "<textarea style='width: 100%; height: 60px;'>" . htmlspecialchars($hashdata) . "</textarea>";

echo "<h2>5. Secure Hash</h2>";
echo "<p><strong>Secure Hash:</strong> {$vnpSecureHash}</p>";

// Tạo URL thanh toán
$query = "";
foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;

echo "<h2>6. URL Thanh toán</h2>";
echo "<textarea style='width: 100%; height: 100px;'>" . htmlspecialchars($vnp_Url) . "</textarea>";

echo "<h2>7. Test Links</h2>";
echo "<p><a href='{$vnp_Url}' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test VNPAY Payment</a></p>";

echo "<h2>8. Thông tin test VNPAY</h2>";
echo "<ul>";
echo "<li><strong>Số thẻ:</strong> 4200000000000000</li>";
echo "<li><strong>Ngày hết hạn:</strong> 12/25</li>";
echo "<li><strong>CVV:</strong> 123</li>";
echo "<li><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</li>";
echo "<li><strong>OTP:</strong> 123456</li>";
echo "</ul>";

echo "<h2>9. Debug Info</h2>";
echo "<p><strong>Thời gian hiện tại:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Create Date:</strong> " . date('YmdHis') . "</p>";
echo "<p><strong>Server IP:</strong> " . ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1') . "</p>";

echo "<h2>10. Test Return URL</h2>";
$returnUrl = "http://localhost/vnpay/return?vnp_Amount={$vnp_Amount}&vnp_BankCode=NCB&vnp_BankTranNo=VNP14123456&vnp_CardType=ATM&vnp_OrderInfo=" . urlencode($vnp_OrderInfo) . "&vnp_PayDate=" . date('YmdHis') . "&vnp_ResponseCode=00&vnp_TmnCode={$vnp_TmnCode}&vnp_TransactionNo=14123456&vnp_TransactionStatus=00&vnp_TxnRef={$vnp_TxnRef}&vnp_SecureHash={$vnpSecureHash}";
echo "<p><a href='{$returnUrl}' target='_blank'>Test VNPAY Return (Success)</a></p>";
?>
