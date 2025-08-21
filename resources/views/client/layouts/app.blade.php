<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'TechViCom'))</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
@stack('styles')
<!-- Zalo/Contact Floating Button & Offcanvas -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    #contactChatBtn {
        position: fixed;
        bottom: 90px;
        right: 28px;
        z-index: 9999;
        background: linear-gradient(135deg, #ff6c2f 0%, #0068ff 100%);
        color: #fff;
        border-radius: 50%;
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
        font-size: 2.2rem;
        cursor: pointer;
        transition: box-shadow 0.3s, transform 0.3s;
    }

    #contactChatBtn:hover {
        box-shadow: 0 8px 32px rgba(0, 104, 255, 0.25);
        transform: scale(1.08);
    }

    .offcanvas-contact {
        border-radius: 1.2rem 1.2rem 0 0;
        box-shadow: 0 8px 32px rgba(0, 104, 255, 0.10);
        border: none;
        background: #f7faff;
        max-width: 370px;
    }

    .offcanvas-contact .offcanvas-header {
        background: linear-gradient(135deg, #ff6c2f 0%, #0068ff 100%);
        color: #fff;
        border-radius: 1.2rem 1.2rem 0 0;
        padding: 1.2rem 1.5rem;
    }

    .offcanvas-contact .offcanvas-body {
        padding: 1.5rem;
    }

    .contact-chat-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 104, 255, 0.10);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .contact-chat-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0068ff;
        margin-bottom: 0.5rem;
    }

    .contact-chat-desc {
        color: #333;
        font-size: 1rem;
        margin-bottom: 1.2rem;
    }

    .contact-chat-actions .btn {
        font-size: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 104, 255, 0.08);
    }

    .contact-chat-actions .btn-primary {
        background: linear-gradient(135deg, #0068ff 0%, #00c3ff 100%);
        border: none;
    }

    .contact-chat-actions .btn-outline-primary {
        border-color: #0068ff;
        color: #0068ff;
    }

    @media (max-width: 600px) {
        #contactChatBtn {
            right: 16px;
            bottom: 16px;
            width: 52px;
            height: 52px;
            font-size: 1.5rem;
        }

        .offcanvas-contact {
            max-width: 100vw;
        }
    }

    #scrollToTopBtn {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 9998;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        outline: none;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }

    #scrollToTopBtn.show {
        opacity: 1;
        pointer-events: auto;
    }

    #scrollToTopBtn i {
        font-size: 1.3rem;
        color: #222;
    }
</style>
<div>
    <button id="contactChatBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#contactChatOffcanvas"
        aria-controls="contactChatOffcanvas" title="Liên hệ & Chat">
        <i class="fas fa-headset"></i>
    </button>
    
    <div class="offcanvas offcanvas-end offcanvas-contact" tabindex="-1" id="contactChatOffcanvas"
        aria-labelledby="contactChatOffcanvasLabel">
        <script lang="javascript">var __vnp = {code : 26143,key:'', secret : 'eaef44ec178666a56bc5c17da48eebc9'};(function() {var ga = document.createElement('script');ga.type = 'text/javascript';ga.async=true; ga.defer=true;ga.src = '//core.vchat.vn/code/tracking.js?v=21980'; var s = document.getElementsByTagName('script');s[0].parentNode.insertBefore(ga, s[0]);})();</script>
        <div class="offcanvas-header">
            <div class="contact-chat-avatar me-3">
                @php
                    $clientLogo = \App\Models\Logo::where('type', 'client')->orderByDesc('id')->first();
                @endphp
                <img src="{{ $clientLogo ? asset('storage/' . $clientLogo->path) : asset('admin_css/images/logo_techvicom.png') }}" alt="{{ $clientLogo->alt ?? 'Techvicom' }}"
                    class="w-10 h-10 rounded-lg mr-3 object-cover">
            </div>
            <div>
                <div class="contact-chat-title">Techvicom Hỗ trợ</div>
                <div class="contact-chat-desc">Xin chào! Rất vui khi được hỗ trợ bạn.</div>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="contact-chat-actions">
                <a href="{{ route('client.contacts.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-envelope me-2"></i>Liên hệ với Techvicom
                </a>
                <a href="https://zalo.me/g/eogvfy529" target="_blank" class="btn btn-primary w-100 mb-2">
                    <i class="fab fa-zalo me-2"></i>Chat bằng Zalo
                </a>
            </div>
        </div>
    </div>
    <button id="scrollToTopBtn" title="Lên đầu trang">
        <i class="fas fa-chevron-up"></i>
    </button>
    <script>
        const scrollBtn = document.getElementById('scrollToTopBtn');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });
        scrollBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</div>
<!-- Thanh topbar phải -->
<!-- ĐÃ XÓA bell notification ở đây, sẽ chuyển vào header.blade.php -->
<div class="flex items-center space-x-4 ml-auto">
    <!-- Các nút khác (quản trị, admin, giỏ hàng) -->
    @yield('topbar-buttons')
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('notification-btn');
    const dropdown = document.getElementById('notification-dropdown');
    if(btn && dropdown) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function(e) {
            if (!btn.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }
});
</script>
</head>

<body>
    @include('client.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('client.layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
@stack('scripts')

</html>
