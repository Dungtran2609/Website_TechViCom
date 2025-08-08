@extends('admin.layouts.app')

@section('content')
    <div class="container">
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

        @if (!isset($returns))
            <div class="alert alert-danger">Biến $returns không được truyền từ controller.</div>
        @else
            <div class="accordion" id="returnsAccordion">
                @forelse ($returns as $return)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingReturn{{ $return['id'] }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseReturn{{ $return['id'] }}" aria-expanded="false"
                                aria-controls="collapseReturn{{ $return['id'] }}">
                                <div class="row w-100 align-items-center">
                                    <div class="col-2">Mã đơn: {{ $return['order_id'] }}</div>
                                    <div class="col-2">Khách hàng: {{ $return['user_name'] }}</div>
                                    <div class="col-2">Loại: {{ $return['type'] === 'cancel' ? 'Hủy' : 'Đổi/Trả' }}</div>
                                    <div class="col-2">Lý do: {{ $return['reason'] }}</div>
                                    <div class="col-2">Tổng tiền: {{ number_format($return['order_total'], 2) }} VND</div>
                                    <div class="col-2">Trạng thái đơn: {{ $return['order_status_vietnamese'] }}</div>
                                    <div class="col-2">Trạng thái yêu cầu: {{ $return['status_vietnamese'] }}</div>
                                    <div class="col-2">Ngày yêu cầu: {{ $return['requested_at'] }}</div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseReturn{{ $return['id'] }}" class="accordion-collapse collapse"
                            aria-labelledby="headingReturn{{ $return['id'] }}" data-bs-parent="#returnsAccordion">
                            <div class="accordion-body">
                                <p><strong>Phương thức thanh toán:</strong> {{ $return['payment_method_vietnamese'] }}</p>
                                <p><strong>Trạng thái đơn hàng:</strong> {{ $return['order_status_vietnamese'] }}</p>
                                <p><strong>Ghi chú admin:</strong> {{ $return['admin_note'] ?? 'Chưa có' }}</p>
                                <p><strong>Ngày xử lý:</strong> {{ $return['processed_at'] ?? 'Chưa xử lý' }}</p>





















                                @if($return['status'] === 'pending')
                                    <form action="{{ route('admin.order.process-return', $return['id']) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Ghi chú của Admin:</label>
                                            <textarea name="admin_note" class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">
                                                Chấp nhận
                                            </button>
                                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">
                                                Từ chối
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">Không có yêu cầu hủy/đổi trả nào.</div>
                @endforelse
            </div>

            <!-- Phân trang -->
            {{ $pagination->links('pagination::bootstrap-5') }}
        @endif
    </div>
@endsection
