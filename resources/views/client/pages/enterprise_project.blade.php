@extends('client.layouts.app')

@section('title', 'Dự án doanh nghiệp - Techvicom')

@section('content')
<style>
/* Ẩn giỏ hàng trên trang này */
#cart-sidebar {
    display: none !important;
}
#cart-overlay {
    display: none !important;
}
</style>
<div class="bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-100 rounded-full mb-6">
                <i class="fas fa-building text-3xl text-indigo-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Dự án doanh nghiệp</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Giải pháp công nghệ toàn diện cho doanh nghiệp. Tối ưu hóa quy trình, 
                nâng cao hiệu quả kinh doanh và xây dựng nền tảng số vững chắc.
            </p>
        </div>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-8 mb-12 text-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h2 class="text-3xl font-bold mb-4">Chuyển đổi số doanh nghiệp</h2>
                        <p class="text-indigo-100 mb-6 text-lg">
                            Hỗ trợ doanh nghiệp vừa và nhỏ chuyển đổi số thành công với 
                            các giải pháp công nghệ hiện đại, chi phí hợp lý.
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-300 mr-3"></i>
                                <span>Tư vấn chiến lược chuyển đổi số</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-300 mr-3"></i>
                                <span>Triển khai hệ thống quản lý</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-300 mr-3"></i>
                                <span>Đào tạo nhân sự</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="bg-white bg-opacity-20 rounded-2xl p-6 backdrop-blur-sm">
                            <i class="fas fa-chart-line text-6xl text-white mb-4"></i>
                            <h3 class="text-xl font-bold mb-2">Tăng 300% hiệu quả</h3>
                            <p class="text-indigo-100">Sau khi triển khai giải pháp</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Section -->
            <div class="mb-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Giải pháp doanh nghiệp</h2>
                    <p class="text-gray-600">Các dịch vụ chuyên nghiệp dành cho doanh nghiệp</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Service 1 -->
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-laptop-code text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Phát triển phần mềm</h3>
                        <p class="text-gray-600 mb-4">
                            Xây dựng phần mềm quản lý theo yêu cầu riêng của doanh nghiệp
                        </p>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Hệ thống ERP
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                CRM & Sales
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Quản lý kho
                            </li>
                        </ul>
                    </div>

                    <!-- Service 2 -->
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
                        <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-cloud text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Cloud & Hosting</h3>
                        <p class="text-gray-600 mb-4">
                            Dịch vụ cloud computing và hosting bảo mật cao
                        </p>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Cloud Server
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Backup tự động
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Bảo mật 24/7
                            </li>
                        </ul>
                    </div>

                    <!-- Service 3 -->
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
                        <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-shield-alt text-2xl text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Bảo mật thông tin</h3>
                        <p class="text-gray-600 mb-4">
                            Giải pháp bảo mật toàn diện cho dữ liệu doanh nghiệp
                        </p>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Firewall
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Mã hóa dữ liệu
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Kiểm soát truy cập
                            </li>
                        </ul>
                    </div>

                    <!-- Service 4 -->
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
                        <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-chart-bar text-2xl text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Phân tích dữ liệu</h3>
                        <p class="text-gray-600 mb-4">
                            Business Intelligence và phân tích dữ liệu kinh doanh
                        </p>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Dashboard
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Báo cáo tự động
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Dự báo xu hướng
                            </li>
                        </ul>
                    </div>

                    <!-- Service 5 -->
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
                        <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-mobile-alt text-2xl text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Ứng dụng di động</h3>
                        <p class="text-gray-600 mb-4">
                            Phát triển ứng dụng mobile cho doanh nghiệp
                        </p>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                iOS & Android
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Cross-platform
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Tích hợp API
                            </li>
                        </ul>
                    </div>

                    <!-- Service 6 -->
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
                        <div class="w-16 h-16 bg-teal-100 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-users text-2xl text-teal-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Đào tạo & Tư vấn</h3>
                        <p class="text-gray-600 mb-4">
                            Đào tạo nhân sự và tư vấn chuyển đổi số
                        </p>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Đào tạo sử dụng
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Tư vấn quy trình
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Hỗ trợ kỹ thuật
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Process Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Quy trình triển khai</h2>
                    <p class="text-gray-600">6 bước chuyển đổi số thành công</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-indigo-600">1</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Khảo sát & Phân tích</h3>
                        <p class="text-gray-600 text-sm">
                            Tìm hiểu nhu cầu và hiện trạng doanh nghiệp
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-blue-600">2</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Lập kế hoạch</h3>
                        <p class="text-gray-600 text-sm">
                            Xây dựng chiến lược và lộ trình triển khai
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-green-600">3</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Thiết kế hệ thống</h3>
                        <p class="text-gray-600 text-sm">
                            Thiết kế kiến trúc và giao diện hệ thống
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-yellow-600">4</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Phát triển</h3>
                        <p class="text-gray-600 text-sm">
                            Lập trình và xây dựng hệ thống
                        </p>
                    </div>

                    <!-- Step 5 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-purple-600">5</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Triển khai</h3>
                        <p class="text-gray-600 text-sm">
                            Cài đặt và cấu hình hệ thống
                        </p>
                    </div>

                    <!-- Step 6 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-red-600">6</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Đào tạo & Hỗ trợ</h3>
                        <p class="text-gray-600 text-sm">
                            Đào tạo sử dụng và hỗ trợ sau triển khai
                        </p>
                    </div>
                </div>
            </div>

            <!-- Success Stories -->
            <div class="mb-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Dự án thành công</h2>
                    <p class="text-gray-600">Những dự án tiêu biểu đã triển khai thành công</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Project 1 -->
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-industry text-6xl text-white opacity-80"></i>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Công ty ABC</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                Hà Nội - Sản xuất
                            </p>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-cog text-blue-500 mr-2 w-4"></i>
                                    <span>Hệ thống ERP</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock text-blue-500 mr-2 w-4"></i>
                                    <span>Triển khai: 6 tháng</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-chart-line text-green-500 mr-2 w-4"></i>
                                    <span>Tăng 250% hiệu quả</span>
                                </div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3">
                                <p class="text-sm text-green-700">
                                    <i class="fas fa-quote-left mr-2"></i>
                                    "Hệ thống đã giúp chúng tôi quản lý sản xuất hiệu quả hơn rất nhiều"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Project 2 -->
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-6xl text-white opacity-80"></i>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Công ty XYZ</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                Hà Nội - Thương mại
                            </p>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-cog text-green-500 mr-2 w-4"></i>
                                    <span>Hệ thống CRM</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock text-green-500 mr-2 w-4"></i>
                                    <span>Triển khai: 4 tháng</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-chart-line text-green-500 mr-2 w-4"></i>
                                    <span>Tăng 180% doanh số</span>
                                </div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3">
                                <p class="text-sm text-green-700">
                                    <i class="fas fa-quote-left mr-2"></i>
                                    "Quản lý khách hàng và bán hàng trở nên dễ dàng hơn bao giờ hết"
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-8 mb-12 text-white">
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-bold mb-2">Thống kê dự án</h3>
                    <p class="text-indigo-100">Con số ấn tượng về các dự án đã triển khai</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">150+</div>
                        <p class="text-indigo-100">Dự án thành công</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">50+</div>
                        <p class="text-indigo-100">Khách hàng doanh nghiệp</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">98%</div>
                        <p class="text-indigo-100">Độ hài lòng</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">24/7</div>
                        <p class="text-indigo-100">Hỗ trợ kỹ thuật</p>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Liên hệ tư vấn</h3>
                    <p class="text-gray-600">Để được tư vấn giải pháp phù hợp cho doanh nghiệp của bạn</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-phone text-indigo-600 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Hotline tư vấn</h4>
                        <p class="text-indigo-500 font-bold text-lg">1800.6601</p>
                        <p class="text-sm text-gray-600">8:00 - 18:00 (T2-T6)</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Email tư vấn</h4>
                        <p class="text-blue-500 font-bold">enterprise@techvicom.com</p>
                        <p class="text-sm text-gray-600">Phản hồi trong 24h</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map-marker-alt text-purple-600 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Văn phòng</h4>
                        <p class="text-gray-700 text-sm">13 Trịnh Văn Bô, Nam Từ Liêm</p>
                        <p class="text-gray-700 text-sm">Hà Nội</p>
                    </div>
                </div>

                <div class="text-center">
                    <button class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-4 px-12 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Đặt lịch tư vấn miễn phí
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-white {
    animation: fadeInUp 0.6s ease-out;
}

/* Hover effects */
.bg-white:hover {
    transform: translateY(-5px);
}

/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Card hover effects */
.project-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>

<script>
// Add smooth scrolling and form handling
document.addEventListener('DOMContentLoaded', function() {
    // Ẩn giỏ hàng trên trang này
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartOverlay = document.getElementById('cart-overlay');
    if (cartSidebar) {
        cartSidebar.style.display = 'none';
        cartSidebar.classList.add('translate-x-full');
    }
    if (cartOverlay) {
        cartOverlay.style.display = 'none';
        cartOverlay.classList.add('hidden');
    }

    // Add click handlers for buttons
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.textContent.includes('Đặt lịch')) {
                alert('Tính năng đặt lịch tư vấn đang được phát triển. Vui lòng liên hệ hotline để được hỗ trợ.');
            }
        });
    });

    // Add smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards
    document.querySelectorAll('.bg-white').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(card);
    });
});
</script>
@endsection
