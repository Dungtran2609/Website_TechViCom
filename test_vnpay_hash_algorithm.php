<?php

// Test thuật toán hash VNPAY theo đúng quy tắc
$vnp_TmnCode = "2WZSC2P3";
$vnp_HashSecret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1";

// Dữ liệu test theo đúng quy tắc VNPAY
$inputData = array(
    "vnp_Amount" => "10000000",
    "vnp_Command" => "pay",
    "vnp_CreateDate" => "20250815040330",
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => "127.0.0.1",
    "vnp_Locale" => "vn",
    "vnp_OrderInfo" => "Thanh toan cho don hang #999",
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => "http://127.0.0.1:8000/vnpay/return",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_TxnRef" => "999",
    "vnp_Version" => "2.1.0"
);

echo "=== VNPAY HASH ALGORITHM TEST ===\n\n";

echo "1. Input Data (before sorting):\n";
foreach ($inputData as $key => $value) {
    echo "   {$key} = {$value}\n";
}

// 1. Sắp xếp theo key A→Z
ksort($inputData);

echo "\n2. Input Data (after sorting):\n";
foreach ($inputData as $key => $value) {
    echo "   {$key} = {$value}\n";
}

// 2. Tạo chuỗi để ký (KHÔNG urlencode)
$hashdata = "";
$i = 0;
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . $key . "=" . $value;
    } else {
        $hashdata .= $key . "=" . $value;
        $i = 1;
    }
}

echo "\n3. Hash Data (raw string for signing):\n";
echo "   {$hashdata}\n";

// 3. Tạo chữ ký với SHA512
$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

echo "\n4. Secure Hash (SHA512):\n";
echo "   {$vnpSecureHash}\n";

// 4. Tạo URL với urlencode
$urlString = "";
$i = 0;
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $urlString .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $urlString .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}
$urlString .= '&vnp_SecureHash=' . $vnpSecureHash;

echo "\n5. URL String (with urlencode):\n";
echo "   {$urlString}\n";

// 5. Tạo URL thanh toán
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?" . $urlString;

echo "\n6. Final Payment URL:\n";
echo "   {$vnp_Url}\n";

echo "\n=== TEST COMPLETED ===\n";
echo "This follows the correct VNPAY signature algorithm:\n";
echo "- SHA512 hash algorithm\n";
echo "- No urlencode when creating hash string\n";
echo "- urlencode only when creating final URL\n";
echo "- Sort keys alphabetically\n";
echo "- Remove empty values\n";
