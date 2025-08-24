@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý liên hệ người dùng</h1>
    <div>
        <a href="{{ route('admin.contacts.trashed') }}" class="btn btn-danger">
            <i class="fas fa-trash me-1"></i> Thùng rác
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.contacts.index') }}" class="mb-4">
    <div class="row g-3">
        <div class="col-md-3">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên, email, SĐT, tiêu đề...">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang phản hồi</option>
                <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Đã phản hồi thành công</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Phản hồi thất bại</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="handled_by" class="form-select">
                <option value="">Tất cả người xử lý</option>
                @foreach($handlers as $handler)
                    <option value="{{ $handler->id }}" {{ request('handled_by') == $handler->id ? 'selected' : '' }}>
                        {{ $handler->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-1">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    @if(request('keyword') || request('status') || request('handled_by'))
        <div class="mt-2">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
    @endif
</form>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-left: 4px solid #dc3545 !important;">
        <div class="d-flex align-items-start">
            <i class="fas fa-exclamation-triangle me-3 mt-1 text-danger"></i>
            <div class="flex-grow-1">
                <strong class="d-block mb-1">Không thể xóa liên hệ!</strong>
                <div class="text-danger-emphasis">
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<!-- Alert thông báo lỗi JavaScript (ẩn ban đầu) -->
<div id="deleteErrorAlert" class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-left: 4px solid #dc3545 !important; display: none;">
    <div class="d-flex align-items-start">
        <i class="fas fa-exclamation-triangle me-3 mt-1 text-danger"></i>
        <div class="flex-grow-1">
            <strong class="d-block mb-1">Không thể xóa liên hệ!</strong>
            <div id="deleteErrorMessage" class="text-danger-emphasis"></div>
        </div>
        <button type="button" class="btn-close ms-2" onclick="hideDeleteError()" aria-label="Close"></button>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>STT</th>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Tiêu đề</th>
                        <th>Người gửi</th>
                        <th>Trạng thái</th>
                        <th>Người xử lý</th>
                        <th>Ngày gửi</th>
                        <th>Ngày đã xử lý</th>
                        <th width="120px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contacts as $contact)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $contact->id }}</span>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-0 text-dark fw-medium">{{ $contact->name }}</h6>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $contact->email }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $contact->phone }}</span>
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 200px;">
                                    <span class="text-dark fw-medium">{{ Str::limit($contact->subject, 40) }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $contact->user?->name ?? 'Khách' }}</span>
                            </td>
                            <td>
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
                                <span class="badge bg-{{ $colors[$contact->status] ?? 'light' }}">
                                    {{ $statuses[$contact->status] ?? ucfirst($contact->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $contact->handledByUser?->name ?? 'Chưa xử lý' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $contact->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td>
                                @if($contact->responded_at)
                                    <span class="text-success fw-medium">{{ $contact->responded_at->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-muted">Chưa xử lý</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    
                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm" 
                                                onclick="return checkDeleteConditions({{ $contact->id }}, '{{ addslashes($contact->subject) }}', '{{ $contact->status }}', {{ $contact->is_read ? 'true' : 'false' }}, '{{ $contact->created_at->format('Y-m-d H:i:s') }}')" 
                                                title="Xoá">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-envelope fa-2x mb-3"></i>
                                    <p>Không có liên hệ nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        {{ $contacts->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
function checkDeleteConditions(contactId, subject, status, isRead, createdAt) {
    // Kiểm tra các điều kiện xóa
    let errorMessage = '';
    
    // Kiểm tra trạng thái
    if (status === 'pending') {
        errorMessage = `Không thể xóa liên hệ "${subject}" vì đang ở trạng thái chờ xử lý. Vui lòng xử lý liên hệ trước khi xóa.`;
    } else if (status === 'in_progress') {
        errorMessage = `Không thể xóa liên hệ "${subject}" vì đang được xử lý. Vui lòng hoàn thành việc xử lý trước khi xóa.`;
    } else if (!isRead) {
        errorMessage = `Không thể xóa liên hệ "${subject}" vì chưa được đọc. Vui lòng đọc và xử lý liên hệ trước khi xóa.`;
    } else {
        // Kiểm tra thời gian tạo (ít nhất 24h)
        const createdDate = new Date(createdAt);
        const now = new Date();
        const hoursDiff = (now - createdDate) / (1000 * 60 * 60);
        
        if (hoursDiff < 24) {
            errorMessage = `Không thể xóa liên hệ "${subject}" vì được tạo chưa đủ 24 giờ. Vui lòng đợi ít nhất 24 giờ sau khi tạo.`;
        }
    }
    
    // Nếu có lỗi, hiển thị alert và ngăn xóa
    if (errorMessage) {
        showDeleteError(errorMessage);
        return false;
    }
    
    // Nếu không có lỗi, hiển thị confirm và cho phép xóa
    return confirm('Bạn có chắc muốn xoá liên hệ này?');
}

function showDeleteError(message) {
    document.getElementById('deleteErrorMessage').innerHTML = message;
    const alert = document.getElementById('deleteErrorAlert');
    alert.style.display = 'block';
    
    // Scroll đến alert
    alert.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    // Tự động ẩn sau 5 giây
    setTimeout(() => {
        hideDeleteError();
    }, 5000);
}

function hideDeleteError() {
    document.getElementById('deleteErrorAlert').style.display = 'none';
}
</script>
@endpush

@push('styles')
<style>
#deleteErrorAlert {
    border-radius: 10px;
    margin-bottom: 20px;
    animation: slideInDown 0.3s ease-out;
}

#deleteErrorAlert .btn-close {
    transition: all 0.3s ease;
}

#deleteErrorAlert .btn-close:hover {
    transform: scale(1.1);
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush
