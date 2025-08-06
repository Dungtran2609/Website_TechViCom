@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Danh sách sản phẩm có bình luận</h2>
    <table class="table table-bordered align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng bình luận</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="fw-bold bg-light">{{ $product->name }}</td>
                    <td class="fw-bold bg-light text-center">{{ $product->comments_count }}</td>
                    <td class="bg-light">
                        <a href="{{ route('admin.products.comments.index', ['product_id' => $product->id]) }}" class="btn btn-outline-warning btn-sm fw-bold">
                            <i class="bi bi-search"></i> Xem bình luận
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Không có sản phẩm nào có bình luận.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 