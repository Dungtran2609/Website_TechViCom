<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class UpdateDeliveredCODOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-delivered-cod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái thanh toán cho tất cả đơn hàng COD đã giao nhưng chưa thanh toán';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu cập nhật trạng thái thanh toán cho đơn hàng COD đã giao...');

        // Tìm tất cả đơn hàng COD đã giao nhưng chưa thanh toán
        $orders = Order::where('payment_method', 'cod')
            ->where('status', 'delivered')
            ->where('payment_status', 'pending')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Không có đơn hàng COD nào đã giao nhưng chưa thanh toán.');
            return;
        }

        $this->info('Tìm thấy ' . $orders->count() . ' đơn hàng cần cập nhật:');

        foreach ($orders as $order) {
            $this->info("Đơn hàng #{$order->id} - " . ($order->order_number ?? 'N/A') . " - {$order->recipient_name}");
            
            // Cập nhật trạng thái thanh toán
            $order->payment_status = 'paid';
            $order->paid_at = now();
            $order->save();
            
            $this->info("  ✓ Đã cập nhật: pending → paid");
        }

        $this->info('Hoàn thành cập nhật ' . $orders->count() . ' đơn hàng COD.');
    }
}
