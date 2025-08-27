<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use App\Models\NewsComment;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNewsCommentController extends Controller
{
    // Hiển thị danh sách bình luận với các bài viết có bình luận
    public function index(Request $request)
    {
        // Lấy danh sách ID bài viết có bình luận cha
        $newsWithCommentsIds = NewsComment::whereNull('parent_id')
            ->distinct()
            ->pluck('news_id');

        // Query bài viết có bình luận
        $query = News::whereIn('id', $newsWithCommentsIds)
            ->select('id', 'title', 'image', 'category_id')
            ->withMax(['comments as latest_comment_created_at' => function ($q) {
                $q->whereNull('parent_id');
            }], 'created_at');

        // Tìm kiếm theo tiêu đề bài viết
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo ngày bình luận
        if ($request->filled('date_from')) {
            $query->whereDate('latest_comment_created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('latest_comment_created_at', '<=', $request->date_to);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'latest_comment_created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'title') {
            $query->orderBy('title', $sortOrder);
        } elseif ($sortBy === 'id') {
            $query->orderBy('id', $sortOrder);
        } else {
            $query->orderBy('latest_comment_created_at', $sortOrder);
        }

        $allNews = $query->paginate(12);

        // Lấy danh sách danh mục cho filter
        $categories = NewsCategory::orderBy('name')->get();

        return view('admin.news.news_comments.index', compact('allNews', 'categories'));
    }

    public function destroy($id)
    {
        $comment = NewsComment::findOrFail($id);
        NewsComment::where('parent_id', $comment->id)->delete();
        $comment->delete();

        return back()->with('success', 'Đã xoá bình luận và phản hồi.');
    }

    public function toggleVisibility($id)
    {
        $comment = NewsComment::findOrFail($id);
        $newState = !$comment->is_hidden;
        $comment->update(['is_hidden' => $newState]);

        NewsComment::where('parent_id', $comment->id)->update(['is_hidden' => $newState]);

        return back()->with('success', 'Đã cập nhật trạng thái hiển thị.');
    }

    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $parent = NewsComment::findOrFail($id);

        NewsComment::create([
            'user_id' => Auth::id() ?? 1,
            'news_id' => $parent->news_id,
            'parent_id' => $parent->id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Đã trả lời bình luận.');
    }

    public function like($id)
    {
        $comment = NewsComment::findOrFail($id);
        $sessionKey = 'liked_comment_' . $id;

        if (session()->has($sessionKey)) {
            return back()->with('error', 'Bạn đã thích bình luận này.');
        }

        $comment->increment('likes_count');
        session()->put($sessionKey, true);

        return back()->with('success', 'Đã thích bình luận.');
    }

    public function show($news_id, Request $request)
    {
        $news = News::with(['category', 'author'])->findOrFail($news_id);
        
        // Query comments với điều kiện tìm kiếm
        $commentsQuery = NewsComment::with(['user', 'children.user'])
            ->where('news_id', $news_id);
        
        // Thêm điều kiện tìm kiếm nếu có
        if ($request->filled('search')) {
            $search = $request->input('search');
            $commentsQuery->where(function($query) use ($search) {
                $query->where('content', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            });
        }
        
        // Lọc theo trạng thái nếu có
        if ($request->filled('status')) {
            $commentsQuery->where('is_hidden', $request->status === 'hidden');
        }
        
        // Lấy comments và gán vào news
        $comments = $commentsQuery->whereNull('parent_id')
                                ->orderByDesc('created_at')
                                ->get();
        $news->setRelation('comments', $comments);

        return view('admin.news.news_comments.show', compact('news'));
    }
}
