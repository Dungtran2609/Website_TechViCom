@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Danh sách sản phẩm có bình luận</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover table-centered">
                <thead class="bg-light-subtle">
                    <tr>
                        <th>STT</th>
                        <th>Sản phẩm</th>
                        <th>Số lượng bình luận</th>
                        <th width="120px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="text-wrap" style="max-width: 300px;">
                                    <span class="text-dark fw-medium">{{ Str::limit($product->name, 80) }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $product->comments_count }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.comments.index', ['product_id' => $product->id]) }}" class="btn btn-light btn-sm" title="Xem bình luận">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-comments fa-2x mb-3"></i>
                                    <p>Không có sản phẩm nào có bình luận</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <!-- Pagination placeholder -->
    </div>
</div>
@endsection 