# Hiển thị Minh chứng từ Client và Admin

## 🎯 Mục tiêu
Hiển thị đầy đủ minh chứng từ cả client và admin trong phần "Lịch sử đơn hàng" để khách hàng có thể xem được tất cả thông tin liên quan đến yêu cầu hủy/trả hàng.

## ✅ Những gì đã được cải thiện

### 1. **Yêu cầu hủy đơn hàng đang chờ phê duyệt**
- ✅ Hiển thị lý do hủy hàng
- ✅ Hiển thị **minh chứng từ client** (ảnh và video)
- ✅ Layout responsive với grid 2-4 cột

### 2. **Yêu cầu trả hàng đang chờ phê duyệt**
- ✅ Hiển thị lý do trả hàng
- ✅ Hiển thị **minh chứng từ client** (ảnh và video)
- ✅ Layout responsive với grid 2-4 cột

### 3. **Yêu cầu trả hàng đã được chấp nhận**
- ✅ Hiển thị lý do trả hàng
- ✅ Hiển thị **minh chứng từ client** (ảnh và video)
- ✅ Hiển thị **phản hồi từ admin** (ghi chú)
- ✅ Hiển thị **minh chứng từ admin** (ảnh hoàn tiền)
- ✅ Layout responsive với grid 2-4 cột

### 4. **Yêu cầu trả hàng đã bị từ chối**
- ✅ Hiển thị lý do trả hàng
- ✅ Hiển thị **minh chứng từ client** (ảnh và video)
- ✅ Hiển thị **lý do từ chối từ admin** (ghi chú)
- ✅ Layout responsive với grid 2-4 cột

### 5. **Đơn hàng đã bị hủy**
- ✅ Hiển thị lý do hủy hàng
- ✅ Hiển thị **minh chứng từ client** (ảnh và video)
- ✅ Hiển thị **minh chứng từ admin** (nếu có)
- ✅ Layout responsive với grid 2-4 cột

### 6. **Đơn hàng đã được trả hàng**
- ✅ Hiển thị lý do trả hàng
- ✅ Hiển thị **minh chứng từ client** (ảnh và video)
- ✅ Hiển thị **minh chứng từ admin** (ảnh hoàn tiền)
- ✅ Layout responsive với grid 2-4 cột

## 🖼️ Cách hiển thị minh chứng

### **Minh chứng từ Client**:
```blade
{{-- Hiển thị ảnh minh chứng --}}
@if($returnRequest->images)
<div class="mt-3">
    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng của bạn:</strong></p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
        @foreach(json_decode($returnRequest->images, true) as $productId => $images)
            @if(is_array($images))
                @foreach($images as $image)
                <img src="{{ asset('storage/' . $image) }}" 
                     alt="Minh chứng client" 
                     class="w-16 h-16 object-cover rounded border cursor-pointer" 
                     onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng của bạn')">
                @endforeach
            @endif
        @endforeach
    </div>
</div>
@endif

{{-- Hiển thị video minh chứng --}}
@if($returnRequest->video)
<div class="mt-3">
    <p class="text-sm text-gray-600 mb-2"><strong>Video minh chứng:</strong></p>
    <video controls class="w-32 h-24 object-cover rounded border">
        <source src="{{ asset('storage/' . $returnRequest->video) }}" type="video/mp4">
        Trình duyệt không hỗ trợ video.
    </video>
</div>
@endif
```

### **Minh chứng từ Admin**:
```blade
{{-- Hiển thị ảnh minh chứng từ admin --}}
@if($returnRequest->admin_proof_images)
<div class="mt-3">
    <p class="text-sm text-gray-600 mb-2"><strong>Minh chứng từ admin:</strong></p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
        @foreach(json_decode($returnRequest->admin_proof_images, true) as $image)
        <img src="{{ asset('storage/' . $image) }}" 
             alt="Minh chứng admin" 
             class="w-16 h-16 object-cover rounded border cursor-pointer" 
             onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Minh chứng từ admin')">
        @endforeach
    </div>
</div>
@endif
```

## 🎨 Giao diện và UX

### **Layout Responsive**:
- **Mobile**: Grid 2 cột cho ảnh
- **Desktop**: Grid 4 cột cho ảnh
- **Spacing**: Gap 2 (8px) giữa các ảnh
- **Image size**: 64x64px (w-16 h-16)

