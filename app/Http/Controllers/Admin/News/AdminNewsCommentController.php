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
    // Hiển thị danh sách bình luận
    public function index(Request $request)
    {
        $query = NewsComment::with(['user', 'news'])
            ->whereNull('parent_id');

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

        $comments = $query->orderByDesc('created_at')->paginate(10);

        // Bổ sung dòng này để không lỗi $allNews
        $allNews = News::select('id', 'title')->get();

        return view('admin.news.news_comments.index', compact('comments', 'allNews'));
    }


    // Xoá bình luận
    public function destroy($id)
    {
        $comment = NewsComment::findOrFail($id);

        // Nếu là bình luận cha thì xóa luôn con
        NewsComment::where('parent_id', $comment->id)->delete();
        $comment->delete();

        return back()->with('success', 'Đã xoá bình luận và phản hồi.');
    }



    // Ẩn / Hiện bình luận
    public function toggleVisibility($id)
    {
        $comment = NewsComment::findOrFail($id);
        $newState = !$comment->is_hidden;
        $comment->update(['is_hidden' => $newState]);

        // Cập nhật tất cả bình luận con nếu là bình luận cha
        NewsComment::where('parent_id', $comment->id)->update(['is_hidden' => $newState]);

        return back()->with('success', 'Đã cập nhật trạng thái hiển thị.');
    }



    // Trả lời bình luận
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


    // Like / Unlike bình luận (dùng session để giả lập)
    public function toggleLike($id)
    {
        $likedComments = session()->get('liked_comments', []);
        if (in_array($id, $likedComments)) {
            $likedComments = array_diff($likedComments, [$id]);
        } else {
            $likedComments[] = $id;
        }

        session(['liked_comments' => $likedComments]);

        return redirect()->back()->with('success', 'Đã cập nhật lượt thích.');
    }

    public function like($id)
    {
        $comment = NewsComment::findOrFail($id);

        $sessionKey = 'liked_comment_' . $id;

        if (session()->has($sessionKey)) {
            return back()->with('error', 'Bạn đã thích bình luận này.');
        }

        // Tăng like
        $comment->increment('likes_count');

        // Ghi lại session để chặn like tiếp
        session()->put($sessionKey, true);

        return back()->with('success', 'Đã thích bình luận.');
    }
}
