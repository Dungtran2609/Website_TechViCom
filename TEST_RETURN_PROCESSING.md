# Test Quy Trình Xử Lý Yêu Cầu Trả Hàng

## 🔍 **Vấn Đề Đã Sửa:**

### **Trước đây:**
- Nút "Chấp nhận" không có validation
- Form submit trực tiếp mà không kiểm tra các trường bắt buộc
- Không có thông báo lỗi rõ ràng cho từng trường

### **Bây giờ:**
- ✅ Thêm `onsubmit="return validateAndSubmit({{ $return['id'] }})"` vào form
- ✅ Thêm `onclick="return validateAndSubmit({{ $return['id'] }})"` vào nút "Chấp nhận"
- ✅ Thêm `required` attribute cho các trường bắt buộc
- ✅ Hiển thị lỗi rõ ràng cho từng trường
- ✅ Validation đầy đủ trước khi submit

## 📋 **Các Trường Bắt Buộc:**

1. **Ghi chú của Admin** (`admin_note`) - Bắt buộc
2. **Checkbox xác nhận** (`confirm_proof_viewed`) - Bắt buộc
3. **Ảnh chứng minh hoàn tiền** (`admin_proof_images[]`) - Bắt buộc khi chấp nhận trả hàng

## 🧪 **Cách Test:**

### **Test 1: Không điền gì cả**
1. Mở modal "Xử lý yêu cầu trả hàng"
2. Ấn nút "Chấp nhận"
3. **Kết quả mong đợi:** Hiển thị lỗi cho tất cả trường bắt buộc

### **Test 2: Chỉ điền ghi chú**
1. Nhập ghi chú vào textarea
2. Ấn nút "Chấp nhận"
3. **Kết quả mong đợi:** Hiển thị lỗi cho checkbox và upload ảnh

### **Test 3: Điền ghi chú + tích checkbox**
1. Nhập ghi chú
2. Tích checkbox "Tôi đã xem xét kỹ lưỡng..."
3. Ấn nút "Chấp nhận"
4. **Kết quả mong đợi:** Hiển thị lỗi cho upload ảnh

### **Test 4: Điền đầy đủ nhưng không upload ảnh**
1. Nhập ghi chú
2. Tích checkbox
3. Ấn nút "Chấp nhận"
4. **Kết quả mong đợi:** Hiển thị lỗi "Vui lòng upload ảnh chứng minh đã hoàn tiền!"

### **Test 5: Điền đầy đủ tất cả**
1. Nhập ghi chú
2. Tích checkbox
3. Upload ảnh chứng minh
4. Ấn nút "Chấp nhận"
5. **Kết quả mong đợi:** Hiện confirm dialog → Submit form thành công

## 🔧 **Các Thay Đổi Đã Thực Hiện:**

### **1. Form Validation:**
```html
<form onsubmit="return validateAndSubmit({{ $return['id'] }})">
```

### **2. Nút Chấp Nhận:**
```html
<button onclick="return validateAndSubmit({{ $return['id'] }})">
```

### **3. Các Trường Bắt Buộc:**
```html
<textarea required>
<input type="checkbox" required>
<input type="file" required>
```

### **4. Hiển Thị Lỗi:**
```javascript
// Hiển thị lỗi cho từng trường
textarea.classList.add('is-invalid');
errorDiv.style.display = 'block';
```

## 📱 **Giao Diện Người Dùng:**

### **Trước khi validation:**
- Các trường bắt buộc có dấu `*` màu đỏ
- Có thông báo "Lưu ý quan trọng" ở đầu modal

### **Khi có lỗi:**
- Trường lỗi có viền đỏ (`is-invalid`)
- Hiển thị thông báo lỗi cụ thể bên dưới
- Focus vào trường đầu tiên có lỗi

### **Khi validation thành công:**
- Hiện confirm dialog: "Bạn có chắc chắn muốn chấp nhận yêu cầu này?"
- Submit form và chuyển hướng về trang danh sách

## 🚀 **Kết Quả Mong Đợi:**

Sau khi sửa, khi admin ấn "Chấp nhận trả hàng":

1. **Nếu thiếu thông tin:** Hiển thị lỗi rõ ràng cho từng trường
2. **Nếu đầy đủ:** Hiện confirm dialog → Submit thành công
3. **Trạng thái đơn hàng:** Chuyển từ `delivered` → `returned`
4. **Trạng thái yêu cầu:** Chuyển từ `pending` → `approved`
5. **Tồn kho:** Được cộng lại
6. **Thông báo:** "Yêu cầu đã được phê duyệt."

## ⚠️ **Lưu Ý:**

- **Chỉ áp dụng cho yêu cầu trả hàng** (`type === 'return'`)
- **Yêu cầu hủy đơn** không cần upload ảnh chứng minh
- **Validation chạy cả ở client (JavaScript) và server (PHP)**
- **Có thể tắt JavaScript validation bằng cách xóa `onsubmit` và `onclick`**
