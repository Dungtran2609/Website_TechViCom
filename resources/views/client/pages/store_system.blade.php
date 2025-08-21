
@extends('client.layouts.app')

@section('title', 'Hệ thống cửa hàng - Techvicom')

@section('content')
<main class="min-h-screen bg-gradient-to-br from-yellow-50 via-orange-50 to-yellow-100 py-12 relative overflow-hidden">
    <!-- Ảnh nền mờ mờ ảo ảo -->
    <img src="{{ asset('client_css/images/store-bg.png') }}" 
         alt="Store Background" 
         class="pointer-events-none select-none absolute opacity-10 md:opacity-20 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[700px] md:w-[900px] lg:w-[1100px] z-0" 
         style="filter: blur(2px);">
    <div class="container mx-auto px-4 relative z-10">
        <!-- Header -->
        <div class="flex flex-col items-center mb-12">
            <span class="text-5xl text-orange-500 mb-2"><i class="fas fa-store"></i></span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-orange-500 drop-shadow mb-2 text-center">
                Hệ thống cửa hàng Techvicom
            </h1>
            <p class="text-lg text-gray-600 text-center max-w-2xl">
                Địa chỉ: <span class="font-semibold text-gray-800">13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</span>
            </p>
        </div>
        <!-- Content -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-center gap-12">
            <!-- Info -->
            <div class="flex-1 flex flex-col justify-center items-start md:items-end">
                <div class="bg-white/90 rounded-2xl shadow-xl p-8 w-full max-w-md">
                    <h2 class="text-2xl font-bold text-orange-500 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i> Techvicom Hà Nội
                    </h2>
                    <ul class="mb-6 space-y-2 text-gray-700 text-lg">
                        <li><i class="fas fa-phone text-orange-400 mr-2"></i>1800.6601</li>
                        <li><i class="fas fa-envelope text-orange-400 mr-2"></i>techvicom@gmail.com</li>
                        <li><i class="fas fa-clock text-orange-400 mr-2"></i>8:00 - 22:00 (T2-CN)</li>
                    </ul>
                    <a href="{{ url('/') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-full shadow-lg transition text-lg w-full text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Quay về trang chủ
                    </a>
                </div>
            </div>
            <!-- Map -->
            <div class="flex-1 w-full flex justify-center items-center">
                <div class="rounded-2xl overflow-hidden border-2 border-orange-100 shadow-xl w-full max-w-xl">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.123456789012!2d105.74468151118364!3d21.038134787375053!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zMTMgVHLhuqduIFbEg24gQsO0LCBOYW0gVMO9IExpw6ptLCBIw6AgTm9p!5e0!3m2!1svi!2s!4v1754592398398!5m2!1svi!2s"
                        width="100%" height="370" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection