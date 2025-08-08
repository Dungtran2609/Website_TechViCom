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
                <button class="btn btn-add-address text-white px-4 py-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#addAddressModal">
                    <i class="fas fa-plus me-2"></i>
                    Thêm địa chỉ mới
                </button>
            </div>
        </div>

        <!-- Address List -->
        <div class="row">
            @forelse($addresses as $address)
                <div class="col-lg-6 mb-4">
                    <div class="address-card {{ $address->is_default ? 'default' : '' }} rounded-lg p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            @if($address->is_default)
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="fas fa-star me-1"></i>
                                    Địa chỉ mặc định
                                </span>
                            @else
                                <span class="badge bg-light text-dark px-3 py-2">Địa chỉ phụ</span>
                            @endif
                            
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" 
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" 
                                           onclick="editAddress({{ $address->id }})">
                                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                        </a>
                                    </li>
                                    @if(!$address->is_default)
                                        <li>
                                            <a class="dropdown-item" href="#" 
                                               onclick="setDefault({{ $address->id }})">
                                                <i class="fas fa-star me-2"></i>Đặt làm mặc định
                                            </a>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
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
                                    <i class="fas fa-user me-2 text-orange-500"></i>
                                    {{ $address->recipient_name }}
                                </h5>
                                <p class="text-gray-600 mb-0">
                                    <i class="fas fa-phone me-2 text-blue-500"></i>
                                    {{ $address->phone }}
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-gray-700 mb-0">
                                    <i class="fas fa-map-marker-alt me-2 text-red-500"></i>
                                    {{ $address->address }}
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
                        <button class="btn btn-add-address text-white px-6 py-3" 
                                data-bs-toggle="modal" 
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
                            <input type="text" name="recipient_name" class="form-control" 
                                   placeholder="Nhập tên người nhận" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control" 
                                   placeholder="Nhập số điện thoại" required>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label font-semibold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3" 
                                      placeholder="Nhập địa chỉ chi tiết (số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố)" 
                                      required></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault">
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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-semibold">Tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" name="recipient_name" class="form-control" 
                                   id="edit_recipient_name" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control" 
                                   id="edit_phone" required>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label font-semibold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3" 
                                      id="edit_address" required></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" 
                                       value="1" id="edit_is_default">
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
// Show success/error messages
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

// Edit address function
function editAddress(addressId) {
    fetch(`/accounts/addresses/${addressId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const address = data.address;
                document.getElementById('edit_recipient_name').value = address.recipient_name;
                document.getElementById('edit_phone').value = address.phone;
                document.getElementById('edit_address').value = address.address;
                document.getElementById('edit_is_default').checked = address.is_default;
                
                document.getElementById('editAddressForm').action = `/accounts/addresses/${addressId}`;
                
                new bootstrap.Modal(document.getElementById('editAddressModal')).show();
            } else {
                Swal.fire('Lỗi!', 'Không thể tải thông tin địa chỉ', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Lỗi!', 'Có lỗi xảy ra khi tải dữ liệu', 'error');
        });
}

// Set default address
function setDefault(addressId) {
    Swal.fire({
        title: 'Xác nhận',
        text: 'Đặt địa chỉ này làm mặc định?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Có',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/accounts/addresses/${addressId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    is_default: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    Swal.fire('Lỗi!', 'Không thể cập nhật địa chỉ', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Lỗi!', 'Có lỗi xảy ra', 'error');
            });
        }
    });
}

// Delete address
function deleteAddress(addressId) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa địa chỉ này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/accounts/addresses/${addressId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    Swal.fire('Lỗi!', 'Không thể xóa địa chỉ', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Lỗi!', 'Có lỗi xảy ra', 'error');
            });
        }
    });
}
</script>
@endpush
