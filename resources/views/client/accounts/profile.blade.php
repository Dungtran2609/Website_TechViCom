@extends('client.layouts.app')

@section('title', 'Thông tin cá nhân')

@push('styles')
<style>
    .profile-card {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 1px solid #e5e7eb;
    }
    
    .info-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-item:hover {
        background: rgba(255, 108, 47, 0.05);
    }
    
    .info-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        color: #6b7280;
        margin: 0;
    }
    
    .btn-edit-profile {
        background: linear-gradient(135deg, #ff6c2f 0%, #ff8a50 100%);
        border: none;
    }
    
    .avatar-large {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        font-weight: bold;
        margin: 0 auto 1.5rem;
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
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ route('accounts.index') }}" 
                                   class="text-gray-600 hover:text-gray-800 text-decoration-none me-3">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <h1 class="text-2xl font-bold mb-0 text-gray-800">Thông tin cá nhân</h1>
                            </div>
                            <p class="text-gray-600 mb-0">Xem và quản lý thông tin tài khoản của bạn</p>
                        </div>
                        <a href="{{ route('accounts.edit') }}" class="btn btn-edit-profile text-white px-4 py-2">
                            <i class="fas fa-edit me-2"></i>
                            Chỉnh sửa
                        </a>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="profile-card rounded-lg p-6">
                    <!-- Avatar & Basic Info -->
                    <div class="text-center mb-6">
                        <div class="avatar-large">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $user->name }}</h3>
                        <p class="text-gray-600">Thành viên từ {{ $user->created_at->format('d/m/Y') }}</p>
                        
                        <div class="d-flex justify-content-center gap-4 mt-4">
                            <div class="text-center">
                                <div class="bg-blue-100 rounded-lg p-3 mb-2">
                                    <i class="fas fa-shopping-bag text-blue-600 text-xl"></i>
                                </div>
                                <div class="font-semibold">{{ $user->orders()->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Đơn hàng</div>
                            </div>
                            <div class="text-center">
                                <div class="bg-green-100 rounded-lg p-3 mb-2">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                                <div class="font-semibold">{{ $user->orders()->where('status', 'delivered')->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Hoàn thành</div>
                            </div>
                            <div class="text-center">
                                <div class="bg-orange-100 rounded-lg p-3 mb-2">
                                    <i class="fas fa-map-marker-alt text-orange-600 text-xl"></i>
                                </div>
                                <div class="font-semibold">{{ $user->addresses()->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Địa chỉ</div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Information -->
                    <div class="bg-white rounded-lg">
                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-label">
                                        <i class="fas fa-user me-2 text-orange-500"></i>
                                        Họ và tên
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <p class="info-value">{{ $user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-label">
                                        <i class="fas fa-envelope me-2 text-blue-500"></i>
                                        Email
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <p class="info-value">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-label">
                                        <i class="fas fa-phone me-2 text-green-500"></i>
                                        Số điện thoại
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <p class="info-value">{{ $user->phone ?? $user->phone_number ?? 'Chưa cập nhật' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-label">
                                        <i class="fas fa-birthday-cake me-2 text-pink-500"></i>
                                        Ngày sinh
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <p class="info-value">
                                        {{ $user->birthday && is_object($user->birthday) ? $user->birthday->format('d/m/Y') : ($user->birthday ?: 'Chưa cập nhật') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-label">
                                        <i class="fas fa-venus-mars me-2 text-purple-500"></i>
                                        Giới tính
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <p class="info-value">
                                        @switch($user->gender)
                                            @case('male')
                                                Nam
                                                @break
                                            @case('female')
                                                Nữ
                                                @break
                                            @case('other')
                                                Khác
                                                @break
                                            @default
                                                Chưa cập nhật
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-label">
                                        <i class="fas fa-calendar-check me-2 text-indigo-500"></i>
                                        Ngày tham gia
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <p class="info-value">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-label">
                                        <i class="fas fa-shield-alt me-2 text-red-500"></i>
                                        Trạng thái tài khoản
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    @if($user->is_active)
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Đang hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Bị khóa
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 d-flex justify-content-center gap-3">
                        <a href="{{ route('accounts.edit') }}" class="btn btn-edit-profile text-white px-4 py-2">
                            <i class="fas fa-edit me-2"></i>
                            Chỉnh sửa thông tin
                        </a>
                        <a href="{{ route('accounts.change-password') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="fas fa-lock me-2"></i>
                            Đổi mật khẩu
                        </a>
                        <a href="{{ route('accounts.addresses') }}" class="btn btn-outline-info px-4 py-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Quản lý địa chỉ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
