<?php
/**
 * Demo VNPAY Payment - File test độc lập
 * Truy cập: http://localhost/vnpay_demo.php
 */

// Cấu hình VNPAY Sandbox
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/vnpay_return_demo.php";
$vnp_TmnCode = "2WZSC2P3";
$vnp_HashSecret = "NWNXS265YSNAIGEH1L26KHKDIVET7QB1";

// Thông tin đơn hàng demo
$vnp_TxnRef = "TEST_" . time(); // Mã đơn hàng
$vnp_OrderInfo = "Thanh toan don hang test";
$vnp_OrderType = "other";
$vnp_Amount = 20000 * 100; // 20,000 VND
$vnp_Locale = "vn";
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
$vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

// Thông tin khách hàng
$vnp_Bill_Mobile = "0123456789";
$vnp_Bill_Email = "test@example.com";
$vnp_Bill_FirstName = "Nguyen";
$vnp_Bill_LastName = "Van A";
$vnp_Bill_Address = "123 Test Street";
$vnp_Bill_City = "Ha Noi";
$vnp_Bill_Country = "VN";

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
    "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
    "vnp_Bill_Email" => $vnp_Bill_Email,
    "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
    "vnp_Bill_LastName" => $vnp_Bill_LastName,
    "vnp_Bill_Address" => $vnp_Bill_Address,
    "vnp_Bill_City" => $vnp_Bill_City,
    "vnp_Bill_Country" => $vnp_Bill_Country,
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
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo VNPAY Payment</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .btn { background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn:hover { background: #0056b3; }
        .info { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Demo VNPAY Payment</h1>
        
        <div class="card">
            <h3>Thông tin đơn hàng test</h3>
            <div class="info">
                <p><strong>Mã đơn hàng:</strong> <?php echo $vnp_TxnRef; ?></p>
                <p><strong>Số tiền:</strong> <?php echo number_format($vnp_Amount / 100); ?> VND</p>
                <p><strong>Mô tả:</strong> <?php echo $vnp_OrderInfo; ?></p>
                <p><strong>Thời gian hết hạn:</strong> <?php echo date('d/m/Y H:i:s', strtotime('+15 minutes')); ?></p>
            </div>
        </div>

        <div class="card">
            <h3>Thông tin thẻ test</h3>
            <div class="info">
                <p><strong>Số thẻ:</strong> 4200000000000000</p>
                <p><strong>Ngày hết hạn:</strong> 12/25</p>
                <p><strong>CVV:</strong> 123</p>
                <p><strong>Tên chủ thẻ:</strong> NGUYEN VAN A</p>
            </div>
        </div>

        <div class="card">
            <h3>Bắt đầu thanh toán</h3>
            <p>Nhấn nút bên dưới để chuyển đến trang thanh toán VNPAY:</p>
            <a href="<?php echo $vnp_Url; ?>" class="btn">Thanh toán VNPAY</a>
        </div>

        <div class="card">
            <h3>Lưu ý</h3>
            <ul>
                <li>Đây là môi trường sandbox (test)</li>
                <li>Không có tiền thật được trừ</li>
                <li>Sau khi thanh toán sẽ chuyển về trang return demo</li>
                <li>Kiểm tra console để xem thông tin callback</li>
            </ul>
        </div>
    </div>
</body>
</html>
