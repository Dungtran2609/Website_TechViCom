<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - Techvicom</title>
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
</head>
<body class="bg-gray-50">
    <!-- Shared Header -->
    <div id="shared-header-container"></div>

    <!-- Contact Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Liên hệ với chúng tôi</h1>
                <p class="text-lg text-gray-600">Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Gửi tin nhắn cho chúng tôi</h2>
                    
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                                <input type="text" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#ff6c2f]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                                <input type="tel" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#ff6c2f]">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#ff6c2f]">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chủ đề</label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#ff6c2f]">
                                <option>Hỗ trợ sản phẩm</option>
                                <option>Đơn hàng và giao hàng</option>
                                <option>Bảo hành</option>
                                <option>Khiếu nại</option>
                                <option>Khác</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung *</label>
                            <textarea rows="5" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#ff6c2f]"
                                      placeholder="Nhập nội dung tin nhắn..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-[#ff6c2f] text-white py-3 rounded-lg font-semibold hover:bg-[#ff6c2f] transition">
                            Gửi tin nhắn
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div>
                    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Thông tin liên hệ</h2>
                        
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <div class="bg-red-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-phone text-[#ff6c2f]"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Hotline</h3>
                                    <p class="text-gray-600">1800.6601 (Miễn phí)</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="bg-red-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-envelope text-[#ff6c2f]"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Email</h3>
                                    <p class="text-gray-600">support@techvicom.vn</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="bg-red-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-map-marker-alt text-[#ff6c2f]"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Địa chỉ</h3>
                                    <p class="text-gray-600">261 - 263 Khánh Hội, P2, Q4, TP.HCM</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="bg-red-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-clock text-[#ff6c2f]"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Giờ làm việc</h3>
                                    <p class="text-gray-600">8:00 - 22:00 (Tất cả các ngày)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Kết nối với chúng tôi</h2>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="bg-blue-400 text-white p-3 rounded-full hover:bg-blue-500 transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="bg-pink-600 text-white p-3 rounded-full hover:bg-pink-700 transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="bg-[#ff6c2f] text-white p-3 rounded-full hover:bg-[#ff6c2f] transition">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Hệ thống cửa hàng</h2>
            <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-map-marked-alt text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Bản đồ cửa hàng sẽ được tích hợp ở đây</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
    
    <!-- Shared Footer -->
    <div id="shared-footer-container"></div>
</body>
</html>
