<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'TechViCom'))</title>
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></noscript>
    
    <!-- Bootstrap Icons - async load -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"></noscript>
    
    <!-- Tailwind CSS - optimized -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp" defer></script>
    
    <!-- Font Awesome - async load -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"></noscript>
    
    <!-- Performance Optimized CSS -->
    <link rel="stylesheet" href="{{ asset('client_css/css/performance-optimized.css') }}">
    
    <!-- Performance Optimized Scripts -->
    <script src="{{ asset('client_css/js/image-optimizer.js') }}" defer></script>

</head>
@stack('styles')
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
                <a href="#" class="btn btn-info w-100 mb-2 text-white" id="openChatbotBtn">
                    <i class="fas fa-robot me-2"></i>Chat với Trợ lý ảo
                </a>
            </div>
        </div>
    </div>

    <!-- Khung chatbot nổi, ẩn mặc định, ĐƯA RA NGOÀI CANVAS -->
    <div class="chatbot-popup" id="chatbotContainer" style="display:none;">
        <div class="chatbot-header">
            <span><i class="fas fa-robot me-2"></i>TechViCom Bot</span>
            <button class="btn-close-chat" id="closeChatbotBtn" aria-label="Đóng">&times;</button>
        </div>
        <div class="chat-box" id="chat-box">
            <div class="message bot-message">
                <span>Chào bạn, tôi là trợ lý ảo của TechViCom.<br>Bạn cần hỗ trợ gì? Hãy nhắn cho mình nhé!</span>
            </div>
        </div>
        <form class="chat-input" onsubmit="return false;">
            <input type="text" id="user-input" placeholder="Nhập tin nhắn..." autocomplete="off" aria-label="Nhập tin nhắn">
            <button id="send-btn" type="button" aria-label="Gửi">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="currentColor">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                </svg>
            </button>
        </form>
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
