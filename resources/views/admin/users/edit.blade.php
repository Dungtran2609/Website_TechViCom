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

                {{-- Ảnh đại diện hiện tại --}}
                @if ($user->image_profile)
                    <div class="mb-3 text-center">
                        <label class="form-label">Ảnh đại diện hiện tại</label><br>
                        <img src="{{ asset('storage/' . $user->image_profile) }}" alt="Ảnh đại diện"
                             class="rounded-circle img-thumbnail" style="max-height: 150px;">
                    </div>
                @endif

                {{-- Thay đổi ảnh đại diện --}}
                <div class="mb-3">
                    <label for="image_profile" class="form-label">Thay ảnh đại diện mới</label>
                    <input type="file" name="image_profile" id="image_profile" 
                           class="form-control @error('image_profile') is-invalid @enderror" 
                           accept="image/*">
                    @error('image_profile')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tên người dùng -->
                <div class="mb-3">
                    <label for="name" class="form-label">Tên người dùng <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mật khẩu mới -->
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu mới</label>
                    <input type="password" name="password" id="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Để trống nếu không muốn thay đổi">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                           placeholder="Xác nhận mật khẩu mới">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Số điện thoại -->
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Số điện thoại</label>
                    <input type="text" name="phone_number" id="phone_number" 
                           class="form-control @error('phone_number') is-invalid @enderror" 
                           value="{{ old('phone_number', $user->phone_number) }}">
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ngày sinh -->
                <div class="mb-3">
                    <label for="birthday" class="form-label">Ngày sinh</label>
                    <input type="date" name="birthday" id="birthday" 
                           class="form-control @error('birthday') is-invalid @enderror"
                           value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}">
                    @error('birthday')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Giới tính -->
                <div class="mb-3">
                    <label for="gender" class="form-label">Giới tính</label>
                    <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="">Chọn giới tính</option>
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Trạng thái -->
                <div class="mb-3">
                    <label for="is_active" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror">
                        <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Vai trò -->
                <div class="mb-3">
                    <label for="roles" class="form-label">Vai trò <span class="text-danger">*</span></label>
                    <select name="roles[]" id="roles" class="form-select @error('roles') is-invalid @enderror" multiple>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" 
                                {{ (in_array($role->id, old('roles', $user->roles->pluck('id')->toArray()))) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Giữ phím Ctrl hoặc Cmd để chọn nhiều vai trò.</small>
                    @error('roles')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('roles.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $defaultAddress = $user->addresses->where('is_default', true)->first() ?? $user->addresses->first();
                @endphp

                <div class="row">
                    <!-- Địa chỉ chi tiết -->
                    <div class="col-md-12 mb-3">
                        <label for="address_line" class="form-label">Địa chỉ chi tiết</label>
                        <input type="text" name="address_line" id="address_line" 
                               class="form-control @error('address_line') is-invalid @enderror"
                               value="{{ old('address_line', $defaultAddress->address_line ?? '') }}" 
                               placeholder="Nhập địa chỉ chi tiết">
                        @error('address_line')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phường/Xã -->
                    <div class="col-md-4 mb-3">
                        <label for="ward" class="form-label">Phường/Xã</label>
                        <input type="text" name="ward" id="ward" 
                               class="form-control @error('ward') is-invalid @enderror"
                               value="{{ old('ward', $defaultAddress->ward ?? '') }}" 
                               placeholder="Nhập phường/xã">
                        @error('ward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Quận/Huyện -->
                    <div class="col-md-4 mb-3">
                        <label for="district" class="form-label">Quận/Huyện</label>
                        <input type="text" name="district" id="district" 
                               class="form-control @error('district') is-invalid @enderror"
                               value="{{ old('district', $defaultAddress->district ?? '') }}" 
                               placeholder="Nhập quận/huyện">
                        @error('district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tỉnh/Thành phố -->
                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label">Tỉnh/Thành phố</label>
                        <input type="text" name="city" id="city" 
                               class="form-control @error('city') is-invalid @enderror"
                               value="{{ old('city', $defaultAddress->city ?? '') }}" 
                               placeholder="Nhập tỉnh/thành phố">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Địa chỉ mặc định -->
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_default" id="is_default" 
                           class="form-check-input @error('is_default') is-invalid @enderror" 
                           value="1" {{ old('is_default', $defaultAddress->is_default ?? false) ? 'checked' : '' }}>
                    <label for="is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
                    @error('is_default')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nút điều hướng --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật người dùng
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
