@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
	<div class="row justify-content-center">
		<div class="col-lg-7 col-md-10">
			<div class="card shadow border-0">
				<div class="card-header bg-primary text-white d-flex align-items-center">
					<i class="bi bi-envelope-paper-fill me-2" style="font-size:1.5rem"></i>
					<h4 class="mb-0">Gửi mail động</h4>
				</div>
				<div class="card-body">
					@if(session('success'))
						<div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
					@elseif(session('error'))
						<div class="alert alert-danger"><i class="bi bi-x-circle me-1"></i> {{ session('error') }}</div>
					@endif
					<form action="{{ route('admin.mails.send') }}" method="POST" autocomplete="off">
						@csrf
						<div class="mb-3">
							<label class="form-label fw-bold">Chọn mẫu mail <span class="text-danger">*</span></label>
							<input type="text" id="mail-template-search" class="form-control mb-2" placeholder="Tìm kiếm mẫu mail...">
							<div class="mail-template-checkbox-list border rounded p-2 bg-light" style="max-height:220px;overflow-y:auto">
								<div class="row">
									@foreach($mailTemplates as $mail)
										<div class="col-12 mb-1 mail-template-checkbox-item">
											<label class="form-check-label w-100">
												<input type="checkbox" name="mail_ids[]" value="{{ $mail->id }}" class="form-check-input me-1" required>
												<span class="fw-normal">{{ $mail->name }}</span>
												<small class="text-muted">({{ $mail->subject }})</small>
											</label>
										</div>
									@endforeach
								</div>
							</div>
							<div class="form-text">Tìm kiếm và tick chọn nhiều mẫu mail để gửi.</div>
						</div>
							<div class="mb-3">
								<label class="form-label fw-bold">Chọn tài khoản nhận <small class="text-muted">(có thể chọn nhiều)</small></label>
								<input type="text" id="user-search" class="form-control mb-2" placeholder="Tìm kiếm tài khoản...">
								<div class="user-checkbox-list border rounded p-2 bg-light" style="max-height:260px;overflow-y:auto">
									<div class="row">
										@foreach($users as $user)
											<div class="col-12 col-md-6 mb-1 user-checkbox-item">
												<label class="form-check-label w-100">
													<input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-check-input me-1">
													<span class="fw-normal">{{ $user->name }}</span>
													<small class="text-muted">({{ $user->email }})</small>
												</label>
											</div>
										@endforeach
									</div>
								</div>
								<div class="form-text">Tìm kiếm nhanh và tick chọn tài khoản cần gửi.</div>
							</div>
						<div class="mb-3" id="coupon-code-group" style="display:none">
							<label for="coupon_code" class="form-label fw-bold">Mã giảm giá <span class="text-danger">*</span></label>
							<input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Nhập mã giảm giá...">
						</div>
						<div class="mb-3">
							<label for="emails" class="form-label fw-bold">Hoặc nhập email bất kỳ <small class="text-muted">(cách nhau dấu phẩy)</small></label>
							<input type="text" name="emails" id="emails" class="form-control" placeholder="email1@example.com, email2@example.com">
						</div>
						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send me-1"></i> Gửi mail</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Bootstrap Icons CDN (nếu chưa có) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	// Tìm kiếm mẫu mail
	const mailTemplateSearch = document.getElementById('mail-template-search');
	if (mailTemplateSearch) {
		mailTemplateSearch.addEventListener('input', function() {
			const val = this.value.toLowerCase();
			document.querySelectorAll('.mail-template-checkbox-item').forEach(function(item) {
				const text = item.textContent.toLowerCase();
				item.style.display = text.includes(val) ? '' : 'none';
			});
		});
	}

	// Tìm kiếm user
	const searchInput = document.getElementById('user-search');
	if (searchInput) {
		searchInput.addEventListener('input', function() {
			const val = this.value.toLowerCase();
			document.querySelectorAll('.user-checkbox-item').forEach(function(item) {
				const text = item.textContent.toLowerCase();
				item.style.display = text.includes(val) ? '' : 'none';
			});
		});
	}

	// Ẩn/hiện input mã giảm giá
	function toggleCouponInput() {
		let show = false;
		document.querySelectorAll('input[name="mail_ids[]"]:checked').forEach(function(checkbox) {
			const label = checkbox.closest('label');
			const text = label ? label.textContent.toLowerCase() : '';
			if (text.includes('giảm giá') || text.includes('coupon')) show = true;
		});
		const couponGroup = document.getElementById('coupon-code-group');
		if (show) {
			couponGroup.style.display = '';
		} else {
			couponGroup.style.display = 'none';
			document.getElementById('coupon_code').value = '';
		}
	}
	document.querySelectorAll('input[name="mail_ids[]"]').forEach(function(checkbox) {
		checkbox.addEventListener('change', toggleCouponInput);
	});
	toggleCouponInput();
});
</script>
@endsection
