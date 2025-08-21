<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminProductRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $productId = $product?->id;
        $isUpdating = $product !== null;

        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($productId)],
            'type' => ['required', Rule::in(['simple', 'variable'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'nullable|boolean',
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:product_all_images,id',
        ];

        if ($this->input('type') === 'simple') {
            $rules['price'] = 'required|numeric|min:0';
            $rules['sale_price'] = ['nullable', 'numeric', 'min:0', 'lt:price'];

            $rules['stock'] = 'required|integer|min:0';
            $rules['low_stock_amount'] = 'nullable|integer|min:0';

            $simpleVariantId = $isUpdating ? $product->variants->whereNull('deleted_at')->first()?->id : null;
            $rules['sku'] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')->ignore($simpleVariantId)
            ];

            $rules['weight'] = 'nullable|numeric|min:0';
            $rules['length'] = 'nullable|numeric|min:0';
            $rules['width'] = 'nullable|numeric|min:0';
            $rules['height'] = 'nullable|numeric|min:0';
            $rules['attributes'] = 'nullable|array';
            $rules['attributes.*'] = 'nullable|exists:attribute_values,id';
        }

        if ($this->input('type') === 'variable') {
            $rules['variants'] = 'required|array|min:1';
            $rules['variants.*.price'] = 'required|numeric|min:0';

            $rules['variants.*.stock'] = 'required|integer|min:0';
            $rules['variants.*.low_stock_amount'] = 'nullable|integer|min:0';
            $rules['variants.*.attributes'] = 'required|array|min:1';

            if ($this->input('variants')) {
                foreach (array_keys($this->input('variants')) as $key) {
                    $variantId = $this->input("variants.{$key}.id") ?? null;

                    $skuRule = ($isUpdating && $variantId)
                        ? ['required', 'string', 'max:255']
                        : ['nullable', 'string', 'max:255'];
                    $rules["variants.{$key}.sku"] = array_merge($skuRule, [Rule::unique('product_variants', 'sku')->ignore($variantId)]);

                    $rules["variants.{$key}.sale_price"] = ['nullable', 'numeric', 'min:0', 'lt:variants.' . $key . '.price'];

                    $rules["variants.{$key}.image"] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'];
                }
            }

            $rules['variants.*.weight'] = 'nullable|numeric|min:0';
            $rules['variants.*.length'] = 'nullable|numeric|min:0';
            $rules['variants.*.width'] = 'nullable|numeric|min:0';
            $rules['variants.*.height'] = 'nullable|numeric|min:0';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'sku.unique' => 'SKU này đã tồn tại.',
            'variants.*.sku.required' => 'SKU của biến thể không được để trống khi cập nhật.',
            'variants.*.sku.unique' => 'SKU của biến thể đã tồn tại.',

            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',

            'type.required' => 'Loại sản phẩm là bắt buộc.',
            'type.in' => 'Loại sản phẩm không hợp lệ.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',

            'brand_id.required' => 'Thương hiệu là bắt buộc.',
            'brand_id.exists' => 'Thương hiệu không hợp lệ.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không hợp lệ.',

            'is_featured.boolean' => 'Trường nổi bật không hợp lệ.',

            'thumbnail.image' => 'Ảnh đại diện phải là hình ảnh.',
            'thumbnail.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            'thumbnail.max' => 'Kích thước ảnh đại diện không được vượt quá 5MB.',

            'short_description.string' => 'Mô tả ngắn phải là chuỗi ký tự.',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 500 ký tự.',
            'long_description.string' => 'Mô tả chi tiết phải là chuỗi ký tự.',

            'gallery.*.image' => 'Ảnh trong thư viện phải là hình ảnh.',
            'gallery.*.mimes' => 'Ảnh trong thư viện phải có định dạng jpeg, png, jpg hoặc webp.',
            'gallery.*.max' => 'Kích thước ảnh trong thư viện không được vượt quá 5MB.',

            'delete_images.*.integer' => 'ID ảnh cần xóa phải là số nguyên.',
            'delete_images.*.exists' => 'ID ảnh cần xóa không hợp lệ.',

            'price.required' => 'Giá bán là bắt buộc.',
            'price.numeric' => 'Giá bán phải là số.',
            'price.min' => 'Giá bán phải lớn hơn hoặc bằng 0.',
            'sale_price.lt' => 'Giá khuyến mãi phải thấp hơn giá bán.',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'sale_price.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',

            'stock.required' => 'Tồn kho là bắt buộc.',
            'stock.integer' => 'Tồn kho phải là số nguyên.',
            'stock.min' => 'Tồn kho phải lớn hơn hoặc bằng 0.',
            'low_stock_amount.integer' => 'Ngưỡng tồn kho thấp phải là số nguyên.',
            'low_stock_amount.min' => 'Ngưỡng tồn kho thấp phải lớn hơn hoặc bằng 0.',

            'sku.string' => 'SKU phải là chuỗi ký tự.',
            'sku.max' => 'SKU không được vượt quá 255 ký tự.',

            'weight.numeric' => 'Cân nặng phải là số.',
            'weight.min' => 'Cân nặng phải lớn hơn hoặc bằng 0.',
            'length.numeric' => 'Chiều dài phải là số.',
            'length.min' => 'Chiều dài phải lớn hơn hoặc bằng 0.',
            'width.numeric' => 'Chiều rộng phải là số.',
            'width.min' => 'Chiều rộng phải lớn hơn hoặc bằng 0.',
            'height.numeric' => 'Chiều cao phải là số.',
            'height.min' => 'Chiều cao phải lớn hơn hoặc bằng 0.',

            'attributes.array' => 'Thuộc tính sản phẩm không hợp lệ.',
            'attributes.*.exists' => 'Giá trị thuộc tính không hợp lệ.',

            'variants.required' => 'Phải có ít nhất một biến thể.',
            'variants.array' => 'Dữ liệu biến thể không hợp lệ.',
            'variants.*.price.required' => 'Giá bán của biến thể là bắt buộc.',
            'variants.*.price.numeric' => 'Giá bán của biến thể phải là số.',
            'variants.*.price.min' => 'Giá bán của biến thể phải lớn hơn hoặc bằng 0.',
            'variants.*.sale_price.lt' => 'Giá khuyến mãi của biến thể phải thấp hơn giá bán của biến thể đó.',
            'variants.*.sale_price.numeric' => 'Giá khuyến mãi của biến thể phải là số.',
            'variants.*.sale_price.min' => 'Giá khuyến mãi của biến thể phải lớn hơn hoặc bằng 0.',

            'variants.*.stock.required' => 'Tồn kho của biến thể là bắt buộc.',
            'variants.*.stock.integer' => 'Tồn kho của biến thể phải là số nguyên.',
            'variants.*.stock.min' => 'Tồn kho của biến thể phải lớn hơn hoặc bằng 0.',
            'variants.*.low_stock_amount.integer' => 'Ngưỡng tồn kho thấp của biến thể phải là số nguyên.',
            'variants.*.low_stock_amount.min' => 'Ngưỡng tồn kho thấp của biến thể phải lớn hơn hoặc bằng 0.',

            'variants.*.attributes.required' => 'Thuộc tính của biến thể là bắt buộc.',
            'variants.*.attributes.array' => 'Thuộc tính của biến thể không hợp lệ.',
            'variants.*.attributes.min' => 'Mỗi biến thể phải có ít nhất một thuộc tính.',

            'variants.*.weight.numeric' => 'Cân nặng của biến thể phải là số.',
            'variants.*.weight.min' => 'Cân nặng của biến thể phải lớn hơn hoặc bằng 0.',
            'variants.*.length.numeric' => 'Chiều dài của biến thể phải là số.',
            'variants.*.length.min' => 'Chiều dài của biến thể phải lớn hơn hoặc bằng 0.',
            'variants.*.width.numeric' => 'Chiều rộng của biến thể phải là số.',
            'variants.*.width.min' => 'Chiều rộng của biến thể phải lớn hơn hoặc bằng 0.',
            'variants.*.height.numeric' => 'Chiều cao của biến thể phải là số.',
            'variants.*.height.min' => 'Chiều cao của biến thể phải lớn hơn hoặc bằng 0.',

            'variants.*.image.image' => 'Ảnh biến thể phải là hình ảnh.',
            'variants.*.image.mimes' => 'Ảnh biến thể phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            'variants.*.image.max' => 'Kích thước ảnh biến thể không được vượt quá 5MB.',
        ];
    }
}