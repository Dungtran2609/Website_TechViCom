<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\AdminCategoryRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $query = Category::with('parent');

        if (request()->has('search')) {
            $search = request('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Sắp xếp theo mới nhất
        $query->orderBy('id', 'desc');

        $categories = $query->paginate(5)->withQueryString();

        return view('admin.products.categories.index', compact('categories'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('admin.products.categories.create', compact('categories'));
    }

    public function store(AdminCategoryRequest $request)
    {
        $imagePath = $request->file('image')
            ? $request->file('image')->store('categories', 'public')
            : null;

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.categories.index')
            ->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.products.categories.edit', compact('category', 'categories'));
    }

    public function update(AdminCategoryRequest $request, Category $category)
    {
        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.categories.index')
            ->with('success', 'Danh mục đã được cập nhật.');
    }

    public function destroy(Category $category)
    {
        // Thêm: Kiểm tra xem danh mục có sản phẩm không
        if ($category->products()->exists()) {
            return redirect()->route('admin.products.categories.index')
                ->with('error', 'Không thể xóa danh mục này vì vẫn còn sản phẩm.');
        }

        // Thêm: Kiểm tra xem danh mục có danh mục con không
        if ($category->children()->exists()) {
            return redirect()->route('admin.products.categories.index')
                ->with('error', 'Không thể xóa danh mục này vì nó chứa danh mục con.');
        }

        $category->delete(); // Soft delete
        return redirect()->route('admin.products.categories.index')
            ->with('success', 'Danh mục đã được chuyển vào thùng rác.');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children']);
        return view('admin.products.categories.show', compact('category'));
    }

    // Hiển thị danh mục đã bị xoá mềm (thùng rác)
    public function trashed()
    {
        $categories = Category::onlyTrashed()->with('parent')->orderBy('deleted_at', 'desc')->paginate(5);
        return view('admin.products.categories.trashed', compact('categories'));
    }

    // Khôi phục danh mục đã bị xoá mềm
    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id);
        if ($category) {
            $category->restore();
            return redirect()->route('admin.products.categories.trashed')->with('success', 'Khôi phục thành công.');
        }
        return redirect()->route('admin.products.categories.trashed')->with('error', 'Không tìm thấy danh mục.');
    }

    // Xoá vĩnh viễn
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->find($id);
        if ($category) {
            // Thêm: Kiểm tra lại sản phẩm trước khi xóa vĩnh viễn
            if ($category->products()->exists()) {
                return redirect()->route('admin.products.categories.trashed')
                    ->with('error', 'Không thể xóa vĩnh viễn. Vẫn còn sản phẩm thuộc danh mục này.');
            }

            // Xoá ảnh nếu có
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $category->forceDelete();
            return redirect()->route('admin.products.categories.trashed')->with('success', 'Đã xoá vĩnh viễn.');
        }
        return redirect()->route('admin.products.categories.trashed')->with('error', 'Không tìm thấy danh mục.');
    }
}