# Test Chức Năng Giới Hạn Số Lượng Tồn Kho

## Các thay đổi đã thực hiện:

### 1. Backend (ClientCartController.php)
- ✅ Thêm logic kiểm tra số lượng tồn kho trong method `update()`
- ✅ Kiểm tra cho cả user đã đăng nhập và session cart
- ✅ Trả về thông báo lỗi và số lượng tối đa khi vượt quá tồn kho

### 2. Frontend - Trang Giỏ Hàng (carts/index.blade.php)
- ✅ Cập nhật JavaScript để xử lý response từ server
- ✅ Tự động cập nhật số lượng về giá trị tối đa khi vượt quá
- ✅ Thêm data attributes cho nút tăng số lượng
- ✅ Vô hiệu hóa nút tăng khi đã đạt giới hạn tồn kho
- ✅ Hiển thị tooltip thông báo số lượng còn lại

### 3. Frontend - Trang Sản Phẩm Chi Tiết (products/show.blade.php)
- ✅ Cập nhật function `updateQuantity()` để kiểm tra giới hạn
- ✅ Hiển thị thông báo lỗi khi vượt quá tồn kho
- ✅ Vô hiệu hóa nút tăng/giảm khi đã đạt giới hạn
- ✅ Cập nhật trạng thái nút theo thời gian thực

## Cách test:

### Test 1: Trang Sản Phẩm Chi Tiết
1. Vào trang sản phẩm có số lượng tồn kho thấp (ví dụ: 5 sản phẩm)
2. Thử tăng số lượng lên 6, 7, 8... 
3. Kiểm tra:
   - Hiển thị thông báo "Chỉ còn X sản phẩm trong kho!"
   - Nút tăng bị vô hiệu hóa khi đạt giới hạn
   - Tooltip hiển thị thông tin tồn kho

### Test 2: Trang Giỏ Hàng
1. Thêm sản phẩm vào giỏ hàng
2. Thử tăng số lượng vượt quá tồn kho
3. Kiểm tra:
   - Hiển thị thông báo lỗi từ server
   - Số lượng tự động cập nhật về giá trị tối đa
   - Nút tăng bị vô hiệu hóa
   - Tổng tiền được cập nhật chính xác

### Test 3: API Test
1. Gửi request PUT đến `/client/carts/{id}` với số lượng vượt quá tồn kho
2. Kiểm tra response:
   ```json
   {
     "success": false,
     "message": "Số lượng vượt quá tồn kho! Chỉ còn X sản phẩm.",
     "max_quantity": X
   }
   ```

## Lưu ý:
- Logic hoạt động cho cả sản phẩm đơn giản và sản phẩm có biến thể
- Kiểm tra tồn kho được thực hiện ở cả frontend và backend
- Thông báo lỗi rõ ràng và thân thiện với người dùng
- UI được cập nhật theo thời gian thực
