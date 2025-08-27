# Chặn Admin Không Cho Mua Hàng

## 🎯 **Mục Tiêu:**

1. **Admin không thể mua hàng** - Chặn hoàn toàn quyền mua hàng
2. **Nút "Mua ngay" hoạt động bình thường** - Cho phép khách hàng mua hàng bình thường

## ✅ **Những Gì Đã Thực Hiện:**

### **1. Checkout Controller - Chặn Admin:**
- ✅ `index()` - Chặn truy cập trang checkout
- ✅ `process()` - Chặn xử lý thanh toán
- ✅ `applyCoupon()` - Chặn áp dụng mã giảm giá
- ✅ `vnpay_payment()` - Chặn thanh toán VNPay
- ✅ `vnpay_return()` - Chặn xử lý VNPay return
- ✅ `success()` - Chặn xem trang thành công
- ✅ `fail()` - Chặn xem trang thất bại

### **2. Cart Controller - Chặn Admin:**
- ✅ `index()` - Chặn xem giỏ hàng
- ✅ `add()` - Chặn thêm vào giỏ hàng
- ✅ `setBuyNow()` - Chặn mua ngay
- ✅ `update()` - Chặn cập nhật giỏ hàng
- ✅ `remove()` - Chặn xóa khỏi giỏ hàng
- ✅ `clear()` - Chặn xóa toàn bộ giỏ hàng
- ✅ `count()` - Chặn đếm giỏ hàng

## 🔒 **Logic Chặn Admin:**

### **Kiểm Tra Vai Trò:**
```php
// Chặn admin không cho mua hàng
if (Auth::check() && Auth::user()->hasRole('admin')) {
    return redirect()->route('home')->with('error', 'Admin không thể mua hàng. Vui lòng sử dụng tài khoản khách hàng.');
}
```

### **Kiểm Tra Nhiều Vai Trò (Cart Controller):**
```php
// Chặn admin và staff không được mua hàng
if (Auth::check()) {
    $user = Auth::user();
    $userRoles = $user->roles->pluck('name')->toArray();
    $blockedRoles = ['admin', 'staff', 'employee', 'manager'];
    
    if (array_intersect($userRoles, $blockedRoles)) {
        return response()->json([
            'success' => false,
            'message' => 'Tài khoản Admin/Staff không được phép mua hàng!'
        ], 403);
    }
}
```

## 🚫 **Các Vai Trò Bị Chặn:**

1. **`admin`** - Quản trị viên chính
2. **`staff`** - Nhân viên
3. **`employee`** - Nhân viên
4. **`manager`** - Quản lý

## ✅ **Các Vai Trò Được Phép:**

1. **`customer`** - Khách hàng
2. **`user`** - Người dùng thường
3. **Khách vãng lai** - Không đăng nhập

## 📱 **Cách Hoạt Động:**

### **Khi Admin Truy Cập:**
1. **Trang checkout** → Redirect về home với thông báo lỗi
2. **Thêm vào giỏ hàng** → JSON response lỗi 403
3. **Mua ngay** → JSON response lỗi 403
4. **Thanh toán** → Redirect về home với thông báo lỗi

### **Khi Khách Hàng Truy Cập:**
1. **Trang checkout** → Hiển thị bình thường
2. **Thêm vào giỏ hàng** → Hoạt động bình thường
3. **Mua ngay** → Hoạt động bình thường
4. **Thanh toán** → Xử lý bình thường

## 🔍 **Thông Báo Lỗi:**

### **Checkout Controller:**
```
"Admin không thể mua hàng. Vui lòng sử dụng tài khoản khách hàng."
```

### **Cart Controller:**
```json
{
    "success": false,
    "message": "Tài khoản Admin/Staff không được phép mua hàng!"
}
```

## 🧪 **Cách Test:**

### **Test 1: Admin Truy Cập Checkout**
1. Đăng nhập với tài khoản admin
2. Truy cập `/checkout`
3. **Kết quả mong đợi:** Redirect về home với thông báo lỗi

### **Test 2: Admin Thêm Vào Giỏ Hàng**
1. Đăng nhập với tài khoản admin
2. Click "Thêm vào giỏ hàng"
3. **Kết quả mong đợi:** JSON response lỗi 403

### **Test 3: Admin Mua Ngay**
1. Đăng nhập với tài khoản admin
2. Click "Mua ngay"
3. **Kết quả mong đợi:** JSON response lỗi 403

### **Test 4: Khách Hàng Mua Bình Thường**
1. Đăng nhập với tài khoản khách hàng
2. Thực hiện các thao tác mua hàng
3. **Kết quả mong đợi:** Hoạt động bình thường

### **Test 5: Khách Vãng Lai Mua Bình Thường**
1. Không đăng nhập
2. Thực hiện các thao tác mua hàng
3. **Kết quả mong đợi:** Hoạt động bình thường

## ⚠️ **Lưu Ý Quan Trọng:**

1. **Chỉ chặn mua hàng** - Admin vẫn có thể truy cập admin panel
2. **Không ảnh hưởng đến quản lý** - Admin vẫn quản lý sản phẩm, đơn hàng
3. **Bảo mật cao** - Kiểm tra cả ở controller và middleware
4. **Thông báo rõ ràng** - Giải thích lý do bị chặn

## 🔧 **Cấu Trúc Code:**

### **Checkout Controller:**
```php
public function index(Request $request)
{
    // Chặn admin không cho mua hàng
    if (Auth::check() && Auth::user()->hasRole('admin')) {
        return redirect()->route('home')->with('error', 'Admin không thể mua hàng. Vui lòng sử dụng tài khoản khách hàng.');
    }
    
    // ... logic checkout bình thường
}
```

### **Cart Controller:**
```php
public function add(Request $request)
{
    // Chặn admin và staff không được thêm vào giỏ hàng
    if (Auth::check()) {
        $user = Auth::user();
        $userRoles = $user->roles->pluck('name')->toArray();
        $blockedRoles = ['admin', 'staff', 'employee', 'manager'];
        
        if (array_intersect($userRoles, $blockedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản Admin/Staff không được phép mua hàng!'
            ], 403);
        }
    }
    
    // ... logic thêm vào giỏ hàng bình thường
}
```

## 🚀 **Kết Quả Cuối Cùng:**

Sau khi thực hiện:

1. **Admin hoàn toàn không thể mua hàng** - Bị chặn ở mọi bước
2. **Khách hàng mua hàng bình thường** - Không bị ảnh hưởng
3. **Nút "Mua ngay" hoạt động tốt** - Cho phép mua hàng nhanh
4. **Bảo mật cao** - Kiểm tra vai trò ở mọi controller
5. **Thông báo rõ ràng** - Giải thích lý do bị chặn

Bây giờ admin sẽ không thể mua hàng, nhưng khách hàng vẫn có thể sử dụng tất cả chức năng mua hàng bình thường! 🎉
