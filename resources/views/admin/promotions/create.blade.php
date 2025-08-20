@extends('admin.layouts.app')
@section('title', 'Tạo chương trình khuyến mãi')

@section('content')
<div class="container py-4">
    <h1 class="h4 fw-bold mb-4 text-dark">➕ Tạo chương trình khuyến mãi</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.promotions.store') }}" method="POST">
                @csrf

                {{-- Chọn mã giảm giá (coupon) --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Chọn mã giảm giá áp dụng cho chương trình</label>
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="selectAllCoupons(true)">Chọn tất cả</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAllCoupons(false)">Bỏ chọn tất cả</button>
                    </div>
                    <div class="border rounded p-2" style="max-height:220px;overflow:auto;">
                        @foreach($coupons as $coupon)
                            <div class="form-check">
                                <input class="form-check-input coupon-checkbox" type="checkbox" name="coupons[]" id="coupon_{{ $coupon->id }}" value="{{ $coupon->id }}">
                                <label class="form-check-label" for="coupon_{{ $coupon->id }}">
                                    {{ $coupon->code }} ({{ $coupon->discount_type }}: {{ $coupon->value }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <script>
                function selectAllCoupons(checked) {
                    document.querySelectorAll('.coupon-checkbox').forEach(cb => cb.checked = checked);
                }
                </script>

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
                            <div class="form-check">
                                <input class="form-check-input product-checkbox" type="checkbox" name="products[]" id="product_{{ $product->id }}" value="{{ $product->id }}">
                                <label class="form-check-label" for="product_{{ $product->id }}">{{ $product->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <script>
                function selectAllProducts(checked) {
                    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = checked);
                }
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
