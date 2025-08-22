@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh sách chương trình khuyến mãi</h1>
        <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tạo chương trình mới
        </a>
    </div>

    <form method="GET" action="" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Tìm kiếm chương trình..."
                value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <select name="flash_type" class="form-select">
                <option value="">-- Kiểu áp dụng --</option>
                <option value="flash_sale" {{ request('flash_type') == 'flash_sale' ? 'selected' : '' }}>Flash Sale</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"
                placeholder="Từ ngày">
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-outline-primary" title="Tìm kiếm">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary" title="Làm mới">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>STT</th>
                            <th>Tên chương trình</th>
                            <th>Kiểu áp dụng</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th width="120px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $promotion)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="text-dark fw-medium">{{ $promotion->name }}</span>
                                </td>
                                <td>
                                    @php
                                        $type = $promotion->flash_type ?? $promotion->type;
                                    @endphp
                                    @if ($type === 'all')
                                        <span class="badge bg-primary">Toàn bộ sản phẩm</span>
                                    @elseif($type === 'category')
                                        <span class="badge bg-info text-dark">Theo danh mục</span>
                                    @elseif($type === 'flash_sale')
                                        <span class="badge bg-danger">Flash Sale</span>
                                    @else
                                        <span class="badge bg-secondary">Không xác định</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="text-muted small">
                                            <i class="fas fa-calendar-check text-success me-1"></i>
                                            @if ($promotion->start_date)
                                                {{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y H:i') }}
                                            @else
                                                --
                                            @endif
                                        </span>
                                        <span class="text-muted small">
                                            <i class="fas fa-calendar-times text-danger me-1"></i>
                                            @if ($promotion->end_date)
                                                {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y H:i') }}
                                            @else
                                                --
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if ($promotion->status)
                                        <span class="badge bg-success">Kích hoạt</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Ẩn</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                            class="btn btn-light btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.promotions.destroy', $promotion->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Xóa chương trình này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-gift fa-2x mb-3"></i>
                                        <p>Không có chương trình khuyến mãi nào</p>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.products.index') }}" class="btn btn-primary">
                                                <i class="fas fa-box me-1"></i> Quản lý sản phẩm
                                            </a>
                                            <p class="text-muted mt-2">Sử dụng "Giảm giá (%)" trực tiếp trong quản lý sản phẩm</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <!-- Additional info placeholder -->
            </div>
            <div>
                {{ $promotions->links() }}
            </div>
        </div>
    </div>
    </div>
@endsection
