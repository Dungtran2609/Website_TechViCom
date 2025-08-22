@extends('client.layouts.app')

@section('title', 'Liên hệ - Techvicom')

@section('content')
    <div class="bg-white py-4 shadow mb-8">
        <div class="items-center text-sm container mx-auto px-4">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-custom-primary">Trang chủ</a>
            <span class="mx-2 text-gray-400">&gt;</span>
            <span class="font-semibold text-green-700">Liên hệ</span>
        </div>
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-extrabold text-custom-primary mb-2 tracking-tight">Liên hệ với TechViCom</h1>
                    <p class="text-lg text-gray-500">Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Contact Form -->
                    <div class="bg-white rounded-2xl shadow-xl p-10 border border-gray-100">
                        <h2 class="text-2xl font-bold text-custom-primary mb-6">Gửi tin nhắn cho chúng tôi</h2>

                        @if(session('success'))
                            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Thành công!</strong>
                                <span class="block sm:inline">{{ session('success') }}</span>
                                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <title>Đóng</title>
                                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                                    </svg>
                                </span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Lỗi!</strong>
                                <span class="block sm:inline">{{ session('error') }}</span>
                                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <title>Đóng</title>
                                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                                    </svg>
                                </span>
                            </div>
                        @endif



                        <form class="space-y-6" method="POST" action="{{ route('client.contacts.store') }}" id="contactForm">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Họ và tên *</label>
                                    <div class="relative">
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                            class="w-full px-4 py-3 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:border-custom-primary">
                                        @if($errors->has('name'))
                                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-500">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </span>
                                        @endif
                                    </div>
                                    @if($errors->has('name'))
                                        <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại *</label>
                                    <div class="relative">
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" 
                                            pattern="^(0|\+84)([0-9]{9,10})$"
                                            title="Vui lòng nhập số điện thoại Việt Nam hợp lệ (bắt đầu bằng 0 hoặc +84, 10-11 số)"
                                            class="w-full px-4 py-3 border {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:border-custom-primary">
                                        @if($errors->has('phone'))
                                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-500">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </span>
                                        @endif
                                    </div>
                                    @if($errors->has('phone'))
                                        <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                                <div class="relative">
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                        title="Vui lòng nhập địa chỉ email hợp lệ (ví dụ: example@domain.com)"
                                        class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:border-custom-primary">
                                    @if($errors->has('email'))
                                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-500">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>
                                    @endif
                                </div>
                                @if($errors->has('email'))
                                    <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Chủ đề *</label>
                                <div class="relative">
                                    <select name="subject" id="subject" 
                                        class="w-full px-4 py-3 border {{ $errors->has('subject') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:border-custom-primary">
                                        <option value="" disabled selected>Chọn chủ đề</option>
                                        <option value="Hỗ trợ sản phẩm" {{ old('subject') == 'Hỗ trợ sản phẩm' ? 'selected' : '' }}>Hỗ trợ sản phẩm</option>
                                        <option value="Đơn hàng và giao hàng" {{ old('subject') == 'Đơn hàng và giao hàng' ? 'selected' : '' }}>Đơn hàng và giao hàng</option>
                                        <option value="Bảo hành" {{ old('subject') == 'Bảo hành' ? 'selected' : '' }}>Bảo hành</option>
                                        <option value="Khiếu nại" {{ old('subject') == 'Khiếu nại' ? 'selected' : '' }}>Khiếu nại</option>
                                        <option value="Phản hồi" {{ old('subject') == 'Phản hồi' ? 'selected' : '' }}>Góp ý</option>
                                        <option value="Khác" {{ old('subject') == 'Khác' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @if($errors->has('subject'))
                                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-500">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>
                                    @endif
                                </div>
                                @if($errors->has('subject'))
                                    <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nội dung *</label>
                                <div class="relative">
                                    <textarea rows="5" name="message" id="message" 
                                        class="w-full px-4 py-3 border {{ $errors->has('message') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:border-custom-primary"
                                        placeholder="Nhập nội dung tin nhắn...">{{ old('message') }}</textarea>
                                    @if($errors->has('message'))
                                        <span class="absolute right-3 top-3 text-red-500">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>
                                    @endif
                                </div>
                                @if($errors->has('message'))
                                    <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('message') }}</span>
                                @endif
                            </div>

                            <button type="submit"
                                class="w-full py-4 rounded-2xl font-bold text-lg shadow-lg transition-all duration-200 
                                @if(auth()->check() && $hasReachedLimit) 
                                    bg-gray-400 text-gray-600 cursor-not-allowed 
                                @else 
                                    bg-[#ff6c2f] text-white hover:bg-[#e55a28] 
                                @endif"
                                @if(auth()->check() && $hasReachedLimit) disabled @endif>
                                @if(auth()->check() && $hasReachedLimit)
                                    Đã đạt giới hạn liên hệ
                                @else
                                    Gửi tin nhắn
                                @endif
                            </button>
                        </form>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <div class="bg-white rounded-2xl shadow-xl p-10 border border-gray-100 mb-8">
                            <h2 class="text-2xl font-bold text-custom-primary mb-6">Thông tin liên hệ</h2>
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <div class="bg-custom-primary/10 p-3 rounded-full mr-4">
                                        <i class="fas fa-phone text-custom-primary"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">Hotline</h3>
                                        <p class="text-gray-600">1800.6601 (Miễn phí)</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-custom-primary/10 p-3 rounded-full mr-4">
                                        <i class="fas fa-envelope text-custom-primary"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">Email</h3>
                                        <p class="text-gray-600">techvicom@gmail.com</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-custom-primary/10 p-3 rounded-full mr-4">
                                        <i class="fas fa-map-marker-alt text-custom-primary"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">Địa chỉ</h3>
                                        <p class="text-gray-600">13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-custom-primary/10 p-3 rounded-full mr-4">
                                        <i class="fas fa-clock text-custom-primary"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">Giờ làm việc</h3>
                                        <p class="text-gray-600">8:00 - 22:00 (Tất cả các ngày)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Social Media -->
                        <div class="bg-white rounded-2xl shadow-xl p-10 border border-gray-100">
                            <h2 class="text-2xl font-bold text-custom-primary mb-6">Kết nối với chúng tôi</h2>
                            <div class="flex space-x-4">
                                <a href="https://www.facebook.com/profile.php?id=61579355081161"
                                    class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://studio.youtube.com/channel/UCgjtfk_OjrfdyQNJphIMxmg"
                                    class="bg-red-600 text-white p-3 rounded-full hover:bg-red-700 transition">
                                    {{-- youtube --}}
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="#"
                                    class="bg-pink-600 text-white p-3 rounded-full hover:bg-pink-700 transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="bg-black text-white p-3 rounded-full hover:bg-gray-800 transition">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                                <a href="#"
                                    class="bg-custom-primary text-white p-3 rounded-full hover:bg-custom-primary-dark transition">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(auth()->check() && $hasReachedLimit)
        // Disable toàn bộ form khi đã đạt giới hạn
        const form = document.getElementById('contactForm');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.disabled = true;
            input.classList.add('bg-gray-100', 'cursor-not-allowed');
        });
    @endif

    // Auto hide success/error messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endpush
