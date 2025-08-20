@extends('admin.layouts.app')

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-general">Thông
                                    tin chung</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-data">Dữ liệu sản
                                    phẩm</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-shipping">Vận
                                    chuyển</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-general" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Thương hiệu</label>
                                        <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id">
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Danh mục</label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                            name="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Mô tả ngắn</label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description"
                                        name="short_description" rows="3">{{ old('short_description') }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="long_description" class="form-label">Mô tả chi tiết</label>
                                    <div class="editor-container">
                                        <textarea name="long_description" id="editor">{{ old('long_description') }}</textarea>
                                    </div>
                                    @error('long_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-data" role="tabpanel">
                                <div class="d-flex justify-content-end mb-3">
                                    <select class="form-select w-auto @error('type') is-invalid @enderror" name="type"
                                        id="productType">
                                        <option value="simple" @selected(old('type', 'simple') == 'simple')>Sản phẩm đơn</option>
                                        <option value="variable" @selected(old('type') == 'variable')>Sản phẩm biến thể</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="simpleProductFields">
                                    <h6 class="mb-3">Thông tin giá & kho</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                                name="price" value="{{ old('price') }}">
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Giá khuyến mãi</label>
                                            <input type="number"
                                                class="form-control @error('sale_price') is-invalid @enderror"
                                                name="sale_price" value="{{ old('sale_price') }}">
                                            @error('sale_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tồn kho <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                                name="stock" value="{{ old('stock') }}">
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ngưỡng tồn kho thấp</label>
                                            <input type="number"
                                                class="form-control @error('low_stock_amount') is-invalid @enderror"
                                                name="low_stock_amount" value="{{ old('low_stock_amount') }}">
                                            @error('low_stock_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <h6 class="mb-3">Thuộc tính sản phẩm</h6>
                                    @foreach ($attributes as $attribute)
                                        <div class="mb-3">
                                            <label class="form-label">{{ $attribute->name }}</label>
                                            <select
                                                class="form-select @error('attributes.' . $attribute->id) is-invalid @enderror"
                                                name="attributes[{{ $attribute->id }}]">
                                                <option value="">-- Chọn --</option>
                                                @foreach ($attribute->values as $value)
                                                    <option value="{{ $value->id }}" @selected(old('attributes.' . $attribute->id) == $value->id)>
                                                        {{ $value->value }}</option>
                                                @endforeach
                                            </select>
                                            @error('attributes.' . $attribute->id)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                                <div id="variableProductFields" style="display: none;">
                                    <div class="border p-3 rounded">
                                        <h6 class="mb-3">Thuộc tính sản phẩm</h6>
                                        @foreach ($attributes as $attribute)
                                            <div class="mb-3">
                                                <label class="form-label">{{ $attribute->name }}</label>
                                                <select class="form-control attribute-select" multiple>
                                                    @foreach ($attribute->values as $value)
                                                        <option value="{{ $value->id }}">{{ $value->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                        <button type="button" class="btn btn-outline-primary"
                                            id="generateVariantsBtn">Tạo biến thể</button>
                                    </div>
                                    <div id="variantsWrapper" class="mt-3"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-shipping" role="tabpanel">
                                <p class="text-muted">Thông tin vận chuyển này chỉ áp dụng cho sản phẩm đơn.</p>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Cân nặng (kg)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('weight') is-invalid @enderror" name="weight"
                                            value="{{ old('weight') }}">
                                        @error('weight')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Dài (cm)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('length') is-invalid @enderror" name="length"
                                            value="{{ old('length') }}">
                                        @error('length')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Rộng (cm)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('width') is-invalid @enderror" name="width"
                                            value="{{ old('width') }}">
                                        @error('width')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Cao (cm)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('height') is-invalid @enderror" name="height"
                                            value="{{ old('height') }}">
                                        @error('height')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Hành động</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">Lưu sản phẩm</button>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Hiển thị</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status">
                                <option value="active" @selected(old('status', 'active') == 'active')>Hiển thị</option>
                                <option value="inactive" @selected(old('status') == 'inactive')>Ẩn</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                value="1" @checked(old('is_featured'))>
                            <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Ảnh sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                                name="thumbnail" accept="image/*">
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <hr>
                        <div>
                            <label class="form-label">Thư viện ảnh</label>
                            <div id="galleryWrapper"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btnAddImage">Thêm
                                ảnh</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productTypeSelect = document.getElementById('productType');
            const simpleFields = document.getElementById('simpleProductFields');
            const variableFields = document.getElementById('variableProductFields');
            const shippingTab = document.querySelector('a[href="#tab-shipping"]');
            const generateBtn = document.getElementById('generateVariantsBtn');
            const variantsWrapper = document.getElementById('variantsWrapper');

            function toggleFields() {
                const isSimple = productTypeSelect.value === 'simple';
                simpleFields.style.display = isSimple ? 'block' : 'none';
                variableFields.style.display = isSimple ? 'none' : 'block';
                if (shippingTab) shippingTab.closest('.nav-item').style.display = isSimple ? 'block' : 'none';
            }
            productTypeSelect.addEventListener('change', toggleFields);
            toggleFields();

            const getCombinations = (arrays) => {
                if (!arrays || arrays.length === 0) return [];
                return arrays.reduce((acc, curr) => {
                    if (acc.length === 0) return curr.map(item => [item]);
                    let res = [];
                    acc.forEach(accItem => {
                        curr.forEach(currItem => {
                            res.push([...accItem, currItem]);
                        });
                    });
                    return res;
                }, []);
            };

            const createVariantHtml = (combo, index) => {
                const comboName = combo.map(c => c.text).join(' / ');
                const attributeInputs = combo.map(c =>
                    `<input type="hidden" name="variants[${index}][attributes][]" value="${c.id}">`).join(
                    '');
                return `
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${index}">${comboName}</button></h2>
                <div id="collapse${index}" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <input type="hidden" name="variants[${index}][id]" value="">
                        <div class="row mb-3">
                            <div class="col-md-9"><label class="form-label">Ảnh riêng cho biến thể</label><input type="file" class="form-control form-control-sm" name="variants[${index}][image]" accept="image/*"></div>
                            <div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="variants[${index}][is_active]" value="1" checked><label class="form-check-label">Hoạt động</label></div></div>
                        </div>
                        <h6 class="mb-3">Thông tin chính</h6>
                        <div class="row">
                            ${attributeInputs}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="variants[${index}][price]">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá khuyến mãi</label>
                                <input type="number" class="form-control" name="variants[${index}][sale_price]">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tồn kho <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="variants[${index}][stock]">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngưỡng tồn kho thấp</label>
                                <input type="number" class="form-control" name="variants[${index}][low_stock_amount]" value="">
                            </div>
                        </div>
                        <hr>
                        <h6 class="mb-3">Thông tin vận chuyển</h6>
                        <div class="row">
                            <div class="col-md-3 mb-3"><label class="form-label">Cân nặng (kg)</label><input type="number" step="0.01" class="form-control" name="variants[${index}][weight]"></div>
                            <div class="col-md-3 mb-3"><label class="form-label">Dài (cm)</label><input type="number" step="0.01" class="form-control" name="variants[${index}][length]"></div>
                            <div class="col-md-3 mb-3"><label class="form-label">Rộng (cm)</label><input type="number" step="0.01" class="form-control" name="variants[${index}][width]"></div>
                            <div class="col-md-3 mb-3"><label class="form-label">Cao (cm)</label><input type="number" step="0.01" class="form-control" name="variants[${index}][height]"></div>
                        </div>
                    </div>
                </div>
            </div>`;
            };

            if (generateBtn) {
                generateBtn.addEventListener('click', () => {
                    const selectedAttributes = Array.from(document.querySelectorAll('.attribute-select'))
                        .map(select => Array.from(select.selectedOptions).map(option => ({
                            id: option.value,
                            text: option.textContent
                        })))
                        .filter(group => group.length > 0);
                    if (selectedAttributes.length === 0) {
                        alert('Vui lòng chọn ít nhất một giá trị thuộc tính.');
                        return;
                    }
                    const combinations = getCombinations(selectedAttributes);
                    const variantsHtml = combinations.map((combo, index) => createVariantHtml(combo, index))
                        .join('');
                    variantsWrapper.innerHTML = `<div class="accordion">${variantsHtml}</div>`;
                });
            }
        });

        document.getElementById('btnAddImage').addEventListener('click', function(e) {
            e.preventDefault();
            const wrapper = document.getElementById('galleryWrapper');
            const div = document.createElement('div');
            div.classList.add('d-flex', 'align-items-center', 'mb-2', 'gallery-item');
            div.innerHTML = `
        <input type="file" name="gallery[]" class="form-control me-2" accept="image/*">
        <button type="button" class="btn btn-danger btn-sm btnRemoveImage">Xóa</button>
    `;
            wrapper.appendChild(div);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btnRemoveImage')) {
                e.preventDefault();
                e.target.closest('.gallery-item').remove();
            }
        });
    </script>

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
@endpush
