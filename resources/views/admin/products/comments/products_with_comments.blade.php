@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-primary fw-bold">Danh sách sản phẩm có bình luận</h2>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow rounded" style="background: #fff;">
            <thead style="background: #e9ecef;">
                <tr>
                    <th style="color: #007bff; font-weight: 600;">Sản phẩm</th>
                    <th style="color: #007bff; font-weight: 600;">Số lượng bình luận</th>
                    <th style="color: #007bff; font-weight: 600;">Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr style="transition: background 0.2s;">
                        <td class="fw-bold" style="background: #f8f9fa; color: #212529; border-radius: 8px 0 0 8px;">{{ $product->name }}</td>
                        <td class="fw-bold text-center" style="background: #f8f9fa; color: #212529;">{{ $product->comments_count }}</td>
                        <td style="background: #f8f9fa; border-radius: 0 8px 8px 0;">
                            <a href="{{ route('admin.products.comments.index', ['product_id' => $product->id]) }}" class="btn btn-primary btn-sm fw-bold shadow-sm px-3" style="border-radius: 20px;">
                                <i class="bi bi-chat-dots"></i> Xem bình luận
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-muted">Không có sản phẩm nào có bình luận.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('styles')
<style>
    .table tbody tr:hover {
        background: #e3f0ff !important;
        transition: background 0.2s;
    }
    @media (max-width: 576px) {
        .table th, .table td {
            font-size: 0.95em;
            padding: 0.5rem;
        }
        .btn {
            font-size: 0.95em;
        }
    }
</style>
@endpush
@endsection 