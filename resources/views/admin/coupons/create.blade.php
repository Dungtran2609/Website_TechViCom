@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Thêm mã giảm giá</h4>

        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf

            <div class="row g-3">
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
