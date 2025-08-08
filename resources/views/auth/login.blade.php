@extends('client.layouts.app')

@section('title', 'Đăng nhập')

@push('styles')
<style>
    .auth-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .auth-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="4"/></g></svg>') repeat;
        opacity: 0.3;
    }
    
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 1;
        max-width: 450px;
        width: 100%;
        margin: 20px;
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .auth-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #ff6c2f 0%, #ff8a50 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 20px rgba(255, 108, 47, 0.3);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus {
        border-color: #ff6c2f;
        box-shadow: 0 0 0 4px rgba(255, 108, 47, 0.1);
        background: white;
    }
    
    .input-group {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        z-index: 3;
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        z-index: 3;
        transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
        color: #ff6c2f;
    }
    
    .form-control.has-icon {
        padding-left: 45px;
    }
    
    .form-control.has-toggle {
        padding-right: 45px;
    }
    
    .btn-auth {
        background: linear-gradient(135deg, #ff6c2f 0%, #ff8a50 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 16px;
        width: 100%;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 108, 47, 0.3);
    }
    
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 108, 47, 0.4);
        color: white;
    }
    
    .form-check-input:checked {
        background-color: #ff6c2f;
        border-color: #ff6c2f;
    }
    
    .auth-links a {
        color: #ff6c2f;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .auth-links a:hover {
        color: #e55a2b;
        text-decoration: underline;
    }
    
    .alert {
        border-radius: 12px;
        border: none;
        margin-bottom: 1.5rem;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
    }
    
    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e5e7eb;
    }
    
    .divider span {
        padding: 0 1rem;
        color: #9ca3af;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card p-5">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Chào mừng trở lại!</h2>
            <p class="text-gray-600">Đăng nhập để tiếp tục mua sắm</p>
        </div>
        
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2 text-orange-500"></i>
                    Địa chỉ email
                </label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" 
                           class="form-control has-icon @error('email') is-invalid @enderror" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="Nhập địa chỉ email của bạn"
                           required 
                           autofocus 
                           autocomplete="username">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-2 text-orange-500"></i>
                    Mật khẩu
                </label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" 
                           class="form-control has-icon has-toggle @error('password') is-invalid @enderror" 
                           type="password" 
                           name="password" 
                           placeholder="Nhập mật khẩu của bạn"
                           required
                           autocomplete="current-password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password_icon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-group">
                <div class="form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label text-gray-600">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn-auth">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Đăng nhập
                </button>
            </div>

            <!-- Forgot Password Link -->
            <div class="text-center auth-links">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        <i class="fas fa-key me-1"></i>
                        Quên mật khẩu?
                    </a>
                @endif
            </div>
        </form>

        <div class="divider">
            <span>hoặc</span>
        </div>

        <div class="text-center auth-links">
            <span class="text-gray-600">Chưa có tài khoản?</span>
            <a href="{{ route('register') }}" class="ms-1">
                <i class="fas fa-user-plus me-1"></i>
                Đăng ký ngay
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputName) {
    const input = document.getElementById(inputName);
    const icon = document.getElementById(inputName + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Add floating animation to auth icon
document.addEventListener('DOMContentLoaded', function() {
    const authIcon = document.querySelector('.auth-icon');
    if (authIcon) {
        authIcon.style.animation = 'float 3s ease-in-out infinite';
    }
});

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
`;
document.head.appendChild(style);
</script>
@endpush
