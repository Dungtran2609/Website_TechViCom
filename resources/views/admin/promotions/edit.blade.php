@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Sửa chương trình khuyến mãi</h1>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        {{-- Tên chương trình --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên chương trình <span class="text-danger">*</span>
                                <small class="text-muted">(Tối thiểu 3 ký tự)</small></label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $promotion->name) }}" minlength="3" maxlength="255"
                                placeholder="Nhập tên chương trình khuyến mãi...">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Kiểu áp dụng --}}
                        <div class="mb-3">
                            <label for="flash_type" class="form-label">Kiểu áp dụng <span
                                    class="text-danger">*</span></label>
                            <select name="flash_type" id="flash_type"
                                class="form-select @error('flash_type') is-invalid @enderror">
                                @php $selectedType = request()->old('flash_type', $promotion->flash_type); @endphp
                                <option value="">-- Chọn kiểu áp dụng --</option>
                                <option value="all" {{ $selectedType == 'all' ? 'selected' : '' }}>Toàn bộ sản phẩm
                                </option>
                                <option value="category" {{ $selectedType == 'category' ? 'selected' : '' }}>Theo danh mục
                                </option>
                                <option value="flash_sale" {{ $selectedType == 'flash_sale' ? 'selected' : '' }}>Theo sản
                                    phẩm (Flash Sale)</option>
                            </select>
                            @error('flash_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả <small class="text-muted">(Tối đa 1000 ký
                            tự)</small></label>
                    <textarea name="description" id="description" rows="3"
                        class="form-control @error('description') is-invalid @enderror" maxlength="1000"
                        placeholder="Nhập mô tả chương trình khuyến mãi...">{{ old('description', $promotion->description) }}</textarea>
                    <div class="form-text">
                        <span id="char-count">0</span>/1000 ký tự
                    </div>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Chọn danh mục --}}
                <div class="mb-3" id="category-select"
                    style="display:{{ $selectedType == 'category' ? 'block' : 'none' }};">
                    <label class="form-label">Chọn danh mục <span class="text-danger">*</span></label>
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary me-1"
                            onclick="selectAllCategories(true)">
                            <i class="fas fa-check-double me-1"></i>Chọn tất cả
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="selectAllCategories(false)">
                            <i class="fas fa-times me-1"></i>Bỏ chọn tất cả
                        </button>
                    </div>
                    <div class="border rounded p-3 mb-3 @error('categories') border-danger @enderror"
                        style="max-height:220px;overflow:auto;">
                        <div class="row">
                            @foreach ($categories as $category)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input category-checkbox" type="checkbox"
                                            name="categories[]" id="category_{{ $category->id }}"
                                            value="{{ $category->id }}"
                                            {{ in_array($category->id, old('categories', $promotion->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="category_{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('categories')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <div class="mb-2">
                        <label for="category_discount_value" class="form-label">Giảm giá (%) cho tất cả sản phẩm thuộc danh
                            mục</label>
                        <input type="number" min="1" max="100" step="1"
                            class="form-control @error('category_discount_value') is-invalid @enderror"
                            name="category_discount_value" id="category_discount_value"
                            value="{{ old('category_discount_value', $promotion->discount_value ?? 10) }}"
                            placeholder="Nhập % giảm giá (ví dụ: 10)">
                        @error('category_discount_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <script>
                    function selectAllCategories(checked) {
                        document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = checked);
                    }
                </script>

                {{-- Chọn sản phẩm --}}
                <div class="mb-3" id="product-select"
                    style="display:{{ $selectedType == 'flash_sale' ? 'block' : 'none' }};">
                    <label class="form-label">Chọn sản phẩm <span class="text-danger">*</span></label>
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary me-1"
                            onclick="selectAllProducts(true)">
                            <i class="fas fa-check-double me-1"></i>Chọn tất cả
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="selectAllProducts(false)">
                            <i class="fas fa-times me-1"></i>Bỏ chọn tất cả
                        </button>
                    </div>
                    <div class="border rounded p-3 @error('products') border-danger @enderror"
                        style="max-height:220px;overflow:auto;">
                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-md-12 mb-2">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input product-checkbox me-2" type="checkbox"
                                            name="products[]" id="product_{{ $product->id }}"
                                            value="{{ $product->id }}"
                                            {{ in_array($product->id, old('products', $promotion->products->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label me-3 flex-grow-1"
                                            for="product_{{ $product->id }}">{{ $product->name }}</label>
                                        <input type="number" step="1000" min="0"
                                            class="form-control form-control-sm sale-price-input"
                                            name="sale_prices[{{ $product->id }}]" placeholder="Giá flash sale"
                                            style="width:130px; display:none;"
                                            value="{{ old('sale_prices.' . $product->id, optional(optional($promotion->products->find($product->id))->pivot)->sale_price) }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('products')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <script>
                    function selectAllProducts(checked) {
                        document.querySelectorAll('.product-checkbox').forEach(cb => {
                            cb.checked = checked;
                            cb.dispatchEvent(new Event('change'));
                        });
                    }
                    // Show sale price input if flash_type is flash_sale
                    function updateSalePriceInputs() {
                        let flashType = document.getElementById('flash_type') ? document.getElementById('flash_type').value : '';
                        document.querySelectorAll('.sale-price-input').forEach(function(input) {
                            input.style.display = (flashType === 'flash_sale') ? 'inline-block' : 'none';
                        });
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        // If you have a flash_type select, listen to its change
                        let flashTypeSelect = document.getElementById('flash_type');
                        if (flashTypeSelect) {
                            flashTypeSelect.addEventListener('change', updateSalePriceInputs);
                            updateSalePriceInputs();
                        }
                        // Show/hide sale price input only for checked products
                        document.querySelectorAll('.product-checkbox').forEach(function(cb) {
                            cb.addEventListener('change', function() {
                                let saleInput = this.closest('.form-check').querySelector('.sale-price-input');
                                if (saleInput) {
                                    saleInput.style.display = (this.checked && (flashTypeSelect &&
                                        flashTypeSelect.value === 'flash_sale')) ? 'inline-block' : 'none';
                                }
                            });
                        });
                    });
                </script>

                <div class="row">
                    <div class="col-md-6">
                        {{-- Ngày bắt đầu --}}
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span>
                                <small class="text-muted">(Từ hôm
                                    nay)</small></label>
                            <input type="datetime-local" name="start_date" id="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date', $promotion->start_date ? date('Y-m-d\TH:i', strtotime($promotion->start_date)) : '') }}"
                                min="{{ date('Y-m-d\TH:i') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Ngày kết thúc --}}
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span>
                                <small class="text-muted">(Sau ngày
                                    bắt đầu)</small></label>
                            <input type="datetime-local" name="end_date" id="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date', $promotion->end_date ? date('Y-m-d\TH:i', strtotime($promotion->end_date)) : '') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Trạng thái --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="1"
                            {{ old('status', $promotion->status ? '1' : '0') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0"
                            {{ old('status', $promotion->status ? '1' : '0') == '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Action --}}
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Huỷ
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>

    </form>
    </div>
    </div>

    {{-- Script hiển thị select theo type và validation ngày --}}
    <script>
        function updateTypeSelectDisplay() {
            var type = document.getElementById('flash_type').value;
            document.getElementById('category-select').style.display = type === 'category' ? 'block' : 'none';
            document.getElementById('product-select').style.display = type === 'flash_sale' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('flash_type').addEventListener('change', updateTypeSelectDisplay);
            updateTypeSelectDisplay();

            // Validation ngày
            document.getElementById('start_date').addEventListener('change', function() {
                const startDate = this.value;
                const endDateInput = document.getElementById('end_date');

                if (startDate) {
                    endDateInput.min = startDate;

                    // Nếu end_date hiện tại nhỏ hơn start_date mới, xóa end_date
                    if (endDateInput.value && endDateInput.value < startDate) {
                        endDateInput.value = '';
                    }
                }
            });

            // Đảm bảo start_date không nhỏ hơn hôm nay (chỉ khi tạo mới)
            document.getElementById('start_date').addEventListener('change', function() {
                const today = new Date().toISOString().slice(0, 16);
                const currentValue = this.value;
                const originalValue =
                    '{{ $promotion->start_date ? date('Y-m-d\TH:i', strtotime($promotion->start_date)) : '' }}';

                // Chỉ validate nếu giá trị mới khác giá trị gốc
                if (currentValue && currentValue < today && currentValue !== originalValue) {
                    alert('Ngày bắt đầu phải từ hôm nay trở đi!');
                    this.value = originalValue || '';
                }
            });

            // Đếm ký tự cho textarea
            document.getElementById('description').addEventListener('input', function() {
                const charCount = this.value.length;
                document.getElementById('char-count').textContent = charCount;

                // Thay đổi màu khi gần đạt giới hạn
                const charCountElement = document.getElementById('char-count');
                if (charCount > 900) {
                    charCountElement.style.color = '#dc3545';
                } else if (charCount > 800) {
                    charCountElement.style.color = '#fd7e14';
                } else {
                    charCountElement.style.color = '#6c757d';
                }
            });

            // Khởi tạo đếm ký tự khi trang load
            const description = document.getElementById('description');
            const charCount = description.value.length;
            document.getElementById('char-count').textContent = charCount;
        });
    </script>
@endsection
