# Chức năng Spam Chặn VNPay cho Khách Vãng Lai

## Tổng quan
Đã triển khai thành công chức năng spam chặn VNPay cho khách vãng lai, tương tự như chức năng đã có cho khách đăng nhập.

## Tính năng chính

### 1. Theo dõi số lần hủy VNPay
- **Khách đăng nhập**: Lưu trữ trong database (bảng `orders` với cột `vnpay_cancel_count`)
- **Khách vãng lai**: Lưu trữ trong session với key `guest_vnpay_cancel_count`

### 2. Logic spam chặn
- Chặn VNPay khi hủy >= 3 lần trong vòng 24 giờ
- Tự động dọn dẹp dữ liệu cũ (hơn 24 giờ)
- Hiển thị thông báo rõ ràng cho người dùng

### 3. Các điểm kiểm tra
- **Trang checkout**: Ẩn/hiện phương thức VNPay
- **Khi xử lý thanh toán**: Kiểm tra trước khi tạo URL VNPay
- **Khi VNPay return**: Tăng counter khi bị hủy

## Files đã cập nhật

### 1. Controller
- `app/Http/Controllers/Client/Checkouts/ClientCheckoutController.php`
  - Thêm methods: `getGuestCancelCount()`, `incrementGuestCancelCount()`
  - Cập nhật logic kiểm tra spam trong các methods: `index()`, `process()`, `vnpay_payment()`, `vnpay_return()`

### 2. View
- `resources/views/client/checkouts/index.blade.php`
  - Thêm logic tính toán spam chặn cho khách vãng lai
  - Cập nhật hiển thị phương thức thanh toán VNPay

### 3. Test files
- `test_guest_vnpay_spam.php`: Script test logic session
- `VNPAY_SPAM_PROTECTION_README.md`: Tài liệu này

## Cách hoạt động

### 1. Lưu trữ dữ liệu
```php
// Session structure
'guest_vnpay_cancel_count' => [
    timestamp1 => count1,
    timestamp2 => count2,
    // ...
]
```

### 2. Tính toán số lần hủy
```php
private function getGuestCancelCount(): int
{
    $cancelData = session('guest_vnpay_cancel_count', []);
    $totalCount = 0;
    $currentTime = time();
    
    foreach ($cancelData as $timestamp => $count) {
        if ($currentTime - $timestamp < 86400) { // 24 giờ
            $totalCount += $count;
        }
    }
    
    return $totalCount;
}
```

### 3. Tăng counter khi hủy
```php
private function incrementGuestCancelCount(): int
{
    $cancelData = session('guest_vnpay_cancel_count', []);
    $currentTime = time();
    
    if (!isset($cancelData[$currentTime])) {
        $cancelData[$currentTime] = 0;
    }
    $cancelData[$currentTime]++;
    
    // Dọn dẹp dữ liệu cũ
    $cleanedData = [];
    foreach ($cancelData as $timestamp => $count) {
        if ($currentTime - $timestamp < 86400) {
            $cleanedData[$timestamp] = $count;
        }
    }
    
    session(['guest_vnpay_cancel_count' => $cleanedData]);
    return $this->getGuestCancelCount();
}
```

## Thông báo cho người dùng

### 1. Khi hủy lần thứ 2
```
"Bạn đã hủy VNPay 2 lần. Vui lòng đổi phương thức khác để hoàn thành, không đơn hàng sẽ bị hủy."
```

### 2. Khi bị chặn (>= 3 lần)
```
"Bạn đã hủy VNPay quá 3 lần. Vui lòng thử lại sau 24 giờ."
```

### 3. Trên giao diện checkout
```
"Phương thức này đã bị khóa do bạn đã hủy thanh toán 3 lần. Vui lòng thử lại sau 24 giờ."
```

## Logging

Hệ thống ghi log chi tiết cho việc debug:
- `Checking VNPay spam protection for guest`
- `Guest VNPay cancel count incremented`
- `VNPay blocked due to total spam for guest`

## Testing

### 1. Chạy script test
```bash
php test_guest_vnpay_spam.php
```

### 2. Test thực tế
1. Vào trang checkout với tư cách khách vãng lai
2. Chọn VNPay và hủy 3 lần
3. Kiểm tra VNPay bị ẩn/disabled
4. Đợi 24h hoặc xóa session để test lại

## Lưu ý

1. **Session-based**: Dữ liệu sẽ mất khi xóa session/cookie
2. **Time-based**: Chỉ tính trong 24 giờ gần nhất
3. **Auto-cleanup**: Tự động dọn dẹp dữ liệu cũ
4. **Compatible**: Tương thích với logic hiện tại cho user đăng nhập

## Kết luận

Chức năng spam chặn VNPay cho khách vãng lai đã được triển khai thành công với:
- ✅ Logic tương tự user đăng nhập
- ✅ Sử dụng session để lưu trữ
- ✅ Tự động dọn dẹp dữ liệu cũ
- ✅ Giao diện thân thiện
- ✅ Logging chi tiết
- ✅ Test script đầy đủ
