<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VNPAY Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho VNPAY Payment Gateway
    |
    */

    // Sandbox environment
    'sandbox' => [
        'url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        'tmn_code' => env('VNPAY_TMN_CODE', '2WZSC2P3'),
        'hash_secret' => env('VNPAY_HASH_SECRET', 'ZU2VKRD77WSG495MSL851DY8PVXIB7RQ'),
    ],

    // Production environment
    'production' => [
        'url' => 'https://pay.vnpay.vn/vpcpay.html',
        'tmn_code' => env('VNPAY_TMN_CODE', ''),
        'hash_secret' => env('VNPAY_HASH_SECRET', ''),
    ],

    // Current environment
    'environment' => env('VNPAY_ENVIRONMENT', 'sandbox'),

    // Return URL
    'return_url' => env('VNPAY_RETURN_URL', '/vnpay/return'),

    // Locale
    'locale' => 'vn',

    // Currency
    'currency' => 'VND',

    // Order type
    'order_type' => 'other',

    // Version
    'version' => '2.1.0',

    // Command
    'command' => 'pay',

    // Expire time (minutes)
    'expire_time' => 15,

    // Response codes
    'response_codes' => [
        '00' => 'Giao dịch thành công',
        '01' => 'Giao dịch chưa hoàn tất',
        '02' => 'Giao dịch bị lỗi',
        '04' => 'Giao dịch đảo (Khách hàng đã bị trừ tiền tại Ngân hàng nhưng GD chưa thành công ở VNPAY)',
        '05' => 'VNPAY đang xử lý',
        '06' => 'VNPAY đã gửi yêu cầu hoàn tiền sang Ngân hàng',
        '07' => 'Giao dịch bị nghi ngờ gian lận',
        '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking',
        '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP)',
        '24' => 'Khách hàng hủy giao dịch',
        '65' => 'Giao dịch không thành công do tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày',
        '75' => 'Ngân hàng thanh toán đang bảo trì',
        '79' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu thanh toán quá số lần quy định',
        '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)',
    ],
];
