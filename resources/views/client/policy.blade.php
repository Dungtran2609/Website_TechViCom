@extends('client.layouts.app')

@section('title', 'Chính sách - Techvicom')

@section('content')
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-orange-500 to-yellow-600 text-white py-20">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">
                    <i class="fas fa-file-contract mr-4"></i>
                    Chính sách Techvicom
                </h1>
                <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
                    Cam kết minh bạch, bảo vệ quyền lợi khách hàng
                </p>
            </div>
        </section>

        <!-- Policy Navigation -->
        <section class="py-8 bg-white border-b">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#shipping" class="bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition-all duration-300">
                        <i class="fas fa-shipping-fast mr-2"></i>
                        Giao hàng
                    </a>
                    <a href="#return" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-all duration-300">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Đổi trả
                    </a>
                    <a href="#warranty" class="bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 transition-all duration-300">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Bảo hành
                    </a>
                    <a href="#privacy" class="bg-purple-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-600 transition-all duration-300">
                        <i class="fas fa-user-shield mr-2"></i>
                        Bảo mật
                    </a>
                </div>
            </div>
        </section>

        <!-- Shipping Policy -->
        <section id="shipping" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-shipping-fast text-3xl text-blue-500"></i>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">Chính sách giao hàng</h2>
                        <div class="w-24 h-1 bg-blue-500 mx-auto"></div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-2xl p-8 mb-8">
                        <h3 class="text-2xl font-bold text-blue-800 mb-4">Thời gian giao hàng</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl p-6">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-map-marker-alt text-blue-500 mr-3"></i>
                                    <h4 class="font-semibold text-gray-800">Nội thành Hà Nội</h4>
                                </div>
                                <p class="text-gray-600">Giao hàng trong ngày (8:00 - 22:00)</p>
                                <p class="text-blue-600 font-semibold">Phí vận chuyển: 0đ</p>
                            </div>
                            <div class="bg-white rounded-xl p-6">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-truck text-blue-500 mr-3"></i>
                                    <h4 class="font-semibold text-gray-800">Ngoại thành Hà Nội</h4>
                                </div>
                                <p class="text-gray-600">Giao hàng từ 1-2 ngày làm việc</p>
                                <p class="text-blue-600 font-semibold">Phí vận chuyển: 30.000đ</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Quy trình giao hàng</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center mr-4 mt-1">1</div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Xác nhận đơn hàng</h4>
                                    <p class="text-gray-600">Nhân viên sẽ gọi xác nhận đơn hàng trong vòng 30 phút</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center mr-4 mt-1">2</div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Chuẩn bị giao hàng</h4>
                                    <p class="text-gray-600">Kiểm tra và đóng gói sản phẩm cẩn thận</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center mr-4 mt-1">3</div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Giao hàng tận nơi</h4>
                                    <p class="text-gray-600">Nhân viên giao hàng sẽ liên hệ trước khi đến</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Return Policy -->
        <section id="return" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-exchange-alt text-3xl text-green-500"></i>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">Chính sách đổi trả</h2>
                        <div class="w-24 h-1 bg-green-500 mx-auto"></div>
                    </div>
                    
                    <div class="bg-green-50 rounded-2xl p-8 mb-8">
                        <h3 class="text-2xl font-bold text-green-800 mb-4">Điều kiện đổi trả</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl p-6">
                                <h4 class="font-semibold text-gray-800 mb-3 text-green-700">Có thể đổi trả</h4>
                                <ul class="text-gray-600 space-y-2">
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        Sản phẩm bị lỗi do nhà sản xuất
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        Không đúng mô tả trên website
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        Bị hư hỏng trong quá trình vận chuyển
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-white rounded-xl p-6">
                                <h4 class="font-semibold text-gray-800 mb-3 text-red-700">Không thể đổi trả</h4>
                                <ul class="text-gray-600 space-y-2">
                                    <li class="flex items-center">
                                        <i class="fas fa-times text-red-500 mr-2"></i>
                                        Sản phẩm đã qua sử dụng
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-times text-red-500 mr-2"></i>
                                        Thiếu phụ kiện, hóa đơn
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-times text-red-500 mr-2"></i>
                                        Hết thời hạn đổi trả
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Thời gian và quy trình</h3>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clock text-2xl text-green-500"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-2">Thời gian</h4>
                                <p class="text-gray-600">7 ngày kể từ ngày nhận hàng</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-phone text-2xl text-green-500"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-2">Liên hệ</h4>
                                <p class="text-gray-600">Gọi 1800.6601 để được hỗ trợ</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-sync text-2xl text-green-500"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-2">Xử lý</h4>
                                <p class="text-gray-600">Hoàn thành trong 24-48 giờ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Warranty Policy -->
        <section id="warranty" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-shield-alt text-3xl text-purple-500"></i>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">Chính sách bảo hành</h2>
                        <div class="w-24 h-1 bg-purple-500 mx-auto"></div>
                    </div>
                    
                    <div class="bg-purple-50 rounded-2xl p-8 mb-8">
                        <h3 class="text-2xl font-bold text-purple-800 mb-4">Thời gian bảo hành</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl p-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Điện thoại & Tablet</h4>
                                <p class="text-2xl font-bold text-purple-600 mb-2">12 tháng</p>
                                <p class="text-gray-600">Bảo hành chính hãng toàn quốc</p>
                            </div>
                            <div class="bg-white rounded-xl p-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Các sản phẩm khác</h4>
                                <p class="text-2xl font-bold text-purple-600 mb-2">24 tháng</p>
                                <p class="text-gray-600">Bảo hành chính hãng toàn quốc</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Dịch vụ bảo hành</h3>
                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-white rounded-lg">
                                <i class="fas fa-tools text-purple-500 mr-4 text-xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Sửa chữa tại chỗ</h4>
                                    <p class="text-gray-600">Dịch vụ sửa chữa nhanh chóng, chuyên nghiệp</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-white rounded-lg">
                                <i class="fas fa-shipping-fast text-purple-500 mr-4 text-xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Vận chuyển miễn phí</h4>
                                    <p class="text-gray-600">Vận chuyển sản phẩm bảo hành miễn phí</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-white rounded-lg">
                                <i class="fas fa-headset text-purple-500 mr-4 text-xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Hỗ trợ 24/7</h4>
                                    <p class="text-gray-600">Tư vấn và hỗ trợ bảo hành mọi lúc</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Privacy Policy -->
        <section id="privacy" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-user-shield text-3xl text-indigo-500"></i>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">Chính sách bảo mật</h2>
                        <div class="w-24 h-1 bg-indigo-500 mx-auto"></div>
                    </div>
                    
                    <div class="bg-indigo-50 rounded-2xl p-8 mb-8">
                        <h3 class="text-2xl font-bold text-indigo-800 mb-4">Cam kết bảo mật</h3>
                        <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                            Techvicom cam kết bảo mật tuyệt đối thông tin cá nhân của khách hàng. 
                            Chúng tôi chỉ sử dụng thông tin cho mục đích giao dịch và chăm sóc khách hàng, 
                            không chia sẻ cho bên thứ ba khi chưa có sự đồng ý của khách hàng.
                        </p>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl p-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Thông tin được bảo mật</h4>
                                <ul class="text-gray-600 space-y-2">
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-indigo-500 mr-2"></i>
                                        Thông tin cá nhân
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-indigo-500 mr-2"></i>
                                        Thông tin thanh toán
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-indigo-500 mr-2"></i>
                                        Lịch sử mua hàng
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-white rounded-xl p-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Biện pháp bảo mật</h4>
                                <ul class="text-gray-600 space-y-2">
                                    <li class="flex items-center">
                                        <i class="fas fa-lock text-indigo-500 mr-2"></i>
                                        Mã hóa SSL 256-bit
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-shield-alt text-indigo-500 mr-2"></i>
                                        Firewall bảo vệ
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-user-lock text-indigo-500 mr-2"></i>
                                        Kiểm soát truy cập
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Quyền của khách hàng</h3>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-eye text-2xl text-indigo-500"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-2">Quyền truy cập</h4>
                                <p class="text-gray-600">Xem và cập nhật thông tin cá nhân</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-edit text-2xl text-indigo-500"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-2">Quyền chỉnh sửa</h4>
                                <p class="text-gray-600">Sửa đổi thông tin không chính xác</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-trash text-2xl text-indigo-500"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-2">Quyền xóa</h4>
                                <p class="text-gray-600">Yêu cầu xóa thông tin cá nhân</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-16 bg-gradient-to-r from-gray-800 to-gray-900 text-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-4xl font-bold mb-6">Cần hỗ trợ thêm?</h2>
                    <p class="text-xl opacity-90 mb-8">
                        Đội ngũ chăm sóc khách hàng của chúng tôi luôn sẵn sàng hỗ trợ bạn
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="tel:18006601" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 hover:scale-105">
                            <i class="fas fa-phone mr-2"></i>
                            1800.6601
                        </a>
                        <a href="mailto:support@techvicom.vn" class="border-2 border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 hover:scale-105">
                            <i class="fas fa-envelope mr-2"></i>
                            Email hỗ trợ
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
