<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => trim($this->input('name')),
        ]);
    }

    public function rules(): array
    {
        $categoryId = $this->route('category');

        return [
            'name' => [
                'bail',
                'required',
                'max:100',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'parent_id' => [
                'nullable',
                'different:category',
                'exists:categories,id',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg,webp',
                'max:2048',
            ],
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
            'parent_id.different' => 'Danh mục cha không thể là chính nó.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Định dạng ảnh phải là jpeg, png, jpg, gif, svg hoặc webp.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}
