@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="display-5 fw-bold">🎉 Chào mừng đến trang quản trị!</h1>
        <p class="lead text-muted">Quản lý nội dung, sản phẩm và người dùng tại đây.</p>
    </div>

    <div class="d-flex justify-content-center">
        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
            ⬅️ Quay về trang chủ
        </a>
    </div>

    <div class="mt-5 row g-4">
        
    </div>
</div>
@endsection
