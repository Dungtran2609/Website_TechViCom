<?php
// Test VNPAY với nhiều hash secret khác nhau
echo "<h1>VNPAY Test với Nhiều Hash Secret</h1>";

// Test data
$tmn_code = '2WZSC2P3';
$url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

// Danh sách hash secret để test
$hash_secrets = [
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
];

// Tạo input data
$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $tmn_code,
    "vnp_Amount" => "10000000",
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => "127.0.0.1",
    "vnp_Locale" => "vn",
    "vnp_OrderInfo" => "Thanh toan don hang #999",
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => "http://localhost/vnpay/return",
    "vnp_TxnRef" => "999",
);

// Remove empty values
$inputData = array_filter($inputData, function($value) {
    return $value !== null && $value !== '';
});

// Sort by key
ksort($inputData);

// Create hash data
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($hashdata != "") {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata = urlencode($key) . "=" . urlencode($value);
    }
}

// Create query
$query = "";
foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

echo "<h2>Test Parameters:</h2>";
echo "<p><strong>TMN Code:</strong> {$tmn_code}</p>";
echo "<p><strong>Hash Data:</strong></p>";
echo "<textarea style='width: 100%; height: 60px;'>" . htmlspecialchars($hashdata) . "</textarea>";

echo "<h2>Test với các Hash Secret:</h2>";

foreach ($hash_secrets as $index => $hash_secret) {
    $secureHash = hash_hmac('sha512', $hashdata, $hash_secret);
    $vnp_Url = $url . "?" . $query . 'vnp_SecureHash=' . $secureHash;
    
    echo "<h3>Test " . ($index + 1) . ":</h3>";
    echo "<p><strong>Hash Secret:</strong> " . substr($hash_secret, 0, 10) . "...</p>";
    echo "<p><strong>Secure Hash:</strong> " . substr($secureHash, 0, 20) . "...</p>";
    echo "<p><a href='{$vnp_Url}' target='_blank'>Test VNPAY Payment " . ($index + 1) . "</a></p>";
    echo "<hr>";
}

// Test với hash secret từ tài liệu VNPAY
echo "<h2>Test với Hash Secret từ Tài liệu VNPAY:</h2>";
$vnpay_doc_hash = 'NWNXS265YSNAIGEH1L26KHKDIVET7QB1';
$secureHash_doc = hash_hmac('sha512', $hashdata, $vnpay_doc_hash);
$vnp_Url_doc = $url . "?" . $query . 'vnp_SecureHash=' . $secureHash_doc;

echo "<p><strong>Hash Secret từ tài liệu:</strong> " . substr($vnpay_doc_hash, 0, 10) . "...</p>";
echo "<p><strong>Secure Hash:</strong> " . substr($secureHash_doc, 0, 20) . "...</p>";
echo "<p><a href='{$vnp_Url_doc}' target='_blank'>Test VNPAY Payment (Tài liệu)</a></p>";

// Test với tham số tối thiểu
echo "<h2>Test với Tham số Tối thiểu:</h2>";
$minData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $tmn_code,
    "vnp_Amount" => "10000000",
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => "127.0.0.1",
    "vnp_Locale" => "vn",
    "vnp_OrderInfo" => "Thanh toan don hang #999",
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => "http://localhost/vnpay/return",
    "vnp_TxnRef" => "999",
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

$minSecureHash = hash_hmac('sha512', $minHashdata, $vnpay_doc_hash);
$minUrl = $url . "?" . $minQuery . 'vnp_SecureHash=' . $minSecureHash;

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
