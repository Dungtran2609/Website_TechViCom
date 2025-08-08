<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - Techvicom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-primary': '#ff6c2f',
                        'custom-primary-dark': '#e55a28',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/component-loader.js"></script>
    <style>
        .success-animation {
            animation: successPulse 2s ease-in-out infinite;
        }
        
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #ea580c;
            animation: confetti-fall 3s linear infinite;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Shared Header -->
    <div id="shared-header-container"></div>
    
    <!-- Success Steps -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-center">
                <div class="hidden md:flex items-center space-x-4">
                    <div class="checkout-step completed flex items-center px-4 py-2 rounded-full bg-green-500 text-white">
                        <span class="w-6 h-6 bg-white text-green-500 rounded-full flex items-center justify-center text-sm font-bold mr-2">✓</span>
                        <span>Thông tin</span>
                    </div>
                    <div class="w-8 h-0.5 bg-green-500"></div>
                    <div class="checkout-step completed flex items-center px-4 py-2 rounded-full bg-green-500 text-white">
                        <span class="w-6 h-6 bg-white text-green-500 rounded-full flex items-center justify-center text-sm font-bold mr-2">✓</span>
                        <span>Thanh toán</span>
                    </div>
                    <div class="w-8 h-0.5 bg-green-500"></div>
                    <div class="checkout-step completed flex items-center px-4 py-2 rounded-full bg-green-500 text-white">
                        <span class="w-6 h-6 bg-white text-green-500 rounded-full flex items-center justify-center text-sm font-bold mr-2">✓</span>
                        <span>Hoàn tất</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container mx-auto px-4 py-8">
        <!-- Success Message -->
        <div class="max-w-2xl mx-auto text-center">
            <!-- Success Icon -->
            <div class="mb-8">
                <div class="success-animation inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-4">
                    <i class="fas fa-check text-4xl text-green-500"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Đặt hàng thành công!</h1>
                <p class="text-gray-600">Cảm ơn bạn đã mua hàng tại Techvicom</p>
            </div>

            <!-- Order Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="grid md:grid-cols-2 gap-6 text-left">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Thông tin đơn hàng</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mã đơn hàng:</span>
                                <span id="order-id" class="font-semibold text-orange-600">#DH123456</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ngày đặt:</span>
                                <span id="order-date" class="font-semibold"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Trạng thái:</span>
                                <span class="font-semibold text-green-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    Đang xử lý
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Thanh toán:</span>
                                <span class="font-semibold text-blue-600">COD</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Thời gian giao hàng dự kiến</h3>
                        <div class="bg-orange-50 rounded-lg p-4">
                            <div class="flex items-center text-orange-700 mb-2">
                                <i class="fas fa-truck mr-2"></i>
                                <span class="font-semibold">2-3 ngày làm việc</span>
                            </div>
                            <p class="text-sm text-orange-600">
                                Đơn hàng sẽ được giao từ 8:00 - 18:00 các ngày trong tuần
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 rounded-lg p-6 mb-8">
                <h3 class="font-semibold text-blue-800 mb-4">Bước tiếp theo</h3>
                <div class="grid md:grid-cols-3 gap-4 text-sm">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                        <p class="font-medium text-blue-800">Xác nhận đơn hàng</p>
                        <p class="text-blue-600">Chúng tôi sẽ gọi điện xác nhận trong 2 giờ</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-box text-blue-600"></i>
                        </div>
                        <p class="font-medium text-blue-800">Chuẩn bị hàng</p>
                        <p class="text-blue-600">Đóng gói và chuẩn bị giao hàng</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-shipping-fast text-blue-600"></i>
                        </div>
                        <p class="font-medium text-blue-800">Giao hàng</p>
                        <p class="text-blue-600">Shipper sẽ liên hệ trước khi giao</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="../index.html" class="bg-orange-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-600 transition inline-flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    Về trang chủ
                </a>
                <a href="phones.html" class="bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-600 transition inline-flex items-center justify-center">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Tiếp tục mua sắm
                </a>
                <button onclick="window.print()" class="border-2 border-orange-500 text-orange-500 px-8 py-3 rounded-lg font-semibold hover:bg-orange-50 transition inline-flex items-center justify-center">
                    <i class="fas fa-print mr-2"></i>
                    In đơn hàng
                </button>
            </div>

            <!-- Contact Info -->
            <div class="mt-8 p-4 bg-gray-100 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Cần hỗ trợ? Liên hệ với chúng tôi
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 text-sm">
                    <a href="tel:1900xxxx" class="text-orange-600 hover:text-orange-700">
                        <i class="fas fa-phone mr-1"></i>
                        Hotline: 1900-xxxx
                    </a>
                    <a href="mailto:support@techvicom.vn" class="text-orange-600 hover:text-orange-700">
                        <i class="fas fa-envelope mr-1"></i>
                        support@techvicom.vn
                    </a>
                    <span class="text-gray-600">
                        <i class="fas fa-clock mr-1"></i>
                        8:00 - 22:00 hàng ngày
                    </span>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-lg font-semibold mb-4">Về Techvicom</h4>
                    <p class="text-gray-300">Chuyên cung cấp các sản phẩm công nghệ chính hãng với giá tốt nhất.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Liên hệ</h4>
                    <p class="text-gray-300">📞 1900-xxxx</p>
                    <p class="text-gray-300">📧 support@techvicom.vn</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Chính sách</h4>
                    <ul class="text-gray-300 space-y-2">
                        <li><a href="#" class="hover:text-orange-400">Chính sách bảo hành</a></li>
                        <li><a href="#" class="hover:text-orange-400">Chính sách đổi trả</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Theo dõi</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-300 hover:text-orange-400"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set order ID from URL params
            const urlParams = new URLSearchParams(window.location.search);
            const orderId = urlParams.get('orderId') || 'DH' + Date.now();
            document.getElementById('order-id').textContent = '#' + orderId;
            
            // Set current date
            const currentDate = new Date().toLocaleDateString('vi-VN');
            document.getElementById('order-date').textContent = currentDate;
            
            // Create confetti effect
            createConfetti();
            
            // Clear any applied promo codes
            localStorage.removeItem('appliedPromo');
        });

        function createConfetti() {
            const colors = ['#ea580c', '#f97316', '#fb923c', '#fed7aa'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 2 + 's';
                    confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                    
                    document.body.appendChild(confetti);
                    
                    // Remove confetti after animation
                    setTimeout(() => {
                        if (confetti.parentNode) {
                            confetti.parentNode.removeChild(confetti);
                        }
                    }, 5000);
                }, i * 100);
            }
        }
    </script>

    <!-- Shared Footer -->
    <div id="shared-footer-container"></div>
</body>
</html>
