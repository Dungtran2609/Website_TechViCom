<?php
// Test VNPAY với hash secret mới
echo "<h1>VNPAY Test với Hash Secret Mới</h1>";

// Test data với hash secret mới
$tmn_code = '2WZSC2P3';
$hash_secret_new = 'NWNXS265YSNAIGEH1L26KHKDIVET7QB1';
$url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

echo "<h2>1. Test với Hash Secret Mới</h2>";
echo "<p><strong>TMN Code:</strong> {$tmn_code}</p>";
echo "<p><strong>Hash Secret:</strong> " . substr($hash_secret_new, 0, 10) . "...</p>";

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

echo "<h3>Parameters:</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Parameter</th><th>Value</th></tr>";
foreach ($inputData as $key => $value) {
    echo "<tr><td>{$key}</td><td>" . htmlspecialchars($value) . "</td></tr>";
}
echo "</table>";

// Create hash data
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($hashdata != "") {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata = urlencode($key) . "=" . urlencode($value);
    }
}

// Create secure hash
$secureHash = hash_hmac('sha512', $hashdata, $hash_secret_new);

echo "<h3>Hash Data:</h3>";
echo "<textarea style='width: 100%; height: 60px;'>" . htmlspecialchars($hashdata) . "</textarea>";

echo "<h3>Secure Hash:</h3>";
echo "<p>{$secureHash}</p>";

// Create URL
$query = "";
foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $url . "?" . $query . 'vnp_SecureHash=' . $secureHash;

echo "<h3>Payment URL:</h3>";
echo "<textarea style='width: 100%; height: 100px;'>" . htmlspecialchars($vnp_Url) . "</textarea>";

echo "<p><a href='{$vnp_Url}' target='_blank'>Test VNPAY Payment (New Hash)</a></p>";

// Test 2: Với hash secret khác
echo "<h2>2. Test với Hash Secret Khác</h2>";
$alt_hash_secret = 'NWNXS265YSNAIGEH1L26KHKDIVET7QB1';
$secureHash2 = hash_hmac('sha512', $hashdata, $alt_hash_secret);

echo "<p><strong>Alternative Hash Secret:</strong> " . substr($alt_hash_secret, 0, 10) . "...</p>";
echo "<p><strong>Alternative Secure Hash:</strong> {$secureHash2}</p>";

$vnp_Url2 = $url . "?" . $query . 'vnp_SecureHash=' . $secureHash2;
echo "<p><a href='{$vnp_Url2}' target='_blank'>Test VNPAY Payment (Alternative Hash)</a></p>";

// Test 3: Với URL khác
echo "<h2>3. Test với URL Khác</h2>";
$alt_url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
$vnp_Url3 = $alt_url . "?" . $query . 'vnp_SecureHash=' . $secureHash;
echo "<p><a href='{$vnp_Url3}' target='_blank'>Test VNPAY Payment (Alternative URL)</a></p>";

// Test 4: Với tham số tối thiểu
echo "<h2>4. Test với Tham số Tối thiểu</h2>";
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

$minSecureHash = hash_hmac('sha512', $minHashdata, $hash_secret_new);

$minQuery = "";
foreach ($minData as $key => $value) {
    $minQuery .= urlencode($key) . "=" . urlencode($value) . '&';
}

$minUrl = $url . "?" . $minQuery . 'vnp_SecureHash=' . $minSecureHash;
echo "<p><a href='{$minUrl}' target='_blank'>Test VNPAY Payment (Minimal Parameters)</a></p>";

echo "<h2>5. Debug Info</h2>";
echo "<p><strong>Current Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Create Date:</strong> " . date('YmdHis') . "</p>";
echo "<p><strong>Server IP:</strong> " . ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1') . "</p>";

echo "<h2>6. Thông tin Test VNPAY</h2>";
echo "<p><strong>Thẻ test:</strong> 4200000000000000</p>";
echo "<p><strong>Ngày hết hạn:</strong> 12/25</p>";
echo "<p><strong>CVV:</strong> 123</p>";
echo "<p><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</p>";
echo "<p><strong>OTP:</strong> 123456</p>";
?>
