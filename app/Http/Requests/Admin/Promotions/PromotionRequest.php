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
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'flash_type' => 'required|in:all,category,flash_sale',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'boolean',
            'categories' => 'array',
            'products' => 'array',
            'category_discount_value' => 'nullable|numeric|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên chương trình là bắt buộc.',
            'name.string' => 'Tên chương trình phải là chuỗi.',
            'name.max' => 'Tên chương trình không được vượt quá 255 ký tự.',
            'flash_type.required' => 'Vui lòng chọn kiểu chương trình.',
            'flash_type.in' => 'Kiểu chương trình không hợp lệ.',
            'start_date.date' => 'Ngày bắt đầu không đúng định dạng.',
            'end_date.date' => 'Ngày kết thúc không đúng định dạng.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
            'categories.array' => 'Danh mục không hợp lệ.',
            'products.array' => 'Sản phẩm không hợp lệ.',
            'category_discount_value.numeric' => 'Giá trị giảm phải là số.',
            'category_discount_value.min' => 'Giá trị giảm phải lớn hơn 0.',
            'category_discount_value.max' => 'Giá trị giảm tối đa là 100%.',
        ];
    }
}
