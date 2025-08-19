@extends('admin.layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Quản lý logo</h3>
        <a href="{{ route('admin.logos.create') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Thêm logo</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Loại</th>
                    <th>Ảnh</th>
                    <th>Alt</th>
                    <th>Ngày tạo</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
            @forelse($logos as $logo)
                <tr>
                    <td>{{ $logo->id }}</td>
                    <td><span class="badge bg-info">{{ $logo->type }}</span></td>
                    <td><img src="{{ asset('storage/' . $logo->path) }}" alt="{{ $logo->alt }}" style="max-height:48px;"></td>
                    <td>{{ $logo->alt }}</td>
                    <td>{{ $logo->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.logos.edit', $logo->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Sửa</a>
                        <form action="{{ route('admin.logos.destroy', $logo->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xoá logo này?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Xoá</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Chưa có logo nào.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
