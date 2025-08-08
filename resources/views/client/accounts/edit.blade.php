@extends('client.layouts.app')

@section('title', 'Chỉnh sửa thông tin tài khoản')

@push('styles')
<style>
    .account-sidebar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .account-form {
        transition: all 0.3s ease;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
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
    
    .avatar-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: bold;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #ff6c2f 0%, #ff8a50 100%);
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 108, 47, 0.3);
    }
    
    @media (max-width: 768px) {
        .account-container {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 account-container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="account-sidebar rounded-lg p-6 text-white mb-4">
                    <div class="text-center mb-6">
                        <div class="avatar-placeholder mx-auto mb-3">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h4 class="font-bold text-lg">{{ Auth::user()->name }}</h4>
                        <p class="text-white/80 text-sm">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <nav class="space-y-2">
                        <a href="{{ route('accounts.index') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                            <i class="fas fa-user mr-3"></i>
                            Thông tin tài khoản
                        </a>
                        <a href="{{ route('accounts.orders') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                            <i class="fas fa-shopping-bag mr-3"></i>
                            Đơn hàng của tôi
                        </a>
                        <a href="{{ route('accounts.profile') }}" class="flex items-center p-3 rounded-lg bg-white/20 text-white">
                            <i class="fas fa-edit mr-3"></i>
                            Chỉnh sửa thông tin
                        </a>
                        <a href="{{ route('accounts.addresses') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            Sổ địa chỉ
                        </a>
                        <a href="{{ route('accounts.change-password') }}" class="flex items-center p-3 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                            <i class="fas fa-lock mr-3"></i>
                            Đổi mật khẩu
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="d-flex align-items-center mb-6">
                        <a href="{{ route('accounts.index') }}" class="text-decoration-none me-3">
                            <i class="fas fa-arrow-left text-gray-500 hover:text-gray-700"></i>
                        </a>
                        <h2 class="text-2xl font-bold text-gray-800 mb-0">
                            <i class="fas fa-edit me-2 text-orange-500"></i>
                            Chỉnh sửa thông tin tài khoản
                        </h2>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                                    <form method="POST" action="{{ route('accounts.update-profile') }}" 
                      class="account-form"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-orange-500"></i>
                                        Họ và tên <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', Auth::user()->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-2 text-orange-500"></i>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', Auth::user()->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label fw-bold">
                                        <i class="fas fa-phone me-2 text-orange-500"></i>
                                        Số điện thoại
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', Auth::user()->phone) }}" 
                                           placeholder="0987654321">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birthday" class="form-label fw-bold">
                                        <i class="fas fa-birthday-cake me-2 text-orange-500"></i>
                                        Ngày sinh
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('birthday') is-invalid @enderror" 
                                           id="birthday" 
                                           name="birthday" 
                                           value="{{ old('birthday', Auth::user()->birthday && is_object(Auth::user()->birthday) ? Auth::user()->birthday->format('Y-m-d') : (Auth::user()->birthday ?: '')) }}">
                                    @error('birthday')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender" class="form-label fw-bold">
                                        <i class="fas fa-venus-mars me-2 text-orange-500"></i>
                                        Giới tính
                                    </label>
                                    <select class="form-select @error('gender') is-invalid @enderror" 
                                            id="gender" 
                                            name="gender">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="male" {{ old('gender', Auth::user()->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender', Auth::user()->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender', Auth::user()->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-6">
                            <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Quay lại
                            </a>
                            <div>
                                <a href="{{ route('accounts.change-password') }}" class="btn btn-outline-warning me-3">
                                    <i class="fas fa-lock me-2"></i>
                                    Đổi mật khẩu
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Lưu thay đổi
                                </button>
                            </div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.account-form');
    const inputs = form.querySelectorAll('input[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
    
    function validateField(field) {
        const value = field.value.trim();
        const isValid = value !== '';
        
        if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const isValidEmail = emailRegex.test(value);
            field.classList.toggle('is-invalid', !isValidEmail);
            field.classList.toggle('is-valid', isValidEmail);
        } else {
            field.classList.toggle('is-invalid', !isValid);
            field.classList.toggle('is-valid', isValid);
        }
    }
    
    // Smooth animations
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            group.style.transition = 'all 0.5s ease';
            group.style.opacity = '1';
            group.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
