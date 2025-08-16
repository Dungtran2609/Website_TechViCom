<?php

// Test VNPAY với URL return đúng
$vnp_TmnCode = "2WZSC2P3";
$vnp_HashSecret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1";
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

// Dữ liệu test
$vnp_Version = "2.1.0";
$vnp_Command = "pay";
$vnp_TxnRef = "999";
$vnp_Amount = "10000000"; // 100,000 VND
$vnp_CurrCode = "VND";
$vnp_BankCode = "";
$vnp_Locale = "vn";
$vnp_OrderInfo = "Thanh toan cho don hang #999";
$vnp_OrderType = "other";
$vnp_ReturnUrl = "http://127.0.0.1:8000/vnpay/return";
$vnp_IpAddr = "127.0.0.1";
$vnp_CreateDate = date('YmdHis');
$vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

// Tạo mảng dữ liệu
$inputData = array(
    "vnp_Version" => $vnp_Version,
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => $vnp_Command,
    "vnp_CreateDate" => $vnp_CreateDate,
    "vnp_CurrCode" => $vnp_CurrCode,
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_ReturnUrl,
    "vnp_TxnRef" => $vnp_TxnRef,
    "vnp_ExpireDate" => $vnp_ExpireDate
);

// Sắp xếp theo key
ksort($inputData);

// Tạo chuỗi hash
$hashdata = "";
$i = 0;
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

// Tạo chữ ký
$vnpSecureHash = hash_hmac('sha256', $hashdata, $vnp_HashSecret);
$hashdata .= '&vnp_SecureHash=' . $vnpSecureHash;

// Tạo URL thanh toán
$vnp_Url = $vnp_Url . "?" . $hashdata;

echo "=== VNPAY SIMPLE TEST ===\n\n";
echo "1. Configuration:\n";
echo "   TMN Code: {$vnp_TmnCode}\n";
echo "   Hash Secret: " . substr($vnp_HashSecret, 0, 10) . "...\n";
echo "   Return URL: {$vnp_ReturnUrl}\n\n";

echo "2. Payment URL:\n";
echo "   {$vnp_Url}\n\n";

echo "3. Hash Data:\n";
echo "   {$hashdata}\n\n";

echo "4. Secure Hash:\n";
echo "   {$vnpSecureHash}\n\n";

echo "=== TEST COMPLETED ===\n";
echo "Copy the URL above and test in browser.\n";
