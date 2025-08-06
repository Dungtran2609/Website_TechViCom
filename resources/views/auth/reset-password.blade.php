@extends('client.layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 420px;">
        <div class="text-center mb-4">
            <i class="bi bi-key-fill" style="font-size: 3rem; color: #0d6efd;"></i>
            <h2 class="mt-2 mb-0">Đặt lại mật khẩu</h2>
            <p class="text-muted small">Tạo một mật khẩu mới, an toàn cho tài khoản của bạn.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token (Bắt buộc phải có) -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Địa chỉ Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mật khẩu mới -->
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Xác nhận mật khẩu mới -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>
                 @error('password_confirmation')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nút gửi -->
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">
                    Đặt lại mật khẩu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
