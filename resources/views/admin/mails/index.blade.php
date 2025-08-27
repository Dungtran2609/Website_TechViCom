@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh sách mail động</h1>
        <div>
            <a href="{{ route('admin.mails.create') }}" class="btn btn-success me-2">
                <i class="fas fa-plus me-1"></i> Thêm mail mới
            </a>
            <a href="{{ route('admin.mails.trash') }}" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Thùng rác
            </a>
        </div>
    </div>

    <form method="GET" action="" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="q" class="form-control" placeholder="Tìm kiếm mail..." value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">-- Loại mail --</option>
                <option value="order" {{ request('type') == 'order' ? 'selected' : '' }}>Đơn hàng</option>
                <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>Người dùng</option>
                <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>Hệ thống</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tắt</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-outline-primary" title="Tìm kiếm">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('admin.mails.index') }}" class="btn btn-outline-secondary" title="Làm mới">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>ID</th>
                            <th>Tên mail</th>
                            <th>Tiêu đề</th>
                            <th>Loại</th>
                            <th>Trạng thái</th>
                            <th>Tự động gửi</th>
                            <th width="120px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mails as $mail)
                            <tr>
                                <td>
                                    <span class="text-muted">#{{ $mail->id }}</span>
                                </td>
                                <td>
                                    <span class="text-dark fw-medium">{{ $mail->name }}</span>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $mail->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <span class="text-primary">{{ $mail->subject }}</span>
                                </td>
                                <td>
                                    @if($mail->type)
                                        <span class="badge bg-info text-dark">{{ $mail->type }}</span>
                                    @else
                                        <span class="badge bg-secondary">Không xác định</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mail->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Kích hoạt
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-times-circle me-1"></i>Tắt
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.mails.toggleAutoSend', $mail->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $mail->auto_send ? 'btn-success' : 'btn-outline-secondary' }}" title="Chuyển trạng thái tự động gửi">
                                            <i class="fas fa-bolt me-1"></i>
                                            {{ $mail->auto_send ? 'Bật' : 'Tắt' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.mails.show', $mail->id) }}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.mails.edit', $mail->id) }}" class="btn btn-warning btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.mails.destroy', $mail->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa mail này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-envelope fa-2x mb-3"></i>
                                        <p>Chưa có mail động nào</p>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.mails.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> Tạo mail đầu tiên
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($mails instanceof \Illuminate\Pagination\LengthAwarePaginator && $mails->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">
                    Hiển thị {{ $mails->firstItem() ?? 0 }} - {{ $mails->lastItem() ?? 0 }} trong tổng số {{ $mails->total() }} mail
                </small>
            </div>
            <div>
                {{ $mails->links() }}
            </div>
        </div>
    </div>
    @endif
@endsection
