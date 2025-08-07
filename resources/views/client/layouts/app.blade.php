<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TechViCom') }}</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Gradient navbar */
        .navbar-custom {
            background: linear-gradient(90deg, #0d6efd, #6610f2);
        }

        .navbar-custom .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: #ffc107 !important;
            transform: translateY(-2px);
        }

        .navbar-brand img {
            border-radius: 8px;
        }

        .btn-link.nav-link {
            color: white !important;
            text-decoration: none;
        }

        .btn-link.nav-link:hover {
            color: #ffc107 !important;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom shadow-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="{{ route('home') }}">
                <img src="{{ asset('admin_css/images/logo_techvicom.png') }}" alt="Logo" height="80"
                    class="me-2">
                {{ config('app.name', 'Techvicom') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    @auth
                        @if (auth()->user()->hasRole(['admin', 'staff']))
                            <li class="nav-item">
                                <a class="nav-link btn btn-warning text-dark px-3 ms-lg-2"
                                    href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Quản trị
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class="bi bi-person-circle me-1"></i> Tài khoản
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-link nav-link" type="submit">
                                    <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-pencil-square me-1"></i> Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
