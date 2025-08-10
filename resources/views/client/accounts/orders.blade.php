@extends('client.layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container py-4">
        <h1 class="fw-bold text-primary mb-4">
            <i class="fas fa-shopping-bag me-2"></i>Đơn hàng của bạn
        </h1>

        @if($orders->isEmpty())
            <div class="alert alert-info rounded shadow-sm">
                <i class="fas fa-info-circle me-2"></i>Bạn chưa có đơn hàng nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle shadow-sm rounded">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Mã đơn</th>
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Ngày đặt</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="fw-bold text-primary">
                                    <span class="text-dark">
                                        {{ $order->random_code ?? $order->code ?? ('DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)) }}
                                    </span>
                                </td>
                                <td>
                                    @foreach($order->orderItems as $item)
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-2" style="width: 50px; height: 50px;">
                                                @if($item->variant_id && $item->variant && $item->variant->image)
                                                    <img src="{{ asset($item->variant->image) }}" 
                                                        alt="{{ $item->name_product }}"
                                                        class="img-fluid rounded"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @elseif($item->image_product)
                                                    <img src="{{ asset('storage/' . $item->image_product) }}"
                                                        alt="{{ $item->name_product }}"
                                                        class="img-fluid rounded"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                        style="width: 100%; height: 100%;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-truncate" style="max-width: 200px;">
                                                    {{ $item->name_product }}
                                                </div>
                                                <div class="small text-muted">
                                                    SL: {{ $item->quantity }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @php
                                        $statusColor = [
                                            'pending' => 'secondary',
                                            'processing' => 'info',
                                            'shipped' => 'warning',
                                            'delivered' => 'success',
                                            'received' => 'success',
                                            'cancelled' => 'danger',
                                            'returned' => 'dark',
                                        ];
                                        $statusKey = $order->status ?? 'pending';
                                        $return = $order->returns()->latest()->first();
                                    @endphp

                                    <span class="badge bg-{{ $statusColor[$statusKey] ?? 'secondary' }} px-3 py-2">
                                        <i class="fas fa-circle me-1"></i>
                                        @if($order->status === 'delivered')
                                            Đã giao
                                        @elseif($order->status === 'received')
                                            Đã nhận hàng
                                        @else
                                            {{ $order->status_vietnamese ?? $order->status }}
                                        @endif
                                    </span>

                                    @if($return && in_array($order->status, ['returned', 'cancelled']))
                                        <div class="mt-1 small text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <span>Lý do khách: {{ $return->client_note ?? $return->reason }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold text-danger">
                                    {{ number_format($order->final_total, 0, ',', '.') }} VND
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('accounts.order-detail', $order->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill me-1">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    @if($order->status === 'pending')
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $order->id }}">
                                            <i class="fas fa-times"></i> Hủy
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>

            {{-- Render các modal RIÊNG, tránh nằm trong <tbody> --}}
                @foreach($orders as $order)
                    @if($order->status === 'pending')
                        <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form class="js-cancel-order-form" method="POST" action="{{ route('client.orders.cancel', $order->id) }}">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cancelModalLabel{{ $order->id }}">
                                                Lý do hủy đơn hàng
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="cancelReason{{ $order->id }}" class="form-label">
                                                    Vui lòng nhập lý do hủy đơn hàng:
                                                </label>
                                                <textarea class="form-control" id="cancelReason{{ $order->id }}" name="client_note"
                                                    rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .badge {
            font-size: 1em;
            border-radius: 1rem;
        }

        .btn-outline-primary {
            transition: box-shadow 0.2s;
        }

        .btn-outline-primary:hover {
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Lấy CSRF token từ thẻ meta (đảm bảo thẻ này có trong layout)
            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

            // Bắt submit các form hủy
            var forms = document.querySelectorAll('.js-cancel-order-form');
            forms.forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    var textarea = form.querySelector('textarea[name="client_note"]');
                    var clientNote = textarea ? textarea.value.trim() : '';
                    if (!clientNote) {
                        alert('Vui lòng nhập lý do hủy đơn hàng');
                        if (textarea) textarea.focus();
                        return;
                    }

                    var fd = new FormData();
                    fd.append('client_note', clientNote);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: fd
                    })
                        .then(function (res) {
                            var ct = res.headers.get('content-type') || '';
                            if (ct.indexOf('application/json') !== -1) return res.json();
                            return { success: res.ok };
                        })
                        .then(function (data) {
                            if (data && data.success) {
                                alert(data.message || 'Hủy đơn hàng thành công!');
                                // Đóng modal (Bootstrap 5)
                                var modalEl = form.closest('.modal');
                                if (modalEl && window.bootstrap && bootstrap.Modal) {
                                    var instance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                                    instance.hide();
                                }
                                window.location.reload();
                            } else {
                                alert((data && data.message) || 'Có lỗi xảy ra khi hủy đơn hàng');
                            }
                        })
                        .catch(function (err) {
                            console.error(err);
                            alert('Có lỗi xảy ra khi hủy đơn hàng');
                        });
                });
            });
        });
    </script>
@endpush