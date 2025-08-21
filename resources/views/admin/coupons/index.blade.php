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
        <table class="simple-table">
            <thead>
                <tr>
                    <th style="width: 13%">ID</th>
                    <th style="width: 14%">Mã</th>
                    <th style="width: 14%">Kiểu giảm</th>
                    <th style="width: 14%">Kiểu áp dụng</th>
                    <th style="width: 10%">Giá trị</th>
                    <th style="width: 10%">Ngày bắt đầu</th>
                    <th style="width: 10%">Ngày kết thúc</th>
                    <th style="width: 7%">Trạng thái</th>
                    <th style="width: 13%">Hành động</th>
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
                    <tr style="background: #fff; {{ $coupon->trashed() ? 'opacity:0.6;' : '' }}">
                        <td><span class="badge bg-secondary">{{ $coupon->id }}</span></td>
                        <td style="font-weight: 500; color: #222;">{{ $coupon->code }}</td>
                        <td>{{ $typeMapping[$coupon->discount_type] ?? 'Không xác định' }}</td>
                        <td>{{ $applyTypeMapping[$coupon->apply_type] ?? $coupon->apply_type }}</td>
                        <td>{{ $coupon->discount_type === 'percent' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', '.') . '₫' }}</td>
                        <td>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</td>
                        <td>
                            @if ($coupon->status)
                                <span class="status-active">Kích hoạt</span>
                            @else
                                <span class="status-inactive">Tạm dừng</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center align-items-center">
                                <a href="{{ route('admin.coupons.show', $coupon->id) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                </a>
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-light btn-sm" title="Sửa mã giảm giá">
                                    <iconify-icon icon="solar:pen-broken" class="align-middle fs-18"></iconify-icon>
                                </a>
                                @if(!$coupon->trashed())
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn chắc chắn muốn xoá mã này?');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $coupon->id }}">
                                        <button type="submit" class="btn btn-light btn-sm" title="Xoá">
                                            <iconify-icon icon="solar:trash-bin-trash-broken" class="align-middle fs-18 text-danger"></iconify-icon>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        <div class="d-flex justify-content-end mb-2">
            <a href="{{ route('admin.coupons.trash') }}" class="btn btn-outline-danger btn-sm">
                <iconify-icon icon="solar:trash-bin-trash-broken" class="align-middle"></iconify-icon> Thùng rác
            </a>
        </div>
@push('styles')
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<style>
    .simple-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fafbfc;
        border-radius: 14px;
        overflow: hidden;
        font-size: 15px;
        box-shadow: 0 2px 8px 0 rgba(0,0,0,0.03);
    }
    .simple-table thead th {
        font-weight: 600;
        background: #f6f6f6;
        color: #222;
        border-bottom: 1.5px solid #ececec;
        padding: 12px 10px;
        text-align: left;
    }
    .simple-table tbody td {
        padding: 10px 10px;
        border-bottom: 1px solid #f0f0f0;
        color: #222;
        background: #fff;
        vertical-align: middle;
    }
    .simple-table tbody tr:last-child td {
        border-bottom: none;
    }
    .simple-table tbody tr:hover {
        background: #f7f7fa;
    }
    .status-active {
        background: #ffe7a3;
        color: #b8860b;
        border-radius: 6px;
        padding: 4px 12px;
        font-weight: 500;
        font-size: 14px;
        display: inline-block;
    }
    .status-inactive {
        background: #f8d7da;
        color: #b02a37;
        border-radius: 6px;
        padding: 4px 12px;
        font-weight: 500;
        font-size: 14px;
        display: inline-block;
    }
    .action-group {
        display: flex;
        gap: 6px;
    }
    .action-btn {
        border: none;
        outline: none;
        background: #f1f3f6;
        color: #222;
        border-radius: 8px;
        padding: 5px 13px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
        margin: 0;
        display: inline-block;
        text-decoration: none;
    }
    .action-btn:hover, .action-btn:focus {
        background: #e2e6ea;
        color: #111;
    }
    .action-edit {
        background: #e7f1ff;
        color: #1766c2;
    }
    .action-edit:hover {
        background: #d0e6ff;
        color: #0d3a6b;
    }
    .action-delete {
        background: #fbeaea;
        color: #c82333;
    }
    .action-delete:hover {
        background: #f5c6cb;
        color: #721c24;
    }
    .action-restore {
        background: #eaffea;
        color: #218838;
    }
    .action-restore:hover {
        background: #c3e6cb;
        color: #155724;
    }
</style>
@endpush
</div>
@endsection
