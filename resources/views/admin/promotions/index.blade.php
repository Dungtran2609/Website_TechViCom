@extends('admin.layouts.app')
@section('title', 'Danh sách chương trình khuyến mãi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold text-dark">Danh sách chương trình khuyến mãi</h1>
        <a href="{{ route('admin.promotions.create') }}" 
           class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i> Tạo chương trình mới
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên chương trình</th>
                            <th>Kiểu</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promotions as $promotion)
                        <tr>
                            <td>{{ $promotion->id }}</td>
                            <td class="fw-semibold">{{ $promotion->name }}</td>
                            <td>{{ $promotion->type }}</td>
                            <td>
                                <span class="text-muted small">
                                    {{ $promotion->start_date }} → {{ $promotion->end_date }}
                                </span>
                            </td>
                            <td>
                                @if($promotion->status)
                                    <span class="badge bg-success">Kích hoạt</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.promotions.edit', $promotion->id) }}" 
                                   class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i> Sửa
                                </a>
                                <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Xóa chương trình này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $promotions->links() }}
    </div>
</div>
@endsection
