            
<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        @php
            $adminLogo = \App\Models\Logo::where('type', 'admin')->orderByDesc('id')->first();
        @endphp
        <a href="{{ route('admin.dashboard') }}" class="logo-dark">
            <img src="{{ $adminLogo ? asset('storage/' . $adminLogo->path) : asset('admin_css/images/logo_techvicom.png') }}" class="logo-sm" alt="{{ $adminLogo->alt ?? 'logo sm' }}">
            <img src="{{ $adminLogo ? asset('storage/' . $adminLogo->path) : asset('admin_css/images/logo_techvicom.png') }}" class="logo-lg" alt="{{ $adminLogo->alt ?? 'logo dark' }}">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo-light">
            <img src="{{ $adminLogo ? asset('storage/' . $adminLogo->path) : asset('admin_css/images/logo_techvicom.png') }}" class="logo-sm" alt="{{ $adminLogo->alt ?? 'logo sm' }}">
            <img src="{{ $adminLogo ? asset('storage/' . $adminLogo->path) : asset('admin_css/images/logo_techvicom.png') }}" class="logo-lg" alt="{{ $adminLogo->alt ?? 'logo light' }}">
        </a>
    </div>

    <!-- Menu Toggle Button -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone" class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">
            <!-- Tổng quan -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Tổng quan </span>
                </a>
            </li>

            <!-- Sản phẩm -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-products" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebar-products">
                    <span class="nav-icon"><iconify-icon icon="mdi:shopping-outline"></iconify-icon></span>
                    <span class="nav-text"> Quản lý sản phẩm  </span>
                </a>
                <div class="collapse" id="sidebar-products">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.products.index') }}">Danh sách sản phẩm</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.products.categories.index') }}">Danh mục</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.products.brands.index') }}">Thương hiệu</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.products.attributes.index') }}">Thuộc tính</a></li>
                    </ul>
                </div>
            </li>

            <!-- Người dùng -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-users" data-bs-toggle="collapse">
                    <span class="nav-icon"><iconify-icon icon="mdi:account-group-outline"></iconify-icon></span>
                    <span class="nav-text"> Quản lý tài khoản </span>
                </a>
                <div class="collapse" id="sidebar-users">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.users.index') }}">Danh sách tài khoản </a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.users.trashed') }}">Thùng rác</a></li>
                    </ul>
                </div>
            </li>

            <!-- Đơn hàng -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-orders" data-bs-toggle="collapse">
                    <span class="nav-icon"><iconify-icon icon="mdi:cart-outline"></iconify-icon></span>
                    <span class="nav-text"> Quản lý đơn hàng </span>
                </a>
                <div class="collapse" id="sidebar-orders">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.orders.returns') }}">Hủy/Đổi trả</a></li>
                    </ul>
                </div>
            </li>

            <!-- Bài viết -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-posts" data-bs-toggle="collapse">
                    <span class="nav-icon"><iconify-icon icon="mdi:post-outline"></iconify-icon></span>
                    <span class="nav-text"> Quản lý bài viết </span>
                </a>
                <div class="collapse" id="sidebar-posts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.news-categories.index') }}">Danh mục bài viết</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.news.index') }}">Bài viết</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.news-comments.index') }}">Bình luận bài viết</a></li>
                    </ul>
                </div>
            </li>

            <!-- Liên hệ -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-contacts" data-bs-toggle="collapse">
                    <span class="nav-icon"><iconify-icon icon="mdi:message-text-outline"></iconify-icon></span>
                    <span class="nav-text"> Liên hệ </span>
                </a>
                <div class="collapse" id="sidebar-contacts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.contacts.index') }}">Người dùng</a></li>
                    </ul>
                </div>
            </li>

            <!-- Đánh giá -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-reviews" data-bs-toggle="collapse">
                    <span class="nav-icon"><iconify-icon icon="mdi:star-outline"></iconify-icon></span>
                    <span class="nav-text"> Quản lý đánh giá </span>
                </a>
                <div class="collapse" id="sidebar-reviews">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.products.comments.products-with-comments') }}">Bình luận</a></li>
                    </ul>
                </div>
            </li>

            <!-- Khuyến mãi -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-promotions" data-bs-toggle="collapse">
                    <span class="nav-icon"><iconify-icon icon="mdi:tag-multiple-outline"></iconify-icon></span>
                    <span class="nav-text"> Khuyến mãi </span>
                </a>
                <div class="collapse" id="sidebar-promotions">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.coupons.index') }}">Mã giảm giá</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.promotions.index') }}">Chương trình</a></li>
                    </ul>
                </div>
            </li>

            <!-- Phân quyền -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-permissions" data-bs-toggle="collapse">
                    <span class="nav-icon"><iconify-icon icon="mdi:shield-key-outline"></iconify-icon></span>
                    <span class="nav-text"> Phân quyền </span>
                </a>
                <div class="collapse" id="sidebar-permissions">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.roles.index') }}">Vai trò</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.permissions.index') }}">Phân quyền chi tiết</a></li>
                    </ul>
                </div>
            </li>

            <!-- Cấu hình -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-settings" data-bs-toggle="collapse">
                    <span class="nav-icon"><iconify-icon icon="mdi:cog-outline"></iconify-icon></span>
                    <span class="nav-text"> Cấu hình hệ thống </span>
                </a>
                <div class="collapse" id="sidebar-settings">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.banner.index') }}">Quản lý banner</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.logos.index') }}">Quản lý logo</a></li>
                    </ul>
                </div>
            </li>
            <!-- Mail động -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebar-mails" data-bs-toggle="collapse">
                    <span class="nav-icon"><iconify-icon icon="mdi:email-outline"></iconify-icon></span>
                    <span class="nav-text"> Quản lý mail </span>
                </a>
                <div class="collapse" id="sidebar-mails">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.mails.index') }}">Danh sách mail</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.mails.trash') }}">Thùng rác</a></li>
                        <li class="sub-nav-item"><a class="sub-nav-link" href="{{ route('admin.mails.send') }}">Gửi mail</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
