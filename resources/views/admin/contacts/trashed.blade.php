@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thùng rác - Liên hệ đã xóa</h1>
    <div>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.contacts.trashed') }}" class="mb-4">
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
            <a href="{{ route('admin.contacts.trashed') }}" class="btn btn-outline-secondary btn-sm">
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
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-header bg-danger text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-trash me-2"></i>Liên hệ đã xóa ({{ $contacts->total() }})
            </h5>
            @if($contacts->count() > 0)
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-sm" onclick="selectAll()">
                        <i class="fas fa-check-square me-1"></i>Chọn tất cả
                    </button>
                    <button type="button" class="btn btn-light btn-sm" onclick="restoreSelected()">
                        <i class="fas fa-undo me-1"></i>Khôi phục đã chọn
                    </button>
                    <button type="button" class="btn btn-light btn-sm" onclick="forceDeleteSelected()">
                        <i class="fas fa-trash-alt me-1"></i>Xóa vĩnh viễn
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th width="50px">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
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
                        <th>Ngày xóa</th>
                        <th width="150px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contacts as $contact)
                        <tr>
                            <td>
                                <input type="checkbox" name="contact_ids[]" value="{{ $contact->id }}" class="form-check-input contact-checkbox">
                            </td>
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
                                <span class="text-danger fw-medium">{{ $contact->deleted_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.contacts.show-trashed', $contact) }}" class="btn btn-light btn-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.contacts.restore', $contact) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" title="Khôi phục">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.contacts.force-delete', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn liên hệ này? Hành động này không thể hoàn tác!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa vĩnh viễn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-trash fa-2x mb-3"></i>
                                    <p>Không có liên hệ nào trong thùng rác</p>
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

<!-- Form ẩn cho bulk actions -->
<form id="bulkRestoreForm" action="{{ route('admin.contacts.restore-multiple') }}" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="contact_ids" id="restoreContactIds">
</form>

<form id="bulkForceDeleteForm" action="{{ route('admin.contacts.force-delete-multiple') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="contact_ids" id="forceDeleteContactIds">
</form>
@endsection

@push('scripts')
<script>
function selectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
    
    contactCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function getSelectedContactIds() {
    const checkboxes = document.querySelectorAll('.contact-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function restoreSelected() {
    const selectedIds = getSelectedContactIds();
    if (selectedIds.length === 0) {
        alert('Vui lòng chọn ít nhất một liên hệ để khôi phục');
        return;
    }
    
    if (confirm(`Bạn có chắc muốn khôi phục ${selectedIds.length} liên hệ đã chọn?`)) {
        document.getElementById('restoreContactIds').value = JSON.stringify(selectedIds);
        document.getElementById('bulkRestoreForm').submit();
    }
}

function forceDeleteSelected() {
    const selectedIds = getSelectedContactIds();
    if (selectedIds.length === 0) {
        alert('Vui lòng chọn ít nhất một liên hệ để xóa vĩnh viễn');
        return;
    }
    
    if (confirm(`Bạn có chắc muốn xóa vĩnh viễn ${selectedIds.length} liên hệ đã chọn? Hành động này không thể hoàn tác!`)) {
        document.getElementById('forceDeleteContactIds').value = JSON.stringify(selectedIds);
        document.getElementById('bulkForceDeleteForm').submit();
    }
}

// Cập nhật trạng thái checkbox "Chọn tất cả"
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
    
    contactCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(contactCheckboxes).every(cb => cb.checked);
            const anyChecked = Array.from(contactCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = anyChecked && !allChecked;
        });
    });
});
</script>
@endpush
