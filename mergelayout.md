# Yêu cầu cho GitHub Copilot
Làm giao diện cho bên client trong E:\laragon\www\TechViCom_Website\resources\views\client với chi tiết cấu trúc thư mục ở dưới
## Mục tiêu
Tạo 1 giao diện giống như giao diện ban đầu và làm đủ các yêu cầu

## Chi tiết:
Giao diện ban đầu viết thuần với html có cấu trúc :
TECHVICOM_WEBSITE_FE/
├── assets/
│   ├── css/
│   ├── data/
│   ├── images/
│   └── js/
├── components/
│   ├── shared-footer.html
│   └── shared-header.html
├── pages/
│   ├── account.html
│   ├── cart.html
│   ├── checkout.html
│   ├── contact.html
│   ├── list.html
│   ├── login.html
│   ├── order-detail.html
│   ├── order-success.html
│   ├── product-detail.html
│   └── register.html
└── index.html
Tôi đã cắt nó ra thành giao diện bình thường để dùng với blade trong E:\laragon\www\TechViCom_Website\resources\views\client
- E:\laragon\www\TechViCom_Website\resources\views\client\home.blade.php chứa giao diện trang chủ
- E:\laragon\www\TechViCom_Website\resources\views\client\layouts\app.blade.php chưa giao diện của layout để có thể thêm header.balde.php và footer.blade.php vào
- E:\laragon\www\TechViCom_Website\resources\views\client\layouts\header.blade.php chứa giao diện header
- E:\laragon\www\TechViCom_Website\resources\views\client\layouts\footer.blade.php chứa giao diện footer
- E:\laragon\www\TechViCom_Website\resources\views\client\carts\index.blade.php chứa giao diện giỏ hàng
- E:\laragon\www\TechViCom_Website\resources\views\client\products\index.blade.php chứa giao diện danh sách sản phẩm
- E:\laragon\www\TechViCom_Website\resources\views\client\products\show.blade.php chứa giao diện chi tiết sản phẩm

- E:\laragon\www\TechViCom_Website\routes\web.php chứa router ( tuy nhiên phải có comment để biết bên nào là client và admin) - đã có sẵn router admin
 -- các file đã đưa vào --
- File TechViCom_Website_FE\index.html tôi đã đưa vào E:\laragon\www\TechViCom_Website\resources\views\client\home.blade.php
- File TechViCom_Website_FE\pages\account.html tôi đã đưa vào E:\laragon\www\TechViCom_Website\resources\views\client\accounts\index.blade.php
- File TechViCom_Website_FE\pages\checkout.html tôi đã đưa vào E:\laragon\www\TechViCom_Website\resources\views\client\checkouts\index.blade.php
- File TechViCom_Website_FE\pages\contact.html tôi đã đưa vào E:\laragon\www\TechViCom_Website\resources\views\client\contacts\index.blade.php
- File TechViCom_Website_FE\pages\list.html tôi đã đưa vào E:\laragon\www\TechViCom_Website\resources\views\client\products\index.blade.php
Các file còn lại cũng với quy tắc tương tự
Cấu trúc controller sẽ là đặt theo quy tắc nh kiểu E:\laragon\www\TechViCom_Website\app\Http\Controllers\Client\Products\ClientProductController.php
Cấu trúc SQL sau khi chạy migrate là file sql.md đã được xuất từ php myadmin

## Ghi chú:
Ưu tiên làm đúng giao diện cắt như giao diện ban đầu