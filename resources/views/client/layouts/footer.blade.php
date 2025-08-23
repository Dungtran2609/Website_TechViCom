<!-- Footer -->
<footer class="bg-gray-900 text-white">
    <!-- Banner hệ thống cửa hàng -->
    <div class="bg-gray-800 py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-lg font-bold text-white mb-2">Hệ thống Techvicom trên toàn quốc</h3>
                    <p class="text-gray-300 text-sm">Bao gồm Cửa hàng Techvicom, Trung tâm Điện máy, Trung tâm Laptop, Techvicom Studio, Đại lý ủy quyền, Dự án doanh nghiệp</p>
                </div>
                <div>
                    <a href="{{ route('client.invoice.index') }}" class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-300">
                        <i class="fas fa-search mr-2"></i>
                        Tra cứu đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Nội dung footer chính -->
    <div class="py-12">
        <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Logo và thông tin công ty -->
            <div class="col-span-1">
                <div class="flex items-center mb-4">
                    @php
                        $clientLogo = \App\Models\Logo::where('type', 'client')->orderByDesc('id')->first();
                    @endphp
                    <img src="{{ $clientLogo ? asset('storage/' . $clientLogo->path) : asset('admin_css/images/logo_techvicom.png') }}" alt="{{ $clientLogo->alt ?? 'Techvicom' }}" class="w-12 h-12 rounded-lg mr-3 object-cover">
                    <div>
                        <h2 class="text-xl font-bold text-white">Techvicom</h2>
                    </div>
                </div>
                <p class="text-orange-400 text-sm mb-4 italic">Công nghệ cho mọi nhà - Giá tốt mỗi ngày</p>
                
                <!-- Social Icons -->
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/profile.php?id=61579355081161" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-blue-600 transition">
                        <i class="fab fa-facebook text-white"></i>
                    </a>
                    <a href="https://studio.youtube.com/channel/UCgjtfk_OjrfdyQNJphIMxmg" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-[#ff6c2f] transition">
                        <i class="fab fa-youtube text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-black transition">
                        <i class="fab fa-tiktok text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-pink-600 transition">
                        <i class="fab fa-instagram text-white"></i>
                    </a>
                </div>
            </div>

            <!-- Dịch vụ khách hàng -->
            <div>
                <h3 class="font-bold text-lg mb-4 text-orange-400">Dịch vụ khách hàng</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/policy" class="hover:text-orange-400 transition">Chính sách bảo hành</a></li>
                    <li><a href="/policy" class="hover:text-orange-400 transition">Chính sách đổi trả</a></li>
                    <li><a href="/policy" class="hover:text-orange-400 transition">Chính sách vận chuyển</a></li>
                    <li><a href="/policy" class="hover:text-orange-400 transition">Chính sách trả góp</a></li>
                    <li><a href="/warranty" class="hover:text-orange-400 transition">Tra cứu bảo hành</a></li>
                    <li><a href="{{ route('client.invoice.index') }}" class="hover:text-orange-400 transition">Tra cứu hóa đơn</a></li>
                </ul>
            </div>

            <!-- Về Techvicom -->
            <div>
                
                <h3 class="font-bold text-lg mb-4 text-orange-400">Về Techvicom</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="about" class="hover:text-orange-400 transition">Giới thiệu công ty</a></li>
                    <li><a href="{{ route('client.news.index') }}" class="hover:text-orange-400 transition">Tin tức & sự kiện</a></li>
                    <li><a href="{{ route('recruitment') }}" class="hover:text-orange-400 transition">Tuyển dụng</a></li>
                    <li><a href="{{ route('client.contacts.index') }}" class="hover:text-orange-400 transition">Liên hệ</a></li>
                    <li><a href="{{ route('client.store_system') }}" class="hover:text-orange-400 transition">Hệ thống cửa hàng</a></li>
                    <li><a href="{{ route('authorized_dealer') }}" class="hover:text-orange-400 transition">Đại lý ủy quyền</a></li>
                    <li><a href="{{ route('enterprise_project') }}" class="hover:text-orange-400 transition">Dự án doanh nghiệp</a></li>
                </ul>
            </div>

            <!-- Vị trí của chúng tôi -->
            <div>
                <h3 class="font-bold text-lg mb-4 text-orange-400">Vị trí của chúng tôi</h3>
                <div class="bg-gray-800 rounded-lg p-3 mb-4">
                    <div class="relative">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.863806021138!2d105.74468151118364!3d21.038134787375053!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1754592398398!5m2!1svi!2s" 
                                width="100%" 
                                height="150" 
                                style="border:0; border-radius: 0.5rem;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <p class="text-xs mt-2 text-gray-300">
                        <a href="https://www.google.com/maps/place/13+Tr%E1%BB%8Bnh+V%C4%83n+B%C3%B4,+Nam+T%E1%BB%AB+Li%C3%AAm,+H%C3%A0+N%E1%BB%99i/@21.0381348,105.7446815,17z/data=!3m1!4b1!4m6!3m5!1s0x313455e940879933:0xcf10b34e9f1a03df!8m2!3d21.0381348!4d105.7472564!16s%2Fm%2F02q53ly?entry=ttu&g_ep=EgoyMDI1MDEwOC4wIKXMDSoASAFQAw%3D%3D" 
                           target="_blank" 
                           class="text-orange-400 hover:text-orange-300 transition">
                            Xem bản đồ lớn hơn
                        </a>
                    </p>
                </div>
                
                <!-- Thông tin liên hệ -->
                <div class="text-sm space-y-1">
                    <p><i class="fas fa-map-marker-alt text-orange-400 mr-2"></i>13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</p>
                    <p><i class="fas fa-phone text-orange-400 mr-2"></i>1800.6601</p>
                    <p><i class="fas fa-envelope text-orange-400 mr-2"></i>techvicom@gmail.com</p>
                    <p><i class="fas fa-clock text-orange-400 mr-2"></i>8:00 - 22:00 (T2-CN)</p>
                    <p class="text-xs text-gray-400 mt-3 italic">
                        <i class="fas fa-info-circle text-orange-400 mr-1"></i>
                        Bản đồ được cung cấp bởi Google Maps
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-700 mt-8 pt-6 text-center">
            <p class="text-gray-400 text-sm">&copy; 2025 Techvicom. All rights reserved.</p>
        </div>
        </div>
    </div>
</footer>