@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Danh sách mã giảm giá</h1>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Thêm mã mới
    </a>
</div>

<form action="{{ route('admin.coupons.index') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="text" name="keyword" value="{{ request('keyword') }}"
               class="form-control" placeholder="Nhập mã cần tìm...">
    </div>
    <div class="col-md-2">
        <select name="discount_type" class="form-select">
            <option value="">-- Kiểu giảm --</option>
            <option value="percent" {{ request('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ request('discount_type') == 'fixed' ? 'selected' : '' }}>Cố định</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="apply_type" class="form-select">
            <option value="">-- Kiểu áp dụng --</option>
            <option value="all" {{ request('apply_type') == 'all' ? 'selected' : '' }}>Tất cả</option>
            <option value="product" {{ request('apply_type') == 'product' ? 'selected' : '' }}>Theo sản phẩm</option>
            <option value="category" {{ request('apply_type') == 'category' ? 'selected' : '' }}>Theo danh mục</option>
            <option value="user" {{ request('apply_type') == 'user' ? 'selected' : '' }}>Theo người dùng</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tạm dừng</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Từ ngày">
    </div>
    <div class="col-md-1 d-flex gap-1">
        <button type="submit" class="btn btn-outline-primary" title="Tìm kiếm">
            <i class="fas fa-search"></i>
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary" title="Làm mới">
            <i class="fas fa-times"></i>
        </a>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>STT</th>
                        <th>Mã</th>
                        <th>Kiểu giảm</th>
                        <th>Kiểu áp dụng</th>
                        <th>Giá trị</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Trạng thái</th>
                        <th width="120px">Hành động</th>
                    </tr>
                </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    @php
                        $typeMapping = ['percent' => 'Phần trăm', 'fixed' => 'Cố định'];
                        $applyTypeMapping = [
                            'all' => 'Tất cả',
                            'product' => 'Theo sản phẩm',
                            'category' => 'Theo danh mục',
                            'user' => 'Theo người dùng',
                        ];
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="text-dark fw-medium">{{ $coupon->code }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $typeMapping[$coupon->discount_type] ?? 'Không xác định' }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $applyTypeMapping[$coupon->apply_type] ?? $coupon->apply_type }}</span>
                        </td>
                        <td>
                            <span class="text-primary fw-medium">
                                {{ $coupon->discount_type === 'percent' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', '.') . '₫' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            @if ($coupon->status)
                                <span class="badge bg-success">Kích hoạt</span>
                            @else
                                <span class="badge bg-warning text-dark">Tạm dừng</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.coupons.show', $coupon->id) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-light btn-sm" title="Sửa mã giảm giá">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$coupon->trashed())
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xoá mã này?');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $coupon->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xoá">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-ticket-alt fa-2x mb-3"></i>
                                <p>Không có mã giảm giá nào</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
            </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('admin.coupons.trash') }}" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-trash me-1"></i> Thùng rác
                </a>
            </div>
            <div>
                <!-- Pagination placeholder -->
            </div>
        </div>
    </div>
</div>
@endsection
