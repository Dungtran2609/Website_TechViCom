@extends('client.layouts.app')

@section('title', 'Tra cứu hóa đơn - Techvicom')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen py-12">
    <div class="techvicom-container">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                <i class="fas fa-file-invoice text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Tra cứu hóa đơn</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Tra cứu và tải hóa đơn điện tử của bạn một cách nhanh chóng và thuận tiện
            </p>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <!-- Search Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tra cứu theo Email</h2>
                    <p class="text-gray-600">Nhập email để nhận mã xác nhận và xem tất cả hóa đơn</p>
                </div>

                <!-- Step 1: Email Input -->
                <div id="step1" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-envelope text-green-500 mr-2"></i>
                            Email
                        </label>
                        <input type="email" 
                               id="email"
                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg"
                               placeholder="VD: example@gmail.com">
                        <p class="text-xs text-gray-500 mt-2">Email đã đăng ký khi mua hàng</p>
                    </div>

                    <div class="text-center">
                        <button type="button" id="sendCodeBtn" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-12 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-paper-plane mr-3"></i>
                            Gửi mã xác nhận
                        </button>
                    </div>
                </div>

                <!-- Step 2: Verification Code Input -->
                <div id="step2" class="space-y-6 hidden">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <i class="fas fa-check text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Mã xác nhận đã được gửi</h3>
                        <p class="text-gray-600">Vui lòng kiểm tra email và nhập mã 6 số</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-key text-blue-500 mr-2"></i>
                            Mã xác nhận
                        </label>
                        <input type="text" 
                               id="verificationCode"
                               maxlength="6"
                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-lg text-center tracking-widest"
                               placeholder="000000">
                        <p class="text-xs text-gray-500 mt-2">Mã 6 số đã được gửi đến email của bạn</p>
                    </div>

                    <div class="text-center space-y-4">
                        <button type="button" id="verifyCodeBtn" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-12 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-check mr-3"></i>
                            Xác nhận mã
                        </button>
                        
                        <div>
                            <button type="button" id="resendCodeBtn" class="text-blue-500 hover:text-blue-600 font-medium text-sm">
                                <i class="fas fa-redo mr-2"></i>
                                Gửi lại mã
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders List (Hidden by default) -->
            <div id="ordersList" class="bg-white rounded-2xl shadow-xl p-8 mb-8 hidden">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Danh sách đơn hàng</h2>
                    <p class="text-gray-600">Các đơn hàng của bạn</p>
                </div>

                <div id="ordersContainer" class="space-y-4">
                    <!-- Orders will be loaded here -->
                </div>
            </div>

            <!-- Information Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Invoice Info -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-file-invoice text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hóa đơn điện tử</h3>
                    <p class="text-gray-600 text-sm mb-4">Hóa đơn điện tử có giá trị pháp lý như hóa đơn giấy</p>
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                        Tuân thủ quy định của Bộ Tài chính
                    </div>
                </div>

                <!-- Download -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-download text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Tải hóa đơn</h3>
                    <p class="text-gray-600 text-sm mb-4">Tải hóa đơn PDF để lưu trữ hoặc in ấn</p>
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-file-pdf text-red-500 mr-1"></i>
                        Định dạng PDF chuẩn
                    </div>
                </div>

                <!-- Support -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-headset text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hỗ trợ</h3>
                    <p class="text-gray-600 text-sm mb-4">Liên hệ với chúng tôi nếu cần hỗ trợ</p>
                    <a href="{{ route('client.contacts.index') }}" class="inline-flex items-center text-blue-500 hover:text-blue-600 font-medium text-sm">
                        Liên hệ ngay <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-200">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-lightbulb text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Lưu ý quan trọng</h3>
                    <p class="text-gray-600">Về hóa đơn điện tử</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Hóa đơn điện tử có giá trị pháp lý</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Có thể tải và in nhiều lần</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Lưu trữ an toàn trên hệ thống</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Hóa đơn được tạo sau khi thanh toán thành công</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Có thể tra cứu trong vòng 10 năm</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xs"></i>
                            </div>
                            <p class="text-gray-700">Liên hệ hotline nếu không tìm thấy hóa đơn</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mt-8">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Thông tin liên hệ</h3>
                    <p class="text-gray-600">Liên hệ với chúng tôi để được hỗ trợ</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-phone text-blue-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Hotline</h4>
                        <p class="text-blue-500 font-bold">1800.6601</p>
                        <p class="text-sm text-gray-600">8:00 - 22:00 (T2-CN)</p>
                    </div>

                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-envelope text-green-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Email</h4>
                        <p class="text-blue-500 font-bold">techvicom@gmail.com</p>
                        <p class="text-sm text-gray-600">Phản hồi trong 24h</p>
                    </div>

                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-map-marker-alt text-purple-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Địa chỉ</h4>
                        <p class="text-gray-700 text-sm">13 Trịnh Văn Bô, Nam Từ Liêm</p>
                        <p class="text-gray-700 text-sm">Hà Nội</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-white {
    animation: fadeInUp 0.6s ease-out;
}

