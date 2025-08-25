@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chi tiết mail động</h1>
    <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">
            <i class="fas fa-eye me-2"></i>
            Thông tin mẫu mail: {{ $mail->name }}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Tên mẫu mail</label>
                    <div class="form-control-plaintext fw-bold text-dark">{{ $mail->name }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Loại mail</label>
                    <div class="form-control-plaintext">
                        @if($mail->type)
                            <span class="badge bg-info text-dark">{{ $mail->type }}</span>
                        @else
                            <span class="badge bg-secondary">Không xác định</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-muted">Tiêu đề mail</label>
            <div class="form-control-plaintext text-primary fw-medium">{{ $mail->subject }}</div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Trạng thái kích hoạt</label>
                    <div class="form-control-plaintext">
                        @if($mail->is_active)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Đang hoạt động
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-times-circle me-1"></i>Đã tắt
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Tự động gửi</label>
                    <div class="form-control-plaintext">
                        @if($mail->auto_send)
                            <span class="badge bg-success">
                                <i class="fas fa-bolt me-1"></i>Bật
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-power-off me-1"></i>Tắt
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-muted">Nội dung mail</label>
            <div class="border rounded p-3 bg-light" style="min-height: 200px; max-height: 400px; overflow-y: auto;">
                <div class="text-dark">
                    {!! nl2br(e($mail->content)) !!}
                </div>
            </div>
            <div class="form-text mt-2">
                <i class="fas fa-info-circle me-1"></i>
                Nội dung mail sẽ được gửi tự động khi có sự kiện tương ứng.
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Ngày tạo</label>
                    <div class="form-control-plaintext">
                        <i class="fas fa-calendar-plus me-1 text-muted"></i>
                        {{ $mail->created_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Cập nhật lần cuối</label>
                    <div class="form-control-plaintext">
                        <i class="fas fa-calendar-edit me-1 text-muted"></i>
                        {{ $mail->updated_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('admin.mails.edit', $mail->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Sửa
            </a>
            <a href="{{ route('admin.mails.send') }}" class="btn btn-primary">
                <i class="fas fa-paper-plane me-1"></i>Gửi mail
            </a>
            <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary">
                <i class="fas fa-list me-1"></i>Danh sách
            </a>
        </div>
    </div>
</div>

<style>
.form-control-plaintext {
    background-color: transparent;
    border: none;
    padding: 0.375rem 0;
    margin-bottom: 0;
    font-size: 1rem;
    line-height: 1.5;
    color: #212529;
    background-color: transparent;
    border: 0;
    border-radius: 0;
}
.form-control-plaintext:focus {
    outline: 0;
    box-shadow: none;
}
</style>
@endsection
