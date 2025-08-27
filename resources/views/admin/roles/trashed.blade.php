@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Thùng rác vai trò</h1>
        <a href="{{ route('admin.roles.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Trở về danh sách
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Danh sách vai trò đã bị xóa ({{ $roles->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên vai trò</th>
                                    <th>Slug</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày xóa</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->slug }}</td>
                                        <td>
                                            <span class="badge bg-{{ $role->status ? 'success' : 'secondary' }}">
                                                {{ $role->status ? 'Kích hoạt' : 'Ẩn' }}
                                            </span>
                                        </td>
                                        <td>{{ $role->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <!-- Khôi phục -->
                                            <form action="{{ route('admin.roles.restore', $role->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Khôi phục vai trò này?')"
                                                        title="Khôi phục">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>

                                            <!-- Xóa vĩnh viễn -->
                                            <form action="{{ route('admin.roles.force-delete', $role->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Xóa vĩnh viễn vai trò này?')"
                                                        title="Xóa vĩnh viễn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> Không có vai trò nào trong thùng rác.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Phân trang nếu dùng paginate --}}
                    {{-- <div class="mt-3">
                        {{ $roles->links('pagination::bootstrap-5') }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
