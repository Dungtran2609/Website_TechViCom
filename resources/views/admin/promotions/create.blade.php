                // ...existing code...
@extends('admin.layouts.app')
@section('title', 'Tạo chương trình khuyến mãi')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif
    <h1 class="h4 fw-bold mb-4 text-dark">➕ Tạo chương trình khuyến mãi</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.promotions.store') }}" method="POST">
                @csrf

                {{-- Coupon selection removed --}}

                {{-- Tên chương trình --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Tên chương trình</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                </div>

                {{-- Kiểu áp dụng --}}
                <div class="mb-3">
                    <label for="type" class="form-label fw-semibold">Kiểu áp dụng</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="all">Toàn bộ sản phẩm</option>
                        <option value="category">Theo danh mục</option>
                        <option value="product">Theo sản phẩm</option>
                    </select>
                </div>

                {{-- Chọn danh mục --}}
                <div class="mb-3" id="category-select" style="display:none;">
                    <label class="form-label fw-semibold">Chọn danh mục</label>
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="selectAllCategories(true)">Chọn tất cả</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAllCategories(false)">Bỏ chọn tất cả</button>
                    </div>
                    <div class="border rounded p-2" style="max-height:220px;overflow:auto;">
                        @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input category-checkbox" type="checkbox" name="categories[]" id="category_{{ $category->id }}" value="{{ $category->id }}">
                                <label class="form-check-label" for="category_{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <script>
                function selectAllCategories(checked) {
                    document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = checked);
                }
                </script>

                {{-- Chọn sản phẩm --}}
                <div class="mb-3" id="product-select" style="display:none;">
                    <label class="form-label fw-semibold">Chọn sản phẩm</label>
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="selectAllProducts(true)">Chọn tất cả</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAllProducts(false)">Bỏ chọn tất cả</button>
                    </div>
                    <div class="border rounded p-2" style="max-height:220px;overflow:auto;">
                        @foreach($products as $product)
                            <div class="form-check d-flex align-items-center mb-2">
                                <input class="form-check-input product-checkbox me-2" type="checkbox" name="products[]" id="product_{{ $product->id }}" value="{{ $product->id }}">
                                <label class="form-check-label me-3" for="product_{{ $product->id }}">{{ $product->name }}</label>
                                <input type="number" step="1000" min="0" class="form-control form-control-sm sale-price-input" name="sale_prices[{{ $product->id }}]" placeholder="Giá flash sale" style="width:130px; display:none;">
                            </div>
                        @endforeach
                    </div>
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
                // Hiển thị danh sách sản phẩm đã chọn
                function updateSelectedProductsList() {
                    let checked = Array.from(document.querySelectorAll('.product-checkbox:checked'));
                    let ul = document.getElementById('selected-products-ul');
                    let listDiv = document.getElementById('selected-products-list');
                    ul.innerHTML = '';
                    if (checked.length > 0) {
                        checked.forEach(cb => {
                            let name = cb.closest('.form-check').querySelector('label').innerText;
                            let li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.innerText = name;
                            ul.appendChild(li);
                        });
                        listDiv.style.display = 'block';
                    } else {
                        listDiv.style.display = 'none';
                    }
                }
                // Hiển thị sản phẩm thuộc danh mục đã chọn
                function updateCategoryProductsList() {
                    let checked = Array.from(document.querySelectorAll('.category-checkbox:checked'));
                    let ul = document.getElementById('category-products-ul');
                    let listDiv = document.getElementById('category-products-list');
                    ul.innerHTML = '';
                    if (checked.length > 0) {
                        // Lấy id danh mục
                        let categoryIds = checked.map(cb => cb.value);
                        // Gọi ajax lấy sản phẩm theo danh mục
                        fetch('/admin/ajax/products-by-categories?ids=' + categoryIds.join(','))
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.length > 0) {
                                    data.forEach(prod => {
                                        let li = document.createElement('li');
                                        li.className = 'list-group-item';
                                        li.innerText = prod.name;
                                        ul.appendChild(li);
                                    });
                                    listDiv.style.display = 'block';
                                } else {
                                    listDiv.style.display = 'none';
                                }
                            });
                    } else {
                        listDiv.style.display = 'none';
                    }
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
                            updateSelectedProductsList();
                            let saleInput = this.closest('.form-check').querySelector('.sale-price-input');
                            if (saleInput) {
                                saleInput.style.display = (this.checked && (flashTypeSelect && flashTypeSelect.value === 'flash_sale')) ? 'inline-block' : 'none';
                            }
                        });
                    });
                    document.querySelectorAll('.category-checkbox').forEach(function(cb) {
                        cb.addEventListener('change', function() {
                            updateCategoryProductsList();
                        });
                    });
                });
                </script>

                {{-- Ngày bắt đầu / kết thúc --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label fw-semibold">Ngày bắt đầu</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label fw-semibold">Ngày kết thúc</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control">
                    </div>
                </div>

                {{-- Trạng thái --}}
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="1">Kích hoạt</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>

                {{-- Action --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tạo chương trình
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script hiển thị select theo type --}}
<script>
    document.getElementById('type').addEventListener('change', function() {
        document.getElementById('category-select').style.display = this.value === 'category' ? 'block' : 'none';
        document.getElementById('product-select').style.display = this.value === 'product' ? 'block' : 'none';
    });
</script>
@endsection
