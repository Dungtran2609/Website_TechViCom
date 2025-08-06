<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Http\Requests\Admin\AdminAttributeRequest;
use Illuminate\Support\Str;

class AdminAttributeController extends Controller
{
    public function index()
    {
        $query = Attribute::query();

        if (request()->has('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        $attributes = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.products.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.products.attributes.create');
    }

    public function store(AdminAttributeRequest $request)
    {
        Attribute::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.products.attributes.index')
            ->with('success', 'Đã tạo thuộc tính thành công.');
    }

    public function edit(Attribute $attribute)
    {
        return view('admin.products.attributes.edit', compact('attribute'));
    }

    public function update(AdminAttributeRequest $request, Attribute $attribute)
    {
        $attribute->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.products.attributes.index')
            ->with('success', 'Đã cập nhật thuộc tính thành công.');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.products.attributes.index')
            ->with('success', 'Đã xoá (ẩn tạm thời) thuộc tính.');
    }

    public function trashed()
    {
        $attributes = Attribute::onlyTrashed()->orderByDesc('id')->get();
        return view('admin.products.attributes.trashed', compact('attributes'));
    }

    public function restore($id)
    {
        $attribute = Attribute::onlyTrashed()->findOrFail($id);
        $attribute->restore();

        return redirect()->route('admin.products.attributes.trashed')
            ->with('success', 'Đã khôi phục thuộc tính.');
    }

    public function forceDelete($id)
    {
        $attribute = Attribute::onlyTrashed()->findOrFail($id);
        $attribute->forceDelete();

        return redirect()->route('admin.products.attributes.trashed')
            ->with('success', 'Đã xoá vĩnh viễn.');
    }
}