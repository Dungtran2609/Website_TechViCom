@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Chỉnh sửa mã: {{ $coupon->code }}</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Mã</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                        value="{{ old('code', $coupon->code) }}">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Loại giảm giá</label>
                    <select name="discount_type" class="form-select @error('discount_type') is-invalid @enderror">
                        <option value="percent" {{ old('discount_type', $coupon->discount_type) == 'percent' ? 'selected' : '' }}>Phần trăm</option>
                        <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Giảm cố định</option>
                    </select>
                    @error('discount_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá trị</label>
                    <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                        value="{{ old('value', $coupon->value) }}">
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giảm tối đa</label>
                    <input type="number" name="max_discount_amount" class="form-control @error('max_discount_amount') is-invalid @enderror"
                        value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                    @error('max_discount_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá trị đơn tối thiểu</label>
                    <input type="number" name="min_order_value" class="form-control @error('min_order_value') is-invalid @enderror"
                        value="{{ old('min_order_value', $coupon->min_order_value) }}">
                    @error('min_order_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá trị đơn tối đa</label>
                    <input type="number" name="max_order_value" class="form-control @error('max_order_value') is-invalid @enderror"
                        value="{{ old('max_order_value', $coupon->max_order_value) }}">
                    @error('max_order_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Số lần dùng mỗi người</label>
                    <input type="number" name="max_usage_per_user" class="form-control @error('max_usage_per_user') is-invalid @enderror"
                        value="{{ old('max_usage_per_user', $coupon->max_usage_per_user) }}">
                    @error('max_usage_per_user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status', $coupon->status) == '1' ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0" {{ old('status', $coupon->status) == '0' ? 'selected' : '' }}>Tạm ngưng</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ngày bắt đầu</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                        value="{{ old('start_date', \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d')) }}">
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ngày kết thúc</label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                        value="{{ old('end_date', \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d')) }}">
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
