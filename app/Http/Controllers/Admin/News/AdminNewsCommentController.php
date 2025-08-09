<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use App\Models\NewsComment;
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

        // Lấy bài viết có bình luận, sắp xếp theo bình luận mới nhất, có phân trang
        $allNews = News::whereIn('id', $newsWithCommentsIds)
            ->select('id', 'title', 'image')
            ->withMax(['comments as latest_comment_created_at' => function ($q) {
                $q->whereNull('parent_id');
            }], 'created_at')
            ->orderByDesc('latest_comment_created_at')
            ->paginate(12);

        $query = NewsComment::with(['user', 'news', 'children'])
            ->withCount('replies')
            ->whereNull('parent_id')
            ->whereIn('news_id', $newsWithCommentsIds);

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('content', 'like', "%$keyword%")
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%$keyword%"))
                    ->orWhereHas('news', fn($q) => $q->where('title', 'like', "%$keyword%"));
            });
        }

        if ($request->filled('news_id')) {
            $query->where('news_id', $request->news_id);
        }

        if ($request->filled('is_hidden')) {
            $query->where('is_hidden', $request->is_hidden);
        }

        $comments = $query
            ->orderByDesc('likes_count')
            ->orderByDesc('replies_count')
            ->orderBy('is_hidden')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.news.news_comments.index', compact('comments', 'allNews'));
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

    public function show($news_id)
    {
        $news = News::with(['comments.user', 'comments.children.user', 'category', 'author'])->findOrFail($news_id);
        return view('admin.news.news_comments.show', compact('news'));
    }
}
