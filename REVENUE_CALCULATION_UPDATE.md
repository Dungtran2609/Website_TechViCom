# Cập Nhật Logic Tính Doanh Thu

## 🔄 **Thay Đổi Logic Tính Doanh Thu:**

### **Trước đây:**
- Chỉ tính doanh thu từ đơn hàng có trạng thái `received` (đã nhận hàng)
- Không tính đơn hàng `delivered` (đã giao)
- Không trừ tiền từ đơn hàng `returned` (đã trả hàng)

### **Bây giờ:**
- ✅ **Cộng doanh thu** từ đơn hàng `delivered` (đã giao) + `received` (đã nhận)
- ✅ **Trừ tiền** từ đơn hàng `returned` (đã trả hàng)
- ✅ **Công thức:** `Doanh thu = (Đã giao + Đã nhận) - Đã trả hàng`

## 📊 **Các Thay Đổi Đã Thực Hiện:**

### **1. AdminController.php - Dashboard Method:**
```php
// Trước đây:
$totalRevenue = Order::where('status', 'received')
                    ->where(function($query) {
                        $query->where('payment_status', 'paid')
                              ->orWhere('payment_method', 'cod');
                    })
                    ->sum('final_total');

// Bây giờ:
$deliveredRevenue = Order::whereIn('status', ['delivered', 'received'])
                        ->where(function($query) {
                            $query->where('payment_status', 'paid')
                                  ->orWhere('payment_method', 'cod');
                        })
                        ->sum('final_total');

$returnedRevenue = Order::where('status', 'returned')
                       ->where(function($query) {
                           $query->where('payment_status', 'paid')
                                 ->orWhere('payment_method', 'cod');
                       })
                       ->sum('final_total');

$totalRevenue = $deliveredRevenue - $returnedRevenue;
```

### **2. AdminController.php - Revenue Last Week:**
```php
// Trước đây:
$revenue = Order::whereDate('created_at', $date)
               ->where('status', 'received')
               ->where(function($query) {
                   $query->where('payment_status', 'paid')
                         ->orWhere('payment_method', 'cod');
               })
               ->sum('final_total');

// Bây giờ:
$deliveredRevenue = Order::whereDate('created_at', $date)
                       ->whereIn('status', ['delivered', 'received'])
                       ->where(function($query) {
                           $query->where('payment_status', 'paid')
                                 ->orWhere('payment_method', 'cod');
                       })
                       ->sum('final_total');

$returnedRevenue = Order::whereDate('created_at', $date)
                       ->where('status', 'returned')
                       ->where(function($query) {
                           $query->where('payment_status', 'paid')
                                 ->orWhere('payment_method', 'cod');
                       })
                       ->sum('final_total');

$revenue = $deliveredRevenue - $returnedRevenue;
```

### **3. AdminController.php - Helper Methods:**
```php
// getRevenueForDate() - Cập nhật tương tự
// getRevenueForDateRange() - Cập nhật tương tự
```

### **4. Dashboard View - Cập Nhật Text:**
```html
<!-- Trước đây: -->
<small class="text-success">
    <i class="fas fa-chart-line me-1"></i>
    khi Khách đã nhận hàng
</small>

<!-- Bây giờ: -->
<small class="text-success">
    <i class="fas fa-chart-line me-1"></i>
    Đã giao + Đã nhận - Đã trả hàng
</small>
```

### **5. Chart Titles - Cập Nhật:**
```javascript
// Trước đây:
title = 'Doanh thu 7 ngày gần đây';

// Bây giờ:
title = 'Doanh thu 7 ngày gần đây (Đã giao + Đã nhận - Đã trả hàng)';
```

## 🎯 **Logic Mới Hoạt Động Như Thế Nào:**

### **Khi Đơn Hàng Được Giao (`delivered`):**
1. **Trạng thái:** `pending` → `processing` → `shipped` → `delivered`
2. **Doanh thu:** ✅ **Được cộng vào** ngay khi chuyển sang `delivered`
3. **Lý do:** Khách đã nhận hàng, có thể thanh toán (COD) hoặc đã thanh toán online

