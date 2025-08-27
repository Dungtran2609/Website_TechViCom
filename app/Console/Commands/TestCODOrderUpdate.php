<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\ProductComment;
use App\Helpers\CommentHelper;

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
    protected $description = 'Test logic tự động cập nhật thanh toán cho đơn hàng COD và kiểm tra khả năng đánh giá sản phẩm';

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
        $this->info("- Ngày nhận hàng: " . ($order->received_at ? $order->received_at->format('d/m/Y') : 'N/A'));
        
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

        // Test khả năng đánh giá sản phẩm
        $this->info("\n=== TEST KHẢ NĂNG ĐÁNH GIÁ SẢN PHẨM ===");
        
        // Lấy sản phẩm đầu tiên từ đơn hàng
        $orderItem = $order->orderItems()->first();
        if (!$orderItem) {
            $this->warn("Không có sản phẩm nào trong đơn hàng để test!");
            return 0;
        }

        $productId = $orderItem->product_id;
        $userId = $order->user_id;
        
        $this->info("Kiểm tra sản phẩm ID: {$productId}");
        $this->info("User ID: {$userId}");
        
        // Test CommentHelper
        $canComment = CommentHelper::canComment($productId);
        $this->info("CommentHelper::canComment(): " . ($canComment ? 'true' : 'false'));
        
        $reviewStatus = CommentHelper::getReviewStatus($productId);
        $this->info("CommentHelper::getReviewStatus(): " . json_encode($reviewStatus, JSON_UNESCAPED_UNICODE));
        
        $remainingDays = CommentHelper::getRemainingDaysToReview($productId);
        $this->info("CommentHelper::getRemainingDaysToReview(): {$remainingDays}");
        
        $purchasedItems = CommentHelper::getPurchasedItems($productId);
        $this->info("CommentHelper::getPurchasedItems(): " . $purchasedItems->count() . " items");
        
        // Test ProductComment model
        $canUserReview = ProductComment::canUserReview($userId, $productId, $orderId);
        $this->info("ProductComment::canUserReview(): " . ($canUserReview ? 'true' : 'false'));
        
        // Kiểm tra xem đã có comment nào chưa
        $existingComment = ProductComment::where('user_id', $userId)
                                       ->where('product_id', $productId)
                                       ->where('order_id', $orderId)
                                       ->whereNull('parent_id')
                                       ->first();
        
        if ($existingComment) {
            $this->info("✅ Đã có đánh giá cho sản phẩm này trong đơn hàng này");
        } else {
            $this->info("ℹ️ Chưa có đánh giá cho sản phẩm này trong đơn hàng này");
        }
        
        $this->info("\n=== KẾT LUẬN ===");
        if ($canComment && $canUserReview) {
            $this->info("✅ Người dùng CÓ THỂ đánh giá sản phẩm khi đơn hàng có trạng thái 'delivered'");
        } else {
            $this->warn("⚠️ Người dùng KHÔNG THỂ đánh giá sản phẩm khi đơn hàng có trạng thái 'delivered'");
        }
        
        return 0;
    }
}
