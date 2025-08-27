@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Thêm mail động mới</h1>
    <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">
            <i class="fas fa-plus me-2"></i>
            Tạo mẫu mail động mới
        </h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.mails.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên mẫu mail <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Nhập tên mẫu mail...">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Loại mail</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">-- Chọn loại mail --</option>
                            <option value="order" {{ old('type') == 'order' ? 'selected' : '' }}>Đơn hàng</option>
                            <option value="user" {{ old('type') == 'user' ? 'selected' : '' }}>Người dùng</option>
                            <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>Hệ thống</option>
                            <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>Khuyến mãi</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề mail <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                       value="{{ old('subject') }}" placeholder="Nhập tiêu đề mail...">
                @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nội dung mail <span class="text-danger">*</span></label>
                <div class="mb-2">
                    <small class="text-muted mb-2 d-block">Click vào các biến bên dưới để chèn vào nội dung:</small>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary variable-insert" data-insert="{{ '{' }}{ $user->name }}" style="cursor:pointer;">
                            <i class="fas fa-user me-1"></i>Tên người dùng
                        </span>
                        <span class="badge bg-success variable-insert" data-insert="{{ '{' }}{ $user->email }}" style="cursor:pointer;">
                            <i class="fas fa-envelope me-1"></i>Email người dùng
                        </span>
                        <span class="badge bg-info variable-insert" data-insert="{{ '{' }}{ $coupon_code }}" style="cursor:pointer;">
                            <i class="fas fa-tag me-1"></i>Mã giảm giá
                        </span>
                        <span class="badge bg-warning variable-insert" data-insert="{{ '{' }}{ date('d/m/Y') }}" style="cursor:pointer;">
                            <i class="fas fa-calendar me-1"></i>Ngày hiện tại
                        </span>
                        <span class="badge bg-secondary variable-insert" data-insert="{{ '{' }}{ $order->id }}" style="cursor:pointer;">
                            <i class="fas fa-shopping-cart me-1"></i>Mã đơn hàng
                        </span>
                    </div>
                </div>
                <textarea name="content" id="content-textarea" class="form-control @error('content') is-invalid @enderror" 
                          rows="12" placeholder="Nhập nội dung mail...">{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Sử dụng các biến trên để tạo nội dung động. Mail sẽ được gửi tự động khi có sự kiện tương ứng.
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold">
                            <i class="fas fa-toggle-on me-1"></i>Kích hoạt mẫu mail
                        </label>
                        <div class="form-text">Mẫu mail sẽ được sử dụng khi kích hoạt</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="auto_send" value="1" 
                               {{ old('auto_send') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold">
                            <i class="fas fa-bolt me-1"></i>Tự động gửi
                        </label>
                        <div class="form-text">Mail sẽ được gửi tự động khi có sự kiện</div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Lưu mẫu mail
                </button>
                <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý chèn biến vào textarea
    document.querySelectorAll('.variable-insert').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const textarea = document.getElementById('content-textarea');
            if (!textarea) return;
            
            const insertText = this.getAttribute('data-insert');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const value = textarea.value;
            
            textarea.value = value.substring(0, start) + insertText + value.substring(end);
            textarea.selectionStart = textarea.selectionEnd = start + insertText.length;
            textarea.focus();
            
            // Hiệu ứng visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        });
    });

    // Auto-resize textarea
    const textarea = document.getElementById('content-textarea');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
});
</script>

<style>
.variable-insert {
    transition: all 0.2s ease;
    user-select: none;
}
.variable-insert:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}
</style>
@endsection
