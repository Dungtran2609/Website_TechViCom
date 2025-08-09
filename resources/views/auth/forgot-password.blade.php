@extends('client.layouts.app')

@section('title', 'Quên mật khẩu')

@push('styles')
<style>
    .auth-container {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
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
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { 
            transform: scale(1);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
        50% { 
            transform: scale(1.05);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4);
        }
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
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
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
    
    .form-control.has-icon {
        padding-left: 45px;
    }
    
    .btn-auth {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 16px;
        width: 100%;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        color: white;
    }
    
    .btn-auth:active {
        transform: translateY(0);
    }
    
    .auth-links a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .auth-links a:hover {
        color: #4f46e5;
        text-decoration: underline;
    }
    
    .success-alert {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        border: none;
        border-radius: 12px;
        color: white;
        padding: 15px 20px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .success-alert .fas {
        margin-right: 8px;
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
    
    .info-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 1.5rem;
        color: #64748b;
        font-size: 14px;
        text-align: center;
    }
    
    .info-box .fas {
        color: #6366f1;
        margin-bottom: 8px;
        font-size: 1.2rem;
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card p-5">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-key text-white" style="font-size: 2rem;"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Quên mật khẩu?</h2>
            <p class="text-gray-600">Không sao cả! Chúng tôi sẽ giúp bạn khôi phục tài khoản</p>
        </div>

        <!-- Success Message -->
        @if (session('status'))
            <div class="success-alert">
                <i class="fas fa-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Info Box -->
        <div class="info-box">
            <i class="fas fa-info-circle d-block"></i>
            Nhập địa chỉ email đã đăng ký và chúng tôi sẽ gửi liên kết đặt lại mật khẩu cho bạn.
        </div>
        
        <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2 text-indigo-500"></i>
                    Địa chỉ email
                </label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" 
                           class="form-control has-icon @error('email') is-invalid @enderror" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="Nhập địa chỉ email đã đăng ký"
                           required 
                           autofocus 
                           autocomplete="email">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn-auth" id="submitBtn">
                    <span class="btn-text">
                        <i class="fas fa-paper-plane me-2"></i>
                        Gửi liên kết đặt lại
                    </span>
                    <span class="btn-loading d-none">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Đang gửi...
                    </span>
                </button>
            </div>
        </form>

        <div class="divider">
            <span>hoặc</span>
        </div>

        <div class="text-center auth-links">
            <span class="text-gray-600">Nhớ mật khẩu rồi?</span>
            <a href="{{ route('login') }}" class="ms-1">
                <i class="fas fa-sign-in-alt me-1"></i>
                Đăng nhập ngay
            </a>
        </div>

        <div class="text-center auth-links mt-2">
            <span class="text-gray-600">Chưa có tài khoản?</span>
            <a href="{{ route('register') }}" class="ms-1">
                <i class="fas fa-user-plus me-1"></i>
                Đăng ký tại đây
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Form submission with loading state
document.getElementById('forgotPasswordForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    // Show loading state
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');
    submitBtn.disabled = true;
    
    // Reset after 5 seconds if no response
    setTimeout(function() {
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
        submitBtn.disabled = false;
    }, 5000);
});

// Email format validation
document.getElementById('email').addEventListener('input', function() {
    const email = this.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        this.setCustomValidity('Vui lòng nhập địa chỉ email hợp lệ');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
});

// Success message auto-hide
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.querySelector('.success-alert');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            successAlert.style.transform = 'translateY(-20px)';
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 300);
        }, 5000);
    }
});
</script>
@endpush
