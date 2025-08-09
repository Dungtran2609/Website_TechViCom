<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminNewsRequest;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['category', 'author']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

    $news = $query->orderByDesc('published_at')->paginate(12);

        return view('admin.news.index', compact('news'));
    }


    public function create()
    {
        $categories = NewsCategory::all();
        return view('admin.news.create', compact('categories'));
    }

    public function store(AdminNewsRequest $request)
    {
        $data = $request->validated();

        if (empty($data['published_at']) && $data['status'] === 'published') {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/news'), $filename);
            $data['image'] = 'uploads/news/' . $filename;
        }

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được tạo thành công.');
    }

    public function edit(News $news)
    {
        $categories = NewsCategory::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(AdminNewsRequest $request, News $news)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $file->move(public_path('uploads/news'), $filename);
            $data['image'] = 'uploads/news/' . $filename;
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được cập nhật thành công.');
    }

    public function show(News $news)
    {
        $news->load([
            'comments' => function ($q) {
                $q->where('is_hidden', false)->with([
                    'user',
                    'children' => function ($q2) {
                        $q2->where('is_hidden', false)->with('user');
                    }
                ]);
            },
            'category',
            'author'
        ]);
        return view('admin.news.show', compact('news'));
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được chuyển vào thùng rác.');
    }

    public function trash(Request $request)
    {
        $query = News::onlyTrashed()->with(['category', 'author']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $query->orderBy('deleted_at', 'desc')->paginate(10);

        return view('admin.news.trash', compact('news'));
    }

    public function restore($id)
    {
        $news = News::onlyTrashed()->findOrFail($id);
        $news->restore();

        return redirect()->route('admin.news.index')->with('success', 'Bài viết đã được khôi phục.');
    }

    public function forceDelete($id)
    {
        $news = News::onlyTrashed()->findOrFail($id);

        if ($news->image && file_exists(public_path($news->image))) {
            unlink(public_path($news->image));
        }

        $news->forceDelete();
        return redirect()->route('admin.news.trash')->with('success', 'Bài viết đã bị xoá vĩnh viễn.');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/news', $filename, 'public');

            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'message' => 'Không có file nào được upload.'
        ], 400);
    }
}
