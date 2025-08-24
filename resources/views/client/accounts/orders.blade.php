@extends('client.layouts.app')


@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @php
        // Chỉ hiển thị các trạng thái có thực trong database
        $statusList = [
            'all' => 'Tất cả',
            'pending' => 'Chờ xử lý',
            'shipped' => 'Đang giao',
            'delivered' => 'Đã giao',
            'received' => 'Đã nhận',
            'cancelled' => 'Đã hủy',
            'returned' => 'Trả hàng',
        ];
        $currentStatus = request('status', 'all');
        $search = request('q', '');
    @endphp


    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50 py-8">
        <div class="techvicom-container">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full mb-4">
                    <i class="fas fa-shopping-bag text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Đơn hàng của bạn</h1>
                <p class="text-gray-600">Quản lý và theo dõi tất cả đơn hàng của bạn</p>
            </div>


            <!-- Status Filter Tabs -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach ($statusList as $key => $label)
                        @php
                            $qs = array_filter(['status' => $key, 'q' => $search]);
                            $href = url()->current() . (count($qs) ? '?' . http_build_query($qs) : '');
                            $isActive = $currentStatus === $key;
                        @endphp
                        <input type="radio" class="hidden" name="orderFilter" id="{{ $key }}" value="{{ $key }}" {{ $isActive ? 'checked' : '' }}>
                        <label
                            class="px-6 py-3 rounded-full font-medium cursor-pointer transition-all duration-200 {{ $isActive ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                            for="{{ $key }}">
                            {{ $label }}
                            @if ($key === 'all')
                                @isset($counts['all'])
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $isActive ? 'bg-white bg-opacity-20' : 'bg-orange-100 text-orange-600' }}">{{ $counts['all'] }}</span>
                                @endisset
                            @else
                                @if (isset($counts[$key]) && $counts[$key] > 0)
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $isActive ? 'bg-white bg-opacity-20' : 'bg-orange-100 text-orange-600' }}">{{ $counts[$key] }}</span>
                                @endif
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>


            <!-- Search and Filter Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                    <div class="lg:col-span-2">
                        <div class="relative">
                            <input id="searchInput" value="{{ $search }}" type="text"
                                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Tìm theo mã đơn (VD: DH000123) hoặc tên sản phẩm...">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <button
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition-colors duration-200"
                                type="button" id="searchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>


                    <div>
                        <select id="statusFilter"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                            <option value="">Tất cả trạng thái</option>
                            @foreach ($statusList as $key => $label)
                                @if ($key !== 'all')
                                    <option value="{{ $key }}" {{ $currentStatus === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>


                    <div class="flex gap-2">
                        <button
                            class="flex-1 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl"
                            type="button" id="filterBtn">
                            <i class="fas fa-filter mr-2"></i>Lọc
                        </button>
                        @if ($search || ($currentStatus && $currentStatus !== 'all'))
                            <a class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200"
                                href="{{ url()->current() }}" id="clearFilterBtn">
                                <i class="fas fa-times mr-2"></i>Xóa
                            </a>
                        @endif
                    </div>
                </div>
            </div>


            @if ($orders->count() === 0)
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-orange-100 to-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shopping-bag text-3xl text-orange-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Chưa có đơn hàng nào</h3>
                    <p class="text-gray-600 mb-6">Bạn chưa có đơn hàng nào trong hệ thống.</p>
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-xl font-medium hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Mua sắm ngay
                    </a>
                </div>
            @else
                <!-- Orders Table + Load More + Collapse -->
                <div id="ordersWrapper"
                     class="bg-white rounded-2xl shadow-lg overflow-hidden"
                     data-current-page="{{ method_exists($orders, 'currentPage') ? $orders->currentPage() : 1 }}"
                     data-last-page="{{ method_exists($orders, 'lastPage') ? $orders->lastPage() : 1 }}">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Mã đơn</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Ngày đặt</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Trạng thái</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Tổng tiền</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTbody">
                                @php
                                    $statusColor = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'received' => 'success',
                                        'cancelled' => 'danger',
                                        'returned' => 'secondary',
                                    ];
                                @endphp


                                {{-- KHÔNG sortBy ở Blade: Controller đã sắp xếp & lọc --}}
                                @foreach ($orders as $order)
                                    @php $return = $order->returns()->latest()->first(); @endphp
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $order->random_code ?? ($order->code ?? 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php $key = $order->status ?? 'pending'; @endphp
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i>Chờ xử lý
                                                    </span>
                                                @break
                                                @case('processing')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-cog mr-1"></i>Đang xử lý
                                                    </span>
                                                @break
                                                @case('shipped')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        <i class="fas fa-truck mr-1"></i>Đang giao
                                                    </span>
                                                @break
                                                @case('delivered')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>Đã giao
                                                    </span>
                                                @break
                                                @case('received')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                        <i class="fas fa-check-double mr-1"></i>Đã nhận hàng
                                                    </span>
                                                @break
                                                @case('cancelled')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i>Đã hủy
                                                    </span>
                                                @break
                                                @case('returned')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <i class="fas fa-undo mr-1"></i>Trả hàng
                                                    </span>
                                                @break
                                                @default
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        <i class="fas fa-question mr-1"></i>{{ ucfirst($order->status) }}
                                                    </span>
                                            @endswitch


                                            @if ($return && in_array($order->status, ['returned', 'cancelled']))
                                                <div class="mt-2 text-xs text-gray-500">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    <span>{{ $return->client_note ?? $return->reason }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-red-600">
                                                {{ number_format($order->final_total, 0, ',', '.') }} VND
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('client.orders.show', $order->id) }}"
                                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white text-sm font-medium rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                                <i class="fas fa-eye mr-2"></i>Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    @if (method_exists($orders, 'hasMorePages') && $orders->hasMorePages())
                        <div id="loadMoreContainer" class="border-t bg-white p-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <button id="loadMoreBtn" type="button"
                                        class="inline-flex items-center justify-center px-5 py-3 rounded-xl font-medium text-white bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 shadow-sm hover:shadow-md transition-all">
                                    <i id="loadMoreSpinner" class="fas fa-spinner fa-spin mr-2 hidden"></i>
                                    <span id="loadMoreText">Xem thêm đơn hàng</span>
                                </button>
                                <button id="collapseBtn" type="button"
                                        class="hidden inline-flex items-center justify-center px-5 py-3 rounded-xl font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 shadow-sm hover:shadow-md transition-all">
                                    <i class="fas fa-chevron-up mr-2"></i>
                                    Ẩn bớt
                                </button>
                            </div>
                            <div id="loadMoreHint" class="text-xs text-gray-500 mt-2">Hiển thị thêm kết quả mà không tải lại trang</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection


@push('styles')
    <style>
        /* Smooth transitions */
        * { transition: all 0.2s ease-in-out; }


        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endpush


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Filters & Search ===
            const filterButtons = document.querySelectorAll('input[name="orderFilter"]');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const statusFilter = document.getElementById('statusFilter');
            const filterBtn = document.getElementById('filterBtn');
            const clearFilterBtn = document.getElementById('clearFilterBtn');


            function updateUrlAndRedirect() {
                const currentUrl = new URL(window.location);
                const searchTerm = searchInput ? searchInput.value.trim() : '';
                const statusValue = statusFilter ? statusFilter.value : '';
                if (searchTerm) currentUrl.searchParams.set('q', searchTerm);
                else currentUrl.searchParams.delete('q');
                if (statusValue) currentUrl.searchParams.set('status', statusValue);
                else currentUrl.searchParams.delete('status');
                // Reset page when filter/search
                currentUrl.searchParams.delete('page');
                window.location.href = currentUrl.toString();
            }


            filterButtons.forEach((button) => {
                button.addEventListener('change', function() {
                    const filterValue = this.value;
                    if (statusFilter) statusFilter.value = filterValue === 'all' ? '' : filterValue;
                    const currentUrl = new URL(window.location);
                    currentUrl.searchParams.delete('page');
                    if (filterValue === 'all') currentUrl.searchParams.delete('status');
                    else currentUrl.searchParams.set('status', filterValue);
                    window.location.href = currentUrl.toString();
                });
            });


            if (searchBtn) searchBtn.addEventListener('click', updateUrlAndRedirect);
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') updateUrlAndRedirect();
                });
            }
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const filterValue = this.value;
                    filterButtons.forEach(button => {
                        if (filterValue === '' && button.value === 'all') button.checked = true;
                        else button.checked = (button.value === filterValue);
                    });
                    updateUrlAndRedirect();
                });
            }
            if (clearFilterBtn) {
                clearFilterBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = window.location.pathname;
                });
            }


            // === Load More + Collapse ===
            const wrapper = document.getElementById('ordersWrapper');
            const tbody   = document.getElementById('ordersTbody');
            const btn     = document.getElementById('loadMoreBtn');
            const spinner = document.getElementById('loadMoreSpinner');
            const btnTxt  = document.getElementById('loadMoreText');
            const container = document.getElementById('loadMoreContainer');
            const collapseBtn = document.getElementById('collapseBtn');


            if (wrapper && tbody && btn) {
                let currentPage = parseInt(wrapper.dataset.currentPage || '1', 10);
                const basePage  = currentPage; // trang ban đầu để có thể "ẩn bớt" quay lại
                const lastPage  = parseInt(wrapper.dataset.lastPage || '1', 10);
                const loadedPages = new Set([currentPage]);


                function setLoading(state) {
                    if (state) {
                        btn.disabled = true;
                        if (spinner) spinner.classList.remove('hidden');
                        if (btnTxt) btnTxt.textContent = 'Đang tải...';
                        btn.setAttribute('aria-busy', 'true');
                    } else {
                        btn.disabled = false;
                        if (spinner) spinner.classList.add('hidden');
                        if (btnTxt) btnTxt.textContent = 'Xem thêm đơn hàng';
                        btn.removeAttribute('aria-busy');
                    }
                }
                function hideLoadMoreBtn() { if (btn) btn.classList.add('hidden'); updateContainerVisibility(); }
                function showLoadMoreBtn() { if (btn) btn.classList.remove('hidden'); updateContainerVisibility(); }
                function showCollapseBtn() { if (collapseBtn) collapseBtn.classList.remove('hidden'); updateContainerVisibility(); }
                function hideCollapseBtn() { if (collapseBtn) collapseBtn.classList.add('hidden'); updateContainerVisibility(); }
                function updateContainerVisibility() {
                    if (!container) return;
                    const loadMoreVisible = btn && !btn.classList.contains('hidden');
                    const collapseVisible = collapseBtn && !collapseBtn.classList.contains('hidden');
                    container.classList.toggle('hidden', !(loadMoreVisible || collapseVisible));
                }


                function buildNextUrl(nextPage) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('page', nextPage);
                    return url.toString();
                }


                async function loadMore() {
                    const nextPage = currentPage + 1;
                    if (nextPage > lastPage || loadedPages.has(nextPage)) {
                        hideLoadMoreBtn(); return;
                    }
                    setLoading(true);
                    try {
                        const nextUrl = buildNextUrl(nextPage);
                        const res = await fetch(nextUrl, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
                        const html = await res.text();
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const nextTbody = doc.querySelector('table tbody');
                        const newRows = nextTbody ? Array.from(nextTbody.querySelectorAll('tr')) : [];
                        if (newRows.length === 0) { hideLoadMoreBtn(); setLoading(false); return; }


                        const frag = document.createDocumentFragment();
                        newRows.forEach(tr => {
                            tr.dataset.page = String(nextPage);           // Đánh dấu trang để có thể Ẩn bớt
                            tr.classList.add('loaded-more-row');
                            frag.appendChild(tr);
                        });
                        tbody.appendChild(frag);


                        currentPage = nextPage;
                        loadedPages.add(nextPage);
                        wrapper.dataset.currentPage = String(currentPage);


                        // Hiện nút Ẩn bớt sau khi đã load ít nhất 1 trang mới
                        showCollapseBtn();


                        // Nếu đã là trang cuối -> ẩn nút xem thêm, vẫn giữ nút Ẩn bớt
                        if (currentPage >= lastPage) {
                            hideLoadMoreBtn();
                        } else {
                            showLoadMoreBtn();
                        }
                    } catch (e) {
                        console.error(e);
                        if (btnTxt) { btnTxt.textContent = 'Lỗi tải. Thử lại'; setTimeout(() => { btnTxt.textContent = 'Xem thêm đơn hàng'; }, 1500); }
                    } finally {
                        setLoading(false);
                    }
                }


                function collapseToBase() {
                    // Xóa các hàng đã được append (có data-page)
                    tbody.querySelectorAll('tr[data-page]').forEach(tr => tr.remove());
                    // Reset lại state
                    currentPage = basePage;
                    wrapper.dataset.currentPage = String(currentPage);
                    loadedPages.clear();
                    loadedPages.add(basePage);


                    // Nút Ẩn bớt ẩn đi
                    hideCollapseBtn();


                    // Nếu còn trang tiếp theo so với basePage -> hiện lại nút "Xem thêm"
                    if (lastPage > basePage) showLoadMoreBtn();
                    else hideLoadMoreBtn();
                }


                // Khởi tạo hiển thị nút ngay từ đầu
                if (currentPage >= lastPage) {
                    hideLoadMoreBtn(); // nếu đang ở trang cuối, không hiện nút xem thêm
                    hideCollapseBtn(); // chưa load thêm trang nào
                } else {
                    showLoadMoreBtn();
                    hideCollapseBtn();
                }


                btn.addEventListener('click', loadMore);
                if (collapseBtn) collapseBtn.addEventListener('click', collapseToBase);
                updateContainerVisibility();
            }
        });
    </script>
@endpush





