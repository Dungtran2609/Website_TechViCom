<?php
namespace App\Http\Controllers\Admin\Promotions;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with(['coupons'])->latest()->paginate(15);
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
            'type' => 'required|in:all,category,product',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'boolean',
            'categories' => 'array',
            'products' => 'array',
        ]);
        $data['slug'] = Str::slug($data['name']);
        $promotion = Promotion::create($data);
        // Gán sản phẩm/danh mục nếu có
        if ($data['type'] === 'category' && !empty($request->categories)) {
            $promotion->categories()->sync($request->categories);
        }
        if ($data['type'] === 'product' && !empty($request->products)) {
            $promotion->products()->sync($request->products);
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
            'type' => 'required|in:all,category,product',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'boolean',
            'categories' => 'array',
            'products' => 'array',
        ]);
        $data['slug'] = Str::slug($data['name']);
        $promotion->update($data);
        if ($data['type'] === 'category' && !empty($request->categories)) {
            $promotion->categories()->sync($request->categories);
        } else {
            $promotion->categories()->detach();
        }
        if ($data['type'] === 'product' && !empty($request->products)) {
            $promotion->products()->sync($request->products);
        } else {
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
