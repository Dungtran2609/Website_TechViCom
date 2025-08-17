<?php

// Test với dữ liệu thực tế từ Order #24 (lỗi mới nhất)
$vnp_TmnCode = "2WZSC2P3";
$vnp_HashSecret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1";

// Dữ liệu thực tế từ log (order #24)
$inputData = array(
    "vnp_Amount" => "3399000000",
    "vnp_Command" => "pay",
    "vnp_CreateDate" => "20250815040831",
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => "127.0.0.1",
    "vnp_Locale" => "vn",
    "vnp_OrderInfo" => "Thanh toan cho don hang #24",
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => "http://127.0.0.1:8000/vnpay/return",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_TxnRef" => "24",
    "vnp_Version" => "2.1.0",
    "vnp_ExpireDate" => "20250815042331",
    "vnp_Bill_FirstName" => "Admin",
    "vnp_Bill_LastName" => "",  // Giá trị rỗng này có thể gây vấn đề
    "vnp_Bill_Mobile" => "0999999999",
    "vnp_Bill_Address" => "43375 Prosacco Rapid Apt. 516, Phường Cống Vị, Quận Ba Đình, Hà Nội",
    "vnp_Bill_City" => "Hanoi",
    "vnp_Bill_Country" => "VN"
);

echo "=== VNPAY ORDER #24 TEST ===\n\n";

echo "1. Input Data (before cleaning):\n";
foreach ($inputData as $key => $value) {
    echo "   {$key} = '{$value}'\n";
}

// 1. Loại bỏ các tham số có giá trị rỗng
$cleanData = array_filter($inputData, function($value) {
    return $value !== null && $value !== '' && $value !== 0 && trim($value) !== '';
});

echo "\n2. Clean Data (after filtering):\n";
foreach ($cleanData as $key => $value) {
    echo "   {$key} = '{$value}'\n";
}

// 2. Sắp xếp theo key A→Z
ksort($cleanData);

echo "\n3. Sorted Data:\n";
foreach ($cleanData as $key => $value) {
    echo "   {$key} = '{$value}'\n";
}

// 3. Tạo chuỗi để ký (KHÔNG urlencode)
$hashdata = "";
$i = 0;
foreach ($cleanData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . $key . "=" . $value;
    } else {
        $hashdata .= $key . "=" . $value;
        $i = 1;
    }
}

echo "\n4. Hash Data (raw string for signing):\n";
echo "   {$hashdata}\n";

// 4. Tạo chữ ký với SHA512
$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

echo "\n5. Secure Hash (SHA512):\n";
echo "   {$vnpSecureHash}\n";

// 5. So sánh với hash từ log
$expectedHash = "8d6a84a0105809596267dd783817d42cd4884f6eb5e6d0931727ecd71386d5de0c53c4b00ce288795f21b022bb7821e3266f0104ed3a50a16fda638fcb533d5e";

echo "\n6. Hash Comparison:\n";
echo "   Expected: {$expectedHash}\n";
echo "   Calculated: {$vnpSecureHash}\n";
echo "   Match: " . ($vnpSecureHash === $expectedHash ? "✓ YES" : "✗ NO") . "\n";

// 6. Tạo URL với urlencode
$urlString = "";
$i = 0;
foreach ($cleanData as $key => $value) {
    if ($i == 1) {
        $urlString .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $urlString .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}
$urlString .= '&vnp_SecureHash=' . $vnpSecureHash;

echo "\n7. URL String (with urlencode):\n";
echo "   {$urlString}\n";

// 7. Tạo URL thanh toán
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?" . $urlString;

echo "\n8. Final Payment URL:\n";
echo "   {$vnp_Url}\n";

echo "\n=== TEST COMPLETED ===\n";
if ($vnpSecureHash === $expectedHash) {
    echo "✓ SUCCESS: Hash matches expected value!\n";
    echo "✓ VNPAY integration is working correctly.\n";
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
