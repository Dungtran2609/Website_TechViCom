# VNPAY Fix Summary - Sửa lỗi "Sai chữ ký" (Complete Solution)

## Vấn đề
Lỗi "Sai chữ ký" (Incorrect signature) xảy ra khi VNPAY không thể xác thực chữ ký hash của request.

## Nguyên nhân chính
1. **Sai chuỗi dữ liệu (hash data)**: Chữ ký số được tạo ra từ một chuỗi dữ liệu bao gồm các tham số giao dịch
2. **Sai thứ tự tham số**: Các tham số trong chuỗi dữ liệu cần được sắp xếp theo thứ tự bảng chữ cái
3. **Thiếu tham số**: Việc thiếu bất kỳ tham số bắt buộc nào trong chuỗi dữ liệu
4. **Sai thuật toán mã hóa**: VNPAY sử dụng HMAC-SHA512
5. **Sai mã bí mật (Hash Secret)**: Mã bí mật không đúng
6. **Sử dụng lại chuỗi mã hóa**: Mỗi chuỗi mã hóa chỉ được sử dụng một lần
7. **Sai logic tạo hash data**: Hash data phải bằng query string theo tài liệu VNPAY chính thức

## Giải pháp hoàn chỉnh đã áp dụng

### 1. Sửa logic tạo hash data trong `VNPayService.php`

**Logic mới (chính xác theo VNPAY):**
```php
// Tạo query string
$query = "";
foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

// Loại bỏ & cuối cùng từ query
$query = rtrim($query, '&');

// Hash data = query string (theo tài liệu VNPAY chính thức)
$hashdata = $query;

// Tạo URL thanh toán
$vnp_Url = $this->config['url'] . "?" . $query;
$vnpSecureHash = hash_hmac('sha512', $hashdata, $this->config['hash_secret']);
$vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
```

### 2. Áp dụng fix cho cả hai method
- `buildPaymentUrl()` - Tạo URL thanh toán
- `processReturn()` - Xử lý callback từ VNPAY

## Cách khắc phục chi tiết

### 1. Kiểm tra lại chuỗi dữ liệu
- Đảm bảo tất cả các tham số cần thiết đều được đưa vào chuỗi dữ liệu
- Sắp xếp đúng thứ tự theo tài liệu hướng dẫn của VNPAY

### 2. Sử dụng hàm sắp xếp có sẵn
- Sử dụng `ksort($inputData)` để sắp xếp các tham số một cách chính xác

### 3. Đối chiếu thuật toán mã hóa
- Xác nhận sử dụng đúng thuật toán mã hóa HMAC-SHA512

### 4. Kiểm tra lại mã bí mật
- Đảm bảo sử dụng đúng mã bí mật được cung cấp trong email đăng ký sandbox

### 5. Tạo mới yêu cầu thanh toán
- Luôn tạo một yêu cầu thanh toán mới cho mỗi giao dịch

## Cách test

### 1. Test đơn giản
Truy cập: `http://localhost/vnpay_debug_simple.php`

### 2. Test hoàn chỉnh
Truy cập: `http://localhost/test_vnpay_complete_fix.php`

### 3. Test fix chữ ký
Truy cập: `http://localhost/test_vnpay_fixed_signature.php`

### 4. Debug chữ ký chi tiết
Truy cập: `http://localhost/debug_vnpay_signature.php`

### 3. Test qua Laravel
Truy cập: `http://localhost/vnpay/payment/1`

### 4. Test return
Truy cập: `http://localhost/vnpay/return` với các tham số test

## Thông tin test VNPAY Sandbox

### Thẻ test
- **Số thẻ:** 4200000000000000
- **Ngày hết hạn:** 12/25
- **CVV:** 123
- **Tên chủ thẻ:** NGUYEN VAN A

### Mã OTP
- **OTP:** 123456

## Cấu hình VNPAY

### File config/vnpay.php
```php
'sandbox' => [
    'url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
    'tmn_code' => '2WZSC2P3',
    'hash_secret' => 'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
],
```

### File .env (nếu có)
```env
VNPAY_ENVIRONMENT=sandbox
VNPAY_TMN_CODE=2WZSC2P3
VNPAY_HASH_SECRET=NWNXS265YSNAIGEH1L26KHKDIVET7QB1
VNPAY_RETURN_URL=http://localhost/vnpay/return
```

## Files đã được sửa

1. **app/Services/VNPayService.php**
   - Sửa method `buildPaymentUrl()` với logic chính xác theo tài liệu VNPAY
   - Sửa method `processReturn()` với logic chính xác theo tài liệu VNPAY

2. **test_vnpay_complete_fix.php** (mới tạo)
   - File test hoàn chỉnh để verify fix

3. **test_vnpay_fixed_signature.php** (mới tạo)
   - File test fix chữ ký VNPAY

4. **debug_vnpay_signature.php** (mới tạo)
   - File debug chi tiết để kiểm tra các phương pháp tạo chữ ký

5. **vnpay_debug_simple.php** (mới tạo)
   - File debug đơn giản để kiểm tra

## Kết quả mong đợi

Sau khi áp dụng fix:
- VNPAY sẽ không còn báo lỗi "Sai chữ ký" (code=70)
- Thanh toán sẽ hoạt động bình thường
- Hash data được tạo đúng format theo tài liệu VNPAY chính thức
- URL thanh toán có format chính xác
- Logic tạo chữ ký nhất quán giữa tạo URL và xử lý callback

## Lưu ý quan trọng

- Fix này đảm bảo tính nhất quán giữa tạo URL và xử lý callback
- Logic mới sử dụng **hash data = query string** theo tài liệu VNPAY chính thức
- Sử dụng `rtrim()` để loại bỏ & cuối cùng từ query string
- Hash data và query string giống nhau (không có & ở cuối)
- Đây là cách tạo chữ ký chính xác theo tài liệu VNPAY mới nhất

## Nếu vẫn gặp vấn đề

1. **Kiểm tra hash secret** - Đảm bảo dùng đúng hash secret từ VNPAY
2. **Kiểm tra TMN Code** - Đảm bảo TMN code đúng
3. **Kiểm tra URL** - Đảm bảo URL sandbox đúng
4. **Kiểm tra tham số** - Đảm bảo tất cả tham số bắt buộc có đầy đủ
5. **Liên hệ VNPAY** - Nếu vẫn lỗi, liên hệ support VNPAY để kiểm tra cấu hình

## Debug Information

- **PHP Version:** Kiểm tra phiên bản PHP
- **Hash Algorithm:** SHA512
- **Hash Secret Length:** 32 characters
- **Hash Data Length:** Variable
- **Secure Hash Length:** 128 characters