### **Khi Đơn Hàng Được Nhận (`received`):**
1. **Trạng thái:** `delivered` → `received`
2. **Doanh thu:** ✅ **Vẫn được tính** (không thay đổi)
3. **Lý do:** Khách xác nhận đã nhận hàng

### **Khi Đơn Hàng Bị Trả (`returned`):**
1. **Trạng thái:** `delivered` → `returned`
2. **Doanh thu:** ❌ **Bị trừ đi** ngay khi chuyển sang `returned`
3. **Lý do:** Khách trả hàng, cần hoàn tiền

## 📈 **Ví Dụ Cụ Thể:**

### **Tình Huống 1: Đơn hàng COD 500,000đ**
- **Khi giao hàng:** Doanh thu +500,000đ
- **Khi khách nhận:** Doanh thu +500,000đ (không thay đổi)
- **Tổng:** +500,000đ

### **Tình Huống 2: Đơn hàng Online 300,000đ**
- **Khi giao hàng:** Doanh thu +300,000đ
- **Khi khách nhận:** Doanh thu +300,000đ (không thay đổi)
- **Tổng:** +300,000đ

### **Tình Huống 3: Đơn hàng bị trả 200,000đ**
- **Khi giao hàng:** Doanh thu +200,000đ
- **Khi trả hàng:** Doanh thu -200,000đ
- **Tổng:** 0đ (đã hoàn tiền)

### **Tổng Doanh Thu:**
```
(500,000 + 300,000 + 200,000) - 200,000 = 800,000đ
```

## 🔍 **Các Trường Hợp Đặc Biệt:**

### **Đơn Hàng Hủy (`cancelled`):**
- **Không được tính vào doanh thu** (vì chưa giao)
- **Không bị trừ tiền** (vì chưa thanh toán)

### **Đơn Hàng Đang Xử Lý (`pending`, `processing`, `shipped`):**
- **Không được tính vào doanh thu** (vì chưa giao)
- **Chỉ tính khi chuyển sang `delivered`**

### **Đơn Hàng Thanh Toán Online:**
- **Đã thanh toán:** Tính doanh thu khi `delivered`
- **Chưa thanh toán:** Không tính doanh thu

## ⚠️ **Lưu Ý Quan Trọng:**

1. **Chỉ tính đơn hàng đã thanh toán hoặc COD**
2. **Doanh thu được cập nhật real-time** khi trạng thái thay đổi
3. **Logic này áp dụng cho tất cả biểu đồ và thống kê**
4. **Không ảnh hưởng đến dữ liệu cũ** (chỉ thay đổi cách tính)

## 🧪 **Cách Test:**

### **Test 1: Tạo đơn hàng mới**
1. Tạo đơn hàng với trạng thái `pending`
2. Chuyển sang `delivered` → Kiểm tra doanh thu tăng
3. Chuyển sang `received` → Doanh thu không thay đổi

### **Test 2: Test trả hàng**
1. Có đơn hàng `delivered` → Doanh thu đã được tính
2. Chuyển sang `returned` → Doanh thu bị trừ đi
3. Kiểm tra tổng doanh thu giảm

### **Test 3: Kiểm tra biểu đồ**
1. Chuyển đổi giữa 7 ngày, 30 ngày, tháng
2. Kiểm tra title thay đổi đúng
3. Kiểm tra dữ liệu được tính theo logic mới

## 🚀 **Kết Quả Mong Đợi:**

Sau khi cập nhật:

1. **Doanh thu tăng nhanh hơn** (tính ngay khi giao hàng)
2. **Doanh thu giảm khi có trả hàng** (tự động trừ tiền)
3. **Thống kê chính xác hơn** (phản ánh thực tế kinh doanh)
4. **Giao diện rõ ràng hơn** (hiển thị logic tính toán)
