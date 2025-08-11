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

        // Tạo comment
        ProductComment::create([
            'product_id' => $productId,
            'user_id' => $user->id,
            'content' => $request->content,
            'rating' => $request->rating,
            'status' => 'pending', // Chờ admin duyệt
        ]);

        return back()->with('success', 'Bình luận của bạn đã được gửi và đang chờ duyệt!');
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
            'status' => 'pending',
            'parent_id' => $commentId,
        ]);

        return back()->with('success', 'Phản hồi của bạn đã được gửi và đang chờ duyệt!');
    }

    public function canComment($productId)
    {
        return CommentHelper::canComment($productId);
    }
} 