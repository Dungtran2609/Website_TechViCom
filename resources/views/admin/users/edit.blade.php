@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chỉnh sửa người dùng</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Form chỉnh sửa -->
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Ảnh đại diện -->
                <div class="form-group text-center mb-4">
                    <label for="image_profile" class="form-label">Ảnh đại diện</label>
                    @if ($user->image_profile)
                        <img src="{{ asset('storage/' . $user->image_profile) }}" alt="Ảnh đại diện"
                             class="rounded-circle mb-3" style="max-height: 150px;">
                    @else
                        <img src="{{ asset('default-avatar.png') }}" alt="Ảnh đại diện" class="rounded-circle mb-3"
                             style="max-height: 150px;">
                    @endif
                    <input type="file" name="image_profile" id="image_profile" class="form-control">
                </div>

                <!-- Thông tin người dùng -->
                <div class="form-group mb-3">
                    <label for="name">Tên người dùng</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="phone_number">Số điện thoại</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
                </div>

                <div class="form-group mb-3">
                    <label for="birthday">Ngày sinh</label>
                    <input type="date" name="birthday" id="birthday" class="form-control"
                           value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="gender">Giới tính</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="is_active">Trạng thái</label>
                    <select name="is_active" id="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="roles">Vai trò</label>
                    <select name="roles[]" id="roles" class="form-control" multiple>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->roles->pluck('id')->contains($role->id) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @php
                    $defaultAddress = $user->addresses->where('is_default', true)->first() ?? $user->addresses->first();
                @endphp

                <div class="form-group mb-3">
                    <label for="address_line">Địa chỉ chi tiết</label>
                    <input type="text" name="address_line" id="address_line" class="form-control"
                           value="{{ old('address_line', $defaultAddress->address_line ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="ward">Phường</label>
                    <input type="text" name="ward" id="ward" class="form-control"
                           value="{{ old('ward', $defaultAddress->ward ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="district">Quận</label>
                    <input type="text" name="district" id="district" class="form-control"
                           value="{{ old('district', $defaultAddress->district ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="city">Thành phố</label>
                    <input type="text" name="city" id="city" class="form-control"
                           value="{{ old('city', $defaultAddress->city ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="is_default">Mặc định</label>
                    <select name="is_default" id="is_default" class="form-control">
                        <option value="1" {{ old('is_default', $defaultAddress->is_default ?? false) == true ? 'selected' : '' }}>Có</option>
                        <option value="0" {{ old('is_default', $defaultAddress->is_default ?? false) == false ? 'selected' : '' }}>Không</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
@endsection
