# Hệ thống Tra cứu Hóa đơn - Techvicom

## Tổng quan

Hệ thống tra cứu hóa đơn cho phép khách vãng lai và khách hàng đã đăng nhập tra cứu đơn hàng của mình thông qua email và mã xác nhận.

## Tính năng chính

### 1. Tra cứu đơn hàng cho khách vãng lai
- Nhập email để nhận mã xác nhận
- Mã xác nhận 6 số được gửi qua email
- Xác thực mã để xem danh sách đơn hàng
- Hiển thị chi tiết đơn hàng
- Tải hóa đơn PDF (đang phát triển)

### 2. Gửi email tự động
- Email xác nhận đơn hàng khi tạo đơn hàng mới
- Email thông báo thanh toán thành công
- Email thông báo đơn hàng đã giao
- Email thông báo đơn hàng đã nhận thành công

### 3. Bảo mật
- Mã xác nhận có hiệu lực 10 phút
- Mã chỉ sử dụng được một lần
- Session-based authentication cho khách vãng lai

## Cấu trúc hệ thống

### Controllers
- `InvoiceController`: Xử lý tra cứu hóa đơn
- `OrderObserver`: Gửi email tự động khi đơn hàng thay đổi trạng thái

### Views
- `invoice.blade.php`: Trang tra cứu chính
- `invoice-detail.blade.php`: Chi tiết đơn hàng
- Email templates trong thư mục `emails/`

### Routes
```php
Route::get('/invoice', [InvoiceController::class, 'index'])->name('client.invoice.index');
Route::post('/invoice/send-verification-code', [InvoiceController::class, 'sendVerificationCode']);
Route::post('/invoice/verify-code', [InvoiceController::class, 'verifyCode']);
Route::get('/invoice/order/{id}', [InvoiceController::class, 'showOrder']);
Route::get('/invoice/download/{id}', [InvoiceController::class, 'downloadInvoice']);
```

## Cách sử dụng

### 1. Cấu hình Email (Mailtrap)
Đảm bảo đã cấu hình Mailtrap trong file `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="techvicom@gmail.com"
MAIL_FROM_NAME="Techvicom"
```

### 2. Chạy Migration
```bash
php artisan migrate
```

### 3. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 4. Test hệ thống
1. Truy cập `/invoice`
2. Nhập email có đơn hàng
3. Kiểm tra email trong Mailtrap
4. Nhập mã xác nhận
5. Xem danh sách đơn hàng

## Email Templates

### 1. Xác nhận đơn hàng (`order-confirmation.blade.php`)
- Gửi khi tạo đơn hàng mới
- Hiển thị thông tin đơn hàng, sản phẩm, tổng tiền

### 2. Thanh toán thành công (`payment-success.blade.php`)
- Gửi khi thanh toán thành công
- Hiển thị thông tin thanh toán và các bước tiếp theo

### 3. Đơn hàng đã giao (`order-shipped.blade.php`)
- Gửi khi đơn hàng được giao
- Hiển thị thông tin giao hàng và địa chỉ

### 4. Đơn hàng đã nhận (`order-delivered.blade.php`)
- Gửi khi đơn hàng được nhận thành công
- Khuyến khích đánh giá và mua sắm thêm

### 5. Mã xác nhận (`invoice-verification.blade.php`)
- Gửi mã 6 số để xác thực tra cứu
- Có hiệu lực 10 phút

## Bảo mật

### 1. Mã xác nhận
- Tạo ngẫu nhiên 6 số
- Lưu trong cache với thời gian 10 phút
- Xóa sau khi sử dụng thành công

### 2. Session Management
- Lưu email đã xác thực trong session
- Kiểm tra session trước khi cho phép xem chi tiết

### 3. Email Validation
- Kiểm tra email hợp lệ
- Kiểm tra tồn tại đơn hàng với email

## Troubleshooting

### 1. Email không gửi được
- Kiểm tra cấu hình Mailtrap
- Kiểm tra log trong `storage/logs/laravel.log`
- Đảm bảo queue worker đang chạy (nếu sử dụng queue)

### 2. Mã xác nhận không hoạt động
- Kiểm tra cache configuration
- Kiểm tra thời gian cache
- Clear cache nếu cần

### 3. Không tìm thấy đơn hàng
- Kiểm tra email trong database
- Đảm bảo đơn hàng có `guest_email` hoặc `user.email`

## Phát triển thêm

### 1. Tải hóa đơn PDF
- Cài đặt package DomPDF hoặc Snappy
- Tạo template PDF
- Implement method `downloadInvoice`

### 2. SMS Notification
- Tích hợp SMS gateway
- Gửi SMS thông báo trạng thái đơn hàng

### 3. Push Notification
- Tích hợp Firebase Cloud Messaging
- Gửi push notification cho mobile app

### 4. Webhook Integration
- Tích hợp với hệ thống bên ngoài
- Gửi webhook khi đơn hàng thay đổi trạng thái

## API Endpoints

### Gửi mã xác nhận
```http
POST /invoice/send-verification-code
Content-Type: application/json

{
    "email": "customer@example.com"
}
```

### Xác thực mã
```http
POST /invoice/verify-code
Content-Type: application/json

{
    "email": "customer@example.com",
    "verification_code": "123456"
}
```

### Xem chi tiết đơn hàng
```http
GET /invoice/order/{id}
```

### Tải hóa đơn
```http
GET /invoice/download/{id}
```

## Logs

Hệ thống ghi log các hoạt động quan trọng:
- Lỗi gửi email
- Xác thực mã thành công/thất bại
- Tra cứu đơn hàng

Log files được lưu trong `storage/logs/laravel.log`
