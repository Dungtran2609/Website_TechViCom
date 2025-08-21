@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Phân quyền cho vai trò</h1>
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Thêm quyền mới
        </a>
    </div>

    @if ($roles->contains('name', 'user'))
        <div class="alert alert-info">
            Vai trò <strong>user (khách hàng)</strong> bị hạn chế, không thể thực hiện các quyền quản trị.
        </div>
    @elseif (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Bộ lọc tìm kiếm -->
    <form method="GET" action="{{ route('admin.permissions.index') }}" class="row g-3 mb-4 align-items-end">
        <div class="col-md-4">
            <label for="permission_name" class="form-label">Tìm theo tên quyền</label>
            <input type="text" name="permission_name" id="permission_name" class="form-control" value="{{ request('permission_name') }}" placeholder="Nhập tên quyền...">
        </div>
        <div class="col-md-4">
            <label for="module" class="form-label">Nhóm quyền</label>
            <select name="module" id="module" class="form-select">
                <option value="">Tất cả nhóm chức năng</option>
                @if(isset($modules))
                    @foreach($modules as $m)
                        <option value="{{ $m }}" {{ request('module') == $m ? 'selected' : '' }}>{{ ucfirst($m) }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Lọc</button>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary ms-2">Đặt lại</a>
        </div>
    </form>

    <form action="{{ route('admin.permissions.updateRoles') }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-body">
                <a href="{{ route('admin.permissions.list') }}" class="btn btn-success mb-3">
                    <i class="fas fa-list"></i> Danh sách phân quyền
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start">Quyền \ Vai trò</th>
                                @foreach ($roles as $role)
                                    <th>{{ $role->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td class="text-start">
                                        <strong>{{ $permission->name }}</strong><br>
                                        <small class="text-muted">{{ $permission->description }}</small>
                                    </td>
                                    @foreach ($roles as $role)
                                        <td>
                                            <input type="checkbox"
                                                name="permissions[{{ $role->id }}][]"
                                                value="{{ $permission->id }}"
                                                {{ $role->permissions->pluck('id')->contains($permission->id) ? 'checked' : '' }}
                                            >
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật quyền
                    </button>
                </div>
            </div>
            <!-- Không phân trang, hiển thị toàn bộ quyền -->
        </div>
    </form>
@endsection
