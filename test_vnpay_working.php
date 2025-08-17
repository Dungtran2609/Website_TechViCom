<?php
/**
 * Test VNPAY Working - Kiểm tra VNPAY hoạt động
 * Truy cập: http://localhost/test_vnpay_working.php
 */

echo "<h1>Test VNPAY Working</h1>";

// Test 1: Kiểm tra config
echo "<h2>1. Kiểm tra config</h2>";
$config = [
    'sandbox' => [
        'url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        'tmn_code' => '2WZSC2P3',
        'hash_secret' => 'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
    ]
];

echo "<pre>";
print_r($config);
echo "</pre>";

// Test 2: Tạo URL thanh toán
echo "<h2>2. Tạo URL thanh toán</h2>";

$vnp_Url = $config['sandbox']['url'];
$vnp_Returnurl = "http://localhost/vnpay_return_demo.php";
$vnp_TmnCode = $config['sandbox']['tmn_code'];
$vnp_HashSecret = $config['sandbox']['hash_secret'];

$vnp_TxnRef = "TEST_" . time();
$vnp_OrderInfo = "Thanh toan don hang test";
$vnp_OrderType = "other";
$vnp_Amount = 20000 * 100;
$vnp_Locale = "vn";
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
$vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

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

ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}

echo "<p><strong>URL thanh toán:</strong></p>";
echo "<p style='word-break: break-all; background: #f0f0f0; padding: 10px; font-size: 12px;'>";
echo htmlspecialchars($vnp_Url);
echo "</p>";

echo "<p><strong>Độ dài URL:</strong> " . strlen($vnp_Url) . " ký tự</p>";

echo "<p><a href='" . htmlspecialchars($vnp_Url) . "' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test VNPAY Payment</a></p>";

// Test 3: Thông tin test
echo "<h2>3. Thông tin test</h2>";
echo "<ul>";
echo "<li><strong>Số thẻ:</strong> 4200000000000000</li>";
echo "<li><strong>Ngày hết hạn:</strong> 12/25</li>";
echo "<li><strong>CVV:</strong> 123</li>";
echo "<li><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</li>";
echo "</ul>";

echo "<h2>4. Kết quả</h2>";
echo "<p>Sau khi thanh toán, VNPAY sẽ chuyển hướng về: <code>$vnp_Returnurl</code></p>";
echo "<p><a href='$vnp_Returnurl' target='_blank'>Xem trang return demo</a></p>";

echo "<h2>5. Test Laravel Routes</h2>";
echo "<p><a href='/vnpay/payment/1' target='_blank'>Test Laravel VNPAY Route (Order ID: 1)</a></p>";
echo "<p><a href='/vnpay/return' target='_blank'>Test Laravel VNPAY Return Route</a></p>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; }
h1 { color: #333; }
h2 { color: #666; margin-top: 30px; }
pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
</style>
