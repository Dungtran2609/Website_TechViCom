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
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $product->name) }}">
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
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                            name="category_id">
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
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description"
                                        name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="long_description" class="form-label">Mô tả chi tiết</label>
                                    <div class="editor-container">
                                        <textarea name="long_description" id="editor">{{ old('long_description', $product->long_description) }}</textarea>
                                    </div>

                                    @error('long_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="tab-pane" id="tab-data" role="tabpanel">
                                <div class="d-flex justify-content-end mb-3 position-relative">
                                    <div class="w-100 position-relative">
                                        <select class="form-select w-auto @error('type') is-invalid @enderror"
                                            name="type" id="productType">
                                            <option value="simple" @selected(old('type', $product->type) == 'simple')>Sản phẩm đơn</option>
                                            <option value="variable" @selected(old('type', $product->type) == 'variable')>Sản phẩm biến thể</option>
                                        </select>
                                        @error('type')
                                            <div class="text-danger fw-semibold mt-2 d-flex align-items-center"
                                                style="font-size: 1rem;">
                                                <span class="me-1" style="font-size:1.2em;">&#9888;&#65039;</span>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                @php $simpleVariant = $product->type == 'simple' ? $product->variants->first() : null; @endphp

                                <div id="simpleProductFields">
                                    <h6 class="mb-3">Thông tin giá & kho</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                                name="price" value="{{ old('price', $simpleVariant?->price) }}">
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Giá khuyến mãi</label>
                                            <input type="number"
                                                class="form-control @error('sale_price') is-invalid @enderror"
                                                name="sale_price"
                                                value="{{ old('sale_price', $simpleVariant?->sale_price) }}">
                                            @error('sale_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tồn kho <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                                name="stock" value="{{ old('stock', $simpleVariant?->stock) }}">
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ngưỡng tồn kho thấp</label>
                                            <input type="number"
                                                class="form-control @error('low_stock_amount') is-invalid @enderror"
                                                name="low_stock_amount"
                                                value="{{ old('low_stock_amount', $simpleVariant?->low_stock_amount) }}">
                                            @error('low_stock_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <h6 class="mb-3">Thuộc tính sản phẩm</h6>
                                    <div id="attribute-select-wrapper">
                                        <div class="row align-items-end mb-2">
                                            <div class="col-md-8">
                                                <label class="form-label">Chọn thuộc tính</label>
                                                <select class="form-select" id="attributeDropdown">
                                                    <option value="">-- Chọn thuộc tính --</option>
                                                    @foreach ($attributes as $attribute)
                                                        <option value="{{ $attribute->id }}"
                                                            data-values='@json($attribute->values)'>
                                                            {{ $attribute->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="btnAddAttribute">Thêm thuộc tính</button>
                                            </div>
                                        </div>
                                        <div id="selectedAttributes" class="mt-2"></div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const attributeDropdown = document.getElementById('attributeDropdown');
                                            const btnAddAttribute = document.getElementById('btnAddAttribute');
                                            const selectedAttributesDiv = document.getElementById('selectedAttributes');
                                            let selectedAttributes = [];

                                            @if ($product->type == 'simple' && $simpleVariant)
                                                @foreach ($simpleVariant->attributeValues as $attValue)
                                                    @php
                                                        $attribute = $attributes->firstWhere('id', $attValue->attribute_id);
                                                    @endphp
                                                    @if ($attribute)
                                                        selectedAttributes.push({
                                                            attrId: '{{ $attribute->id }}',
                                                            attrName: '{{ $attribute->name }}',
                                                            valId: '{{ $attValue->id }}',
                                                            valName: '{{ $attValue->value }}',
                                                            values: @json($attribute->values),
                                                        });
                                                    @endif
                                                @endforeach
                                            @endif

                                            btnAddAttribute?.addEventListener('click', function() {
                                                const attrId = attributeDropdown.value;
                                                const attrName = attributeDropdown.options[attributeDropdown.selectedIndex]?.text;
                                                if (!attrId) return alert('Vui lòng chọn thuộc tính!');
                                                if (selectedAttributes.find(a => a.attrId === attrId)) return alert(
                                                    'Thuộc tính này đã được chọn!');
                                                const selectedOption = attributeDropdown.options[attributeDropdown.selectedIndex];
                                                const values = selectedOption && selectedOption.dataset.values ? JSON.parse(selectedOption
                                                    .dataset.values) : [];
                                                selectedAttributes.push({
                                                    attrId,
                                                    attrName,
                                                    valId: '',
                                                    valName: '',
                                                    values
                                                });
                                                renderSelectedAttributes();
                                                attributeDropdown.value = '';
                                            });

                                            function renderSelectedAttributes() {
                                                selectedAttributesDiv.innerHTML = selectedAttributes.map((a, idx) => `
                                                    <div class="d-flex align-items-center mb-1">
                                                        <span class="me-2">${a.attrName}:</span>
                                                        <select class="form-select form-select-sm me-2 attr-value-select" data-idx="${idx}" name="attributes[${a.attrId}]">
                                                            <option value="">-- Chọn giá trị --</option>
                                                            ${a.values.map(v => `<option value="${v.id}" ${a.valId == v.id ? 'selected' : ''}>${v.value}</option>`).join('')}
                                                        </select>
                                                        <button type="button" class="btn btn-sm btn-danger btnRemoveAttr" data-id="${a.attrId}">Xóa</button>
                                                    </div>`).join('');
                                            }

                                            selectedAttributesDiv?.addEventListener('change', function(e) {
                                                if (e.target.classList.contains('attr-value-select')) {
                                                    const idx = e.target.dataset.idx;
                                                    const valId = e.target.value;
                                                    selectedAttributes[idx].valId = valId;
                                                }
                                            });

                                            selectedAttributesDiv?.addEventListener('click', function(e) {
                                                if (e.target.classList.contains('btnRemoveAttr')) {
                                                    const id = e.target.dataset.id;
                                                    selectedAttributes = selectedAttributes.filter(a => a.attrId !== id);
                                                    renderSelectedAttributes();
                                                }
                                            });
                                            renderSelectedAttributes();
                                        });
                                    </script>
                                </div>

                                <div id="variableProductFields" style="display: none;">
                                    <div class="border p-3 rounded">
                                        <h6 class="mb-3">Thuộc tính sản phẩm</h6>
                                        <div id="variant-attribute-select-wrapper">
                                            <div class="row align-items-end mb-2">
                                                <div class="col-md-8">
                                                    <label class="form-label">Chọn thuộc tính</label>
                                                    <select class="form-select" id="variantAttributeDropdown">
                                                        <option value="">-- Chọn thuộc tính --</option>
                                                        @foreach ($attributes as $attribute)
                                                            <option value="{{ $attribute->id }}"
                                                                data-values='@json($attribute->values)'>
                                                                {{ $attribute->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="button" class="btn btn-outline-primary"
                                                        id="btnAddVariantAttribute">Thêm thuộc tính</button>
                                                </div>
                                            </div>
                                            <div id="selectedVariantAttributes" class="mt-2"></div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary mt-2"
                                            id="generateVariantsBtn">Tạo biến thể</button>
                                    </div>
                                    <div id="variantsWrapper" class="mt-3">
                                        <div class="accordion">
                                            @php
                                                $oldVariants = old('variants');
                                                $variantsToShow = [];
                                                if (is_array($oldVariants)) {
                                                    $variantsToShow = $oldVariants;
                                                } elseif ($product->type == 'variable') {
                                                    $variantsToShow = $product->variants
                                                        ->map(function ($v) {
                                                            // Chuyển về array cho đồng nhất
                                                            return [
                                                                'id' => $v->id,
                                                                'attributes' => $v->attributeValues
                                                                    ->pluck('id')
                                                                    ->toArray(),
                                                                'price' => $v->price,
                                                                'sale_price' => $v->sale_price,
                                                                'stock' => $v->stock,
                                                                'low_stock_amount' => $v->low_stock_amount,
                                                                'weight' => $v->weight,
                                                                'length' => $v->length,
                                                                'width' => $v->width,
                                                                'height' => $v->height,
                                                                'is_active' => $v->is_active,
                                                                'image' => $v->image,
                                                            ];
                                                        })
                                                        ->toArray();
                                                }
                                            @endphp
                                            @foreach ($variantsToShow as $index => $variant)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $index }}">
                                                            @php
                                                                // Hiển thị tên biến thể theo thuộc tính
                                                                $attValues = [];
                                                                if (
                                                                    !empty($variant['attributes']) &&
                                                                    is_array($variant['attributes'])
                                                                ) {
                                                                    $attValues = \App\Models\AttributeValue::whereIn(
                                                                        'id',
                                                                        $variant['attributes'],
                                                                    )
                                                                        ->pluck('value')
                                                                        ->toArray();
                                                                }
                                                            @endphp
                                                            {{ $attValues ? implode(' / ', $attValues) : 'Biến thể mặc định' }}
                                                        </button>
                                                    </h2>
                                                    <div id="collapse{{ $index }}"
                                                        class="accordion-collapse collapse show">
                                                        <div class="accordion-body">
                                                            @if (!empty($variant['id']))
                                                                <input type="hidden"
                                                                    name="variants[{{ $index }}][id]"
                                                                    value="{{ $variant['id'] }}">
                                                            @endif
                                                            @if (!empty($variant['attributes']) && is_array($variant['attributes']))
                                                                @foreach ($variant['attributes'] as $attId)
                                                                    <input type="hidden"
                                                                        name="variants[{{ $index }}][attributes][]"
                                                                        value="{{ $attId }}">
                                                                @endforeach
                                                            @endif
                                                            <div class="row mb-3">
                                                                <div class="col-md-9">
                                                                    <label class="form-label">Ảnh riêng cho biến
                                                                        thể</label>
                                                                    <input type="file"
                                                                        class="form-control form-control-sm @error('variants.' . $index . '.image') is-invalid @enderror"
                                                                        name="variants[{{ $index }}][image]"
                                                                        accept="image/*">
                                                                    @error('variants.' . $index . '.image')
                                                                        <div class="invalid-feedback d-block">
                                                                            {{ $message }}</div>
                                                                    @enderror
                                                                    @if (!empty($variant['image']) && Storage::disk('public')->exists($variant['image']))
                                                                        <img src="{{ asset('storage/' . $variant['image']) }}"
                                                                            class="img-fluid rounded mt-2" width="80">
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-3 d-flex align-items-end">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="variants[{{ $index }}][is_active]"
                                                                            value="1" @checked(old('variants.' . $index . '.is_active', !empty($variant['is_active'])))>
                                                                        <label class="form-check-label">Hoạt động</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h6 class="mb-3">Thông tin chính</h6>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Giá bán <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number"
                                                                        class="form-control @error('variants.' . $index . '.price') is-invalid @enderror"
                                                                        name="variants[{{ $index }}][price]"
                                                                        value="{{ old('variants.' . $index . '.price', $variant['price'] ?? '') }}">
                                                                    @error('variants.' . $index . '.price')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Giá khuyến mãi</label>
                                                                    <input type="number"
                                                                        class="form-control @error('variants.' . $index . '.sale_price') is-invalid @enderror"
                                                                        name="variants[{{ $index }}][sale_price]"
                                                                        value="{{ old('variants.' . $index . '.sale_price', $variant['sale_price'] ?? '') }}">
                                                                    @error('variants.' . $index . '.sale_price')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Tồn kho <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number"
                                                                        class="form-control @error('variants.' . $index . '.stock') is-invalid @enderror"
                                                                        name="variants[{{ $index }}][stock]"
                                                                        value="{{ old('variants.' . $index . '.stock', $variant['stock'] ?? '') }}">
                                                                    @error('variants.' . $index . '.stock')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Ngưỡng tồn kho thấp</label>
                                                                    <input type="number"
                                                                        class="form-control @error('variants.' . $index . '.low_stock_amount') is-invalid @enderror"
                                                                        name="variants[{{ $index }}][low_stock_amount]"
                                                                        value="{{ old('variants.' . $index . '.low_stock_amount', $variant['low_stock_amount'] ?? '') }}">
                                                                    @error('variants.' . $index . '.low_stock_amount')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <h6 class="mb-3">Thông tin vận chuyển</h6>
                                                            <div class="row">
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">Cân nặng (kg)</label>
                                                                    <input type="number" step="0.01"
                                                                        class="form-control @error('variants.' . $index . '.weight') is-invalid @enderror"
                                                                        name="variants[{{ $index }}][weight]"
                                                                        value="{{ old('variants.' . $index . '.weight', $variant['weight'] ?? '') }}">
                                                                    @error('variants.' . $index . '.weight')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">Dài (cm)</label>
                                                                    <input type="number" step="0.01"
                                                                        class="form-control @error('variants.' . $index . '.length') is-invalid @enderror"
                                                                        name="variants[{{ $index }}][length]"
                                                                        value="{{ old('variants.' . $index . '.length', $variant['length'] ?? '') }}">
                                                                    @error('variants.' . $index . '.length')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">Rộng (cm)</label>
                                                                    <input type="number" step="0.01"
                                                                        class="form-control @error('variants.' . $index . '.width') is-invalid @enderror"
                                                                        name="variants[{{ $index }}][width]"
                                                                        value="{{ old('variants.' . $index . '.width', $variant['width'] ?? '') }}">
                                                                    @error('variants.' . $index . '.width')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">Cao (cm)</label>
                                                                    <input type="number" step="0.01"
                                                                        class="form-control @error('variants.' . $index . '.height') is-invalid @enderror"
                                                                        name="variants[{{ $index }}][height]"
                                                                        value="{{ old('variants.' . $index . '.height', $variant['height'] ?? '') }}">
                                                                    @error('variants.' . $index . '.height')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const variantAttributeDropdown = document.getElementById('variantAttributeDropdown');
                                        const btnAddVariantAttribute = document.getElementById('btnAddVariantAttribute');
                                        const selectedVariantAttributesDiv = document.getElementById('selectedVariantAttributes');
                                        let selectedVariantAttributes = [];

                                        btnAddVariantAttribute?.addEventListener('click', function() {
                                            const attrId = variantAttributeDropdown.value;
                                            const attrName = variantAttributeDropdown.options[variantAttributeDropdown.selectedIndex]
                                                ?.text;
                                            if (!attrId) return alert('Vui lòng chọn thuộc tính!');
                                            if (selectedVariantAttributes.find(a => a.attrId === attrId)) return alert(
                                                'Thuộc tính này đã được chọn!');
                                            const selectedOption = variantAttributeDropdown.options[variantAttributeDropdown
                                                .selectedIndex];
                                            const values = selectedOption && selectedOption.dataset.values ? JSON.parse(selectedOption
                                                .dataset.values) : [];
                                            selectedVariantAttributes.push({
                                                attrId,
                                                attrName,
                                                valIds: [],
                                                values
                                            });
                                            renderSelectedVariantAttributes();
                                            variantAttributeDropdown.value = '';
                                        });

                                        function renderSelectedVariantAttributes() {
                                            selectedVariantAttributesDiv.innerHTML = selectedVariantAttributes.map((a, idx) => `
                                            <div class="mb-2">
                                                <div class="fw-bold mb-1">${a.attrName}:</div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    ${a.values.map(v => `
                                                        <label class="form-check form-check-inline mb-0">
                                                            <input type="checkbox" class="form-check-input variant-attr-value-checkbox" data-idx="${idx}" value="${v.id}" ${Array.isArray(a.valIds) && a.valIds.includes(v.id) ? 'checked' : ''}>
                                                            <span class="form-check-label">${v.value}</span>
                                                        </label>`).join('')}
                                                    <button type="button" class="btn btn-sm btn-danger ms-2 btnRemoveVariantAttr" data-id="${a.attrId}">Xóa</button>
                                                </div>
                                                ${(Array.isArray(a.valIds) ? a.valIds : []).map(valId => `<input type="hidden" name="variant_attributes[${a.attrId}][]" value="${valId}">`).join('')}
                                            </div>`).join('');
                                        }

                                        selectedVariantAttributesDiv?.addEventListener('input', function(e) {
                                            if (e.target.classList.contains('variant-attr-value-checkbox')) {
                                                const idx = e.target.dataset.idx;
                                                const checkboxes = selectedVariantAttributesDiv.querySelectorAll(
                                                    `.variant-attr-value-checkbox[data-idx='${idx}']`);
                                                const checkedVals = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
                                                selectedVariantAttributes[idx].valIds = checkedVals;
                                            }
                                        });

                                        selectedVariantAttributesDiv?.addEventListener('click', function(e) {
                                            if (e.target.classList.contains('btnRemoveVariantAttr')) {
                                                const id = e.target.dataset.id;
                                                selectedVariantAttributes = selectedVariantAttributes.filter(a => a.attrId !== id);
                                                renderSelectedVariantAttributes();
                                            }
                                        });
                                    });
                                </script>
                            </div>

                            <div class="tab-pane" id="tab-shipping" role="tabpanel">
                                <p class="text-muted">Thông tin vận chuyển này chỉ áp dụng cho sản phẩm đơn.</p>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Cân nặng (kg)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('weight') is-invalid @enderror" name="weight"
                                            value="{{ old('weight', $simpleVariant?->weight) }}">
                                        @error('weight')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Dài (cm)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('length') is-invalid @enderror" name="length"
                                            value="{{ old('length', $simpleVariant?->length) }}">
                                        @error('length')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Rộng (cm)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('width') is-invalid @enderror" name="width"
                                            value="{{ old('width', $simpleVariant?->width) }}">
                                        @error('width')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Cao (cm)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('height') is-invalid @enderror" name="height"
                                            value="{{ old('height', $simpleVariant?->height) }}">
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
                        <button type="submit" class="btn btn-primary w-100">Cập nhật sản phẩm</button>
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
                                <option value="active" @selected(old('status', $product->status) == 'active')>Hiển thị</option>
                                <option value="inactive" @selected(old('status', $product->status) == 'inactive')>Ẩn</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                value="1" @checked(old('is_featured', $product->is_featured))>
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
                            @if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail))
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Current Thumbnail"
                                    class="img-fluid rounded mt-2">
                            @endif
                        </div>
                        <hr>
                        <div>
                            <label class="form-label">Thư viện ảnh</label>
                            <div class="row g-2 mb-2">
                                @foreach ($product->allImages as $image)
                                    <div class="col-auto">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail"
                                                width="100" alt="Gallery image">
                                            <div class="position-absolute top-0 end-0 p-1 bg-white bg-opacity-75 rounded">
                                                <input type="checkbox" class="form-check-input" name="delete_images[]"
                                                    value="{{ $image->id }}" id="delete_image_{{ $image->id }}">
                                                <label for="delete_image_{{ $image->id }}" class="text-danger small"
                                                    title="Chọn để xóa">Xóa</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <label class="form-label small text-muted">Thêm ảnh mới vào thư viện</label>
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
            const accordionContainer = variantsWrapper.querySelector('.accordion');
            let variantIndex = {{ $product->variants->count() }};

            function toggleFields() {
                const isSimple = productTypeSelect.value === 'simple';
                simpleFields.style.display = isSimple ? 'block' : 'none';
                variableFields.style.display = isSimple ? 'none' : 'block';
                if (shippingTab) shippingTab.closest('.nav-item').style.display = isSimple ? 'block' : 'none';
            }

            const createVariantHtml = (combo, index, data = {}) => {
                const comboName = combo.map(c => c.text).join(' / ') || 'Biến thể mặc định';
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

            productTypeSelect.addEventListener('change', function() {
                toggleFields();
                if (this.value === 'variable' && accordionContainer.children.length === 0) {
                    // ...giữ nguyên logic tạo biến thể đầu tiên từ dữ liệu đơn...
                    const simpleData = {
                        price: document.querySelector('#simpleProductFields input[name="price"]').value,
                        sale_price: document.querySelector(
                            '#simpleProductFields input[name="sale_price"]').value,
                        stock: document.querySelector('#simpleProductFields input[name="stock"]').value,
                        low_stock_amount: document.querySelector(
                            '#simpleProductFields input[name="low_stock_amount"]').value,
                        weight: document.querySelector('#tab-shipping input[name="weight"]').value,
                        length: document.querySelector('#tab-shipping input[name="length"]').value,
                        width: document.querySelector('#tab-shipping input[name="width"]').value,
                        height: document.querySelector('#tab-shipping input[name="height"]').value,
                    };
                    const simpleAttributeValues = [];
                    document.querySelectorAll('#selectedAttributes select.attr-value-select').forEach(
                        select => {
                            if (select.value) {
                                const selectedOption = select.options[select.selectedIndex];
                                simpleAttributeValues.push({
                                    id: selectedOption.value,
                                    text: selectedOption.text
                                });
                            }
                        });
                    if (simpleAttributeValues.length > 0) {
                        const newVariantHtml = createVariantHtml(simpleAttributeValues, variantIndex,
                            simpleData);
                        accordionContainer.insertAdjacentHTML('beforeend', newVariantHtml);
                        variantIndex++;
                    }
                }
                // Nếu chuyển từ variable sang simple thì lấy dữ liệu biến thể đầu tiên đổ vào input đơn
                if (this.value === 'simple') {
                    const firstVariant = accordionContainer.querySelector('.accordion-item');
                    if (firstVariant) {
                        const getVal = (selector) => {
                            const input = firstVariant.querySelector(selector);
                            return input ? input.value : '';
                        };
                        document.querySelector('#simpleProductFields input[name="price"]').value = getVal(
                            'input[name*="[price]"]');
                        document.querySelector('#simpleProductFields input[name="sale_price"]').value =
                            getVal('input[name*="[sale_price]"]');
                        document.querySelector('#simpleProductFields input[name="stock"]').value = getVal(
                            'input[name*="[stock]"]');
                        document.querySelector('#simpleProductFields input[name="low_stock_amount"]')
                            .value = getVal('input[name*="[low_stock_amount]"]');
                        document.querySelector('#tab-shipping input[name="weight"]').value = getVal(
                            'input[name*="[weight]"]');
                        document.querySelector('#tab-shipping input[name="length"]').value = getVal(
                            'input[name*="[length]"]');
                        document.querySelector('#tab-shipping input[name="width"]').value = getVal(
                            'input[name*="[width]"]');
                        document.querySelector('#tab-shipping input[name="height"]').value = getVal(
                            'input[name*="[height]"]');
                        // Thuộc tính đơn: clear và set lại
                        const simpleAttrs = document.querySelectorAll(
                            '#selectedAttributes select.attr-value-select');
                        simpleAttrs.forEach(sel => sel.value = '');
                        const variantAttrs = firstVariant.querySelectorAll('input[name*="[attributes][]"]');
                        variantAttrs.forEach(input => {
                            const attrId = input.value;
                            const select = document.querySelector(
                                `#selectedAttributes select option[value='${attrId}']`);
                            if (select) select.selected = true;
                        });
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

            if (generateBtn) {
                generateBtn.addEventListener('click', () => {
                    const selectedAttributes = Array.from(document.querySelectorAll(
                            '#selectedVariantAttributes .variant-attr-value-checkbox:checked'))
                        .reduce((acc, checkbox) => {
                            const attrId = checkbox.closest('.mb-2').querySelector(
                                '.btnRemoveVariantAttr').dataset.id;
                            if (!acc[attrId]) acc[attrId] = [];
                            acc[attrId].push({
                                id: checkbox.value,
                                text: checkbox.nextElementSibling.textContent
                            });
                            return acc;
                        }, {});

                    const attributeGroups = Object.values(selectedAttributes);
                    if (attributeGroups.length === 0) {
                        alert('Vui lòng chọn ít nhất một giá trị thuộc tính.');
                        return;
                    }

                    const combinations = getCombinations(attributeGroups);
                    const existingCombos = Array.from(accordionContainer.querySelectorAll(
                        '.accordion-item')).map(
                        item => Array.from(item.querySelectorAll('input[name*="[attributes][]"]')).map(
                            input => input.value).sort().join('-')
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

            document.getElementById('btnAddImage')?.addEventListener('click', function(e) {
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
                if (e.target && e.target.classList.contains('btnRemoveImage')) {
                    e.preventDefault();
                    e.target.closest('.gallery-item').remove();
                }
            });
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
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => new MyUploadAdapter(loader);
            }
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote',
                        'insertTable', 'undo', 'redo', 'imageUpload'
                    ],
                    mediaEmbed: {
                        previewsInData: true
                    },
                    htmlSupport: {
                        allow: [{
                            name: 'iframe',
                            attributes: true,
                            classes: true,
                            styles: true
                        }]
                    },
                    extraPlugins: [MyCustomUploadAdapterPlugin]
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        document.querySelector('#editor').value = editor.getData();
                    });
                })
                .catch(error => console.error(error));
        </script>
    @endsection
@endpush
