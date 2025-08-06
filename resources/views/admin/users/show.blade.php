@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi tiết người dùng</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Ảnh đại diện -->
            <div class="text-center mb-4">
                @if ($user->image_profile)
                    <img src="{{ asset('storage/' . $user->image_profile) }}" alt="Ảnh đại diện" class="rounded-circle" style="max-height: 150px;">
                @else
                    <img src="{{ asset('default-avatar.png') }}" alt="Ảnh đại diện" class="rounded-circle" style="max-height: 150px;">
                @endif
            </div>

            <!-- Thông tin người dùng -->
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên người dùng</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $user->phone_number ?? 'Không có' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày sinh</th>
                        <td>{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : 'Không có' }}</td>
                    </tr>
                    <tr>
                        <th>Giới tính</th>
                        <td>
                            @switch($user->gender)
                                @case('male') Nam @break
                                @case('female') Nữ @break
                                @case('other') Khác @break
                                @default Không xác định
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>{{ $user->is_active ? 'Hoạt động' : 'Không hoạt động' }}</td>
                    </tr>
                    <tr>
                        <th>Vai trò</th>
                        <td>
                            @foreach ($user->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Địa chỉ</th>
                        <td>
                            @if ($user->addresses->isNotEmpty())
                                <ul>
                                    @foreach ($user->addresses as $address)
                                        <li>
                                            {{ $address->address_line }},
                                            {{ $address->ward }},
                                            {{ $address->district }},
                                            {{ $address->city }}
                                            <span class="badge bg-success">
                                                {{ $address->is_default ? 'Mặc định' : '' }}
                                            </span>
                                            <div class="d-inline-block">
                                                <form action="{{ route('admin.users.addresses.update', ['address' => $address->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="is_default" value="1">
                                                    <button class="btn btn-sm btn-primary">Đặt mặc định</button>
                                                </form>
                                                <form action="{{ route('admin.users.addresses.destroy', ['address' => $address->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Không có địa chỉ nào được liên kết với người dùng này.</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'Không xác định' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày cập nhật</th>
                        <td>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'Không xác định' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Thêm địa chỉ mới -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Thêm địa chỉ mới</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.addresses.store', $user->id) }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="address_line">Địa chỉ</label>
                    <input type="text" id="address_line" name="address_line" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="ward">Phường</label>
                    <input type="text" id="ward" name="ward" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="district">Quận/Huyện</label>
                    <input type="text" id="district" name="district" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="city">Thành phố</label>
                    <input type="text" id="city" name="city" class="form-control" value="Hà Nội" readonly required>
                    
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" id="is_default" name="is_default" class="form-check-input">
                    <label for="is_default" class="form-check-label">Đặt làm mặc định</label>
                </div>
                <button type="submit" class="btn btn-success">Thêm địa chỉ</button>
            </form>
        </div>
    </div>
@endsection
