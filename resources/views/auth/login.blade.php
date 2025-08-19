@extends('client.layouts.app')

@section('title', 'Đăng nhập')

@push('styles')
    <style>
        :root {
            --primary: #ff6c2f;
            --primary-2: #ff8a50;
            --bg-dark: #0f1220;
            --bg-dark-2: #1b2236;
        }

        /* ===== Layout & Theme (electronics look) ===== */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-dark-2) 100%);
            position: relative;
            overflow: hidden;
        }

        /* subtle circuit pattern overlay */
        .auth-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                url("data:image/svg+xml,%3Csvg width='80' height='80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg opacity='0.15' stroke='%23ff6c2f' stroke-width='1' fill='none'%3E%3Cpath d='M0 40 H80'/%3E%3Cpath d='M40 0 V80'/%3E%3Ccircle cx='40' cy='40' r='3'/%3E%3C/g%3E%3C/svg%3E") repeat;
            background-size: 80px 80px;
            pointer-events: none;
        }

        .auth-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            margin: 20px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
            padding: 28px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .auth-icon {
            width: 84px;
            height: 84px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: 0 10px 24px rgba(255, 108, 47, 0.35);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-8px)
            }
        }

        /* ===== Form ===== */
        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: .45rem;
            display: block;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 16px;
            transition: .25s ease;
            background: #fff;
        }

        .form-control.has-icon {
            padding-left: 44px;
        }

        .form-control.has-toggle {
            padding-right: 44px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 108, 47, .12);
            outline: 0;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: none;
            color: #9ca3af;
            cursor: pointer;
            transition: .2s;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .error-text {
            color: #dc2626;
            font-size: 13px;
            margin-top: .35rem;
        }

        .btn-auth {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 700;
            font-size: 16px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
            box-shadow: 0 6px 18px rgba(255, 108, 47, .35);
            transition: transform .15s ease, box-shadow .2s ease;
        }

        .btn-auth:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 26px rgba(255, 108, 47, .45);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border-radius: 6px;
            border: 2px solid #e5e7eb;
            appearance: none;
            display: inline-block;
            position: relative;
            cursor: pointer;
        }

        .form-check-input:checked {
            background: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            color: #6b7280;
        }

        .auth-links a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #9ca3af;
            font-size: 14px;
            margin: 1.2rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* ===== Social ===== */
        .social-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 10px;
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            font-weight: 700;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-google {
            background: #fff;
            color: #444;
            border-color: #e5e7eb;
        }

        .btn-google:hover {
            background: #f9fafb;
        }

        .btn-facebook {
            background: #1877f2;
            color: #fff;
        }

        .btn-facebook:hover {
            filter: brightness(1.03);
        }

        /* Alerts (optional, used by session status) */
        .alert {
            border: 0;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 1rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        }

        /* Small helpers */
        .icon-left {
            margin-right: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fas fa-microchip" style="font-size:2rem;"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Chào mừng trở lại!</h2>
                <p class="text-gray-600">Đăng nhập để tiếp tục mua sắm đồ điện tử</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Địa chỉ email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input id="email" class="form-control has-icon @error('email') is-invalid @enderror"
                            type="email" name="email" value="{{ old('email') }}"
                            placeholder="Nhập địa chỉ email của bạn" required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <div class="error-text">
                            <i class="fas fa-exclamation-circle icon-left"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password"
                            class="form-control has-icon has-toggle @error('password') is-invalid @enderror" type="password"
                            name="password" placeholder="Nhập mật khẩu của bạn" required autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password_icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-text">
                            <i class="fas fa-exclamation-circle icon-left"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember -->
                <div class="form-group">
                    <div class="form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label for="remember_me" class="form-check-label">Ghi nhớ đăng nhập</label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="form-group">
                    <button type="submit" class="btn-auth">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </button>
                </div>

                <!-- Forgot -->
                <div class="text-center auth-links">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"><i class="fas fa-key icon-left"></i>Quên mật khẩu?</a>
                    @endif
                </div>
            </form>

            <div class="divider"><span>hoặc đăng nhập nhanh</span></div>

            <div class="social-buttons">
                <a href="{{ url('auth/google') }}" class="btn-social btn-google">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google"
                        width="22" height="22">
                    Đăng nhập với Google
                </a>
                <a href="{{ url('auth/facebook') }}" class="btn-social btn-facebook">
                    <i class="fab fa-facebook-f"></i> Đăng nhập với Facebook
                </a>
            </div>

            <div class="text-center auth-links" style="margin-top:10px;">
                <span class="text-gray-600">Chưa có tài khoản?</span>
                <a href="{{ route('register') }}" style="margin-left:4px;"><i class="fas fa-user-plus icon-left"></i>Đăng ký
                    ngay</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '_icon');
            const isPwd = input.type === 'password';
            input.type = isPwd ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isPwd);
            icon.classList.toggle('fa-eye-slash', isPwd);
        }
    </script>
@endpush
