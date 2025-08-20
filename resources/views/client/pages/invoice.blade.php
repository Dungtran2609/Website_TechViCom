@extends('client.layouts.app')

@section('title', 'Tra cứu hóa đơn - Techvicom')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                <i class="fas fa-file-invoice text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Tra cứu hóa đơn</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Tra cứu và tải hóa đơn điện tử của bạn một cách nhanh chóng và thuận tiện
            </p>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <!-- Search Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tra cứu thông tin hóa đơn</h2>
                    <p class="text-gray-600">Nhập thông tin để kiểm tra và tải hóa đơn</p>
                </div>

                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-receipt text-blue-500 mr-2"></i>
                                Mã đơn hàng
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-lg"
                                   placeholder="VD: TVC202501001">
                            <p class="text-xs text-gray-500 mt-2">Mã đơn hàng có định dạng: TVC + Năm + Tháng + Số thứ tự</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-phone text-blue-500 mr-2"></i>
                                Số điện thoại
                            </label>
                            <input type="tel" 
                                   class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-lg"
                                   placeholder="VD: 0123456789">
                            <p class="text-xs text-gray-500 mt-2">Số điện thoại đã đăng ký khi mua hàng</p>
                        </div>
                    </div>

                    <div class="text-center pt-4">
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-12 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-search mr-3"></i>
                            Tra cứu hóa đơn
                        </button>
                    </div>
                </form>
            </div>

            <!-- Alternative Search -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tra cứu theo Email</h2>
                    <p class="text-gray-600">Nhập email để xem tất cả hóa đơn</p>
                </div>

                <form class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-envelope text-green-500 mr-2"></i>
                            Email
                        </label>
                        <input type="email" 
                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg"
                               placeholder="VD: example@gmail.com">
                        <p class="text-xs text-gray-500 mt-2">Email đã đăng ký khi mua hàng</p>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-12 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-search mr-3"></i>
                            Tra cứu theo Email
                        </button>
                    </div>
                </form>
            </div>

            <!-- Information Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Invoice Info -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-file-invoice text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hóa đơn điện tử</h3>
                    <p class="text-gray-600 text-sm mb-4">Hóa đơn điện tử có giá trị pháp lý như hóa đơn giấy</p>
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                        Tuân thủ quy định của Bộ Tài chính
                    </div>
                </div>

                <!-- Download -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-download text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Tải hóa đơn</h3>
                    <p class="text-gray-600 text-sm mb-4">Tải hóa đơn PDF để lưu trữ hoặc in ấn</p>
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-file-pdf text-red-500 mr-1"></i>
                        Định dạng PDF chuẩn
                    </div>
                </div>

                <!-- Support -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-headset text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hỗ trợ</h3>
                    <p class="text-gray-600 text-sm mb-4">Liên hệ với chúng tôi nếu cần hỗ trợ</p>
                    <a href="{{ route('client.contacts.index') }}" class="inline-flex items-center text-blue-500 hover:text-blue-600 font-medium text-sm">
                        Liên hệ ngay <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-200">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-lightbulb text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Lưu ý quan trọng</h3>
                    <p class="text-gray-600">Về hóa đơn điện tử</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Hóa đơn điện tử có giá trị pháp lý</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Có thể tải và in nhiều lần</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Lưu trữ an toàn trên hệ thống</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Hóa đơn được tạo sau khi thanh toán thành công</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Có thể tra cứu trong vòng 10 năm</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Liên hệ hotline nếu không tìm thấy hóa đơn</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mt-8">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Thông tin liên hệ</h3>
                    <p class="text-gray-600">Liên hệ với chúng tôi để được hỗ trợ</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-phone text-blue-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Hotline</h4>
                        <p class="text-blue-500 font-bold">1800.6601</p>
                        <p class="text-sm text-gray-600">8:00 - 22:00 (T2-CN)</p>
                    </div>

                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-envelope text-green-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Email</h4>
                        <p class="text-blue-500 font-bold">techvicom@gmail.com</p>
                        <p class="text-sm text-gray-600">Phản hồi trong 24h</p>
                    </div>

                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-map-marker-alt text-purple-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Địa chỉ</h4>
                        <p class="text-gray-700 text-sm">Trường Cao đẳng FPT Polytechnic</p>
                        <p class="text-gray-700 text-sm">Hà Nội, Việt Nam</p>
                    </div>
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
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

<script>
// Add smooth scrolling and form handling
document.addEventListener('DOMContentLoaded', function() {
    // Form submission handling
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Đang tra cứu...';
            submitBtn.disabled = true;
            
            // Simulate search (replace with actual functionality)
            setTimeout(() => {
                alert('Tính năng đang được phát triển. Vui lòng liên hệ hotline để được hỗ trợ.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
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
});
</script>
@endsection
