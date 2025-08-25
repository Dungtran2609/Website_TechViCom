@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý Hủy/Đổi trả</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Quản lý đơn hàng
        </a>
    </div>
</div>

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

<form method="GET" action="{{ route('admin.orders.returns') }}" class="mb-4">
    <div class="row g-3">
        <div class="col-md-2">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo mã đơn, khách hàng, lý do...">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã phê duyệt</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">Tất cả loại</option>
                <option value="cancel" {{ request('type') == 'cancel' ? 'selected' : '' }}>Hủy</option>
                <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Đổi/Trả</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" placeholder="Từ ngày">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" placeholder="Đến ngày">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    @if(request('keyword') || request('status') || request('type') || request('order_status') || request('date_from') || request('date_to'))
        <div class="mt-2">
            <a href="{{ route('admin.orders.returns') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
    @endif
</form>

@if (!isset($returns))
    <div class="alert alert-danger">Biến $returns không được truyền từ controller.</div>
@else
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>STT</th>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Loại</th>
                            <th>Lý do</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái yêu cầu</th>
                            <th>Trạng thái đơn</th>
                            <th>Ngày yêu cầu</th>
                            <th width="120px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $return)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $return['order_id'] }}</span>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0 text-dark fw-medium">{{ $return['user_name'] }}</h6>
                                    </div>
                                </td>
                                <td>
                                    @if($return['type'] === 'cancel')
                                        <span class="badge bg-danger">Hủy</span>
                                    @else
                                        <span class="badge bg-warning">Đổi/Trả</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 200px;">
                                        <span class="text-dark">{{ Str::limit($return['reason'], 40) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark fw-medium">{{ number_format($return['order_total'], 0) }} VND</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$return['status']] ?? 'light' }}">
                                        {{ $return['status_vietnamese'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $return['order_status_vietnamese'] }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $return['requested_at'] }}</span>
                                </td>
                                <td>
                                    @if($return['status'] === 'pending')
                                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#processModal{{ $return['id'] }}" title="Xử lý yêu cầu">
                                            <iconify-icon icon="solar:settings-linear" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    @else
                                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $return['id'] }}" title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-exchange-alt fa-2x mb-3"></i>
                                        <p>Không có yêu cầu hủy/đổi trả nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $pagination->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Modals cho từng yêu cầu -->
    @foreach($returns as $return)
        <!-- Modal xử lý yêu cầu -->
        @if($return['status'] === 'pending')
            <div class="modal fade" id="processModal{{ $return['id'] }}" tabindex="-1" aria-labelledby="processModalLabel{{ $return['id'] }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="processModalLabel{{ $return['id'] }}">Xử lý yêu cầu #{{ $return['id'] }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.orders.process-return', $return['id']) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Mã đơn hàng:</label>
                                        <p class="text-dark">{{ $return['order_id'] }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Khách hàng:</label>
                                        <p class="text-dark">{{ $return['user_name'] }}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Loại yêu cầu:</label>
                                        <p class="text-dark">{{ $return['type'] === 'cancel' ? 'Hủy' : 'Đổi/Trả' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Tổng tiền:</label>
                                        <p class="text-dark fw-medium">{{ number_format($return['order_total'], 0) }} VND</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Lý do:</label>
                                    <p class="text-dark">{{ $return['reason'] }}</p>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Phương thức thanh toán:</label>
                                        <p class="text-dark">{{ $return['payment_method_vietnamese'] }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Trạng thái đơn hàng:</label>
                                        <p class="text-dark">{{ $return['order_status_vietnamese'] }}</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="admin_note{{ $return['id'] }}" class="form-label fw-medium">Ghi chú của Admin <span class="text-danger">*</span>:</label>
                                    <textarea name="admin_note" id="admin_note{{ $return['id'] }}" class="form-control @error('admin_note') is-invalid @enderror" rows="4" placeholder="Nhập ghi chú khi xử lý yêu cầu..." >{{ old('admin_note') }}</textarea>
                                    <div class="invalid-feedback" id="admin_note_error{{ $return['id'] }}" style="display: none;">
                                        Vui lòng nhập ghi chú khi xử lý yêu cầu.
                                    </div>
                                    @error('admin_note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger">
                                    <i class="fas fa-times me-1"></i> Từ chối
                                </button>
                                <button type="submit" name="action" value="approve" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Chấp nhận
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal xem chi tiết -->
        <div class="modal fade" id="detailModal{{ $return['id'] }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $return['id'] }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel{{ $return['id'] }}">Chi tiết yêu cầu #{{ $return['id'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Mã đơn hàng:</label>
                                <p class="text-dark">{{ $return['order_id'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Khách hàng:</label>
                                <p class="text-dark">{{ $return['user_name'] }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Loại yêu cầu:</label>
                                <p class="text-dark">{{ $return['type'] === 'cancel' ? 'Hủy' : 'Đổi/Trả' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Tổng tiền:</label>
                                <p class="text-dark fw-medium">{{ number_format($return['order_total'], 0) }} VND</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Lý do:</label>
                            <p class="text-dark">{{ $return['reason'] }}</p>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Phương thức thanh toán:</label>
                                <p class="text-dark">{{ $return['payment_method_vietnamese'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Trạng thái đơn hàng:</label>
                                <p class="text-dark">{{ $return['order_status_vietnamese'] }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Ngày yêu cầu:</label>
                                <p class="text-dark">{{ $return['requested_at'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Ngày xử lý:</label>
                                <p class="text-dark">{{ $return['processed_at'] ?? 'Chưa xử lý' }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Ghi chú của Admin:</label>
                            <p class="text-dark">{{ $return['admin_note'] ?? 'Chưa có' }}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả các textarea admin_note
    const adminNotes = document.querySelectorAll('textarea[name="admin_note"]');
    
    adminNotes.forEach(function(textarea) {
        const returnId = textarea.id.replace('admin_note', '');
        const errorDiv = document.getElementById('admin_note_error' + returnId);
        const submitButtons = textarea.closest('form').querySelectorAll('button[type="submit"]');
        
        // Validate khi người dùng nhập
        textarea.addEventListener('input', function() {
            validateAdminNote(textarea, errorDiv, submitButtons);
        });
        
        // Validate khi người dùng blur (rời khỏi field)
        textarea.addEventListener('blur', function() {
            validateAdminNote(textarea, errorDiv, submitButtons);
        });
        
        // Validate khi submit form
        textarea.closest('form').addEventListener('submit', function(e) {
            if (!validateAdminNote(textarea, errorDiv, submitButtons)) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    function validateAdminNote(textarea, errorDiv, submitButtons) {
        const value = textarea.value.trim();
        const isValid = value.length > 0;
        
        if (isValid) {
            // Ẩn lỗi
            textarea.classList.remove('is-invalid');
            errorDiv.style.display = 'none';
            
            // Enable các nút submit
            submitButtons.forEach(btn => {
                btn.disabled = false;
            });
        } else {
            // Hiển thị lỗi
            textarea.classList.add('is-invalid');
            errorDiv.style.display = 'block';
            
            // Disable các nút submit
            submitButtons.forEach(btn => {
                btn.disabled = true;
            });
        }
        
        return isValid;
    }
});
</script>
@endsection