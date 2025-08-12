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


                <!-- Thông báo (Custom Dropdown) -->
                <div class="custom-dropdown topbar-item">
                    <button type="button"
                        class="custom-dropdown-toggle btn btn-icon btn-topbar btn-ghost-secondary rounded-circle position-relative btn-notification"
                        aria-label="Thông báo mới">
                        <i class="bx bx-bell fs-22"></i>
                        @if ($Contacts->count() > 0)
                            <span class="custom-badge">{{ $Contacts->count() }}</span>
                        @endif
                    </button>
                    <div class="custom-dropdown-menu dropdown-lg dropdown-menu-end">
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <span><i class="bx bx-bell me-2"></i>Thông báo liên hệ mới</span>
                            <a href="{{ route('admin.contacts.index') }}" class="text-dark text-decoration-underline"
                                style="font-size:13px"><small>Xem tất cả</small></a>
                        </div>
                        <div style="max-height: 280px; overflow-y: auto;">
                            @forelse($Contacts as $contact)
                                <a href="{{ route('admin.contacts.show', $contact->id) }}"
                                    class="dropdown-item border-bottom text-wrap">
                                    <i class="bx bx-envelope-open text-primary"></i>
                                    <div style="flex:1">
                                        <span class="notification-contact">{{ $contact->name }}</span> đã gửi một liên
                                        hệ mới.<br>
                                        <span
                                            class="notification-time">{{ $contact->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="dropdown-item text-center text-muted">Không có liên hệ mới.</div>
                            @endforelse
                        </div>
                        <div class="text-center">
                            <a href="{{ route('admin.contacts.index') }}" class="btn btn-primary btn-sm mt-2">Xem tất cả
                                liên hệ</a>
                        </div>
                    </div>
                </div>


                <!-- User Dropdown (Custom Dropdown) -->
                <div class="custom-dropdown topbar-item">
                    <button type="button" class="custom-dropdown-toggle topbar-button" aria-label="Tài khoản">
                        <span class="d-flex align-items-center">
                            @php
                                $user = Auth::user();
                                $avatar = $user && $user->image_profile
                                    ? asset('storage/' . $user->image_profile)
                                    : asset('admin_css/images/avta.png');
                            @endphp
                            <img class="rounded-circle" width="32" src="{{ $avatar }}" alt="avatar">
                        </span>
                    </button>
                    <div class="custom-dropdown-menu dropdown-menu-end" style="top:110%; min-width:200px;">
                        <div class="dropdown-header"><i class="bx bx-user me-2"></i>Xin chào</div>
                        @if($user)
                            <a class="dropdown-item" href="{{ route('admin.users.show', $user->id) }}">
                                <i class="bx bx-user-circle text-primary"></i> Hồ sơ cá nhân<br>
                                <span class="fw-bold">{{ $user->name }}</span><br>
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"
                                style="width:100%;text-align:left;display:flex;align-items:center;gap:10px;">
                                <i class="bx bx-log-out"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</header>
