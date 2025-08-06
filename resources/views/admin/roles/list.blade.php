@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quản lý vai trò</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Trở về trang vai trò
            </a>
            <a href="{{ route('admin.roles.trashed') }}" class="btn btn-danger">
                <i class="fas fa-trash"></i> Thùng rác
            </a>
            {{-- <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm vai trò
            </a> --}}
        </div>
    </div>

    <form method="GET" action="{{ route('admin.roles.index') }}" class="mb-4 d-flex gap-2 align-items-center">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25"
            placeholder="Tìm vai trò...">
        <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>

        @if (request('search'))
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-undo"></i> Quay lại danh sách đầy đủ
            </a>
        @endif
    </form>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>ID</th>
                            <th>Tên vai trò</th>
                            <th>Mô tả</th>
                            <th>Số quyền</th>
                            <th>Trạng thái</th>
                            <th width="200px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>
                                    <p class="text-dark fw-medium fs-15 mb-0">{{ $role->name }}</p>
                                </td>
                                <td>{{ $role->description ?? 'Không có mô tả' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $role->permissions->count() }} quyền</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $role->is_active ? 'success' : 'secondary' }}">
                                        {{ $role->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-light btn-sm"
                                            title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                            class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                            <iconify-icon icon="solar:pen-2-broken"
                                                class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn xoá vai trò này?')"
                                                title="Xoá">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary">Không tìm thấy vai trò nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $roles->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
