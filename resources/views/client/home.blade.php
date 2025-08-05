@extends('client.layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-end align-items-center mb-4">
        @auth
            <div class="alert alert-success d-flex align-items-center mb-0" role="alert">
                <i class="bi bi-person-circle me-2 fs-5"></i>
                <div>
                    Xin chào, <strong>{{ Auth::user()->name }}</strong>! Chúc bạn một ngày tốt lành 🌞
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg shadow-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i> Vui lòng đăng nhập / đăng ký
            </a>
        @endauth
    </div>

    {{-- Nội dung chính của trang nếu có --}}
    
</div>
@endsection
