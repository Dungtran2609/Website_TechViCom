@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thùng rác mail động</h1>
    <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
</div>

<div class="card">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">
            <i class="fas fa-trash me-2"></i>
            Mail đã xóa
        </h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control" placeholder="Tìm kiếm mail..." value="{{ request('q') }}">
                        <button class="btn btn-danger" type="submit">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Tên mail</th>
                        <th>Tiêu đề</th>
                        <th>Loại</th>
                        <th>Đã xóa lúc</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mails as $mail)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $mail->id }}</span>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $mail->name }}</div>
                            </td>
                            <td>
                                <div class="text-primary">{{ $mail->subject }}</div>
                            </td>
                            <td>
                                @if($mail->type)
                                    <span class="badge bg-info text-dark">
                                        <i class="fas fa-envelope me-1"></i>{{ ucfirst($mail->type) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Không xác định</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-times text-danger me-2"></i>
                                    <span class="text-muted">
                                        {{ $mail->deleted_at ? \Carbon\Carbon::parse($mail->deleted_at)->format('d/m/Y H:i') : '--' }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <form action="{{ route('admin.mails.restore', $mail->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="Khôi phục">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.mails.forceDelete', $mail->id) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn mail này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa vĩnh viễn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-trash fa-2x mb-3"></i>
                                    <p>Không có mail nào trong thùng rác</p>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.mails.index') }}" class="btn btn-primary">
                                            <i class="fas fa-list me-1"></i> Xem danh sách mail
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mails instanceof \Illuminate\Pagination\LengthAwarePaginator && $mails->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <small class="text-muted">
                    Hiển thị {{ $mails->firstItem() ?? 0 }} - {{ $mails->lastItem() ?? 0 }} trong tổng số {{ $mails->total() }} mail đã xóa
                </small>
            </div>
            <div>
                {{ $mails->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(220, 53, 69, 0.05);
}
.badge {
    font-size: 0.75em;
}
</style>
@endsection
