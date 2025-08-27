<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use PayOS\PayOS;

class WebhookController extends Controller
{
    /**
     * Nhận và xử lý webhook từ PayOS.
     */
    public function handlePayment(Request $request)
    {
        $payOS = new PayOS(env('PAYOS_CLIENT_ID'), env('PAYOS_API_KEY'), env('PAYOS_CHECKSUM_KEY'));

        try {
            $webhookBody = $request->all();
            $verifiedData = $payOS->verifyPaymentWebhook($webhookBody);

            if ($verifiedData && $verifiedData['code'] === '00') {
                $orderId = $verifiedData['data']['orderCode'];
                $order = Order::find($orderId);

                if ($order && $order->status === 'pending') {
                    // Cập nhật trạng thái đơn hàng thành "đã thanh toán"
                    $order->update(['status' => 'paid']);

                    // Ghi log để theo dõi
                    Log::info('Webhook: Đã cập nhật thanh toán thành công cho đơn hàng #' . $orderId);

                    // TODO: Gửi email xác nhận cho khách hàng, thông báo cho admin, trừ kho...
                }
            }

            // Luôn trả về 200 OK để PayOS không gửi lại webhook
            return response()->json(['message' => 'Webhook handled successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Lỗi xử lý Webhook PayOS: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 400);
        }
    }
}
