<?php

namespace App\Http\Controllers\Admin\Products;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\AdminProductRequest;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand', 'variants'])
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'brands' => Brand::all(),
            'categories' => Category::all(),
            'attributes' => Attribute::with('values')->get(),
        ]);
    }

    private function generateUniqueSku($prefix = 'SP')
    {
        do {
            $sku = $prefix . strtoupper(Str::random(6));
        } while (ProductVariant::where('sku', $sku)->exists());
        return $sku;
    }

    public function store(AdminProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            $productData = $this->prepareProductData($request);
            $product = Product::create($productData);
            $this->syncVariants($product, $request);
            
            // Xử lý thư viện ảnh
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $image) {
                    $path = $image->store('products/gallery', 'public');
                    $product->allImages()->create(['image_path' => $path]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công.');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'variants.attributeValues.attribute', 'allImages']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['variants.attributeValues', 'brand', 'category', 'allImages']);
        return view('admin.products.edit', [
            'product' => $product,
            'brands' => Brand::all(),
            'categories' => Category::all(),
            'attributes' => Attribute::with('values')->get(),
        ]);
    }

    public function update(AdminProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $productData = $this->prepareProductData($request, $product);
            $product->update($productData);
            $this->syncVariants($product, $request);
            
            if ($request->filled('delete_images')) {
                foreach ($request->delete_images as $id) {
                    $image = $product->allImages()->find($id);
                    if ($image) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
            }

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $path = $file->store('products/gallery', 'public');
                    $product->allImages()->create(['image_path' => $path]);
                }
            }
        });
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được chuyển vào thùng rác.');
    }

    private function prepareProductData(Request $request, Product $product = null): array
    {
        $validated = $request->safe()->except(['price', 'sale_price', 'sku', 'stock', 'variants', 'weight', 'length', 'width', 'height']);
        if ($product === null || $request->name !== $product->name) {
            $validated['slug'] = Str::slug($request->name);
        }
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }
        return $validated;
    }

    private function syncVariants(Product $product, Request $request): void
    {
        $submittedVariantIds = [];
        if ($request->type === 'simple') {
        $simpleVariantData = $request->only(['price', 'sale_price', 'sku', 'stock', 'low_stock_amount', 'weight', 'length', 'width', 'height']);
        $simpleVariantData['is_active'] = true;

        // Sinh SKU tự động nếu không nhập
        if (empty($simpleVariantData['sku'])) {
            $simpleVariantData['sku'] = $this->generateUniqueSku();
        }

        $variant = $product->variants()->updateOrCreate(['id' => $product->variants()->first()?->id], $simpleVariantData);

        // Lưu thuộc tính cho sản phẩm đơn
        $attributeValueIds = collect($request->input('attributes', []))->filter()->values()->toArray();
        $variant->attributeValues()->sync($attributeValueIds);

        $submittedVariantIds[] = $variant->id;
        } 
        elseif ($request->type === 'variable' && $request->has('variants')) {
            foreach ($request->variants as $key => $variantData) {
                $variantData['is_active'] = isset($variantData['is_active']);
                $variantPayload = Arr::except($variantData, ['attributes', 'image']);
                
                if (empty($variantPayload['sku'])) {
                    $variantPayload['sku'] = $this->generateUniqueSku();
                }

                $variant = $product->variants()->updateOrCreate(['id' => $variantData['id'] ?? null], $variantPayload);
                
                if ($request->hasFile("variants.{$key}.image")) {
                    $path = $request->file("variants.{$key}.image")->store('products/variants', 'public');
                    $variant->update(['image' => $path]);
                }
                
                $variant->attributeValues()->sync($variantData['attributes']);
                $submittedVariantIds[] = $variant->id;
            }
        }
        $product->variants()->whereNotIn('id', $submittedVariantIds)->delete();
    }

    public function trashed()
    {

        $products = Product::onlyTrashed()->with(['brand', 'category'])->latest('deleted_at')->paginate(10);
        return view('admin.products.trashed', compact('products'));
    }

    public function restore($id)
    {

        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('admin.products.trashed')->with('success', 'Khôi phục sản phẩm thành công.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->with('allImages')->findOrFail($id);
        
        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        foreach ($product->allImages as $image) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $product->forceDelete();
        return redirect()->route('admin.products.trashed')->with('success', 'Đã xoá vĩnh viễn sản phẩm.');
    }

}