@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thùng rác - Giá trị thuộc tính: {{ $attribute->name }}</h1>
    <a href="{{ route('admin.products.attributes.values.index', $attribute->id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Giá trị</th>
                        <th>Mã màu</th>
                        <th>Ngày xoá</th>
                        <th width="200px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($values as $val)
                        <tr>
                            <td>{{ $val->id }}</td>
                            <td>{{ $val->value }}</td>
                            <td>
                                @if ($attribute->type === 'color')
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background-color: {{ $val->color_code }}; border: 1px solid #ccc; display: inline-block;" title="{{ $val->color_code }}"></div>
                                    <div><small class="text-muted">{{ $val->color_code }}</small></div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $val->deleted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="{{ route('admin.products.attributes.values.restore', $val->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm" onclick="return confirm('Khôi phục giá trị này?')">
                                            <i class="fas fa-undo"></i> Khôi phục
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.attributes.values.force-delete', $val->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Xoá vĩnh viễn giá trị này?')">
                                            <i class="fas fa-trash-alt"></i> Xoá vĩnh viễn
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted text-center">Không có giá trị nào trong thùng rác.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $values->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection