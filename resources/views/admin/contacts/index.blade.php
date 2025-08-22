@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý liên hệ người dùng</h1>
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
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
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
                                    
                                    @if(in_array($contact->status, ['responded', 'rejected']))
                                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá liên hệ này?')" title="Xoá">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-soft-secondary btn-sm" disabled title="Chỉ có thể xóa liên hệ đã phản hồi hoặc bị từ chối">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    @endif
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
