@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Thùng rác thương hiệu</h1>
        <a href="{{ route('admin.products.brands.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Trở về danh sách thương hiệu
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($brands->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên thương hiệu</th>
                                <th>Ảnh</th>
                                <th>Trạng thái</th>
                                <th>Ngày xoá</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                                <tr>
                                    <td>{{ $brand->id }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>
                                        @if ($brand->image)
                                            <img src="{{ asset('storage/' . $brand->image) }}" alt="Ảnh thương hiệu" width="60">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $brand->status ? 'success' : 'secondary' }}">
                                            {{ $brand->status ? 'Hiển thị' : 'Ẩn' }}
                                        </span>
                                    </td>
                                    <td>{{ $brand->deleted_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.products.brands.restore', $brand->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo"></i> Khôi phục
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.products.brands.force-delete', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá vĩnh viễn thương hiệu này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt"></i> Xoá vĩnh viễn
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Không có thương hiệu nào trong thùng rác.</p>
            @endif
        </div>
    </div>
</div>
@endsection
