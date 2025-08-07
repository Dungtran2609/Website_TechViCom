<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $query->orderBy('id', 'desc');

        $brands = $query->paginate(5)->withQueryString();

        return view('admin.products.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.products.brands.create');
    }

    public function store(AdminBrandRequest $request)
    {
        $validatedData = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brands', 'public');
        }

        Brand::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'image' => $imagePath,
            'description' => $validatedData['description'],
            'status' => $validatedData['status'],
        ]);

        return redirect()->route('admin.products.brands.index')
            ->with('success', 'Thương hiệu đã được tạo thành công.');
    }

    public function show(Brand $brand)
    {
        return view('admin.products.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.products.brands.edit', compact('brand'));
    }

    public function update(AdminBrandRequest $request, Brand $brand)
    {
        $validatedData = $request->validated();

        $imagePath = $brand->image;
        if ($request->hasFile('image')) {
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }
            $imagePath = $request->file('image')->store('brands', 'public');
        }

        $brand->update([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'image' => $imagePath,
            'description' => $validatedData['description'],
            'status' => $validatedData['status'],
        ]);

        return redirect()->route('admin.products.brands.index')
            ->with('success', 'Thương hiệu đã được cập nhật.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->exists()) {
            return redirect()->route('admin.products.brands.index')
                ->with('error', 'Không thể xóa thương hiệu này vì vẫn còn sản phẩm.');
        }

        $brand->delete(); // Soft delete
        return redirect()->route('admin.products.brands.index')
            ->with('success', 'Thương hiệu đã được chuyển vào thùng rác.');
    }

    public function trashed()
    {
        $brands = Brand::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(5);
        return view('admin.products.brands.trashed', compact('brands'));
    }

    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->find($id);

        if (!$brand) {
            return redirect()->route('admin.products.brands.trashed')->with('error', 'Không tìm thấy thương hiệu.');
        }

        $brand->restore();

        return redirect()->route('admin.products.brands.trashed')->with('success', 'Khôi phục thương hiệu thành công.');
    }

    public function forceDelete($id)
    {
        $brand = Brand::onlyTrashed()->find($id);

        if (!$brand) {
            return redirect()->route('admin.products.brands.trashed')->with('error', 'Không tìm thấy thương hiệu.');
        }

        if ($brand->products()->exists()) {
            return redirect()->route('admin.products.brands.trashed')
                ->with('error', 'Không thể xóa vĩnh viễn thương hiệu vì vẫn còn sản phẩm.');
        }

        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->forceDelete();

        return redirect()->route('admin.products.brands.trashed')->with('success', 'Đã xoá vĩnh viễn thương hiệu.');
    }

}