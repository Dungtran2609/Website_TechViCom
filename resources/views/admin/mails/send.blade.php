@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gửi mail động</h1>
    <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-paper-plane me-2"></i>
            Gửi mail động cho người dùng
        </h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.mails.send') }}" method="POST" autocomplete="off">
            @csrf
            
            <div class="mb-4">
                <label class="form-label fw-bold">Chọn mẫu mail <span class="text-danger">*</span></label>
                <div class="input-group mb-2">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="mail-template-search" class="form-control" placeholder="Tìm kiếm mẫu mail...">
                </div>
                <div class="border rounded p-3 bg-light" style="max-height: 220px; overflow-y: auto;">
                    <div class="row">
                        @foreach($mailTemplates as $mail)
                            <div class="col-12 mb-2 mail-template-checkbox-item">
                                <div class="form-check">
                                    <input type="checkbox" name="mail_ids[]" value="{{ $mail->id }}" 
                                           class="form-check-input" id="mail_{{ $mail->id }}">
                                    <label class="form-check-label" for="mail_{{ $mail->id }}">
                                        <span class="fw-medium">{{ $mail->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $mail->subject }}</small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Tìm kiếm và chọn một hoặc nhiều mẫu mail để gửi.
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Chọn tài khoản nhận <small class="text-muted">(có thể chọn nhiều)</small></label>
                <div class="input-group mb-2">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="user-search" class="form-control" placeholder="Tìm kiếm tài khoản...">
                </div>
                <div class="border rounded p-3 bg-light" style="max-height: 260px; overflow-y: auto;">
                    <div class="row">
                        @foreach($users as $user)
                            <div class="col-12 col-md-6 mb-2 user-checkbox-item">
                                <div class="form-check">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                           class="form-check-input" id="user_{{ $user->id }}">
                                    <label class="form-check-label" for="user_{{ $user->id }}">
                                        <span class="fw-medium">{{ $user->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Tìm kiếm và chọn tài khoản cần gửi mail.
                </div>
            </div>

            <div class="mb-3" id="coupon-code-group" style="display: none;">
                <label for="coupon_code" class="form-label fw-bold">Mã giảm giá <span class="text-danger">*</span></label>
                <input type="text" name="coupon_code" id="coupon_code" class="form-control" 
                       placeholder="Nhập mã giảm giá...">
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Mã giảm giá sẽ được thay thế vào nội dung mail.
                </div>
            </div>

            <div class="mb-4">
                <label for="emails" class="form-label fw-bold">
                    Hoặc nhập email bất kỳ <small class="text-muted">(cách nhau dấu phẩy)</small>
                </label>
                <textarea name="emails" id="emails" class="form-control" rows="3" 
                          placeholder="email1@example.com, email2@example.com, email3@example.com"></textarea>
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Nhập danh sách email cách nhau bằng dấu phẩy nếu muốn gửi cho email không có trong hệ thống.
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane me-2"></i>Gửi mail
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tìm kiếm mẫu mail
    const mailTemplateSearch = document.getElementById('mail-template-search');
    if (mailTemplateSearch) {
        mailTemplateSearch.addEventListener('input', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('.mail-template-checkbox-item').forEach(function(item) {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(val) ? '' : 'none';
            });
        });
    }

    // Tìm kiếm user
    const searchInput = document.getElementById('user-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('.user-checkbox-item').forEach(function(item) {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(val) ? '' : 'none';
            });
        });
    }

    // Ẩn/hiện input mã giảm giá
    function toggleCouponInput() {
        let show = false;
        document.querySelectorAll('input[name="mail_ids[]"]:checked').forEach(function(checkbox) {
            const label = checkbox.closest('label');
            const text = label ? label.textContent.toLowerCase() : '';
            if (text.includes('giảm giá') || text.includes('coupon') || text.includes('khuyến mãi')) {
                show = true;
            }
        });
        
        const couponGroup = document.getElementById('coupon-code-group');
        if (show) {
            couponGroup.style.display = '';
        } else {
            couponGroup.style.display = 'none';
            document.getElementById('coupon_code').value = '';
        }
    }

    document.querySelectorAll('input[name="mail_ids[]"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', toggleCouponInput);
    });
    
    toggleCouponInput();
});
</script>

<style>
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.form-check-label {
    cursor: pointer;
}
.form-check-label:hover {
    background-color: rgba(13, 110, 253, 0.05);
    border-radius: 4px;
    padding: 2px 4px;
    margin: -2px -4px;
}
</style>
@endsection
