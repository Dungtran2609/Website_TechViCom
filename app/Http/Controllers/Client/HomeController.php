<?php

namespace App\Http\Controllers\Client;

use App\Models\News;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        // Debug authentication
        $user = Auth::user();
        Log::info('Home page access', [
            'is_authenticated' => Auth::check(),
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ] : null,
            'session_id' => session()->getId()
        ]);
        
        // Tự động xóa session thanh toán lại khi vào trang chủ
        if (session()->has('repayment_order_id')) {
            session()->forget('repayment_order_id');
            session()->forget('show_repayment_message');
            session()->forget('force_cod_for_order_id');
            session()->forget('payment_cancelled_message');
            Log::info('Auto-cleared repayment session on home page access');
        }
        
        // Lấy banner đang hoạt động
        $banners = Banner::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('stt')
            ->get();

        // Sản phẩm nổi bật: gắn cờ is_featured, trạng thái active
        // Lấy chương trình flash sale đang diễn ra (cả kiểu flash_sale và category)
        $now = now();
        $flashSale = Promotion::whereIn('flash_type', ['flash_sale', 'category'])
            ->where('status', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orderBy('start_date', 'asc')
            ->first();

        $featuredProducts = Product::with(['brand', 'category', 'productAllImages', 'variants', 'productComments'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->whereHas('brand', function ($q) {
                $q->where('status', 1);
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($product) use ($flashSale) {
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
                        if ($promoProduct && $promoProduct->pivot) {
                            // Ưu tiên giá cố định, nếu không có thì dùng phần trăm
                            if ($promoProduct->pivot->sale_price && $promoProduct->pivot->sale_price > 0) {
                                $salePrice = $promoProduct->pivot->sale_price;
                            } elseif ($promoProduct->pivot->discount_percent && $promoProduct->pivot->discount_percent > 0 && $price) {
                                $salePrice = $price * (1 - $promoProduct->pivot->discount_percent / 100);
                            }
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
            ->whereHas('brand', function ($q) {
                $q->where('status', 1);
            })
            ->orderByDesc('view_count')
            ->limit(10)
            ->get()
            ->map(function ($product) use ($flashSale) {
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
                        if ($promoProduct && $promoProduct->pivot) {
                            // Ưu tiên giá cố định, nếu không có thì dùng phần trăm
                            if ($promoProduct->pivot->sale_price && $promoProduct->pivot->sale_price > 0) {
                                $salePrice = $promoProduct->pivot->sale_price;
                            } elseif ($promoProduct->pivot->discount_percent && $promoProduct->pivot->discount_percent > 0 && $price) {
                                $salePrice = $price * (1 - $promoProduct->pivot->discount_percent / 100);
                            }
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
                $flashSaleProducts = Product::with(['variants', 'productComments'])
                    ->where('status', 'active')
                    ->whereIn('category_id', $allCategoryIds)
                    ->get()
                    ->map(function ($product) use ($flashSale) {
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
                $flashSaleProducts = $flashSale->products()->with(['variants', 'productComments'])->get()->map(function ($product) use ($flashSale) {
                    $variant = $product->variants->first();
                    $price = $variant ? $variant->price : null;
                    $salePrice = null;
                    
                    // Ưu tiên giá cố định, nếu không có thì dùng phần trăm
                    if ($product->pivot->sale_price && $product->pivot->sale_price > 0) {
                        $salePrice = $product->pivot->sale_price;
                    } elseif ($product->pivot->discount_percent && $product->pivot->discount_percent > 0 && $price) {
                        $salePrice = $price * (1 - $product->pivot->discount_percent / 100);
                    }
                    
                    $discountPercent = ($price && $salePrice && $price > 0) ? round(100 * ($price - $salePrice) / $price) : 0;
                    $product->flash_sale_price = $salePrice;
                    $product->discount_percent = $discountPercent;
                    return $product;
                });
            }
        }

    // Lấy bài viết theo giờ mới nhất lên đầu (không quan tâm ngày)
    $latestNews = News::orderByRaw('TIME(created_at) DESC')->limit(4)->get();

        // Lấy danh sách sản phẩm yêu thích của user (nếu đã đăng nhập)
        $favoriteProductIds = [];
        if (Auth::check()) {
            $favoriteProductIds = Auth::user()->favoriteProducts()->pluck('product_id')->toArray();
        }

        return view('client.home', compact(
            'banners',
            'featuredProducts',
            'hotProducts',
            'categories',
            'brands',
            'latestNews',
            'flashSaleProducts',
            'flashSaleEndTime',
            'favoriteProductIds'
        ));
    }
}