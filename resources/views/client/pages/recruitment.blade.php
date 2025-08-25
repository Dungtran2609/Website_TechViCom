@extends('client.layouts.app')

@section('title', 'Tuyển dụng - Techvicom')

@section('content')
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-orange-500 via-red-500 to-orange-600 text-white py-20 relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="absolute inset-0">
                <div class="absolute top-10 left-10 w-20 h-20 bg-white opacity-10 rounded-full"></div>
                <div class="absolute top-20 right-20 w-32 h-32 bg-white opacity-5 rounded-full"></div>
                <div class="absolute bottom-10 left-1/4 w-16 h-16 bg-white opacity-10 rounded-full"></div>
            </div>
            <div class="container mx-auto px-4 text-center relative z-10">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-pulse">
                    <i class="fas fa-users-cog mr-4"></i>
                    Tuyển dụng
                </h1>
                <p class="text-xl md:text-2xl opacity-90 max-w-4xl mx-auto mb-8">
                    Tham gia cùng chúng tôi xây dựng tương lai công nghệ
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                                         <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-6 py-3">
                         <i class="fas fa-map-marker-alt mr-2"></i>
                         <span>Hà Nội</span>
                     </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-6 py-3">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Toàn thời gian</span>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-6 py-3">
                        <i class="fas fa-star mr-2"></i>
                        <span>Môi trường trẻ</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Join Us -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-800 mb-6">Tại sao chọn Techvicom?</h2>
                        <div class="w-24 h-1 bg-orange-500 mx-auto"></div>
                        <p class="text-lg text-gray-600 mt-6 max-w-3xl mx-auto">
                            Chúng tôi không chỉ là nơi làm việc, mà còn là nơi bạn phát triển sự nghiệp và thực hiện ước mơ
                        </p>
                    </div>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Benefit 1 -->
                        <div class="text-center group">
                            <div class="w-20 h-20 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-graduation-cap text-2xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Đào tạo liên tục</h3>
                            <p class="text-gray-600">Cơ hội học hỏi và phát triển kỹ năng chuyên môn</p>
                        </div>

                        <!-- Benefit 2 -->
                        <div class="text-center group">
                            <div class="w-20 h-20 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-chart-line text-2xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Thăng tiến rõ ràng</h3>
                            <p class="text-gray-600">Lộ trình thăng tiến minh bạch và cơ hội thăng cấp</p>
                        </div>

                        <!-- Benefit 3 -->
                        <div class="text-center group">
                            <div class="w-20 h-20 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-heart text-2xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Phúc lợi hấp dẫn</h3>
                            <p class="text-gray-600">Bảo hiểm, thưởng, du lịch và nhiều quyền lợi khác</p>
                        </div>

                        <!-- Benefit 4 -->
                        <div class="text-center group">
                            <div class="w-20 h-20 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-users text-2xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Môi trường trẻ</h3>
                            <p class="text-gray-600">Làm việc trong môi trường năng động, sáng tạo</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Current Openings -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-800 mb-6">Vị trí đang tuyển</h2>
                        <div class="w-24 h-1 bg-orange-500 mx-auto"></div>
                    </div>
                    
                    <div class="grid lg:grid-cols-2 gap-8">
                        <!-- Job 1 -->
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border-l-4 border-orange-500">
                            <div class="p-8">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Nhân viên bán hàng</h3>
                                        <p class="text-orange-500 font-semibold">Sales Executive</p>
                                    </div>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Đang tuyển</span>
                                </div>
                                
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt text-orange-500 mr-3 w-5"></i>
                                        <span>Hà Nội</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-clock text-orange-500 mr-3 w-5"></i>
                                        <span>Toàn thời gian</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-dollar-sign text-orange-500 mr-3 w-5"></i>
                                        <span>15-25 triệu VNĐ/tháng</span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-6">
                                    Tư vấn và bán các sản phẩm công nghệ cho khách hàng, đảm bảo doanh số và sự hài lòng của khách hàng.
                                </p>
                                
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Giao tiếp tốt</span>
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Kinh nghiệm bán hàng</span>
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Am hiểu công nghệ</span>
                                </div>
                                
                                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
                                    Ứng tuyển ngay
                                </button>
                            </div>
                        </div>

                        <!-- Job 2 -->
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border-l-4 border-orange-500">
                            <div class="p-8">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Nhân viên kỹ thuật</h3>
                                        <p class="text-orange-500 font-semibold">Technical Support</p>
                                    </div>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Đang tuyển</span>
                                </div>
                                
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt text-orange-500 mr-3 w-5"></i>
                                        <span>Hà Nội</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-clock text-orange-500 mr-3 w-5"></i>
                                        <span>Toàn thời gian</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-dollar-sign text-orange-500 mr-3 w-5"></i>
                                        <span>20-35 triệu VNĐ/tháng</span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-6">
                                    Hỗ trợ kỹ thuật cho khách hàng, sửa chữa và bảo hành sản phẩm công nghệ.
                                </p>
                                
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Kỹ thuật viên</span>
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Sửa chữa</span>
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Bảo hành</span>
                                </div>
                                
                                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
                                    Ứng tuyển ngay
                                </button>
                            </div>
                        </div>

                        <!-- Job 3 -->
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border-l-4 border-orange-500">
                            <div class="p-8">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Nhân viên Marketing</h3>
                                        <p class="text-orange-500 font-semibold">Marketing Specialist</p>
                                    </div>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Đang tuyển</span>
                                </div>
                                
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt text-orange-500 mr-3 w-5"></i>
                                        <span>Hà Nội</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-clock text-orange-500 mr-3 w-5"></i>
                                        <span>Toàn thời gian</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-dollar-sign text-orange-500 mr-3 w-5"></i>
                                        <span>18-30 triệu VNĐ/tháng</span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-6">
                                    Xây dựng và thực hiện các chiến dịch marketing, quản lý mạng xã hội và tăng cường thương hiệu.
                                </p>
                                
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Digital Marketing</span>
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Social Media</span>
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Content Creation</span>
                                </div>
                                
                                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
                                    Ứng tuyển ngay
                                </button>
                            </div>
                        </div>

                        <!-- Job 4 -->
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border-l-4 border-orange-500">
                            <div class="p-8">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Nhân viên kho vận</h3>
                                        <p class="text-orange-500 font-semibold">Warehouse Staff</p>
                                    </div>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Đang tuyển</span>
                                </div>
                                
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt text-orange-500 mr-3 w-5"></i>
                                        <span>Hà Nội</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-clock text-orange-500 mr-3 w-5"></i>
                                        <span>Toàn thời gian</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-dollar-sign text-orange-500 mr-3 w-5"></i>
                                        <span>12-18 triệu VNĐ/tháng</span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-6">
                                    Quản lý kho hàng, xuất nhập hàng hóa, kiểm tra chất lượng sản phẩm và đóng gói.
                                </p>
                                
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Quản lý kho</span>
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Xuất nhập</span>
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">Đóng gói</span>
                                </div>
                                
                                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
                                    Ứng tuyển ngay
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Application Process -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-800 mb-6">Quy trình ứng tuyển</h2>
                        <div class="w-24 h-1 bg-orange-500 mx-auto"></div>
                    </div>
                    
                    <div class="grid md:grid-cols-4 gap-8">
                        <!-- Step 1 -->
                        <div class="text-center relative">
                            <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white font-bold text-xl">1</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Nộp hồ sơ</h3>
                            <p class="text-gray-600">Gửi CV và thông tin cá nhân qua email hoặc form online</p>
                            <div class="hidden md:block absolute top-8 left-full w-full h-0.5 bg-orange-200 transform translate-x-4"></div>
                        </div>

                        <!-- Step 2 -->
                        <div class="text-center relative">
                            <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white font-bold text-xl">2</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Sàng lọc</h3>
                            <p class="text-gray-600">HR sẽ xem xét và liên hệ với ứng viên phù hợp</p>
                            <div class="hidden md:block absolute top-8 left-full w-full h-0.5 bg-orange-200 transform translate-x-4"></div>
                        </div>

                        <!-- Step 3 -->
                        <div class="text-center relative">
                            <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white font-bold text-xl">3</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Phỏng vấn</h3>
                            <p class="text-gray-600">Tham gia phỏng vấn với HR và trưởng bộ phận</p>
                            <div class="hidden md:block absolute top-8 left-full w-full h-0.5 bg-orange-200 transform translate-x-4"></div>
                        </div>

                        <!-- Step 4 -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white font-bold text-xl">4</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Nhận việc</h3>
                            <p class="text-gray-600">Ký hợp đồng và bắt đầu công việc mới</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-16 bg-gradient-to-r from-orange-500 to-red-500 text-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-4xl font-bold mb-6">Sẵn sàng tham gia cùng chúng tôi?</h2>
                    <p class="text-xl mb-8 opacity-90">
                        Gửi CV và thông tin của bạn để chúng tôi có thể liên hệ sớm nhất
                    </p>
                    
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-6">
                            <i class="fas fa-envelope text-3xl mb-4"></i>
                            <h3 class="text-xl font-bold mb-2">Email</h3>
                            <p class="opacity-90">hr@techvicom.com</p>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-6">
                            <i class="fas fa-phone text-3xl mb-4"></i>
                            <h3 class="text-xl font-bold mb-2">Điện thoại</h3>
                            <p class="opacity-90">1800.6601</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button class="bg-white text-orange-500 hover:bg-gray-100 font-semibold py-4 px-8 rounded-lg transition-colors duration-300">
                            <i class="fas fa-download mr-2"></i>
                            Tải mẫu CV
                        </button>
                        <button class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-orange-500 font-semibold py-4 px-8 rounded-lg transition-all duration-300">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Gửi hồ sơ ngay
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Company Culture -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-800 mb-6">Văn hóa công ty</h2>
                        <div class="w-24 h-1 bg-orange-500 mx-auto"></div>
                    </div>
                    
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="bg-white rounded-xl p-8 shadow-lg text-center">
                            <i class="fas fa-lightbulb text-4xl text-orange-500 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Sáng tạo</h3>
                            <p class="text-gray-600">Khuyến khích ý tưởng mới và cách tiếp cận sáng tạo</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-8 shadow-lg text-center">
                            <i class="fas fa-handshake text-4xl text-orange-500 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Hợp tác</h3>
                            <p class="text-gray-600">Làm việc nhóm hiệu quả và hỗ trợ lẫn nhau</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-8 shadow-lg text-center">
                            <i class="fas fa-trophy text-4xl text-orange-500 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Thành công</h3>
                            <p class="text-gray-600">Hướng đến kết quả và thành công chung</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
