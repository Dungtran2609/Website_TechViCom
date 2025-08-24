@extends('admin.layouts.app')
@section('content')
<div class="container-fluid w-100" style="max-width:900px;">
    <h1 class="mb-4">Thêm mail mới</h1>
    <form action="{{ route('admin.mails.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên mẫu mail</label>
            <input type="text" name="name" class="form-control" >
        </div>
        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="subject" class="form-control" >
        </div>
        <div class="mb-3">
            <label class="form-label">Loại</label>
            <input type="text" name="type" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Nội dung</label>
            <div class="mb-2 variable-badge-row" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;overflow-x:auto;">
                <span class="badge bg-secondary variable-insert" data-insert="{{ '{' }}{ $user->name }}" style="cursor:pointer;min-width:90px;text-align:center;">Tên người dùng</span>
                <span class="badge bg-secondary variable-insert" data-insert="{{ '{' }}{ $coupon_code }}" style="cursor:pointer;min-width:90px;text-align:center;">Mã giảm giá</span>
                <span class="badge bg-secondary variable-insert" data-insert="{{ '{' }}{ date('d/m/Y') }}" style="cursor:pointer;min-width:90px;text-align:center;">Ngày hiện tại</span>
            </div>
            <textarea name="content" id="editor" ></textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
            <label class="form-check-label">Kích hoạt</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="auto_send" value="1">
            <label class="form-check-label">Tự động gửi</label>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
<script>
    let ckeditorInstance;
    ClassicEditor.create(document.querySelector('#editor'), {
        toolbar: [
            'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
            '|', 'blockQuote', 'insertTable', 'undo', 'redo', 'imageUpload'
        ]
    }).then(editor => {
        ckeditorInstance = editor;
    });
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.variable-insert').forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (!ckeditorInstance) return;
                const insertText = this.getAttribute('data-insert');
                const viewFragment = ckeditorInstance.data.processor.toView(insertText);
                const modelFragment = ckeditorInstance.data.toModel(viewFragment);
                ckeditorInstance.model.insertContent(modelFragment);
                ckeditorInstance.editing.view.focus();
            });
        });
        // Đảm bảo submit form sẽ lấy content từ CKEditor
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Nếu CKEditor chưa khởi tạo xong thì chặn submit
                if (!ckeditorInstance) {
                    e.preventDefault();
                    setTimeout(() => form.requestSubmit(), 100);
                    return false;
                }
                document.querySelector('#editor').value = ckeditorInstance.getData();
            });
        }
    });
</script>
@endsection
