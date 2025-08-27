<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\DynamicMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Hiển thị trang tra cứu hóa đơn
     */
    public function index()
    {
        return view('client.pages.invoice');
    }

    /**
     * Gửi mã xác nhận qua email
     */
    public function sendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;

        // Kiểm tra xem có đơn hàng nào với email này không
        $orders = Order::where('guest_email', $email)
            ->orWhereHas('user', function($query) use ($email) {
                $query->where('email', $email);
            })
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng nào với email này'
            ], 404);
        }

        // Tạo mã xác nhận 6 số
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Lưu mã vào cache với thời gian 10 phút
        $cacheKey = 'invoice_verification_' . $email;
        Cache::put($cacheKey, $verificationCode, 600); // 10 phút

        // Gửi email chứa mã xác nhận
        try {
            $subject = 'Mã xác nhận tra cứu hóa đơn - Techvicom';
            $content = view('emails.invoice-verification', [
                'verification_code' => $verificationCode,
                'email' => $email,
                'expires_in' => '10 phút'
            ])->render();

            // Log thông tin debug
            Log::info('Sending invoice verification email', [
                'email' => $email,
                'verification_code' => $verificationCode,
                'orders_count' => $orders->count(),
                'cache_key' => $cacheKey
            ]);

            // Gửi email thật
            Mail::to($email)->send(new DynamicMail($subject, $content));
            
            Log::info('Invoice verification email sent successfully', [
                'email' => $email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mã xác nhận đã được gửi đến email của bạn'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send invoice verification email', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    /**
     * Xác thực mã và hiển thị danh sách đơn hàng
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'verification_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        $verificationCode = $request->verification_code;

        // Kiểm tra mã xác nhận
        $cacheKey = 'invoice_verification_' . $email;
        $cachedCode = Cache::get($cacheKey);

        if (!$cachedCode || $cachedCode !== $verificationCode) {
            return response()->json([
                'success' => false,
                'message' => 'Mã xác nhận không đúng hoặc đã hết hạn'
            ], 400);
        }

        // Xóa mã khỏi cache sau khi xác thực thành công
        Cache::forget($cacheKey);

        // Lấy danh sách đơn hàng
        $orders = Order::with([
            'orderItems.productVariant.product.images',
            'shippingMethod',
            'coupon'
        ])
        ->where(function($query) use ($email) {
            $query->where('guest_email', $email)
                  ->orWhereHas('user', function($q) use ($email) {
                      $q->where('email', $email);
                  });
        })
        ->orderBy('created_at', 'desc')
        ->get();

        // Tạo session để lưu trạng thái đã xác thực
        session(['invoice_verified_email' => $email]);

        return response()->json([
            'success' => true,
            'message' => 'Xác thực thành công',
            'orders' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'status' => $order->status,
                    'status_vietnamese' => $order->status_vietnamese,
                    'payment_status' => $order->payment_status,
                    'payment_status_vietnamese' => $order->payment_status_vietnamese,
                    'total_amount' => number_format($order->total_amount),
                    'final_total' => number_format($order->final_total),
                    'created_at' => $order->created_at->format('d/m/Y H:i'),
                    'items_count' => $order->orderItems->count(),
                    'first_item' => $order->orderItems->first() ? [
                        'name' => $order->orderItems->first()->name_product ?? 
                                 $order->orderItems->first()->productVariant->product->name ?? 'N/A',
                        'image' => $order->orderItems->first()->image_product ?? 
                                  $order->orderItems->first()->productVariant->product->images->first()->image_path ?? null
                    ] : null
                ];
            })
        ]);
    }

    /**
     * Hiển thị chi tiết đơn hàng (sau khi đã xác thực)
     */
    public function showOrder($id)
    {
        $verifiedEmail = session('invoice_verified_email');
        
        if (!$verifiedEmail) {
            return redirect()->route('client.invoice.index')
                ->with('error', 'Vui lòng xác thực email trước khi xem chi tiết đơn hàng');
        }

        $order = Order::with([
            'orderItems.productVariant.product.images',
            'orderItems.productVariant.attributeValues.attribute',
            'shippingMethod',
            'coupon',
            'returns'
        ])
        ->where(function($query) use ($verifiedEmail) {
            $query->where('guest_email', $verifiedEmail)
                  ->orWhereHas('user', function($q) use ($verifiedEmail) {
                      $q->where('email', $verifiedEmail);
                  });
        })
        ->findOrFail($id);

        return view('client.pages.invoice-detail', [
            'order' => $order,
            'paymentStatusMap' => Order::PAYMENT_STATUSES,
        ]);
    }

    /**
     * Tải hóa đơn PDF (sau khi đã xác thực)
     */
    public function downloadInvoice($id)
    {
        $verifiedEmail = session('invoice_verified_email');
        
        if (!$verifiedEmail) {
            return redirect()->route('client.invoice.index')
                ->with('error', 'Vui lòng xác thực email trước khi tải hóa đơn');
        }

        $order = Order::with([
            'orderItems.productVariant.product.images',
            'orderItems.productVariant.attributeValues.attribute',
            'shippingMethod',
            'coupon',
            'user'
        ])
        ->where(function($query) use ($verifiedEmail) {
            $query->where('guest_email', $verifiedEmail)
                  ->orWhereHas('user', function($q) use ($verifiedEmail) {
                      $q->where('email', $verifiedEmail);
                  });
        })
        ->findOrFail($id);

        // Tạo PDF hóa đơn
        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        
        // Tên file PDF
        $filename = 'Hoa_don_' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        
        // Tải xuống PDF
        return $pdf->download($filename);
    }

    /**
     * Hủy đơn hàng (cho khách vãng lai)
     */
    public function cancelOrder($id)
    {
        $verifiedEmail = session('invoice_verified_email');
        
        if (!$verifiedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng xác thực email trước khi thực hiện thao tác này'
            ], 401);
        }

        $order = Order::where(function($query) use ($verifiedEmail) {
            $query->where('guest_email', $verifiedEmail)
                  ->orWhereHas('user', function($q) use ($verifiedEmail) {
                      $q->where('email', $verifiedEmail);
                  });
        })
        ->findOrFail($id);

        // Kiểm tra trạng thái đơn hàng
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể hủy đơn hàng khi đang ở trạng thái "Chờ xử lý" hoặc "Đang xử lý"'
            ], 403);
        }

        // Chặn hủy đơn hàng VNPay
        if ($order->payment_method === 'bank_transfer') {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng thanh toán VNPay không thể hủy. Vui lòng liên hệ bộ phận hỗ trợ để được hỗ trợ!'
            ], 403);
        }

        $request = request();
        $cancelReason = $request->input('cancel_reason', 'Khách hủy');
        $clientNote = $request->input('client_note', '');

        // Xử lý hủy đơn hàng theo phương thức thanh toán
        if ($order->payment_method === 'cod') {
            // COD: Hủy ngay lập tức không cần phê duyệt
            $order->status = 'cancelled';
            $order->save();

            // Cộng lại tồn kho khi hủy đơn hàng
            \App\Http\Controllers\Client\Checkouts\ClientCheckoutController::releaseStockStatic($order);

            // Tạo record lưu lý do hủy với trạng thái approved
            \App\Models\OrderReturn::create([
                'order_id'     => $order->id,
                'type'         => 'cancel',
                'reason'       => $cancelReason,
                'client_note'  => $clientNote,
                'status'       => 'approved', // Tự động approved cho COD
                'requested_at' => now(),
                'approved_at'  => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng COD đã được hủy thành công!'
            ]);
        } else {
            // Các phương thức khác: Tạo yêu cầu hủy cần phê duyệt
            \App\Models\OrderReturn::create([
                'order_id'     => $order->id,
                'type'         => 'cancel',
                'reason'       => $cancelReason,
                'client_note'  => $clientNote,
                'status'       => 'pending', // Cần phê duyệt
                'requested_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu hủy đơn hàng đã được gửi thành công. Admin sẽ duyệt yêu cầu này.'
            ]);
        }
    }

    /**
     * Xác nhận thanh toán (cho khách vãng lai)
     */
    public function confirmPayment($id)
    {
        $verifiedEmail = session('invoice_verified_email');
        
        if (!$verifiedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng xác thực email trước khi thực hiện thao tác này'
            ], 401);
        }

        $order = Order::where(function($query) use ($verifiedEmail) {
            $query->where('guest_email', $verifiedEmail)
                  ->orWhereHas('user', function($q) use ($verifiedEmail) {
                      $q->where('email', $verifiedEmail);
                  });
        })
        ->whereIn('status', ['pending', 'processing'])
        ->whereIn('payment_status', ['pending', 'processing'])
        ->findOrFail($id);

        $order->payment_status = 'paid';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã xác nhận thanh toán!'
        ]);
    }

    /**
     * Yêu cầu trả hàng (cho khách vãng lai)
     */
    public function requestReturn($id)
    {
        $verifiedEmail = session('invoice_verified_email');
        
        if (!$verifiedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng xác thực email trước khi thực hiện thao tác này'
            ], 401);
        }

        $order = Order::where(function($query) use ($verifiedEmail) {
            $query->where('guest_email', $verifiedEmail)
                  ->orWhereHas('user', function($q) use ($verifiedEmail) {
                      $q->where('email', $verifiedEmail);
                  });
        })
        ->where('status', 'delivered')
        ->findOrFail($id);

        $request = request();
        
        // Validate required fields
        if (!$request->has('selected_products') || !$request->has('return_reason')) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng chọn sản phẩm và lý do trả hàng'
            ], 400);
        }

        $selectedProducts = json_decode($request->input('selected_products'), true);
        $returnReason = $request->input('return_reason');
        $clientNote = $request->input('client_note', '');
        $clientNoteOther = $request->input('client_note_other', '');
        
        // Combine notes
        $finalNote = $clientNote;
        if ($clientNoteOther) {
            $finalNote = $finalNote ? $finalNote . "\n" . $clientNoteOther : $clientNoteOther;
        }

        // Create OrderReturn record
        $orderReturn = \App\Models\OrderReturn::create([
            'order_id'     => $order->id,
            'type'         => 'return',
            'reason'       => $returnReason,
            'client_note'  => $finalNote,
            'status'       => 'pending',
            'requested_at' => now(),
        ]);

        // Handle file uploads if present
        $videoPath = null;
        if ($request->hasFile('return_video')) {
            $video = $request->file('return_video');
            $videoPath = $video->store('returns/videos', 'public');
        }

        // Handle product images if present
        $productImages = [];
        if ($request->hasFile('product_images')) {
            $uploadedImages = $request->file('product_images');
            if (is_array($uploadedImages)) {
                foreach ($uploadedImages as $productId => $images) {
                    if (!is_array($images)) {
                        $images = [$images];
                    }
                    
                    $productImages[$productId] = [];
                    foreach ($images as $image) {
                        if ($image && $image->isValid()) {
                            $imagePath = $image->store('returns/images', 'public');
                            $productImages[$productId][] = $imagePath;
                        }
                    }
                }
            }
        }

        // Update OrderReturn record with file paths
        $orderReturn->update([
            'video' => $videoPath,
            'images' => $productImages,
            'selected_products' => $selectedProducts
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Yêu cầu trả hàng đã được gửi thành công. Chúng tôi sẽ xem xét và phản hồi trong thời gian sớm nhất.'
        ]);
    }

    /**
     * Thanh toán VNPay (cho khách vãng lai)
     */
    public function payWithVnpay($id)
    {
        $verifiedEmail = session('invoice_verified_email');
        
        if (!$verifiedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng xác thực email trước khi thực hiện thao tác này'
            ], 401);
        }

        $order = Order::where(function($query) use ($verifiedEmail) {
            $query->where('guest_email', $verifiedEmail)
                  ->orWhereHas('user', function($q) use ($verifiedEmail) {
                      $q->where('email', $verifiedEmail);
                  });
        })
        ->whereIn('status', ['pending', 'processing'])
        ->whereIn('payment_status', ['pending', 'processing'])
        ->findOrFail($id);

        try {
            // Sử dụng VNPayService từ checkout controller
            $vnpayService = new \App\Services\VNPayService();
            $paymentUrl = $vnpayService->createPaymentUrl($order);
            
            return response()->json([
                'success' => true,
                'message' => 'Đang chuyển hướng đến trang thanh toán VNPay...',
                'payment_url' => $paymentUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo thanh toán VNPay: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xác nhận đã nhận hàng (cho khách vãng lai)
     */
    public function confirmReceipt($id)
    {
        $verifiedEmail = session('invoice_verified_email');
        
        if (!$verifiedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng xác thực email trước khi thực hiện thao tác này'
            ], 401);
        }

        $order = Order::where(function($query) use ($verifiedEmail) {
            $query->where('guest_email', $verifiedEmail)
                  ->orWhereHas('user', function($q) use ($verifiedEmail) {
                      $q->where('email', $verifiedEmail);
                  });
        })
        ->findOrFail($id);

        if (!in_array($order->status, ['delivered', 'shipped'])) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ xác nhận khi đơn hàng đã giao hoặc đang giao!'
            ], 400);
        }

        $order->status = 'received';
        $order->received_at = now();
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã xác nhận nhận hàng!'
        ]);
    }
}
