@extends('client.layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 420px;">
        <div class="text-center mb-4">
            <i class="bi bi-envelope-check" style="font-size: 3rem; color: #0d6efd;"></i>
            <h2 class="mt-2 mb-0">Quên mật khẩu</h2>
            <p class="text-muted small">Nhập email của bạn để nhận liên kết đặt lại mật khẩu.</p>
        </div>

        <!-- Hiển thị thông báo trạng thái, ví dụ: "Liên kết đã được gửi!" -->
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Địa chỉ Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <!-- Hiển thị lỗi nếu có -->
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nút gửi -->
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">
                    Gửi liên kết đặt lại mật khẩu
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small ms-1">Quay lại trang đăng nhập</a>
        </div>
    </div>
</div>
@endsection
