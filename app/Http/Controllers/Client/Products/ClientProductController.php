<?php

namespace App\Http\Controllers\Client\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with([
                'brand',
                'category',
                'productAllImages',
                // cần cho lọc hiển thị giá theo biến thể ở view
                'variants.attributeValues'
            ])
            // thêm avg_rating & reviews_count để hiển thị và lọc theo sao
            ->withAvg(['productComments as avg_rating' => function ($q) {
                $q->whereNotNull('rating')->where('status', 'approved');
            }], 'rating')
            ->withCount(['productComments as reviews_count' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->where('status', 1);

        // Lấy danh sách sản phẩm yêu thích của user (nếu đã đăng nhập)
        $favoriteProductIds = [];
        if (Auth::check()) {
            $favoriteProductIds = Auth::user()->favoriteProducts()->pluck('product_id')->toArray();
        }

        // Lọc theo danh mục (slug hoặc id) - hỗ trợ nhiều danh mục và phân cấp
        if ($request->filled('category')) {
            $categorySlugs = is_array($request->category) ? $request->category : explode(',', $request->category);
            $categoryIds = [];
            
            foreach ($categorySlugs as $slug) {
                if (is_numeric($slug)) {
                    $categoryIds[] = $slug;
                } else {
                    $category = Category::where('slug', $slug)->first();
                    if ($category) {
                        $categoryIds[] = $category->id;
                        
                        // Nếu là danh mục cha, thêm cả danh mục con
                        if ($category->children()->count() > 0) {
                            $childrenIds = $category->children()->where('status', true)->pluck('id')->toArray();
                            $categoryIds = array_merge($categoryIds, $childrenIds);
                        }
                    }
                }
            }
            
            if (!empty($categoryIds)) {
                $categoryIds = array_unique($categoryIds);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Lọc theo nhiều thương hiệu (brands = danh sách slug)
        if ($request->filled('brands')) {
            $brandSlugs = is_array($request->brands) ? $request->brands : explode(',', $request->brands);
            $brandIds = Brand::whereIn('slug', $brandSlugs)->pluck('id')->all();
            if (!empty($brandIds)) {
                $query->whereIn('brand_id', $brandIds);
            }
        } elseif ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Lọc theo nhiều RAM (attribute_id = 2)
        if ($request->filled('ram')) {
            $rams = is_array($request->ram) ? $request->ram : explode(',', $request->ram);
            $rams = array_map('strval', $rams); // 4,6,8,...
            $query->whereHas('variants.attributeValues', function ($q) use ($rams) {
                $q->where('attribute_id', 2)
                    ->whereIn(DB::raw("REPLACE(LOWER(value),'gb','')"), $rams);
            });
        }

        // Lọc theo nhiều Storage (attribute_id = 3)
        if ($request->filled('storage')) {
            $storages = is_array($request->storage) ? $request->storage : explode(',', $request->storage);
            // chuẩn hoá "1TB" -> 1024, "256GB"->256 để so sánh thống nhất
            $storages = array_map(function ($v) {
                $v = strtolower((string)$v);
                return str_contains($v, 'tb') ? (string)((int)str_replace('tb', '', $v) * 1024) : (string)((int)str_replace('gb', '', $v));
            }, $storages);

            $query->whereHas('variants.attributeValues', function ($q) use ($storages) {
                $q->where('attribute_id', 3)
                    ->whereIn(DB::raw("
                        CASE
                            WHEN LOWER(value) LIKE '%tb' THEN CAST(REPLACE(LOWER(value),'tb','') AS UNSIGNED) * 1024
                            ELSE CAST(REPLACE(LOWER(value),'gb','') AS UNSIGNED)
                        END
                  "), $storages);
            });
        }

        // Lọc theo SAO: 1,2,3,4,5 (theo khoảng AVG)
        if ($request->filled('rating')) {
            $star = (int)$request->rating;
            if ($star >= 1 && $star <= 5) {
                // dùng alias avg_rating do withAvg sinh ra
                $query->having('avg_rating', '>=', $star);
                if ($star < 5) {
                    $query->having('avg_rating', '<', $star + 1);
                }
            }
        }

        // Tìm kiếm
        if ($request->filled('search')) {
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

        // Lọc theo khoảng GIÁ (dựa trên giá hiệu lực của variant: sale_price nếu < price, ngược lại price)
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $min = $request->min_price;
            $max = $request->max_price;

            $query->whereHas('variants', function ($v) use ($min, $max) {
                if ($min) {
                    $v->whereRaw('(CASE WHEN sale_price IS NOT NULL AND sale_price < price THEN sale_price ELSE price END) >= ?', [$min]);
                }
                if ($max) {
                    $v->whereRaw('(CASE WHEN sale_price IS NOT NULL AND sale_price < price THEN sale_price ELSE price END) <= ?', [$max]);
                }
            });
        }

        // Sắp xếp (giữ như bạn đang dùng; nếu muốn sort theo giá biến thể, mình có thể nâng cấp sau)
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

        $products   = $query->paginate(12);
        $categories = Category::where('status', 1)
            ->with(['children' => function($q) {
                $q->where('status', 1);
            }])
            ->get();
        $brands     = Brand::where('status', 1)->get();
        $attributes = Attribute::with('attributeValues')->get();

        $currentCategory = null;
        if ($request->filled('category') && !is_numeric($request->category)) {
            $currentCategory = Category::where('slug', $request->category)->first();
        }

        return view('client.products.index', compact(
            'products',
            'categories',
            'brands',
            'attributes',
            'currentCategory',
            'favoriteProductIds'
        ));
    }

    public function show($id)
    {
        $product = Product::with([
            'brand',
            'category',
            'productAllImages',
            'variants.attributeValues.attribute',
            'productComments' => function ($q) {
                $q->where('status', 'approved');
            },
            'productComments.user'
        ])->findOrFail($id);

        // Tăng view_count
        $product->increment('view_count');

        // Kiểm tra flash sale đang diễn ra (theo sản phẩm hoặc danh mục)
        $now = now();
        $flashSale = \App\Models\Promotion::whereIn('flash_type', ['flash_sale', 'category'])
            ->where('status', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orderBy('start_date', 'asc')
            ->first();

        $flashSaleInfo = null;
        if ($flashSale) {
            if ($flashSale->flash_type === 'flash_sale') {
                // Kiểm tra sản phẩm có trong promotion_product không
                $promoProduct = $flashSale->products()->where('products.id', $product->id)->first();
                if ($promoProduct && $promoProduct->pivot) {
                    $variant = $product->variants->first();
                    $price = $variant ? $variant->price : null;
                    $salePrice = null;
                    
                    // Ưu tiên giá cố định, nếu không có thì dùng phần trăm
                    if ($promoProduct->pivot->sale_price && $promoProduct->pivot->sale_price > 0) {
                        $salePrice = $promoProduct->pivot->sale_price;
                    } elseif ($promoProduct->pivot->discount_percent && $promoProduct->pivot->discount_percent > 0 && $price) {
                        $salePrice = $price * (1 - $promoProduct->pivot->discount_percent / 100);
                    }
                    
                    if ($salePrice) {
                        $discountPercent = ($price && $salePrice && $price > 0) ? round(100 * ($price - $salePrice) / $price) : 0;
                        $flashSaleInfo = [
                            'sale_price' => $salePrice,
                            'discount_percent' => $discountPercent,
                            'promotion' => $flashSale
                        ];
                    }
                }
            } elseif ($flashSale->flash_type === 'category') {
                // Kiểm tra sản phẩm thuộc danh mục được áp dụng
                $categoryIds = $flashSale->categories->pluck('id')->toArray();
                $allCategoryIds = $categoryIds;
                foreach ($flashSale->categories as $cat) {
                    $childIds = $cat->children()->pluck('id')->toArray();
                    $allCategoryIds = array_merge($allCategoryIds, $childIds);
                }
                $allCategoryIds = array_unique($allCategoryIds);
                if (in_array($product->category_id, $allCategoryIds)) {
                    // Tính giá giảm theo discount_type/value
                    $variant = $product->variants->first();
                    $price = $variant ? $variant->price : null;
                    $salePrice = null;
                    if ($price) {
                        if ($flashSale->discount_type === 'percent') {
                            $salePrice = $price * (1 - $flashSale->discount_value / 100);
                        } elseif ($flashSale->discount_type === 'amount') {
                            $salePrice = max(0, $price - $flashSale->discount_value);
                        }
                    }
                    $discountPercent = ($price && $salePrice && $price > 0) ? round(100 * ($price - $salePrice) / $price) : 0;
                    $flashSaleInfo = [
                        'sale_price' => $salePrice,
                        'discount_percent' => $discountPercent,
                        'promotion' => $flashSale
                    ];
                }
            }
        }

        // Sản phẩm liên quan
        $relatedProducts = Product::with(['brand', 'category', 'productAllImages', 'variants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->limit(4)
            ->get();

        // Lấy danh sách sản phẩm yêu thích của user (nếu đã đăng nhập)
        $favoriteProductIds = [];
        if (Auth::check()) {
            $favoriteProductIds = Auth::user()->favoriteProducts()->pluck('product_id')->toArray();
        }

        return view('client.products.show', compact('product', 'relatedProducts', 'flashSaleInfo', 'favoriteProductIds'));
    }

    public function love(Request $request)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            // Thay vì redirect, hiển thị trang với thông báo đăng nhập
            $products = collect();
            $notLoggedIn = true;
            
            return view('client.products.love', compact('products', 'notLoggedIn'));
        }

        // Lấy danh sách sản phẩm yêu thích
        $favorites = Auth::user()->favoriteProducts()
            ->with(['product.brand', 'product.category', 'product.productAllImages', 'product.variants.attributeValues', 'product.productComments'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Tính toán avg_rating và reviews_count cho mỗi sản phẩm
        $favorites->transform(function ($favorite) {
            $product = $favorite->product;
            $approvedComments = $product->productComments->where('status', 'approved')->whereNotNull('rating');
            $avgRating = $approvedComments->count() > 0 ? $approvedComments->avg('rating') : 0;
            $reviewsCount = $product->productComments->where('status', 'approved')->count();
            $product->avg_rating = $avgRating;
            $product->reviews_count = $reviewsCount;
            return $favorite;
        });

        // Lấy danh sách sản phẩm từ favorites để hiển thị
        $products = $favorites->pluck('product');

        $notLoggedIn = false;

        return view('client.products.love', compact('products', 'notLoggedIn'));
    }
}
