@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý Banner</h1>
    <a href="{{ route('admin.banner.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Thêm Banner
    </a>
</div>
<form method="GET" action="{{ route('admin.banner.index') }}" class="mb-4">
    <div class="row g-3">
        <div class="col-md-3">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo từ khóa...">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="Sắp diễn ra" {{ request('status') == 'Sắp diễn ra' ? 'selected' : '' }}>Sắp diễn ra</option>
                <option value="Hiện" {{ request('status') == 'Hiện' ? 'selected' : '' }}>Hiện</option>
                <option value="Đã kết thúc" {{ request('status') == 'Đã kết thúc' ? 'selected' : '' }}>Đã kết thúc</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="start_date_from" value="{{ request('start_date_from') }}" class="form-control" placeholder="Từ ngày">
        </div>
        <div class="col-md-2">
            <input type="date" name="end_date_from" value="{{ request('end_date_from') }}" class="form-control" placeholder="Đến ngày">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    @if(request('keyword') || request('status') || request('start_date_from') || request('end_date_from'))
        <div class="mt-2">
            <a href="{{ route('admin.banner.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
    @endif
</form>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>STT</th>
                        <th>Hình ảnh</th>
                        <th>Đường dẫn</th>
                        <th>Trạng thái</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th width="120px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banners as $index => $banner)
                        <tr>
                            <td>{{ ($banners->currentPage() - 1) * $banners->perPage() + $index + 1 }}</td>
                            <td>
                                @if ($banner->image)
                                    <img src="{{ asset('storage/' . $banner->image) }}" 
                                         class="img-thumbnail" 
                                         style="width: 100px; height: 60px; object-fit: cover;"
                                         onerror="this.style.display='none';">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank" class="text-primary">{{ Str::limit($banner->link, 50) }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($banner->status == 'Hiện')
                                    <span class="badge bg-success">Hiện</span>
                                @elseif($banner->status == 'Sắp diễn ra')
                                    <span class="badge bg-warning text-dark">Sắp diễn ra</span>
                                @else
                                    <span class="badge bg-secondary">Đã kết thúc</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($banner->start_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($banner->end_date)->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.banner.edit', $banner) }}" class="btn btn-light btn-sm" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.banner.destroy', $banner) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa banner này?')" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <!-- Pagination sẽ được thêm sau nếu cần -->
    </div>
</div>
@endsection
