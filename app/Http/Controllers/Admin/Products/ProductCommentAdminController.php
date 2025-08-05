<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Models\ProductComment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCommentAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductComment::with(['product', 'user', 'replies'])
            ->whereNull('parent_id'); // Chỉ lấy bình luận gốc

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('product_name')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->product_name . '%');
            });
        }
        if ($request->filled('user_name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $comments = $query->orderByDesc('created_at')->get();
        return view('admin.products.comments.index', compact('comments'));
    }

    public function show($id)
    {
        $comment = ProductComment::with(['product', 'user'])->findOrFail($id);
        return view('admin.products.comments.show', compact('comment'));
    }

    public function edit($id)
    {
        $comment = ProductComment::with(['product', 'user'])->findOrFail($id);
        return view('admin.products.comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $comment = ProductComment::findOrFail($id);
        $data = $request->validate([
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);
        $comment->update($data);
        return redirect()->route('admin.products.comments.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(Request $request, $id)
    {
        $comment = ProductComment::findOrFail($id);
        $comment->status = ProductComment::STATUS_DELETED;
        $comment->save();
        $comment->delete();
        $redirectRoute = route('admin.products.comments.index');
        if ($request->has('product_id')) {
            $redirectRoute .= '?product_id=' . $request->input('product_id');
        }
        return redirect($redirectRoute)->with('success', 'Đã xoá bình luận!');
    }

    public function approve($id, Request $request)
    {
        $comment = ProductComment::findOrFail($id);
        $comment->status = ProductComment::STATUS_APPROVED;
        $comment->save();
        $redirectRoute = route('admin.products.comments.index');
        if ($request->has('product_id')) {
            $redirectRoute .= '?product_id=' . $request->input('product_id');
        }
        return redirect($redirectRoute)->with('success', 'Đã duyệt bình luận!');
    }

    public function toggleStatus($id)
    {
        $comment = ProductComment::findOrFail($id);
        if ($comment->status === ProductComment::STATUS_APPROVED) {
            $comment->status = ProductComment::STATUS_DELETED;
        } else {
            $comment->status = ProductComment::STATUS_APPROVED;
        }
        $comment->save();
        return redirect()->route('admin.products.comments.index');
    }

    public function reply(Request $request, $id)
    {
        $comment = ProductComment::findOrFail($id);
        // Kiểm tra đã có phản hồi chưa
        if ($comment->replies()->exists()) {
            $redirectRoute = route('admin.products.comments.index');
            $productId = $request->input('product_id') ?? $comment->product_id;
            if ($productId) {
                $redirectRoute .= '?product_id=' . $productId;
            }
            return redirect($redirectRoute)->with('error', 'Bạn chỉ được phản hồi 1 lần cho mỗi bình luận!');
        }
        $request->validate([
            'reply_content' => 'required|string',
        ]);
        ProductComment::create([
            'product_id' => $comment->product_id,
            'user_id' => Auth::id(),
            'content' => $request->reply_content,
            'rating' => null,
            'status' => ProductComment::STATUS_APPROVED,
            'parent_id' => $comment->id,
        ]);
        $redirectRoute = route('admin.products.comments.index');
        $productId = $request->input('product_id') ?? $comment->product_id;
        if ($productId) {
            $redirectRoute .= '?product_id=' . $productId;
        }
        return redirect($redirectRoute)->with('success', 'Đã phản hồi bình luận!');
    }

    public function productsWithComments()
    {
        $products = \App\Models\Product::whereHas('comments', function($q) {
            $q->whereNull('parent_id');
        })
        ->withCount(['comments' => function($q) {
            $q->whereNull('parent_id');
        }])
        ->orderByDesc('comments_count')
        ->get();
        return view('admin.products.comments.products_with_comments', compact('products'));
    }
} 