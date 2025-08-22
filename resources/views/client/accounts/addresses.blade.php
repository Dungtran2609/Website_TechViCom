@extends('client.layouts.app')

@section('title', 'Quản lý địa chỉ')

@push('styles')
    <style>
        .address-card {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
        }

        .address-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .address-card.default {
            border-color: #ff6c2f;
            background: linear-gradient(135deg, #fff7ed 0%, #fff1f0 100%);
        }

        .btn-add-address {
            background: linear-gradient(135deg, #ff6c2f 0%, #ff8a50 100%);
            border: none;
        }

        .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #ff6c2f;
            box-shadow: 0 0 0 3px rgba(255, 108, 47, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="bg-white rounded-lg p-6 mb-6 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <a href="{{ route('accounts.index') }}"
                                class="text-gray-600 hover:text-gray-800 text-decoration-none me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <h1 class="text-2xl font-bold mb-0 text-gray-800">Quản lý địa chỉ</h1>
                        </div>
                        <p class="text-gray-600 mb-0">Quản lý địa chỉ giao hàng của bạn</p>
                    </div>
                    <button class="btn btn-add-address text-white px-4 py-2" data-bs-toggle="modal"
                        data-bs-target="#addAddressModal">
                        <i class="fas fa-plus me-2"></i>
                        Thêm địa chỉ mới
                    </button>
                </div>
            </div>

            <!-- Dropdown chọn địa chỉ -->
            @if ($addresses->count())
                <div class="mb-4">
                    <label class="form-label font-semibold">Chọn địa chỉ giao hàng</label>
                    <select class="form-control" id="address-select">
                        @foreach ($addresses as $address)
                            <option value="{{ $address->id }}" data-detail="{{ $address->address_line }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->city }}" {{ $address->is_default ? 'selected' : '' }}>
                                {{ $address->address_line }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->city }}
                                @if ($address->is_default)
                                    (Mặc định)
                                @endif
                            </option>
                        @endforeach
                        <option value="new">+ Thêm địa chỉ mới</option>
                    </select>
                    <div id="address-detail" class="mt-2 text-secondary small"></div>
                </div>
            @endif

            <!-- Address List -->
            <div class="row">
                @forelse($addresses as $address)
                    <div class="col-lg-6 mb-4">
                        <div class="address-card {{ $address->is_default ? 'default' : '' }} rounded-lg p-4 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                @if ($address->is_default)
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="fas fa-star me-1"></i>
                                        Địa chỉ mặc định
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark px-3 py-2">Địa chỉ phụ</span>
                                @endif

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="editAddress({{ $address->id }})">
                                                <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                            </a>
                                        </li>
                                        @if (!$address->is_default)
                                            <li>
                                                <a class="dropdown-item" href="#"
                                                    onclick="setDefault({{ $address->id }})">
                                                    <i class="fas fa-star me-2"></i>Đặt làm mặc định
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                                onclick="deleteAddress({{ $address->id }})">
                                                <i class="fas fa-trash me-2"></i>Xóa
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-1">
                                        <i class="fas fa-map-marker-alt me-2 text-orange-500"></i>
                                        {{ $address->address_line }}
                                    </h5>
                                    <p class="text-gray-600 mb-0">
                                        <i class="fas fa-location-arrow me-2 text-blue-500"></i>
                                        {{ $address->ward }}, {{ $address->district }}, {{ $address->city }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <i class="fas fa-map-marker-alt text-gray-300" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="text-gray-600 mb-3">Chưa có địa chỉ nào</h3>
                            <p class="text-gray-500 mb-4">Thêm địa chỉ đầu tiên để bắt đầu mua sắm</p>
                            <button class="btn btn-add-address text-white px-6 py-3" data-bs-toggle="modal"
                                data-bs-target="#addAddressModal">
                                <i class="fas fa-plus me-2"></i>
                                Thêm địa chỉ mới
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-xl font-bold">Thêm địa chỉ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('accounts.store-address') }}" method="POST" id="addAddressForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-semibold">Tên người nhận <span class="text-danger">*</span></label>
                                <input type="text" name="recipient_name" class="form-control" placeholder="Nhập tên người nhận" value="{{ old('recipient_name') }}">
                                @error('recipient_name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
                                @error('phone')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label font-semibold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                <input type="text" name="address_line" class="form-control mb-2" placeholder="VD: 134 Nguyễn Xá, Minh Khai, Bắc Từ Liêm" value="{{ old('address_line') }}">
                                @error('address_line')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <select id="province" name="city_code" class="form-control">
                                            <option value="">Chọn tỉnh/thành phố</option>
                                        </select>
                                        <input type="hidden" name="city" id="city_name">
                                        @error('city')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <select id="district" name="district_code" class="form-control">
                                            <option value="">Chọn quận/huyện</option>
                                        </select>
                                        <input type="hidden" name="district" id="district_name">
                                        @error('district')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <select id="ward" name="ward_code" class="form-control">
                                            <option value="">Chọn phường/xã</option>
                                        </select>
                                        <input type="hidden" name="ward" id="ward_name">
                                        @error('ward')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault" {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label font-semibold" for="isDefault">
                                        Đặt làm địa chỉ mặc định
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-add-address text-white">
                            <i class="fas fa-save me-2"></i>
                            Lưu địa chỉ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-xl font-bold">Chỉnh sửa địa chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAddressForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div id="edit_address_detail" class="mb-3"><h3 class="text-primary fw-bold mb-3" id="edit_address_detail_h3"></h3></div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-semibold">Tên người nhận <span class="text-danger">*</span></label>
                                <input type="text" name="recipient_name" class="form-control" id="edit_recipient_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" id="edit_phone">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label font-semibold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                <input type="text" name="address_line" class="form-control mb-2" id="edit_address_line">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <select id="edit_province" name="city_code" class="form-control">
                                            <option value="">Chọn tỉnh/thành phố</option>
                                        </select>
                                        <input type="hidden" name="city" id="edit_city_name">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <select id="edit_district" name="district_code" class="form-control">
                                            <option value="">Chọn quận/huyện</option>
                                        </select>
                                        <input type="hidden" name="district" id="edit_district_name">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <select id="edit_ward" name="ward_code" class="form-control">
                                            <option value="">Chọn phường/xã</option>
                                        </select>
                                        <input type="hidden" name="ward" id="edit_ward_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="edit_is_default">
                                    <label class="form-check-label font-semibold" for="edit_is_default">
                                        Đặt làm địa chỉ mặc định
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-add-address text-white">
                            <i class="fas fa-save me-2"></i>
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            const cityNameInput = document.getElementById('city_name');
            const districtNameInput = document.getElementById('district_name');
            const wardNameInput = document.getElementById('ward_name');

            // Load tỉnh/thành phố (chỉ Hà Nội)
            if (provinceSelect) {
                fetch('/api/provinces')
                    .then(res => res.json())
                    .then(data => {
                        provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                        data.forEach(item => {
                            provinceSelect.innerHTML +=
                                `<option value="${item.code}">${item.name}</option>`;
                        });
                        // Nếu có old value (khi validate lỗi), set lại
                        if (window.oldCity) {
                            provinceSelect.value = window.oldCity;
                            provinceSelect.dispatchEvent(new Event('change'));
                        }
                    });
            }

            if (provinceSelect && districtSelect) {
                provinceSelect.addEventListener('change', function() {
                    const code = this.value;
                    cityNameInput.value = this.options[this.selectedIndex].text;
                    districtSelect.innerHTML = '<option value="">Đang tải...</option>';
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    if (!code) {
                        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                        return;
                    }
                    fetch(`/api/districts/${code}`)
                        .then(res => res.json())
                        .then(data => {
                            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                            data.forEach(item => {
                                districtSelect.innerHTML +=
                                    `<option value="${item.code}">${item.name}</option>`;
                            });
                            if (window.oldDistrict) {
                                districtSelect.value = window.oldDistrict;
                                districtSelect.dispatchEvent(new Event('change'));
                            }
                        });
                });
            }

            if (districtSelect && wardSelect) {
                districtSelect.addEventListener('change', function() {
                    const code = this.value;
                    districtNameInput.value = this.options[this.selectedIndex].text;
                    wardSelect.innerHTML = '<option value="">Đang tải...</option>';
                    if (!code) {
                        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                        return;
                    }
                    fetch(`/api/wards/${code}`)
                        .then(res => res.json())
                        .then(data => {
                            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                            data.forEach(item => {
                                wardSelect.innerHTML +=
                                    `<option value="${item.code}">${item.name}</option>`;
                            });
                            if (window.oldWard) {
                                wardSelect.value = window.oldWard;
                            }
                        });
                });
            }

            if (wardSelect) {
                wardSelect.addEventListener('change', function() {
                    wardNameInput.value = this.options[this.selectedIndex].text;
                });
            }

            // Sửa lại logic JS cho modal sửa địa chỉ
            let editProvinceSelect = document.getElementById('edit_province');
            let editDistrictSelect = document.getElementById('edit_district');
            let editWardSelect = document.getElementById('edit_ward');
            let editCityNameInput = document.getElementById('edit_city_name');
            let editDistrictNameInput = document.getElementById('edit_district_name');
            let editWardNameInput = document.getElementById('edit_ward_name');
            window.editAddress = function(addressId) {
                fetch(`/accounts/addresses/${addressId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const address = data.address;
                            document.getElementById('edit_recipient_name').value = address.recipient_name;
                            document.getElementById('edit_phone').value = address.phone;
                            document.getElementById('edit_address_line').value = address.address_line;
                            document.getElementById('edit_is_default').checked = address.is_default;
                            document.getElementById('editAddressForm').action = `/accounts/addresses/${addressId}`;
                            // Load province list
                            fetch('/api/provinces')
                                .then(res => res.json())
                                .then(provinces => {
                                    editProvinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                                    provinces.forEach(item => {
                                        editProvinceSelect.innerHTML += `<option value="${item.code}">${item.name}</option>`;
                                    });
                                    editProvinceSelect.value = address.city_code || '';
                                    editCityNameInput.value = address.city;
                                    // Load districts
                                    if (address.city_code) {
                                        fetch(`/api/districts/${address.city_code}`)
                                            .then(res => res.json())
                                            .then(districts => {
                                                editDistrictSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                                                districts.forEach(item => {
                                                    editDistrictSelect.innerHTML += `<option value="${item.code}">${item.name}</option>`;
                                                });
                                                editDistrictSelect.value = address.district_code || '';
                                                editDistrictNameInput.value = address.district;
                                                // Load wards
                                                if (address.district_code) {
                                                    fetch(`/api/wards/${address.district_code}`)
                                                        .then(res => res.json())
                                                        .then(wards => {
                                                            editWardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                                                            wards.forEach(item => {
                                                                editWardSelect.innerHTML += `<option value="${item.code}">${item.name}</option>`;
                                                            });
                                                            editWardSelect.value = address.ward_code || '';
                                                            editWardNameInput.value = address.ward;
                                                        });
                                                }
                                            });
                                    }
                                });
                            // Sự kiện thay đổi dropdown
                            editProvinceSelect.onchange = function() {
                                let code = this.value;
                                editCityNameInput.value = this.options[this.selectedIndex].text;
                                editDistrictSelect.innerHTML = '<option value="">Đang tải...</option>';
                                editWardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                                if (!code) {
                                    editDistrictSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                                    return;
                                }
                                fetch(`/api/districts/${code}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        editDistrictSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                                        data.forEach(item => {
                                            editDistrictSelect.innerHTML += `<option value="${item.code}">${item.name}</option>`;
                                        });
                                    });
                            };
                            editDistrictSelect.onchange = function() {
                                let code = this.value;
                                editDistrictNameInput.value = this.options[this.selectedIndex].text;
                                editWardSelect.innerHTML = '<option value="">Đang tải...</option>';
                                if (!code) {
                                    editWardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                                    return;
                                }
                                fetch(`/api/wards/${code}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        editWardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                                        data.forEach(item => {
                                            editWardSelect.innerHTML += `<option value="${item.code}">${item.name}</option>`;
                                        });
                                    });
                            };
                            editWardSelect.onchange = function() {
                                editWardNameInput.value = this.options[this.selectedIndex].text;
                            };
                            // Hiển thị chi tiết địa chỉ nổi bật
                            const detail = `${address.address_line}, ${address.ward}, ${address.district}, ${address.city}`;
                            document.getElementById('edit_address_detail_h3').textContent = detail;
                            // Set giá trị cho input ẩn (fix lỗi validate)
                            document.getElementById('edit_city_name').value = address.city;
                            document.getElementById('edit_district_name').value = address.district;
                            document.getElementById('edit_ward_name').value = address.ward;
                            new bootstrap.Modal(document.getElementById('editAddressModal')).show();
                        } else {
                            Swal.fire('Lỗi!', 'Không thể tải thông tin địa chỉ', 'error', { timer: 3000 });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Lỗi!', 'Có lỗi xảy ra khi tải dữ liệu', 'error', { timer: 3000 });
                    });
            };

            window.setDefault = function(addressId) {
                Swal.fire({
                    title: 'Đặt làm mặc định?',
                    text: 'Bạn có chắc muốn đặt địa chỉ này làm mặc định?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/accounts/addresses/${addressId}/set-default`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Thành công', data.message, 'success', { timer: 3000 }).then(() => location.reload());
                            } else {
                                Swal.fire('Lỗi', data.message || 'Không thể đặt mặc định', 'error', { timer: 3000 });
                            }
                        })
                        .catch(() => Swal.fire('Lỗi', 'Có lỗi xảy ra', 'error'));
                    }
                });
            };

            window.deleteAddress = function(addressId) {
                Swal.fire({
                    title: 'Xóa địa chỉ?',
                    text: 'Bạn có chắc muốn xóa địa chỉ này? Hành động này không thể hoàn tác.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/accounts/addresses/${addressId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Đã xóa', data.message, 'success', { timer: 3000 }).then(() => location.reload());
                            } else {
                                Swal.fire('Lỗi', data.message || 'Không thể xóa địa chỉ', 'error', { timer: 3000 });
                            }
                        })
                        .catch(() => Swal.fire('Lỗi', 'Có lỗi xảy ra', 'error'));
                    }
                });
            };

            // Xử lý submit form sửa địa chỉ bằng AJAX
            const editForm = document.getElementById('editAddressForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(editForm);
                    // Lấy giá trị thực tế từ input ẩn (city, district, ward)
                    const city = document.getElementById('edit_city_name').value.trim();
                    const district = document.getElementById('edit_district_name').value.trim();
                    const ward = document.getElementById('edit_ward_name').value.trim();
                    formData.set('city', city);
                    formData.set('district', district);
                    formData.set('ward', ward);
                    const url = editForm.action;
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Thành công', 'Cập nhật địa chỉ thành công', 'success', { timer: 3000 }).then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi', data.message || 'Không thể cập nhật địa chỉ', 'error', { timer: 3000 });
                        }
                    })
                    .catch(() => Swal.fire('Lỗi', 'Có lỗi xảy ra', 'error'));
                });
            }

            // Hiển thị chi tiết địa chỉ khi chọn dropdown
            const addressSelect = document.getElementById('address-select');
            const addressDetail = document.getElementById('address-detail');
            if (addressSelect && addressDetail) {
                function showDetail() {
                    const selected = addressSelect.options[addressSelect.selectedIndex];
                    addressDetail.textContent = selected.dataset.detail || '';
                }
                addressSelect.addEventListener('change', showDetail);
                showDetail(); // Hiển thị ngay khi load trang
            }
        });
    
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                var addModal = new bootstrap.Modal(document.getElementById('addAddressModal'));
                addModal.show();
            @endif
        });
    </script>
@endpush
