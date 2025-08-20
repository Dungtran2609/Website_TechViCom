@extends('admin.layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Sửa mail</h1>
    <form action="{{ route('admin.mails.update', $mail->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên mẫu mail</label>
            <input type="text" name="name" class="form-control" value="{{ $mail->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="subject" class="form-control" value="{{ $mail->subject }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Loại</label>
            <input type="text" name="type" class="form-control" value="{{ $mail->type }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Nội dung</label>
            <div class="mb-2">
                <span class="badge bg-secondary variable-insert" data-insert="{{ '{' }}{ $user->name }}">Tên người dùng</span>
                <span class="badge bg-secondary variable-insert" data-insert="{{ '{' }}{ $coupon_code }}">Mã giảm giá</span>
                <span class="badge bg-secondary variable-insert" data-insert="{{ '{' }}{ date('d/m/Y') }}">Ngày hiện tại</span>
            </div>
            <textarea name="content" id="content-textarea" class="form-control" rows="8" required>{{ $mail->content }}</textarea>
            <div class="form-text">Click vào biến để chèn vào nội dung. Có thể dùng cú pháp Blade như <code>{{ '{' }}{ $user->name }}</code>, <code>{{ '{' }}{ $coupon_code }}</code>, ...</div>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $mail->is_active ? 'checked' : '' }}>
            <label class="form-check-label">Kích hoạt</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="auto_send" value="1" {{ $mail->auto_send ? 'checked' : '' }}>
            <label class="form-check-label">Tự động gửi</label>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.variable-insert').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const textarea = document.getElementById('content-textarea');
            if (!textarea) return;
            const insertText = this.getAttribute('data-insert');
            // Chèn vào vị trí con trỏ
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const value = textarea.value;
            textarea.value = value.substring(0, start) + insertText + value.substring(end);
            // Đưa con trỏ về sau đoạn vừa chèn
            textarea.selectionStart = textarea.selectionEnd = start + insertText.length;
            textarea.focus();
        });
    });
});
</script>
@endsection
