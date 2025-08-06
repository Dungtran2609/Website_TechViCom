@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Phân vai trò cho người dùng</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <a href="{{ route('admin.roles.list') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Danh sách vai trò
            </a>

            <form action="{{ route('admin.roles.updateUsers') }}" method="POST">
                @csrf
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Người dùng \ Vai trò</th>
                            @foreach ($roles as $role)
                                <th>{{ $role->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-start">
                                    <strong>{{ $user->name }}</strong><br>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </td>
                                @foreach ($roles as $role)
                                    <td>
                                        <input type="checkbox" name="roles[{{ $user->id }}][]"
                                            value="{{ $role->id }}"
                                            {{ $user->roles->pluck('id')->contains($role->id) ? 'checked' : '' }}
                                            @if ($user->id == auth()->id()) disabled @endif
                                        >
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật vai trò
                    </button>
                </div>
            </form>

            <div class="mt-3">
                {{-- {{ $users->links('pagination::bootstrap-5') }} --}}
            </div>
        </div>
    </div>
@endsection
