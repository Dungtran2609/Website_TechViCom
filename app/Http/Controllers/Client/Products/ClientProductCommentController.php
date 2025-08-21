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

class ClientProductCommentController extends Controller
{
    public function store(StoreProductCommentRequest $request, $productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        // Kiểm tra quyền comment
        if (!CommentHelper::canComment($productId)) {
            $message = CommentHelper::getCommentRestrictionMessage($productId);
            return back()->with('error', $message);
        }

        // Lấy order_id từ order đã nhận hàng và chưa đánh giá
        $orderItems = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'received');
        })->where('product_id', $productId)->get();

        $selectedOrder = null;

        // Tìm đơn hàng chưa đánh giá và còn thời gian
        foreach ($orderItems as $orderItem) {
            $order = $orderItem->order;
            
            // Kiểm tra thời gian nhận hàng (15 ngày)
            if ($order->received_at) {
                $daysSinceReceived = now()->diffInDays($order->received_at);
                if ($daysSinceReceived < 0) {
                    $daysSinceReceived = 0;
                }
                if ($daysSinceReceived > 15) {
                    continue; // Bỏ qua đơn hàng hết thời gian
                }
            }

            // Kiểm tra đã comment cho đơn hàng này chưa
            $existingComment = ProductComment::where('user_id', $user->id)
                                           ->where('product_id', $productId)
                                           ->where('order_id', $order->id)
                                           ->whereNull('parent_id')
                                           ->first();

            if (!$existingComment) {
                $selectedOrder = $order;
                break; // Tìm thấy đơn hàng phù hợp
            }
        }

        if (!$selectedOrder) {
            return back()->with('error', 'Không tìm thấy đơn hàng phù hợp để đánh giá.');
        }

        // Tạo comment
        ProductComment::create([
            'product_id' => $productId,
            'user_id' => $user->id,
            'order_id' => $selectedOrder->id,
            'content' => $request->content,
            'rating' => $request->rating,
            'status' => 'approved', // Hiển thị ngay lập tức
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
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