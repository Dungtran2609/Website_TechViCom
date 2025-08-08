<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order; // Đảm bảo bạn đã có model Order
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PayOS\PayOS; // Import thư viện PayOS

class ClientPaymentController extends Controller
{
    /**
     * Tạo đơn hàng và chuyển hướng đến trang thanh toán QR.
     * Phương thức này được gọi khi người dùng nhấn nút "Xác nhận đơn hàng".
     */
    public function create(Request $request)
    {
        // 1. Tạo đơn hàng trong cơ sở dữ liệu của bạn với trạng thái 'pending'
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $request->total_amount, // Giả sử tổng tiền được gửi lên từ form
            'status' => 'pending', // Trạng thái ban đầu
            //... thêm các trường khác của đơn hàng như địa chỉ, ghi chú...
        ]);

        // 2. Khởi tạo PayOS
        $payOS = new PayOS(env('PAYOS_CLIENT_ID'), env('PAYOS_API_KEY'), env('PAYOS_CHECKSUM_KEY'));

        // 3. Chuẩn bị dữ liệu để tạo link thanh toán
        $paymentData = [
            "orderCode" => $order->id, // Mã đơn hàng của bạn, PHẢI LÀ DUY NHẤT
            "amount" => (int) $order->total_amount,
            "description" => "Thanh toan don hang #" . $order->id,
            "returnUrl" => route('client.payment.success'), // Route sẽ được định nghĩa ở dưới
            "cancelUrl" => route('client.payment.failed'),  // Route sẽ được định nghĩa ở dưới
        ];

        try {
            // 4. Gọi API của PayOS để tạo link
            $response = $payOS->createPaymentLink($paymentData);

            // 5. Trả về view để hiển thị mã QR cho khách hàng
            // View này sẽ nằm ở resources/views/client/payments/show_qr.blade.php
            return view('client.payments.show_qr', [
                'order' => $order,
                'qrImage' => $response['qrCode'],
            ]);

        } catch (\Exception $e) {
            // Xử lý nếu có lỗi khi kết nối với PayOS
            \Log::error('Lỗi tạo link thanh toán PayOS: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Hệ thống thanh toán đang gặp sự cố. Vui lòng thử lại sau.');
        }
    }

    /**
     * Endpoint cho JavaScript (Polling) gọi đến để kiểm tra trạng thái đơn hàng.
     */
    public function checkPaymentStatus(Order $order)
    {
        // Chỉ cần kiểm tra trạng thái trong DB của bạn.
        // Webhook sẽ là nguồn cập nhật trạng thái này một cách tin cậy.
        if ($order->status === 'paid') {
            return response()->json(['status' => 'paid']);
        }
        return response()->json(['status' => 'pending']);
    }

    /**
     * Hiển thị trang thông báo thanh toán thành công.
     */
    public function paymentSuccess(Request $request)
    {
        // Bạn có thể lấy thông tin đơn hàng từ request của PayOS trả về để hiển thị
        $orderId = $request->input('orderCode');
        $order = Order::find($orderId);
        return view('client.payments.success', compact('order'));
    }

    /**
     * Hiển thị trang thông báo thanh toán thất bại.
     */
    public function paymentFailed()
    {
        return view('client.payments.failed');
    }
}
