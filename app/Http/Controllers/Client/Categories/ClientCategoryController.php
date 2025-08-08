<?php

namespace App\Http\Controllers\Client\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('client.categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        // Lấy tất cả sản phẩm trong danh mục này và các danh mục con (nếu có)
        $categoryIds = [$category->id];
        
        // Nếu là danh mục cha, lấy thêm ID của các danh mục con
        if ($category->children()->count() > 0) {
            $childrenIds = $category->children()->where('status', true)->pluck('id')->toArray();
            $categoryIds = array_merge($categoryIds, $childrenIds);
        }

        $products = Product::whereIn('category_id', $categoryIds)
            ->where('status', 1)
            ->with(['brand', 'category', 'productAllImages', 'variants'])
            ->paginate(12);

        // Lấy danh mục con để hiển thị sidebar
        $subcategories = $category->children()->where('status', true)->get();
        
        // Lấy tất cả danh mục cha để hiển thị breadcrumb và sidebar
        $parentCategories = Category::where('status', true)
            ->whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->where('status', true);
            }])
            ->get();

        return view('client.categories.show', compact(
            'category', 
            'products', 
            'subcategories', 
            'parentCategories'
        ));
    }
}
