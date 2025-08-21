<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class VNPayService
{
    protected string $environment;
    protected array $config;

    public function __construct()
    {
        $this->environment = config('vnpay.environment', 'sandbox');
        $this->config = (array) config("vnpay.{$this->environment}", []);

        if (!$this->config) {
            throw new \RuntimeException("VNPAY config not found for environment: {$this->environment}");
        }
        if (empty($this->config['tmn_code']) || empty($this->config['hash_secret']) || empty($this->config['url'])) {
            throw new \RuntimeException("VNPAY TMN_CODE / HASH_SECRET / URL not configured for environment: {$this->environment}");
        }

        \Log::info('VNPAY Service initialized', [
            'environment' => $this->environment,
            'tmn_code'    => $this->config['tmn_code'],
            'url'         => $this->config['url'],
        ]);
    }

    /**
     * Build query theo chuẩn VNPAY:
     * - Bỏ rỗng nếu $dropEmpty = true
     * - Sort key A→Z
     * - urlencode cả key & value
     */
    private function buildVnpQuery(array $data, bool $dropEmpty = true): string
    {
        if ($dropEmpty) {
            $data = array_filter($data, static fn($v) => $v !== '' && $v !== null);
        }
        ksort($data);
        $pairs = [];
        foreach ($data as $k => $v) {
            $pairs[] = urlencode((string) $k) . '=' . urlencode((string) $v);
        }
        return implode('&', $pairs);
    }




    public function createPaymentUrl(Order $order, ?Request $request = null, array $options = []): string
    {
        // Lấy đúng TxnRef/Amount đã lưu trên đơn (hoặc override từ $options)
        $vnp_TxnRef = (string) ($options['txn_ref'] ?? $order->vnp_txn_ref);
        $vnp_Amount = (string) (int) ($options['amount'] ?? $order->vnp_amount_expected);
        if ($vnp_TxnRef === '' || (int) $vnp_Amount <= 0) {
            throw new \InvalidArgumentException('Missing or invalid vnp_TxnRef / vnp_Amount.');
        }

        $vnp_OrderInfo = "Thanh toan don hang #{$order->id}";
        $vnp_OrderType = (string) config('vnpay.order_type', 'other');
        $vnp_Locale = (string) config('vnpay.locale', 'vn');
        $vnp_IpAddr = $request ? $request->ip() : request()->ip();

        // Thời gian tạo & hết hạn
        $vnp_CreateDate = now()->format('YmdHis');
        $vnp_ExpireDate = now()->addMinutes((int) config('vnpay.expire_time', 15))->format('YmdHis');

        // LẤY RETURN URL TỪ CONFIG (tuyệt đối) – KHÔNG dùng route() để tránh lệch APP_URL
        $returnUrlCfg = config('vnpay.return_url', '/vnpay/return');
        $vnp_ReturnUrl = (str_starts_with($returnUrlCfg, 'http'))
            ? $returnUrlCfg
            : url($returnUrlCfg);

        $inputData = [
            'vnp_Version' => (string) config('vnpay.version', '2.1.0'),
            'vnp_Command' => (string) config('vnpay.command', 'pay'),
            'vnp_TmnCode' => (string) $this->config['tmn_code'],
            'vnp_Amount' => $vnp_Amount,   // (VND * 100) ở DB
            'vnp_CurrCode' => (string) config('vnpay.currency', 'VND'),
            'vnp_TxnRef' => $vnp_TxnRef,
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => $vnp_OrderType,
            'vnp_Locale' => $vnp_Locale,
            'vnp_ReturnUrl' => $vnp_ReturnUrl,
            'vnp_IpAddr' => $vnp_IpAddr,
            'vnp_CreateDate' => $vnp_CreateDate,
            'vnp_ExpireDate' => $vnp_ExpireDate,
        ];

        // Optional: BankCode người dùng chọn
        if ($request && ($bank = trim((string) $request->input('bank_code', ''))) !== '') {
            $inputData['vnp_BankCode'] = $bank;
        }

        // Billing info (tùy chọn)
        $fullName = trim((string) ($order->recipient_name ?? ''));
        if ($fullName !== '') {
            $parts = preg_split('/\s+/', $fullName);
            if (count($parts) > 1) {
                $inputData['vnp_Bill_FirstName'] = array_pop($parts);
                $lastName = trim(implode(' ', $parts));
                if ($lastName !== '')
                    $inputData['vnp_Bill_LastName'] = $lastName;
            } else {
                $inputData['vnp_Bill_FirstName'] = $fullName;
            }
        }
        if (!empty($order->recipient_phone))
            $inputData['vnp_Bill_Mobile'] = (string) $order->recipient_phone;
        if (!empty($order->recipient_address))
            $inputData['vnp_Bill_Address'] = (string) $order->recipient_address;
        $inputData['vnp_Bill_City'] = 'Hanoi';
        $inputData['vnp_Bill_Country'] = 'VN';

        \Log::info('VNPAY Payment Input Data', [
            'order_id' => $order->id,
            'txn_ref' => $vnp_TxnRef,
            'amount' => $vnp_Amount,
            'return_url' => $vnp_ReturnUrl,
            'input_data' => $inputData,
        ]);

        return $this->buildPaymentUrl($inputData);
    }


    /**
     * Build URL & hash HMAC SHA512 theo chuẩn VNPay
     */
    protected function buildPaymentUrl(array $inputData): string
    {
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        // Chuỗi ký (đã URL-encode và sort)
        $hashData = $this->buildVnpQuery($inputData, true);

        $secureHash = hash_hmac('sha512', $hashData, (string) $this->config['hash_secret']);

        $inputData['vnp_SecureHashType'] = 'HmacSHA512';
        $inputData['vnp_SecureHash']     = $secureHash;

        $query  = $this->buildVnpQuery($inputData, true);
        $vnpUrl = rtrim((string) $this->config['url'], '?') . '?' . $query;

        \Log::info('VNPAY Payment URL Generated', [
            'url'           => $vnpUrl,
            'hashdata_raw'  => $hashData,
            'secure_hash'   => $secureHash,
            'clean_data'    => $inputData,
        ]);

        return $vnpUrl;
    }

    /**
     * Xử lý dữ liệu trả về từ VNPAY (return)
     * - Re-encode + sort + ký lại để so sánh chữ ký
     * - Trả về key theo đúng tên controller sử dụng
     */
    public function processReturn(Request $request): array
    {
        // Lấy các vnp_* param
        $params = [];
        foreach ($request->all() as $k => $v) {
            if (strpos($k, 'vnp_') === 0) {
                $params[$k] = $v;
            }
        }

        $receivedHash = (string) ($params['vnp_SecureHash'] ?? '');
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);

        ksort($params);
        $hashData = $this->buildVnpQuery($params, true);
        $calcHash = hash_hmac('sha512', $hashData, (string) $this->config['hash_secret']);
        $isValid  = hash_equals(strtolower($calcHash), strtolower($receivedHash));

        \Log::info('VNPAY Signature Verification', [
            'received_hash' => $receivedHash,
            'calculated'    => $calcHash,
            'hash_data'     => $hashData,
            'is_valid'      => $isValid,
            'all_params'    => $params, // Log tất cả tham số để debug
        ]);

        // Chuẩn hoá output cho controller
        return [
            'is_valid'              => $isValid,
            'vnp_TxnRef'            => $params['vnp_TxnRef']            ?? null,
            'vnp_Amount'            => isset($params['vnp_Amount']) ? (int) $params['vnp_Amount'] : null, // *100
            'vnp_ResponseCode'      => $params['vnp_ResponseCode']      ?? null,
            'vnp_TransactionStatus' => $params['vnp_TransactionStatus'] ?? null,
            'vnp_TransactionNo'     => $params['vnp_TransactionNo']     ?? null,
            'vnp_BankCode'          => $params['vnp_BankCode']          ?? null,
            'vnp_CardType'          => $params['vnp_CardType']          ?? null,
            'vnp_PayDate'           => $params['vnp_PayDate']           ?? null,
            'vnp_PromotionCode'     => $params['vnp_PromotionCode']     ?? null, // Mã khuyến mại
            'vnp_PromotionAmount'   => isset($params['vnp_PromotionAmount']) ? (int) $params['vnp_PromotionAmount'] : null, // Số tiền khuyến mại
            'raw'                   => $params,
        ];
    }

    /**
     * Cập nhật trạng thái đơn dựa trên dữ liệu trả về
     * - Không tự truy vấn order khác; chỉ dùng $order đã map theo TxnRef tại controller
     */
    public function updateOrderStatus(Order $order, array $vnpayData): array
    {
        $ok = (
            ($vnpayData['vnp_ResponseCode']      ?? null) === '00' &&
            ($vnpayData['vnp_TransactionStatus'] ?? null) === '00'
        );

        if ($ok) {
            $order->forceFill([
                'payment_status'       => 'paid',
                'paid_at'              => now(),
                'vnpay_transaction_id' => $vnpayData['vnp_TransactionNo'] ?? null,
                'vnpay_bank_code'      => $vnpayData['vnp_BankCode']      ?? null,
                'vnpay_card_type'      => $vnpayData['vnp_CardType']      ?? null,
            ])->save();

            return ['success' => true, 'message' => 'Thanh toán thành công'];
        }

        // Không “huỷ đơn” ở đây; để controller quyết định (đã có restoreCart)
        $order->update([
            'payment_status' => 'failed',
        ]);

        return ['success' => false, 'message' => 'Thanh toán không thành công'];
    }
}