### **Interactive Elements**:
- **Clickable images**: Click để mở modal xem ảnh lớn
- **Video controls**: Video có controls để play/pause
- **Hover effects**: Border và cursor pointer cho ảnh

### **Visual Hierarchy**:
- **Section headers**: Bold text với màu xanh
- **Image labels**: Text nhỏ với màu xám
- **Spacing**: Margin top 3 (12px) giữa các section

## 🔍 Các trường hợp hiển thị

### **1. Yêu cầu đang chờ phê duyệt**:
- ✅ Lý do yêu cầu
- ✅ Minh chứng từ client (ảnh + video)
- ❌ Không có minh chứng từ admin

### **2. Yêu cầu đã được chấp nhận**:
- ✅ Lý do yêu cầu
- ✅ Minh chứng từ client (ảnh + video)
- ✅ Phản hồi từ admin
- ✅ Minh chứng từ admin (ảnh hoàn tiền)

### **3. Yêu cầu đã bị từ chối**:
- ✅ Lý do yêu cầu
- ✅ Minh chứng từ client (ảnh + video)
- ✅ Lý do từ chối từ admin
- ❌ Không có minh chứng từ admin

### **4. Đơn hàng đã hoàn thành (hủy/trả)**:
- ✅ Lý do yêu cầu
- ✅ Minh chứng từ client (ảnh + video)
- ✅ Minh chứng từ admin (nếu có)

## 🛠️ Cấu trúc dữ liệu

### **Bảng `order_returns`**:
```sql
- images: JSON array của ảnh minh chứng từ client
- video: String path của video minh chứng từ client
- admin_proof_images: JSON array của ảnh minh chứng từ admin
- client_note: Ghi chú từ client
- admin_note: Ghi chú từ admin
- reason: Lý do yêu cầu
- status: 'pending' | 'approved' | 'rejected'
- type: 'cancel' | 'return'
```

### **Format JSON cho images**:
```json
{
    "product_id_1": ["image1.jpg", "image2.jpg"],
    "product_id_2": ["image3.jpg"]
}
```

### **Format JSON cho admin_proof_images**:
```json
["admin_proof_1.jpg", "admin_proof_2.jpg"]
```

## 🎯 Lợi ích

### **Cho Client**:
- ✅ Xem được minh chứng mình đã gửi
- ✅ Xem được minh chứng từ admin (khi được chấp nhận)
- ✅ Hiểu rõ trạng thái yêu cầu
- ✅ Có bằng chứng đầy đủ

### **Cho Admin**:
- ✅ Client có thể xem minh chứng admin đã upload
- ✅ Tăng tính minh bạch
- ✅ Giảm thắc mắc từ client

### **Cho Hệ thống**:
- ✅ Lưu trữ đầy đủ minh chứng
- ✅ Theo dõi quá trình xử lý
- ✅ Tăng độ tin cậy

## 🔧 Tính năng bổ sung

### **Modal xem ảnh lớn**:
- Click vào ảnh để mở modal
- Hiển thị ảnh với kích thước lớn
- Tiêu đề rõ ràng (Minh chứng của bạn / Minh chứng từ admin)

### **Video player**:
- Controls đầy đủ (play, pause, volume)
- Kích thước phù hợp (128x96px)
- Fallback text nếu trình duyệt không hỗ trợ

### **Responsive design**:
- Grid layout thích ứng với màn hình
- Spacing và sizing phù hợp
- Touch-friendly trên mobile

## 📱 Responsive Breakpoints

### **Mobile (< 768px)**:
- Grid 2 cột cho ảnh
- Spacing nhỏ hơn
- Text size phù hợp

### **Tablet (768px - 1024px)**:
- Grid 3-4 cột cho ảnh
- Spacing trung bình

### **Desktop (> 1024px)**:
- Grid 4 cột cho ảnh
- Spacing lớn hơn
- Layout tối ưu

## ✅ Kết quả cuối cùng

Bây giờ bên client sẽ hiển thị đầy đủ:

1. **Minh chứng của bạn** (ảnh + video từ client)
2. **Minh chứng từ admin** (ảnh hoàn tiền từ admin)
3. **Lý do và ghi chú** từ cả client và admin
4. **Trạng thái yêu cầu** rõ ràng với icon màu sắc

Tất cả minh chứng đều được hiển thị một cách trực quan, dễ xem và có thể click để xem ảnh lớn hơn! 🎉
