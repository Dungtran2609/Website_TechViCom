<?php

namespace App\Http\Controllers\Client\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClientPaymentController extends Controller
{
    /**
     * Hiển thị trang thanh toán với QR code
     */
    public function showPayment($orderId)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->findOrFail($orderId);

        // Tạo thông tin thanh toán VietQR
        $paymentInfo = $this->generateVietQRData($order);
        
        // Tạo QR code
        $qrCode = QrCode::size(300)
            ->format('png')
            ->generate($paymentInfo['qrString']);

        return view('client.payments.show_qr', [
            'order' => $order,
            'paymentInfo' => $paymentInfo,
            'qrCode' => $qrCode
        ]);
    }

    /**
     * Tạo dữ liệu VietQR
     */
    private function generateVietQRData($order)
    {
        // Thông tin tài khoản ngân hàng (cần thay đổi theo tài khoản thực tế)
        $bankInfo = [
            'bankCode' => '970436', // Mã ngân hàng (VD: Vietcombank)
            'accountNumber' => '1234567890', // Số tài khoản
            'accountName' => 'CONG TY TECHVICOM', // Tên tài khoản
        ];

        // Tạo chuỗi QR theo chuẩn VietQR
        $qrString = $this->generateVietQRString($bankInfo, $order);

        return [
            'bankInfo' => $bankInfo,
            'qrString' => $qrString,
            'amount' => $order->final_total,
            'orderCode' => $order->random_code ?? ('DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)),
            'description' => 'Thanh toan don hang ' . ($order->random_code ?? ('DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)))
        ];
    }

    /**
     * Tạo chuỗi QR theo chuẩn VietQR
     */
    private function generateVietQRString($bankInfo, $order)
    {
        // Chuẩn VietQR: https://www.vietqr.io/
        $qrData = [
            'bankBin' => $bankInfo['bankCode'],
            'accountNo' => $bankInfo['accountNumber'],
            'amount' => $order->final_total,
            'description' => 'Thanh toan don hang ' . ($order->random_code ?? ('DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT)))
        ];

        // Tạo chuỗi QR theo format EMV QR Code
        $qrString = "00020101021238";
        $qrString .= "0010A000000727";
        $qrString .= "01290000";
        $qrString .= "0113" . $bankInfo['bankCode'];
        $qrString .= "02" . strlen($bankInfo['accountNumber']) . $bankInfo['accountNumber'];
        $qrString .= "52045";
        $qrString .= "5303364";
        $qrString .= "54" . strlen($order->final_total) . $order->final_total;
        $qrString .= "5802VN";
        $qrString .= "62" . strlen($qrData['description']) . $qrData['description'];
        $qrString .= "6304";

        return $qrString;
    }

    /**
     * Xác nhận thanh toán (webhook từ ngân hàng)
     */
    public function confirmPayment(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Kiểm tra chữ ký từ ngân hàng (nếu có)
        if ($this->verifyBankSignature($request)) {
            $order->payment_status = 'paid';
            $order->status = 'processing'; // Chuyển sang xử lý sau khi thanh toán
            $order->paid_at = now();
            $order->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid signature']);
    }

    /**
     * Kiểm tra chữ ký từ ngân hàng
     */
    private function verifyBankSignature($request)
    {
        // Implement logic kiểm tra chữ ký theo từng ngân hàng
        // Đây là ví dụ đơn giản, cần thay đổi theo API thực tế
        return true;
    }
}
