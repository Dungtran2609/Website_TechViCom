@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow border-0">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <i class="bi bi-envelope-paper-fill me-2" style="font-size:1.5rem"></i>
                    <h4 class="mb-0">Chi tiết mail động</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3"><b>Tên mẫu:</b> <span class="fw-bold">{{ $mail->name }}</span></div>
                    <div class="mb-3"><b>Tiêu đề:</b> <span class="text-primary">{{ $mail->subject }}</span></div>
                    <div class="mb-3"><b>Loại:</b> <span class="badge bg-secondary">{{ $mail->type ?: 'Không xác định' }}</span></div>
                    <div class="mb-3">
                        <b>Kích hoạt:</b> {!! $mail->is_active ? '<span class="badge bg-success">Bật</span>' : '<span class="badge bg-secondary">Tắt</span>' !!}
                        <b class="ms-3">Tự động gửi:</b> {!! $mail->auto_send ? '<span class="badge bg-success">Bật</span>' : '<span class="badge bg-secondary">Tắt</span>' !!}
                    </div>
                    <div class="mb-3">
                        <b>Nội dung:</b>
                        <div class="border rounded p-3 bg-light mt-2" style="min-height:120px">
                            {!! nl2br(e($mail->content)) !!}
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('admin.mails.edit', $mail->id) }}" class="btn btn-warning"><i class="bi bi-pencil-square me-1"></i> Sửa</a>
                        <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons CDN (nếu chưa có) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
