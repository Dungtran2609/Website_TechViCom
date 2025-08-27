# Chức năng Hủy Đơn Hàng cho Khách Vãng Lai

## Tổng quan
Chức năng này cho phép khách vãng lai (không đăng nhập) hủy đơn hàng của họ với các điều kiện và hạn chế cụ thể.

## Các điều kiện hủy đơn hàng

### ✅ Được phép hủy:
- **Trạng thái đơn hàng**: Chỉ được hủy khi trạng thái là `pending` (chờ xử lý) hoặc `processing` (đang xử lý)
- **Phương thức thanh toán COD**: Được hủy ngay lập tức không cần phê duyệt
- **Phương thức thanh toán khác**: Tạo yêu cầu hủy cần admin phê duyệt

### ❌ Không được phép hủy:
- **Đơn hàng VNPay**: Không thể hủy đơn hàng thanh toán qua VNPay (`payment_method = 'bank_transfer'`)
- **Đã có yêu cầu hủy**: Không thể gửi yêu cầu hủy mới nếu đã có yêu cầu hủy trước đó

## Cách hoạt động

### 1. Khách vãng lai truy cập chi tiết đơn hàng
- URL: `/invoice/order/{id}`
- Cần xác thực email trước khi thực hiện thao tác

### 2. Hiển thị nút hủy đơn hàng
- Chỉ hiển thị khi đơn hàng ở trạng thái `pending`
- Không hiển thị cho đơn hàng VNPay
- Không hiển thị nếu đã có yêu cầu hủy

### 3. Modal nhập lý do hủy
- Hiển thị danh sách lý do hủy phổ biến
- Có tùy chọn "Lý do khác" với textarea để nhập lý do cụ thể
- Validation: Phải chọn lý do trước khi xác nhận

### 4. Xử lý hủy đơn hàng
- **COD**: Hủy ngay lập tức, trạng thái chuyển thành `cancelled`
- **Khác**: Tạo yêu cầu hủy với trạng thái `pending`, cần admin phê duyệt

## Các lý do hủy đơn hàng

1. **Đổi ý không muốn mua nữa**
2. **Tìm thấy sản phẩm rẻ hơn ở nơi khác**
3. **Đặt nhầm sản phẩm**
4. **Thay đổi địa chỉ giao hàng**
5. **Lý do khác** (có textarea để nhập lý do cụ thể)

## Luồng xử lý

### Đơn hàng COD:
```
Khách vãng lai → Chọn lý do → Xác nhận → Hủy ngay lập tức → Trạng thái: cancelled
```

### Đơn hàng khác:
```
Khách vãng lai → Chọn lý do → Xác nhận → Tạo yêu cầu hủy → Trạng thái: pending → Admin phê duyệt
```

## Thông báo và trạng thái

### Thông báo hủy thành công:
- **COD**: "Đơn hàng COD đã được hủy thành công!"
- **Khác**: "Yêu cầu hủy đơn hàng đã được gửi thành công. Admin sẽ duyệt yêu cầu này."

### Hiển thị trạng thái:
- **Đã hủy**: Hiển thị lý do hủy
- **Đang chờ phê duyệt**: Hiển thị yêu cầu hủy đang chờ
- **Không thể hủy**: Hiển thị lý do (VNPay, đã có yêu cầu hủy)

## Bảo mật

- **Xác thực email**: Khách vãng lai phải xác thực email trước khi hủy
- **CSRF Protection**: Sử dụng CSRF token để bảo vệ form
- **Kiểm tra quyền**: Chỉ khách vãng lai mới có thể hủy đơn hàng của mình

## Database

### Bảng `order_returns`:
- `order_id`: ID đơn hàng
- `type`: 'cancel' (hủy)
- `reason`: Lý do hủy
- `client_note`: Ghi chú từ khách hàng
- `status`: 'approved' (COD) hoặc 'pending' (khác)
- `requested_at`: Thời gian yêu cầu
- `approved_at`: Thời gian phê duyệt (nếu có)

## API Endpoint

```
POST /invoice/order/{id}/cancel
```

### Parameters:
- `cancel_reason`: Lý do hủy
- `client_note`: Ghi chú cụ thể (nếu chọn "Lý do khác")

### Response:
```json
{
    "success": true,
    "message": "Đơn hàng COD đã được hủy thành công!"
}
```

## Ghi chú

- Chức năng này chỉ dành cho khách vãng lai (không đăng nhập)
- Khách hàng đã đăng nhập sử dụng chức năng hủy đơn hàng riêng biệt
- Admin có thể xem và phê duyệt các yêu cầu hủy đơn hàng trong admin panel
