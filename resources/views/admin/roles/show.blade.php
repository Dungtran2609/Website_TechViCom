@extends('admin.layouts.app')

@section('title', 'Chi tiết vai trò')

@section('content')
    <div class="container-fluid">
        <!-- Header của trang -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 fw-bold">
                <i class="fas fa-user-shield me-2 text-primary"></i>Chi tiết vai trò
            </h1>
            <a href="{{ route('admin.roles.list') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>

        <!-- Hàng chứa nội dung chính -->
        <div class="row justify-content-center">
            <!-- Cột nội dung chính - ĐÃ ĐƯỢỢC MỞ RỘNG TỪ col-lg-8 LÊN col-xl-10 -->
            <div class="col-12 col-xl-10">
                <!-- Card chính -->
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-5">
                            <!-- Cột thông tin chi tiết (Trái) - Tái cấu trúc lại cho gọn gàng hơn -->
                            <div class="col-lg-4">
                                <h5 class="text-muted mb-4">Thông tin cơ bản</h5>

                                <div class="d-flex align-items-start mb-4">
                                    <i class="fas fa-hashtag fa-fw text-muted mt-1 me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">ID</small>
                                        <span class="fw-bold fs-5">{{ $role->id ?? $role->role_id }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start mb-4">
                                    <i class="fas fa-user-tag fa-fw text-muted mt-1 me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Tên vai trò</small>
                                        <span class="badge bg-primary-soft fs-6">{{ $role->name }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start mb-4">
                                    <i class="fas fa-link fa-fw text-muted mt-1 me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Slug</small>
                                        <span class="badge bg-info-soft fs-6">{{ $role->slug }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start">
                                    <i class="fas fa-toggle-on fa-fw text-muted mt-1 me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Trạng thái</small>
                                        <span
                                            class="badge fs-6 {{ $role->status ? 'bg-success-soft' : 'bg-secondary-soft' }}">
                                            {{ $role->status ? 'Kích hoạt' : 'Ẩn' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Cột Quyền của vai trò (Phải) - Dành nhiều không gian hơn -->
                            <div class="col-lg-8">
                                <h5 class="text-muted mb-4">
                                    <i class="fas fa-key me-2"></i>Quyền của vai trò
                                </h5>
                                @if ($role->permissions && $role->permissions->count())
                                    <div class="permissions-container p-3 bg-light rounded-3">
                                        @foreach ($role->permissions as $permission)
                                            <span class="permission-badge">
                                                <i class="fas fa-check-circle text-success me-1"></i>{{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-light text-center">Không có quyền nào được gán.</div>
                                @endif
                            </div>
                        </div>

                        <!-- Đường kẻ phân cách -->
                        <hr class="my-4">

                        <!-- Các nút hành động -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.roles.list') }}" class="btn btn-light">
                                <i class="fas fa-list me-2"></i>Danh sách vai trò
                            </a>
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Chỉnh sửa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    {{-- Thêm CSS tùy chỉnh cho trang này --}}
    <style>
        /* Các lớp badge màu "mềm mại" */
        .bg-primary-soft {
            background-color: rgba(var(--bs-primary-rgb), 0.15) !important;
            color: var(--bs-primary) !important;
        }

        .bg-info-soft {
            background-color: rgba(var(--bs-info-rgb), 0.15) !important;
            color: var(--bs-info) !important;
        }

        .bg-success-soft {
            background-color: rgba(var(--bs-success-rgb), 0.15) !important;
            color: var(--bs-success) !important;
        }

        .bg-secondary-soft {
            background-color: rgba(var(--bs-secondary-rgb), 0.15) !important;
            color: var(--bs-secondary) !important;
        }

        /* Khung chứa danh sách quyền */
        .permissions-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            max-height: 400px;
            /* Tăng chiều cao tối đa */
            overflow-y: auto;
            border: 1px solid var(--bs-border-color-translucent);
        }

        /* Tùy chỉnh thanh cuộn cho đẹp hơn */
        .permissions-container::-webkit-scrollbar {
            width: 6px;
        }

        .permissions-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .permissions-container::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 6px;
        }

        .permissions-container::-webkit-scrollbar-thumb:hover {
            background-color: #aaa;
        }

        /* Badge cho mỗi quyền */
        .permission-badge {
            background-color: #ffc107;
            /* Màu vàng */
            color: #5d4300;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 500;
            font-family: monospace;
            font-size: 0.9em;
            white-space: nowrap;
            /* Giữ trên một hàng */
        }
    </style>
@endpush
