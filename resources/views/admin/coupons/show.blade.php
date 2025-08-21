@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
	<div class="card">
		<div class="card-body">
			<h4 class="mb-4">Chi tiết mã giảm giá: <span class="text-primary">{{ $coupon->code }}</span></h4>
			<div class="row g-3">
				<div class="col-md-6">
					<strong>ID:</strong> {{ $coupon->id }}
				</div>
				<div class="col-md-6">
					<strong>Kiểu áp dụng:</strong> 
					@php
						$applyTypeMapping = [
							'all' => 'Tất cả',
							'product' => 'Theo sản phẩm',
							'category' => 'Theo danh mục',
							'user' => 'Theo người dùng',
						];
					@endphp
					{{ $applyTypeMapping[$coupon->apply_type] ?? $coupon->apply_type }}
				</div>
				<div class="col-md-6">
					<strong>Loại giảm giá:</strong> {{ $coupon->discount_type == 'percent' ? 'Phần trăm' : 'Cố định' }}
				</div>
				<div class="col-md-6">
					<strong>Giá trị:</strong> {{ $coupon->discount_type == 'percent' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', '.') . '₫' }}
				</div>
				<div class="col-md-6">
					<strong>Giảm tối đa:</strong> {{ number_format($coupon->max_discount_amount, 0, ',', '.') }}₫
				</div>
				<div class="col-md-6">
					<strong>Giá trị đơn tối thiểu:</strong> {{ number_format($coupon->min_order_value, 0, ',', '.') }}₫
				</div>
				<div class="col-md-6">
					<strong>Giá trị đơn tối đa:</strong> {{ number_format($coupon->max_order_value, 0, ',', '.') }}₫
				</div>
				<div class="col-md-6">
					<strong>Số lần dùng mỗi người:</strong> {{ $coupon->max_usage_per_user }}
				</div>
				<div class="col-md-6">
					<strong>Trạng thái:</strong> 
					@if ($coupon->status)
						<span class="badge bg-success">Kích hoạt</span>
					@else
						<span class="badge bg-danger">Tạm dừng</span>
					@endif
				</div>
				<div class="col-md-6">
					<strong>Ngày bắt đầu:</strong> {{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}
				</div>
				<div class="col-md-6">
					<strong>Ngày kết thúc:</strong> {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}
				</div>
				<div class="col-md-12">
					<strong>Người dùng áp dụng:</strong>
					@if($coupon->users && $coupon->users->count())
						<ul>
						@foreach($coupon->users as $user)
							<li>{{ $user->name }} ({{ $user->email }})</li>
						@endforeach
						</ul>
					@else
						<span class="text-muted">Không giới hạn</span>
					@endif
				</div>
				<div class="col-md-12">
					<strong>Danh mục áp dụng:</strong>
					@if($coupon->categories && $coupon->categories->count())
						<ul>
						@foreach($coupon->categories as $cat)
							<li>{{ $cat->name }}</li>
						@endforeach
						</ul>
					@else
						<span class="text-muted">Không giới hạn</span>
					@endif
				</div>
				<div class="col-md-12">
					<strong>Sản phẩm áp dụng:</strong>
					@if($coupon->products && $coupon->products->count())
						<ul>
						@foreach($coupon->products as $prod)
							<li>{{ $prod->name }}</li>
						@endforeach
						</ul>
					@else
						<span class="text-muted">Không giới hạn</span>
					@endif
				</div>
			</div>
			<div class="mt-4">
				<a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại</a>
				<a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-primary">Sửa</a>
			</div>
		</div>
	</div>
</div>
@endsection
