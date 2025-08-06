@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thêm người dùng</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Tên người dùng -->
            <div class="mb-3">
                <label for="name" class="form-label">Tên người dùng</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên người dùng" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email" required>
            </div>

            <!-- Mật khẩu -->
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
            </div>

            <!-- Số điện thoại -->
            <div class="mb-3">
                <label for="phone_number" class="form-label">Số điện thoại</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Nhập số điện thoại">
            </div>

            <!-- Ngày sinh -->
            <div class="mb-3">
                <label for="birthday" class="form-label">Ngày sinh</label>
                <input type="date" id="birthday" name="birthday" class="form-control">
            </div>

            <!-- Giới tính -->
            <div class="mb-3">
                <label for="gender" class="form-label">Giới tính</label>
                <select id="gender" name="gender" class="form-select">
                    <option value="male">Nam</option>
                    <option value="female">Nữ</option>
                    <option value="other">Khác</option>
                </select>
            </div>

            <!-- Vai trò -->
            <div class="mb-3">
                <label for="roles" class="form-label">Vai trò</label>
                <select id="roles" name="roles[]" class="form-select" multiple>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Giữ phím Ctrl hoặc Cmd để chọn nhiều vai trò.</small>
            </div>

            <!-- Trạng thái -->
            <div class="mb-3">
                <label for="is_active" class="form-label">Trạng thái</label>
                <select id="is_active" name="is_active" class="form-select">
                    <option value="1">Hoạt động</option>
                    <option value="0">Không hoạt động</option>
                </select>
            </div>

            <!-- Ảnh đại diện -->
            <div class="mb-3">
                <label for="image_profile" class="form-label">Ảnh đại diện</label>
                <input type="file" id="image_profile" name="image_profile" class="form-control" onchange="previewImage(event)">
                <div class="mt-3">
                    <img id="image_preview" src="#" alt="Xem trước ảnh" style="max-height: 150px; display: none;" class="rounded">
                </div>
            </div>

            <!-- Địa chỉ -->
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" id="address" name="address_line" class="form-control" placeholder="Nhập địa chỉ chi tiết">
            </div>
            <div class="mb-3">
                <label for="ward" class="form-label">Xã/Phường</label>
                <input type="text" id="ward" name="ward" class="form-control" placeholder="Nhập phường">
            </div>
            <div class="mb-3">
                <label for="district" class="form-label">Quận/Huyện</label>
                <input type="text" id="district" name="district" class="form-control" placeholder="Nhập quận">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">Thành phố</label>
                <input type="text" id="city" name="city" class="form-control" placeholder="Nhập thành phố">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" id="is_default" name="is_default" class="form-check-input">
                <label for="is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
            </div>

            <!-- Nút submit -->
            <button type="submit" class="btn btn-primary">Thêm người dùng</button>
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
