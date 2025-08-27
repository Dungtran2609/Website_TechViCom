# Chức năng Trả Hàng cho Khách Vãng Lai

## Tổng quan
Chức năng này cho phép khách vãng lai (không đăng nhập) yêu cầu trả hàng với các yêu cầu về minh chứng cụ thể. Admin phải xem xét minh chứng trước khi xử lý yêu cầu.

## Các điều kiện trả hàng

### ✅ Được phép yêu cầu trả hàng:
- **Trạng thái đơn hàng**: Chỉ được yêu cầu khi trạng thái là `delivered` (đã giao hàng)
- **Chưa có yêu cầu trả hàng**: Không thể gửi yêu cầu mới nếu đã có yêu cầu trước đó

### ❌ Không được phép:
- **Đơn hàng chưa giao**: Chỉ được yêu cầu khi đã nhận hàng
- **Đã có yêu cầu trả hàng**: Không thể gửi yêu cầu mới

## Yêu cầu bắt buộc khi trả hàng

### 1. **Chọn sản phẩm cần trả**:
- Phải chọn ít nhất một sản phẩm từ đơn hàng
- Hiển thị danh sách sản phẩm với hình ảnh và thông tin

### 2. **Lý do trả hàng**:
- **Sản phẩm bị lỗi**
- **Sản phẩm không đúng mô tả**
- **Sản phẩm bị hỏng khi giao hàng**
- **Không vừa size**
- **Lý do khác** (có textarea để nhập lý do cụ thể)

### 3. **Hình ảnh minh chứng** (BẮT BUỘC):
- Phải chụp ảnh để chứng minh lý do trả hàng
- Có thể chọn nhiều ảnh
- Định dạng: JPG, PNG
- Giới hạn: Tối đa 10MB mỗi ảnh

### 4. **Video minh chứng** (BẮT BUỘC):
- Phải quay video ngắn để chứng minh lý do trả hàng
- Định dạng: MP4, AVI, MOV
- Giới hạn: Tối đa 50MB

### 5. **Ghi chú thêm**:
- Mô tả chi tiết về vấn đề gặp phải
- Không bắt buộc nhưng khuyến khích

## Luồng xử lý trả hàng

### 1. **Khách vãng lai gửi yêu cầu**:
```
Chọn sản phẩm → Chọn lý do → Upload ảnh/video → Ghi chú → Gửi yêu cầu
```

### 2. **Admin xem xét**:
```
Xem minh chứng → Xác nhận đã xem → Upload minh chứng hoàn tiền → Chấp nhận/Từ chối
```

### 3. **Kết quả**:
- **Chấp nhận**: Đơn hàng chuyển trạng thái `returned`, admin upload minh chứng hoàn tiền
- **Từ chối**: Yêu cầu bị từ chối với lý do cụ thể

## Giao diện người dùng

### **Khách vãng lai**:
- Nút "Yêu cầu trả hàng" hiển thị khi đơn hàng đã giao
- Modal nhập thông tin trả hàng với validation đầy đủ
- Preview ảnh và video trước khi gửi
- Thông báo trạng thái yêu cầu trả hàng

### **Admin**:
- Danh sách yêu cầu trả hàng với thông tin chi tiết
- Xem minh chứng của client (ảnh, video, ghi chú)
- Upload minh chứng hoàn tiền khi chấp nhận
- Xác nhận đã xem xét minh chứng trước khi xử lý

## Bảo mật và validation

### **Phía client**:
- **Xác thực email**: Khách vãng lai phải xác thực email trước khi trả hàng
- **CSRF Protection**: Sử dụng CSRF token để bảo vệ form
- **Validation**: Kiểm tra đầy đủ các trường bắt buộc
- **File validation**: Kiểm tra định dạng và kích thước file

### **Phía admin**:
- **Xác nhận xem minh chứng**: Bắt buộc xác nhận đã xem xét minh chứng
- **Upload minh chứng**: Bắt buộc upload ảnh khi chấp nhận trả hàng
- **Ghi chú**: Bắt buộc nhập ghi chú khi xử lý yêu cầu

## Database

### **Bảng `order_returns`**:
- `order_id`: ID đơn hàng
- `type`: 'return' (trả hàng)
- `reason`: Lý do trả hàng
- `client_note`: Ghi chú từ khách hàng
- `status`: 'pending' (chờ phê duyệt), 'approved' (đã chấp nhận), 'rejected' (đã từ chối)
- `requested_at`: Thời gian yêu cầu
- `processed_at`: Thời gian xử lý
- `admin_note`: Ghi chú từ admin
- `images`: Ảnh minh chứng từ client (JSON)
- `video`: Video minh chứng từ client
- `admin_proof_images`: Ảnh minh chứng từ admin (JSON)
- `selected_products`: Sản phẩm được chọn để trả (JSON)

## API Endpoint

```
POST /invoice/order/{id}/request-return
```

### **Parameters**:
- `selected_products[]`: ID sản phẩm được chọn
- `return_reason`: Lý do trả hàng
- `product_images[]`: Ảnh minh chứng
- `return_video`: Video minh chứng
- `client_note`: Ghi chú thêm

### **Response**:
```json
{
    "success": true,
    "message": "Yêu cầu trả hàng đã được gửi thành công. Chúng tôi sẽ xem xét và phản hồi trong thời gian sớm nhất."
}
```

## Hiển thị trạng thái

### **Đang chờ phê duyệt**:
- Icon đồng hồ màu vàng
- Hiển thị lý do trả hàng
- Nút "Đã yêu cầu trả hàng" (disabled)

### **Đã được chấp nhận**:
- Icon check màu xanh
- Hiển thị lý do và phản hồi từ admin
- Hiển thị minh chứng từ admin (ảnh hoàn tiền)

### **Đã bị từ chối**:
- Icon X màu đỏ
- Hiển thị lý do và lý do từ chối từ admin

## Minh chứng từ admin

### **Khi chấp nhận trả hàng**:
- Admin phải upload ảnh chứng minh đã hoàn tiền
- Ảnh được lưu vào `admin_proof_images`
- Client có thể xem minh chứng này trong chi tiết đơn hàng

### **Mục đích**:
- Chứng minh đã hoàn tiền cho khách hàng
- Tạo minh bạch trong quá trình xử lý
- Lưu trữ bằng chứng cho việc kế toán

## Ghi chú quan trọng

- **Chức năng này chỉ dành cho khách vãng lai** (không đăng nhập)
- **Khách hàng đã đăng nhập** sử dụng chức năng trả hàng riêng biệt
- **Admin bắt buộc phải xem xét minh chứng** trước khi xử lý yêu cầu
- **Minh chứng từ client và admin** đều được lưu trữ và hiển thị
- **Quá trình trả hàng** được theo dõi đầy đủ từ yêu cầu đến hoàn thành
