<header class="topbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="d-flex align-items-center">
                <!-- Menu Toggle Button -->
                <div class="topbar-item">
                    <button type="button" class="button-toggle-menu me-2">
                        <iconify-icon icon="solar:hamburger-menu-broken" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>


                <!-- Welcome Message -->
                <div class="topbar-item">
                    <h4 class="fw-bold topbar-button pe-none text-uppercase mb-0">Welcome!</h4>
                </div>
            </div>


            <div class="d-flex align-items-center gap-1">
                <!-- Theme Toggle -->
                <div class="topbar-item">
                    <button type="button" class="topbar-button" id="light-dark-mode">
                        <iconify-icon icon="solar:moon-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>


                <!-- Notification -->
                <div class="dropdown topbar-item">
                    <!-- Button Thông báo -->
                    <button type="button"
                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle position-relative btn-notification"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">

                        <i class="bx bx-bell fs-22"></i>

                        {{-- @if ($newContacts->count() > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger fs-10 px-2 notification-badge"
                                style="z-index: 1;">
                                {{ $newContacts->count() }}
                            </span>
                        @endif --}}
                    </button>

                    <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-16 fw-semibold">Thông báo liên hệ mới</h6>
                                </div>
                                <div class="col-auto">
                                    <a href="#"
                                        class="text-dark text-decoration-underline"><small>Xem tất cả</small></a>
                                </div>
                            </div>
                        </div>

                        <div data-simplebar style="max-height: 280px;">
                            {{-- @forelse($newContacts as $contact)
                                <a href="{{ route('admin.contacts.show', $contact->id) }}"
                                    class="dropdown-item py-3 border-bottom text-wrap">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="mb-0">
                                                <span class="fw-medium">{{ $contact->name }}</span> đã gửi một liên hệ
                                                mới.
                                                <br>
                                                <small
                                                    class="text-muted">{{ $contact->created_at->diffForHumans() }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="text-center text-muted p-3">Không có liên hệ mới.</p>
                            @endforelse --}}
                        </div>

                        <div class="text-center py-3">
                            <a href="#" class="btn btn-primary btn-sm">Xem tất cả liên
                                hệ</a>
                        </div>
                    </div>
                </div>


                <!-- Theme Settings -->
                <div class="topbar-item d-none d-md-flex">
                    <button type="button" class="topbar-button" data-bs-toggle="offcanvas"
                        data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
                        <iconify-icon icon="solar:settings-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>


                <!-- User Dropdown -->
                <div class="dropdown topbar-item">
                    <a type="button" class="topbar-button" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="d-flex align-items-center">
                            {{-- Sửa: Hiển thị ảnh đại diện động, có ảnh mặc định nếu không tồn tại --}}
                            <img class="rounded-circle" width="32" height="32" style="object-fit: cover;"
                                 src="{{ Auth::user()->image_profile ? asset('storage/' . Auth::user()->image_profile) : 'https://via.placeholder.com/32' }}"
                                 alt="{{ Auth::user()->name }}">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        {{-- Sửa: Hiển thị tên người dùng động --}}
                        <h6 class="dropdown-header">Welcome, {{ Auth::user()->name }}!</h6>

                        {{-- Sửa: Thêm link tới trang profile --}}
                        <a class="dropdown-item" href="{{ route('admin.users.profile') }}">
                            <i class="bx bx-user-circle text-muted fs-18 align-middle me-1"></i> Profile
                        </a>
                        <a class="dropdown-item" href="#">
<style>
    .custom-dropdown { position: relative; }
    .custom-dropdown-toggle { cursor: pointer; }
    .custom-dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        min-width: 200px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        z-index: 1000;
        padding: 0.5rem 0;
        transition: all 0.2s;
    }
    .custom-dropdown-menu.show {
        display: block;
        animation: fadeIn 0.2s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.querySelector('.custom-dropdown');
        var toggle = dropdown.querySelector('.custom-dropdown-toggle');
        var menu = dropdown.querySelector('.custom-dropdown-menu');
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            menu.classList.toggle('show');
        });
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    });
</script>
                            <i class="bx bx-message-dots text-muted fs-18 align-middle me-1"></i> Messages
                        </a>
                        <div class="dropdown-divider my-1"></div>

                        {{-- Sửa: Tạo chức năng Logout đúng cách và bảo mật --}}
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            <i class="bx bx-log-out fs-18 align-middle me-1"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>

                <!-- Search -->
                <form class="app-search d-none d-md-block ms-2">
                    <div class="position-relative">
                        <input type="search" class="form-control" placeholder="Search..." autocomplete="off">
                        <iconify-icon icon="solar:magnifer-linear" class="search-widget-icon"></iconify-icon>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>
