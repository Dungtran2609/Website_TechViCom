<?php
// Debug VNPAY Hash Generation

echo "<h1>VNPAY Hash Debug</h1>";

// Cấu hình VNPAY (sử dụng cấu hình từ config/vnpay.php nếu có thể)
// Hoặc điền trực tiếp để test độc lập
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/vnpay/return"; // URL return của Laravel
$vnp_TmnCode = "2WZSC2P3"; // Mã website tại VNPAY
$vnp_HashSecret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1"; // Chuỗi bí mật

echo "<h2>1. Cấu hình VNPAY đang test</h2>";
echo "<p><strong>URL:</strong> {$vnp_Url}</p>";
echo "<p><strong>Return URL:</strong> {$vnp_Returnurl}</p>";
echo "<p><strong>TMN Code:</strong> {$vnp_TmnCode}</p>";
echo "<p><strong>Hash Secret (first 10 chars):</strong> " . substr($vnp_HashSecret, 0, 10) . "...</p>";

// Tạo dữ liệu thanh toán mẫu
$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => "10000000", // 100,000 VND
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => "127.0.0.1",
    "vnp_Locale" => "vn",
    "vnp_OrderInfo" => "Thanh toan don hang test",
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => "TEST_" . time(),
    "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes')),
    // Thêm các tham số khác nếu cần thiết theo tài liệu VNPAY
    // "vnp_Bill_Mobile" => "0901234567",
    // "vnp_Bill_Email" => "test@example.com",
    // "vnp_Bill_FirstName" => "Nguyen",
    // "vnp_Bill_LastName" => "Van A",
    // "vnp_Bill_Address" => "123 Le Loi",
    // "vnp_Bill_City" => "Ha Noi",
    // "vnp_Bill_Country" => "VN",
    // "vnp_Inv_Type" => "I",
    // "vnp_Inv_Company" => "ABC Corp",
    // "vnp_Inv_Address" => "456 Tran Hung Dao",
    // "vnp_Inv_Taxcode" => "1234567890",
    // "vnp_Inv_Email" => "inv@example.com",
);

// Loại bỏ các tham số rỗng
$inputData = array_filter($inputData, function($value) {
    return $value !== null && $value !== '';
});

// Sắp xếp theo thứ tự alphabet
ksort($inputData);

echo "<h2>2. Tham số sau khi sắp xếp</h2>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Parameter</th><th>Value</th></tr>";
foreach ($inputData as $key => $value) {
    echo "<tr><td>{$key}</td><td>" . htmlspecialchars($value) . "</td></tr>";
}
echo "</table>";

$hashData = "";
foreach ($inputData as $key => $value) {
    if ($hashData != "") {
        $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = urlencode($key) . "=" . urlencode($value);
    }
}

echo "<h2>3. Chuỗi dữ liệu (Hash Data) trước khi băm</h2>";
echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($hashData) . "</pre>";

// Tạo chữ ký HMAC-SHA512
$vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

echo "<h2>4. Chữ ký (Secure Hash) được tạo ra</h2>";
echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>" . $vnp_SecureHash . "</pre>";

// Xây dựng URL thanh toán
$query = "";
foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}
$query .= "vnp_SecureHash=" . $vnp_SecureHash;

$paymentUrl = $vnp_Url . "?" . $query;

echo "<h2>5. URL thanh toán</h2>";
echo "<a href='" . htmlspecialchars($paymentUrl) . "' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test VNPAY Payment</a>";

echo "<h2>6. Debug Info</h2>";
echo "<p><strong>Thuật toán mã hóa:</strong> HMAC-SHA512</p>";
echo "<p><strong>Thời gian tạo:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Create Date:</strong> " . date('YmdHis') . "</p>";

echo "<h2>7. Hướng dẫn debug</h2>";
echo "<p>Click vào URL trên để kiểm tra. Nếu vẫn lỗi 'Sai chữ ký', hãy:</p>";
echo "<ol>";
echo "<li>So sánh 'Chuỗi dữ liệu (Hash Data)' với tài liệu VNPAY</li>";
echo "<li>Kiểm tra thứ tự tham số có đúng alphabet không</li>";
echo "<li>Kiểm tra Hash Secret có đúng không</li>";
echo "<li>Liên hệ VNPAY support với mã tra cứu để kiểm tra</li>";
echo "</ol>";

echo "<h2>8. Thông tin test VNPAY</h2>";
echo "<p><strong>Thẻ test:</strong> 4200000000000000</p>";
echo "<p><strong>Ngày hết hạn:</strong> 12/25</p>";
echo "<p><strong>CVV:</strong> 123</p>";
echo "<p><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</p>";
echo "<p><strong>OTP:</strong> 123456</p>";
?>
