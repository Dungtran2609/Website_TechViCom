@extends('admin.layouts.app')

@section('title', 'Chi tiết Role')

@section('content')
    <h1 class="mb-4">Chi tiết Role</h1>
    <div class="mb-3">
        <strong>ID:</strong> {{ $role->role_id }}
    </div>
    <div class="mb-3">
        <strong>Tên:</strong> {{ $role->name }}
    </div>
    <div class="mb-3">
        <strong>Slug:</strong> {{ $role->slug }}
    </div>
    <div class="mb-3">
        <strong>Role cha:</strong> {{ $role->parent ? $role->parent->name : 'Không' }}
    </div>
    <div class="mb-3">
        <strong>Trạng thái:</strong> {{ $role->status ? 'Kích hoạt' : 'Ẩn' }}
    </div>
    <div class="mb-3">
        <strong>Ảnh:</strong>
        @if($role->image)
            <img src="{{ asset('storage/' . $role->image) }}" alt="Ảnh" width="120">
        @else
            Không có ảnh
        @endif
    </div>
    <a href="{{ route('admin.products.roles.index') }}" class="btn btn-secondary">Quay lại</a>
@endsection
