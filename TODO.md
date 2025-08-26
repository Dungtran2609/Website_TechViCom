# TODO List

## ✅ Completed Tasks

### Chức năng hủy đơn hàng cho khách vãng lai
- [x] Thêm nút hủy đơn hàng trong invoice-detail.blade.php
- [x] Tạo modal hủy đơn hàng với form nhập lý do
- [x] Thêm JavaScript xử lý chức năng hủy đơn hàng
- [x] Thêm route POST /invoice/order/{id}/cancel
- [x] Cập nhật logic cancelOrder trong InvoiceController theo yêu cầu

### Sửa lỗi hệ thống
- [x] Sửa lỗi Internal Server Error do truy cập user->name null trong show.blade.php
- [x] Sửa lỗi 'Giỏ hàng trống' khi có sản phẩm trong checkout
- [x] Thêm debug log để kiểm tra session cart trong checkout
- [x] Thêm logic kiểm tra và xử lý session cart lần cuối trước khi redirect

## 🔄 In Progress Tasks

### Kiểm tra và test sau khi sửa lỗi
- [ ] Test chức năng checkout với khách vãng lai để xác nhận cart không bị xóa
- [ ] Test chức năng "Mua ngay" với khách hàng đã đăng nhập để xác nhận chỉ lấy sản phẩm được chọn
- [ ] Test chức năng checkout với user đã đăng nhập để xác nhận cart items được hiển thị đúng
- [ ] Test chức năng checkout với user đã đăng nhập để xác nhận chuyển đến trang success thay vì cart
- [ ] Kiểm tra log để xác nhận session cart và database cart được xử lý đúng cách
- [ ] Test các trường hợp edge case (variant, no variant, etc.) cho cả hai chức năng
- [ ] Test tùy chọn clear_cart khi khách hàng muốn xóa cart

## 📋 Pending Tasks

### Kiểm tra và test
- [ ] Test chức năng checkout với khách vãng lai
- [ ] Kiểm tra log để xác nhận session cart được xử lý đúng
- [ ] Test các trường hợp edge case (variant, no variant, etc.)

## 🎯 Next Steps

1. Test checkout với khách vãng lai để xác nhận lỗi đã được sửa
2. Kiểm tra log để đảm bảo session cart được xử lý đúng cách
3. Nếu cần, thêm unit test cho logic xử lý cart

## 🔧 Recent Fixes Applied

### Lỗi giỏ hàng trống khách vãng lai
- **Vấn đề**: Logic xử lý session cart không đúng với format key `productId_variantId`
- **Giải pháp**: 
  - Thêm logic xử lý toàn bộ session cart khi không có selectedParam
  - Cải thiện debug logging để dễ dàng troubleshoot
  - Sửa logic xử lý selectedParam để tương thích với format key
- **Trạng thái**: Đã sửa, cần test để xác nhận

### Ngăn chặn xác nhận nhận hàng khi đã yêu cầu trả hàng
- **Vấn đề**: Khi đã yêu cầu trả hàng, vẫn có thể xác nhận nhận hàng
- **Giải pháp**:
  - Thêm điều kiện kiểm tra returns status cho nút "Xác nhận nhận hàng"
  - Chỉ hiển thị nút khi chưa có yêu cầu trả hàng đang xử lý
- **Trạng thái**: Đã sửa

### Sửa hiển thị tên khách hàng trong admin returns
- **Vấn đề**: Admin panel hiển thị "Khách vãng lai" thay vì tên thực tế
- **Giải pháp**:
  - Sửa logic trong AdminOrderController để ưu tiên `guest_name` trước
  - Hiển thị tên khách vãng lai từ database thay vì hardcode
- **Trạng thái**: Đã sửa
