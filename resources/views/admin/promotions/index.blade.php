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
            <div class="p-3 pb-0">
                <form method="GET" action="" class="input-group mb-2" style="max-width:400px">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Tìm kiếm chương trình..." value="{{ request('q') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Tìm kiếm</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên chương trình</th>
                            <th>Kiểu áp dụng</th>
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
                            <td>
                                @php
                                    $type = $promotion->flash_type ?? $promotion->type;
                                @endphp
                                @if($type === 'all')
                                    <span class="badge bg-primary"><i class="bi bi-globe"></i> Toàn bộ sản phẩm</span>
                                @elseif($type === 'category')
                                    <span class="badge bg-info text-dark"><i class="bi bi-tags"></i> Theo danh mục</span>
                                @elseif($type === 'flash_sale')
                                    <span class="badge bg-danger"><i class="bi bi-lightning-charge"></i> Flash Sale</span>
                                @else
                                    <span class="badge bg-secondary">Không xác định</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <span class="badge bg-light text-dark border border-1 px-2 py-1" title="Ngày bắt đầu">
                                        <i class="bi bi-calendar-check text-success"></i>
                                        @if($promotion->start_date)
                                            {{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y H:i') }}
                                        @else
                                            --
                                        @endif
                                    </span>
                                    <span class="badge bg-light text-dark border border-1 px-2 py-1" title="Ngày kết thúc">
                                        <i class="bi bi-calendar-x text-danger"></i>
                                        @if($promotion->end_date)
                                            {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y H:i') }}
                                        @else
                                            --
                                        @endif
                                    </span>
                                </div>
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
