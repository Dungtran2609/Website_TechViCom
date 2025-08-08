@extends('client.layouts.app')

@section('title', 'Đổi mật khẩu')

@push('styles')
<style>
    .password-form {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 1px solid #e5e7eb;
    }
    
    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #ff6c2f;
        box-shadow: 0 0 0 3px rgba(255, 108, 47, 0.1);
    }
    
    .btn-change-password {
        background: linear-gradient(135deg, #ff6c2f 0%, #ff8a50 100%);
        border: none;
    }
    
    .password-requirements {
        background: #f8f9fa;
        border-left: 4px solid #17a2b8;
    }
    
    .requirement {
        transition: all 0.3s ease;
    }
    
    .requirement.valid {
        color: #28a745;
    }
    
    .requirement.invalid {
        color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="bg-white rounded-lg p-6 mb-6 shadow-sm">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ route('accounts.index') }}" 
                           class="text-gray-600 hover:text-gray-800 text-decoration-none me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold mb-0 text-gray-800">Đổi mật khẩu</h1>
                    </div>
                    <p class="text-gray-600 mb-0">Cập nhật mật khẩu để bảo mật tài khoản của bạn</p>
                </div>

                <!-- Password Form -->
                <div class="password-form rounded-lg p-6">
                    <form action="{{ route('accounts.update-password') }}" method="POST" id="changePasswordForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-12 mb-4">
                                <label class="form-label font-semibold">
                                    <i class="fas fa-lock me-2 text-orange-500"></i>
                                    Mật khẩu hiện tại <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <input type="password" name="current_password" class="form-control pe-5" 
                                           placeholder="Nhập mật khẩu hiện tại" required>
                                    <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y me-2 p-1" 
                                            onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label font-semibold">
                                    <i class="fas fa-key me-2 text-orange-500"></i>
                                    Mật khẩu mới <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <input type="password" name="password" class="form-control pe-5" 
                                           id="new_password" placeholder="Nhập mật khẩu mới" required>
                                    <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y me-2 p-1" 
                                            onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password_icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label font-semibold">
                                    <i class="fas fa-check-circle me-2 text-orange-500"></i>
                                    Xác nhận mật khẩu mới <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" class="form-control pe-5" 
                                           id="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                                    <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y me-2 p-1" 
                                            onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="password-requirements rounded p-4 mb-4">
                            <h6 class="font-semibold mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Yêu cầu mật khẩu
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="requirement" id="req_length">
                                        <i class="fas fa-times-circle me-2"></i>
                                        Ít nhất 8 ký tự
                                    </div>
                                    <div class="requirement" id="req_uppercase">
                                        <i class="fas fa-times-circle me-2"></i>
                                        Ít nhất 1 chữ hoa
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="requirement" id="req_lowercase">
                                        <i class="fas fa-times-circle me-2"></i>
                                        Ít nhất 1 chữ thường
                                    </div>
                                    <div class="requirement" id="req_number">
                                        <i class="fas fa-times-circle me-2"></i>
                                        Ít nhất 1 số
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-times me-2"></i>
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-change-password text-white px-4 py-2">
                                <i class="fas fa-save me-2"></i>
                                Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show success/error messages
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

// Toggle password visibility
function togglePassword(inputName) {
    const input = document.querySelector(`input[name="${inputName}"]`);
    const icon = document.getElementById(`${inputName}_icon`);
    
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
document.getElementById('new_password').addEventListener('input', function() {
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
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.setCustomValidity('Mật khẩu xác nhận không khớp');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
});
</script>
@endpush
