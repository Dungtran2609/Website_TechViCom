<?php

// Test với dữ liệu thực tế từ Order #26 (lỗi mới nhất) - chỉ dùng tham số tối thiểu
$vnp_TmnCode = "2WZSC2P3";
$vnp_HashSecret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1";

// Dữ liệu thực tế từ log (order #26) - chỉ dùng tham số cần thiết
$inputData = array(
    "vnp_Amount" => "2899000000",
    "vnp_Command" => "pay",
    "vnp_CreateDate" => "20250815061052",
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => "127.0.0.1",
    "vnp_Locale" => "vn",
    "vnp_OrderInfo" => "Thanh toan cho don hang #26",
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => "http://127.0.0.1:8000/vnpay/return",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_TxnRef" => "26",
    "vnp_Version" => "2.1.0",
    "vnp_ExpireDate" => "20250815062552"
);

echo "=== VNPAY ORDER #26 TEST (MINIMAL PARAMETERS) ===\n\n";

echo "1. Input Data (minimal parameters only):\n";
foreach ($inputData as $key => $value) {
    echo "   {$key} = '{$value}'\n";
}

// 1. Sắp xếp theo key A→Z
ksort($inputData);

echo "\n2. Sorted Data:\n";
foreach ($inputData as $key => $value) {
    echo "   {$key} = '{$value}'\n";
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

// 4. So sánh với hash từ log
$expectedHash = "db62d360c19a9b28edba714328eaffd040b45e93ecc2951176902e79c1c9cb780ac2450a56570399155c57bf0994d18a142f0e18ff96548674e145b6519d8bb6";

echo "\n5. Hash Comparison:\n";
echo "   Expected: {$expectedHash}\n";
echo "   Calculated: {$vnpSecureHash}\n";
echo "   Match: " . ($vnpSecureHash === $expectedHash ? "✓ YES" : "✗ NO") . "\n";

// 5. Tạo URL với urlencode
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

echo "\n6. URL String (with urlencode):\n";
echo "   {$urlString}\n";

// 6. Tạo URL thanh toán
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?" . $urlString;

echo "\n7. Final Payment URL:\n";
echo "   {$vnp_Url}\n";

echo "\n=== TEST COMPLETED ===\n";
if ($vnpSecureHash === $expectedHash) {
    echo "✓ SUCCESS: Hash matches expected value!\n";
    echo "✓ VNPAY integration is working correctly.\n";
    echo "✓ Using minimal parameters solved the issue!\n";
} else {
    echo "✗ FAILED: Hash does not match expected value.\n";
    echo "✗ There may still be issues with the implementation.\n";
    
    // Debug thêm
    echo "\n=== DEBUG INFO ===\n";
    echo "Expected hash length: " . strlen($expectedHash) . "\n";
    echo "Calculated hash length: " . strlen($vnpSecureHash) . "\n";
    
    // So sánh từng ký tự
    $diff = 0;
    for ($i = 0; $i < min(strlen($expectedHash), strlen($vnpSecureHash)); $i++) {
        if ($expectedHash[$i] !== $vnpSecureHash[$i]) {
            $diff++;
            if ($diff <= 10) { // Chỉ hiển thị 10 ký tự đầu tiên khác nhau
                echo "Diff at position {$i}: expected '{$expectedHash[$i]}', got '{$vnpSecureHash[$i]}'\n";
            }
        }
    }
    echo "Total differences: {$diff}\n";
}
