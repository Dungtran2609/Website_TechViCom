@extends('client.layouts.app')

@section('title', 'Đặt lại mật khẩu')

@push('styles')
<style>
    .auth-container {
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
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
        background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><path d="M30 10L40 20L30 30L20 20Z"/></g></svg>') repeat;
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
        max-width: 500px;
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
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
        animation: glow 2s ease-in-out infinite alternate;
    }
    
    @keyframes glow {
        0% { 
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
        }
        100% { 
            box-shadow: 0 15px 30px rgba(245, 158, 11, 0.5);
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
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
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
        color: #f59e0b;
    }
    
    .form-control.has-icon {
        padding-left: 45px;
    }
    
    .form-control.has-toggle {
        padding-right: 45px;
    }
    
    .btn-auth {
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 16px;
        width: 100%;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        color: white;
    }
    
    .btn-auth:active {
        transform: translateY(0);
    }
    
    .auth-links a {
        color: #f59e0b;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .auth-links a:hover {
        color: #d97706;
        text-decoration: underline;
    }
    
    .password-requirements {
        background: #fefce8;
        border: 1px solid #fde047;
        border-radius: 8px;
        padding: 12px;
        margin-top: 8px;
        font-size: 14px;
    }
    
    .requirement {
        display: flex;
        align-items: center;
        margin-bottom: 4px;
        transition: color 0.3s ease;
    }
    
    .requirement:last-child {
        margin-bottom: 0;
    }
    
    .requirement.valid {
        color: #059669;
    }
    
    .requirement.invalid {
        color: #dc2626;
    }
    
    .requirement .fa-check-circle {
        color: #059669;
    }
    
    .requirement .fa-times-circle {
        color: #dc2626;
    }
    
    .info-box {
        background: #fef3c7;
        border: 1px solid #fbbf24;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 1.5rem;
        color: #92400e;
        font-size: 14px;
        text-align: center;
    }
    
    .info-box .fas {
        color: #f59e0b;
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
                <i class="fas fa-shield-alt text-white" style="font-size: 2rem;"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Đặt lại mật khẩu</h2>
            <p class="text-gray-600">Tạo mật khẩu mới an toàn cho tài khoản của bạn</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <i class="fas fa-info-circle d-block"></i>
            Vui lòng tạo mật khẩu mạnh với ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số.
        </div>
        
        <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2 text-amber-500"></i>
                    Địa chỉ email
                </label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" 
                           class="form-control has-icon @error('email') is-invalid @enderror" 
                           type="email" 
                           name="email" 
                           value="{{ old('email', $request->email) }}"
                           readonly
                           autocomplete="username">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-2 text-amber-500"></i>
                    Mật khẩu mới
                </label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" 
                           class="form-control has-icon has-toggle @error('password') is-invalid @enderror" 
                           type="password" 
                           name="password" 
                           placeholder="Tạo mật khẩu mới"
                           required
                           autocomplete="new-password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password_icon"></i>
                    </button>
                </div>
                
                <!-- Password Requirements -->
                <div class="password-requirements">
                    <div class="requirement" id="req_length">
                        <i class="fas fa-times-circle me-2"></i>
                        Ít nhất 8 ký tự
                    </div>
                    <div class="requirement" id="req_uppercase">
                        <i class="fas fa-times-circle me-2"></i>
                        Ít nhất 1 chữ hoa
                    </div>
                    <div class="requirement" id="req_lowercase">
                        <i class="fas fa-times-circle me-2"></i>
                        Ít nhất 1 chữ thường
                    </div>
                    <div class="requirement" id="req_number">
                        <i class="fas fa-times-circle me-2"></i>
                        Ít nhất 1 số
                    </div>
                </div>
                
                @error('password')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    <i class="fas fa-check-double me-2 text-amber-500"></i>
                    Xác nhận mật khẩu
                </label>
                <div class="input-group">
                    <i class="fas fa-check-double input-icon"></i>
                    <input id="password_confirmation" 
                           class="form-control has-icon has-toggle @error('password_confirmation') is-invalid @enderror" 
                           type="password" 
                           name="password_confirmation" 
                           placeholder="Nhập lại mật khẩu mới"
                           required
                           autocomplete="new-password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye" id="password_confirmation_icon"></i>
                    </button>
                </div>
                @error('password_confirmation')
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
                        <i class="fas fa-shield-alt me-2"></i>
                        Đặt lại mật khẩu
                    </span>
                    <span class="btn-loading d-none">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Đang xử lý...
                    </span>
                </button>
            </div>
        </form>

        <div class="text-center auth-links mt-3">
            <span class="text-gray-600">Nhớ mật khẩu rồi?</span>
            <a href="{{ route('login') }}" class="ms-1">
                <i class="fas fa-sign-in-alt me-1"></i>
                Đăng nhập ngay
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

// Password validation
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    
    // Check length
    const lengthReq = document.getElementById('req_length');
    if (password.length >= 8) {
        lengthReq.classList.remove('invalid');
        lengthReq.classList.add('valid');
        lengthReq.querySelector('i').classList.remove('fa-times-circle');
        lengthReq.querySelector('i').classList.add('fa-check-circle');
    } else {
        lengthReq.classList.remove('valid');
        lengthReq.classList.add('invalid');
        lengthReq.querySelector('i').classList.remove('fa-check-circle');
        lengthReq.querySelector('i').classList.add('fa-times-circle');
    }
    
    // Check uppercase
    const uppercaseReq = document.getElementById('req_uppercase');
    if (/[A-Z]/.test(password)) {
        uppercaseReq.classList.remove('invalid');
        uppercaseReq.classList.add('valid');
        uppercaseReq.querySelector('i').classList.remove('fa-times-circle');
        uppercaseReq.querySelector('i').classList.add('fa-check-circle');
    } else {
        uppercaseReq.classList.remove('valid');
        uppercaseReq.classList.add('invalid');
        uppercaseReq.querySelector('i').classList.remove('fa-check-circle');
        uppercaseReq.querySelector('i').classList.add('fa-times-circle');
    }
    
    // Check lowercase
    const lowercaseReq = document.getElementById('req_lowercase');
    if (/[a-z]/.test(password)) {
        lowercaseReq.classList.remove('invalid');
        lowercaseReq.classList.add('valid');
        lowercaseReq.querySelector('i').classList.remove('fa-times-circle');
        lowercaseReq.querySelector('i').classList.add('fa-check-circle');
    } else {
        lowercaseReq.classList.remove('valid');
        lowercaseReq.classList.add('invalid');
        lowercaseReq.querySelector('i').classList.remove('fa-check-circle');
        lowercaseReq.querySelector('i').classList.add('fa-times-circle');
    }
    
    // Check number
    const numberReq = document.getElementById('req_number');
    if (/[0-9]/.test(password)) {
        numberReq.classList.remove('invalid');
        numberReq.classList.add('valid');
        numberReq.querySelector('i').classList.remove('fa-times-circle');
        numberReq.querySelector('i').classList.add('fa-check-circle');
    } else {
        numberReq.classList.remove('valid');
        numberReq.classList.add('invalid');
        numberReq.querySelector('i').classList.remove('fa-check-circle');
        numberReq.querySelector('i').classList.add('fa-times-circle');
    }
});

// Confirm password matching
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.setCustomValidity('Mật khẩu xác nhận không khớp');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
});

// Form submission with loading state
document.getElementById('resetPasswordForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    // Show loading state
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');
    submitBtn.disabled = true;
});
</script>
@endpush
