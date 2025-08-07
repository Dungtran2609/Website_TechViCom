@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.products.attributes.values.update', $value->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa giá trị</h1>
                    <p class="mb-0 text-muted">
                        Chỉnh sửa chi tiết cho giá trị <span class="fw-bold text-primary">{{ $value->value }}</span>
                        của thuộc tính <span class="fw-bold text-primary">{{ $attribute->name }}</span>.
                    </p>
                </div>
                <a href="{{ route('admin.products.attributes.values.index', $attribute->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">Thông tin cơ bản</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="value" class="form-label fw-semibold">Tên giá trị <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="value" id="value"
                                    class="form-control @error('value') is-invalid @enderror"
                                    value="{{ old('value', $value->value) }}" placeholder="Ví dụ: Đỏ, L, 128GB">
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($attribute->type === 'color')
                                <div class="mb-3">
                                    <label for="color_code" class="form-label fw-semibold">Mã màu</label>
                                    <div class="input-group">
                                        <input type="color" name="color_code" id="color_code"
                                            class="form-control form-control-color"
                                            value="{{ old('color_code', $value->color_code ?? '#5E72E4') }}"
                                            style="max-width: 60px;">
                                        <input type="text" class="form-control"
                                            value="{{ old('color_code', $value->color_code ?? '#5E72E4') }}"
                                            onchange="document.getElementById('color_code').value = this.value">
                                    </div>
                                    <div class="form-text">Chọn một màu để đại diện cho giá trị này.</div>
                                    @error('color_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">Hành động</h6>
                        </div>
                        <div class="card-body">
                            <p class="form-text">
                                Nhấn "Lưu thay đổi" để cập nhật thông tin.
                            </p>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i> Lưu thay đổi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const colorPicker = document.getElementById('color_code');
        if (colorPicker) {
            const colorText = colorPicker.closest('.input-group').querySelector('input[type="text"]');
            if (colorText) {
                colorPicker.addEventListener('input', (event) => {
                    colorText.value = event.target.value.toUpperCase();
                });
                colorText.addEventListener('input', (event) => {
                    colorPicker.value = event.target.value;
                });
            }
        }
    </script>
@endpush
