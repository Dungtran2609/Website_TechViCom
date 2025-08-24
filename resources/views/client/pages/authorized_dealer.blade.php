@extends('client.layouts.app')

@section('title', 'Đại lý ủy quyền - Techvicom')

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
<div class="bg-gradient-to-br from-gray-50 to-green-50 min-h-screen py-12">
            <div class="techvicom-container">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                <i class="fas fa-store text-3xl text-green-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Đại lý ủy quyền</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Mạng lưới đại lý ủy quyền chính thức của Techvicom tại Hà Nội. 
                Đảm bảo chất lượng sản phẩm và dịch vụ hậu mãi tốt nhất cho khách hàng.
            </p>
        </div>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto">
            <!-- Search Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tìm đại lý gần nhất</h2>
                    <p class="text-gray-600">Nhập địa chỉ hoặc tỉnh thành để tìm đại lý ủy quyền gần bạn</p>
                </div>

                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                Tỉnh/Thành phố
                            </label>
                            <select class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg">
                                <option value="">Chọn quận/huyện</option>
                                <option value="badinh">Ba Đình</option>
                                <option value="hoankiem">Hoàn Kiếm</option>
                                <option value="tayho">Tây Hồ</option>
                                <option value="longbien">Long Biên</option>
                                <option value="caugiay">Cầu Giấy</option>
                                <option value="dongda">Đống Đa</option>
                                <option value="haibatrung">Hai Bà Trưng</option>
                                <option value="hoangmai">Hoàng Mai</option>
                                <option value="thanhxuan">Thanh Xuân</option>
                                <option value="socson">Sóc Sơn</option>
                                <option value="donganh">Đông Anh</option>
                                <option value="gialam">Gia Lâm</option>
                                <option value="namtuliem">Nam Từ Liêm</option>
                                <option value="thanhtri">Thanh Trì</option>
                                <option value="bacninh">Bắc Ninh</option>
                                <option value="hungyen">Hưng Yên</option>
                                <option value="haiduong">Hải Dương</option>
                                <option value="vinhphuc">Vĩnh Phúc</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-search text-green-500 mr-2"></i>
                                Tìm kiếm
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg"
                                   placeholder="Nhập địa chỉ, quận huyện...">
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-search mr-3"></i>
                                Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Featured Dealers -->
            <div class="mb-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Đại lý nổi bật</h2>
                    <p class="text-gray-600">Các đại lý ủy quyền chính thức được nhiều khách hàng tin tưởng</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-center">
                    <!-- Dealer 1 -->
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="relative">
                            <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                <i class="fas fa-store text-6xl text-white opacity-80"></i>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-star mr-1"></i>Chính thức
                                </span>
                            </div>
                        </div>
                        <div class="p-6 text-center text-left">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Techvicom Hà Nội</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội
                            </p>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-phone text-green-500 mr-2 w-4"></i>
                                    <span>1800.6601</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock text-green-500 mr-2 w-4"></i>
                                    <span>8:00 - 22:00 (T2-CN)</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-star text-yellow-400 mr-2 w-4"></i>
                                    <span>4.8/5 (156 đánh giá)</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg text-sm font-semibold transition-colors">
                                    <i class="fas fa-phone mr-2"></i>Gọi ngay
                                </button>
                                <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg text-sm font-semibold transition-colors">
                                    <i class="fas fa-map mr-2"></i>Chỉ đường
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <!-- Why Choose Us -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <i class="fas fa-award text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Tại sao chọn đại lý ủy quyền?</h3>
                        <p class="text-gray-600">Những lợi ích khi mua hàng tại đại lý chính thức</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Sản phẩm chính hãng 100%</h4>
                                <p class="text-gray-600 text-sm">Đảm bảo nguồn gốc xuất xứ rõ ràng, chất lượng cao</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Bảo hành chính hãng</h4>
                                <p class="text-gray-600 text-sm">Hưởng chế độ bảo hành đầy đủ từ nhà sản xuất</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Dịch vụ hậu mãi tốt</h4>
                                <p class="text-gray-600 text-sm">Hỗ trợ kỹ thuật, sửa chữa nhanh chóng, chuyên nghiệp</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Giá cả cạnh tranh</h4>
                                <p class="text-gray-600 text-sm">Mức giá tốt nhất với nhiều ưu đãi hấp dẫn</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dealer Benefits -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                            <i class="fas fa-handshake text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Lợi ích khi trở thành đại lý</h3>
                        <p class="text-gray-600">Cơ hội hợp tác kinh doanh cùng Techvicom</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                <i class="fas fa-chart-line text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Hoa hồng hấp dẫn</h4>
                                <p class="text-gray-600 text-sm">Mức hoa hồng cao nhất trong ngành</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                <i class="fas fa-tools text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Hỗ trợ kỹ thuật</h4>
                                <p class="text-gray-600 text-sm">Đào tạo và hỗ trợ kỹ thuật miễn phí</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                <i class="fas fa-bullhorn text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Marketing hỗ trợ</h4>
                                <p class="text-gray-600 text-sm">Tài liệu quảng cáo và chiến dịch marketing</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                <i class="fas fa-shipping-fast text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Giao hàng nhanh</h4>
                                <p class="text-gray-600 text-sm">Hệ thống logistics hiện đại, giao hàng trong nội thành Hà Nội</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-8 mb-12 text-white">
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-bold mb-2">Thống kê mạng lưới đại lý</h3>
                    <p class="text-green-100">Con số ấn tượng về hệ thống đại lý Techvicom</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">25+</div>
                        <p class="text-green-100">Đại lý tại Hà Nội</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">12</div>
                        <p class="text-green-100">Quận huyện phủ sóng</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">50K+</div>
                        <p class="text-green-100">Khách hàng phục vụ</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">98%</div>
                        <p class="text-green-100">Độ hài lòng khách hàng</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Liên hệ hợp tác</h3>
                    <p class="text-gray-600">Trở thành đại lý ủy quyền của Techvicom</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-phone text-green-600 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Hotline hợp tác</h4>
                        <p class="text-green-500 font-bold text-lg">1800.6601</p>
                        <p class="text-sm text-gray-600">8:00 - 18:00 (T2-T6)</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Email hợp tác</h4>
                        <p class="text-blue-500 font-bold">partnership@techvicom.com</p>
                        <p class="text-sm text-gray-600">Phản hồi trong 24h</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map-marker-alt text-purple-600 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Trụ sở chính</h4>
                        <p class="text-gray-700 text-sm">13 Trịnh Văn Bô, Nam Từ Liêm</p>
                        <p class="text-gray-700 text-sm">Hà Nội</p>
                    </div>
                </div>

                <div class="text-center mt-8">
                    <button class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-12 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-handshake mr-3"></i>
                        Đăng ký trở thành đại lý
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
    background: linear-gradient(135deg, #10b981, #059669);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Card hover effects */
.dealer-card:hover {
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

    // Form submission handling
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Đang tìm kiếm...';
            submitBtn.disabled = true;
            
            // Simulate search (replace with actual functionality)
            setTimeout(() => {
                alert('Tính năng đang được phát triển. Vui lòng liên hệ hotline để được hỗ trợ.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    });

    // Add click handlers for dealer cards
    const dealerCards = document.querySelectorAll('.dealer-card');
    dealerCards.forEach(card => {
        const callBtn = card.querySelector('button:first-child');
        const mapBtn = card.querySelector('button:last-child');
        
        if (callBtn) {
            callBtn.addEventListener('click', function() {
                alert('Tính năng gọi điện đang được phát triển.');
            });
        }
        
        if (mapBtn) {
            mapBtn.addEventListener('click', function() {
                alert('Tính năng chỉ đường đang được phát triển.');
            });
        }
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