/* Hover effects */
.bg-white:hover {
    transform: translateY(-5px);
}

/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const verificationCodeInput = document.getElementById('verificationCode');
    const sendCodeBtn = document.getElementById('sendCodeBtn');
    const verifyCodeBtn = document.getElementById('verifyCodeBtn');
    const resendCodeBtn = document.getElementById('resendCodeBtn');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const ordersList = document.getElementById('ordersList');
    const ordersContainer = document.getElementById('ordersContainer');

    let currentEmail = '';

    // Send verification code
    sendCodeBtn.addEventListener('click', function() {
        const email = emailInput.value.trim();
        
        if (!email) {
            showAlert('Vui lòng nhập email', 'error');
            return;
        }

        if (!isValidEmail(email)) {
            showAlert('Email không hợp lệ', 'error');
            return;
        }

        // Show loading state
        const originalText = sendCodeBtn.innerHTML;
        sendCodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Đang gửi...';
        sendCodeBtn.disabled = true;

        // Send AJAX request
        fetch('/invoice/send-verification-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentEmail = email;
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
                showAlert(data.message, 'success');
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra, vui lòng thử lại', 'error');
        })
        .finally(() => {
            sendCodeBtn.innerHTML = originalText;
            sendCodeBtn.disabled = false;
        });
    });

    // Verify code
    verifyCodeBtn.addEventListener('click', function() {
        const code = verificationCodeInput.value.trim();
        
        if (!code || code.length !== 6) {
            showAlert('Vui lòng nhập mã 6 số', 'error');
            return;
        }

        // Show loading state
        const originalText = verifyCodeBtn.innerHTML;
        verifyCodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Đang xác thực...';
        verifyCodeBtn.disabled = true;

        // Send AJAX request
        fetch('/invoice/verify-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                email: currentEmail,
                verification_code: code 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                step2.classList.add('hidden');
                ordersList.classList.remove('hidden');
                displayOrders(data.orders);
                showAlert(data.message, 'success');
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra, vui lòng thử lại', 'error');
        })
        .finally(() => {
            verifyCodeBtn.innerHTML = originalText;
            verifyCodeBtn.disabled = false;
        });
    });

    // Resend code
    resendCodeBtn.addEventListener('click', function() {
        if (!currentEmail) {
            showAlert('Vui lòng nhập email trước', 'error');
            return;
        }

        // Show loading state
        const originalText = resendCodeBtn.innerHTML;
        resendCodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...';
        resendCodeBtn.disabled = true;

        // Send AJAX request
        fetch('/invoice/send-verification-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: currentEmail })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Mã xác nhận mới đã được gửi', 'success');
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra, vui lòng thử lại', 'error');
        })
        .finally(() => {
            resendCodeBtn.innerHTML = originalText;
            resendCodeBtn.disabled = false;
        });
    });

    // Display orders
    function displayOrders(orders) {
        if (orders.length === 0) {
            ordersContainer.innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-box-open text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Không có đơn hàng nào</h3>
                    <p class="text-gray-600">Chưa có đơn hàng nào với email này</p>
                </div>
            `;
            return;
        }

        const ordersHtml = orders.map(order => `
            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-gray-500"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">${order.order_number}</h3>
                            <p class="text-sm text-gray-600">${order.created_at}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(order.status)}">
                            ${order.status_vietnamese}
                        </div>
                        <div class="text-lg font-bold text-gray-900 mt-1">${order.final_total} ₫</div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        ${order.items_count} sản phẩm
                    </div>
                    <div class="space-x-2">
                        <button onclick="viewOrderDetail(${order.id})" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-eye mr-2"></i>Xem chi tiết
                        </button>
                        <button onclick="downloadInvoice(${order.id})" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-2"></i>Tải hóa đơn
                        </button>
                    </div>
                </div>
            </div>
        `).join('');

        ordersContainer.innerHTML = ordersHtml;
    }

    // Helper functions
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function getStatusColor(status) {
        const colors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'processing': 'bg-blue-100 text-blue-800',
            'shipped': 'bg-purple-100 text-purple-800',
            'delivered': 'bg-green-100 text-green-800',
            'received': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800',
            'returned': 'bg-gray-100 text-gray-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }

    function showAlert(message, type) {
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        alert.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3"></i>
                <span>${message}</span>
            </div>
        `;

        // Add to page
        document.body.appendChild(alert);

        // Remove after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }

    // Global functions for order actions
    window.viewOrderDetail = function(orderId) {
        window.open(`/invoice/order/${orderId}`, '_blank');
    };

    window.downloadInvoice = function(orderId) {
        fetch(`/invoice/download/${orderId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            showAlert(data.message, 'info');
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi tải hóa đơn', 'error');
        });
    };
});
</script>
@endsection
