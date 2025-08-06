@extends('client.layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 420px;">
            <div class="text-center mb-4">
                <i class="bi bi-person-circle" style="font-size: 3rem; color: #0d6efd;"></i>
                <h2 class="mt-2 mb-0">Đăng nhập</h2>
                <p class="text-muted small">Chào mừng bạn quay lại!</p>
            </div>
            @if (session('status'))
                <div class="alert alert-success mb-3">{{ session('status') }}</div>
            @endif
            @if (session('status'))
                <div class="alert alert-success mb-3" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}"
                            required autofocus autocomplete="username">
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
                        <input id="password" class="form-control" type="password" name="password" required
                            autocomplete="current-password">
                    </div>
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label">Ghi nhớ đăng nhập</label>
                </div>

                <!-- Nút Đăng nhập -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                    </button>
                </div>

                <!-- Liên kết Quên mật khẩu -->
                <div class="text-center">
                    @if (Route::has('password.request'))
                        <a class="small" href="{{ route('password.request') }}">
                            Quên mật khẩu?
                        </a>
                    @endif
                </div>
            </form>

            <div class="text-center mt-3">
                <span class="small text-muted">Chưa có tài khoản?</span>
                <a href="{{ route('register') }}" class="small ms-1">Đăng ký ngay</a>
            </div>
        </div>
    </div>
@endsection
