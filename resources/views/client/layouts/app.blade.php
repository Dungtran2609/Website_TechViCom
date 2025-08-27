<!DOCTYPE html>
<html lang="vi" class="client-page">

<head>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('logo_techvicom.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'TechViCom'))</title>
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    </noscript>

    <!-- Bootstrap Icons - async load -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </noscript>

    <!-- Tailwind CSS - optimized -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp" defer></script>

    <!-- Font Awesome - async load -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </noscript>

    <!-- Performance Optimized CSS -->
    <link rel="stylesheet" href="{{ asset('client_css/css/performance-optimized.css') }}">

    <!-- Performance Optimized Scripts -->
    <script src="{{ asset('client_css/js/image-optimizer.js') }}" defer></script>

</head>
@stack('styles')
</head>

<body>
    @include('client.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('client.layouts.footer')
    <!-- Zalo/Contact Floating Button & Offcanvas -->
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

        < !-- Chat với chúng tôi đã bị xóa -->padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 104, 255, 0.08);
        }

        .contact-chat-actions .btn-primary {
            background: linear-gradient(135deg, #0068ff 0%, #00c3ff 100%);
            border: none;
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

        .chatbot-popup {
            position: fixed;
            bottom: 100px;
            right: 32px;
            width: 350px;
            max-width: 95vw;
            background: #fff7f2;
            border-radius: 1.2rem;
            box-shadow: 0 8px 32px rgba(255, 108, 47, 0.18);
            display: flex;
            flex-direction: column;
            z-index: 10000;
            border: 1.5px solid #ffb347;
        }

        .chatbot-header {
            background: linear-gradient(135deg, #ff6c2f 0%, #ffb347 100%);
            color: #fff;
            border-radius: 1.2rem 1.2rem 0 0;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .chatbot-header i {
            margin-right: 8px;
        }

        .btn-close-chat {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            margin-left: 10px;
        }

        .chat-box {
            padding: 1.2rem 1rem 1rem 1rem;
            flex: 1;
            overflow-y: auto;
            background: #fff7f2;
            min-height: 120px;
            max-height: 320px;
        }

        .message {
            margin-bottom: 0.7rem;
            display: flex;
        }

        .bot-message span {
            background: linear-gradient(135deg, #ffb347 0%, #ff6c2f 100%);
            color: #fff;
            padding: 0.7rem 1.1rem;
            border-radius: 1.1rem 1.1rem 1.1rem 0.3rem;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(255, 108, 47, 0.10);
        }

        .user-message span {
            background: #fff3e6;
            color: #b85c1c;
            padding: 0.7rem 1.1rem;
            border-radius: 1.1rem 1.1rem 0.3rem 1.1rem;
            font-size: 1rem;
            border: 1px solid #ffb347;
        }

        .chat-input {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem 1rem 1rem;
            background: #fff7f2;
            border-radius: 0 0 1.2rem 1.2rem;
            border-top: 1px solid #ffb347;
        }

        .chat-input input[type="text"] {
            flex: 1;
            border: 1.5px solid #ffb347;
            border-radius: 0.8rem;
            padding: 0.6rem 1rem;
            font-size: 1rem;
            outline: none;
            margin-right: 0.7rem;
            background: #fff;
            color: #b85c1c;
        }

        .chat-input input[type="text"]::placeholder {
            color: #ffb347;
        }

        .chat-input button {
            background: linear-gradient(135deg, #ff6c2f 0%, #ffb347 100%);
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .chat-input button:hover {
            background: linear-gradient(135deg, #ffb347 0%, #ff6c2f 100%);
        }

        @media (max-width: 600px) {
            .chatbot-popup {
                right: 8px;
                bottom: 70px;
                width: 98vw;
                min-width: 0;
            }
        }
    </style>
    <div>
        <button id="contactChatBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#contactChatOffcanvas"
            aria-controls="contactChatOffcanvas" title="Liên hệ & Chat">
            <i class="fas fa-headset"></i>
        </button>

        <div class="offcanvas offcanvas-end offcanvas-contact" tabindex="-1" id="contactChatOffcanvas"
            aria-labelledby="contactChatOffcanvasLabel">
            <div class="offcanvas-header">
                <div class="contact-chat-avatar me-3">
                    @php
                        $clientLogo = \App\Models\Logo::where('type', 'client')->orderByDesc('id')->first();
                    @endphp
                    <img src="{{ $clientLogo ? asset('storage/' . $clientLogo->path) : asset('admin_css/images/logo_techvicom.png') }}"
                        alt="{{ $clientLogo->alt ?? 'Techvicom' }}" class="w-10 h-10 rounded-lg mr-3 object-cover">
                </div>
                <div>
                    <div class="contact-chat-title">Techvicom Hỗ trợ</div>
                    <div class="contact-chat-desc">Xin chào! Rất vui khi được hỗ trợ bạn.</div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="contact-chat-actions">
                    <a href="{{ route('client.contacts.index') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-envelope me-2"></i>Liên hệ với Techvicom
                    </a>
                    <a href="https://zalo.me/g/eogvfy529" target="_blank" class="btn btn-primary w-100 mb-2">
                        <i class="fab fa-zalo me-2"></i>Chat bằng Zalo
                    </a>
                    <button class="btn btn-info w-100 mb-2 text-white"
                        id="openChatbotBtn">
                        <i class="fas fa-robot me-2"></i>Chat với Trợ lý ảo
                    </button>
                </div>
            </div>
        </div>


        <button id="scrollToTopBtn" title="Lên đầu trang">
            <i class="fas fa-chevron-up"></i>
        </button>
        
        <!-- Chatbot Container - Hiển thị trực tiếp trên trang -->
        <div class="chatbot-popup" id="chatbotContainer" style="display: none;">
            <div class="chatbot-header">
                <span><i class="fas fa-robot me-2"></i>Trợ lý ảo Techvicom</span>
                <button class="btn-close-chat" id="closeChatbotBtn" title="Đóng">&times;</button>
            </div>
            <div class="chat-box" id="chat-box"></div>
            <div class="chat-input">
                <input type="text" id="user-input" placeholder="Nhập câu hỏi..." autocomplete="off" />
                <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
        
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
            // Notification dropdown logic giữ nguyên
            const btn = document.getElementById('notification-btn');
            const dropdown = document.getElementById('notification-dropdown');
            if (btn && dropdown) {
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

            // Chatbot popup logic
            const openChatbotBtn = document.getElementById('openChatbotBtn');
            const chatbotContainer = document.getElementById('chatbotContainer');
            const closeChatbotBtn = document.getElementById('closeChatbotBtn');
            let contactOffcanvas = null;
            if (typeof bootstrap !== 'undefined' && document.getElementById('contactChatOffcanvas')) {
                contactOffcanvas = new bootstrap.Offcanvas(document.getElementById('contactChatOffcanvas'));
            }
            if (openChatbotBtn && chatbotContainer) {
                openChatbotBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (contactOffcanvas) contactOffcanvas.hide();
                    chatbotContainer.style.display = 'flex';
                    setTimeout(() => {
                        document.getElementById('user-input').focus();
                    }, 200);
                });
            }
            if (closeChatbotBtn && chatbotContainer) {
                closeChatbotBtn.addEventListener('click', function() {
                    chatbotContainer.style.display = 'none';
                });
            }
        });

        // === LOGIC CHO CHATBOT ===
        document.addEventListener("DOMContentLoaded", function() {
            // Các đối tượng DOM của chatbot
            const chatbotContainer = document.getElementById('chatbotContainer');
            const openChatbotBtn = document.getElementById('openChatbotBtn');
            const closeChatbotBtn = document.getElementById('closeChatbotBtn');
            const chatBox = document.getElementById('chat-box');
            const userInput = document.getElementById('user-input');
            const sendBtn = document.getElementById('send-btn');

            // Hàm để thêm tin nhắn vào giao diện
            function addMessage(message, sender) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', sender === 'user' ? 'user-message' : 'bot-message');
                messageDiv.innerHTML = `<span>${message}</span>`;
                chatBox.appendChild(messageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            // Hàm để gửi tin nhắn
            async function sendMessage() {
                const message = userInput.value.trim();
                if (message === '') return;

                addMessage(message, 'user');
                userInput.value = '';

                try {
                    const response = await fetch("{{ route('chatbot.send') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    });

                    if (response.status === 419) {
                        addMessage('Phiên làm việc của bạn đã hết hạn. Vui lòng tải lại trang.', 'bot');
                        return;
                    }

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Lỗi mạng');
                    }

                    const data = await response.json();
                    addMessage(data.reply, 'bot');

                } catch (error) {
                    console.error('Chatbot Error:', error);
                    addMessage('Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
                }
            }

            sendBtn.addEventListener('click', sendMessage);
            userInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>

    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0 position-relative">
                    <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal"
                        aria-label="Close" style="top: 15px; right: 15px; z-index: 10;"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <!-- Auth Icon -->
                    <div class="text-center mb-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-microchip text-white text-2xl"></i>
                        </div>
                        <h4 class="modal-title fw-bold text-dark mb-2" id="authModalLabel">Chào mừng trở lại!</h4>
                        <p class="text-muted small">Đăng nhập để tiếp tục mua sắm đồ điện tử</p>
                    </div>

                    <!-- Email Login Form -->
                    <div id="emailLoginForm">
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            <div class="mb-3">
                                <label for="auth-email" class="form-label fw-semibold text-dark">Địa chỉ email</label>
                                <input type="email" class="form-control form-control-lg border-2" id="auth-email"
                                    name="email" placeholder="Nhập email của bạn"
                                    style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;">
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold text-dark">Mật khẩu</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg border-2"
                                        id="password" name="password" placeholder="Nhập mật khẩu"
                                        style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;">
                                    <button type="button"
                                        class="btn position-absolute end-0 top-0 h-100 border-0 bg-transparent"
                                        id="togglePassword" style="border-radius: 0 12px 12px 0;">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="passwordError"></div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label text-muted" for="remember">Ghi nhớ đăng nhập</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" id="loginSubmitBtn"
                                style="background: linear-gradient(135deg, #ff6c2f 0%, #ff8c42 100%); border: none; border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-arrow-right me-2"></i>Đăng nhập
                            </button>
                        </form>
                        <div class="text-center mb-3">
                            <button type="button" onclick="showForgotPasswordForm()"
                                class="text-decoration-none text-orange-500 small border-0 bg-transparent">
                                <i class="fas fa-key me-1"></i>Quên mật khẩu?
                            </button>
                        </div>
                        <div class="text-center mb-3">
                            <span class="text-muted small">hoặc đăng nhập nhanh</span>
                        </div>
                        <div class="text-center mb-3">
                            <a href="{{ route('auth.google') }}" class="google-login-btn">
                                <div class="google-btn-content">
                                    <svg class="google-icon" viewBox="0 0 24 24">
                                        <path fill="#4285F4"
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                        <path fill="#34A853"
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                        <path fill="#FBBC05"
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                        <path fill="#EA4335"
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                    </svg>
                                    <span class="google-text">Đăng nhập với Google</span>
                                </div>
                            </a>
                        </div>
                        <div class="text-center">
                            <span class="text-muted small">Chưa có tài khoản?</span>
                            <button type="button" onclick="showRegisterForm()"
                                class="text-decoration-none text-orange-500 small ms-1 border-0 bg-transparent">
                                <i class="fas fa-user me-1"></i>Đăng ký ngay
                            </button>
                        </div>
                    </div>

                    <!-- Register Form -->
                    <div id="registerForm" style="display: none;">
                        <form method="POST" action="{{ route('register') }}" id="registerFormSubmit">
                            @csrf
                            <div class="mb-3">
                                <label for="reg_name" class="form-label fw-semibold text-dark">Họ và tên</label>
                                <input type="text" class="form-control form-control-lg border-2" id="reg_name"
                                    name="name" placeholder="Nhập họ và tên của bạn"
                                    style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;">
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="reg_email" class="form-label fw-semibold text-dark">Địa chỉ email</label>
                                <input type="email" class="form-control form-control-lg border-2" id="reg_email"
                                    name="email" placeholder="Nhập email của bạn"
                                    style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;">
                                <div class="invalid-feedback" id="regEmailError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="reg_password" class="form-label fw-semibold text-dark">Mật khẩu</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg border-2"
                                        id="reg_password" name="password" placeholder="Tạo mật khẩu mạnh"
                                        style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;">
                                    <button type="button"
                                        class="btn position-absolute end-0 top-0 h-100 border-0 bg-transparent"
                                        id="toggleRegPassword" style="border-radius: 0 12px 12px 0;">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="regPasswordError"></div>

                                <!-- Password Requirements -->
                                <div class="mt-2">
                                    <p class="text-muted small mb-1 fw-semibold">Mật khẩu phải có:</p>
                                    <div class="password-requirements">
                                        <p class="text-muted small mb-1">• Ít nhất 8 ký tự</p>
                                        <p class="text-muted small mb-1">• Ít nhất 1 chữ hoa (A-Z)</p>
                                        <p class="text-muted small mb-1">• Ít nhất 1 chữ thường (a-z)</p>
                                        <p class="text-muted small mb-1">• Ít nhất 1 số (0-9)</p>
                                        <p class="text-muted small mb-0">• Ít nhất 1 ký tự đặc biệt (@$!%*?&)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="reg_password_confirmation" class="form-label fw-semibold text-dark">Xác
                                    nhận mật khẩu</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg border-2"
                                        id="reg_password_confirmation" name="password_confirmation"
                                        placeholder="Nhập lại mật khẩu"
                                        style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;">
                                    <button type="button"
                                        class="btn position-absolute end-0 top-0 h-100 border-0 bg-transparent"
                                        id="toggleRegConfirmPassword" style="border-radius: 0 12px 12px 0;">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="regPasswordConfirmError"></div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="reg_terms" name="terms">
                                <label class="form-check-label text-muted" for="reg_terms">
                                    Tôi đồng ý với <a href="#" class="text-orange-500 text-decoration-none">điều
                                        khoản sử dụng</a> và <a href="#"
                                        class="text-orange-500 text-decoration-none">chính sách bảo mật</a>
                                </label>
                                <div class="invalid-feedback" id="termsError">Bạn phải đồng ý với điều khoản sử dụng
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" id="registerSubmitBtn"
                                style="background: linear-gradient(135deg, #ff6c2f 0%, #ff8c42 100%); border: none; border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-arrow-right me-2"></i>Đăng ký
                            </button>
                        </form>
                        <div class="text-center mb-3">
                            <span class="text-muted small">hoặc đăng ký nhanh</span>
                        </div>
                        <div class="text-center mb-3">
                            <a href="{{ route('auth.google') }}" class="google-login-btn">
                                <div class="google-btn-content">
                                    <svg class="google-icon" viewBox="0 0 24 24">
                                        <path fill="#4285F4"
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                        <path fill="#34A853"
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                        <path fill="#FBBC05"
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                        <path fill="#EA4335"
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                    </svg>
                                    <span class="google-text">Đăng ký với Google</span>
                                </div>
                            </a>
                        </div>
                        <div class="text-center">
                            <span class="text-muted small">Đã có tài khoản?</span>
                            <button type="button" onclick="showLoginForm()"
                                class="text-decoration-none text-orange-500 small ms-1 border-0 bg-transparent">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập ngay
                            </button>
                        </div>
                    </div>

                    <!-- Forgot Password Form -->
                    <div id="forgotPasswordForm" style="display: none;">
                        <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordFormSubmit">
                            @csrf
                            <div class="mb-3">
                                <label for="forgot_email" class="form-label fw-semibold text-dark">Địa chỉ
                                    email</label>
                                <input type="email" class="form-control form-control-lg border-2" id="forgot_email"
                                    name="email" placeholder="Nhập email của bạn"
                                    style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;">
                                <div class="invalid-feedback" id="forgotEmailError"></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3"
                                id="forgotPasswordSubmitBtn"
                                style="background: linear-gradient(135deg, #ff6c2f 0%, #ff8c42 100%); border: none; border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-paper-plane me-2"></i>Gửi link đặt lại mật khẩu
                            </button>
                        </form>
                        <div class="text-center mb-2">
                            <span class="text-muted small">Nhớ mật khẩu rồi?</span>
                            <button type="button" onclick="showLoginForm()"
                                class="text-decoration-none text-orange-500 small ms-1 border-0 bg-transparent">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập ngay
                            </button>
                        </div>
                        <div class="text-center">
                            <span class="text-muted small">Chưa có tài khoản?</span>
                            <button type="button" onclick="showRegisterForm()"
                                class="text-decoration-none text-orange-500 small ms-1 border-0 bg-transparent">
                                <i class="fas fa-user me-1"></i>Đăng ký ngay
                            </button>
                        </div>
                    </div>

                    <!-- Reset Password Form -->
                    <div id="resetPasswordForm" style="display: none;">
                        <form method="POST" action="{{ route('password.store') }}" id="resetPasswordFormSubmit">
                            @csrf
                            <input type="hidden" name="token" id="reset_token" value="">
                            <div class="mb-3">
                                <label for="reset_email" class="form-label fw-semibold text-dark">Địa chỉ
                                    email</label>
                                <input type="email" class="form-control form-control-lg border-2" id="reset_email"
                                    name="email" placeholder="Email của bạn"
                                    style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;"
                                    readonly>
                                <div class="invalid-feedback" id="resetEmailError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="reset_password" class="form-label fw-semibold text-dark">Mật khẩu
                                    mới</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg border-2"
                                        id="reset_password" name="password" placeholder="Tạo mật khẩu mới"
                                        style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;">
                                    <button type="button"
                                        class="btn position-absolute end-0 top-0 h-100 border-0 bg-transparent"
                                        id="toggleResetPassword" style="border-radius: 0 12px 12px 0;">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="resetPasswordError"></div>

                                <!-- Password Requirements -->
                                <div class="mt-2">
                                    <p class="text-muted small mb-1 fw-semibold">Mật khẩu phải có:</p>
                                    <div class="password-requirements">
                                        <p class="text-muted small mb-1">• Ít nhất 8 ký tự</p>
                                        <p class="text-muted small mb-1">• Ít nhất 1 chữ hoa (A-Z)</p>
                                        <p class="text-muted small mb-1">• Ít nhất 1 chữ thường (a-z)</p>
                                        <p class="text-muted small mb-1">• Ít nhất 1 số (0-9)</p>
                                        <p class="text-muted small mb-0">• Ít nhất 1 ký tự đặc biệt (@$!%*?&)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="reset_password_confirmation" class="form-label fw-semibold text-dark">Xác
                                    nhận mật khẩu</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg border-2"
                                        id="reset_password_confirmation" name="password_confirmation"
                                        placeholder="Nhập lại mật khẩu mới"
                                        style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;">
                                    <button type="button"
                                        class="btn position-absolute end-0 top-0 h-100 border-0 bg-transparent"
                                        id="toggleResetConfirmPassword" style="border-radius: 0 12px 12px 0;">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="resetPasswordConfirmError"></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3"
                                id="resetPasswordSubmitBtn"
                                style="background: linear-gradient(135deg, #ff6c2f 0%, #ff8c42 100%); border: none; border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-shield-alt me-2"></i>Đặt lại mật khẩu
                            </button>
                        </form>
                        <div class="text-center">
                            <span class="text-muted small">Nhớ mật khẩu rồi?</span>
                            <button type="button" onclick="showLoginForm()"
                                class="text-decoration-none text-orange-500 small ms-1 border-0 bg-transparent">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập ngay
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Form -->
                    <div id="confirmPasswordForm" style="display: none;">
                        <form method="POST" action="{{ route('password.confirm') }}"
                            id="confirmPasswordFormSubmit">
                            @csrf
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label fw-semibold text-dark">Xác nhận mật
                                    khẩu</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg border-2"
                                        id="confirm_password" name="password" placeholder="Nhập mật khẩu hiện tại"
                                        style="border-radius: 12px; border-color: #e9ecef; background-color: #f8f9fa;"
                                        required>
                                    <button type="button"
                                        class="btn position-absolute end-0 top-0 h-100 border-0 bg-transparent"
                                        id="toggleConfirmPassword" style="border-radius: 0 12px 12px 0;">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="confirmPasswordError"></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3"
                                id="confirmPasswordSubmitBtn"
                                style="background: linear-gradient(135deg, #ff6c2f 0%, #ff8c42 100%); border: none; border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-check me-2"></i>Xác nhận
                            </button>
                        </form>
                    </div>

                    <!-- Verify Email Form -->
                    <div id="verifyEmailForm" style="display: none;">
                        <div class="text-center mb-4">
                            <div
                                class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-envelope text-white text-2xl"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Xác thực email</h4>
                            <p class="text-muted">Vui lòng kiểm tra email và nhấp vào liên kết xác thực để hoàn tất
                                đăng ký.</p>
                        </div>
                        <form method="POST" action="{{ route('verification.send') }}" id="verifyEmailFormSubmit">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3"
                                id="verifyEmailSubmitBtn"
                                style="background: linear-gradient(135deg, #ff6c2f 0%, #ff8c42 100%); border: none; border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-paper-plane me-2"></i>Gửi lại email xác thực
                            </button>
                        </form>
                        <div class="text-center">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="text-decoration-none text-orange-500 small border-0 bg-transparent">
                                    <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auth Modal Styles -->
    <style>
        .modal-content {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .modal-backdrop.show {
            opacity: 0.7;
        }

        .form-control:focus {
            border-color: #ff6c2f;
            box-shadow: 0 0 0 0.2rem rgba(255, 108, 47, 0.25);
            background-color: #fff !important;
        }

        .form-control {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ff8c42 0%, #ff6c2f 100%) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 108, 47, 0.3);
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #ff6c2f;
            color: #ff6c2f;
        }

        .text-orange-500 {
            color: #ff6c2f !important;
        }

        .text-orange-500:hover {
            color: #e55a1f !important;
        }

        /* Google Login Button Styles */
        .google-login-btn {
            display: inline-block;
            width: 100%;
            max-width: 280px;
            background: #ffffff;
            border: 1px solid #dadce0;
            border-radius: 12px;
            padding: 12px 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .google-login-btn:hover {
            background: #f8f9fa;
            border-color: #dadce0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            transform: translateY(-1px);
            text-decoration: none;
        }

        .google-login-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .google-btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .google-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .google-text {
            color: #3c4043;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            letter-spacing: 0.25px;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .google-login-btn {
                max-width: 100%;
                padding: 14px 16px;
            }

            .google-text {
                font-size: 15px;
            }

            .google-icon {
                width: 22px;
                height: 22px;
            }
        }

        /* Loading state for Google button */
        .google-login-btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .google-login-btn.loading .google-btn-content::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid #dadce0;
            border-top: 2px solid #4285F4;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .invalid-feedback {
            display: block;
            font-size: 0.875rem;
            color: #dc3545;
            margin-top: 0.25rem;
        }

        #authModal .modal-dialog {
            max-width: 480px;
            animation: modalSlideIn 0.3s ease-out;
        }

        #authModal .modal-content {
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 576px) {
            #authModal .modal-dialog {
                margin: 1rem;
                max-width: calc(100% - 2rem);
            }

            #authModal .modal-content {
                border-radius: 16px;
            }

            #authModal .form-control-lg {
                font-size: 16px;
                /* Prevent zoom on iOS */
            }
        }

        /* Password Requirements Styling */
        .password-requirements {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 8px 12px;
            border: 1px solid #e9ecef;
        }
    </style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Auth Modal Handler -->
    <script src="{{ asset('client_css/js/auth-modal-handler.js') }}"></script>

    <!-- Auth Modal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing auth modal...');

            // Debug: Check if functions are available
            console.log('openAuthModal function:', typeof openAuthModal);
            console.log('showRegisterForm function:', typeof showRegisterForm);
            console.log('openAuthModalAndShowRegister function:', typeof openAuthModalAndShowRegister);

            // Check for session flash messages to open modal
            @if (session('openAuthModal'))
                const modalAction = '{{ session('openAuthModal') }}';
                const token = '{{ session('token', '') }}';

                console.log('Session flash detected:', modalAction);

                // Open modal and show appropriate form
                setTimeout(() => {
                    window.openAuthModal();

                    switch (modalAction) {
                        case 'login':
                            showLoginForm();
                            break;
                        case 'register':
                            showRegisterForm();
                            break;
                        case 'forgot-password':
                            showForgotPasswordForm();
                            break;
                        case 'reset-password':
                            if (token) {
                                showResetPasswordForm(token, '');
                            }
                            break;
                        case 'confirm-password':
                            showConfirmPasswordForm();
                            break;
                        case 'verify-email':
                            showVerifyEmailForm();
                            break;
                        default:
                            showLoginForm();
                    }
                }, 500);
            @endif

            // Simple modal functions
            window.openAuthModal = function() {
                console.log('Opening auth modal...');
                const modal = document.getElementById('authModal');
                if (modal) {
                    modal.style.display = 'block';
                    modal.classList.add('show');
                    document.body.classList.add('modal-open');

                    // Add backdrop
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    backdrop.style.position = 'fixed';
                    backdrop.style.top = '0';
                    backdrop.style.left = '0';
                    backdrop.style.width = '100vw';
                    backdrop.style.height = '100vh';
                    backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                    backdrop.style.zIndex = '1040';
                    document.body.appendChild(backdrop);

                    // Close modal when clicking backdrop
                    backdrop.addEventListener('click', function() {
                        closeAuthModal();
                    });

                    console.log('Modal opened successfully');
                } else {
                    console.error('Modal element not found');
                }
            };



            window.closeAuthModal = function() {
                console.log('Closing auth modal...');
                const modal = document.getElementById('authModal');
                if (modal) {
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                    document.body.classList.remove('modal-open');

                    // Remove backdrop
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }

                    // Reset to login form when modal is closed
                    setTimeout(() => {
                        showLoginForm();
                    }, 100);
                }
            };

            window.testModal = function() {
                console.log('Testing modal...');
                window.openAuthModal();
            };

            // Close modal when clicking close button
            const closeBtn = document.querySelector('#authModal .btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    window.closeAuthModal();
                });
            }

            // Toggle password visibility
            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }

            // Handle login form submission
            const loginForm = document.getElementById('loginForm');
            const loginSubmitBtn = document.getElementById('loginSubmitBtn');
            const emailInput = document.getElementById('auth-email');

            if (loginForm && loginSubmitBtn) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Login form submitted');

                    // Reset previous errors
                    emailInput.classList.remove('is-invalid');
                    passwordInput.classList.remove('is-invalid');
                    document.getElementById('emailError').textContent = '';
                    document.getElementById('passwordError').textContent = '';

                    // Show loading state
                    const originalText = loginSubmitBtn.innerHTML;
                    loginSubmitBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
                    loginSubmitBtn.disabled = true;

                    // Get form data
                    const formData = new FormData(loginForm);

                    // Submit form via AJAX
                    fetch(loginForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 422) {
                                    return response.json().then(data => {
                                        throw new Error(JSON.stringify(data.errors));
                                    });
                                }
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Login successful
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Đăng nhập thành công!',
                                    text: 'Chào mừng bạn trở lại!',
                                    confirmButtonColor: '#ff6c2f'
                                }).then(() => {
                                    window.closeAuthModal();
                                    window.location.reload();
                                });
                            } else {
                                // Show error in form
                                if (data.message) {
                                    emailInput.classList.add('is-invalid');
                                    document.getElementById('emailError').textContent = data.message;
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Handle validation errors
                            if (error.message.startsWith('{')) {
                                try {
                                    const errors = JSON.parse(error.message);

                                    // Display validation errors
                                    if (errors.email) {
                                        emailInput.classList.add('is-invalid');
                                        document.getElementById('emailError').textContent = errors
                                            .email[0];
                                    }
                                    if (errors.password) {
                                        passwordInput.classList.add('is-invalid');
                                        document.getElementById('passwordError').textContent = errors
                                            .password[0];
                                    }
                                } catch (e) {
                                    console.error('Error parsing validation errors:', e);
                                }
                            } else {
                                // Show generic error in form
                                emailInput.classList.add('is-invalid');
                                document.getElementById('emailError').textContent =
                                    'Có lỗi xảy ra, vui lòng thử lại';
                            }
                        })
                        .finally(() => {
                            // Reset button state
                            loginSubmitBtn.innerHTML = originalText;
                            loginSubmitBtn.disabled = false;
                        });
                });
            }

            // Google login button handling
            const googleLoginBtn = document.querySelector('.google-login-btn');
            if (googleLoginBtn) {
                googleLoginBtn.addEventListener('click', function(e) {
                    // Add loading state
                    this.classList.add('loading');
                    this.querySelector('.google-text').textContent = 'Đang chuyển hướng...';

                    // Remove loading state after a short delay (in case of redirect)
                    setTimeout(() => {
                        this.classList.remove('loading');
                        this.querySelector('.google-text').textContent = 'Đăng nhập với Google';
                    }, 3000);
                });
            }

            console.log('Auth modal script loaded successfully');

            // Form switching functions
            window.showLoginForm = function() {
                hideAllForms();
                document.getElementById('emailLoginForm').style.display = 'block';

                // Update modal title and description
                document.getElementById('authModalLabel').textContent = 'Chào mừng trở lại!';
                document.querySelector('#authModal .text-muted').textContent =
                    'Đăng nhập để tiếp tục mua sắm đồ điện tử';
            };

            window.showRegisterForm = function() {
                hideAllForms();
                document.getElementById('registerForm').style.display = 'block';

                // Update modal title and description
                document.getElementById('authModalLabel').textContent = 'Tạo tài khoản mới';
                document.querySelector('#authModal .text-muted').textContent =
                    'Đăng ký để trải nghiệm mua sắm tuyệt vời';
            };

            window.showForgotPasswordForm = function() {
                hideAllForms();
                document.getElementById('forgotPasswordForm').style.display = 'block';

                // Update modal title and description
                document.getElementById('authModalLabel').textContent = 'Quên mật khẩu?';
                document.querySelector('#authModal .text-muted').textContent =
                    'Nhập email để nhận link đặt lại mật khẩu';
            };

            window.showResetPasswordForm = function(token, email) {
                hideAllForms();
                document.getElementById('resetPasswordForm').style.display = 'block';

                // Set token and email
                document.getElementById('reset_token').value = token;
                document.getElementById('reset_email').value = email;

                // Update modal title and description
                document.getElementById('authModalLabel').textContent = 'Đặt lại mật khẩu';
                document.querySelector('#authModal .text-muted').textContent =
                    'Tạo mật khẩu mới an toàn cho tài khoản của bạn';
            };

            window.showConfirmPasswordForm = function() {
                hideAllForms();
                document.getElementById('confirmPasswordForm').style.display = 'block';

                // Update modal title and description
                document.getElementById('authModalLabel').textContent = 'Xác nhận mật khẩu';
                document.querySelector('#authModal .text-muted').textContent =
                    'Vui lòng xác nhận mật khẩu để tiếp tục';
            };

            window.showVerifyEmailForm = function() {
                hideAllForms();
                document.getElementById('verifyEmailForm').style.display = 'block';

                // Update modal title and description
                document.getElementById('authModalLabel').textContent = 'Xác thực email';
                document.querySelector('#authModal .text-muted').textContent =
                    'Vui lòng kiểm tra email và xác thực tài khoản';
            };

            function hideAllForms() {
                document.getElementById('emailLoginForm').style.display = 'none';
                document.getElementById('registerForm').style.display = 'none';
                document.getElementById('forgotPasswordForm').style.display = 'none';
                document.getElementById('resetPasswordForm').style.display = 'none';
                document.getElementById('confirmPasswordForm').style.display = 'none';
                document.getElementById('verifyEmailForm').style.display = 'none';
            }

            window.openAuthModalAndShowLogin = function() {
                console.log('Opening auth modal and showing login form...');
                try {
                    // Open modal and show login form
                    const modal = document.getElementById('authModal');
                    if (modal) {
                        modal.style.display = 'block';
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');

                        // Add backdrop
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        backdrop.style.position = 'fixed';
                        backdrop.style.top = '0';
                        backdrop.style.left = '0';
                        backdrop.style.width = '100vw';
                        backdrop.style.height = '100vh';
                        backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                        backdrop.style.zIndex = '1040';
                        document.body.appendChild(backdrop);

                        // Close modal when clicking backdrop
                        backdrop.addEventListener('click', function() {
                            closeAuthModal();
                        });

                        // Show login form directly
                        showLoginForm();

                        console.log('Modal opened with login form');
                    } else {
                        console.error('Modal element not found');
                    }
                } catch (error) {
                    console.error('Error in openAuthModalAndShowLogin:', error);
                }
            };

            window.openAuthModalAndShowRegister = function() {
                console.log('Opening auth modal and showing register form...');
                try {
                    // Open modal without showing login form first
                    const modal = document.getElementById('authModal');
                    if (modal) {
                        modal.style.display = 'block';
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');

                        // Add backdrop
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        backdrop.style.position = 'fixed';
                        backdrop.style.top = '0';
                        backdrop.style.left = '0';
                        backdrop.style.width = '100vw';
                        backdrop.style.height = '100vh';
                        backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                        backdrop.style.zIndex = '1040';
                        document.body.appendChild(backdrop);

                        // Close modal when clicking backdrop
                        backdrop.addEventListener('click', function() {
                            closeAuthModal();
                        });

                        // Show register form directly
                        showRegisterForm();

                        console.log('Modal opened with register form');
                    } else {
                        console.error('Modal element not found');
                    }
                } catch (error) {
                    console.error('Error in openAuthModalAndShowRegister:', error);
                }
            };

            // Password visibility toggles for register form
            const toggleRegPasswordBtn = document.getElementById('toggleRegPassword');
            const regPasswordInput = document.getElementById('reg_password');
            const toggleRegConfirmPasswordBtn = document.getElementById('toggleRegConfirmPassword');
            const regConfirmPasswordInput = document.getElementById('reg_password_confirmation');

            if (toggleRegPasswordBtn && regPasswordInput) {
                toggleRegPasswordBtn.addEventListener('click', function() {
                    const type = regPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    regPasswordInput.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }

            if (toggleRegConfirmPasswordBtn && regConfirmPasswordInput) {
                toggleRegConfirmPasswordBtn.addEventListener('click', function() {
                    const type = regConfirmPasswordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    regConfirmPasswordInput.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }

            // Password visibility toggles for reset password form
            const toggleResetPasswordBtn = document.getElementById('toggleResetPassword');
            const resetPasswordInput = document.getElementById('reset_password');
            const toggleResetConfirmPasswordBtn = document.getElementById('toggleResetConfirmPassword');
            const resetConfirmPasswordInput = document.getElementById('reset_password_confirmation');

            if (toggleResetPasswordBtn && resetPasswordInput) {
                toggleResetPasswordBtn.addEventListener('click', function() {
                    const type = resetPasswordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    resetPasswordInput.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }

            if (toggleResetConfirmPasswordBtn && resetConfirmPasswordInput) {
                toggleResetConfirmPasswordBtn.addEventListener('click', function() {
                    const type = resetConfirmPasswordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    resetConfirmPasswordInput.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }

            // Password visibility toggle for confirm password form
            const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('confirm_password');

            if (toggleConfirmPasswordBtn && confirmPasswordInput) {
                toggleConfirmPasswordBtn.addEventListener('click', function() {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    confirmPasswordInput.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }

            // Handle register form submission
            const registerForm = document.getElementById('registerFormSubmit');
            const registerSubmitBtn = document.getElementById('registerSubmitBtn');

            if (registerForm && registerSubmitBtn) {
                registerForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Register form submitted');

                    // Reset previous errors
                    const regNameInput = document.getElementById('reg_name');
                    const regEmailInput = document.getElementById('reg_email');
                    const regPasswordInput = document.getElementById('reg_password');
                    const regConfirmPasswordInput = document.getElementById('reg_password_confirmation');
                    const regTermsInput = document.getElementById('reg_terms');

                    regNameInput.classList.remove('is-invalid');
                    regEmailInput.classList.remove('is-invalid');
                    regPasswordInput.classList.remove('is-invalid');
                    regConfirmPasswordInput.classList.remove('is-invalid');
                    regTermsInput.classList.remove('is-invalid');

                    document.getElementById('nameError').textContent = '';
                    document.getElementById('regEmailError').textContent = '';
                    document.getElementById('regPasswordError').textContent = '';
                    document.getElementById('regPasswordConfirmError').textContent = '';
                    document.getElementById('termsError').textContent = '';

                    // Show loading state
                    const originalText = registerSubmitBtn.innerHTML;
                    registerSubmitBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng ký...';
                    registerSubmitBtn.disabled = true;

                    // Get form data
                    const formData = new FormData(registerForm);

                    // Submit form via AJAX
                    fetch(registerForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 422) {
                                    return response.json().then(data => {
                                        throw new Error(JSON.stringify(data.errors));
                                    });
                                }
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Registration successful
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Đăng ký thành công!',
                                    text: 'Chào mừng bạn đến với TechViCom!',
                                    confirmButtonColor: '#ff6c2f'
                                }).then(() => {
                                    window.closeAuthModal();
                                    window.location.reload();
                                });
                            } else {
                                // Show error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Đăng ký thất bại',
                                    text: data.message || 'Có lỗi xảy ra, vui lòng thử lại',
                                    confirmButtonColor: '#ff6c2f'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Handle validation errors
                            if (error.message.startsWith('{')) {
                                try {
                                    const errors = JSON.parse(error.message);

                                    // Display validation errors
                                    if (errors.name) {
                                        regNameInput.classList.add('is-invalid');
                                        document.getElementById('nameError').textContent = errors.name[
                                            0];
                                    }
                                    if (errors.email) {
                                        regEmailInput.classList.add('is-invalid');
                                        document.getElementById('regEmailError').textContent = errors
                                            .email[0];
                                    }
                                    if (errors.password) {
                                        regPasswordInput.classList.add('is-invalid');
                                        document.getElementById('regPasswordError').textContent = errors
                                            .password[0];
                                    }
                                    if (errors.password_confirmation) {
                                        regConfirmPasswordInput.classList.add('is-invalid');
                                        document.getElementById('regPasswordConfirmError').textContent =
                                            errors.password_confirmation[0];
                                    }
                                    if (errors.terms) {
                                        regTermsInput.classList.add('is-invalid');
                                        document.getElementById('termsError').textContent = errors
                                            .terms[0];
                                    }
                                } catch (e) {
                                    console.error('Error parsing validation errors:', e);
                                }
                            } else {
                                // Show generic error in form
                                regEmailInput.classList.add('is-invalid');
                                document.getElementById('regEmailError').textContent =
                                    'Có lỗi xảy ra, vui lòng thử lại';
                            }
                        })
                        .finally(() => {
                            // Reset button state
                            registerSubmitBtn.innerHTML = originalText;
                            registerSubmitBtn.disabled = false;
                        });
                });
            }

            // Handle forgot password form submission
            const forgotPasswordForm = document.getElementById('forgotPasswordFormSubmit');
            const forgotPasswordSubmitBtn = document.getElementById('forgotPasswordSubmitBtn');

            if (forgotPasswordForm && forgotPasswordSubmitBtn) {
                forgotPasswordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Forgot password form submitted');

                    // Reset previous errors
                    const forgotEmailInput = document.getElementById('forgot_email');
                    forgotEmailInput.classList.remove('is-invalid');
                    document.getElementById('forgotEmailError').textContent = '';

                    // Show loading state
                    const originalText = forgotPasswordSubmitBtn.innerHTML;
                    forgotPasswordSubmitBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
                    forgotPasswordSubmitBtn.disabled = true;

                    // Get form data
                    const formData = new FormData(forgotPasswordForm);

                    // Submit form via AJAX
                    fetch(forgotPasswordForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 422) {
                                    return response.json().then(data => {
                                        throw new Error(JSON.stringify(data.errors));
                                    });
                                }
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Success
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Email đã được gửi!',
                                    text: 'Vui lòng kiểm tra email của bạn để đặt lại mật khẩu.',
                                    confirmButtonColor: '#ff6c2f'
                                }).then(() => {
                                    window.closeAuthModal();
                                });
                            } else {
                                // Show error in form
                                if (data.message) {
                                    forgotEmailInput.classList.add('is-invalid');
                                    document.getElementById('forgotEmailError').textContent = data
                                        .message;
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Handle validation errors
                            if (error.message.startsWith('{')) {
                                try {
                                    const errors = JSON.parse(error.message);

                                    // Display validation errors
                                    if (errors.email) {
                                        forgotEmailInput.classList.add('is-invalid');
                                        document.getElementById('forgotEmailError').textContent = errors
                                            .email[0];
                                    }
                                } catch (e) {
                                    console.error('Error parsing validation errors:', e);
                                }
                            } else {
                                // Show generic error in form
                                forgotEmailInput.classList.add('is-invalid');
                                document.getElementById('forgotEmailError').textContent =
                                    'Có lỗi xảy ra, vui lòng thử lại';
                            }
                        })
                        .finally(() => {
                            // Reset button state
                            forgotPasswordSubmitBtn.innerHTML = originalText;
                            forgotPasswordSubmitBtn.disabled = false;
                        });
                });
            }

            // Handle reset password form submission
            const resetPasswordForm = document.getElementById('resetPasswordFormSubmit');
            const resetPasswordSubmitBtn = document.getElementById('resetPasswordSubmitBtn');

            if (resetPasswordForm && resetPasswordSubmitBtn) {
                resetPasswordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Reset password form submitted');

                    // Reset previous errors
                    const resetEmailInput = document.getElementById('reset_email');
                    const resetPasswordInput = document.getElementById('reset_password');
                    const resetConfirmPasswordInput = document.getElementById(
                    'reset_password_confirmation');

                    resetEmailInput.classList.remove('is-invalid');
                    resetPasswordInput.classList.remove('is-invalid');
                    resetConfirmPasswordInput.classList.remove('is-invalid');

                    document.getElementById('resetEmailError').textContent = '';
                    document.getElementById('resetPasswordError').textContent = '';
                    document.getElementById('resetPasswordConfirmError').textContent = '';

                    // Show loading state
                    const originalText = resetPasswordSubmitBtn.innerHTML;
                    resetPasswordSubmitBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
                    resetPasswordSubmitBtn.disabled = true;

                    // Get form data
                    const formData = new FormData(resetPasswordForm);

                    // Submit form via AJAX
                    fetch(resetPasswordForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 422) {
                                    return response.json().then(data => {
                                        throw new Error(JSON.stringify(data.errors));
                                    });
                                }
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Success
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Đặt lại mật khẩu thành công!',
                                    text: 'Mật khẩu của bạn đã được cập nhật. Vui lòng đăng nhập lại.',
                                    confirmButtonColor: '#ff6c2f'
                                }).then(() => {
                                    window.closeAuthModal();
                                    window.location.reload();
                                });
                            } else {
                                // Show error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Đặt lại mật khẩu thất bại',
                                    text: data.message || 'Có lỗi xảy ra, vui lòng thử lại',
                                    confirmButtonColor: '#ff6c2f'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Handle validation errors
                            if (error.message.startsWith('{')) {
                                try {
                                    const errors = JSON.parse(error.message);

                                    // Display validation errors
                                    if (errors.email) {
                                        resetEmailInput.classList.add('is-invalid');
                                        document.getElementById('resetEmailError').textContent = errors
                                            .email[0];
                                    }
                                    if (errors.password) {
                                        resetPasswordInput.classList.add('is-invalid');
                                        document.getElementById('resetPasswordError').textContent =
                                            errors.password[0];
                                    }
                                    if (errors.password_confirmation) {
                                        resetConfirmPasswordInput.classList.add('is-invalid');
                                        document.getElementById('resetPasswordConfirmError')
                                            .textContent = errors.password_confirmation[0];
                                    }
                                } catch (e) {
                                    console.error('Error parsing validation errors:', e);
                                }
                            } else {
                                // Show generic error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: 'Có lỗi xảy ra, vui lòng thử lại',
                                    confirmButtonColor: '#ff6c2f'
                                });
                            }
                        })
                        .finally(() => {
                            // Reset button state
                            resetPasswordSubmitBtn.innerHTML = originalText;
                            resetPasswordSubmitBtn.disabled = false;
                        });
                });
            }

            // Handle confirm password form submission
            const confirmPasswordForm = document.getElementById('confirmPasswordFormSubmit');
            const confirmPasswordSubmitBtn = document.getElementById('confirmPasswordSubmitBtn');

            if (confirmPasswordForm && confirmPasswordSubmitBtn) {
                confirmPasswordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Confirm password form submitted');

                    // Reset previous errors
                    const confirmPasswordInput = document.getElementById('confirm_password');
                    confirmPasswordInput.classList.remove('is-invalid');
                    document.getElementById('confirmPasswordError').textContent = '';

                    // Show loading state
                    const originalText = confirmPasswordSubmitBtn.innerHTML;
                    confirmPasswordSubmitBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Đang xác nhận...';
                    confirmPasswordSubmitBtn.disabled = true;

                    // Get form data
                    const formData = new FormData(confirmPasswordForm);

                    // Submit form via AJAX
                    fetch(confirmPasswordForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 422) {
                                    return response.json().then(data => {
                                        throw new Error(JSON.stringify(data.errors));
                                    });
                                }
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Success
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Xác nhận thành công!',
                                    text: 'Mật khẩu đã được xác nhận.',
                                    confirmButtonColor: '#ff6c2f'
                                }).then(() => {
                                    window.closeAuthModal();
                                    window.location.reload();
                                });
                            } else {
                                // Show error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Xác nhận thất bại',
                                    text: data.message || 'Mật khẩu không đúng',
                                    confirmButtonColor: '#ff6c2f'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Handle validation errors
                            if (error.message.startsWith('{')) {
                                try {
                                    const errors = JSON.parse(error.message);

                                    // Display validation errors
                                    if (errors.password) {
                                        confirmPasswordInput.classList.add('is-invalid');
                                        document.getElementById('confirmPasswordError').textContent =
                                            errors.password[0];
                                    }
                                } catch (e) {
                                    console.error('Error parsing validation errors:', e);
                                }
                            } else {
                                // Show generic error
                                confirmPasswordInput.classList.add('is-invalid');
                                document.getElementById('confirmPasswordError').textContent =
                                    'Mật khẩu không đúng';
                            }
                        })
                        .finally(() => {
                            // Reset button state
                            confirmPasswordSubmitBtn.innerHTML = originalText;
                            confirmPasswordSubmitBtn.disabled = false;
                        });
                });
            }

            // Handle verify email form submission
            const verifyEmailForm = document.getElementById('verifyEmailFormSubmit');
            const verifyEmailSubmitBtn = document.getElementById('verifyEmailSubmitBtn');

            if (verifyEmailForm && verifyEmailSubmitBtn) {
                verifyEmailForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Verify email form submitted');

                    // Show loading state
                    const originalText = verifyEmailSubmitBtn.innerHTML;
                    verifyEmailSubmitBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
                    verifyEmailSubmitBtn.disabled = true;

                    // Get form data
                    const formData = new FormData(verifyEmailForm);

                    // Submit form via AJAX
                    fetch(verifyEmailForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Success
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Email đã được gửi!',
                                    text: 'Vui lòng kiểm tra email của bạn để xác thực tài khoản.',
                                    confirmButtonColor: '#ff6c2f'
                                });
                            } else {
                                // Show error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gửi email thất bại',
                                    text: data.message || 'Có lỗi xảy ra, vui lòng thử lại',
                                    confirmButtonColor: '#ff6c2f'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Show generic error
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Có lỗi xảy ra, vui lòng thử lại',
                                confirmButtonColor: '#ff6c2f'
                            });
                        })
                        .finally(() => {
                            // Reset button state
                            verifyEmailSubmitBtn.innerHTML = originalText;
                            verifyEmailSubmitBtn.disabled = false;
                        });
                });
            }



            // Reset forms when modal is closed
            document.getElementById('authModal').addEventListener('hidden.bs.modal', function() {
                // Reset all forms
                document.getElementById('loginForm').reset();
                document.getElementById('registerFormSubmit').reset();
                document.getElementById('forgotPasswordFormSubmit').reset();
                document.getElementById('resetPasswordFormSubmit').reset();
                document.getElementById('confirmPasswordFormSubmit').reset();
                document.getElementById('verifyEmailFormSubmit').reset();

                // Reset error states
                const inputs = document.querySelectorAll('#authModal .form-control');
                inputs.forEach(input => input.classList.remove('is-invalid'));

                const errorDivs = document.querySelectorAll('#authModal .invalid-feedback');
                errorDivs.forEach(div => div.textContent = '');

                // Always show login form by default when modal is closed
                showLoginForm();
            });

            // Add event listener for manual modal close
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-close') ||
                    e.target.closest('.btn-close') ||
                    e.target.classList.contains('modal-backdrop')) {
                    // Reset to login form when modal is manually closed
                    setTimeout(() => {
                        showLoginForm();
                    }, 100);
                }
            });
        });
    </script>

</body>
@stack('scripts')

</html>
