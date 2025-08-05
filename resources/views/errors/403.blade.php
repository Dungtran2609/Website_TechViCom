@extends('admin.layouts.app')

@section('title', '403 - Không có quyền truy cập')

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
        max-width: 250px;
        margin-bottom: 20px;
    }
</style>

<div class="container error-container">
    <img src="https://media.giphy.com/media/UoeaPqYrimha6rdTFV/giphy.gif" alt="Access Denied">
    <h1 class="display-4 text-danger fw-bold">403 - Cấm truy cập</h1>
    <p class="lead mb-4">Bạn không có quyền truy cập vào trang này hoặc hành động này bị hạn chế.</p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
        <i class="bi bi-arrow-left-circle me-1"></i> Quay lại trang chủ
    </a>
</div>
@endsection
