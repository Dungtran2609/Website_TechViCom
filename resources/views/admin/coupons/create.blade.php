@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Thêm mã giảm giá</h4>

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
});
</script>
@endpush
                <div class="col-md-6">
    <label class="form-label">Mã</label>
    <input type="text" name="code" class="form-control" value="{{ old('code') }}">
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
    <label class="form-label">Giá trị</label>
    <input type="number" name="value" class="form-control" value="{{ old('value') }}">
    @error('value')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Giảm tối đa</label>
    <input type="number" name="max_discount_amount" class="form-control" value="{{ old('max_discount_amount') }}">
    @error('max_discount_amount')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Giá trị đơn tối thiểu</label>
    <input type="number" name="min_order_value" class="form-control" value="{{ old('min_order_value') }}">
    @error('min_order_value')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Giá trị đơn tối đa</label>
    <input type="number" name="max_order_value" class="form-control" value="{{ old('max_order_value') }}">
    @error('max_order_value')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Số lần dùng mỗi người</label>
    <input type="number" name="max_usage_per_user" class="form-control" value="{{ old('max_usage_per_user') }}">
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
    <label class="form-label">Ngày bắt đầu</label>
    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
    @error('start_date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-6">
    <label class="form-label">Ngày kết thúc</label>
    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
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
