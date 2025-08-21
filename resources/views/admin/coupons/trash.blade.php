@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
	<div class="row justify-content-center">
		<div class="col-lg-11">
			<div class="card shadow-sm border-0 rounded-4">
				<div class="card-header bg-danger bg-gradient text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
					<div class="d-flex align-items-center gap-2">
						<iconify-icon icon="solar:trash-bin-trash-broken" class="fs-3"></iconify-icon>
						<span class="fw-bold fs-5">Thùng rác mã giảm giá</span>
					</div>
					<a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-semibold">
						<iconify-icon icon="solar:arrow-left-broken" class="align-middle"></iconify-icon> Quay lại danh sách
					</a>
				</div>
				<div class="card-body p-4">
					<div class="table-responsive">
						<table class="table table-hover align-middle rounded-3 overflow-hidden mb-0">
							<thead class="table-light">
								<tr>
									<th>ID</th>
									<th>Mã</th>
									<th>Kiểu giảm</th>
									<th>Kiểu áp dụng</th>
									<th>Giá trị</th>
									<th>Ngày bắt đầu</th>
									<th>Ngày kết thúc</th>
									<th>Hành động</th>
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
									<tr style="background: #fff; opacity:0.85;">
										<td><span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">{{ $coupon->id }}</span></td>
										<td class="fw-semibold text-primary-emphasis">{{ $coupon->code }}</td>
										<td>{{ $typeMapping[$coupon->discount_type] ?? 'Không xác định' }}</td>
										<td>{{ $applyTypeMapping[$coupon->apply_type] ?? $coupon->apply_type }}</td>
										<td>
											<span class="fw-bold">
												{{ $coupon->discount_type === 'percent' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', '.') . '₫' }}
											</span>
										</td>
										<td>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</td>
										<td>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</td>
										<td>
											<div class="d-flex gap-2 justify-content-center align-items-center">
												<form action="{{ route('admin.coupons.restore', $coupon->id) }}" method="POST" style="display:inline-block">
													@csrf
													@method('PUT')
													<button type="submit" class="btn btn-outline-success btn-circle btn-sm" title="Khôi phục">
														<iconify-icon icon="solar:arrow-up-broken" class="fs-5"></iconify-icon>
													</button>
												</form>
												<form action="{{ route('admin.coupons.forceDelete', $coupon->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xoá vĩnh viễn?');">
													@csrf
													@method('DELETE')
													<button type="submit" class="btn btn-outline-danger btn-circle btn-sm" title="Xoá vĩnh viễn">
														<iconify-icon icon="solar:trash-bin-trash-broken" class="fs-5"></iconify-icon>
													</button>
												</form>
											</div>
										</td>
									</tr>
								@empty
									<tr><td colspan="8" class="text-center text-muted py-4">Không có mã nào trong thùng rác.</td></tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@push('styles')
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<style>
	.btn-circle {
		border-radius: 50% !important;
		width: 38px;
		height: 38px;
		padding: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.1rem;
		transition: background 0.15s;
	}
	.btn-circle.btn-sm {
		width: 32px;
		height: 32px;
		font-size: 1rem;
	}
	.table thead th, .table tbody td {
		vertical-align: middle;
	}
	.table-hover tbody tr:hover {
		background: #f7f7fa;
	}
	.badge.bg-secondary {
		font-size: 15px;
		font-weight: 500;
		background: #e7eaf3 !important;
		color: #3a3a3a !important;
	}
</style>
@endpush
@endsection
