<?php
// Simple VNPAY Debug Script
echo "<h1>VNPAY Debug</h1>";

$tmn_code = "2WZSC2P3";
$hash_secret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1";

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $tmn_code,
    "vnp_Amount" => 2000000,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => "127.0.0.1",
    "vnp_Locale" => "vn",
    "vnp_OrderInfo" => "Thanh toan don hang test",
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => "http://localhost/vnpay/return",
    "vnp_TxnRef" => "TEST_" . time(),
);

ksort($inputData);

$query = "";
$hashdata = "";

foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
    if ($hashdata != "") {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata = urlencode($key) . "=" . urlencode($value);
    }
}

$query = rtrim($query, '&');
$secureHash = hash_hmac('sha512', $hashdata, $hash_secret);
$url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?" . $query . "&vnp_SecureHash=" . $secureHash;

echo "<p><strong>Hash Data:</strong> " . $hashdata . "</p>";
echo "<p><strong>Secure Hash:</strong> " . $secureHash . "</p>";
echo "<p><a href='" . $url . "' target='_blank'>Test Payment</a></p>";
?>
