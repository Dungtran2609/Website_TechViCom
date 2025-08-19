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

        // Lọc theo nhiều thương hiệu (brands)
        if ($request->has('brands') && $request->brands) {
            $brandSlugs = is_array($request->brands) ? $request->brands : explode(',', $request->brands);
            $brandIds = Brand::whereIn('slug', $brandSlugs)->pluck('id')->toArray();
            if (!empty($brandIds)) {
                $query->whereIn('brand_id', $brandIds);
            }
        } else if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }

        // Lọc theo nhiều RAM (attribute_id = 2)
        if ($request->has('ram') && $request->ram) {
            $rams = is_array($request->ram) ? $request->ram : explode(',', $request->ram);
            $query->whereHas('variants', function ($variant) use ($rams) {
                $variant->whereHas('attributeValues', function ($attr) use ($rams) {
                    $attr->where('attribute_id', 2)->whereIn('value', $rams);
                });
            });
        }

        // Lọc theo nhiều Storage (attribute_id = 3)
        if ($request->has('storage') && $request->storage) {
            $storages = is_array($request->storage) ? $request->storage : explode(',', $request->storage);
            $query->whereHas('variants.attributeValues', function ($q) use ($storages) {
                $q->where('attribute_id', 3)->whereIn('value', $storages);
            });
        }

        // Lọc theo rating trung bình đánh giá >= x sao
        if ($request->has('rating') && $request->rating) {
            $query->whereHas('productComments', function ($q) use ($request) {
                $q->selectRaw('product_id, AVG(rating) as avg_rating')->groupBy('product_id')->havingRaw('AVG(rating) >= ?', [$request->rating]);
            });
        }
        // Tìm kiếm theo tên, danh mục, thương hiệu
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('category', function ($cat) use ($search) {
                        $cat->where('name', 'like', "%$search%")
                            ->orWhere('slug', 'like', "%$search%");
                    })
                    ->orWhereHas('brand', function ($brand) use ($search) {
                        $brand->where('name', 'like', "%$search%")
                            ->orWhere('slug', 'like', "%$search%");
                    });
            });
        }

        // Lọc theo khoảng giá: chỉ whereHas sang product_variants cho cả simple và variable product
        if (($request->has('min_price') && $request->min_price) || ($request->has('max_price') && $request->max_price)) {
            $min = $request->min_price;
            $max = $request->max_price;
            $query->whereHas('variants', function ($v) use ($min, $max) {
                if ($min) $v->where('price', '>=', $min);
                if ($max) $v->where('price', '<=', $max);
            });
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

        // Tăng view_count mỗi khi vào chi tiết sản phẩm
        $product->increment('view_count');

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
