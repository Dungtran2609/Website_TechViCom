<?php

namespace App\Http\Controllers\Client\Brands;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientBrandController extends Controller
{
	public function index()
	{
		$brands = Brand::where('status', true)
			->orderBy('name')
			->get();

		return view('client.brands.index', compact('brands'));
	}

	public function show($slug)
	{
		$brand = Brand::where('slug', $slug)
			->where('status', true)
			->firstOrFail();

		// Lấy tất cả sản phẩm thuộc brand này
		$products = Product::where('brand_id', $brand->id)
			->where('status', 1)
			->with(['brand', 'category', 'productAllImages', 'variants'])
			->paginate(12);

		// Lấy tất cả brand để hiển thị sidebar hoặc breadcrumb
		$allBrands = Brand::where('status', true)
			->orderBy('name')
			->get();

		return view('client.brands.show', compact(
			'brand',
			'products',
			'allBrands'
		));
	}
}
