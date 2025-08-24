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
            <!-- Hiển thị thông báo lỗi validate chung -->
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif
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

                @php
                    $onlyUserRole = $user->roles->count() === 1 && in_array($user->roles->first()->name, ['user', 'customer']);
                @endphp
                <!-- Tên người dùng -->
                <div class="mb-3">
                    <label for="name" class="form-label">Tên người dùng <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" @if($onlyUserRole) readonly @endif>
                    @if($onlyUserRole)
                        <input type="hidden" name="name" value="{{ $user->name }}">
                    @endif
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" @if($onlyUserRole) readonly @endif>
                    @if($onlyUserRole)
                        <input type="hidden" name="email" value="{{ $user->email }}">
                    @endif
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mật khẩu mới -->
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu mới</label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Để trống nếu không muốn thay đổi" @if($onlyUserRole) readonly @endif>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           placeholder="Xác nhận mật khẩu mới" @if($onlyUserRole) readonly @endif>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Số điện thoại -->
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Số điện thoại</label>
                    <input type="text" name="phone_number" id="phone_number"
                           class="form-control @error('phone_number') is-invalid @enderror"
                           value="{{ old('phone_number', $user->phone_number) }}" @if($onlyUserRole) readonly @endif>
                    @if($onlyUserRole)
                        <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                    @endif
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ngày sinh -->
                <div class="mb-3">
                    <label for="birthday" class="form-label">Ngày sinh</label>
                    <input type="date" name="birthday" id="birthday"
                           class="form-control @error('birthday') is-invalid @enderror"
                           value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}" @if($onlyUserRole) readonly @endif>
                    @if($onlyUserRole)
                        <input type="hidden" name="birthday" value="{{ $user->birthday }}">
                    @endif
                    @error('birthday')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Giới tính -->
                <div class="mb-3">
                    <label for="gender" class="form-label">Giới tính</label>
                    <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" @if($onlyUserRole) disabled @endif>
                        <option value="">Chọn giới tính</option>
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                    @if($onlyUserRole)
                        <input type="hidden" name="gender" value="{{ $user->gender }}">
                    @endif
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

                @if(!$onlyUserRole)
                <div class="col-12 mb-3">
                    <label class="form-label font-semibold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                    <input type="text" name="address_line" class="form-control mb-2 @error('address_line') is-invalid @enderror" id="edit_address_line" value="{{ old('address_line', $defaultAddress->address_line ?? '') }}" placeholder="Nhập số nhà, tên đường...">
                    @error('address_line')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <select id="edit_province" name="city_code" class="form-control @error('city_code') is-invalid @enderror">
                                <option value="">Chọn tỉnh/thành phố</option>
                            </select>
                            <input type="hidden" name="city" id="edit_city_name" value="{{ old('city', $defaultAddress->city ?? '') }}" required>
                            @error('city_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <select id="edit_district" name="district_code" class="form-control @error('district_code') is-invalid @enderror">
                                <option value="">Chọn quận/huyện</option>
                            </select>
                            <input type="hidden" name="district" id="edit_district_name" value="{{ old('district', $defaultAddress->district ?? '') }}" required>
                            <input type="hidden" name="district_code_hidden" id="edit_district_code_hidden" value="{{ old('district_code', '') }}">
                            @error('district_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <select id="edit_ward" name="ward_code" class="form-control @error('ward_code') is-invalid @enderror">
                                <option value="">Chọn phường/xã</option>
                            </select>
                            <input type="hidden" name="ward" id="edit_ward_name" value="{{ old('ward', $defaultAddress->ward ?? '') }}" required>
                            <input type="hidden" name="ward_code_hidden" id="edit_ward_code_hidden" value="{{ old('ward_code', '') }}">
                            @error('ward_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

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

@section('scripts')
    <script>
        $(document).ready(function() {
            const apiBaseUrl = '{{ url('/api') }}';

            function loadProvinces() {
                fetch(`${apiBaseUrl}/provinces`)
                    .then(response => response.json())
                    .then(data => {
                        $('select[name="city_code"]').each(function() {
                            const provinceSelect = $(this);
                            provinceSelect.empty().append('<option value="">Chọn tỉnh/thành phố</option>');
                            data.forEach(province => {
                                provinceSelect.append(`<option value="${province.code}">${province.name}</option>`);
                            });
                            // Set selected if value exists
                            const selectedCity = $('#edit_city_name').val();
                            if (selectedCity) {
                                provinceSelect.find('option').filter(function() {
                                    return $(this).text() === selectedCity;
                                }).prop('selected', true);
                            }
                        });
                    });
            }

            function loadDistricts(provinceCode, districtSelectElement) {
                const wardSelectElement = $(districtSelectElement).closest('.row').find('select[name="ward_code"]');
                $(districtSelectElement).empty().append('<option value="">Chọn quận/huyện</option>');
                $(wardSelectElement).empty().append('<option value="">Chọn phường/xã</option>');
                if (!provinceCode) return;
                fetch(`${apiBaseUrl}/districts/${provinceCode}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(district => {
                            $(districtSelectElement).append(`<option value="${district.code}">${district.name}</option>`);
                        });
                        // Set selected if value exists
                        const selectedDistrict = $('#edit_district_name').val();
                        if (selectedDistrict) {
                            $(districtSelectElement).find('option').filter(function() {
                                return $(this).text() === selectedDistrict;
                            }).prop('selected', true);
                        }
                    });
            }

            function loadWards(districtCode, wardSelectElement) {
                $(wardSelectElement).empty().append('<option value="">Chọn phường/xã</option>');
                if (!districtCode) return;
                fetch(`${apiBaseUrl}/wards/${districtCode}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(ward => {
                            $(wardSelectElement).append(`<option value="${ward.code}">${ward.name}</option>`);
                        });
                        // Set selected if value exists
                        const selectedWard = $('#edit_ward_name').val();
                        if (selectedWard) {
                            $(wardSelectElement).find('option').filter(function() {
                                return $(this).text() === selectedWard;
                            }).prop('selected', true);
                        }
                    });
            }

            // Sự kiện chọn tỉnh/thành
            $(document).on('change', 'select[name="city_code"]', function() {
                const provinceCode = $(this).val();
                const districtSelect = $(this).closest('.row').find('select[name="district_code"]');
                $(this).closest('.row').find('input[name="city"]').val($(this).find('option:selected').text());
                loadDistricts(provinceCode, districtSelect);
            });

            // Sự kiện chọn quận/huyện
            $(document).on('change', 'select[name="district_code"]', function() {
                const districtCode = $(this).val();
                const wardSelect = $(this).closest('.row').find('select[name="ward_code"]');
                $(this).closest('.row').find('input[name="district"]').val($(this).find('option:selected').text());
                // Lưu lại district_code vào input ẩn
                $('#edit_district_code_hidden').val(districtCode);
                loadWards(districtCode, wardSelect);
            });

            // Sự kiện chọn phường/xã
            $(document).on('change', 'select[name="ward_code"]', function() {
                const wardCode = $(this).val();
                $(this).closest('.row').find('input[name="ward"]').val($(this).find('option:selected').text());
                // Lưu lại ward_code vào input ẩn
                $('#edit_ward_code_hidden').val(wardCode);
            });

            // Tải dữ liệu dropdown khi trang tải
            // Ưu tiên lấy code từ input ẩn nếu có, nếu không thì lấy từ old() hoặc defaultAddress
            const selectedCityName = $('#edit_city_name').val();
            const selectedDistrictName = $('#edit_district_name').val();
            const selectedWardName = $('#edit_ward_name').val();
            const selectedDistrictCode = $('#edit_district_code_hidden').val();
            const selectedWardCode = $('#edit_ward_code_hidden').val();

            // Hàm lấy code từ tên (nếu không có code lưu sẵn)
            function getCodeByName(list, name) {
                const found = list.find(item => item.name === name);
                return found ? found.code : '';
            }

            // Load provinces và sau đó load districts/wards nếu có dữ liệu cũ
            fetch(`${apiBaseUrl}/provinces`).then(res => res.json()).then(provinces => {
                const provinceSelect = $('select[name="city_code"]');
                provinceSelect.empty().append('<option value="">Chọn tỉnh/thành phố</option>');
                provinces.forEach(province => {
                    provinceSelect.append(`<option value="${province.code}">${province.name}</option>`);
                });
                // Set selected city
                if (selectedCityName) {
                    provinceSelect.find('option').filter(function() {
                        return $(this).text() === selectedCityName;
                    }).prop('selected', true);
                }
                // Lấy code tỉnh/thành phố
                let cityCode = provinceSelect.val();
                if (!cityCode && selectedCityName) {
                    cityCode = getCodeByName(provinces, selectedCityName);
                    provinceSelect.val(cityCode);
                }
                if (cityCode) {
                    // Load districts
                    fetch(`${apiBaseUrl}/districts/${cityCode}`).then(res => res.json()).then(districts => {
                        const districtSelect = $('select[name="district_code"]');
                        districtSelect.empty().append('<option value="">Chọn quận/huyện</option>');
                        districts.forEach(district => {
                            districtSelect.append(`<option value="${district.code}">${district.name}</option>`);
                        });
                        // Set selected district
                        let districtCode = selectedDistrictCode;
                        if (!districtCode && selectedDistrictName) {
                            districtCode = getCodeByName(districts, selectedDistrictName);
                        }
                        if (districtCode) {
                            districtSelect.val(districtCode);
                        } else if (selectedDistrictName) {
                            districtSelect.find('option').filter(function() {
                                return $(this).text() === selectedDistrictName;
                            }).prop('selected', true);
                        }
                        // Load wards nếu có districtCode
                        if (districtCode) {
                            fetch(`${apiBaseUrl}/wards/${districtCode}`).then(res => res.json()).then(wards => {
                                const wardSelect = $('select[name="ward_code"]');
                                wardSelect.empty().append('<option value="">Chọn phường/xã</option>');
                                wards.forEach(ward => {
                                    wardSelect.append(`<option value="${ward.code}">${ward.name}</option>`);
                                });
                                // Set selected ward
                                let wardCode = selectedWardCode;
                                if (!wardCode && selectedWardName) {
                                    wardCode = getCodeByName(wards, selectedWardName);
                                }
                                if (wardCode) {
                                    wardSelect.val(wardCode);
                                } else if (selectedWardName) {
                                    wardSelect.find('option').filter(function() {
                                        return $(this).text() === selectedWardName;
                                    }).prop('selected', true);
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
