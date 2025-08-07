@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <h1 class="mb-4">Chi tiết liên hệ</h1>

                <div class="card shadow">
                    <div class="card-body">
                        {{-- Thông tin người liên hệ --}}
                        <h4 class="mb-3">🧑‍💼 Thông tin người liên hệ</h4>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Họ tên:</strong> {{ $contact->name }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Email:</strong> {{ $contact->email ?? 'Không có' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Số điện thoại:</strong> {{ $contact->phone ?? 'Không có' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Gửi lúc:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <hr>

                        {{-- Nội dung liên hệ --}}
                        <h4 class="mb-3">📝 Nội dung liên hệ</h4>
                        <p><strong>Tiêu đề:</strong> {{ $contact->subject }}</p>
                        <div class="border p-3 bg-light rounded mb-3">
                            {{ $contact->message }}
                        </div>

                        <hr>

                        <h4 class="mb-3">⚙️ Trạng thái & Xử lý</h4>

                        {{-- Trạng thái hiện tại --}}
                        <p>
                            <strong>Trạng thái hiện tại:</strong>
                            @switch($contact->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                @break

                                @case('in_progress')
                                    <span class="badge bg-info text-dark">Đang phản hồi</span>
                                @break

                                @case('responded')
                                    <span class="badge bg-success">Đã phản hồi thành công</span>
                                @break

                                @case('rejected')
                                    <span class="badge bg-danger">Phản hồi thất bại</span>
                                @break

                                @default
                                    <span class="badge bg-secondary">Không xác định</span>
                            @endswitch
                        </p>

                        {{-- Form cập nhật trạng thái --}}
                        <form action="{{ route('admin.contacts.markAsHandled', $contact->id) }}" method="POST"
                            class="d-flex align-items-center gap-2 mb-3">
                            @csrf
                            @method('PATCH')
                            <div class="input-group w-auto">
                                <select name="status" class="form-select" required>
                                    @php
                                        $allowedStatuses = ['pending', 'in_progress', 'responded'];

                                        if (!in_array($contact->status, $allowedStatuses)) {
                                            $allowedStatuses[] = $contact->status;
                                        }
                                        $labels = [
                                            'pending' => 'Chờ xử lý',
                                            'in_progress' => 'Đang phản hồi',
                                            'responded' => 'Đã phản hồi thành công',
                                            'rejected' => 'Phản hồi thất bại',
                                        ];
                                    @endphp

                                    @foreach ($allowedStatuses as $status)
                                        <option value="{{ $status }}"
                                            {{ $contact->status === $status ? 'selected' : '' }}>
                                            {{ $labels[$status] }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" type="submit">Cập nhật</button>
                            </div>
                        </form>

                        {{-- Thông báo lỗi --}}
                        @if ($errors->has('status'))
                            <div class="alert alert-danger mt-2">
                                {{ $errors->first('status') }}
                            </div>
                        @endif


                        {{-- Hiển thị người xử lý nếu có --}}
                        @if ($contact->handledByUser)
                            <p><strong>Người xử lý:</strong> {{ $contact->handledByUser->name }}</p>
                        @endif

                        {{-- Thời điểm phản hồi nếu có --}}
                        @if ($contact->responded_at)
                            <p><strong>Thời điểm phản hồi:</strong> {{ $contact->responded_at->format('d/m/Y H:i') }}</p>
                        @endif


                        {{-- Nút quay lại --}}
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary mt-3 ms-2">
                            ← Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
