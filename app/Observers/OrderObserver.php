<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\DynamicMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order)
    {
        // Gửi email xác nhận đơn hàng cho khách hàng
        $this->sendOrderConfirmationEmail($order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order)
    {
        // Kiểm tra nếu trạng thái thanh toán thay đổi thành 'paid'
        if ($order->wasChanged('payment_status') && $order->payment_status === 'paid') {
            $this->sendPaymentSuccessEmail($order);
        }

        // Kiểm tra nếu trạng thái đơn hàng thay đổi thành 'shipped'
        if ($order->wasChanged('status') && $order->status === 'shipped') {
            $this->sendOrderShippedEmail($order);
        }

        // Kiểm tra nếu trạng thái đơn hàng thay đổi thành 'delivered'
        if ($order->wasChanged('status') && $order->status === 'delivered') {
            // Tự động cập nhật thanh toán cho đơn hàng COD
            if ($order->payment_method === 'cod' && $order->payment_status === 'pending') {
                $order->payment_status = 'paid';
                $order->paid_at = now();
                $order->save();
                
                // Gửi email thanh toán thành công
                $this->sendPaymentSuccessEmail($order);
            }
            
            $this->sendOrderDeliveredEmail($order);
        }
    }

    /**
     * Gửi email xác nhận đơn hàng
     */
    private function sendOrderConfirmationEmail(Order $order)
    {
        $email = $order->guest_email ?? $order->user->email ?? null;
        
        if (!$email) {
            return;
        }

        $subject = 'Xác nhận đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' - Techvicom';
        
        $content = view('emails.order-confirmation', [
            'order' => $order,
            'orderNumber' => 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'customerName' => $order->recipient_name,
            'orderDate' => $order->created_at->format('d/m/Y H:i'),
            'totalAmount' => number_format($order->final_total),
            'paymentMethod' => $order->payment_method_vietnamese,
            'shippingAddress' => $order->recipient_address,
            'orderItems' => $order->orderItems
        ])->render();

        try {
            Mail::to($email)->send(new DynamicMail($subject, $content));
        } catch (\Exception $e) {
            // Log lỗi gửi email
            Log::error('Lỗi gửi email xác nhận đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Gửi email thanh toán thành công
     */
    private function sendPaymentSuccessEmail(Order $order)
    {
        $email = $order->guest_email ?? $order->user->email ?? null;
        
        if (!$email) {
            return;
        }

        $subject = 'Thanh toán thành công - Đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' - Techvicom';
        
        $content = view('emails.payment-success', [
            'order' => $order,
            'orderNumber' => 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'customerName' => $order->recipient_name,
            'paymentDate' => $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i'),
            'totalAmount' => number_format($order->final_total),
            'paymentMethod' => $order->payment_method_vietnamese
        ])->render();

        try {
            Mail::to($email)->send(new DynamicMail($subject, $content));
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email thanh toán thành công: ' . $e->getMessage());
        }
    }

    /**
     * Gửi email đơn hàng đã giao
     */
    private function sendOrderShippedEmail(Order $order)
    {
        $email = $order->guest_email ?? $order->user->email ?? null;
        
        if (!$email) {
            return;
        }

        $subject = 'Đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' đã được giao - Techvicom';
        
        $content = view('emails.order-shipped', [
            'order' => $order,
            'orderNumber' => 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'customerName' => $order->recipient_name,
            'shippedDate' => $order->shipped_at ? $order->shipped_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i'),
            'shippingAddress' => $order->recipient_address
        ])->render();

        try {
            Mail::to($email)->send(new DynamicMail($subject, $content));
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email đơn hàng đã giao: ' . $e->getMessage());
        }
    }

    /**
     * Gửi email đơn hàng đã nhận
     */
    private function sendOrderDeliveredEmail(Order $order)
    {
        $email = $order->guest_email ?? $order->user->email ?? null;
        
        if (!$email) {
            return;
        }

        $subject = 'Đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' đã được giao thành công - Techvicom';
        
        $content = view('emails.order-delivered', [
            'order' => $order,
            'orderNumber' => 'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'customerName' => $order->recipient_name,
            'deliveredDate' => $order->received_at ? $order->received_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i')
        ])->render();

        try {
            Mail::to($email)->send(new DynamicMail($subject, $content));
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email đơn hàng đã nhận: ' . $e->getMessage());
        }
    }
}
