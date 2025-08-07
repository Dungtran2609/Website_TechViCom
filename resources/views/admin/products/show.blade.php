@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-0">Chi tiết sản phẩm</h1>
        <p class="text-muted mb-0">{{ $product->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Chỉnh sửa sản phẩm
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Thông tin chung</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="mb-0"><strong class="d-block text-muted">ID:</strong> {{ $product->id }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-0"><strong class="d-block text-muted">Thương hiệu:</strong> {{ $product->brand->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-0"><strong class="d-block text-muted">Danh mục:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-0"><strong class="d-block text-muted">Trạng thái:</strong>
                            @if ($product->status === 'active') <span class="badge bg-success-subtle text-success">Hiển thị</span> @else <span class="badge bg-danger-subtle text-danger">Ẩn</span> @endif
                        </p>
                    </div>
                     <div class="col-md-6 mb-3">
                        <p class="mb-0"><strong class="d-block text-muted">Sản phẩm nổi bật:</strong>
                            @if ($product->is_featured) <span class="badge bg-info-subtle text-info">Có</span> @else <span class="badge bg-secondary-subtle text-secondary">Không</span> @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-0"><strong class="d-block text-muted">Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-0"><strong class="d-block text-muted">Cập nhật:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Dữ liệu & Biến thể</h5></div>
            <div class="card-body">
                @if($product->type === 'simple' && $product->variants->isNotEmpty())
                    @php $variant = $product->variants->first(); @endphp
                    <div class="row">
                        <div class="col-md-6"><p class="mb-2"><strong class="d-block text-muted">SKU:</strong> {{ $variant->sku ?? 'N/A' }}</p></div>
                        <div class="col-md-6"><p class="mb-2"><strong class="d-block text-muted">Tồn kho:</strong> {{ $variant->stock }}</p></div>
                        <div class="col-md-6"><p class="mb-2"><strong class="d-block text-muted">Giá bán:</strong> <span class="text-danger fw-bold">{{ number_format($variant->price) }} đ</span></p></div>
                        <div class="col-md-6"><p class="mb-2"><strong class="d-block text-muted">Giá khuyến mãi:</strong> {{ $variant->sale_price ? number_format($variant->sale_price) . ' đ' : 'Không có' }}</p></div>
                        <div class="col-md-12"><p class="mb-2"><strong class="d-block text-muted">Ngưỡng tồn kho thấp:</strong> {{ $variant->low_stock_amount ?? 0 }}</p></div>
                    </div>
                    @if($variant->attributeValues && $variant->attributeValues->count())
                        <hr>
                        <h6 class="mb-2">Thuộc tính sản phẩm</h6>
                        <ul class="mb-3 ps-3">
                            @foreach($variant->attributeValues as $attValue)
                                <li><strong>{{ $attValue->attribute->name }}:</strong> {{ $attValue->value }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <hr>
                    <h6 class="mb-3">Thông tin vận chuyển</h6>
                    <div class="row">
                        <div class="col-md-3"><p class="mb-1"><strong class="d-block text-muted">Cân nặng:</strong> {{ $variant->weight ?? 'N/A' }} kg</p></div>
                        <div class="col-md-3"><p class="mb-1"><strong class="d-block text-muted">Dài:</strong> {{ $variant->length ?? 'N/A' }} cm</p></div>
                        <div class="col-md-3"><p class="mb-1"><strong class="d-block text-muted">Rộng:</strong> {{ $variant->width ?? 'N/A' }} cm</p></div>
                        <div class="col-md-3"><p class="mb-1"><strong class="d-block text-muted">Cao:</strong> {{ $variant->height ?? 'N/A' }} cm</p></div>
                    </div>
                @elseif($product->type === 'variable')
                    <div class="accordion" id="variantsAccordion">
                        @forelse($product->variants as $index => $variant)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                        <div class="d-flex w-100 justify-content-between align-items-center pe-3">
                                            <span>{{ $variant->attributeValues->pluck('value')->join(' / ') ?: 'Biến thể' }}</span>
                                            <span class="badge bg-primary-subtle text-primary">Giá: {{ number_format($variant->price) }} đ</span>
                                            <span class="badge bg-info-subtle text-info">Kho: {{ $variant->stock }}</span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-3 text-center">
                                                @if ($variant->image && Storage::disk('public')->exists($variant->image))
                                                    <img src="{{ asset('storage/' . $variant->image) }}" alt="Ảnh biến thể" class="img-fluid rounded border mb-2">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="height: 120px;">
                                                        <small class="text-muted">Không có ảnh</small>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-sm-6 mb-2"><strong class="d-block text-muted">SKU:</strong> {{ $variant->sku ?? 'N/A' }}</div>
                                                    <div class="col-sm-6 mb-2"><strong class="d-block text-muted">Trạng thái:</strong> @if($variant->is_active)<span class="badge bg-success">Hoạt động</span>@else<span class="badge bg-secondary">Ngừng</span>@endif</div>
                                                    <div class="col-sm-6 mb-2"><strong class="d-block text-muted">Giá bán:</strong> <span class="text-danger fw-bold">{{ number_format($variant->price) }} đ</span></div>
                                                    <div class="col-sm-6 mb-2"><strong class="d-block text-muted">Giá KM:</strong> {{ $variant->sale_price ? number_format($variant->sale_price) . ' đ' : 'N/A' }}</div>
                                                    <div class="col-sm-12 mb-2"><strong class="d-block text-muted">Ngưỡng tồn kho thấp:</strong> {{ $variant->low_stock_amount ?? 0 }}</div>
                                                </div>
                                                <hr class="my-2">
                                                <p class="mb-1"><strong>Vận chuyển:</strong> <small class="text-muted">{{ $variant->weight ?? 'N/A' }}kg, {{ $variant->length ?? 'N/A' }}x{{ $variant->width ?? 'N/A' }}x{{ $variant->height ?? 'N/A' }}cm</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Sản phẩm này chưa có biến thể nào.</p>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Mô tả sản phẩm</h5></div>
            <div class="card-body">
                @if($product->short_description)
                <div class="mb-4">
                    <h6 class="fw-medium">Mô tả ngắn</h6>
                    <p class="text-muted">{{ $product->short_description }}</p>
                </div>
                @endif
                @if($product->long_description)
                <div>
                    <h6 class="fw-medium">Mô tả chi tiết</h6>
                    <div class="text-muted">{!! nl2br(e($product->long_description)) !!}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Ảnh đại diện</h5></div>
            <div class="card-body text-center">
                @if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail))
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="height: 200px;">
                        <p class="text-muted mb-0">Không có ảnh đại diện</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Thư viện ảnh</h5></div>
            <div class="card-body">
                <div class="row g-2">
                    @forelse($product->allImages as $image)
                        <div class="col-4">
                            <a href="{{ asset('storage/' . $image->image_path) }}" data-lightbox="product-gallery">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded border">
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted mb-0">Không có ảnh trong thư viện.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection