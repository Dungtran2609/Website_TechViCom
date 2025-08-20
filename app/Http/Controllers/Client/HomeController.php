<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy banner đang hoạt động
        $banners = Banner::where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->orderBy('stt')
                        ->limit(3)
                        ->get();

        // Sản phẩm nổi bật: gắn cờ is_featured, trạng thái active
        // Lấy chương trình flash sale đang diễn ra (cả kiểu flash_sale và category)
        $now = now();
        $flashSale = \App\Models\Promotion::whereIn('flash_type', ['flash_sale', 'category'])
            ->where('status', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orderBy('start_date', 'asc')
            ->first();

        $featuredProducts = Product::with(['brand', 'category', 'productAllImages', 'variants', 'productComments'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function($product) use ($flashSale) {
                $variant = $product->variants->first();
                $price = $variant ? $variant->price : null;
                $salePrice = null;
                $discountPercent = 0;
                if ($flashSale) {
                    if ($flashSale->flash_type === 'category') {
                        $categoryIds = $flashSale->categories->pluck('id')->toArray();
                        $allCategoryIds = $categoryIds;
                        foreach ($flashSale->categories as $cat) {
                            $childIds = $cat->children()->pluck('id')->toArray();
                            $allCategoryIds = array_merge($allCategoryIds, $childIds);
                        }
                        $allCategoryIds = array_unique($allCategoryIds);
                        if (in_array($product->category_id, $allCategoryIds) && $price) {
                            if ($flashSale->discount_type === 'percent') {
                                $salePrice = $price * (1 - $flashSale->discount_value / 100);
                            } elseif ($flashSale->discount_type === 'amount') {
                                $salePrice = max(0, $price - $flashSale->discount_value);
                            }
                        }
                    } elseif ($flashSale->flash_type === 'flash_sale') {
                        $promoProduct = $flashSale->products()->where('products.id', $product->id)->first();
                        if ($promoProduct && $promoProduct->pivot && $promoProduct->pivot->sale_price) {
                            $salePrice = $promoProduct->pivot->sale_price;
                        }
                    }
                }
                if ($price && $salePrice && $price > 0) {
                    $discountPercent = round(100 * ($price - $salePrice) / $price);
                }
                $product->flash_sale_price = $salePrice;
                $product->discount_percent = $discountPercent;
                return $product;
            });

        $hotProducts = Product::with(['brand', 'category', 'productAllImages', 'variants', 'productComments'])
            ->where('status', 'active')
            ->orderByDesc('view_count')
            ->limit(10)
            ->get()
            ->map(function($product) use ($flashSale) {
                $variant = $product->variants->first();
                $price = $variant ? $variant->price : null;
                $salePrice = null;
                $discountPercent = 0;
                if ($flashSale) {
                    if ($flashSale->flash_type === 'category') {
                        $categoryIds = $flashSale->categories->pluck('id')->toArray();
                        $allCategoryIds = $categoryIds;
                        foreach ($flashSale->categories as $cat) {
                            $childIds = $cat->children()->pluck('id')->toArray();
                            $allCategoryIds = array_merge($allCategoryIds, $childIds);
                        }
                        $allCategoryIds = array_unique($allCategoryIds);
                        if (in_array($product->category_id, $allCategoryIds) && $price) {
                            if ($flashSale->discount_type === 'percent') {
                                $salePrice = $price * (1 - $flashSale->discount_value / 100);
                            } elseif ($flashSale->discount_type === 'amount') {
                                $salePrice = max(0, $price - $flashSale->discount_value);
                            }
                        }
                    } elseif ($flashSale->flash_type === 'flash_sale') {
                        $promoProduct = $flashSale->products()->where('products.id', $product->id)->first();
                        if ($promoProduct && $promoProduct->pivot && $promoProduct->pivot->sale_price) {
                            $salePrice = $promoProduct->pivot->sale_price;
                        }
                    }
                }
                if ($price && $salePrice && $price > 0) {
                    $discountPercent = round(100 * ($price - $salePrice) / $price);
                }
                $product->flash_sale_price = $salePrice;
                $product->discount_percent = $discountPercent;
                return $product;
            });

        // Lấy danh mục cha và con
        $categories = Category::where('status', 1)
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->where('status', 1)->limit(5);
            }])
            ->limit(6)
            ->get();

        // Lấy thương hiệu
        $brands = Brand::where('status', 1)
                      ->limit(8)
                      ->get();

        // Lấy chương trình flash sale đang diễn ra (cả kiểu flash_sale và category) - đã lấy ở trên
        $flashSaleProducts = [];
        $flashSaleEndTime = null;
        if ($flashSale) {
            $flashSaleEndTime = $flashSale->end_date;
            if ($flashSale->flash_type === 'category') {
                $categoryIds = $flashSale->categories->pluck('id')->toArray();
                $allCategoryIds = $categoryIds;
                foreach ($flashSale->categories as $cat) {
                    $childIds = $cat->children()->pluck('id')->toArray();
                    $allCategoryIds = array_merge($allCategoryIds, $childIds);
                }
                $allCategoryIds = array_unique($allCategoryIds);
                $flashSaleProducts = \App\Models\Product::with(['variants', 'productComments'])
                    ->where('status', 'active')
                    ->whereIn('category_id', $allCategoryIds)
                    ->get()
                    ->map(function($product) use ($flashSale) {
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
                        $product->flash_sale_price = $salePrice;
                        $product->discount_percent = $discountPercent;
                        return $product;
                    });
            } else {
                $flashSaleProducts = $flashSale->products()->with(['variants', 'productComments'])->get()->map(function($product) use ($flashSale) {
                    $salePrice = $product->pivot->sale_price ?? null;
                    $variant = $product->variants->first();
                    $price = $variant ? $variant->price : null;
                    $discountPercent = ($price && $salePrice && $price > 0) ? round(100 * ($price - $salePrice) / $price) : 0;
                    $product->flash_sale_price = $salePrice;
                    $product->discount_percent = $discountPercent;
                    return $product;
                });
            }
        }

        // Lấy bài viết mới nhất
        $latestNews = \App\Models\News::orderByDesc('created_at')->limit(4)->get();

        return view('client.home', compact(
            'banners',
            'featuredProducts',
            'hotProducts',
            'categories',
            'brands',
            'latestNews',
            'flashSaleProducts',
            'flashSaleEndTime'
        ));
    }
}
