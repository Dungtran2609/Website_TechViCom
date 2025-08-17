<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class VNPayService
{
    protected $environment;
    protected $config;

    public function __construct()
    {
        $this->environment = config('vnpay.environment', 'sandbox');
        $this->config = config("vnpay.{$this->environment}");

        // Validate config
        if (!$this->config) {
            throw new \Exception("VNPAY config not found for environment: {$this->environment}");
        }
        if (empty($this->config['tmn_code']) || empty($this->config['hash_secret'])) {
            throw new \Exception("VNPAY TMN_CODE or HASH_SECRET not configured for environment: {$this->environment}");
        }

        \Log::info('VNPAY Service initialized', [
            'environment' => $this->environment,
            'tmn_code' => $this->config['tmn_code'],
            'url' => $this->config['url'],
        ]);
    }

    /**
     * Helper: build query theo chuẩn VNPAY
     * - Sort key A→Z
     * - urlencode(key) & urlencode(value)
     * - Bỏ field rỗng nếu $dropEmpty = true
     */
    private function buildVnpQuery(array $data, bool $dropEmpty = true): string
    {
        if ($dropEmpty) {
            $data = array_filter($data, static function ($v) {
                return $v !== '' && $v !== null;
            });
        }
        ksort($data);
        $pairs = [];
        foreach ($data as $k => $v) {
            $pairs[] = urlencode($k) . '=' . urlencode((string) $v);
        }
        return implode('&', $pairs);
    }

    /**
     * Tạo URL thanh toán VNPAY
     * @param Order $order
     * @param Request|null $request
     * @return string
     */
    public function createPaymentUrl(Order $order, Request $request = null)
    {
        // =================================================================
        // LƯU Ý: Code 70 (Sai chữ ký) thường do sai TMN code / Hash secret
        // hoặc ký trên chuỗi chưa URL-encode. File này đã xử lý đúng chuẩn.
        // =================================================================

        // Tham chiếu & mô tả đơn
        $vnp_TxnRef = (string) $order->id;
        $vnp_OrderInfo = "Thanh toan cho don hang #{$order->id}";
        $vnp_OrderType = (string) config('vnpay.order_type', 'other');

        // VNPAY yêu cầu số tiền nhân 100 và là chuỗi số
        // Dùng round để tránh lỗi số thực
        $vnp_Amount = (string) (int) round(((float) $order->final_total) * 100);

        if ((int) $vnp_Amount <= 0) {
            throw new \Exception("Số tiền thanh toán phải lớn hơn 0");
        }

        $vnp_Locale = (string) config('vnpay.locale', 'vn');
        $vnp_BankCode = $request ? (string) $request->input('bank_code', '') : '';
        $vnp_IpAddr = $request ? $request->ip() : request()->ip();

        // Thời gian
        $vnp_CreateDate = date('YmdHis');
        $expireMinutes = (int) config('vnpay.expire_time', 15);
        $vnp_ExpireDate = date('YmdHis', strtotime("+{$expireMinutes} minutes", strtotime($vnp_CreateDate)));

        \Log::info('VNPAY Create Date', [
            'create_date' => $vnp_CreateDate,
            'expire_date' => $vnp_ExpireDate,
            'expire_minutes' => $expireMinutes,
        ]);

        // Dữ liệu gửi VNPAY (chưa có hash)
        $inputData = [
            "vnp_Version" => (string) config('vnpay.version', '2.1.0'),
            "vnp_TmnCode" => (string) $this->config['tmn_code'],
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => (string) config('vnpay.command', 'pay'),
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => (string) config('vnpay.currency', 'VND'),
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => route('vnpay.return'),
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData["vnp_BankCode"] = $vnp_BankCode;
        }

        // Billing info (tùy chọn)
        $fullName = trim((string) ($order->recipient_name ?? ''));
        if ($fullName !== '') {
            $nameParts = preg_split('/\s+/', $fullName);
            if (count($nameParts) > 1) {
                $inputData['vnp_Bill_FirstName'] = array_pop($nameParts);
                $lastName = trim(implode(' ', $nameParts));
                if ($lastName !== '') {
                    $inputData['vnp_Bill_LastName'] = $lastName;
                }
            } else {
                $inputData['vnp_Bill_FirstName'] = $fullName;
            }
        }

        // Thêm thông tin billing bắt buộc
        if (isset($order->recipient_phone)) {
            $inputData['vnp_Bill_Mobile'] = (string) $order->recipient_phone;
        }
        
        if (isset($order->recipient_address)) {
            $inputData['vnp_Bill_Address'] = (string) $order->recipient_address;
        }
        
        // Thêm city và country mặc định
        $inputData['vnp_Bill_City'] = 'Hanoi';
        $inputData['vnp_Bill_Country'] = 'VN';

        \Log::info('VNPAY Payment Input Data', [
            'order_id' => $order->id,
            'amount' => $vnp_Amount,
            'input_data' => $inputData,
        ]);

        return $this->buildPaymentUrl($inputData);
    }

    /**
     * Xây dựng URL thanh toán và tạo chữ ký (đúng chuẩn VNPAY)
     */
    protected function buildPaymentUrl(array $inputData)
    {
        // Bỏ 2 trường hash nếu có
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        // Chuỗi ký: URL-encode + sort + bỏ rỗng
        $hashData = $this->buildVnpQuery($inputData, true);

        // Ký HMAC SHA512
        $secureHash = hash_hmac('sha512', $hashData, $this->config['hash_secret']);

        // Gắn hash & loại hash vào data gửi
        $inputData['vnp_SecureHashType'] = 'HmacSHA512';
        $inputData['vnp_SecureHash'] = $secureHash;

        // Build URL bằng đúng chuẩn encode
        $query = $this->buildVnpQuery($inputData, true);
        $vnp_Url = rtrim($this->config['url'], '?') . '?' . $query;

        \Log::info('VNPAY Payment URL Generated', [
            'url' => $vnp_Url,
            'hashdata_raw' => $hashData, // đã encode
            'secure_hash' => $secureHash,
            'clean_data' => $inputData,
        ]);

        return $vnp_Url;
    }

    /**
     * Xử lý dữ liệu trả về từ VNPAY (IPN/Return)
     */
    public function processReturn(Request $request)
    {
        // Lưu ý: PHP đã decode query string => cần re-encode lại trước khi ký
        $inputData = $request->all();
        $vnp_SecureHash = (string) ($inputData['vnp_SecureHash'] ?? '');

        \Log::info('VNPAY Return Data Received', [
            'all_data' => $inputData,
            'secure_hash' => $vnp_SecureHash,
        ]);

        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        // Re-encode + sort + bỏ rỗng => ký lại
        $hashData = $this->buildVnpQuery($inputData, true);
        $calculated = hash_hmac('sha512', $hashData, $this->config['hash_secret']);

        $isValid = hash_equals($calculated, $vnp_SecureHash);

        \Log::info('VNPAY Signature Verification', [
            'received_hash' => $vnp_SecureHash,
            'calculated_hash' => $calculated,
            'hash_data' => $hashData,
            'is_valid' => $isValid,
        ]);

        $responseCode = $inputData['vnp_ResponseCode'] ?? '99';
        $message = config("vnpay.response_codes.{$responseCode}") ?? 'Lỗi không xác định';
        
        return [
            'is_valid' => $isValid,
            'data' => $inputData,
            'order_id' => $inputData['vnp_TxnRef'] ?? null,
            'response_code' => $responseCode,
            'message' => $message,
            'transaction_id' => $inputData['vnp_TransactionNo'] ?? null,
            'bank_code' => $inputData['vnp_BankCode'] ?? null,
            'card_type' => $inputData['vnp_CardType'] ?? null,
        ];
    }

    /**
     * Cập nhật trạng thái đơn hàng sau khi thanh toán
     */
    public function updateOrderStatus(Order $order, array $vnpayData)
    {
        $responseCode = $vnpayData['response_code'] ?? '99';
        
        if ($responseCode === '00') {
            // Thanh toán thành công
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now(),
                'vnpay_transaction_id' => $vnpayData['transaction_id'] ?? null,
                'vnpay_bank_code' => $vnpayData['bank_code'] ?? null,
                'vnpay_card_type' => $vnpayData['card_type'] ?? null,
            ]);

            return ['success' => true, 'message' => 'Thanh toán thành công!'];
        } elseif ($responseCode === '24') {
            // Khách hàng hủy giao dịch
            $order->update([
                'payment_status' => 'cancelled',
                'status' => 'cancelled',
            ]);

            return ['success' => false, 'message' => 'Bạn đã hủy thanh toán. Đơn hàng vẫn được giữ lại.'];
        } else {
            // Thanh toán thất bại
            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);

            return [
                'success' => false,
                'message' => 'Thanh toán thất bại: ' . ($vnpayData['message'] ?? 'Không rõ nguyên nhân'),
            ];
        }
    }
}