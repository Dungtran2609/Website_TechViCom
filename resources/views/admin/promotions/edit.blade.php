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
                            placeholder="Nhập % giảm giá (ví dụ: 10)"
                            oninput="validateCategoryDiscount(this)" maxlength="3">
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
                    
                    {{-- Tìm kiếm sản phẩm --}}
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="product-search" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                            <button type="button" class="btn btn-outline-secondary" onclick="clearProductSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary me-1"
                            onclick="selectAllProducts(true)">
                            <i class="fas fa-check-double me-1"></i>Chọn tất cả
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="selectAllProducts(false)">
                            <i class="fas fa-times me-1"></i>Bỏ chọn tất cả
                        </button>
                        <span class="badge bg-info ms-2" id="product-count">0 sản phẩm được chọn</span>
                    </div>
                    
                    <div class="border rounded p-3 @error('products') border-danger @enderror"
                        style="max-height:300px;overflow:auto;">
                        <div class="row" id="products-container">
                            @foreach ($products as $product)
                                <div class="col-md-12 mb-2 product-item" data-name="{{ strtolower($product->name) }}">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input product-checkbox me-2" type="checkbox"
                                            name="products[]" id="product_{{ $product->id }}"
                                            value="{{ $product->id }}"
                                            {{ in_array($product->id, old('products', $promotion->products->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label me-3 flex-grow-1"
                                            for="product_{{ $product->id }}">
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium">{{ $product->name }}</span>
                                            </div>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" step="0.01" min="0" max="100"
                                                class="form-control form-control-sm discount-percent-input"
                                                name="discount_percents[{{ $product->id }}]" 
                                                placeholder="% giảm giá"
                                                style="width:100px; display:none;"
                                                value="{{ old('discount_percents.' . $product->id, optional(optional($promotion->products->find($product->id))->pivot)->discount_percent ?? '') }}"
                                                oninput="validateDiscountPercent(this, {{ $product->id }})">
                                            <span class="text-muted small" style="width:60px;">%</span>
                                            <span class="text-muted">hoặc</span>
                                            <input type="number" step="1000" min="0" max="9999999"
                                                class="form-control form-control-sm sale-price-input"
                                                name="sale_prices[{{ $product->id }}]" 
                                                placeholder="Giá cố định"
                                                style="width:130px; display:none;"
                                                value="{{ old('sale_prices.' . $product->id, optional(optional($promotion->products->find($product->id))->pivot)->sale_price) }}"
                                                oninput="validateSalePrice(this, {{ $product->id }})">
                                            <span class="text-muted small" style="width:60px;">₫</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('products')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Nhập phần trăm giảm giá (0-100%) hoặc giá cố định cho từng sản phẩm. Sản phẩm không có giá trị sẽ không được áp dụng khuyến mãi.
                    </div>
                </div>
                <script>
                    function selectAllProducts(checked) {
                        document.querySelectorAll('.product-checkbox').forEach(cb => {
                            cb.checked = checked;
                            cb.dispatchEvent(new Event('change'));
                        });
                        
                        // Sắp xếp lại sau khi chọn tất cả
                        setTimeout(() => {
                            sortProductsBySelection();
                        }, 100);
                    }

                    function updateProductCount() {
                        const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
                        document.getElementById('product-count').textContent = `${checkedCount} sản phẩm được chọn`;
                    }

                    function clearProductSearch() {
                        document.getElementById('product-search').value = '';
                        filterProducts('');
                    }

                    function filterProducts(searchTerm) {
                        const products = document.querySelectorAll('.product-item');
                        const term = searchTerm.toLowerCase();
                        
                        products.forEach(product => {
                            const name = product.getAttribute('data-name');
                            if (name.includes(term)) {
                                product.style.display = 'block';
                            } else {
                                product.style.display = 'none';
                            }
                        });
                    }

                    // Function để sắp xếp sản phẩm theo trạng thái chọn
                    function sortProductsBySelection() {
                        const container = document.getElementById('products-container');
                        const products = Array.from(container.querySelectorAll('.product-item'));
                        
                        // Tách sản phẩm đã chọn và chưa chọn
                        const selectedProducts = [];
                        const unselectedProducts = [];
                        
                        products.forEach(product => {
                            const checkbox = product.querySelector('.product-checkbox');
                            if (checkbox.checked) {
                                selectedProducts.push(product);
                            } else {
                                unselectedProducts.push(product);
                            }
                        });
                        
                        // Sắp xếp lại: sản phẩm đã chọn lên đầu
                        const sortedProducts = [...selectedProducts, ...unselectedProducts];
                        
                        // Cập nhật DOM
                        sortedProducts.forEach(product => {
                            container.appendChild(product);
                        });
                    }

                    // Show discount inputs if flash_type is flash_sale
                    function updateDiscountInputs() {
                        let flashType = document.getElementById('flash_type') ? document.getElementById('flash_type').value : '';
                        document.querySelectorAll('.discount-percent-input, .sale-price-input').forEach(function(input) {
                            input.style.display = (flashType === 'flash_sale') ? 'inline-block' : 'none';
                        });
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        // Search functionality
                        document.getElementById('product-search').addEventListener('input', function() {
                            filterProducts(this.value);
                        });

                        // Flash type change
                        let flashTypeSelect = document.getElementById('flash_type');
                        if (flashTypeSelect) {
                            flashTypeSelect.addEventListener('change', updateDiscountInputs);
                            updateDiscountInputs();
                        }

                        // Show/hide discount inputs only for checked products
                        document.querySelectorAll('.product-checkbox').forEach(function(cb) {
                            cb.addEventListener('change', function() {
                                let discountInput = this.closest('.form-check').querySelector('.discount-percent-input');
                                let salePriceInput = this.closest('.form-check').querySelector('.sale-price-input');
                                if (discountInput) {
                                    discountInput.style.display = (this.checked && (flashTypeSelect &&
                                        flashTypeSelect.value === 'flash_sale')) ? 'inline-block' : 'none';
                                }
                                if (salePriceInput) {
                                    salePriceInput.style.display = (this.checked && (flashTypeSelect &&
                                        flashTypeSelect.value === 'flash_sale')) ? 'inline-block' : 'none';
                                }
                                updateProductCount();
                                
                                // Sắp xếp lại danh sách sản phẩm
                                sortProductsBySelection();
                            });
                        });

                        // Initialize count
                        updateProductCount();
                        
                        // Sắp xếp ban đầu: sản phẩm đã chọn lên đầu
                        sortProductsBySelection();
                    });
                </script>

                <div class="row">
                    <div class="col-md-6">
                                        {{-- Ngày bắt đầu --}}
                <div class="mb-3">
                    <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span>
                        <small class="text-muted">(Từ hôm nay)</small></label>
                    <input type="datetime-local" name="start_date" id="start_date"
                        class="form-control @error('start_date') is-invalid @enderror"
                        value="{{ old('start_date', $promotion->start_date ? date('Y-m-d\TH:i', strtotime($promotion->start_date)) : '') }}">
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Nếu ngày bắt đầu đã qua, hệ thống sẽ tự động cập nhật thành thời gian hiện tại khi lưu.
                    </div>
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

            // Bỏ validation ngày bắt đầu < today vì sẽ được tự động cập nhật
            // document.getElementById('start_date').addEventListener('change', function() {
            //     const today = new Date().toISOString().slice(0, 16);
            //     const currentValue = this.value;
            //     const originalValue =
            //         '{{ $promotion->start_date ? date('Y-m-d\TH:i', strtotime($promotion->start_date)) : '' }}';

            //     // Chỉ validate nếu giá trị mới khác giá trị gốc
            //     if (currentValue && currentValue < today && currentValue !== originalValue) {
            //         alert('Ngày bắt đầu phải từ hôm nay trở đi!');
            //         this.value = originalValue || '';
            //     }
            // });

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

            // Real-time validation cho tên chương trình
            document.getElementById('name').addEventListener('input', function() {
                const name = this.value.trim();
                if (name.length === 0) {
                    showError('name', 'Tên chương trình là bắt buộc!');
                } else if (name.length < 3) {
                    showError('name', 'Tên chương trình phải có ít nhất 3 ký tự!');
                } else if (name.length > 255) {
                    showError('name', 'Tên chương trình không được vượt quá 255 ký tự!');
                } else {
                    this.classList.remove('is-invalid');
                    const existingError = this.parentNode.querySelector('.invalid-feedback');
                    if (existingError) {
                        existingError.remove();
                    }
                }
            });

            // Real-time validation cho kiểu áp dụng
            document.getElementById('flash_type').addEventListener('change', function() {
                const value = this.value;
                if (!value) {
                    showError('flash_type', 'Vui lòng chọn kiểu áp dụng!');
                } else if (!['all', 'category', 'flash_sale'].includes(value)) {
                    showError('flash_type', 'Kiểu áp dụng không hợp lệ!');
                } else {
                    this.classList.remove('is-invalid');
                    const existingError = this.parentNode.querySelector('.invalid-feedback');
                    if (existingError) {
                        existingError.remove();
                    }
                }
            });

            // Real-time validation cho trạng thái
            document.getElementById('status').addEventListener('change', function() {
                if (!this.value) {
                    showError('status', 'Vui lòng chọn trạng thái!');
                } else {
                    this.classList.remove('is-invalid');
                    const existingError = this.parentNode.querySelector('.invalid-feedback');
                    if (existingError) {
                        existingError.remove();
                    }
                }
            });

            // Form validation khi submit
            document.querySelector('form').addEventListener('submit', function(e) {
                // Clear previous error messages
                clearAllErrors();

                // Validation sẽ được xử lý ở backend
                // Chỉ kiểm tra cơ bản ở frontend để UX tốt hơn
                const name = document.getElementById('name').value.trim();
                const flashType = document.getElementById('flash_type').value;
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                const status = document.getElementById('status').value;
                const today = new Date().toISOString().slice(0, 16);

                let hasError = false;

                // Kiểm tra tên chương trình
                if (!name) {
                    showError('name', 'Tên chương trình là bắt buộc!');
                    hasError = true;
                } else if (name.length < 3) {
                    showError('name', 'Tên chương trình phải có ít nhất 3 ký tự!');
                    hasError = true;
                } else if (name.length > 255) {
                    showError('name', 'Tên chương trình không được vượt quá 255 ký tự!');
                    hasError = true;
                }

                // Kiểm tra kiểu áp dụng
                if (!flashType) {
                    showError('flash_type', 'Vui lòng chọn kiểu áp dụng!');
                    hasError = true;
                } else if (!['all', 'category', 'flash_sale'].includes(flashType)) {
                    showError('flash_type', 'Kiểu áp dụng không hợp lệ!');
                    hasError = true;
                }

                // Kiểm tra trạng thái
                if (status === null || status === '') {
                    showError('status', 'Vui lòng chọn trạng thái!');
                    hasError = true;
                }

                // Kiểm tra ngày bắt đầu
                if (!startDate) {
                    showError('start_date', 'Vui lòng chọn ngày bắt đầu!');
                    hasError = true;
                }
                // Bỏ validation ngày bắt đầu < today vì sẽ được tự động cập nhật

                // Kiểm tra ngày kết thúc
                if (!endDate) {
                    showError('end_date', 'Vui lòng chọn ngày kết thúc!');
                    hasError = true;
                } else if (endDate <= startDate) {
                    showError('end_date', 'Ngày kết thúc phải lớn hơn ngày bắt đầu!');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    return false;
                }
            });

            // Function validate sale price (giới hạn 7 số)
            function validateSalePrice(input, productId) {
                const value = parseFloat(input.value);
                const maxValue = 9999999; // Giới hạn 7 số
                
                // Giới hạn độ dài input
                if (input.value.length > 7) {
                    input.value = input.value.slice(0, 7);
                }
                
                if (value < 0) {
                    input.classList.add('is-invalid');
                    if (!input.parentNode.querySelector('.invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Giá cố định phải lớn hơn 0';
                        input.parentNode.appendChild(errorDiv);
                    }
                } else if (value > maxValue) {
                    input.classList.add('is-invalid');
                    if (!input.parentNode.querySelector('.invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Giá cố định tối đa 9,999,999₫';
                        input.parentNode.appendChild(errorDiv);
                    }
                } else {
                    input.classList.remove('is-invalid');
                    const existingError = input.parentNode.querySelector('.invalid-feedback');
                    if (existingError) {
                        existingError.remove();
                    }
                }
            }

            // Function validate discount percent
            function validateDiscountPercent(input, productId) {
                const value = parseFloat(input.value);
                
                if (value < 0 || value > 100) {
                    input.classList.add('is-invalid');
                    if (!input.parentNode.querySelector('.invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Phần trăm giảm giá phải từ 0-100%';
                        input.parentNode.appendChild(errorDiv);
                    }
                } else {
                    input.classList.remove('is-invalid');
                    const existingError = input.parentNode.querySelector('.invalid-feedback');
                    if (existingError) {
                        existingError.remove();
                    }
                }
            }

            // Function validate category discount
            function validateCategoryDiscount(input) {
                const value = parseFloat(input.value);
                if (value < 1 || value > 100) {
                    input.classList.add('is-invalid');
                    if (!input.parentNode.querySelector('.invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Giá trị giảm giá phải từ 1-100%';
                        input.parentNode.appendChild(errorDiv);
                    }
                } else if (value > 9223372036854775807) {
                    input.classList.add('is-invalid');
                    if (!input.parentNode.querySelector('.invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Giá trị giảm giá quá lớn';
                        input.parentNode.appendChild(errorDiv);
                    }
                } else {
                    input.classList.remove('is-invalid');
                    const existingError = input.parentNode.querySelector('.invalid-feedback');
                    if (existingError) {
                        existingError.remove();
                    }
                }
            }

            // Function để hiển thị lỗi
            function showError(fieldId, message) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.classList.add('is-invalid');

                    // Xóa error message cũ nếu có
                    const existingError = field.parentNode.querySelector('.invalid-feedback');
                    if (existingError) {
                        existingError.remove();
                    }

                    // Tạo error message mới
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = message;
                    field.parentNode.appendChild(errorDiv);
                } else if (fieldId === 'products') {
                    // Xử lý đặc biệt cho validation sản phẩm
                    const productContainer = document.getElementById('product-select');
                    if (productContainer) {
                        // Xóa error message cũ nếu có
                        const existingError = productContainer.querySelector('.text-danger');
                        if (existingError && existingError.textContent.includes('sản phẩm')) {
                            existingError.remove();
                        }

                        // Tạo error message mới
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'text-danger small';
                        errorDiv.textContent = message;
                        productContainer.appendChild(errorDiv);
                    }
                }
            }

            // Function để xóa tất cả lỗi
            function clearAllErrors() {
                document.querySelectorAll('.is-invalid').forEach(field => {
                    field.classList.remove('is-invalid');
                });
                document.querySelectorAll('.invalid-feedback').forEach(error => {
                    error.remove();
                });
                // Xóa lỗi sản phẩm và danh mục
                document.querySelectorAll('.text-danger.small').forEach(error => {
                    if (error.textContent.includes('sản phẩm') || error.textContent.includes('danh mục')) {
                        error.remove();
                    }
                });
            }

            // Function để hiển thị lỗi từ backend
            function showBackendErrors(errors) {
                clearAllErrors();

                Object.keys(errors).forEach(field => {
                    const errorMessage = errors[field][0];
                    showError(field, errorMessage);
                });
            }
        });

        // Xử lý lỗi từ Laravel validation
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const errors = @json($errors->getMessages());
                showBackendErrors(errors);
            });
        @endif

                    // Real-time validation cho ngày bắt đầu
            document.getElementById('start_date').addEventListener('change', function() {
                const startDate = this.value;
                const today = new Date().toISOString().slice(0, 16);

                if (startDate && startDate < today) {
                    // Hiển thị thông báo thân thiện thay vì lỗi
                    const infoDiv = this.parentNode.querySelector('.form-text');
                    if (infoDiv) {
                        infoDiv.innerHTML = '<i class="fas fa-info-circle me-1 text-warning"></i>Ngày bắt đầu đã qua, sẽ được tự động cập nhật thành thời gian hiện tại khi lưu.';
                        infoDiv.className = 'form-text text-warning';
                    }
                } else {
                    // Khôi phục thông báo mặc định
                    const infoDiv = this.parentNode.querySelector('.form-text');
                    if (infoDiv) {
                        infoDiv.innerHTML = '<i class="fas fa-info-circle me-1"></i>Nếu ngày bắt đầu đã qua, hệ thống sẽ tự động cập nhật thành thời gian hiện tại khi lưu.';
                        infoDiv.className = 'form-text';
                    }
                    
                    this.classList.remove('is-invalid');
                    const existingError = this.parentNode.querySelector('.invalid-feedback');
                    if (existingError) {
                        existingError.remove();
                    }
                }
            });

        // Real-time validation cho ngày kết thúc
        document.getElementById('end_date').addEventListener('change', function() {
            const endDate = this.value;
            const startDate = document.getElementById('start_date').value;

            if (endDate && startDate && endDate <= startDate) {
                showError('end_date', 'Ngày kết thúc phải lớn hơn ngày bắt đầu!');
            } else {
                this.classList.remove('is-invalid');
                const existingError = this.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }
            }
        });
    </script>
@endsection
