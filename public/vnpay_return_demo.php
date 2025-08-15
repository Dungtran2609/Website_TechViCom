<?php
/**
 * Demo VNPAY Return - Xử lý callback từ VNPAY
 */

$vnp_HashSecret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1";

$inputData = array();
$returnData = array();

$data = $_GET;

foreach ($data as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

$vnp_SecureHash = $inputData['vnp_SecureHash'];
unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

$orderId = $inputData['vnp_TxnRef'];
$responseCode = $inputData['vnp_ResponseCode'];
$message = $inputData['vnp_Message'] ?? '';
$transactionId = $inputData['vnp_TransactionNo'] ?? '';
$bankCode = $inputData['vnp_BankCode'] ?? '';
$cardType = $inputData['vnp_CardType'] ?? '';

$isValid = $secureHash == $vnp_SecureHash;
$isSuccess = $responseCode == '00';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VNPAY Return Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .success { border-color: #28a745; background-color: #d4edda; }
        .error { border-color: #dc3545; background-color: #f8d7da; }
        .warning { border-color: #ffc107; background-color: #fff3cd; }
        .info { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .btn { background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .status-icon { font-size: 48px; text-align: center; margin: 20px 0; }
        .success-icon { color: #28a745; }
        .error-icon { color: #dc3545; }
        .warning-icon { color: #ffc107; }
    </style>
</head>
<body>
    <div class="container">
        <h1>VNPAY Payment Result</h1>
        
        <?php if ($isValid): ?>
            <?php if ($isSuccess): ?>
                <div class="card success">
                    <div class="status-icon success-icon">✅</div>
                    <h2 style="color: #28a745; text-align: center;">Thanh toán thành công!</h2>
                </div>
            <?php else: ?>
                <div class="card error">
                    <div class="status-icon error-icon">❌</div>
                    <h2 style="color: #dc3545; text-align: center;">Thanh toán thất bại!</h2>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="card warning">
                <div class="status-icon warning-icon">⚠️</div>
                <h2 style="color: #856404; text-align: center;">Chữ ký không hợp lệ!</h2>
            </div>
        <?php endif; ?>

        <div class="card">
            <h3>Thông tin giao dịch</h3>
            <div class="info">
                <p><strong>Mã đơn hàng:</strong> <?php echo htmlspecialchars($orderId); ?></p>
                <p><strong>Mã phản hồi:</strong> <?php echo htmlspecialchars($responseCode); ?></p>
                <p><strong>Thông báo:</strong> <?php echo htmlspecialchars($message); ?></p>
                <?php if ($transactionId): ?>
                    <p><strong>Mã giao dịch VNPAY:</strong> <?php echo htmlspecialchars($transactionId); ?></p>
                <?php endif; ?>
                <?php if ($bankCode): ?>
                    <p><strong>Mã ngân hàng:</strong> <?php echo htmlspecialchars($bankCode); ?></p>
                <?php endif; ?>
                <?php if ($cardType): ?>
                    <p><strong>Loại thẻ:</strong> <?php echo htmlspecialchars($cardType); ?></p>
                <?php endif; ?>
                <p><strong>Chữ ký hợp lệ:</strong> <?php echo $isValid ? 'Có' : 'Không'; ?></p>
            </div>
        </div>

        <div class="card">
            <h3>Dữ liệu callback từ VNPAY</h3>
            <div class="info">
                <pre><?php print_r($inputData); ?></pre>
            </div>
        </div>

        <div class="card">
            <h3>Thông tin hash</h3>
            <div class="info">
                <p><strong>Hash data:</strong> <?php echo htmlspecialchars($hashData); ?></p>
                <p><strong>Secure hash từ VNPAY:</strong> <?php echo htmlspecialchars($vnp_SecureHash); ?></p>
                <p><strong>Secure hash tính toán:</strong> <?php echo htmlspecialchars($secureHash); ?></p>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="vnpay_demo.php" class="btn">Quay lại Demo</a>
            <a href="/" class="btn">Về trang chủ</a>
        </div>
    </div>

    <script>
        // Log thông tin để debug
        console.log('VNPAY Return Data:', <?php echo json_encode($inputData); ?>);
        console.log('Is Valid:', <?php echo json_encode($isValid); ?>);
        console.log('Is Success:', <?php echo json_encode($isSuccess); ?>);
    </script>
</body>
</html>
