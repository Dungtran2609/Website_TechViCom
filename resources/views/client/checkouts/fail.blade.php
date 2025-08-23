@extends('client.layouts.app')

@section('title', 'Thanh toán thất bại - Techvicom')

@push('styles')
    <style>
        .fail-container {
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .fail-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        
        .fail-icon {
            width: 80px;
            height: 80px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }
        
        .fail-icon i {
            font-size: 2.5rem;
            color: #dc2626;
        }
        
        .fail-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .fail-message {
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ea580c, #dc2626);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid #d1d5db;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }
        
        .timer-info {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 1rem;
            margin: 1.5rem 0;
            color: #92400e;
        }
        
        .timer-info strong {
            color: #d97706;
        }
    </style>
@endpush

@section('content')
    {{-- Header dùng chung --}}
    <div id="shared-header-container" class="no-print"></div>

    <main class="techvicom-container py-8">
        <div class="fail-container">
            <div class="fail-card">
                <div class="fail-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                
                <h1 class="fail-title">Thanh toán bị chặn</h1>
                
                <div class="fail-message">
                    @if (session('error'))
                        {{ session('error') }}
                    @else
                        Bạn đã hủy thanh toán VNPay quá 3 lần cho đơn hàng này. 
                        Hệ thống đã tạm thời chặn phương thức thanh toán này để bảo vệ.
                    @endif
                </div>
                
                                            <div class="timer-info">
                                <strong>⏰ Thời gian chờ:</strong> 2 phút<br>
                                Sau 2 phút, bạn có thể thử lại phương thức thanh toán VNPay.
                            </div>
                
                <div class="action-buttons">
                    <a href="{{ route('carts.index') }}" class="btn-secondary">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Về giỏ hàng
                    </a>
                    
                    <a href="{{ route('home') }}" class="btn-primary">
                        <i class="fas fa-home mr-2"></i>
                        Về trang chủ
                    </a>
                </div>
                
                <div class="mt-6 text-sm text-gray-500">
                    <p>Nếu bạn cần hỗ trợ, vui lòng liên hệ:</p>
                    <p class="mt-2">
                        <i class="fas fa-phone mr-2"></i>
                        Hotline: 1900-xxxx
                    </p>
                    <p>
                        <i class="fas fa-envelope mr-2"></i>
                        Email: support@techvicom.com
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Auto redirect sau 30 giây
        let countdown = 30;
        const countdownElement = document.createElement('div');
        countdownElement.className = 'mt-4 text-sm text-gray-500';
        countdownElement.innerHTML = `Tự động chuyển về trang chủ sau <strong>${countdown}</strong> giây`;
        
        document.querySelector('.action-buttons').after(countdownElement);
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.innerHTML = `Tự động chuyển về trang chủ sau <strong>${countdown}</strong> giây`;
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = '{{ route('home') }}';
            }
        }, 1000);
    </script>
@endpush
