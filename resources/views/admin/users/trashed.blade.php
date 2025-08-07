@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Thùng rác tài khoản</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Trở về danh sách
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Danh sách tài khoản đã bị xóa ({{ $users->total() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ và tên</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>Ngày xóa</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->roles->isNotEmpty())
                                                @foreach ($user->roles as $role)
                                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Không có vai trò</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <!-- Khôi phục -->
                                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-success"
                                                        data-bs-toggle="tooltip" title="Khôi phục tài khoản này"
                                                        onclick="return confirm('Bạn có chắc chắn muốn khôi phục tài khoản này?')">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                            <!-- Xóa vĩnh viễn -->
                                            <form action="{{ route('admin.users.force-delete', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip" title="Xóa vĩnh viễn tài khoản này"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn tài khoản này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> Không có tài khoản nào trong thùng rác.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

