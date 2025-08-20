@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-danger text-white d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-trash3-fill me-2 fs-4"></i>
                <span class="fs-5 fw-bold">Thùng rác mail</span>
            </div>
            <a href="{{ route('admin.mails.index') }}" class="btn btn-light text-danger"><i class="bi bi-arrow-left"></i> Quay lại danh sách</a>
        </div>
        <div class="card-body p-0">
            <div class="p-3 pb-0">
                <form method="GET" action="" class="input-group mb-2" style="max-width:400px">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Tìm kiếm mail..." value="{{ request('q') }}">
                    <button class="btn btn-danger" type="submit"><i class="bi bi-search"></i> Tìm kiếm</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Tên</th>
                            <th>Tiêu đề</th>
                            <th>Loại</th>
                            <th>Đã xóa lúc</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($mails as $mail)
                        <tr>
                            <td class="text-center fw-bold">{{ $mail->id }}</td>
                            <td>{{ $mail->name }}</td>
                            <td>{{ $mail->subject }}</td>
                            <td>
                                <span class="badge bg-info text-dark px-3 py-2">
                                    <i class="bi bi-envelope-paper me-1"></i> {{ ucfirst($mail->type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <i class="bi bi-calendar-x text-danger me-1"></i>
                                    {{ $mail->deleted_at ? \Carbon\Carbon::parse($mail->deleted_at)->format('d/m/Y H:i') : '--' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.mails.restore', $mail->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm me-1"><i class="bi bi-arrow-clockwise"></i> Khôi phục</button>
                                </form>
                                <form action="{{ route('admin.mails.forceDelete', $mail->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Xóa vĩnh viễn mail này?')"><i class="bi bi-trash"></i> Xóa vĩnh viễn</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Không có mail nào trong thùng rác.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
