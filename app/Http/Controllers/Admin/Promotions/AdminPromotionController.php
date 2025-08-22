<?php
namespace App\Http\Controllers\Admin\Promotions;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Promotions\PromotionRequest;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminPromotionController extends Controller
{
    public function index()
    {
        $query = Promotion::with(['coupons']);
        
        // Filter by keyword
        if (request('q')) {
            $q = request('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%")
                    ->orWhere('id', $q);
            });
        }

        // Filter by flash type
        if (request('flash_type')) {
            $query->where('flash_type', request('flash_type'));
        }

        // Filter by status
        if (request('status') !== null && request('status') !== '') {
            $query->where('status', request('status'));
        }

        // Filter by date from
        if (request('date_from')) {
            $query->where('start_date', '>=', request('date_from'));
        }

        $promotions = $query->latest()->paginate(15)->appends(request()->query());
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
    $categories = Category::all();
    $products = Product::all();
    $coupons = \App\Models\Coupon::whereNull('promotion_id')->orWhere('promotion_id', 0)->get();
    return view('admin.promotions.create', compact('categories', 'products', 'coupons'));
    }

    public function store(PromotionRequest $request)
    {
    $data = $request->validated();
        // Tạo slug duy nhất (kiểm tra cả soft deleted records)
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 1;
        while (\Illuminate\Support\Facades\DB::table('promotions')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
            // Tránh vòng lặp vô hạn
            if ($i > 100) {
                $slug = $baseSlug . '-' . time();
                break;
            }
        }
        $data['slug'] = $slug;
        // Nếu là kiểu category thì lưu discount_value và cập nhật sale_price cho các sản phẩm thuộc danh mục
        if ($data['flash_type'] === 'category') {
            $data['discount_type'] = 'percent';
            $data['discount_value'] = $request->category_discount_value ?? 10;
        }
        $promotion = Promotion::create($data);
        // Gán sản phẩm/danh mục nếu có
        if ($data['flash_type'] === 'category' && !empty($request->categories)) {
            $promotion->categories()->sync($request->categories);
            // Tự động cập nhật sale_price cho các sản phẩm thuộc danh mục
            $categoryIds = $request->categories;
            $productIds = \App\Models\Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
            $discountPercent = $data['discount_value'];
            foreach ($productIds as $pid) {
                $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                foreach ($variants as $variant) {
                    if (is_null($variant->old_sale_price)) {
                        $variant->old_sale_price = $variant->sale_price;
                    }
                    $variant->sale_price = round($variant->price * (1 - $discountPercent / 100));
                    $variant->save();
                }
            }
        }
        if ($data['flash_type'] === 'flash_sale' && !empty($request->products) && $data['status'] == 1) {
            $syncData = [];
            foreach ($request->products as $pid) {
                $syncData[$pid] = [
                    'sale_price' => isset($request->sale_prices[$pid]) ? $request->sale_prices[$pid] : null,
                    'discount_percent' => isset($request->discount_percents[$pid]) ? $request->discount_percents[$pid] : null
                ];
                
                // Lưu giá giảm cũ trước khi cập nhật giá flash sale
                \App\Models\ProductVariant::where('product_id', $pid)
                    ->whereNull('old_sale_price')
                    ->update(['old_sale_price' => DB::raw('sale_price')]);
                
                // Cập nhật sale_price cho tất cả variant của sản phẩm này
                if (isset($request->sale_prices[$pid]) && $request->sale_prices[$pid] > 0) {
                    // Nếu có giá cố định
                    \App\Models\ProductVariant::where('product_id', $pid)->update(['sale_price' => $request->sale_prices[$pid]]);
                } elseif (isset($request->discount_percents[$pid]) && $request->discount_percents[$pid] > 0) {
                    // Nếu có phần trăm giảm giá
                    $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                    foreach ($variants as $variant) {
                        $discountedPrice = round($variant->price * (1 - $request->discount_percents[$pid] / 100));
                        $variant->sale_price = $discountedPrice;
                        $variant->save();
                    }
                }
            }
            $promotion->products()->sync($syncData);
        } else {
            // Nếu không phải flash_sale hoặc không kích hoạt thì không cập nhật sale_price, chỉ sync products
            $promotion->products()->sync($request->products ?? []);
        }
        // Gán coupon cho promotion
        if ($request->coupons) {
            \App\Models\Coupon::whereIn('id', $request->coupons)->update(['promotion_id' => $promotion->id]);
        }
        return redirect()->route('admin.promotions.index')->with('success', 'Tạo chương trình thành công!');
    }

    public function edit($id)
    {
                $promotion = Promotion::with(['categories', 'products', 'coupons'])->findOrFail($id);
                $categories = Category::all();
                $products = Product::all();
                $coupons = \App\Models\Coupon::where(function($q) use ($promotion) {
                        $q->whereNull('promotion_id')
                            ->orWhere('promotion_id', 0)
                            ->orWhere('promotion_id', $promotion->id);
                })->get();
                return view('admin.promotions.edit', compact('promotion', 'categories', 'products', 'coupons'));
    }

    public function update(PromotionRequest $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
    $data = $request->validated();
        
        // Tạo slug duy nhất cho update (kiểm tra cả soft deleted records)
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 1;
        while (\Illuminate\Support\Facades\DB::table('promotions')->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
            // Tránh vòng lặp vô hạn
            if ($i > 100) {
                $slug = $baseSlug . '-' . time();
                break;
            }
        }
        $data['slug'] = $slug;
        if ($data['flash_type'] === 'category') {
            $data['discount_type'] = 'percent';
            $data['discount_value'] = $request->category_discount_value ?? 10;
            // Cập nhật trực tiếp discount_value nếu đã có promotion
            $promotion->discount_type = 'percent';
            $promotion->discount_value = $data['discount_value'];
            $promotion->save();
            // Cập nhật các trường khác
            unset($data['discount_type'], $data['discount_value']);
            $promotion->update($data);
            // Cập nhật sale_price cho các sản phẩm thuộc danh mục
            $categoryIds = $request->categories ?? [];
            $productIds = \App\Models\Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
            $discountPercent = $promotion->discount_value;
            foreach ($productIds as $pid) {
                $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                foreach ($variants as $variant) {
                    if (is_null($variant->old_sale_price)) {
                        $variant->old_sale_price = $variant->sale_price;
                    }
                    $variant->sale_price = round($variant->price * (1 - $discountPercent / 100));
                    $variant->save();
                }
            }
        } else {
            $promotion->update($data);
        }
        if ($data['flash_type'] === 'category' && !empty($request->categories)) {
            $promotion->categories()->sync($request->categories);
        } else {
            // Nếu không còn là category hoặc bị ẩn/hết hạn thì revert sale_price về old_sale_price cho các sản phẩm từng thuộc promotion này
            $oldCategoryIds = $promotion->categories()->pluck('categories.id')->toArray();
            $oldProductIds = \App\Models\Product::whereIn('category_id', $oldCategoryIds)->pluck('id')->toArray();
            foreach ($oldProductIds as $pid) {
                $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                foreach ($variants as $variant) {
                    if (!is_null($variant->old_sale_price)) {
                        $variant->sale_price = $variant->old_sale_price;
                        $variant->old_sale_price = null;
                        $variant->save();
                    }
                }
            }
            $promotion->categories()->detach();
        }
        if ($data['flash_type'] === 'flash_sale' && !empty($request->products) && $data['status'] == 1) {
            $syncData = [];
            foreach ($request->products as $pid) {
                $syncData[$pid] = [
                    'sale_price' => isset($request->sale_prices[$pid]) ? $request->sale_prices[$pid] : null,
                    'discount_percent' => isset($request->discount_percents[$pid]) ? $request->discount_percents[$pid] : null
                ];
                
                // Lưu giá giảm cũ trước khi cập nhật giá flash sale
                \App\Models\ProductVariant::where('product_id', $pid)
                    ->whereNull('old_sale_price')
                    ->update(['old_sale_price' => DB::raw('sale_price')]);
                
                // Cập nhật sale_price cho tất cả variant của sản phẩm này
                if (isset($request->sale_prices[$pid]) && $request->sale_prices[$pid] > 0) {
                    // Nếu có giá cố định
                    \App\Models\ProductVariant::where('product_id', $pid)->update(['sale_price' => $request->sale_prices[$pid]]);
                } elseif (isset($request->discount_percents[$pid]) && $request->discount_percents[$pid] > 0) {
                    // Nếu có phần trăm giảm giá
                    $variants = \App\Models\ProductVariant::where('product_id', $pid)->get();
                    foreach ($variants as $variant) {
                        $discountedPrice = round($variant->price * (1 - $request->discount_percents[$pid] / 100));
                        $variant->sale_price = $discountedPrice;
                        $variant->save();
                    }
                }
            }
            $promotion->products()->sync($syncData);
        } else {
            // Nếu không còn là flash_sale hoặc bị ẩn/hết hạn thì revert sale_price về old_sale_price cho các sản phẩm từng thuộc promotion này
            $oldProductIds = $promotion->products()->pluck('products.id')->toArray();
            \App\Models\ProductVariant::whereIn('product_id', $oldProductIds)
                ->whereNotNull('old_sale_price')
                ->update(['sale_price' => DB::raw('old_sale_price'), 'old_sale_price' => null]);
            $promotion->products()->detach();
        }
        // Gán coupon cho promotion
        // Xóa liên kết cũ
        \App\Models\Coupon::where('promotion_id', $promotion->id)->update(['promotion_id' => null]);
        // Gán lại các coupon mới
        if ($request->coupons) {
            \App\Models\Coupon::whereIn('id', $request->coupons)->update(['promotion_id' => $promotion->id]);
        }
        return redirect()->route('admin.promotions.index')->with('success', 'Cập nhật chương trình thành công!');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Đã xóa chương trình!');
    }
}
