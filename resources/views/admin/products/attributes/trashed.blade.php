@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Thùng rác thuộc tính</h1>
        <a href="{{ route('admin.products.attributes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Trở về danh sách thuộc tính
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($attributes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên thuộc tính</th>
                                <th>Loại</th>
                                <th>Ngày xoá</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attributes as $attribute)
                                <tr>
                                    <td>{{ $attribute->id }}</td>
                                    <td>{{ $attribute->name }}</td>
                                    <td>{{ ucfirst($attribute->type) }}</td>
                                    <td>{{ $attribute->deleted_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.products.attributes.restore', $attribute->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo"></i> Khôi phục
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.products.attributes.force-delete', $attribute->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá vĩnh viễn thuộc tính này?')">
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
                <p class="text-muted">Không có thuộc tính nào trong thùng rác.</p>
            @endif
        </div>
    </div>
</div>
@endsection
