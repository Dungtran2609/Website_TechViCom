<?php

namespace App\Http\Controllers\Client\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use Illuminate\Http\Request;

class ClientProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'productAllImages'])
                       ->where('status', 1);

        // Lọc theo danh mục (hỗ trợ cả slug và id)
        if ($request->has('category') && $request->category) {
            if (is_numeric($request->category)) {
                // Nếu là số thì lọc theo ID
                $query->where('category_id', $request->category);
            } else {
                // Nếu không phải số thì lọc theo slug
                $category = Category::where('slug', $request->category)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Lọc theo thương hiệu
        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }

        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo giá
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        // Lấy dữ liệu cho bộ lọc
        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();
        $attributes = Attribute::with('attributeValues')->get();

        // Lấy thông tin danh mục hiện tại (nếu có)
        $currentCategory = null;
        if ($request->has('category') && $request->category && !is_numeric($request->category)) {
            $currentCategory = Category::where('slug', $request->category)->first();
        }

        return view('client.products.index', compact(
            'products',
            'categories',
            'brands',
            'attributes',
            'currentCategory'
        ));
    }

    public function show($id)
    {
        $product = Product::with([
            'brand',
            'category',
            'productAllImages',
            'variants.attributeValues.attribute',
            'productComments.user'
        ])->findOrFail($id);

        // Sản phẩm liên quan
        $relatedProducts = Product::with(['brand', 'category', 'productAllImages', 'variants'])
                                 ->where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('status', 1)
                                 ->limit(4)
                                 ->get();

        return view('client.products.show', compact('product', 'relatedProducts'));
    }
}
