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
        // Kiểm tra xem thuộc tính có còn giá trị con không
        if ($attribute->values()->exists()) {
            return redirect()->route('admin.products.attributes.index')
                ->with('error', 'Không thể xóa vì đang có giá trị thuộc tính.');
        }

        $attribute->delete();
        return redirect()->route('admin.products.attributes.index')
            ->with('success', 'Đã chuyển thuộc tính vào thùng rác.');
    }

    public function trashed()
    {
        $attributes = Attribute::onlyTrashed()->orderByDesc('deleted_at')->paginate(10);
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

        if ($attribute->values()->withTrashed()->exists()) {
            return redirect()->route('admin.products.attributes.trashed')
                ->with('error', 'Không thể xóa vĩnh viễn. Thuộc tính vẫn còn chứa giá trị (kể cả trong thùng rác).');
        }

        $attribute->forceDelete();

        return redirect()->route('admin.products.attributes.trashed')
            ->with('success', 'Đã xoá vĩnh viễn thuộc tính.');
    }
}