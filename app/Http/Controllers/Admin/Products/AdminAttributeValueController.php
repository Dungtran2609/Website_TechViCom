<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Http\Requests\Admin\AdminAttributeValueRequest;

class AdminAttributeValueController extends Controller
{
    public function index($attributeId)
    {
        $attribute = Attribute::findOrFail($attributeId);

        $values = AttributeValue::where('attribute_id', $attributeId)
            ->when(request('search'), function ($query) {
                $query->where('value', 'like', '%' . request('search') . '%');
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.products.attributes.values.index', compact('attribute', 'values'));
    }

    public function store(AdminAttributeValueRequest $request, $attributeId)
    {
        $attribute = Attribute::findOrFail($attributeId);

        AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value' => $request->value,
            'color_code' => $attribute->type === 'color' ? $request->color_code : null,
        ]);

        return redirect()->route('admin.products.attributes.values.index', $attribute->id)
                         ->with('success', 'Thêm giá trị thành công.');
    }

    public function edit($id)
    {
        $value = AttributeValue::findOrFail($id);
        $attribute = $value->attribute;

        return view('admin.products.attributes.values.edit', compact('value', 'attribute'));
    }

    public function update(AdminAttributeValueRequest $request, $id)
    {
        $value = AttributeValue::findOrFail($id);
        $attribute = $value->attribute;

        $value->update([
            'value' => $request->value,
            'color_code' => $attribute->type === 'color' ? $request->color_code : null,
        ]);

        return redirect()->route('admin.products.attributes.values.index', $attribute->id)
                         ->with('success', 'Cập nhật giá trị thành công.');
    }

    public function destroy($id)
    {
        $value = AttributeValue::findOrFail($id);
        $attributeId = $value->attribute_id;

        // Kiểm tra xem giá trị có đang được sản phẩm nào sử dụng không
        if ($value->productVariants()->exists()) {
            return back()->with('error', 'Không thể xóa giá trị này vì đang được sản phẩm sử dụng.');
        }

        $value->delete();
        return redirect()->route('admin.products.attributes.values.index', $attributeId)
                        ->with('success', 'Đã chuyển giá trị vào thùng rác.');
    }

    public function trashed($attributeId)
    {
        $attribute = Attribute::findOrFail($attributeId);
        $values = AttributeValue::onlyTrashed()
            ->where('attribute_id', $attributeId)
            ->latest('deleted_at')
            ->paginate(10);

        return view('admin.products.attributes.values.trashed', compact('attribute', 'values'));
    }

    public function restore($id)
    {
        $value = AttributeValue::onlyTrashed()->findOrFail($id);
        $value->restore();

        return back()->with('success', 'Đã khôi phục giá trị.');
    }

    public function forceDelete($id)
    {
        $value = AttributeValue::onlyTrashed()->findOrFail($id);

        // Kiểm tra lại lần cuối
        if ($value->productVariants()->exists()) {
            return back()->with('error', 'Không thể xóa vĩnh viễn. Giá trị này vẫn đang được sản phẩm sử dụng.');
        }
        
        $value->forceDelete();
        return back()->with('success', 'Đã xoá vĩnh viễn giá trị.');
    }
}