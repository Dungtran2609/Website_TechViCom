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
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã</th>
                    <th>Kiểu</th>
                    <th>Giá trị</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    @php
                        $typeMapping = ['percent' => 'Phần trăm', 'fixed' => 'Cố định'];
                    @endphp
                    <tr class="{{ $coupon->trashed() ? 'table-danger' : '' }}">
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $typeMapping[$coupon->discount_type] ?? 'Không xác định' }}</td>
                        <td>
                            {{ $coupon->discount_type === 'percent' 
                                ? $coupon->value . '%' 
                                : number_format($coupon->value, 0, ',', '.') . '₫' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</td>
                        <td>
                            @if ($coupon->status)
                                <span class="badge bg-success">Kích hoạt</span>
                            @else
                                <span class="badge bg-danger">Tạm dừng</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($coupon->trashed())
                                <form action="{{ route('admin.coupons.restore', $coupon->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success">Khôi phục</button>
                                </form>

                                <form action="{{ route('admin.coupons.forceDelete', $coupon->id) }}" method="POST" class="d-inline-block"
                                      onsubmit="return confirm('Bạn chắc chắn muốn xoá vĩnh viễn?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-dark">Xoá vĩnh viễn</button>
                                </form>
                            @else
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                   class="btn btn-sm btn-warning text-white">Sửa</a>

                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline-block"
                                      onsubmit="return confirm('Bạn chắc chắn muốn xoá?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $coupon->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
