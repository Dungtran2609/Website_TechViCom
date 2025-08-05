<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Khi cập nhật, route('attribute') có thể là model => cần lấy id
        $id = optional($this->route('attribute'))->id;

        return [
            'name' => 'required|string|max:100|unique:attributes,name,' . $id,
            'type' => 'required|in:text,select,color,number',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Vui lòng nhập tên thuộc tính.',
            'name.string'       => 'Tên thuộc tính phải là chuỗi.',
            'name.max'          => 'Tên không được vượt quá 100 ký tự.',
            'name.unique'       => 'Tên thuộc tính đã tồn tại.',

            'type.required'     => 'Vui lòng chọn loại thuộc tính.',
            'type.in'           => 'Loại thuộc tính không hợp lệ.',

            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max'    => 'Mô tả không được vượt quá 255 ký tự.',
        ];
    }
}
