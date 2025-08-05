@extends('client.layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 480px;">
        <div class="text-center mb-4">
            <i class="bi bi-person-plus" style="font-size: 3rem; color: #198754;"></i>
            <h2 class="mt-2 mb-0">Đăng ký</h2>
            <p class="text-muted small">Tạo tài khoản mới để sử dụng dịch vụ</p>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Họ và tên</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                </div>
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                </div>
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                </div>
                @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>
                @error('password_confirmation')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <a class="small text-decoration-none" href="{{ route('login') }}">
                    Đã có tài khoản? Đăng nhập
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-person-plus"></i> Đăng ký
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
