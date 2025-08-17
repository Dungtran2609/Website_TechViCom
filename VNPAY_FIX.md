# Hướng dẫn sửa lỗi VNPAY "Sai chữ ký"

## Vấn đề
Lỗi "Sai chữ ký" (Incorrect signature) xảy ra khi VNPAY không thể xác thực chữ ký hash của request.

## Nguyên nhân có thể
1. **Hash Secret không đúng** - Cấu hình hash secret trong config không khớp với VNPAY
2. **Thứ tự tham số sai** - VNPAY yêu cầu tham số phải được sắp xếp theo thứ tự alphabet
3. **Encoding tham số sai** - URL encoding không đúng format
4. **Tham số rỗng** - Có tham số null hoặc rỗng trong hash data

## Các thay đổi đã thực hiện

### 1. Sửa VNPAYService.php
- Cải thiện logic tạo hash data
- Loại bỏ tham số rỗng trước khi tạo hash
- Đảm bảo thứ tự tham số đúng
- Chỉ thêm thông tin khách hàng khi có giá trị

### 2. Sửa Controller
- Sửa parameter binding trong `vnpay_payment` method
- Đảm bảo lấy `order_id` từ route parameter thay vì request

### 3. Cải thiện xử lý callback
- Logic nhất quán giữa tạo URL và xử lý return
- Loại bỏ tham số rỗng trong callback

## Cách test

### 1. Test đơn giản
Truy cập: `http://localhost/test_vnpay_simple.php`

### 2. Test qua Laravel
Truy cập: `http://localhost/vnpay/payment/{order_id}`

### 3. Test return
Truy cập: `http://localhost/vnpay/return` với các tham số test

## Cấu hình cần kiểm tra

### 1. File .env
```env
VNPAY_ENVIRONMENT=sandbox
VNPAY_TMN_CODE=2WZSC2P3
VNPAY_HASH_SECRET=NWNXS265YSNAIGEH1L26KHKDIVET7QB1
VNPAY_RETURN_URL=http://localhost/vnpay/return
```

### 2. File config/vnpay.php
Đảm bảo cấu hình sandbox đúng:
```php
'sandbox' => [
    'url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
    'tmn_code' => '2WZSC2P3',
    'hash_secret' => 'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
],
```

## Thông tin test VNPAY Sandbox

### Thẻ test
- **Số thẻ:** 4200000000000000
- **Ngày hết hạn:** 12/25
- **CVV:** 123
- **Tên chủ thẻ:** NGUYEN VAN A

### Mã OTP
- **OTP:** 123456

## Debug

### 1. Kiểm tra hash data
Xem hash data được tạo ra có đúng format không:
```
vnp_Amount=10000000&vnp_Command=pay&vnp_CreateDate=20250815120000&vnp_CurrCode=VND&vnp_IpAddr=127.0.0.1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%23999&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2Flocalhost%2Fvnpay%2Freturn&vnp_TmnCode=2WZSC2P3&vnp_TxnRef=999&vnp_Version=2.1.0
```

### 2. Kiểm tra secure hash
Đảm bảo secure hash được tạo đúng với hash secret.

### 3. Kiểm tra URL
URL cuối cùng phải có format:
```
https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Amount=10000000&vnp_Command=pay&...&vnp_SecureHash=abc123...
```

## Nếu vẫn lỗi

1. **Kiểm tra hash secret** - Đảm bảo dùng đúng hash secret từ VNPAY
2. **Kiểm tra TMN Code** - Đảm bảo TMN code đúng
3. **Kiểm tra URL** - Đảm bảo URL sandbox đúng
4. **Liên hệ VNPAY** - Nếu vẫn lỗi, liên hệ support VNPAY để kiểm tra cấu hình

## Lưu ý
- Luôn test trên sandbox trước khi deploy production
- Không commit hash secret production vào code
- Sử dụng environment variables cho production
