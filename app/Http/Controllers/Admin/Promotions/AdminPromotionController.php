<?php
namespace App\Http\Controllers\Admin\Promotions;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminPromotionController extends Controller
{
    public function index()
    {
        $query = Promotion::with(['coupons']);
        if (request('q')) {
            $q = request('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%")
                    ->orWhere('id', $q);
            });
        }
        $promotions = $query->latest()->paginate(15)->appends(['q' => request('q')]);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
    $categories = Category::all();
    $products = Product::all();
    $coupons = \App\Models\Coupon::whereNull('promotion_id')->orWhere('promotion_id', 0)->get();
    return view('admin.promotions.create', compact('categories', 'products', 'coupons'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'flash_type' => 'required|in:all,category,flash_sale',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'boolean',
            'categories' => 'array',
            'products' => 'array',
        ]);
        $data['slug'] = Str::slug($data['name']);
        $promotion = Promotion::create($data);
        // Gán sản phẩm/danh mục nếu có
        if ($data['flash_type'] === 'category' && !empty($request->categories)) {
            $promotion->categories()->sync($request->categories);
        }
        if ($data['flash_type'] === 'flash_sale' && !empty($request->products) && $data['status'] == 1) {
            if ($request->has('sale_prices')) {
                $syncData = [];
                foreach ($request->products as $pid) {
                    $syncData[$pid] = [
                        'sale_price' => isset($request->sale_prices[$pid]) ? $request->sale_prices[$pid] : null
                    ];
                    // Lưu giá giảm cũ trước khi cập nhật giá flash sale
                    \App\Models\ProductVariant::where('product_id', $pid)
                        ->whereNull('old_sale_price')
                        ->update(['old_sale_price' => DB::raw('sale_price')]);
                    // Cập nhật sale_price cho tất cả variant của sản phẩm này
                    \App\Models\ProductVariant::where('product_id', $pid)->update(['sale_price' => isset($request->sale_prices[$pid]) ? $request->sale_prices[$pid] : null]);
                }
                $promotion->products()->sync($syncData);
            } else {
                $promotion->products()->sync($request->products);
            }
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

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'flash_type' => 'required|in:all,category,flash_sale',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'boolean',
            'categories' => 'array',
            'products' => 'array',
        ]);
        $data['slug'] = Str::slug($data['name']);
        $promotion->update($data);
        if ($data['flash_type'] === 'category' && !empty($request->categories)) {
            $promotion->categories()->sync($request->categories);
        } else {
            $promotion->categories()->detach();
        }
        if ($data['flash_type'] === 'flash_sale' && !empty($request->products) && $data['status'] == 1) {
            if ($request->has('sale_prices')) {
                $syncData = [];
                foreach ($request->products as $pid) {
                    $syncData[$pid] = [
                        'sale_price' => isset($request->sale_prices[$pid]) ? $request->sale_prices[$pid] : null
                    ];
                    // Lưu giá giảm cũ trước khi cập nhật giá flash sale
                    \App\Models\ProductVariant::where('product_id', $pid)
                        ->whereNull('old_sale_price')
                        ->update(['old_sale_price' => DB::raw('sale_price')]);
                    // Cập nhật sale_price cho tất cả variant của sản phẩm này
                    \App\Models\ProductVariant::where('product_id', $pid)->update(['sale_price' => isset($request->sale_prices[$pid]) ? $request->sale_prices[$pid] : null]);
                }
                $promotion->products()->sync($syncData);
            } else {
                $promotion->products()->sync($request->products);
            }
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
