# Modal Authentication System

## Tổng quan

Hệ thống Modal Authentication đã được tích hợp để thay thế các trang authentication riêng biệt. Tất cả các form đăng nhập, đăng ký, quên mật khẩu, đặt lại mật khẩu, xác nhận mật khẩu và xác thực email đều được hiển thị trong một Modal duy nhất.

## Các tính năng

### 1. Forms được hỗ trợ
- **Login Form**: Đăng nhập với email và mật khẩu
- **Register Form**: Đăng ký tài khoản mới
- **Forgot Password Form**: Gửi link đặt lại mật khẩu
- **Reset Password Form**: Đặt lại mật khẩu với token
- **Confirm Password Form**: Xác nhận mật khẩu cho các thao tác nhạy cảm
- **Verify Email Form**: Gửi lại email xác thực

### 2. Cách sử dụng

#### Mở Modal từ JavaScript
```javascript
// Mở Modal với form đăng nhập
openAuthModalWithForm('login');

// Mở Modal với form đăng ký
openAuthModalWithForm('register');

// Mở Modal với form quên mật khẩu
openAuthModalWithForm('forgot-password');

// Mở Modal với form đặt lại mật khẩu
openAuthModalWithForm('reset-password', { token: 'your-token', email: 'user@example.com' });

// Mở Modal với form xác nhận mật khẩu
openAuthModalWithForm('confirm-password');

// Mở Modal với form xác thực email
openAuthModalWithForm('verify-email');
```

#### Sử dụng data attributes
```html
<!-- Link đăng nhập -->
<a href="#" data-auth-modal="login">Đăng nhập</a>

<!-- Link đăng ký -->
<a href="#" data-auth-modal="register">Đăng ký</a>

<!-- Link quên mật khẩu -->
<a href="#" data-auth-modal="forgot-password">Quên mật khẩu</a>

<!-- Link đặt lại mật khẩu với token -->
<a href="#" data-auth-modal="reset-password" data-token="your-token" data-email="user@example.com">Đặt lại mật khẩu</a>
```

#### Sử dụng URL parameters
```
https://yoursite.com/?auth_modal=login
https://yoursite.com/?auth_modal=register
https://yoursite.com/?auth_modal=forgot-password
https://yoursite.com/?auth_modal=reset-password&token=your-token&email=user@example.com
```

#### Sử dụng hash fragments
```
https://yoursite.com/#auth_modal=login
https://yoursite.com/#auth_modal=register
https://yoursite.com/#auth_modal=forgot-password
```

### 3. Routes đã được cập nhật

Tất cả các route authentication cũ đã được redirect về Modal:

- `/login` → Redirect to home với Modal login
- `/register` → Redirect to home với Modal register  
- `/forgot-password` → Redirect to home với Modal forgot-password
- `/reset-password/{token}` → Redirect to home với Modal reset-password
- `/confirm-password` → Redirect to home với Modal confirm-password
- `/verify-email` → Redirect to home với Modal verify-email

### 4. API Endpoints

Các endpoint POST vẫn hoạt động bình thường:
- `POST /login` - Xử lý đăng nhập
- `POST /register` - Xử lý đăng ký
- `POST /forgot-password` - Gửi email đặt lại mật khẩu
- `POST /reset-password` - Đặt lại mật khẩu
- `POST /confirm-password` - Xác nhận mật khẩu
- `POST /email/verification-notification` - Gửi email xác thực

### 5. Session Flash Messages

Modal có thể được mở tự động từ session flash messages:

```php
// Trong controller
return redirect()->route('home')->with('openAuthModal', 'login');
return redirect()->route('home')->with('openAuthModal', 'register');
return redirect()->route('home')->with('openAuthModal', 'forgot-password');
return redirect()->route('home')->with('openAuthModal', 'reset-password')->with('token', $token);
```

### 6. Styling

Modal sử dụng Bootstrap 5 với custom styling:
- Responsive design
- Smooth animations
- Custom color scheme (orange theme)
- Password visibility toggles
- Loading states
- Error handling với SweetAlert2

### 7. Validation

Tất cả các form đều có client-side và server-side validation:
- Email format validation
- Password strength requirements
- Required field validation
- Real-time feedback

### 8. Security

- CSRF protection
- Rate limiting cho email verification
- Secure password reset tokens
- Session management

## Migration từ trang cũ

### Trước đây:
```html
<a href="{{ route('login') }}">Đăng nhập</a>
<a href="{{ route('register') }}">Đăng ký</a>
```

### Bây giờ:
```html
<a href="#" onclick="openAuthModalWithForm('login'); return false;">Đăng nhập</a>
<a href="#" onclick="openAuthModalWithForm('register'); return false;">Đăng ký</a>
```

Hoặc sử dụng data attributes:
```html
<a href="#" data-auth-modal="login">Đăng nhập</a>
<a href="#" data-auth-modal="register">Đăng ký</a>
```

## Troubleshooting

### Modal không mở
1. Kiểm tra console để xem có lỗi JavaScript không
2. Đảm bảo file `auth-modal-handler.js` đã được load
3. Kiểm tra các function `openAuthModal`, `showLoginForm` đã được định nghĩa

### Form không submit
1. Kiểm tra CSRF token
2. Đảm bảo route POST vẫn hoạt động
3. Kiểm tra validation rules

### Styling issues
1. Đảm bảo Bootstrap 5 đã được load
2. Kiểm tra custom CSS không bị conflict
3. Kiểm tra responsive breakpoints

## Files đã được cập nhật

1. `resources/views/client/layouts/app.blade.php` - Modal HTML và JavaScript
2. `routes/auth.php` - Routes đã được cập nhật
3. `app/Http/Controllers/Auth/AuthModalController.php` - Controller mới cho Modal
4. `public/client_css/js/auth-modal-handler.js` - JavaScript handler
5. Các Auth Controllers đã được cập nhật để hỗ trợ AJAX

## Lưu ý

- Tất cả các trang authentication cũ (`login.blade.php`, `register.blade.php`, etc.) vẫn tồn tại nhưng không còn được sử dụng
- Có thể xóa các file này nếu không cần thiết
- Modal hoạt động tốt trên mobile và desktop
- Tương thích với tất cả các trình duyệt hiện đại
