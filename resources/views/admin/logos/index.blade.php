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
        <table class="table table-hover align-middle shadow-sm border rounded">
            <thead class="table-light align-middle">
                <tr>
                    <th class="text-center">#</th>
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
                    <td class="text-center fw-bold">{{ $logo->id }}</td>
                    <td>
                        <span class="badge bg-info text-dark px-3 py-2 fs-6">
                            <i class="bi bi-bookmark-star me-1"></i> {{ ucfirst($logo->type) }}
                        </span>
                    </td>
                    <td>
                        <img src="{{ asset('storage/' . $logo->path) }}" alt="{{ $logo->alt }}" class="rounded shadow-sm border" style="max-height:48px; max-width:90px; object-fit:contain; background:#f8f9fa;">
                    </td>
                    <td>
                        <span class="d-inline-flex align-items-center">
                            <i class="bi bi-card-text text-primary me-1"></i> {{ $logo->alt }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1">
                            <i class="bi bi-calendar-event text-success me-1"></i>
                            {{ $logo->created_at->format('d/m/Y H:i') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.logos.edit', $logo->id) }}" class="btn btn-outline-warning btn-sm me-1" title="Sửa logo"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.logos.destroy', $logo->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xoá logo này?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Xoá logo"><i class="bi bi-trash"></i></button>
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
