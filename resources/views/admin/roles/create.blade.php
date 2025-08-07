@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Thêm vai trò</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf

                        {{-- Tên vai trò --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên vai trò <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" 
                                placeholder="Nhập tên vai trò">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                id="slug" name="slug" value="{{ old('slug') }}" 
                                placeholder="vd: admin (tự động tạo nếu để trống)">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Để trống để tự động tạo từ tên vai trò</small>
                        </div>

                        {{-- Mô tả --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="3" 
                                placeholder="Nhập mô tả cho vai trò">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Quyền --}}
                        <div class="mb-3">
                            <label class="form-label">Quyền <span class="text-danger">*</span></label>
                            <div class="row">
                                @if(isset($permissions) && $permissions->count() > 0)
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input @error('permissions') is-invalid @enderror" 
                                                    type="checkbox" name="permissions[]" 
                                                    value="{{ $permission->id }}" 
                                                    id="permission_{{ $permission->id }}"
                                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <p class="text-muted">Không có quyền nào được định nghĩa.</p>
                                    </div>
                                @endif
                            </div>
                            @error('permissions')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('permissions.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nút điều hướng --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu vai trò
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
            .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
        
        // Only set if slug field is empty or user hasn't manually edited it
        const slugField = document.getElementById('slug');
        if (!slugField.dataset.userEdited) {
            slugField.value = slug;
        }
    });

    // Mark slug as user-edited when manually changed
    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.userEdited = 'true';
    });

    // Select/Deselect all permissions
    function toggleAllPermissions() {
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(cb => {
            cb.checked = !allChecked;
        });
    }

    // Add select all button
    document.addEventListener('DOMContentLoaded', function() {
        const permissionsLabel = document.querySelector('label:contains("Quyền")');
        if (permissionsLabel) {
            const selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.className = 'btn btn-sm btn-outline-primary ms-2';
            selectAllBtn.innerHTML = '<i class="fas fa-check-double"></i> Chọn tất cả';
            selectAllBtn.onclick = toggleAllPermissions;
            permissionsLabel.parentNode.insertBefore(selectAllBtn, permissionsLabel.nextSibling);
        }
    });
</script>
@endsection
