<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class TestCODOrderUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:test-cod-update {order_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test logic tự động cập nhật thanh toán cho đơn hàng COD';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        $order = Order::find($orderId);
        
        if (!$order) {
            $this->error("Không tìm thấy đơn hàng #{$orderId}");
            return 1;
        }

        $this->info("Thông tin đơn hàng #{$orderId}:");
        $this->info("- Phương thức thanh toán: {$order->payment_method}");
        $this->info("- Trạng thái hiện tại: {$order->status}");
        $this->info("- Trạng thái thanh toán: {$order->payment_status}");
        
        if ($order->payment_method !== 'cod') {
            $this->error("Đơn hàng này không phải COD!");
            return 1;
        }

        if ($order->status !== 'pending' && $order->status !== 'processing') {
            $this->error("Đơn hàng này không thể chuyển sang 'delivered'!");
            return 1;
        }

        $this->info("\nBắt đầu test chuyển trạng thái sang 'delivered'...");
        
        // Lưu trạng thái cũ
        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        
        // Chuyển trạng thái sang delivered
        $order->status = 'delivered';
        $order->save();
        
        // Reload để lấy thông tin mới
        $order->refresh();
        
        $this->info("Kết quả:");
        $this->info("- Trạng thái: {$oldStatus} → {$order->status}");
        $this->info("- Thanh toán: {$oldPaymentStatus} → {$order->payment_status}");
        
        if ($order->payment_status === 'paid') {
            $this->info("✅ Logic tự động thanh toán hoạt động!");
        } else {
            $this->warn("⚠️ Logic tự động thanh toán chưa hoạt động!");
        }
        
        return 0;
    }
}
