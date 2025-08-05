@extends('client.layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Thông tin tài khoản</h2>
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Họ tên:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'Chưa cập nhật' }}</p>
            <!-- Thêm các thông tin khác nếu cần -->
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Chỉnh sửa tài khoản
        </a>
        <a href="{{ route('profile.edit') }}#update-password" class="btn btn-warning">
            <i class="bi bi-key"></i> Đổi mật khẩu
        </a>
        <a href="{{ route('profile.edit') }}#update-address" class="btn btn-info">
            <i class="bi bi-geo-alt"></i> Cập nhật địa chỉ
        </a>
    </div>
</div>
@endsection

