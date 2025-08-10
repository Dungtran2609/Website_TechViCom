# Hệ thống Comment Sản phẩm

## Tổng quan
Hệ thống comment cho phép user đã mua sản phẩm có thể đánh giá và bình luận về sản phẩm đó. Tất cả comment đều cần được admin duyệt trước khi hiển thị.

## Tính năng chính

### 1. Điều kiện để comment
- User phải đăng nhập
- User phải đã mua sản phẩm (đơn hàng có status 'delivered' hoặc 'shipped')
- User chỉ được comment 1 lần cho mỗi sản phẩm
- User chỉ được reply 1 lần cho mỗi comment

### 2. Quy trình comment
1. User mua sản phẩm
2. Đơn hàng được giao thành công (status = 'delivered' hoặc 'shipped')
3. User có thể comment với rating và nội dung
4. Comment được gửi với status 'pending'
5. Admin duyệt comment trong admin panel
6. Comment được hiển thị trên trang sản phẩm

### 3. Cấu trúc dữ liệu

#### Bảng `product_comments`
- `id`: Primary key
- `product_id`: ID sản phẩm
- `user_id`: ID user
- `content`: Nội dung comment
- `rating`: Đánh giá (1-5 sao, nullable)
- `status`: Trạng thái (pending, approved, deleted)
- `parent_id`: ID comment cha (cho reply, nullable)
- `created_at`, `updated_at`: Timestamps
- `deleted_at`: Soft delete

## Files đã tạo/cập nhật

### Controllers
- `app/Http/Controllers/Client/Products/ClientProductCommentController.php` - Controller xử lý comment của client

### Requests
- `app/Http/Requests/Client/StoreProductCommentRequest.php` - Validation cho tạo comment
- `app/Http/Requests/Client/ReplyProductCommentRequest.php` - Validation cho reply comment

### Helpers
- `app/Helpers/CommentHelper.php` - Helper class kiểm tra quyền comment

### Views
- `resources/views/client/products/show.blade.php` - Cập nhật view hiển thị comment

### Routes
- Thêm routes trong `routes/web.php`:
  - `POST /products/{productId}/comments` - Tạo comment
  - `POST /products/{productId}/comments/{commentId}/reply` - Reply comment

## Cách sử dụng

### 1. Kiểm tra quyền comment
```php
use App\Helpers\CommentHelper;

// Kiểm tra user có thể comment không
$canComment = CommentHelper::canComment($productId);

// Lấy thông báo lý do không thể comment
$message = CommentHelper::getCommentRestrictionMessage($productId);
```

### 2. Tạo comment
```php
// Trong controller
ProductComment::create([
    'product_id' => $productId,
    'user_id' => $user->id,
    'content' => $request->content,
    'rating' => $request->rating,
    'status' => 'pending',
]);
```

### 3. Hiển thị comment trong view
```php
// Lấy comment đã được duyệt
$approvedComments = $product->productComments()
    ->where('status', 'approved')
    ->whereNull('parent_id')
    ->with(['user', 'replies.user'])
    ->orderBy('created_at', 'desc')
    ->get();
```

## Admin Panel

### Quản lý comment
- Truy cập: `/admin/product-comments`
- Chức năng:
  - Xem danh sách comment
  - Duyệt/từ chối comment
  - Xóa comment
  - Reply comment
  - Lọc theo sản phẩm, user, status

### Quản lý sản phẩm có comment
- Truy cập: `/admin/product-comments/products-with-comments`
- Hiển thị danh sách sản phẩm có comment

## Validation Rules

### Tạo comment
- `content`: required, string, min:10, max:500
- `rating`: required, integer, min:1, max:5

### Reply comment
- `reply_content`: required, string, min:5, max:200

## Status của comment
- `pending`: Chờ admin duyệt
- `approved`: Đã được duyệt, hiển thị trên website
- `deleted`: Đã bị xóa

## Lưu ý quan trọng

1. **Bảo mật**: Tất cả comment đều cần được admin duyệt trước khi hiển thị
2. **Performance**: Sử dụng eager loading để tránh N+1 query
3. **UX**: Hiển thị thông báo rõ ràng khi user không thể comment
4. **Validation**: Kiểm tra chặt chẽ điều kiện trước khi cho phép comment

## Test

Chạy file `test_comment_system.php` để kiểm tra chức năng:
```bash
php test_comment_system.php
```

## Troubleshooting

### Lỗi thường gặp
1. **Comment không hiển thị**: Kiểm tra status có phải 'approved' không
2. **Không thể comment**: Kiểm tra user đã mua sản phẩm chưa
3. **Lỗi validation**: Kiểm tra độ dài content và rating

### Debug
- Sử dụng `CommentHelper::getCommentRestrictionMessage()` để debug lý do không thể comment
- Kiểm tra logs trong `storage/logs/laravel.log` 