@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Danh sách mã giảm giá</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            + Thêm mã mới
        </a>
    </div>

    <form action="{{ route('admin.coupons.index') }}" method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                   class="form-control" placeholder="Nhập mã cần tìm...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">Tìm kiếm</button>
        </div>
        @if(request('keyword'))
        <div class="col-auto">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-link">Xoá tìm kiếm</a>
        </div>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle shadow-sm rounded-3 overflow-hidden">
            <thead class="table-light align-middle text-center">
                <tr>
                    <th><i class="bi bi-ticket-perforated"></i> Mã</th>
                    <th><i class="bi bi-percent"></i> Kiểu giảm</th>
                    <th><i class="bi bi-funnel"></i> Kiểu áp dụng</th>
                    <th><i class="bi bi-cash-coin"></i> Giá trị</th>
                    <th><i class="bi bi-calendar-event"></i> Ngày bắt đầu</th>
                    <th><i class="bi bi-calendar-check"></i> Ngày kết thúc</th>
                    <th><i class="bi bi-toggle-on"></i> Trạng thái</th>
                    <th class="text-center"><i class="bi bi-gear"></i> Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    @php
                        $typeMapping = ['percent' => 'Phần trăm', 'fixed' => 'Cố định'];
                        $applyTypeMapping = [
                            'all' => 'Tất cả',
                            'product' => 'Theo sản phẩm',
                            'category' => 'Theo danh mục',
                            'user' => 'Theo người dùng',
                        ];
                    @endphp
                    <tr class="{{ $coupon->trashed() ? 'table-danger' : '' }}">
                        <td class="fw-bold text-primary"><i class="bi bi-ticket-perforated"></i> {{ $coupon->code }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                <i class="bi bi-percent"></i> {{ $typeMapping[$coupon->discount_type] ?? 'Không xác định' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                <i class="bi bi-funnel"></i> {{ $applyTypeMapping[$coupon->apply_type] ?? $coupon->apply_type }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-cash-coin"></i>
                                {{ $coupon->discount_type === 'percent' 
                                    ? $coupon->value . '%' 
                                    : number_format($coupon->value, 0, ',', '.') . '₫' }}
                            </span>
                        </td>
                        <td><span class="badge bg-light border text-dark"><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</span></td>
                        <td><span class="badge bg-light border text-dark"><i class="bi bi-calendar-check"></i> {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</span></td>
                        <td>
                            @if ($coupon->status)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Kích hoạt</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Tạm dừng</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                            @if ($coupon->trashed())
                                <form action="{{ route('admin.coupons.restore', $coupon->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success" title="Khôi phục"><i class="bi bi-arrow-clockwise"></i></button>
                                </form>
                                <form action="{{ route('admin.coupons.forceDelete', $coupon->id) }}" method="POST" class="d-inline-block"
                                      onsubmit="return confirm('Bạn chắc chắn muốn xoá vĩnh viễn?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-dark" title="Xoá vĩnh viễn"><i class="bi bi-trash3"></i></button>
                                </form>
                            @else
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                   class="btn btn-sm btn-warning text-white" title="Sửa"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline-block"
                                      onsubmit="return confirm('Bạn chắc chắn muốn xoá?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $coupon->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xoá"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .table thead th { vertical-align: middle; }
    .table-striped > tbody > tr:nth-of-type(odd) { background-color: #f9fafb; }
    .table-hover tbody tr:hover { background-color: #f1f5f9; }
    .badge { font-size: 0.95em; }
    .btn-group .btn { margin-right: 0.2em; }
</style>
@endpush
</div>
@endsection
