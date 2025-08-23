@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Thêm mã giảm giá</h4>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                <iconify-icon icon="solar:arrow-left-broken" class="align-middle"></iconify-icon> Quay lại danh sách
            </a>
        </div>

        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kiểu áp dụng</label>
                    <select name="apply_type" id="apply_type" class="form-select">
                        <option value="all" {{ old('apply_type') == 'all' ? 'selected' : '' }}>Tất cả đơn hàng</option>
                        <option value="product" {{ old('apply_type') == 'product' ? 'selected' : '' }}>Theo sản phẩm</option>
                        <option value="category" {{ old('apply_type') == 'category' ? 'selected' : '' }}>Theo danh mục</option>
                        <option value="user" {{ old('apply_type') == 'user' ? 'selected' : '' }}>Theo người dùng</option>
                    </select>
                    @error('apply_type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6" id="category_select_box" style="display:none;">
                    <label class="form-label">Chọn danh mục áp dụng</label>
                    <select name="category_ids[]" id="category_id" class="form-select" multiple>
                        @php
                            $selectedCategories = old('category_ids', []);
                        @endphp
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ in_array($cat->id, $selectedCategories) ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6" id="product_select_box" style="display:none;">
                    <label class="form-label">Chọn sản phẩm áp dụng</label>
                    <select name="product_ids[]" id="product_id" class="form-select" multiple>
                        @php
                            $selectedProducts = old('product_ids', []);
                        @endphp
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ in_array($prod->id, $selectedProducts) ? 'selected' : '' }}>{{ $prod->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6" id="user_select_box" style="display:none;">
                    <label class="form-label">Chọn người dùng áp dụng</label>
                    <select name="user_ids[]" id="user_id" class="form-select" multiple>
                        @php
                            $selectedUsers = old('user_ids', []);
                        @endphp
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, $selectedUsers) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    function toggleApplyTypeFields() {
        var type = $('#apply_type').val();
        $('#category_select_box').toggle(type === 'category');
        $('#product_select_box').toggle(type === 'product');
        $('#user_select_box').toggle(type === 'user');
    }
    $('#apply_type').on('change', toggleApplyTypeFields);
    toggleApplyTypeFields();
    $('#category_id').select2({placeholder: 'Chọn danh mục', allowClear: true, width: '100%'});
    $('#product_id').select2({placeholder: 'Chọn sản phẩm', allowClear: true, width: '100%'});
    $('#user_id').select2({placeholder: 'Chọn người dùng', allowClear: true, width: '100%'});
    
    // Cập nhật validation cho giá trị dựa trên loại giảm giá
    function updateValueValidation() {
        const discountType = $('select[name="discount_type"]').val();
        const valueInput = $('input[name="value"]');
        
        if (discountType === 'percent') {
            valueInput.attr('max', '100');
            valueInput.attr('placeholder', 'VD: 10 (10%), 25 (25%)');
            $('input[name="value"]').next('.form-text').text('Nhập số phần trăm từ 1-100%');
        } else {
            valueInput.attr('max', '10000000');
            valueInput.attr('placeholder', 'VD: 50000 (50,000₫), 100000 (100,000₫)');
            $('input[name="value"]').next('.form-text').text('Nhập số tiền cố định (tối đa 10,000,000₫)');
        }
    }
    
    $('select[name="discount_type"]').on('change', updateValueValidation);
    updateValueValidation(); // Chạy lần đầu
});
</script>
@endpush
                <div class="col-md-6">
    <label class="form-label">Mã <span class="text-danger">*</span></label>
    <input type="text" name="code" class="form-control" value="{{ old('code') }}" 
           placeholder="VD: SALE2024, GIAM50, TET2025" 
           pattern="[A-Za-z0-9]+" 
           title="Chỉ cho phép chữ cái và số, không ký tự đặc biệt">
    <small class="form-text text-muted">Chỉ cho phép chữ cái và số, tối đa 20 ký tự</small>
    @error('code')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Loại giảm giá</label>
    <select name="discount_type" class="form-select">
        <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Giảm cố định</option>
    </select>
    @error('discount_type')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Giá trị <span class="text-danger">*</span></label>
    <input type="number" name="value" class="form-control" value="{{ old('value') }}" 
           placeholder="VD: 10 (10%) hoặc 50000 (50,000₫)" 
           min="1" max="100" step="0.01">
    <small class="form-text text-muted">Nhập số phần trăm (1-100) hoặc số tiền cố định</small>
    @error('value')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Giảm tối đa</label>
    <input type="number" name="max_discount_amount" class="form-control" value="{{ old('max_discount_amount') }}" 
           placeholder="VD: 100000 (100,000₫)" min="0" step="1000">
    <small class="form-text text-muted">Giới hạn số tiền giảm tối đa (để trống = không giới hạn)</small>
    @error('max_discount_amount')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Giá trị đơn tối thiểu</label>
    <input type="number" name="min_order_value" class="form-control" value="{{ old('min_order_value') }}" 
           placeholder="VD: 500000 (500,000₫)" min="0" step="1000">
    <small class="form-text text-muted">Đơn hàng phải có giá trị tối thiểu để áp dụng (để trống = không giới hạn)</small>
    @error('min_order_value')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Giá trị đơn tối đa</label>
    <input type="number" name="max_order_value" class="form-control" value="{{ old('max_order_value') }}" 
           placeholder="VD: 5000000 (5,000,000₫)" min="0" step="1000">
    <small class="form-text text-muted">Đơn hàng không được vượt quá giá trị này (để trống = không giới hạn)</small>
    @error('max_order_value')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Số lần dùng mỗi người</label>
    <input type="number" name="max_usage_per_user" class="form-control" value="{{ old('max_usage_per_user') }}" 
           placeholder="VD: 1, 3, 5" min="1" step="1">
    <small class="form-text text-muted">Giới hạn số lần mỗi người dùng có thể sử dụng mã này (để trống = không giới hạn)</small>
    @error('max_usage_per_user')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Trạng thái</label>
    <select name="status" class="form-select">
        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tạm ngưng</option>
    </select>
    @error('status')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" 
           min="{{ date('Y-m-d') }}" 
           placeholder="Chọn ngày bắt đầu">
    <small class="form-text text-muted">Ngày bắt đầu hiệu lực của mã giảm giá</small>
    @error('start_date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" 
           min="{{ date('Y-m-d') }}" 
           placeholder="Chọn ngày kết thúc">
    <small class="form-text text-muted">Ngày kết thúc hiệu lực của mã giảm giá</small>
    @error('end_date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">Lưu</button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary px-4">Hủy</a>
                
            </div>
        </form>
    </div>
</div>
@endsection
