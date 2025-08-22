<?php

namespace App\Http\Requests\Admin\Promotions;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255|min:3',
            'description' => 'nullable|string|max:1000',
            'flash_type' => 'required|in:all,category,flash_sale',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean',
            'categories' => 'array',
            'products' => 'array',
            'category_discount_value' => 'nullable|numeric|min:1|max:100',
            'discount_percents.*' => 'nullable|numeric|min:0|max:100',
            'sale_prices.*' => 'nullable|numeric|min:0',
        ];

        // Conditional validation based on flash_type
        if ($this->input('flash_type') === 'category') {
            $rules['categories'] = 'required|array|min:1';
            $rules['category_discount_value'] = 'required|numeric|min:1|max:100';
        }

        if ($this->input('flash_type') === 'flash_sale') {
            $rules['products'] = 'required|array|min:1';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên chương trình là bắt buộc.',
            'name.string' => 'Tên chương trình phải là chuỗi.',
            'name.min' => 'Tên chương trình phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên chương trình không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'flash_type.required' => 'Vui lòng chọn kiểu chương trình.',
            'flash_type.in' => 'Kiểu chương trình không hợp lệ.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không đúng định dạng.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không đúng định dạng.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
            'categories.required' => 'Vui lòng chọn ít nhất một danh mục.',
            'categories.array' => 'Danh mục không hợp lệ.',
            'categories.min' => 'Vui lòng chọn ít nhất một danh mục.',
            'products.required' => 'Vui lòng chọn ít nhất một sản phẩm.',
            'products.array' => 'Sản phẩm không hợp lệ.',
            'products.min' => 'Vui lòng chọn ít nhất một sản phẩm.',
            'category_discount_value.required' => 'Giá trị giảm giá là bắt buộc.',
            'category_discount_value.numeric' => 'Giá trị giảm phải là số.',
            'category_discount_value.min' => 'Giá trị giảm phải lớn hơn 0.',
            'category_discount_value.max' => 'Giá trị giảm tối đa là 100%.',
        ];
    }
}
