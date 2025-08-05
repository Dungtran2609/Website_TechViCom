@extends('admin.layouts.app')

@section('title', '404 - Không tìm thấy trang')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
    }
    .error-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
    }
    .error-container img {
        max-width: 300px;
        margin-bottom: 20px;
    }
</style>

<div class="container error-container">
    <img src="https://media.giphy.com/media/14uQ3cOFteDaU/giphy.gif" alt="Not Found">
    <h1 class="display-4 text-warning fw-bold">404 - Không tìm thấy trang</h1>
    <p class="lead mb-4">Rất tiếc! Trang bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
    <a href="{{ route('client.home') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left-circle me-1"></i> Quay lại trang chủ
    </a>
</div>
@endsection
