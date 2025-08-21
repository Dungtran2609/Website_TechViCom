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
                                        <td>{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : 'Không có' }}
                                        </td>
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
                                            @if ($user->is_active)
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
                                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'Không xác định' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ngày cập nhật:</th>
                                        <td>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'Không xác định' }}
                                        </td>
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
                                    @if ($address->is_default)
                                        <span class="badge bg-success">Mặc định</span>
                                    @else
                                        <span></span>
                                    @endif
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if (!$address->is_default)
                                                <li>
                                                    <form
                                                        action="{{ route('admin.users.addresses.update', ['address' => $address->id]) }}"
                                                        method="POST">
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
                                                <form
                                                    action="{{ route('admin.users.addresses.destroy', ['address' => $address->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
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
                                <!-- Tên người nhận -->
                                <div class="col-md-6 mb-3">
                                    <label for="recipient_name" class="form-label">Tên người nhận <span class="text-danger">*</span></label>
                                    <input type="text" id="recipient_name" name="recipient_name"
                                           class="form-control @error('recipient_name') is-invalid @enderror"
                                           value="{{ old('recipient_name') }}"
                                           placeholder="Nhập tên người nhận">
                                    @error('recipient_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Số điện thoại -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" id="phone" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}"
                                           placeholder="Nhập số điện thoại">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Bắt đầu phần địa chỉ đã được thay thế -->
                                <div class="col-12 mb-3">
                                    <label class="form-label font-semibold">Địa chỉ chi tiết <span
                                            class="text-danger">*</span></label>


                                    <!-- Ô nhập địa chỉ chi tiết (số nhà, tên đường...) -->
                                    <input type="text" name="address_line"
                                        class="form-control mb-2 @error('address_line') is-invalid @enderror"
                                        id="edit_address_line" value="{{ old('address_line') }}"
                                        placeholder="Nhập số nhà, tên đường...">
                                    @error('address_line')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror


                                    <!-- Hàng chứa các ô chọn Tỉnh, Huyện, Xã -->
                                    <div class="row">
                                        <!-- Tỉnh/Thành phố -->
                                        <div class="col-md-4 mb-2">
                                            <select id="edit_province" name="city_code"
                                                class="form-control @error('city_code') is-invalid @enderror">
                                                <option value="">Chọn tỉnh/thành phố</option>
                                            </select>
                                            <input type="hidden" name="city" id="edit_city_name" required>
                                            @error('city_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Quận/Huyện -->
                                        <div class="col-md-4 mb-2">
                                            <select id="edit_district" name="district_code"
                                                class="form-control @error('district_code') is-invalid @enderror">
                                                <option value="">Chọn quận/huyện</option>
                                            </select>
                                            <input type="hidden" name="district" id="edit_district_name" required>
                                            @error('district_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Phường/Xã -->
                                        <div class="col-md-4 mb-2">
                                            <select id="edit_ward" name="ward_code"
                                                class="form-control @error('ward_code') is-invalid @enderror">
                                                <option value="">Chọn phường/xã</option>
                                            </select>
                                            <input type="hidden" name="ward" id="edit_ward_name" required>
                                            @error('ward_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Kết thúc phần địa chỉ -->
                                <!-- Checkbox mặc định -->
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="is_default" name="is_default"
                                        class="form-check-input @error('is_default') is-invalid @enderror" value="1"
                                        {{ old('is_default') ? 'checked' : '' }}>
                                    <label for="is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
                                    @error('is_default')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Buttons -->
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse"
                                        data-bs-target="#addAddressForm">
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
    // Sử dụng @push('scripts') trong file Blade của bạn để chèn script này
        <script>
            $(document).ready(function() {


                //======================================================================
                // SECTION 1: LOGIC XỬ LÝ DROPDOWN ĐỊA CHỈ
                //======================================================================


                const apiBaseUrl = '{{ url('/api') }}'; // Sử dụng API công khai đã có


                // Hàm để load danh sách Tỉnh/Thành (chỉ Hà Nội)
                function loadProvinces() {
                    fetch(`${apiBaseUrl}/provinces`)
                        .then(response => response.json())
                        .then(data => {
                            // Áp dụng cho cả form thêm mới và form chỉnh sửa (nếu có)
                            $('select[name="city_code"]').each(function() {
                                const provinceSelect = $(this);
                                provinceSelect.empty().append(
                                    '<option value="">Chọn tỉnh/thành phố</option>');
                                data.forEach(province => {
                                    provinceSelect.append(
                                        `<option value="${province.code}">${province.name}</option>`
                                        );
                                });
                            });
                        });
                }


                // Hàm để load Quận/Huyện dựa vào mã Tỉnh/Thành
                function loadDistricts(provinceCode, districtSelectElement) {
                    const wardSelectElement = $(districtSelectElement).closest('.row').find('select[name="ward_code"]');


                    $(districtSelectElement).empty().append('<option value="">Chọn quận/huyện</option>');
                    $(wardSelectElement).empty().append('<option value="">Chọn phường/xã</option>');


                    if (!provinceCode) return;


                    fetch(`${apiBaseUrl}/districts/${provinceCode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(district => {
                                $(districtSelectElement).append(
                                    `<option value="${district.code}">${district.name}</option>`);
                            });
                        });
                }


                // Hàm để load Phường/Xã dựa vào mã Quận/Huyện
                function loadWards(districtCode, wardSelectElement) {
                    $(wardSelectElement).empty().append('<option value="">Chọn phường/xã</option>');


                    if (!districtCode) return;


                    fetch(`${apiBaseUrl}/wards/${districtCode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(ward => {
                                $(wardSelectElement).append(
                                    `<option value="${ward.code}">${ward.name}</option>`);
                            });
                        });
                }


                // --- Gán sự kiện cho các dropdown ---
                // Sử dụng event delegation để áp dụng cho cả các form được tạo động


                // Khi chọn Tỉnh/Thành
                $(document).on('change', 'select[name="city_code"]', function() {
                    const provinceCode = $(this).val();
                    const districtSelect = $(this).closest('.row').find('select[name="district_code"]');


                    // Cập nhật input ẩn với TÊN của tỉnh/thành
                    $(this).closest('.row').find('input[name="city"]').val($(this).find('option:selected')
                    .text());


                    loadDistricts(provinceCode, districtSelect);
                });


                // Khi chọn Quận/Huyện
                $(document).on('change', 'select[name="district_code"]', function() {
                    const districtCode = $(this).val();
                    const wardSelect = $(this).closest('.row').find('select[name="ward_code"]');


                    // Cập nhật input ẩn với TÊN của quận/huyện
                    $(this).closest('.row').find('input[name="district"]').val($(this).find('option:selected')
                        .text());


                    loadWards(districtCode, wardSelect);
                });


                // Khi chọn Phường/Xã
                $(document).on('change', 'select[name="ward_code"]', function() {
                    // Cập nhật input ẩn với TÊN của phường/xã
                    $(this).closest('.row').find('input[name="ward"]').val($(this).find('option:selected')
                    .text());
                });


                // Tự động load danh sách tỉnh/thành khi trang được tải
                loadProvinces();




                //======================================================================
                // SECTION 2: LOGIC XỬ LÝ FORM CHUNG
                //======================================================================


                // 2.1. Tự động mở form "Thêm địa chỉ" nếu có lỗi validation
                @if ($errors->any())
                    const addAddressForm = document.getElementById('addAddressForm');
                    if (addAddressForm) {
                        // Sử dụng new bootstrap.Collapse để điều khiển
                        const bsCollapse = new bootstrap.Collapse(addAddressForm, {
                            toggle: false // Không tự động bật/tắt, chỉ điều khiển bằng lệnh
                        });
                        bsCollapse.show(); // Luôn hiển thị form nếu có lỗi
                    }
                @endif


                // 2.2. Hiển thị trạng thái "loading" khi submit bất kỳ form nào và kiểm tra input ẩn
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(event) {
                        // Kiểm tra input ẩn city, district, ward
                        const city = this.querySelector('input[name="city"]');
                        const district = this.querySelector('input[name="district"]');
                        const ward = this.querySelector('input[name="ward"]');
                        if (city && !city.value.trim()) {
                            alert('Vui lòng chọn Tỉnh/Thành phố!');
                            city.focus();
                            event.preventDefault();
                            return false;
                        }
                        if (district && !district.value.trim()) {
                            alert('Vui lòng chọn Quận/Huyện!');
                            district.focus();
                            event.preventDefault();
                            return false;
                        }
                        if (ward && !ward.value.trim()) {
                            alert('Vui lòng chọn Phường/Xã!');
                            ward.focus();
                            event.preventDefault();
                            return false;
                        }
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if (submitBtn && !submitBtn.disabled) {
                            submitBtn.disabled = true;
                            const originalText = submitBtn.innerHTML;
                            submitBtn.innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                            setTimeout(() => {
                                if (submitBtn.disabled) {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalText;
                                }
                            }, 5000);
                        }
                    });
                });


            });
        </script>
    @endsection



