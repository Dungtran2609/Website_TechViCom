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

        // Sản phẩm nổi bật: gắn cờ is_featured, trạng thái active
        $featuredProducts = Product::with(['brand', 'category', 'productAllImages', 'variants'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // Sản phẩm hot: nhiều lượt xem nhất, trạng thái active
        $hotProducts = Product::with(['brand', 'category', 'productAllImages', 'variants'])
            ->where('status', 'active')
            ->orderByDesc('view_count')
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

        return view('client.home', compact(
            'banners',
            'featuredProducts',
            'hotProducts',
            'categories',
            'brands'
        ));
    }
}
