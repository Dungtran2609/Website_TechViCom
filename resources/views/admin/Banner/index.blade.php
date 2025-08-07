{{-- resources/views/admin/banner/index.blade.php --}}
@extends('admin.layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .banner-table th,
        .banner-table td {
            vertical-align: middle !important;
            text-align: center;
        }

        .banner-img {
            width: 140px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 2px 12px #e0e0e0;
            background: #f5f5f5;
            transition: box-shadow 0.2s;
        }

        .banner-img:hover {
            box-shadow: 0 4px 20px #bdbdbd;
        }

        .action-btn {
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }

        .action-btn:hover {
            box-shadow: 0 2px 8px #90caf9;
            color: #1565c0 !important;
        }

        .badge {
            font-size: 1rem;
            padding: 0.5em 1em;
            border-radius: 1em;
        }

        .container-banner {
            margin-top: 32px;
            margin-bottom: 32px;
            padding: 24px 16px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 16px;
            box-shadow: 0 2px 16px #2222;
        }

        @media (max-width: 1200px) {

            .banner-table th,
            .banner-table td {
                font-size: 0.95em;
                padding: 0.4em;
            }

            .banner-img {
                width: 90px;
                height: 50px;
            }

            .container-banner {
                padding: 12px 4px;
            }
        }
    </style>
    <div class="container-fluid container-banner">
        <h2 class="text-center mb-4 fw-bold text-primary">Quản lý Banner</h2>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.banner.create') }}" class="btn btn-primary">Thêm Banner</a>
        </div>
        <div class="w-100" style="overflow-x:auto;">
            <form method="GET" class="row g-2 mb-4 align-items-end flex-nowrap justify-content-center"
                style="min-width:700px;max-width:1100px;margin:auto;">

                <div class="col-lg-3 col-md-4 col-6 mb-2">
                    <label class="form-label">Từ khóa (Đường dẫn)</label>
                    <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                        placeholder="Nhập từ khóa...">
                </div>
                <div class="col-lg-2 col-md-3 col-6 mb-2">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="Sắp diễn ra" {{ request('status') == 'Sắp diễn ra' ? 'selected' : '' }}>Sắp diễn ra
                        </option>
                        <option value="Hiện" {{ request('status') == 'Hiện' ? 'selected' : '' }}>Hiện</option>
                        <option value="Đã kết thúc" {{ request('status') == 'Đã kết thúc' ? 'selected' : '' }}>Đã kết thúc
                        </option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-6 mb-2">
                    <label class="form-label">Bắt đầu từ</label>
                    <input type="date" name="start_date_from" class="form-control"
                        value="{{ request('start_date_from') }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6 mb-2">
                    <label class="form-label">Bắt đầu đến</label>
                    <input type="date" name="start_date_to" class="form-control" value="{{ request('start_date_to') }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6 mb-2">
                    <label class="form-label">Kết thúc từ</label>
                    <input type="date" name="end_date_from" class="form-control" value="{{ request('end_date_from') }}">
                </div>
                <div class="col-lg-2 col-md-3 col-6 mb-2">
                    <label class="form-label">Kết thúc đến</label>
                    <input type="date" name="end_date_to" class="form-control" value="{{ request('end_date_to') }}">
                </div>
                <div class="col-lg-1 col-md-2 col-12 d-flex gap-1 align-items-end mb-2">
                    <button type="submit" class="btn btn-primary w-100" title="Tìm kiếm"><i
                            class="bi bi-search"></i></button>
                    <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary w-100" title="Làm mới"><i
                            class="bi bi-arrow-clockwise"></i></a>
                </div>
            </form>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered banner-table align-middle shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>Hình ảnh</th>
                        <th>Đường dẫn</th>
                        <th>Trạng thái</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banners as $index => $banner)
                        <tr>
                            <td>{{ ($banners->currentPage() - 1) * $banners->perPage() + $index + 1 }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $banner->image) }}" class="banner-img"
                                    onerror="this.src='https://via.placeholder.com/120x70?text=No+Image';">
                            </td>
                            <td>
                                @if ($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank">{{ $banner->link }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($banner->status == 'Hiện')
                                    <span class="badge bg-success">Hiện</span>
                                @elseif($banner->status == 'Sắp diễn ra')
                                    <span class="badge bg-info text-dark">Sắp diễn ra</span>
                                @else
                                    <span class="badge bg-secondary">Đã kết thúc</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($banner->start_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($banner->end_date)->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.banner.edit', $banner) }}" class="btn btn-warning btn-sm"
                                        title="Sửa">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.banner.destroy', $banner) }}" method="POST"
                                        style="display:inline-block">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa banner này?')"
                                            title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endsection
