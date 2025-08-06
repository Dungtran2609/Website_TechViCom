@extends('admin.layouts.app')

@section('title', 'Hồ sơ của tôi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Cột thông tin cơ bản bên trái -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    {{-- Hiển thị ảnh đại diện, có ảnh mặc định nếu không tồn tại --}}
                    <img src="{{ $admin->image_profile ? asset('storage/' . $admin->image_profile) : 'https://via.placeholder.com/150' }}"
                         alt="Ảnh đại diện của {{ $admin->name }}"
                         class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">

                    <h5 class="my-3">{{ $admin->name }}</h5>
                    <p class="text-muted mb-1">{{ $admin->email }}</p>

                    {{-- Hiển thị vai trò --}}
                     <div class="my-3">
                        @forelse ($admin->roles as $role)
                            <span class="badge bg-success">{{ $role->name }}</span>
                        @empty
                            <span class="badge bg-secondary">Chưa có vai trò</span>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center mb-2">
                        {{-- Nút này sẽ dẫn đến trang chỉnh sửa (bạn có thể tạo sau) --}}
                        <a href="#" class="btn btn-primary">Chỉnh sửa hồ sơ</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột thông tin chi tiết bên phải -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Thông tin chi tiết</h5>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Họ và tên</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ $admin->name }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ $admin->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Số điện thoại</p>
                        </div>
                        <div class="col-sm-9">
                            {{-- Sử dụng toán tử ?? để hiển thị text mặc định nếu giá trị là null --}}
                            <p class="text-muted mb-0">{{ $admin->phone_number ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Ngày sinh</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ $admin->birthday ? \Carbon\Carbon::parse($admin->birthday)->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                     <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Ngày tham gia</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ $admin->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection```
