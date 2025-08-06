@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Thùng rác danh mục</h1>
        <a href="{{ route('admin.products.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Trở về danh mục
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Danh mục cha</th>
                                <th>Trạng thái</th>
                                <th>Ngày xoá</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->parent?->name ?? 'Không có' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                                            {{ $category->status ? 'Hiển thị' : 'Ẩn' }}
                                        </span>
                                    </td>
                                    <td>{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.products.categories.restore', $category->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo"></i> Khôi phục
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.products.categories.force-delete', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá vĩnh viễn?')">
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
                <p class="text-muted">Không có danh mục nào trong thùng rác.</p>
            @endif
        </div>
    </div>
</div>
@endsection
