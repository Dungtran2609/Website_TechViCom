@extends('client.layouts.app')

@section('title', 'Chatbot AI - Trợ lý ảo')

@section('content')
    <div class="container py-4 min-h-screen flex flex-col items-center justify-center">
        <div class="w-full max-w-md mx-auto chatbot-popup" id="chatbotContainer" style="display: flex;">
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
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatbotContainer = document.getElementById('chatbotContainer');
            const closeChatbotBtn = document.getElementById('closeChatbotBtn');
            const chatBox = document.getElementById('chat-box');
            const userInput = document.getElementById('user-input');
            const sendBtn = document.getElementById('send-btn');

            function addMessage(message, sender) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', sender === 'user' ? 'user-message' : 'bot-message');
                messageDiv.innerHTML = `<span>${message}</span>`;
                chatBox.appendChild(messageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            }

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
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            message
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
                    addMessage('Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
                }
            }

            sendBtn.addEventListener('click', sendMessage);
            userInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') sendMessage();
            });
            closeChatbotBtn.addEventListener('click', function() {
                window.location.href = '/';
            });
        });
    </script>
@endpush
