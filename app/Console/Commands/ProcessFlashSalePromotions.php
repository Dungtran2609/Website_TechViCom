<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Promotion;
use App\Models\Product;
use Carbon\Carbon;

class ProcessFlashSalePromotions extends Command
{
    protected $signature = 'promotion:process-flashsale';
    protected $description = 'Kích hoạt hoặc kết thúc Flash Sale, cập nhật giá sale cho sản phẩm';

    public function handle()
    {
        $now = Carbon::now();
        // Lấy các promotion flash sale đang hoạt động hoặc vừa kết thúc
        $promotions = Promotion::where('flash_type', 'flash_sale')
            ->where(function($q) use ($now) {
                $q->where(function($q2) use ($now) {
                    $q2->where('start_date', '<=', $now)->where('end_date', '>=', $now);
                })
                ->orWhere(function($q2) use ($now) {
                    $q2->where('end_date', '<', $now);
                });
            })
            ->get();

        foreach ($promotions as $promotion) {
            $isActive = $promotion->start_date <= $now && $promotion->end_date >= $now;
            $isExpired = $promotion->end_date < $now;

            // Lấy danh sách sản phẩm áp dụng
            $products = $promotion->products;
            foreach ($products as $product) {
                $pivot = $product->pivot;
                if ($isActive) {
                    // Đang flash sale: cập nhật giá sale
                    if ($pivot->sale_price && $pivot->sale_price > 0) {
                        $product->sale_price = $pivot->sale_price;
                        $product->save();
                    }
                } elseif ($isExpired) {
                    // Hết flash sale: trả về giá thường
                    $product->sale_price = null;
                    $product->save();
                }
            }
        }
        $this->info('Đã xử lý Flash Sale promotions.');
    }
}
