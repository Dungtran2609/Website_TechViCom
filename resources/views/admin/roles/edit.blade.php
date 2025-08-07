@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Chỉnh sửa vai trò</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Tên vai trò --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên vai trò <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $role->name) }}" 
                                placeholder="Nhập tên vai trò">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                id="slug" name="slug" value="{{ old('slug', $role->slug) }}" 
                                placeholder="vd: admin">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">URL-friendly identifier cho vai trò</small>
                        </div>

                        {{-- Mô tả --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="3" 
                                placeholder="Nhập mô tả cho vai trò">{{ old('description', $role->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                                <option value="active" {{ old('status', $role->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ old('status', $role->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Quyền --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Quyền <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="toggleAllPermissions">
                                    <i class="fas fa-check-double"></i> Chọn tất cả
                                </button>
                            </div>
                            <div class="row">
                                @if(isset($permissions) && $permissions->count() > 0)
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input @error('permissions') is-invalid @enderror" 
                                                    type="checkbox" name="permissions[]" 
                                                    value="{{ $permission->id }}" 
                                                    id="permission_{{ $permission->id }}"
                                                    {{ in_array($permission->id, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}>
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
                                <i class="fas fa-save"></i> Cập nhật vai trò
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
    document.addEventListener('DOMContentLoaded', function() {
        // Auto generate slug from name (only if slug is empty)
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slugField = document.getElementById('slug');
            
            // Only auto-generate if slug field is empty or hasn't been manually edited
            if (!slugField.value || !slugField.dataset.userEdited) {
                const slug = name.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
                    .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
                
                slugField.value = slug;
            }
        });

        // Mark slug as user-edited when manually changed
        document.getElementById('slug').addEventListener('input', function() {
            this.dataset.userEdited = 'true';
        });

        // Toggle all permissions functionality
        const toggleAllBtn = document.getElementById('toggleAllPermissions');
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        
        if (toggleAllBtn && checkboxes.length > 0) {
            toggleAllBtn.addEventListener('click', function() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const icon = this.querySelector('i');
                
                checkboxes.forEach(cb => {
                    cb.checked = !allChecked;
                });

                // Update button text and icon
                if (allChecked) {
                    icon.className = 'fas fa-check-double';
                    this.innerHTML = '<i class="fas fa-check-double"></i> Chọn tất cả';
                } else {
                    icon.className = 'fas fa-times';
                    this.innerHTML = '<i class="fas fa-times"></i> Bỏ chọn tất cả';
                }
            });

            // Update button state on individual checkbox change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    const icon = toggleAllBtn.querySelector('i');
                    
                    if (allChecked) {
                        icon.className = 'fas fa-times';
                        toggleAllBtn.innerHTML = '<i class="fas fa-times"></i> Bỏ chọn tất cả';
                    } else {
                        icon.className = 'fas fa-check-double';
                        toggleAllBtn.innerHTML = '<i class="fas fa-check-double"></i> Chọn tất cả';
                    }
                });
            });

            // Set initial button state
            const initialAllChecked = Array.from(checkboxes).every(cb => cb.checked);
            if (initialAllChecked && checkboxes.length > 0) {
                const icon = toggleAllBtn.querySelector('i');
                icon.className = 'fas fa-times';
                toggleAllBtn.innerHTML = '<i class="fas fa-times"></i> Bỏ chọn tất cả';
            }
        }
    });
</script>
@endsection
