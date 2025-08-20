@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
	<div class="row justify-content-center">
		<div class="col-lg-11 col-md-12">
			<div class="card shadow border-0">
				<div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
					<div class="d-flex align-items-center">
						<i class="bi bi-envelope-paper-fill me-2" style="font-size:1.5rem"></i>
						<h4 class="mb-0">Danh sách mail động</h4>
					</div>
					<div>
						<a href="{{ route('admin.mails.create') }}" class="btn btn-success me-2"><i class="bi bi-plus-circle me-1"></i> Thêm mail mới</a>
						<a href="{{ route('admin.mails.trash') }}" class="btn btn-danger"><i class="bi bi-trash3 me-1"></i> Thùng rác</a>
					</div>
				</div>
				<div class="card-body p-0">
					<div class="p-3 pb-0">
						<form method="GET" action="" class="input-group mb-2" style="max-width:400px">
							<span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
							<input type="text" name="q" class="form-control" placeholder="Tìm kiếm mail..." value="{{ request('q') }}">
							<button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Tìm kiếm</button>
						</form>
					</div>
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0" id="mail-table">
							<thead class="table-light">
								<tr>
									<th>ID</th>
									<th>Tên</th>
									<th>Tiêu đề</th>
									<th>Loại</th>
									<th>Kích hoạt</th>
									<th>Tự động gửi</th>
									<th class="text-center">Hành động</th>
								</tr>
							</thead>
							<tbody>
							@forelse($mails as $mail)
								<tr class="mail-row">
									<td class="text-muted">#{{ $mail->id }}</td>
									<td class="fw-bold">{{ $mail->name }}</td>
									<td class="text-primary">{{ $mail->subject }}</td>
									<td><span class="badge bg-secondary">{{ $mail->type ?: 'Không xác định' }}</span></td>
									<td>
										@if($mail->is_active)
											<span class="badge bg-success">Bật</span>
										@else
											<span class="badge bg-secondary">Tắt</span>
										@endif
									</td>
									<td>
										<form action="{{ route('admin.mails.toggleAutoSend', $mail->id) }}" method="POST" style="display:inline-block">
											@csrf
											<button type="submit" class="btn btn-sm {{ $mail->auto_send ? 'btn-success' : 'btn-outline-secondary' }}" title="Chuyển trạng thái tự động gửi">
												<i class="bi bi-lightning-charge"></i> {{ $mail->auto_send ? 'Bật' : 'Tắt' }}
											</button>
										</form>
									</td>
									<td class="text-center">
										<a href="{{ route('admin.mails.show', $mail->id) }}" class="btn btn-info btn-sm me-1" title="Xem chi tiết"><i class="bi bi-eye"></i></a>
										<a href="{{ route('admin.mails.edit', $mail->id) }}" class="btn btn-warning btn-sm me-1" title="Sửa"><i class="bi bi-pencil-square"></i></a>
										<form action="{{ route('admin.mails.destroy', $mail->id) }}" method="POST" style="display:inline-block">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa mail này?')" title="Xóa"><i class="bi bi-trash"></i></button>
										</form>
									</td>
								</tr>
							@empty
								<tr><td colspan="7" class="text-center text-muted">Chưa có mail động nào.</td></tr>
							@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Bootstrap Icons CDN (nếu chưa có) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
