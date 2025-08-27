# Sửa lỗi TypeError: json_decode() Argument #1 must be of type string, array given

## 🚨 Lỗi đã gặp phải

### **Lỗi chính**:
```
TypeError: json_decode(): Argument #1 ($json) must be of type string, array given
```

### **Vị trí lỗi**:
- **File**: `resources/views/client/pages/invoice-detail.blade.php`
- **Dòng**: 254 (và các dòng khác)
- **Nguyên nhân**: Gọi `json_decode()` trên dữ liệu đã là array

## 🔍 Nguyên nhân gốc rễ

### **Vấn đề**:
Trong model `OrderReturn`, các trường sau đã được cast thành array:
```php
protected $casts = [
    'images' => 'array',
    'admin_proof_images' => 'array',
    'selected_products' => 'array',
];
```

### **Kết quả**:
- Khi truy cập `$returnRequest->images`, Laravel tự động trả về **array**
- Không cần gọi `json_decode()` nữa
- Gọi `json_decode()` trên array sẽ gây ra lỗi TypeError

## ✅ Những gì đã được sửa

### 1. **Sửa hiển thị minh chứng từ client**:
```blade
{{-- TRƯỚC (SAI) --}}
@foreach(json_decode($returnRequest->images, true) as $productId => $images)

{{-- SAU (ĐÚNG) --}}
@foreach($returnRequest->images as $productId => $images)
```

### 2. **Sửa hiển thị minh chứng từ admin**:
```blade
{{-- TRƯỚC (SAI) --}}
@foreach(json_decode($returnRequest->admin_proof_images, true) as $image)

{{-- SAU (ĐÚNG) --}}
@foreach($returnRequest->admin_proof_images as $image)
```

### 3. **Các vị trí đã sửa**:
- ✅ Dòng 194: `$cancelReturn->images`
- ✅ Dòng 220: `$cancelReturn->admin_proof_images`
- ✅ Dòng 253: `$returnReturn->images`
- ✅ Dòng 279: `$returnReturn->admin_proof_images`
- ✅ Dòng 355: `$returnRequest->images` (pending)
- ✅ Dòng 393: `$returnRequest->images` (approved)
- ✅ Dòng 425: `$returnRequest->admin_proof_images`
- ✅ Dòng 449: `$returnRequest->images` (rejected)

## 🛠️ Cách hoạt động của Laravel Casts

### **Khi không có cast**:
```php
// Database: JSON string
'images' => '{"product_1": ["img1.jpg", "img2.jpg"]}'

// PHP: String
$returnRequest->images // "{\"product_1\": [\"img1.jpg\", \"img2.jpg\"]}"

// Cần: json_decode()
json_decode($returnRequest->images, true) // Array
```

### **Khi có cast 'array'**:
```php
// Database: JSON string
'images' => '{"product_1": ["img1.jpg", "img2.jpg"]}'

// PHP: Array (tự động)
$returnRequest->images // ['product_1' => ['img1.jpg', 'img2.jpg']]

// Không cần: json_decode()
// json_decode($returnRequest->images, true) // LỖI!
```

## 🔧 Cấu trúc dữ liệu sau khi sửa

### **Minh chứng từ client**:
```php
$returnRequest->images = [
    'product_id_1' => ['image1.jpg', 'image2.jpg'],
    'product_id_2' => ['image3.jpg']
];

// Hiển thị:
@foreach($returnRequest->images as $productId => $images)
    @if(is_array($images))
        @foreach($images as $image)
            <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng">
        @endforeach
    @endif
@endforeach
```

### **Minh chứng từ admin**:
```php
$returnRequest->admin_proof_images = [
    'admin_proof_1.jpg',
    'admin_proof_2.jpg'
];

// Hiển thị:
@foreach($returnRequest->admin_proof_images as $image)
    <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng admin">
@endforeach
```

## 📊 So sánh trước và sau khi sửa

### **TRƯỚC (CÓ LỖI)**:
```blade
@if($returnRequest->images)
    <div class="mt-3">
        <p><strong>Minh chứng của bạn:</strong></p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            @foreach(json_decode($returnRequest->images, true) as $productId => $images)
                @if(is_array($images))
                    @foreach($images as $image)
                        <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng">
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
@endif
```

### **SAU (ĐÃ SỬA)**:
```blade
@if($returnRequest->images)
    <div class="mt-3">
        <p><strong>Minh chứng của bạn:</strong></p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            @foreach($returnRequest->images as $productId => $images)
                @if(is_array($images))
                    @foreach($images as $image)
                        <img src="{{ asset('storage/' . $image) }}" alt="Minh chứng">
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
@endif
```

## 🎯 Lợi ích sau khi sửa

### **1. Không còn lỗi TypeError**:
- ✅ Dữ liệu được xử lý đúng kiểu
- ✅ Không cần gọi `json_decode()` không cần thiết
- ✅ Code chạy mượt mà

### **2. Hiệu suất tốt hơn**:
- ✅ Không cần parse JSON mỗi lần hiển thị
- ✅ Laravel tự động cast dữ liệu
- ✅ Giảm overhead xử lý

### **3. Code sạch hơn**:
- ✅ Không cần kiểm tra kiểu dữ liệu
- ✅ Logic đơn giản và rõ ràng
- ✅ Dễ maintain và debug

## 🔍 Kiểm tra sau khi sửa

### **1. Kiểm tra database**:
```sql
-- Kiểm tra cấu trúc bảng order_returns
DESCRIBE order_returns;

-- Kiểm tra dữ liệu mẫu
SELECT images, admin_proof_images FROM order_returns LIMIT 1;
```

### **2. Kiểm tra model**:
```php
// Kiểm tra casts trong OrderReturn model
protected $casts = [
    'images' => 'array',
    'admin_proof_images' => 'array',
    'selected_products' => 'array',
];
```

### **3. Kiểm tra view**:
- ✅ Không còn lỗi TypeError
- ✅ Minh chứng hiển thị đúng
- ✅ Layout responsive hoạt động tốt

## 📝 Ghi chú quan trọng

### **Khi nào cần json_decode()**:
- Khi dữ liệu từ database là JSON string
- Khi không có cast trong model
- Khi cần parse JSON từ API response

### **Khi nào KHÔNG cần json_decode()**:
- Khi đã có cast 'array' trong model
- Khi Laravel tự động cast dữ liệu
- Khi dữ liệu đã là array

### **Best Practices**:
- ✅ Luôn sử dụng casts trong model khi có thể
- ✅ Không gọi `json_decode()` trên dữ liệu đã cast
- ✅ Kiểm tra kiểu dữ liệu trước khi xử lý

## ✅ Kết quả cuối cùng

Sau khi sửa:
1. **Không còn lỗi TypeError**
2. **Minh chứng hiển thị đúng** từ cả client và admin
3. **Code chạy mượt mà** và hiệu suất tốt hơn
4. **Dễ maintain** và debug

Bây giờ trang chi tiết đơn hàng sẽ hiển thị đầy đủ minh chứng mà không gặp lỗi! 🎉
