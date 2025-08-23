<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdatePromotionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotions:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động cập nhật trạng thái chương trình khuyến mãi dựa trên thời gian';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu cập nhật trạng thái chương trình khuyến mãi...');
        
        $now = Carbon::now();
        $updatedCount = 0;
        
        // Bước 1: Ẩn tất cả chương trình đã kết thúc
        $expiredPromotions = Promotion::where('status', 1)
            ->where('end_date', '<', $now)
            ->get();
            
        foreach ($expiredPromotions as $promotion) {
            $this->info("Chương trình '{$promotion->name}' đã kết thúc, đang ẩn...");
            
            // Ẩn chương trình
            $promotion->status = 0;
            $promotion->save();
            
            // Revert giá sản phẩm
            $this->revertPromotionPrices($promotion);
            
            $updatedCount++;
        }
        
        // Bước 2: Ẩn tất cả chương trình đang kích hoạt trước khi kích hoạt chương trình mới
        $currentlyActivePromotions = Promotion::where('status', 1)->get();
        foreach ($currentlyActivePromotions as $promotion) {
            $this->info("Ẩn chương trình đang kích hoạt: '{$promotion->name}'");
            
            // Ẩn chương trình
            $promotion->status = 0;
            $promotion->save();
            
            // Revert giá sản phẩm
            $this->revertPromotionPrices($promotion);
            
            $updatedCount++;
        }
        
        // Bước 3: Tìm chương trình đang diễn ra (ưu tiên cao nhất)
        $activePromotion = Promotion::where('status', 0)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orderBy('start_date', 'asc')
            ->first();
            
        if ($activePromotion) {
            $this->info("Kích hoạt chương trình đang diễn ra: '{$activePromotion->name}'");
            
            // Kích hoạt chương trình
            $activePromotion->status = 1;
            $activePromotion->save();
            
            // Áp dụng giá khuyến mãi
            $this->applyPromotionPrices($activePromotion);
            
            $updatedCount++;
        } else {
            // Bước 4: Nếu không có chương trình đang diễn ra, tìm chương trình sắp diễn ra gần nhất
            $upcomingPromotion = Promotion::where('status', 0)
                ->where('start_date', '>', $now)
                ->orderBy('start_date', 'asc')
                ->first();
                
            if ($upcomingPromotion) {
                $this->info("Chương trình sắp diễn ra gần nhất: '{$upcomingPromotion->name}' (bắt đầu: {$upcomingPromotion->start_date})");
                // Không kích hoạt, chỉ thông báo
            }
        }
        
        $this->info("Hoàn thành! Đã cập nhật {$updatedCount} chương trình.");
        
        return 0;
    }
    
    /**
     * Revert giá sản phẩm về giá cũ khi ẩn promotion
     * KHÔNG xóa liên kết sản phẩm/danh mục để giữ lại dữ liệu
     */
    private function revertPromotionPrices($promotion)
    {
        if ($promotion->flash_type === 'category') {
            // Revert giá sản phẩm theo danh mục
            $categoryIds = $promotion->categories()->pluck('categories.id')->toArray();
            $productIds = \App\Models\Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
            foreach ($productIds as $pid) {
                $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                foreach ($variants as $variant) {
                    if (!is_null($variant->old_sale_price)) {
                        $variant->sale_price = $variant->old_sale_price;
                        $variant->old_sale_price = null;
                        $variant->save();
                    }
                }
            }
            // KHÔNG detach categories để giữ lại dữ liệu
        } elseif ($promotion->flash_type === 'flash_sale') {
            // Revert giá sản phẩm flash sale
            $oldProductIds = $promotion->products()->pluck('products.id')->toArray();
            \App\Models\ProductVariant::whereIn('product_id', $oldProductIds)
                ->whereNotNull('old_sale_price')
                ->update(['sale_price' => DB::raw('old_sale_price'), 'old_sale_price' => null]);
            // KHÔNG detach products để giữ lại dữ liệu
        }
    }
    
    /**
     * Áp dụng giá khuyến mãi khi kích hoạt promotion
     */
    private function applyPromotionPrices($promotion)
    {
        if ($promotion->flash_type === 'category') {
            // Áp dụng giá khuyến mãi cho sản phẩm theo danh mục
            $categoryIds = $promotion->categories()->pluck('categories.id')->toArray();
            $productIds = \App\Models\Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
            $discountPercent = $promotion->discount_value;
            foreach ($productIds as $pid) {
                $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                foreach ($variants as $variant) {
                    if (is_null($variant->old_sale_price)) {
                        $variant->old_sale_price = $variant->sale_price;
                    }
                    $variant->sale_price = $this->calculateSalePrice($variant->price, $discountPercent);
                    $variant->save();
                }
            }
        } elseif ($promotion->flash_type === 'flash_sale') {
            // Áp dụng giá khuyến mãi cho sản phẩm flash sale
            $promotionProducts = $promotion->products()->get();
            foreach ($promotionProducts as $promoProduct) {
                if ($promoProduct->pivot) {
                    // Lưu giá giảm cũ trước khi cập nhật giá flash sale
                    \App\Models\ProductVariant::where('product_id', $promoProduct->id)
                        ->whereNull('old_sale_price')
                        ->update(['old_sale_price' => DB::raw('sale_price')]);
                    
                    // Cập nhật sale_price cho tất cả variant của sản phẩm này
                    if ($promoProduct->pivot->sale_price && $promoProduct->pivot->sale_price > 0) {
                        // Nếu có giá cố định
                        \App\Models\ProductVariant::where('product_id', $promoProduct->id)
                            ->update(['sale_price' => $promoProduct->pivot->sale_price]);
                    } elseif ($promoProduct->pivot->discount_percent && $promoProduct->pivot->discount_percent > 0) {
                        // Nếu có phần trăm giảm giá
                        $variants = \App\Models\ProductVariant::where('product_id', $promoProduct->id)->get();
                        foreach ($variants as $variant) {
                            $discountedPrice = $this->calculateSalePrice($variant->price, $promoProduct->pivot->discount_percent);
                            $variant->sale_price = $discountedPrice;
                            $variant->save();
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Tính toán sale_price với validation giới hạn
     */
    private function calculateSalePrice($originalPrice, $discountPercent)
    {
        $discountedPrice = round($originalPrice * (1 - $discountPercent / 100));
        
        // Kiểm tra giới hạn của bigInteger (9223372036854775807)
        if ($discountedPrice > 9223372036854775807) {
            $discountedPrice = 9223372036854775807;
        }
        if ($discountedPrice < 0) {
            $discountedPrice = 0;
        }
        
        // Kiểm tra giá khuyến mãi phải nhỏ hơn giá gốc
        if ($discountedPrice >= $originalPrice) {
            $discountedPrice = $originalPrice - 1; // Giảm ít nhất 1 đồng
        }
        
        return $discountedPrice;
    }
}
