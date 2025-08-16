# Hướng dẫn Test VNPAY Payment

## Cấu hình hiện tại

### 1. Thông tin Sandbox VNPAY
- **URL**: https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
- **TMN Code**: 2WZSC2P3
- **Hash Secret**: NWNXS265YSNAIGEH1L26KHKDIVET7QB1

### 2. Routes đã tạo
- `GET /vnpay/payment/{order_id}` - Tạo URL thanh toán VNPAY
- `GET /vnpay/return` - Xử lý callback từ VNPAY

### 3. Database đã cập nhật
- Thêm các cột VNPAY vào bảng `orders`:
  - `vnpay_url` - URL thanh toán
  - `vnpay_transaction_id` - Mã giao dịch VNPAY
  - `vnpay_bank_code` - Mã ngân hàng
  - `vnpay_card_type` - Loại thẻ
  - `paid_at` - Thời gian thanh toán

## Cách test

### Bước 1: Tạo đơn hàng
1. Vào trang checkout
2. Chọn sản phẩm
3. Chọn phương thức thanh toán "VNPAY"
4. Nhấn "Xác nhận đặt hàng"

### Bước 2: Thanh toán trên VNPAY
1. Hệ thống sẽ chuyển hướng đến trang VNPAY sandbox
2. Chọn phương thức thanh toán:
   - **QR Code**: Quét mã QR bằng app VNPAY
   - **Thẻ ngân hàng**: Nhập thông tin thẻ test

### Bước 3: Thông tin thẻ test
- **Số thẻ**: 4200000000000000
- **Ngày hết hạn**: 12/25
- **CVV**: 123
- **Tên chủ thẻ**: NGUYEN VAN A

### Bước 4: Kết quả
- **Thành công**: Chuyển về trang success với thông báo "Thanh toán thành công!"
- **Thất bại**: Chuyển về trang checkout với thông báo lỗi

## Cấu trúc code

### 1. Service Class
- `app/Services/VNPayService.php` - Xử lý logic VNPAY

### 2. Controller Methods
- `vnpay_payment()` - Tạo URL thanh toán
- `vnpay_return()` - Xử lý callback

### 3. Config
- `config/vnpay.php` - Cấu hình VNPAY

### 4. Views
- Cập nhật checkout để hiển thị VNPAY
- Cập nhật success page để hiển thị thông tin thanh toán

## Lưu ý quan trọng

1. **Sandbox Environment**: Hiện tại đang sử dụng môi trường test
2. **Return URL**: Phải là URL công khai (không thể localhost)
3. **Hash Secret**: Phải khớp với cấu hình VNPAY
4. **Amount**: VNPAY yêu cầu số tiền nhân 100 (VND)

## Troubleshooting

### Lỗi thường gặp
1. **Chữ ký không hợp lệ**: Kiểm tra Hash Secret
2. **Không tìm thấy đơn hàng**: Kiểm tra order_id
3. **URL không hợp lệ**: Kiểm tra return URL

### Debug
- Kiểm tra logs: `storage/logs/laravel.log`
- Kiểm tra database: Bảng `orders` với các cột VNPAY
