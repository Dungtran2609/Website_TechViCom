                // ...existing code...
                @extends('admin.layouts.app')

                @section('content')
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Tạo chương trình khuyến mãi</h1>
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
                            <form action="{{ route('admin.promotions.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- Tên chương trình --}}
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên chương trình <span
                                                    class="text-danger">*</span> <small class="text-muted">(Tối thiểu 3 ký
                                                    tự)</small></label>
                                            <input type="text" name="name" id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" minlength="3" maxlength="255"
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
                                                <option value="">-- Chọn kiểu áp dụng --</option>
                                                <option value="flash_sale"
                                                    {{ old('flash_type') == 'flash_sale' ? 'selected' : '' }}>Theo sản phẩm
                                                    (Flash Sale)</option>
                                            </select>
                                            @error('flash_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Mô tả --}}
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả <small class="text-muted">(Tối đa 1000
                                            ký tự)</small></label>
                                    <textarea name="description" id="description" rows="3"
                                        class="form-control @error('description') is-invalid @enderror" maxlength="1000"
                                        placeholder="Nhập mô tả chương trình khuyến mãi...">{{ old('description') }}</textarea>
                                    <div class="form-text">
                                        <span id="char-count">0</span>/1000 ký tự
                                    </div>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Chọn danh mục --}}
                                <div class="mb-3" id="category-select" style="display:none;">
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
                                                            {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
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
                                        <label for="category_discount_value" class="form-label">Giảm giá (%) cho tất cả sản
                                            phẩm thuộc danh mục</label>
                                        <input type="number" min="1" max="100" step="1"
                                            class="form-control @error('category_discount_value') is-invalid @enderror"
                                            name="category_discount_value" id="category_discount_value"
                                            value="{{ old('category_discount_value', 10) }}"
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
                                <div class="mb-3" id="product-select" style="display:none;">
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
                                                        <input class="form-check-input product-checkbox me-2"
                                                            type="checkbox" name="products[]"
                                                            id="product_{{ $product->id }}" value="{{ $product->id }}"
                                                            {{ in_array($product->id, old('products', [])) ? 'checked' : '' }}>
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
                                                value="{{ old('discount_percents.' . $product->id) }}">
                                            <span class="text-muted small" style="width:60px;">%</span>
                                            <span class="text-muted">hoặc</span>
                                            <input type="number" step="1000" min="0"
                                                class="form-control form-control-sm sale-price-input"
                                                name="sale_prices[{{ $product->id }}]" 
                                                placeholder="Giá cố định"
                                                style="width:130px; display:none;"
                                                value="{{ old('sale_prices.' . $product->id) }}">
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
                                            });
                                        });

                                        // Initialize count
                                        updateProductCount();
                                    });
                                </script>

                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- Ngày bắt đầu --}}
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">Ngày bắt đầu <span
                                                    class="text-danger">*</span> <small class="text-muted">(Từ hôm
                                                    nay)</small></label>
                                            <input type="datetime-local" name="start_date" id="start_date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                value="{{ old('start_date') }}" min="{{ date('Y-m-d\TH:i') }}">
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- Ngày kết thúc --}}
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">Ngày kết thúc <span
                                                    class="text-danger">*</span> <small class="text-muted">(Sau ngày bắt
                                                    đầu)</small></label>
                                            <input type="datetime-local" name="end_date" id="end_date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                value="{{ old('end_date') }}">
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Trạng thái --}}
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái <span
                                            class="text-danger">*</span></label>
                                    <select name="status" id="status"
                                        class="form-select @error('status') is-invalid @enderror">
                                        <option value="">-- Chọn trạng thái --</option>
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Kích hoạt
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
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
                                        <i class="fas fa-plus me-1"></i> Tạo chương trình
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Script hiển thị select theo type và validation ngày --}}
                    <script>
                        document.getElementById('flash_type').addEventListener('change', function() {
                            document.getElementById('category-select').style.display = this.value === 'category' ? 'block' : 'none';
                            document.getElementById('product-select').style.display = this.value === 'flash_sale' ? 'block' :
                                'none';
                        });

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

                        // Đảm bảo start_date không nhỏ hơn hôm nay
                        document.getElementById('start_date').addEventListener('change', function() {
                            const today = new Date().toISOString().slice(0, 16);
                            if (this.value < today) {
                                alert('Ngày bắt đầu phải từ hôm nay trở đi!');
                                this.value = '';
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
                        document.addEventListener('DOMContentLoaded', function() {
                            const description = document.getElementById('description');
                            const charCount = description.value.length;
                            document.getElementById('char-count').textContent = charCount;
                        });

                        // Form validation khi submit
                        document.querySelector('form').addEventListener('submit', function(e) {
                            const startDate = document.getElementById('start_date').value;
                            const endDate = document.getElementById('end_date').value;
                            const today = new Date().toISOString().slice(0, 16);
                            const flashType = document.getElementById('flash_type').value;

                            // Kiểm tra ngày bắt đầu
                            if (!startDate) {
                                e.preventDefault();
                                alert('Vui lòng chọn ngày bắt đầu!');
                                document.getElementById('start_date').focus();
                                return false;
                            }

                            if (startDate < today) {
                                e.preventDefault();
                                alert('Ngày bắt đầu phải từ hôm nay trở đi!');
                                document.getElementById('start_date').focus();
                                return false;
                            }

                            // Kiểm tra ngày kết thúc
                            if (!endDate) {
                                e.preventDefault();
                                alert('Vui lòng chọn ngày kết thúc!');
                                document.getElementById('end_date').focus();
                                return false;
                            }

                            if (endDate <= startDate) {
                                e.preventDefault();
                                alert('Ngày kết thúc phải lớn hơn ngày bắt đầu!');
                                document.getElementById('end_date').focus();
                                return false;
                            }

                            // Kiểm tra sản phẩm và phần trăm giảm giá
                            if (flashType === 'flash_sale') {
                                const checkedProducts = document.querySelectorAll('.product-checkbox:checked');
                                if (checkedProducts.length === 0) {
                                    e.preventDefault();
                                    alert('Vui lòng chọn ít nhất 1 sản phẩm!');
                                    return false;
                                }

                                let hasDiscountValue = false;
                                checkedProducts.forEach(product => {
                                    const discountInput = product.closest('.form-check').querySelector('.discount-percent-input');
                                    const salePriceInput = product.closest('.form-check').querySelector('.sale-price-input');
                                    
                                    if ((discountInput && discountInput.value && parseFloat(discountInput.value) > 0) ||
                                        (salePriceInput && salePriceInput.value && parseFloat(salePriceInput.value) > 0)) {
                                        hasDiscountValue = true;
                                    }
                                });

                                if (!hasDiscountValue) {
                                    e.preventDefault();
                                    alert('Vui lòng nhập phần trăm giảm giá hoặc giá cố định cho ít nhất 1 sản phẩm!');
                                    return false;
                                }
                            }
                        });
                    </script>
                @endsection
