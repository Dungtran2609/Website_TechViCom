@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý liên hệ người dùng</h1>
    </div>

    {{-- Form tìm kiếm --}}
    <form action="{{ route('admin.contacts.index') }}" method="GET" class="row g-2 mb-4">
        <div class="col-md-5">
            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                placeholder="Tìm theo tên, email, số điện thoại...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang phản hồi
                </option>
                <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Đã phản hồi thành công
                </option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Phản hồi thất bại</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter me-1"></i> Lọc
            </button>
        </div>
    </form>

    {{-- Thông báo thành công --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Bảng dữ liệu --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
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
                            <th>Ngày phản hồi</th>
                            <th>Ngày gửi</th>
                            <th class="text-center" style="width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contact->id }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ Str::limit($contact->subject, 30) }}</td>
                                <td>{{ $contact->user?->name ?? 'Không có' }}</td>
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
                                <td>{{ $contact->handledByUser?->name ?? 'Không có' }}</td>
                                <td>{{ $contact->updated_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-light btn-sm"
                                            title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="fs-5"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xoá liên hệ này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-soft-danger btn-sm" title="Xoá">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="fs-5"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Không có liên hệ nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        <div class="card-footer">
            {{ $contacts->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
