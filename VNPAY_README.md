# Hệ thống Thanh toán VNPAY - Techvicom

## Tổng quan

Hệ thống thanh toán VNPAY đã được tích hợp hoàn chỉnh vào website Techvicom với các tính năng:

- ✅ Thanh toán qua VNPAY (QR Code + Thẻ ngân hàng)
- ✅ Xử lý callback từ VNPAY
- ✅ Cập nhật trạng thái đơn hàng tự động
- ✅ Lưu trữ thông tin giao dịch
- ✅ Giao diện thân thiện người dùng

## Cấu trúc Code

### 1. Service Layer
```
app/Services/VNPayService.php
```
- Tạo URL thanh toán VNPAY
- Xử lý callback từ VNPAY
- Cập nhật trạng thái đơn hàng

### 2. Controller
```
app/Http/Controllers/Client/Checkouts/ClientCheckoutController.php
```
- `vnpay_payment()` - Tạo URL thanh toán
- `vnpay_return()` - Xử lý callback

### 3. Configuration
```
config/vnpay.php
```
- Cấu hình sandbox/production
- Thông tin TMN Code, Hash Secret
- Các tham số mặc định

### 4. Database
```sql
-- Các cột mới trong bảng orders
vnpay_url VARCHAR(500) NULL
vnpay_transaction_id VARCHAR(100) NULL  
vnpay_bank_code VARCHAR(50) NULL
vnpay_card_type VARCHAR(50) NULL
paid_at TIMESTAMP NULL
```

### 5. Routes
```php
// VNPAY Payment routes
Route::prefix('vnpay')->name('vnpay.')->group(function () {
    Route::get('/payment/{order_id}', [ClientCheckoutController::class, 'vnpay_payment'])->name('payment');
    Route::get('/return', [ClientCheckoutController::class, 'vnpay_return'])->name('return');
});
```

## Cách sử dụng

### 1. Trong Checkout
1. Chọn sản phẩm vào giỏ hàng
2. Vào trang checkout
3. Chọn phương thức thanh toán "VNPAY"
4. Nhấn "Xác nhận đặt hàng"
5. Hệ thống chuyển hướng đến VNPAY

### 2. Thanh toán trên VNPAY
- **QR Code**: Quét mã QR bằng app VNPAY
- **Thẻ ngân hàng**: Nhập thông tin thẻ

### 3. Kết quả
- **Thành công**: Chuyển về trang success
- **Thất bại**: Chuyển về trang checkout với thông báo lỗi

## Test

### 1. Demo độc lập
```
http://localhost/vnpay_demo.php
```
- File test VNPAY không phụ thuộc Laravel
- Hiển thị đầy đủ thông tin callback

### 2. Test trong hệ thống
1. Tạo đơn hàng với VNPAY
2. Thanh toán với thẻ test
3. Kiểm tra kết quả

### 3. Thông tin thẻ test
- **Số thẻ**: 4200000000000000
- **Ngày hết hạn**: 12/25
- **CVV**: 123
- **Tên chủ thẻ**: NGUYEN VAN A

## Cấu hình

### 1. Sandbox (Test)
```php
// config/vnpay.php
'sandbox' => [
    'url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
    'tmn_code' => '2WZSC2P3',
    'hash_secret' => 'NWNXS265YSNAIGEH1L26KHKDIVET7QB1',
],
```

### 2. Production
```php
// config/vnpay.php
'production' => [
    'url' => 'https://pay.vnpay.vn/vpcpay.html',
    'tmn_code' => env('VNPAY_TMN_CODE', ''),
    'hash_secret' => env('VNPAY_HASH_SECRET', ''),
],
```

### 3. Environment Variables
```env
VNPAY_ENVIRONMENT=sandbox
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_RETURN_URL=https://yourdomain.com/vnpay/return
```

## Tính năng nâng cao

### 1. Webhook (Tùy chọn)
- Nhận thông báo từ VNPAY qua webhook
- Cập nhật trạng thái real-time
- Xử lý các trường hợp đặc biệt

### 2. Logging
- Log tất cả giao dịch VNPAY
- Debug và troubleshooting
- Audit trail

### 3. Security
- Validate chữ ký VNPAY
- Kiểm tra IP address
- Rate limiting

## Troubleshooting

### 1. Lỗi thường gặp
- **Route [vnpay.return] not defined**: Đã sửa - sử dụng URL thay vì route helper trong config
- **Class 'App\Services\VNPayService' not found**: Đã sửa - chạy `composer dump-autoload` và `php artisan optimize:clear`
- **Data too long for column 'vnpay_url'**: Đã sửa - tăng kích thước cột lên 2000 ký tự
- **Chữ ký không hợp lệ**: Kiểm tra Hash Secret
- **Không tìm thấy đơn hàng**: Kiểm tra order_id
- **URL không hợp lệ**: Kiểm tra return URL

### 2. Debug
```php
// Bật debug mode
Log::info('VNPAY Debug', $vnpayData);

// Kiểm tra logs
tail -f storage/logs/laravel.log
```

### 3. Test tools
- VNPAY Demo: `http://localhost/vnpay_demo.php`
- Return Demo: `http://localhost/vnpay_return_demo.php`
- Simple Test: `http://localhost/test_vnpay_simple.php`
- Working Test: `http://localhost/test_vnpay_working.php`

## Lưu ý quan trọng

1. **Sandbox Environment**: Chỉ dùng để test, không có tiền thật
2. **Return URL**: Phải là URL công khai (không thể localhost)
3. **Hash Secret**: Phải khớp với cấu hình VNPAY
4. **Amount**: VNPAY yêu cầu số tiền nhân 100 (VND)
5. **Timeout**: Giao dịch hết hạn sau 15 phút

## Tài liệu tham khảo

- [VNPAY Documentation](https://sandbox.vnpayment.vn/apis/docs/huong-dan-tich-hop)
- [VNPAY Sandbox](https://sandbox.vnpayment.vn/)
- [VNPAY Production](https://pay.vnpay.vn/)

## Support

Nếu có vấn đề, vui lòng:
1. Kiểm tra logs: `storage/logs/laravel.log`
2. Test với demo: `http://localhost/vnpay_demo.php`
3. Liên hệ support: support@techvicom.vn
