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

    <form action="{{ route('admin.permissions.updateRoles') }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-body">
                <a href="{{ route('admin.permissions.list') }}" class="btn btn-success mb-3">
                    <i class="fas fa-list"></i> Danh sách vai trò
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
        </div>
    </form>
@endsection
