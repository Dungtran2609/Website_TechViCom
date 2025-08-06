@extends('admin.layouts.app')

@section('content')
    <h1>Thùng rác - Quyền đã xoá</h1>

    <a href="{{ route('admin.permissions.list') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên quyền</th>
                        <th>Mô tả</th>
                        <th>Ngày xoá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->description ?? 'Không có mô tả' }}</td>
                            <td>{{ $permission->deleted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.permissions.restore', $permission->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Khôi phục quyền này?')">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.permissions.force-delete', $permission->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xoá vĩnh viễn quyền này?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Không có quyền nào trong thùng rác.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $permissions->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
