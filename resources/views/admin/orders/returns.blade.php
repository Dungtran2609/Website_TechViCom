@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý Hủy/Đổi trả</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Quản lý đơn hàng
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<form method="GET" action="{{ route('admin.orders.returns') }}" class="mb-4">
    <div class="row g-3">
        <div class="col-md-2">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo mã đơn, khách hàng, lý do...">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã phê duyệt</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">Tất cả loại</option>
                <option value="cancel" {{ request('type') == 'cancel' ? 'selected' : '' }}>Hủy</option>
                <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Đổi/Trả</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" placeholder="Từ ngày">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" placeholder="Đến ngày">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    @if(request('keyword') || request('status') || request('type') || request('order_status') || request('date_from') || request('date_to'))
        <div class="mt-2">
            <a href="{{ route('admin.orders.returns') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
    @endif
</form>

@if (!isset($returns))
    <div class="alert alert-danger">Biến $returns không được truyền từ controller.</div>
@else
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover table-centered">
                    <thead class="bg-light-subtle">
                        <tr>
                            <th>STT</th>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Loại</th>
                            <th>Lý do</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái yêu cầu</th>
                            <th>Trạng thái đơn</th>
                            <th>Ngày yêu cầu</th>
                            <th width="120px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $return)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $return['order_id'] }}</span>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0 text-dark fw-medium">{{ $return['user_name'] }}</h6>
                                    </div>
                                </td>
                                <td>
                                    @if($return['type'] === 'cancel')
                                        <span class="badge bg-danger">Hủy</span>
                                    @else
                                        <span class="badge bg-warning">Đổi/Trả</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 200px;">
                                        <span class="text-dark">{{ Str::limit($return['reason'], 40) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark fw-medium">{{ number_format($return['order_total'], 0) }} VND</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$return['status']] ?? 'light' }}">
                                        {{ $return['status_vietnamese'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $return['order_status_vietnamese'] }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $return['requested_at'] }}</span>
                                </td>
                                <td>
                                    @if($return['status'] === 'pending')
                                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#processModal{{ $return['id'] }}" title="Xử lý yêu cầu">
                                            <iconify-icon icon="solar:settings-linear" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    @else
                                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $return['id'] }}" title="Xem chi tiết">
                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-exchange-alt fa-2x mb-3"></i>
                                        <p>Không có yêu cầu hủy/đổi trả nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $pagination->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Modals cho từng yêu cầu -->
    @foreach($returns as $return)
        <!-- Modal xử lý yêu cầu -->
        @if($return['status'] === 'pending')
            <div class="modal fade" id="processModal{{ $return['id'] }}" tabindex="-1" aria-labelledby="processModalLabel{{ $return['id'] }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="processModalLabel{{ $return['id'] }}">Xử lý yêu cầu #{{ $return['id'] }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.orders.process-return', $return['id']) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Mã đơn hàng:</label>
                                        <p class="text-dark">{{ $return['order_id'] }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Khách hàng:</label>
                                        <p class="text-dark">{{ $return['user_name'] }}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Loại yêu cầu:</label>
                                        <p class="text-dark">{{ $return['type'] === 'cancel' ? 'Hủy' : 'Đổi/Trả' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Tổng tiền:</label>
                                        <p class="text-dark fw-medium">{{ number_format($return['order_total'], 0) }} VND</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Lý do:</label>
                                    <p class="text-dark">{{ $return['reason'] }}</p>
                                </div>
                                
                                <!-- Minh chứng của client -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="fas fa-images me-2"></i>
                                        Minh chứng của khách hàng
                                    </h6>
                                    
                                    <!-- Sản phẩm được chọn và ảnh chứng minh -->
                                    @if(isset($return['selected_products']) && $return['selected_products'] && is_array($return['selected_products']))
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Sản phẩm được chọn:</label>
                                            <div class="row">
                                                @foreach($return['selected_products'] as $productId)
                                                    @php
                                                        $product = \App\Models\ProductVariant::find($productId);
                                                        $productName = $product ? $product->product->name : 'Sản phẩm #' . $productId;
                                                    @endphp
                                                    <div class="col-md-6 mb-2">
                                                        <div class="card border-primary">
                                                            <div class="card-body p-2">
                                                                <small class="text-primary fw-bold">{{ $productName }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Ảnh chứng minh của client -->
                                    @if(isset($return['images']) && $return['images'] && is_array($return['images']))
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Ảnh chứng minh từ khách hàng:</label>
                                            <div class="row">
                                                @foreach($return['images'] as $productId => $imagePaths)
                                                    @if(is_array($imagePaths) && count($imagePaths) > 0)
                                                        @php
                                                            $product = \App\Models\ProductVariant::find($productId);
                                                            $productName = $product ? $product->product->name : 'Sản phẩm #' . $productId;
                                                        @endphp
                                                        <div class="col-12 mb-3">
                                                            <div class="card border-info">
                                                                <div class="card-header bg-info text-white py-2">
                                                                    <small class="fw-bold">{{ $productName }}</small>
                                                                </div>
                                                                <div class="card-body p-2">
                                                                    <div class="row">
                                                                        @foreach($imagePaths as $imagePath)
                                                                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                                                                <div class="position-relative">
                                                                                    <img src="{{ asset('storage/' . $imagePath) }}" 
                                                                                         alt="Ảnh chứng minh" 
                                                                                         class="img-fluid rounded border" 
                                                                                         style="width: 100%; height: 100px; object-fit: cover; cursor: pointer;"
                                                                                         onclick="openImageModal('{{ asset('storage/' . $imagePath) }}')"
                                                                                         title="Click để xem ảnh to">
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Video chứng minh của client -->
                                    @if(isset($return['video']) && $return['video'])
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Video chứng minh từ khách hàng:</label>
                                            <div class="card border-success">
                                                <div class="card-body p-2">
                                                    <video controls class="w-100" style="max-height: 300px;">
                                                        <source src="{{ asset('storage/' . $return['video']) }}" type="video/mp4">
                                                        <source src="{{ asset('storage/' . $return['video']) }}" type="video/avi">
                                                        <source src="{{ asset('storage/' . $return['video']) }}" type="video/mov">
                                                        Trình duyệt của bạn không hỗ trợ video.
                                                    </video>
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-download me-1"></i>
                                                            <a href="{{ asset('storage/' . $return['video']) }}" target="_blank" class="text-decoration-none">
                                                                Tải xuống video
                                                            </a>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Ghi chú của client -->
                                    @if(isset($return['client_note']) && $return['client_note'])
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Ghi chú của khách hàng:</label>
                                            <div class="alert alert-info">
                                                <i class="fas fa-comment me-2"></i>
                                                {{ $return['client_note'] }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Cảnh báo admin phải xem minh chứng -->
                                <div class="alert alert-warning border-warning">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle me-3 mt-1 text-warning fs-4"></i>
                                        <div>
                                            <strong class="text-warning">Lưu ý quan trọng:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Vui lòng xem xét kỹ lưỡng tất cả minh chứng của khách hàng trước khi xử lý yêu cầu</li>
                                                <li>Ảnh và video chứng minh là bằng chứng quan trọng để đánh giá tính hợp lệ của yêu cầu</li>
                                                <li>Chỉ chấp nhận yêu cầu khi có đầy đủ minh chứng rõ ràng và hợp lý</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Phương thức thanh toán:</label>
                                        <p class="text-dark">{{ $return['payment_method_vietnamese'] }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Trạng thái đơn hàng:</label>
                                        <p class="text-dark">{{ $return['order_status_vietnamese'] }}</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="admin_note{{ $return['id'] }}" class="form-label fw-medium">Ghi chú của Admin <span class="text-danger">*</span>:</label>
                                    <textarea name="admin_note" id="admin_note{{ $return['id'] }}" class="form-control @error('admin_note') is-invalid @enderror" rows="4" placeholder="Nhập ghi chú khi xử lý yêu cầu..." >{{ old('admin_note') }}</textarea>
                                    <div class="invalid-feedback" id="admin_note_error{{ $return['id'] }}" style="display: none;">
                                        Vui lòng nhập ghi chú khi xử lý yêu cầu.
                                    </div>
                                    @error('admin_note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Xác nhận đã xem minh chứng -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirmProofViewed{{ $return['id'] }}" name="confirm_proof_viewed" required>
                                        <label class="form-check-label fw-medium" for="confirmProofViewed{{ $return['id'] }}">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <strong>Tôi đã xem xét kỹ lưỡng tất cả minh chứng của khách hàng và hiểu rõ vấn đề</strong>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="invalid-feedback" id="confirm_proof_viewed_error{{ $return['id'] }}" style="display: none;">
                                            Vui lòng xác nhận đã xem xét minh chứng trước khi xử lý yêu cầu.
                                        </div>
                                        @error('confirm_proof_viewed')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Upload ảnh chứng minh khi chấp nhận trả hàng -->
                                @if($return['type'] === 'return')
                                <div class="mb-3" id="adminProofSection{{ $return['id'] }}" style="display: block;">
                                    <label class="form-label fw-medium">Ảnh chứng minh hoàn tiền <span class="text-danger">*</span>:</label>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Bắt buộc:</strong> Vui lòng upload ảnh chứng minh đã hoàn tiền cho khách hàng.
                                    </div>
                                    <input type="file" name="admin_proof_images[]" class="form-control @error('admin_proof_images') is-invalid @enderror" accept="image/*" multiple>
                                    <div class="form-text">Có thể chọn nhiều ảnh (JPG, PNG, GIF)</div>
                                    @error('admin_proof_images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn từ chối yêu cầu này?')">
                                    <i class="fas fa-times me-1"></i> Từ chối
                                </button>
                                <button type="submit" name="action" value="approve" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Chấp nhận
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal xem chi tiết -->
        <div class="modal fade" id="detailModal{{ $return['id'] }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $return['id'] }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel{{ $return['id'] }}">Chi tiết yêu cầu #{{ $return['id'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Mã đơn hàng:</label>
                                <p class="text-dark">{{ $return['order_id'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Khách hàng:</label>
                                <p class="text-dark">{{ $return['user_name'] }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Loại yêu cầu:</label>
                                <p class="text-dark">{{ $return['type'] === 'cancel' ? 'Hủy' : 'Đổi/Trả' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Tổng tiền:</label>
                                <p class="text-dark fw-medium">{{ number_format($return['order_total'], 0) }} VND</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Lý do:</label>
                            <p class="text-dark">{{ $return['reason'] }}</p>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Phương thức thanh toán:</label>
                                <p class="text-dark">{{ $return['payment_method_vietnamese'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Trạng thái đơn hàng:</label>
                                <p class="text-dark">{{ $return['order_status_vietnamese'] }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Ngày yêu cầu:</label>
                                <p class="text-dark">{{ $return['requested_at'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Ngày xử lý:</label>
                                <p class="text-dark">{{ $return['processed_at'] ?? 'Chưa xử lý' }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Ghi chú của Admin:</label>
                            <p class="text-dark">{{ $return['admin_note'] ?? 'Chưa có' }}</p>
                        </div>
                        
                        <!-- Hiển thị sản phẩm được chọn và ảnh chứng minh -->
                        @if($return['type'] === 'return' && isset($return['selected_products']) && !empty($return['selected_products']) && is_array($return['selected_products']))
                        <div class="mb-3">
                            <label class="form-label fw-medium">Sản phẩm được chọn đổi/trả:</label>
                            <div class="row">
                                @foreach($return['selected_products'] as $productId)
                                @php
                                    $orderItem = \App\Models\OrderItem::find($productId);
                                @endphp
                                @if($orderItem)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">{{ $orderItem->name_product }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-2"><strong>Số lượng:</strong> {{ $orderItem->quantity }} x {{ number_format($orderItem->price, 0, ',', '.') }}₫</p>
                                            @if($orderItem->productVariant && $orderItem->productVariant->attributeValues->count() > 0)
                                            <p class="mb-2"><strong>Thuộc tính:</strong>
                                                @foreach($orderItem->productVariant->attributeValues as $attrValue)
                                                    {{ $attrValue->attribute->name }}: {{ $attrValue->value }}@if (!$loop->last), @endif
                                                @endforeach
                                            </p>
                                            @endif
                                            
                                            <!-- Hiển thị ảnh chứng minh cho sản phẩm này -->
                                            @if(isset($return['images'][$productId]) && !empty($return['images'][$productId]) && is_array($return['images'][$productId]))
                                            <div class="mt-3">
                                                <label class="form-label fw-medium">Ảnh chứng minh:</label>
                                                <div class="row">
                                                    @foreach($return['images'][$productId] as $image)
                                                    <div class="col-md-4 mb-2">
                                                        <img src="{{ asset('storage/' . $image) }}" 
                                                             alt="Chứng minh" 
                                                             class="img-fluid rounded border" 
                                                             style="max-height: 150px; width: 100%; object-fit: cover; cursor: pointer;"
                                                             onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Ảnh chứng minh sản phẩm')">
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if($return['type'] === 'return' && isset($return['video']) && !empty($return['video']))
                        <div class="mb-3">
                            <label class="form-label fw-medium">Video chứng minh:</label>
                            <video controls class="w-100" style="max-height: 300px;">
                                <source src="{{ asset('storage/' . $return['video']) }}" type="video/mp4">
                                Trình duyệt không hỗ trợ video.
                            </video>
                        </div>
                        @endif
                        
                        <!-- Hiển thị ảnh chứng minh của admin -->
                        @if($return['type'] === 'return' && isset($return['admin_proof_images']) && !empty($return['admin_proof_images']) && is_array($return['admin_proof_images']))
                        <div class="mb-3">
                            <label class="form-label fw-medium">Ảnh chứng minh hoàn tiền của Admin:</label>
                            <div class="row">
                                @foreach($return['admin_proof_images'] as $image)
                                <div class="col-md-4 mb-2">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="Chứng minh hoàn tiền" 
                                         class="img-fluid rounded border" 
                                         style="max-height: 200px; width: 100%; object-fit: cover; cursor: pointer;"
                                         onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Ảnh chứng minh hoàn tiền')">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả các textarea admin_note
    const adminNotes = document.querySelectorAll('textarea[name="admin_note"]');
    
    adminNotes.forEach(function(textarea) {
        const returnId = textarea.id.replace('admin_note', '');
        const errorDiv = document.getElementById('admin_note_error' + returnId);
        const submitButtons = textarea.closest('form').querySelectorAll('button[type="submit"]');
        
        // Validate khi người dùng nhập
        textarea.addEventListener('input', function() {
            validateAdminNote(textarea, errorDiv, submitButtons);
        });
        
        // Validate khi người dùng blur (rời khỏi field)
        textarea.addEventListener('blur', function() {
            validateAdminNote(textarea, errorDiv, submitButtons);
        });
        
        // Validate khi submit form
        textarea.closest('form').addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            if (!validateAdminNote(textarea, errorDiv, submitButtons)) {
                console.log('Form validation failed, preventing submit');
                e.preventDefault();
                return false;
            }
            console.log('Form validation passed, allowing submit');
        });
    });
    
    function validateAdminNote(textarea, errorDiv, submitButtons) {
        const value = textarea.value.trim();
        const isValid = value.length > 0;
        
        if (isValid) {
            // Ẩn lỗi
            textarea.classList.remove('is-invalid');
            errorDiv.style.display = 'none';
            
            // Enable các nút submit
            submitButtons.forEach(btn => {
                btn.disabled = false;
            });
        } else {
            // Hiển thị lỗi
            textarea.classList.add('is-invalid');
            errorDiv.style.display = 'block';
            
            // Disable các nút submit
            submitButtons.forEach(btn => {
                btn.disabled = true;
            });
        }
        
        return isValid;
    }
    
    // Hàm xử lý khi chọn action
    window.setAction = function(action, returnId) {
        console.log('setAction called:', action, returnId);
        
        const actionInput = document.getElementById('action' + returnId);
        const adminProofSection = document.getElementById('adminProofSection' + returnId);
        
        console.log('actionInput:', actionInput);
        console.log('adminProofSection:', adminProofSection);
        
        actionInput.value = action;
        
        if (action === 'approve' && adminProofSection) {
            console.log('Showing admin proof section');
            adminProofSection.style.display = 'block';
        } else if (adminProofSection) {
            console.log('Hiding admin proof section');
            adminProofSection.style.display = 'none';
        }
        
        // Submit form
        const form = actionInput.closest('form');
        const submitBtn = document.getElementById('submitBtn' + returnId);
        console.log('form:', form);
        console.log('submitBtn:', submitBtn);
        
        if (validateForm(returnId)) {
            console.log('Form validation passed, submitting...');
            // Click button submit thực sự
            if (submitBtn) {
                submitBtn.click();
            } else {
                // Fallback: submit form trực tiếp
                try {
                    form.submit();
                } catch (e) {
                    console.error('Error submitting form:', e);
                }
            }
        } else {
            console.log('Form validation failed');
        }
    }
    
    // Hàm validate form
    window.validateForm = function(returnId) {
        console.log('validateForm called for returnId:', returnId);
        
        const textarea = document.getElementById('admin_note' + returnId);
        const errorDiv = document.getElementById('admin_note_error' + returnId);
        const actionInput = document.getElementById('action' + returnId);
        const adminProofSection = document.getElementById('adminProofSection' + returnId);
        
        console.log('textarea:', textarea);
        console.log('actionInput:', actionInput);
        console.log('adminProofSection:', adminProofSection);
        
        const isValid = textarea.value.trim().length > 0;
        console.log('textarea isValid:', isValid);

        if (!isValid) {
            errorDiv.style.display = 'block';
            textarea.classList.add('is-invalid');
            return false;
        } else {
            errorDiv.style.display = 'none';
            textarea.classList.remove('is-invalid');
        }
        
        // Kiểm tra upload ảnh khi chấp nhận trả hàng
        if (actionInput.value === 'approve' && adminProofSection) {
            console.log('Checking admin proof images...');
            const fileInput = adminProofSection.querySelector('input[name="admin_proof_images[]"]');
            console.log('fileInput:', fileInput);
            console.log('fileInput files:', fileInput ? fileInput.files : 'null');
            console.log('fileInput files length:', fileInput ? fileInput.files.length : 'null');
            
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                alert('Vui lòng upload ảnh chứng minh đã hoàn tiền!');
                if (fileInput) fileInput.focus();
                return false;
            }
        }

        console.log('Validation passed');
        return true;
    }
    
    // Hàm validate và submit đơn giản
    window.validateAndSubmit = function(returnId) {
        console.log('validateAndSubmit called for returnId:', returnId);
        
        // Check if admin note is filled
        const textarea = document.getElementById('admin_note' + returnId);
        if (!textarea.value.trim()) {
            alert('Vui lòng nhập ghi chú!');
            textarea.focus();
            return false;
        }
        
        // Check if admin has confirmed viewing client proof
        const confirmProofCheckbox = document.getElementById('confirmProofViewed' + returnId);
        if (!confirmProofCheckbox || !confirmProofCheckbox.checked) {
            alert('Vui lòng xác nhận đã xem xét minh chứng của khách hàng trước khi xử lý yêu cầu!');
            if (confirmProofCheckbox) confirmProofCheckbox.focus();
            return false;
        }
        
        // Check if admin proof images are uploaded
        const adminProofSection = document.getElementById('adminProofSection' + returnId);
        if (adminProofSection) {
            const fileInput = adminProofSection.querySelector('input[name="admin_proof_images[]"]');
            console.log('fileInput:', fileInput);
            console.log('fileInput files:', fileInput ? fileInput.files : 'null');
            
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                alert('Vui lòng upload ảnh chứng minh đã hoàn tiền!');
                if (fileInput) fileInput.focus();
                return false;
            }
        }
        
        // Confirm action
        if (!confirm('Bạn có chắc chắn muốn chấp nhận yêu cầu này?')) {
            return false;
        }
        
        console.log('Validation passed, form will submit');
        return true;
    }
    
    // Hàm mở modal xem ảnh to
    window.openImageModal = function(imageSrc, title) {
        // Tạo modal nếu chưa có
        var existingModal = document.getElementById('imageModal');
        if (!existingModal) {
            var modalHTML = `
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="modalImage" src="" alt="Ảnh chứng minh" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
        
        // Cập nhật ảnh và hiển thị modal
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModalLabel').textContent = title;
        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
});
</script>
@endsection