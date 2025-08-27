# Sửa lỗi và Hướng dẫn sử dụng Chức năng Trả Hàng

## 🚨 Lỗi đã được sửa

### **Lỗi SQL: Column not found 'selected_products'**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'selected_products' in 'field list'
```

**Nguyên nhân**: Migration `2025_08_26_034219_add_selected_products_to_order_returns_table` chưa được chạy.

**Cách sửa**: Chạy migration để thêm cột `selected_products` vào bảng `order_returns`.

```bash
php artisan migrate
```

**Kết quả**: Migration đã được chạy thành công, cột `selected_products` đã được thêm vào bảng.

## ✅ Các vấn đề đã được sửa

### 1. **Cột database thiếu**:
- ✅ Thêm cột `selected_products` vào bảng `order_returns`
- ✅ Migration đã được chạy thành công

### 2. **Hiển thị sản phẩm trong modal trả hàng**:
- ✅ Sửa giá trị checkbox để sử dụng ID sản phẩm thay vì ID order item
- ✅ Cải thiện hiển thị tên sản phẩm với fallback
- ✅ Cải thiện hiển thị ảnh sản phẩm với nhiều fallback

### 3. **JavaScript validation**:
- ✅ Sửa việc append `client_note` hai lần
- ✅ Kết hợp lý do trả hàng và ghi chú thêm
- ✅ Validation đầy đủ các trường bắt buộc

## 🎯 Cách sử dụng chức năng trả hàng

### **Bước 1: Truy cập đơn hàng**
- URL: `/invoice/order/{id}`
- Đơn hàng phải ở trạng thái `delivered` (đã giao hàng)

### **Bước 2: Mở modal trả hàng**
- Nhấn nút "Yêu cầu trả hàng" (màu cam)
- Modal sẽ hiển thị với form đầy đủ

### **Bước 3: Chọn sản phẩm cần trả**
- ✅ Chọn ít nhất một sản phẩm từ danh sách
- Mỗi sản phẩm hiển thị: ảnh, tên, số lượng
- Checkbox để chọn sản phẩm

### **Bước 4: Chọn lý do trả hàng**
- **Sản phẩm bị lỗi**
- **Sản phẩm không đúng mô tả**
- **Sản phẩm bị hỏng khi giao hàng**
- **Không vừa size**
- **Lý do khác** (có textarea để nhập lý do cụ thể)

### **Bước 5: Upload minh chứng**
- **Hình ảnh minh chứng** (BẮT BUỘC):
  - Chọn nhiều ảnh
  - Định dạng: JPG, PNG
  - Giới hạn: 10MB mỗi ảnh
  - Preview ảnh trước khi gửi

- **Video minh chứng** (BẮT BUỘC):
  - Định dạng: MP4, AVI, MOV
  - Giới hạn: 50MB
  - Preview video trước khi gửi

### **Bước 6: Ghi chú thêm**
- Mô tả chi tiết về vấn đề gặp phải
- Không bắt buộc nhưng khuyến khích

### **Bước 7: Xác nhận và gửi**
- Nhấn nút "Xác nhận yêu cầu trả hàng"
- Xác nhận thông tin cuối cùng
- Gửi yêu cầu đến admin

## 🔍 Validation và bảo mật

### **Validation phía client**:
- ✅ Phải chọn ít nhất một sản phẩm
- ✅ Phải chọn lý do trả hàng
- ✅ Phải upload ít nhất một ảnh
- ✅ Phải upload video
- ✅ Nếu chọn "Lý do khác" thì phải nhập lý do cụ thể

### **Bảo mật**:
- ✅ Xác thực email trước khi trả hàng
- ✅ CSRF Protection
- ✅ Validation file upload
- ✅ Kiểm tra quyền truy cập đơn hàng

## 📊 Trạng thái yêu cầu trả hàng

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

## 🛠️ Cấu trúc database

### **Bảng `order_returns`**:
```sql
- order_id: ID đơn hàng
- type: 'return' (trả hàng)
- reason: Lý do trả hàng
- client_note: Ghi chú từ khách hàng
- status: 'pending' | 'approved' | 'rejected'
- requested_at: Thời gian yêu cầu
- processed_at: Thời gian xử lý
- admin_note: Ghi chú từ admin
- images: Ảnh minh chứng từ client (JSON)
- video: Video minh chứng từ client
- admin_proof_images: Ảnh minh chứng từ admin (JSON)
- selected_products: Sản phẩm được chọn để trả (JSON)
```

## 🚀 API Endpoint

```
POST /invoice/order/{id}/request-return
```

### **Parameters**:
```json
{
    "selected_products": "[1,2,3]", // JSON string của ID sản phẩm
    "return_reason": "Sản phẩm bị lỗi",
    "product_images[]": [File1, File2, ...], // Multiple image files
    "return_video": File, // Single video file
    "client_note": "Lý do + Ghi chú thêm"
}
```

### **Response**:
```json
{
    "success": true,
    "message": "Yêu cầu trả hàng đã được gửi thành công. Chúng tôi sẽ xem xét và phản hồi trong thời gian sớm nhất."
}
```

## 🔧 Troubleshooting

### **Lỗi thường gặp**:

1. **"Column not found 'selected_products'"**:
   - Chạy `php artisan migrate`
   - Kiểm tra migration status

2. **"Không tìm thấy sản phẩm đã chọn"**:
   - Kiểm tra relationship giữa Order và OrderItems
   - Đảm bảo order có orderItems

3. **File upload không hoạt động**:
   - Kiểm tra storage link: `php artisan storage:link`
   - Kiểm tra quyền thư mục storage

4. **Modal không hiển thị**:
   - Kiểm tra JavaScript console
   - Đảm bảo đơn hàng ở trạng thái `delivered`

## 📝 Ghi chú quan trọng

- **Chức năng này chỉ dành cho khách vãng lai** (không đăng nhập)
- **Admin bắt buộc phải xem xét minh chứng** trước khi xử lý yêu cầu
- **Minh chứng từ client và admin** đều được lưu trữ và hiển thị
- **Quá trình trả hàng** được theo dõi đầy đủ từ yêu cầu đến hoàn thành
- **Validation nghiêm ngặt** để đảm bảo chất lượng yêu cầu trả hàng

## ✅ Kiểm tra sau khi sửa

1. **Database**: Cột `selected_products` đã được thêm
2. **Form**: Modal trả hàng hiển thị đúng sản phẩm
3. **Validation**: Tất cả validation hoạt động đúng
4. **Upload**: File upload hoạt động bình thường
5. **API**: Endpoint trả về response đúng

Chức năng trả hàng đã được sửa hoàn toàn và sẵn sàng sử dụng!
