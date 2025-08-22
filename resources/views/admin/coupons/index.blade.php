
@extends('admin.layouts.app')
@section('title', 'Danh sách mã giảm giá')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold text-dark">Danh sách mã giảm giá</h1>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i> Thêm mã mới
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="p-3 pb-0">
                <form method="GET" action="{{ route('admin.coupons.index') }}" class="input-group mb-2" style="max-width:400px">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="keyword" class="form-control" placeholder="Nhập mã cần tìm..." value="{{ request('keyword') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Tìm kiếm</button>
                    @if(request('keyword'))
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-link">Xoá tìm kiếm</a>
                    @endif
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Mã</th>
                            <th>Kiểu giảm</th>
                            <th>Kiểu áp dụng</th>
                            <th>Giá trị</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
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
                            <tr style="{{ $coupon->trashed() ? 'opacity:0.6;' : '' }}">
                                <td>{{ $coupon->id }}</td>
                                <td class="fw-semibold">{{ $coupon->code }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $typeMapping[$coupon->discount_type] ?? 'Không xác định' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border border-1">
                                        {{ $applyTypeMapping[$coupon->apply_type] ?? $coupon->apply_type }}
                                    </span>
                                </td>
                                <td>
                                    @if($coupon->discount_type === 'percent')
                                        <span class="badge bg-primary">{{ $coupon->value }}%</span>
                                    @else
                                        <span class="badge bg-success">{{ number_format($coupon->value, 0, ',', '.') }}₫</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border border-1 px-2 py-1">
                                        <i class="bi bi-calendar-check text-success"></i>
                                        {{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') : '--' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border border-1 px-2 py-1">
                                        <i class="bi bi-calendar-x text-danger"></i>
                                        {{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') : '--' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($coupon->status)
                                        <span class="badge bg-success">Kích hoạt</span>
                                    @else
                                        <span class="badge bg-secondary">Tạm dừng</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.coupons.show', $coupon->id) }}" class="btn btn-sm btn-info me-1" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-warning me-1" title="Sửa mã giảm giá">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if(!$coupon->trashed())
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xoá mã này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <a href="{{ route('admin.coupons.trash') }}" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-trash"></i> Thùng rác
        </a>
        <div>
            {{-- Nếu có phân trang, thêm ở đây --}}
            {{-- {{ $coupons->links() }} --}}
        </div>
    </div>
</div>
@endsection
