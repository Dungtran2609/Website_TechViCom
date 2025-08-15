<?php
// Test VNPAY với nhiều hash secret khác nhau
echo "<h1>VNPAY Test với Nhiều Hash Secret</h1>";

// Cấu hình cơ bản
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/vnpay/return";
$vnp_TmnCode = "2WZSC2P3";

// Danh sách hash secret để test
$hash_secrets = [
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1', // Hash secret hiện tại
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1', // Hash secret phổ biến
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1', // Hash secret từ tài liệu cũ
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1', // Hash secret từ tài liệu mới
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1', // Hash secret thay thế
];

// Tạo dữ liệu thanh toán
$vnp_TxnRef = "TEST_" . time();
$vnp_OrderInfo = "Thanh toan don hang test";
$vnp_OrderType = "other";
$vnp_Amount = 20000 * 100;
$vnp_Locale = "vn";
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'] ?? "127.0.0.1";
$vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

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

// Tạo hash data
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($hashdata != "") {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata = urlencode($key) . "=" . urlencode($value);
    }
}

// Tạo query
$query = "";
foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

echo "<h2>Thông tin cơ bản</h2>";
echo "<p><strong>TMN Code:</strong> {$vnp_TmnCode}</p>";
echo "<p><strong>Hash Data:</strong></p>";
echo "<textarea style='width: 100%; height: 60px;'>" . htmlspecialchars($hashdata) . "</textarea>";

echo "<h2>Test với các Hash Secret:</h2>";

foreach ($hash_secrets as $index => $hash_secret) {
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $hash_secret);
    $vnp_Url_test = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;
    
    echo "<h3>Test " . ($index + 1) . ":</h3>";
    echo "<p><strong>Hash Secret:</strong> " . substr($hash_secret, 0, 10) . "...</p>";
    echo "<p><strong>Secure Hash:</strong> " . substr($vnpSecureHash, 0, 20) . "...</p>";
    echo "<p><a href='{$vnp_Url_test}' target='_blank'>Test VNPAY Payment " . ($index + 1) . "</a></p>";
    echo "<hr>";
}

// Test với hash secret từ tài liệu VNPAY mới nhất
echo "<h2>Test với Hash Secret từ Tài liệu VNPAY Mới nhất:</h2>";
$vnpay_new_hash = 'NWNXS265YSNAIGEH1L26KHKDIVET7QB1';
$vnpSecureHash_new = hash_hmac('sha512', $hashdata, $vnpay_new_hash);
$vnp_Url_new = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash_new;

echo "<p><strong>Hash Secret mới:</strong> " . substr($vnpay_new_hash, 0, 10) . "...</p>";
echo "<p><strong>Secure Hash:</strong> " . substr($vnpSecureHash_new, 0, 20) . "...</p>";
echo "<p><a href='{$vnp_Url_new}' target='_blank'>Test VNPAY Payment (Hash Mới)</a></p>";

// Test với tham số tối thiểu
echo "<h2>Test với Tham số Tối thiểu:</h2>";
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
$minHashdata = "";
foreach ($minData as $key => $value) {
    if ($minHashdata != "") {
        $minHashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $minHashdata = urlencode($key) . "=" . urlencode($value);
    }
}

$minQuery = "";
foreach ($minData as $key => $value) {
    $minQuery .= urlencode($key) . "=" . urlencode($value) . '&';
}

$minSecureHash = hash_hmac('sha512', $minHashdata, $vnpay_new_hash);
$minUrl = $vnp_Url . "?" . $minQuery . 'vnp_SecureHash=' . $minSecureHash;

echo "<p><strong>Hash Data (Minimal):</strong></p>";
echo "<textarea style='width: 100%; height: 60px;'>" . htmlspecialchars($minHashdata) . "</textarea>";
echo "<p><strong>Secure Hash:</strong> " . substr($minSecureHash, 0, 20) . "...</p>";
echo "<p><a href='{$minUrl}' target='_blank'>Test VNPAY Payment (Minimal)</a></p>";

echo "<h2>Thông tin Test:</h2>";
echo "<p><strong>Thẻ test:</strong> 4200000000000000</p>";
echo "<p><strong>Ngày hết hạn:</strong> 12/25</p>";
echo "<p><strong>CVV:</strong> 123</p>";
echo "<p><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</p>";
echo "<p><strong>OTP:</strong> 123456</p>";
?>
