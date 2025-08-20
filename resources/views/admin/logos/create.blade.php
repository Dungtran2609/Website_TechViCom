@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow border-0 mx-auto" style="max-width:500px;">
        <div class="card-header bg-success text-white d-flex align-items-center">
            <i class="bi bi-plus-circle me-2 fs-4"></i>
            <h4 class="mb-0">Thêm logo mới</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.logos.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-bookmark-star me-1 text-info"></i> Loại logo</label>
                    <select name="type" class="form-select" required>
                        <option value="client">Logo trang chủ (client)</option>
                        <option value="admin">Logo admin/sidebar</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-image me-1 text-primary"></i> Chọn ảnh logo <span class="text-danger">*</span></label>
                    <input type="file" name="logo" class="form-control" required accept="image/*" onchange="previewLogo(event)">
                    <div class="mt-2" id="logo-preview"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-card-text me-1 text-secondary"></i> Alt (mô tả ảnh, tuỳ chọn)</label>
                    <input type="text" name="alt" class="form-control">
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success px-4"><i class="bi bi-save me-1"></i> Lưu</button>
                    <a href="{{ route('admin.logos.index') }}" class="btn btn-secondary px-4"><i class="bi bi-arrow-left"></i> Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function previewLogo(event) {
    const input = event.target;
    const preview = document.getElementById('logo-preview');
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="rounded border shadow-sm mt-1" style="max-height:60px; max-width:120px; object-fit:contain; background:#f8f9fa;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
