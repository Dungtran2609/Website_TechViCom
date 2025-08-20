@extends('client.layouts.app')

@section('title', 'Giới thiệu - Techvicom')

@section('content')
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-orange-500 to-red-500 text-white py-20">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">
                    <i class="fas fa-building mr-4"></i>
                    Về Techvicom
                </h1>
                <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
                    Hệ thống bán lẻ công nghệ chính hãng hàng đầu Việt Nam
                </p>
            </div>
        </section>

        <!-- Company Overview -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-800 mb-6">Chúng tôi là ai?</h2>
                        <div class="w-24 h-1 bg-orange-500 mx-auto"></div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Sứ mệnh của Techvicom</h3>
                            <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                                Techvicom là hệ thống bán lẻ các sản phẩm công nghệ chính hãng hàng đầu Việt Nam, 
                                chuyên cung cấp điện thoại, laptop, tablet, phụ kiện và các thiết bị thông minh từ 
                                các thương hiệu uy tín như Apple, Samsung, Xiaomi, Dell, HP, v.v.
                            </p>
                            <p class="text-lg text-gray-600 leading-relaxed">
                                Với phương châm <strong class="text-orange-500">"Khách hàng là trung tâm"</strong>, 
                                Techvicom luôn nỗ lực mang đến cho khách hàng trải nghiệm mua sắm tốt nhất với 
                                giá cả cạnh tranh, dịch vụ chuyên nghiệp và hậu mãi tận tâm.
                            </p>
                        </div>
                        <div class="relative">
                            <div class="bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl p-8">
                                <div class="text-center">
                                    <i class="fas fa-rocket text-6xl text-orange-500 mb-4"></i>
                                    <h4 class="text-xl font-bold text-gray-800 mb-2">Định hướng tương lai</h4>
                                    <p class="text-gray-600">Không ngừng đổi mới để phục vụ khách hàng ngày càng tốt hơn</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Core Values -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-800 mb-6">Giá trị cốt lõi</h2>
                        <div class="w-24 h-1 bg-orange-500 mx-auto"></div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Value 1 -->
                        <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-shield-alt text-2xl text-orange-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">Chính hãng 100%</h3>
                                <p class="text-gray-600">Sản phẩm bảo hành minh bạch, nguồn gốc rõ ràng</p>
                            </div>
                        </div>

                        <!-- Value 2 -->
                        <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-2xl text-orange-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">Tư vấn chuyên nghiệp</h3>
                                <p class="text-gray-600">Đội ngũ tư vấn nhiệt tình, am hiểu sản phẩm</p>
                            </div>
                        </div>

                        <!-- Value 3 -->
                        <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-shipping-fast text-2xl text-orange-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">Giao hàng nhanh</h3>
                                <p class="text-gray-600">Giao hàng toàn quốc với thời gian nhanh chóng</p>
                            </div>
                        </div>

                        <!-- Value 4 -->
                        <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-exchange-alt text-2xl text-orange-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">Đổi trả linh hoạt</h3>
                                <p class="text-gray-600">Chính sách đổi trả linh hoạt, bảo vệ quyền lợi khách hàng</p>
                            </div>
                        </div>

                        <!-- Value 5 -->
                        <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-credit-card text-2xl text-orange-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">Trả góp 0%</h3>
                                <p class="text-gray-600">Hỗ trợ trả góp lãi suất thấp, thủ tục đơn giản</p>
                            </div>
                        </div>

                        <!-- Value 6 -->
                        <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-headset text-2xl text-orange-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">Hỗ trợ 24/7</h3>
                                <p class="text-gray-600">Dịch vụ khách hàng chuyên nghiệp, hỗ trợ mọi lúc</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Call to Action -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">Sẵn sàng trải nghiệm?</h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Hãy để Techvicom đồng hành cùng bạn trong hành trình công nghệ
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('home') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 hover:scale-105">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Mua sắm ngay
                        </a>
                        <a href="{{ route('client.contacts.index') }}" class="border-2 border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 hover:scale-105">
                            <i class="fas fa-phone mr-2"></i>
                            Liên hệ tư vấn
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
