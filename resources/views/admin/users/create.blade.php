@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thêm người dùng</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Tên người dùng -->
            <div class="mb-3">
                <label for="name" class="form-label">Tên người dùng <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" 
                       placeholder="Nhập tên người dùng">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       placeholder="Nhập email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mật khẩu -->
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                <input type="password" id="password" name="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Nhập mật khẩu">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Xác nhận mật khẩu -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                       placeholder="Nhập lại mật khẩu">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Số điện thoại -->
            <div class="mb-3">
                <label for="phone_number" class="form-label">Số điện thoại</label>
                <input type="text" id="phone_number" name="phone_number" 
                       class="form-control @error('phone_number') is-invalid @enderror" 
                       value="{{ old('phone_number') }}" 
                       placeholder="Nhập số điện thoại">
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Ngày sinh -->
            <div class="mb-3">
                <label for="birthday" class="form-label">Ngày sinh</label>
                <input type="date" id="birthday" name="birthday" 
                       class="form-control @error('birthday') is-invalid @enderror" 
                       value="{{ old('birthday') }}">
                @error('birthday')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Giới tính -->
            <div class="mb-3">
                <label for="gender" class="form-label">Giới tính</label>
                <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="">Chọn giới tính</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Vai trò -->
            <div class="mb-3">
                <label for="roles" class="form-label">Vai trò <span class="text-danger">*</span></label>
                <select id="roles" name="roles[]" class="form-select @error('roles') is-invalid @enderror" multiple>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" 
                            {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
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

            <!-- Trạng thái -->
            <div class="mb-3">
                <label for="is_active" class="form-label">Trạng thái</label>
                <select id="is_active" name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
                @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Ảnh đại diện -->
            <div class="mb-3">
                <label for="image_profile" class="form-label">Ảnh đại diện</label>
                <input type="file" id="image_profile" name="image_profile" 
                       class="form-control @error('image_profile') is-invalid @enderror" 
                       accept="image/*">
                @error('image_profile')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="mt-3">
                    <img id="image_preview" src="#" alt="Xem trước ảnh" 
                         style="max-height: 150px; display: none;" class="rounded">
                </div>
            </div>

            <div class="row">
                <!-- Địa chỉ -->
                <div class="col-md-12 mb-3">
                    <label for="address" class="form-label">Địa chỉ chi tiết</label>
                    <input type="text" id="address" name="address_line" 
                           class="form-control @error('address_line') is-invalid @enderror" 
                           value="{{ old('address_line') }}" 
                           placeholder="Nhập địa chỉ chi tiết">
                    @error('address_line')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="ward" class="form-label">Xã/Phường</label>
                    <input type="text" id="ward" name="ward" 
                           class="form-control @error('ward') is-invalid @enderror" 
                           value="{{ old('ward') }}" 
                           placeholder="Nhập phường/xã">
                    @error('ward')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="district" class="form-label">Quận/Huyện</label>
                    <input type="text" id="district" name="district" 
                           class="form-control @error('district') is-invalid @enderror" 
                           value="{{ old('district') }}" 
                           placeholder="Nhập quận/huyện">
                    @error('district')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="city" class="form-label">Tỉnh/Thành phố</label>
                    <input type="text" id="city" name="city" 
                           class="form-control @error('city') is-invalid @enderror" 
                           value="{{ old('city') }}" 
                           placeholder="Nhập tỉnh/thành phố">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" id="is_default" name="is_default" 
                       class="form-check-input @error('is_default') is-invalid @enderror" 
                       value="1" {{ old('is_default') ? 'checked' : '' }}>
                <label for="is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
                @error('is_default')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nút điều hướng -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Thêm người dùng
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('image_profile').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image_preview');

        if (file) {
            // Kiểm tra loại file (chỉ chấp nhận ảnh)
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chọn một tệp ảnh hợp lệ.');
                this.value = ''; // Reset input
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    });
</script>
@endsection
