<?php

namespace App\Http\Controllers\Client\news;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsCategory;

class ClientNewsController extends Controller
{
	// Trang tất cả bài viết
	public function index(Request $request)
	{
		if ($request->has('category')) {
			$news = News::where('status', 'published')
				->where('category_id', $request->input('category'))
				->orderByDesc('published_at')
				->get();
		} else {
			$news = News::where('status', 'published')
				->orderByDesc('published_at')
				->get();
		}
		// Lấy các danh mục có bài viết mới nhất lên đầu, tối đa 10 danh mục
		$showAllCategories = $request->input('show_all_categories');
		$categories = NewsCategory::with(['news' => function($q) {
			$q->where('status', 'published')->orderByDesc('published_at');
		}])
		->get()
		->sortByDesc(function($cat) {
			// Ưu tiên danh mục có bài viết mới nhất lên đầu, nếu không thì lấy ngày tạo danh mục
			return optional($cat->news->first())->published_at ?? $cat->created_at;
		});
		if (!$showAllCategories) {
			$categories = $categories->take(10);
		}
		$allCategoriesCount = NewsCategory::count();
		$featuredNews = News::where('status', 'published')
			->withCount('comments')
			->withSum('comments as likes_count', 'likes_count')
			->orderByDesc('likes_count')
			->orderByDesc('comments_count')
			->take(5)
			->get();
	return view('client.news.index', compact('news', 'categories', 'featuredNews', 'allCategoriesCount'));
	}

	// Trang chi tiết bài viết
	public function show($id)
	{
		$news = News::where('status', 'published')
			->with([
				'comments' => function ($q) {
					$q->where('is_hidden', false)->whereNull('parent_id')->with([
						'user',
						'children' => function ($q2) {
							$q2->where('is_hidden', false)->with('user');
						}
					]);
				},
				'category',
				'author'
			])
			->findOrFail($id);
		$related = News::where('status', 'published')
			->where('id', '!=', $news->id)
			->orderByDesc('published_at')
			->limit(4)
			->get();
		// Đếm số bình luận hiển thị (không bị ẩn, parent_id=null)
		$visibleCommentCount = $news->comments->count();
		return view('client.news.show', compact('news', 'related', 'visibleCommentCount'));
	}
	// Lưu bình luận mới
	public function storeComment(Request $request, $id)
	{
		$request->validate([
			'content' => 'required|string|max:3000',
		]);
		$news = News::findOrFail($id);
		$comment = $news->comments()->create([
			'user_id' => auth()->id() ?? null,
			'content' => $request->input('content'),
			'parent_id' => null,
		]);
		return redirect()->back()->with('success', 'Bình luận đã được gửi!');
	}

	// Like bình luận: chỉ cho phép like 1 lần mỗi session (giống admin)
	public function likeComment($id)
	{
		$comment = \App\Models\NewsComment::findOrFail($id);
		$sessionKey = 'liked_comment_' . $id;
		if (session()->has($sessionKey)) {
			return redirect()->back()->with('error', 'Bạn đã thích bình luận này!');
		}
		$comment->increment('likes_count');
		session()->put($sessionKey, true);
		return redirect()->back()->with('success', 'Đã like bình luận!');
	}

	// Trả lời bình luận
	public function replyComment(Request $request, $id)
	{
		$request->validate([
			'content' => 'required|string|max:3000',
		]);
		$parent = \App\Models\NewsComment::findOrFail($id);
		$reply = $parent->news->comments()->create([
			'user_id' => auth()->id() ?? null,
			'content' => $request->input('content'),
			'parent_id' => $parent->id,
		]);
		return redirect()->back()->with('success', 'Phản hồi đã được gửi!');
	}
}
