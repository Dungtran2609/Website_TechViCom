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
            ->get();

        // Lấy sản phẩm mới nhất
        $latestProducts = Product::with(['brand', 'category', 'productAllImages', 'variants'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Lấy sản phẩm bán chạy (giả sử theo số lượng đã bán)
        $popularProducts = Product::with(['brand', 'category', 'productAllImages', 'variants'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

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

        // Lấy 4 bài viết mới nhất
        $latestNews = \App\Models\News::where('status', 'published')
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        return view('client.home', compact(
            'banners',
            'latestProducts',
            'popularProducts',
            'categories',
            'brands',
            'latestNews'
        ));
    }
}
