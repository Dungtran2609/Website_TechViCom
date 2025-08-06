@extends('admin.layouts.app')

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-general">Thông tin chung</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-data">Dữ liệu sản phẩm</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-shipping">Vận chuyển</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Thương hiệu</label>
                                    <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id">
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Danh mục</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" name="category_id">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Mô tả ngắn</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="long_description" class="form-label">Mô tả chi tiết</label>
                                <textarea class="form-control @error('long_description') is-invalid @enderror" id="long_description" name="long_description" rows="7">{{ old('long_description', $product->long_description) }}</textarea>
                                @error('long_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="tab-pane" id="tab-data" role="tabpanel">
                            <div class="d-flex justify-content-end mb-3">
                                <select class="form-select w-auto @error('type') is-invalid @enderror" name="type" id="productType">
                                    <option value="simple" @selected(old('type', $product->type) == 'simple')>Sản phẩm đơn</option>
                                    <option value="variable" @selected(old('type', $product->type) == 'variable')>Sản phẩm biến thể</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            @php $simpleVariant = $product->type == 'simple' ? $product->variants->first() : null; @endphp

                            <div id="simpleProductFields">
                                <h6 class="mb-3">Thông tin giá & kho</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $simpleVariant?->price) }}">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giá khuyến mãi</label>
                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" name="sale_price" value="{{ old('sale_price', $simpleVariant?->sale_price) }}">
                                        @error('sale_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tồn kho <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $simpleVariant?->stock) }}">
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ngưỡng tồn kho thấp</label>
                                        <input type="number" class="form-control @error('low_stock_amount') is-invalid @enderror" name="low_stock_amount" value="{{ old('low_stock_amount', $simpleVariant?->low_stock_amount) }}">
                                        @error('low_stock_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <h6 class="mb-3">Thuộc tính sản phẩm</h6>
                                @foreach ($attributes as $attribute)
                                    <div class="mb-3">
                                        <label class="form-label">{{ $attribute->name }}</label>
                                        <select class="form-select @error('attributes.' . $attribute->id) is-invalid @enderror" name="attributes[{{ $attribute->id }}]">
                                            <option value="">-- Chọn --</option>
                                            @foreach ($attribute->values as $value)
                                                <option value="{{ $value->id }}" @selected(old('attributes.' . $attribute->id) ? old('attributes.' . $attribute->id) == $value->id : ($simpleVariant && $simpleVariant->attributeValues->contains($value->id)))>{{ $value->value }}</option>
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
                                    <h6 class="mb-3">Thêm biến thể mới</h6>
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
                                    <button type="button" class="btn btn-outline-primary" id="generateVariantsBtn">Tạo thêm biến thể</button>
                                </div>
                                <div id="variantsWrapper" class="mt-3">
                                    <div class="accordion">
                                        @if ($product->type == 'variable')
                                            @foreach ($product->variants as $index => $variant)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">{{ $variant->attributeValues->pluck('value')->join(' / ') ?: 'Biến thể mặc định' }}</button>
                                                    </h2>
                                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse show">
                                                        <div class="accordion-body">
                                                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                                            @foreach ($variant->attributeValues as $attValue)
                                                                <input type="hidden" name="variants[{{ $index }}][attributes][]" value="{{ $attValue->id }}">
                                                            @endforeach
                                                            <div class="row mb-3">
                                                                <div class="col-md-9">
                                                                    <label class="form-label">Ảnh riêng cho biến thể</label>
                                                                    <input type="file" class="form-control form-control-sm @error('variants.' . $index . '.image') is-invalid @enderror" name="variants[{{ $index }}][image]" accept="image/*">
                                                                    @error('variants.' . $index . '.image')
                                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                    @enderror
                                                                    @if ($variant->image && Storage::disk('public')->exists($variant->image))
                                                                        <img src="{{ asset('storage/' . $variant->image) }}" class="img-fluid rounded mt-2" width="80">
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-3 d-flex align-items-end">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" name="variants[{{ $index }}][is_active]" value="1" @checked(old('variants.' . $index . '.is_active', $variant->is_active))>
                                                                        <label class="form-check-label">Hoạt động</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h6 class="mb-3">Thông tin chính</h6>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control @error('variants.' . $index . '.price') is-invalid @enderror" name="variants[{{ $index }}][price]" value="{{ old('variants.' . $index . '.price', $variant->price) }}">
                                                                    @error('variants.' . $index . '.price')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Giá khuyến mãi</label>
                                                                    <input type="number" class="form-control @error('variants.' . $index . '.sale_price') is-invalid @enderror" name="variants[{{ $index }}][sale_price]" value="{{ old('variants.' . $index . '.sale_price', $variant->sale_price) }}">
                                                                    @error('variants.' . $index . '.sale_price')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">SKU</label>
                                                                    <input type="text" class="form-control @error('variants.' . $index . '.sku') is-invalid @enderror" name="variants[{{ $index }}][sku]" value="{{ old('variants.' . $index . '.sku', $variant->sku) }}">
                                                                    @error('variants.' . $index . '.sku')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Tồn kho <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control @error('variants.' . $index . '.stock') is-invalid @enderror" name="variants[{{ $index }}][stock]" value="{{ old('variants.' . $index . '.stock', $variant->stock) }}">
                                                                    @error('variants.' . $index . '.stock')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label class="form-label">Ngưỡng tồn kho thấp</label>
                                                                    <input type="number" class="form-control @error('variants.' . $index . '.low_stock_amount') is-invalid @enderror" name="variants[{{ $index }}][low_stock_amount]" value="{{ old('variants.' . $index . '.low_stock_amount', $variant->low_stock_amount) }}">
                                                                    @error('variants.' . $index . '.low_stock_amount')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <h6 class="mb-3">Thông tin vận chuyển</h6>
                                                            <div class="row">
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">Cân nặng (kg)</label>
                                                                    <input type="number" step="0.01" class="form-control @error('variants.' . $index . '.weight') is-invalid @enderror" name="variants[{{ $index }}][weight]" value="{{ old('variants.' . $index . '.weight', $variant->weight) }}">
                                                                    @error('variants.' . $index . '.weight')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">Dài (cm)</label>
                                                                    <input type="number" step="0.01" class="form-control @error('variants.' . $index . '.length') is-invalid @enderror" name="variants[{{ $index }}][length]" value="{{ old('variants.' . $index . '.length', $variant->length) }}">
                                                                    @error('variants.' . $index . '.length')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">Rộng (cm)</label>
                                                                    <input type="number" step="0.01" class="form-control @error('variants.' . $index . '.width') is-invalid @enderror" name="variants[{{ $index }}][width]" value="{{ old('variants.' . $index . '.width', $variant->width) }}">
                                                                    @error('variants.' . $index . '.width')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">Cao (cm)</label>
                                                                    <input type="number" step="0.01" class="form-control @error('variants.' . $index . '.height') is-invalid @enderror" name="variants[{{ $index }}][height]" value="{{ old('variants.' . $index . '.height', $variant->height) }}">
                                                                    @error('variants.' . $index . '.height')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab-shipping" role="tabpanel">
                            <p class="text-muted">Thông tin vận chuyển này chỉ áp dụng cho sản phẩm đơn.</p>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Cân nặng (kg)</label>
                                    <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" name="weight" value="{{ old('weight', $simpleVariant?->weight) }}">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Dài (cm)</label>
                                    <input type="number" step="0.01" class="form-control @error('length') is-invalid @enderror" name="length" value="{{ old('length', $simpleVariant?->length) }}">
                                    @error('length')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Rộng (cm)</label>
                                    <input type="number" step="0.01" class="form-control @error('width') is-invalid @enderror" name="width" value="{{ old('width', $simpleVariant?->width) }}">
                                    @error('width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Cao (cm)</label>
                                    <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror" name="height" value="{{ old('height', $simpleVariant?->height) }}">
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
                <div class="card-header"><h5 class="card-title mb-0">Hành động</h5></div>
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">Cập nhật sản phẩm</button>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0">Hiển thị</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="active" @selected(old('status', $product->status) == 'active')>Hiển thị</option>
                            <option value="inactive" @selected(old('status', $product->status) == 'inactive')>Ẩn</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
                        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Ảnh sản phẩm</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Ảnh đại diện</label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" name="thumbnail" accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail))
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Current Thumbnail" class="img-fluid rounded mt-2">
                        @endif
                    </div>
                    <hr>
                    <div>
                        <label class="form-label">Thư viện ảnh</label>
                        <div class="row g-2 mb-2">
                            @foreach ($product->allImages as $image)
                                <div class="col-auto">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail" width="100" alt="Gallery image">
                                        <div class="position-absolute top-0 end-0 p-1 bg-white bg-opacity-75 rounded">
                                            <input type="checkbox" class="form-check-input" name="delete_images[]" value="{{ $image->id }}" id="delete_image_{{ $image->id }}">
                                            <label for="delete_image_{{ $image->id }}" class="text-danger small" title="Chọn để xóa">Xóa</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <label class="form-label small text-muted">Thêm ảnh mới vào thư viện</label>
                        <div id="galleryWrapper"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btnAddImage">Thêm ảnh</button>
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
    const accordionContainer = variantsWrapper.querySelector('.accordion');
    let variantIndex = {{ $product->variants->count() }};

    function toggleFields() {
        const isSimple = productTypeSelect.value === 'simple';
        simpleFields.style.display = isSimple ? 'block' : 'none';
        variableFields.style.display = isSimple ? 'none' : 'block';
        if (shippingTab) shippingTab.closest('.nav-item').style.display = isSimple ? 'block' : 'none';
    }

    productTypeSelect.addEventListener('change', function() {
        toggleFields();
        if (this.value === 'variable' && accordionContainer.children.length === 0) {
            const simpleData = {
                price: document.querySelector('input[name="price"]').value,
                sale_price: document.querySelector('input[name="sale_price"]').value,
                stock: document.querySelector('input[name="stock"]').value,
                low_stock_amount: document.querySelector('input[name="low_stock_amount"]').value,
                weight: document.querySelector('input[name="weight"]').value,
                length: document.querySelector('input[name="length"]').value,
                width: document.querySelector('input[name="width"]').value,
                height: document.querySelector('input[name="height"]').value,
            };

            const simpleAttributes = [];
            document.querySelectorAll('#simpleProductFields select[name^="attributes"]').forEach(select => {
                if (select.value) {
                    const selectedOption = select.options[select.selectedIndex];
                    simpleAttributes.push({ id: selectedOption.value, text: selectedOption.text });
                }
            });
            
            if (simpleAttributes.length > 0) {
                const newVariantHtml = createVariantHtml(simpleAttributes, variantIndex, simpleData);
                accordionContainer.insertAdjacentHTML('beforeend', newVariantHtml);
                variantIndex++;
            }
        }
    });

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

    const createVariantHtml = (combo, index, data = {}) => {
        const comboName = combo.map(c => c.text).join(' / ') || 'Biến thể mặc định';
        const attributeInputs = combo.map(c => `<input type="hidden" name="variants[${index}][attributes][]" value="${c.id}">`).join('');
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
                        <div class="col-md-6 mb-3"><label class="form-label">Giá bán <span class="text-danger">*</span></label><input type="number" class="form-control" name="variants[${index}][price]" value="${data.price || ''}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Giá khuyến mãi</label><input type="number" class="form-control" name="variants[${index}][sale_price]" value="${data.sale_price || ''}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Tồn kho <span class="text-danger">*</span></label><input type="number" class="form-control" name="variants[${index}][stock]" value="${data.stock || ''}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Ngưỡng tồn kho thấp</label><input type="number" class="form-control" name="variants[${index}][low_stock_amount]" value="${data.low_stock_amount || ''}"></div>
                    </div>
                    <hr>
                    <h6 class="mb-3">Thông tin vận chuyển</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3"><label class="form-label">Cân nặng (kg)</label><input type="number" step="0.01" class="form-control" name="variants[${index}][weight]" value="${data.weight || ''}"></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Dài (cm)</label><input type="number" step="0.01" class="form-control" name="variants[${index}][length]" value="${data.length || ''}"></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Rộng (cm)</label><input type="number" step="0.01" class="form-control" name="variants[${index}][width]" value="${data.width || ''}"></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Cao (cm)</label><input type="number" step="0.01" class="form-control" name="variants[${index}][height]" value="${data.height || ''}"></div>
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
                .filter(group => group.length > 0 && group.every(opt => opt.id));
            if (selectedAttributes.length === 0) {
                alert('Vui lòng chọn ít nhất một giá trị thuộc tính.');
                return;
            }

            const combinations = getCombinations(selectedAttributes);
            const existingCombos = Array.from(accordionContainer.querySelectorAll('.accordion-item')).map(
                item => Array.from(item.querySelectorAll('input[name*="[attributes][]"]')).map(input => input.value).sort().join('-')
            );

            combinations.forEach((combo) => {
                const comboKey = combo.map(c => c.id).sort().join('-');
                if (existingCombos.includes(comboKey)) {
                    return;
                }
                const variantHtml = createVariantHtml(combo, variantIndex);
                accordionContainer.insertAdjacentHTML('beforeend', variantHtml);
                variantIndex++;
            });
        });
    }

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
});
</script>
@endpush```