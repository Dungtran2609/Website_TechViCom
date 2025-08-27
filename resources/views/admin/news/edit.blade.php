@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Chỉnh sửa bài viết: {{ $news->title }}</h2>

        {{-- Form cập nhật --}}
        <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-4 rounded shadow-sm border">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Tiêu đề --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}">
                    @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Danh mục --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $news->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tác giả --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Tác giả</label>
                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                <input type="hidden" name="author_id" value="{{ auth()->id() }}">
                @error('author_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Ảnh hiện tại --}}
            <div class="mb-3">
                <label class="form-label">Ảnh hiện tại</label><br>
                @if ($news->image)
                    <img src="{{ asset($news->image) }}" width="200" class="img-thumbnail" alt="Ảnh bài viết">
                @else
                    <p class="text-muted fst-italic">Chưa có ảnh</p>
                @endif
            </div>

            {{-- Ảnh mới --}}
            <div class="mb-3">
                <label class="form-label">Ảnh mới (nếu muốn thay)</label>
                <input type="file" name="image" class="form-control">
                @error('image')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="editor" class="form-label fw-bold">Nội dung bài viết <span
                        class="text-danger">*</span></label>
                <div class="editor-container">
                    <textarea name="content" id="editor">{{ old('content', $news->content) }}</textarea>
                </div>
                @error('content')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Trạng thái & Ngày đăng --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="published" {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>Đã
                            đăng</option>
                        <option value="draft" {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>Nháp
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Ngày đăng</label>
                    <input type="text" class="form-control"
                        value="{{ optional($news->published_at)->format('d/m/Y H:i') }}" readonly>
                    @error('published_at')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Nút hành động --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">← Quay lại</a>
                <button type="submit" class="btn btn-success">Cập nhật</button>
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
                }
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
