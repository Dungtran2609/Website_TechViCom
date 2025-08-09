@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quản lý quyền</h1>
        {{-- [CẬP NHẬT] Thêm các nút đồng bộ vào nhóm hành động --}}
        <div class="d-flex flex-wrap gap-2">
            {{-- Nút đồng bộ (chỉ thêm mới) --}}
            <form action="{{ route('admin.permissions.sync') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-sync-alt"></i> Đồng bộ tự động
                </button>
            </form>

            {{-- Nút đồng bộ và dọn dẹp (thêm mới và xoá cũ) --}}
            <form action="{{ route('admin.permissions.sync', ['prune' => 'true']) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Bạn có chắc chắn muốn xoá các quyền không còn tồn tại trong hệ thống? Hành động này không thể hoàn tác.')">
                @csrf
                <button type="submit" class="btn btn-dark">
                    <i class="fas fa-broom"></i> Dọn dẹp quyền
                </button>
            </form>

            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-shield-alt"></i> Phân quyền
            </a>
            <a href="{{ route('admin.permissions.trashed') }}" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> Thùng rác
            </a>
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm quyền
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.permissions.list') }}" class="mb-4 d-flex gap-2 align-items-center">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25"
               placeholder="Tìm quyền...">
        <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
        @if (request('search'))
            <a href="{{ route('admin.permissions.list') }}" class="btn btn-secondary">Xoá lọc</a>
        @endif
    </form>

    {{-- [CẬP NHẬT] Thêm thông báo cho các loại session khác nhau --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
     @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên quyền</th>
                            <th>Mô tả</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->description ?? 'Không có mô tả' }}</td>
                                <td>
                                    <a href="{{ route('admin.permissions.edit', $permission) }}"
                                       class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xoá quyền này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Xoá">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Không tìm thấy quyền nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $permissions->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
