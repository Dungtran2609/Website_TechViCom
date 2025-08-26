<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class CheckAllCODOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-all-cod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra tất cả đơn hàng COD và trạng thái thanh toán';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Kiểm tra tất cả đơn hàng COD...');

        $orders = Order::where('payment_method', 'cod')->get();

        if ($orders->isEmpty()) {
            $this->info('Không có đơn hàng COD nào.');
            return;
        }

        $this->table(
            ['ID', 'Mã đơn hàng', 'Trạng thái', 'Thanh toán', 'Tổng tiền', 'Ngày tạo'],
            $orders->map(function ($order) {
                return [
                    $order->id,
                    $order->order_number ?? 'N/A',
                    $order->status,
                    $order->payment_status,
                    number_format($order->final_total, 0, ',', '.') . '₫',
                    $order->created_at->format('d/m/Y H:i')
                ];
            })->toArray()
        );

        $this->info('Tổng cộng: ' . $orders->count() . ' đơn hàng COD');
    }
}
