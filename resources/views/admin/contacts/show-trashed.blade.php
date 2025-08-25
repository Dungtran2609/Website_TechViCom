@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chi tiết liên hệ đã xóa</h1>
    <div>
        <a href="{{ route('admin.contacts.trashed') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại thùng rác
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-trash me-2"></i>Thông tin liên hệ đã xóa
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Họ tên:</label>
                        <p class="form-control-plaintext">{{ $contact->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email:</label>
                        <p class="form-control-plaintext">{{ $contact->email ?? 'Không có' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Số điện thoại:</label>
                        <p class="form-control-plaintext">{{ $contact->phone ?? 'Không có' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Người gửi:</label>
                        <p class="form-control-plaintext">{{ $contact->user?->name ?? 'Khách' }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu đề:</label>
                    <p class="form-control-plaintext">{{ $contact->subject }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nội dung:</label>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($contact->message)) !!}
                    </div>
                </div>

                @if($contact->response)
                <div class="mb-3">
                    <label class="form-label fw-bold">Phản hồi:</label>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($contact->response)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Thông tin xử lý
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Trạng thái:</label>
                    @php
                        $statuses = [
                            'pending' => 'Chờ xử lý',
                            'in_progress' => 'Đang phản hồi',
                            'responded' => 'Đã phản hồi thành công',
                            'rejected' => 'Phản hồi thất bại',
                        ];
                        $colors = [
                            'pending' => 'warning',
                            'in_progress' => 'info',
                            'responded' => 'success',
                            'rejected' => 'danger',
                        ];
                    @endphp
                    <div>
                        <span class="badge bg-{{ $colors[$contact->status] ?? 'light' }} fs-6">
                            {{ $statuses[$contact->status] ?? ucfirst($contact->status) }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Người xử lý:</label>
                    <p class="form-control-plaintext">{{ $contact->handledByUser?->name ?? 'Chưa xử lý' }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Ngày gửi:</label>
                    <p class="form-control-plaintext">{{ $contact->created_at->format('d/m/Y H:i:s') }}</p>
                </div>

                @if($contact->responded_at)
                <div class="mb-3">
                    <label class="form-label fw-bold">Ngày phản hồi:</label>
                    <p class="form-control-plaintext">{{ $contact->responded_at->format('d/m/Y H:i:s') }}</p>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">Ngày xóa:</label>
                    <p class="form-control-plaintext text-danger">{{ $contact->deleted_at->format('d/m/Y H:i:s') }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Đã đọc:</label>
                    <p class="form-control-plaintext">
                        @if($contact->is_read)
                            <span class="badge bg-success">Đã đọc</span>
                        @else
                            <span class="badge bg-warning">Chưa đọc</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Hành động
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.contacts.restore', $contact) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-undo me-2"></i>Khôi phục liên hệ
                        </button>
                    </form>

                    <form action="{{ route('admin.contacts.force-delete', $contact) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn liên hệ này? Hành động này không thể hoàn tác!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash-alt me-2"></i>Xóa vĩnh viễn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
