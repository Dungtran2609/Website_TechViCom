@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi tiết người dùng</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin người dùng -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin người dùng</h5>
                </div>
                <div class="card-body">
                    <!-- Ảnh đại diện -->
                    <div class="text-center mb-4">
                        @if ($user->image_profile)
                            <img src="{{ asset('storage/' . $user->image_profile) }}" alt="Ảnh đại diện" 
                                 class="rounded-circle img-thumbnail" style="max-height: 150px;">
                        @else
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Thông tin chi tiết -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th width="40%">ID:</th>
                                        <td>{{ $user->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tên người dùng:</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Số điện thoại:</th>
                                        <td>{{ $user->phone_number ?? 'Không có' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ngày sinh:</th>
                                        <td>{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : 'Không có' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th width="40%">Giới tính:</th>
                                        <td>
                                            @switch($user->gender)
                                                @case('male') 
                                                    <span class="badge bg-info">Nam</span>
                                                    @break
                                                @case('female') 
                                                    <span class="badge bg-pink">Nữ</span>
                                                    @break
                                                @case('other') 
                                                    <span class="badge bg-secondary">Khác</span>
                                                    @break
                                                @default 
                                                    <span class="badge bg-light text-dark">Không xác định</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái:</th>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-danger">Không hoạt động</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Vai trò:</th>
                                        <td>
                                            @forelse ($user->roles as $role)
                                                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                            @empty
                                                <span class="text-muted">Chưa có vai trò</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ngày tạo:</th>
                                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'Không xác định' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ngày cập nhật:</th>
                                        <td>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'Không xác định' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Địa chỉ -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Địa chỉ</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#addAddressForm">
                        <i class="fas fa-plus"></i> Thêm địa chỉ
                    </button>
                </div>
                <div class="card-body">
                    @if ($user->addresses->isNotEmpty())
                        @foreach ($user->addresses as $address)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    @if($address->is_default)
                                        <span class="badge bg-success">Mặc định</span>
                                    @else
                                        <span></span>
                                    @endif
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(!$address->is_default)
                                                <li>
                                                    <form action="{{ route('admin.users.addresses.update', ['address' => $address->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="is_default" value="1">
                                                        <button class="dropdown-item" type="submit">
                                                            <i class="fas fa-star text-warning"></i> Đặt mặc định
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <form action="{{ route('admin.users.addresses.destroy', ['address' => $address->id]) }}" 
                                                      method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger" type="submit">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <address class="mb-0">
                                    {{ $address->address_line }}<br>
                                    {{ $address->ward }}, {{ $address->district }}<br>
                                    {{ $address->city }}
                                </address>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                            <p class="mb-0">Chưa có địa chỉ nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Form thêm địa chỉ mới -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="collapse" id="addAddressForm">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thêm địa chỉ mới</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.addresses.store', $user->id) }}">
                            @csrf
                            <div class="row">
                                <!-- Địa chỉ chi tiết -->
                                <div class="col-md-12 mb-3">
                                    <label for="address_line" class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                    <input type="text" id="address_line" name="address_line" 
                                           class="form-control @error('address_line') is-invalid @enderror" 
                                           value="{{ old('address_line') }}" 
                                           placeholder="Nhập địa chỉ chi tiết">
                                    @error('address_line')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phường/Xã -->
                                <div class="col-md-4 mb-3">
                                    <label for="ward" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                    <input type="text" id="ward" name="ward" 
                                           class="form-control @error('ward') is-invalid @enderror" 
                                           value="{{ old('ward') }}" 
                                           placeholder="Nhập phường/xã">
                                    @error('ward')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Quận/Huyện -->
                                <div class="col-md-4 mb-3">
                                    <label for="district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                    <input type="text" id="district" name="district" 
                                           class="form-control @error('district') is-invalid @enderror" 
                                           value="{{ old('district') }}" 
                                           placeholder="Nhập quận/huyện">
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tỉnh/Thành phố -->
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                    <input type="text" id="city" name="city" 
                                           class="form-control @error('city') is-invalid @enderror" 
                                           value="{{ old('city', 'Hà Nội') }}" 
                                           placeholder="Nhập tỉnh/thành phố">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Checkbox mặc định -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" id="is_default" name="is_default" 
                                       class="form-check-input @error('is_default') is-invalid @enderror" 
                                       value="1" {{ old('is_default') ? 'checked' : '' }}>
                                <label for="is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
                                @error('is_default')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#addAddressForm">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Thêm địa chỉ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Auto-show form if there are validation errors
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            const addAddressForm = document.getElementById('addAddressForm');
            if (addAddressForm) {
                const bsCollapse = new bootstrap.Collapse(addAddressForm, {
                    show: true
                });
            }
        });
    @endif

    // Handle form submit with loading state
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                    
                    // Re-enable after 3 seconds as fallback
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 3000);
                }
            });
        });
    });
</script>
@endsection
