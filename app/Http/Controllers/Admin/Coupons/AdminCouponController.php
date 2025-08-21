<?php

namespace App\Http\Controllers\Admin\Coupons;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Coupons\StoreCouponRequest;
use App\Http\Requests\Admin\Coupons\UpdateCouponRequest;
use App\Http\Requests\Admin\Coupons\DeleteCouponRequest;

class AdminCouponController extends Controller
{
    public function index(Request $request)
{
    $query = Coupon::withTrashed()->latest();

    if ($request->filled('keyword')) {
        $query->where('code', 'like', '%' . $request->keyword . '%');
    }

    $coupons = $query->get();

    return view('admin.coupons.index', compact('coupons'));
}


    public function create()
    {
    $categories = \App\Models\Category::all();
    $products = \App\Models\Product::all();
    $users = \App\Models\User::all();
    return view('admin.coupons.create', compact('categories', 'products', 'users'));
    }

    public function store(StoreCouponRequest $request)
{
    $coupon = Coupon::create($request->validated());

    // Sync pivot tables
    if ($request->has('product_ids')) {
        $coupon->products()->sync($request->input('product_ids'));
    }
    if ($request->has('category_ids')) {
        $coupon->categories()->sync($request->input('category_ids'));
    }
    if ($request->has('user_ids')) {
        $coupon->users()->sync($request->input('user_ids'));
    }

    return redirect()->route('admin.coupons.index')
        ->with('success', 'Tạo mã giảm giá thành công!');
}

    public function edit($id)
    {
    $coupon = Coupon::withTrashed()->findOrFail($id);
    $categories = \App\Models\Category::all();
    $products = \App\Models\Product::all();
    $users = \App\Models\User::all();
    return view('admin.coupons.edit', compact('coupon', 'categories', 'products', 'users'));
    }


public function update(UpdateCouponRequest $request, $id)
{
    try {
        $coupon = Coupon::findOrFail($id);
        $coupon->update($request->validated());

        // Sync pivot tables
        if ($request->has('product_ids')) {
            $coupon->products()->sync($request->product_ids);
        } else {
            $coupon->products()->detach();
        }
        if ($request->has('category_ids')) {
            $coupon->categories()->sync($request->category_ids);
        } else {
            $coupon->categories()->detach();
        }
        if ($request->has('user_ids')) {
            $coupon->users()->sync($request->user_ids);
        } else {
            $coupon->users()->detach();
        }

        return redirect()
            ->route('admin.coupons.edit', $coupon->id)
            ->with('success', 'Cập nhật thành công');
    } catch (\Exception $e) {
        return redirect()
            ->route('admin.coupons.edit', $id)
            ->with('error', 'Cập nhật thất bại: ' . $e->getMessage());
    }
}



    public function destroy(DeleteCouponRequest $request)
{
    $coupon = Coupon::findOrFail($request->id);
    $coupon->delete();
    return redirect()->back()->with('success', 'Đã xoá tạm thời.');
}

    public function restore($id)
    {
        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->restore();
        return redirect()->back()->with('success', 'Đã khôi phục mã giảm giá.');
    }

    public function forceDelete($id)
    {
        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->forceDelete();
        return redirect()->back()->with('success', 'Đã xoá vĩnh viễn.');
    }
}
