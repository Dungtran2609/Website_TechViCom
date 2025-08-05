@extends('client.layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Chỉnh sửa tài khoản</h2>
    {{-- Thêm form chỉnh sửa thông tin tài khoản ở đây --}}
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', Auth::user()->address) }}">
        </div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
    {{-- Thêm các form đổi mật khẩu, cập nhật địa chỉ nếu cần --}}
</div>
@endsection