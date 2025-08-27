@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <h2 class="mb-4 text-primary"><i class="bi bi-plus-square me-2"></i>Thêm bài viết</h2>

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-4 rounded shadow-sm">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" >
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Danh mục bài viết <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" >
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tác giả</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                    <input type="hidden" name="author_id" value="{{ auth()->id() }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Trạng thái</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" >
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Đã đăng</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Nháp</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ảnh đại diện</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="editor" class="form-label fw-bold">Nội dung bài viết <span
                        class="text-danger">*</span></label>
                <div class="editor-container">
                    <textarea name="content" id="editor">{{ old('content') }}</textarea>
                </div>
                @error('content')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                    ← Quay lại
                </a>
                <button type="submit" class="btn btn-success">
                    💾 Lưu bài viết
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file.then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);
                    data.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route('admin.news.upload-image') }}', {
                            method: 'POST',
                            body: data
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.url) {
                                resolve({
                                    default: result.url
                                });
                            } else {
                                reject(result.message || 'Upload thất bại');
                            }
                        })
                        .catch(() => reject('Lỗi mạng khi upload ảnh.'));
                }));
            }

            abort() {}
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [
                    'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                    '|', 'blockQuote', 'insertTable', 'undo', 'redo', 'imageUpload'
                ],
                mediaEmbed: {
                    previewsInData: true // 🔥 Cho phép lưu nội dung đã render (iframe/oembed)
                },
                htmlSupport: {
                    allow: [{
                        name: 'iframe',
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                },
                extraPlugins: [MyCustomUploadAdapterPlugin] // Thêm dòng này để đăng ký upload adapter
            })
            .then(editor => {
                // Optional: Lấy nội dung để lưu
                editor.model.document.on('change:data', () => {
                    document.querySelector('#editor').value = editor.getData();
                });
            })
            .catch(error => console.error(error));
    </script>
@endsection
