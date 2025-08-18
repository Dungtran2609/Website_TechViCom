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

    @stack('styles')

    <!-- CSS cho Nút Zalo/Lên đầu trang VÀ Chatbot -->
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

        /* === CSS CỦA CHATBOT === */
        #chatbotContainer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            max-width: 90%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            display: none; /* Ẩn ban đầu */
            flex-direction: column;
            font-family: sans-serif;
            z-index: 10000; /* Đặt z-index cao hơn để nổi lên trên */
        }
        #chatbotContainer.show {
            display: flex; /* Hiện khi có class 'show' */
        }
        .chat-header {
            background: linear-gradient(135deg, #0068ff 0%, #00c3ff 100%);
            color: white;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chat-header .btn-close-chat {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            opacity: 0.8;
            cursor: pointer;
        }
        .chat-box {
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .message {
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 18px;
            word-wrap: break-word;
            line-height: 1.4;
        }
        .user-message {
            background-color: #0d6efd;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }
        .bot-message {
            background-color: #e9e9eb;
            color: black;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }
        .chat-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .chat-input input {
            flex-grow: 1;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 20px;
            outline: none;
            font-size: 14px;
        }
        .chat-input button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0 12px;
            color: #0d6efd;
        }
    </style>
</head>

<body>
    @include('client.layouts.header')

    <main>
        @yield('content')
    </main>

    @include('client.layouts.footer')

    <!-- ======================================================= -->
    <!-- === HTML CHO CÁC NÚT HỖ TRỢ VÀ KHUNG CHATBOT === -->
    <!-- ======================================================= -->

    <!-- HTML cho Nút Zalo/Contact và Offcanvas -->
    <div>
        <button id="contactChatBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#contactChatOffcanvas"
            aria-controls="contactChatOffcanvas" title="Liên hệ & Chat">
            <i class="fas fa-headset"></i>
        </button>
        <div class="offcanvas offcanvas-end offcanvas-contact" tabindex="-1" id="contactChatOffcanvas"
            aria-labelledby="contactChatOffcanvasLabel">
            <div class="offcanvas-header">
                <div class="contact-chat-avatar me-3">
                    <img src="{{ asset('admin_css/images/logo_techvicom.png') }}" alt="Techvicom"
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
                    <!-- NÚT MỞ CHATBOT ĐÃ ĐƯỢC THÊM VÀO ĐÂY -->
                    <a href="#" class="btn btn-info w-100 mb-2 text-white" id="openChatbotBtn">
                        <i class="fas fa-robot me-2"></i>Chat với Trợ lý ảo
                    </a>
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
    </div>

    <!-- HTML cho Khung Chat (Ẩn ban đầu) -->
    <div class="chat-container" id="chatbotContainer">
        <div class="chat-header">
            <span>TechViCom Bot</span>
            <button class="btn-close-chat" id="closeChatbotBtn">&times;</button>
        </div>
        <div class="chat-box" id="chat-box">
            <div class="message bot-message"><span>Chào bạn, tôi là trợ lý ảo của TechViCom. Tôi có thể giúp gì cho bạn?</span></div>
        </div>
        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Nhập tin nhắn..." autocomplete="off">
            <button id="send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SCRIPT CHO NÚT LÊN ĐẦU TRANG VÀ CHATBOT -->
    <script>
        // Logic cho nút Lên đầu trang
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

        // === LOGIC CHO CHATBOT ===
        document.addEventListener("DOMContentLoaded", function() {
            // Các đối tượng DOM của chatbot
            const chatbotContainer = document.getElementById('chatbotContainer');
            const openChatbotBtn = document.getElementById('openChatbotBtn');
            const closeChatbotBtn = document.getElementById('closeChatbotBtn');
            const chatBox = document.getElementById('chat-box');
            const userInput = document.getElementById('user-input');
            const sendBtn = document.getElementById('send-btn');

            // Lấy đối tượng Offcanvas để đóng nó khi mở chat
            const contactOffcanvas = new bootstrap.Offcanvas(document.getElementById('contactChatOffcanvas'));

            // Sự kiện mở chatbot
            openChatbotBtn.addEventListener('click', function(e) {
                e.preventDefault();
                contactOffcanvas.hide(); // Đóng offcanvas
                chatbotContainer.classList.add('show'); // Mở khung chat
                userInput.focus();
            });

            // Sự kiện đóng chatbot
            closeChatbotBtn.addEventListener('click', function() {
                chatbotContainer.classList.remove('show');
            });

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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: message })
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

    @stack('scripts')

</body>
</html>
