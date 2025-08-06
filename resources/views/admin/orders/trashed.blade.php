@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Thùng rác đơn hàng</h1>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
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

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Tổng số lượng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Ngày xóa</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order['id'] }}</td>
                                <td>
                                    @if ($order['image'])
                                        <img src="{{ asset('storage/' . $order['image']) }}" alt="Ảnh sản phẩm" width="40"
                                            class="rounded">
                                    @else
                                        <span>Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $order['user_name'] }}</td>
                                <td>{{ $order['product_names'] }}</td>
                                <td>{{ $order['total_quantity'] }}</td>
                                <td>{{ number_format($order['final_total'], 2) }} VND</td>
                                <td>{{ $order['status_vietnamese'] }}</td>
                                <td>{{ $order['created_at'] }}</td>
                                <td>{{ $order['deleted_at'] }}</td>
                                <td>
                                    <form action="{{ route('admin.order.restore', $order['id']) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Bạn có chắc muốn phục hồi đơn hàng này?')">
                                            <i class="fas fa-undo"></i> Phục hồi
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.order.forceDelete', $order['id']) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn đơn hàng này?')">
                                            <i class="fas fa-trash"></i> Xóa vĩnh viễn
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Không có đơn hàng trong thùng rác.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
