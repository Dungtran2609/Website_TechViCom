<?php

namespace App\Http\Controllers\Client\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreProductCommentRequest;
use App\Http\Requests\Client\ReplyProductCommentRequest;
use App\Helpers\CommentHelper;
use App\Models\ProductComment;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientProductCommentController extends Controller
{
    public function store(StoreProductCommentRequest $request, $productId)
    {
        Log::info('Bắt đầu xử lý đánh giá sản phẩm', [
            'product_id' => $productId,
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);
        
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        // Lấy order_id từ request
        $orderId = $request->input('order_id');
        
        if (!$orderId) {
            Log::info('Không có order_id');
            return back()->with('error', 'Vui lòng chọn đơn hàng để đánh giá.');
        }

        Log::info('Order ID: ' . $orderId);

        // Kiểm tra order có tồn tại và thuộc về user không
        $order = Order::where('id', $orderId)
                     ->where('user_id', $user->id)
                     ->where('status', 'received')
                     ->first();

        if (!$order) {
            Log::info('Order không tồn tại hoặc không hợp lệ', ['order_id' => $orderId]);
            return back()->with('error', 'Đơn hàng không tồn tại hoặc không hợp lệ.');
        }

        Log::info('Order hợp lệ', ['order_id' => $orderId, 'received_at' => $order->received_at]);

        // Kiểm tra thời gian nhận hàng (15 ngày)
        if ($order->received_at) {
            $daysSinceReceived = now()->diffInDays($order->received_at);
            if ($daysSinceReceived < 0) {
                $daysSinceReceived = 0;
            }
            if ($daysSinceReceived > 15) {
                Log::info('Quá thời gian đánh giá', ['days' => $daysSinceReceived]);
                return back()->with('error', 'Đã quá thời gian đánh giá (15 ngày kể từ khi nhận hàng).');
            }
            Log::info('Thời gian hợp lệ', ['days' => $daysSinceReceived]);
        }

        // Kiểm tra xem user đã đánh giá sản phẩm này cho đơn hàng này chưa
        $existingComment = ProductComment::where('user_id', $user->id)
                                       ->where('product_id', $productId)
                                       ->where('order_id', $orderId)
                                       ->whereNull('parent_id')
                                       ->first();

        if ($existingComment) {
            Log::info('Đã đánh giá rồi');
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này cho đơn hàng này rồi!');
        }

        Log::info('Chưa đánh giá, tiến hành tạo comment');

        try {
            // Tạo comment
            $comment = ProductComment::create([
                'product_id' => $productId,
                'user_id' => $user->id,
                'order_id' => $orderId,
                'content' => $request->content,
                'rating' => $request->rating,
                'status' => 'approved', // Hiển thị ngay lập tức
            ]);

            Log::info('Tạo comment thành công', ['comment_id' => $comment->id]);
            return back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi tạo comment: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.');
        }
    }

    public function reply(ReplyProductCommentRequest $request, $commentId)
    {
        $user = Auth::user();
        $parentComment = ProductComment::findOrFail($commentId);

        // Kiểm tra quyền reply
        if (!CommentHelper::canReply($parentComment->product_id)) {
            $message = CommentHelper::getCommentRestrictionMessage($parentComment->product_id);
            return back()->with('error', $message);
        }

        // Kiểm tra đã có phản hồi chưa
        $existingReply = ProductComment::where('user_id', $user->id)
                                     ->where('parent_id', $commentId)
                                     ->first();

        if ($existingReply) {
            return back()->with('error', 'Bạn đã phản hồi bình luận này rồi!');
        }

        // Tạo phản hồi
        ProductComment::create([
            'product_id' => $parentComment->product_id,
            'user_id' => $user->id,
            'content' => $request->reply_content,
            'rating' => null,
            'status' => 'approved',
            'parent_id' => $commentId,
        ]);

        return back()->with('success', 'Phản hồi của bạn đã được gửi thành công!');
    }

    public function canComment($productId)
    {
        return CommentHelper::canComment($productId);
    }

    public function filterComments(Request $request, $productId)
    {
        $product = \App\Models\Product::findOrFail($productId);
        
        // Lấy tất cả comments đã approved và không bị ẩn
        $query = $product->productComments()
            ->where('status', 'approved')
            ->where('is_hidden', false)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest();

        // Lọc theo rating nếu có
        if ($request->filled('rating') && $request->rating !== 'all') {
            $query->where('rating', $request->rating);
        }

        $comments = $query->get();

        // Đếm số lượng comment theo từng rating
        $ratingCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = $product->productComments()
                ->where('status', 'approved')
                ->where('is_hidden', false)
                ->whereNull('parent_id')
                ->where('rating', $i)
                ->count();
        }
        $ratingCounts['all'] = $product->productComments()
            ->where('status', 'approved')
            ->where('is_hidden', false)
            ->whereNull('parent_id')
            ->count();

        return response()->json([
            'comments' => $comments,
            'rating_counts' => $ratingCounts,
            'selected_rating' => $request->rating ?? 'all'
        ]);
    }
} 