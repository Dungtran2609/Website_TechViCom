<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $id = optional($this->route('attribute_value'))->id;

        return [
            'value' => 'required|string|max:100|unique:attribute_values,value,' . $id,
            'color_code' => 'nullable|string|max:20|regex:/^#[0-9a-fA-F]{3,6}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'value.required'     => 'Vui lòng nhập giá trị thuộc tính.',
            'value.string'       => 'Giá trị phải là chuỗi ký tự.',
            'value.unique'       => 'Giá trị này đã tồn tại.',
            'value.max'          => 'Giá trị không được vượt quá 100 ký tự.',

            'color_code.string'  => 'Mã màu phải là chuỗi ký tự.',
            'color_code.max'     => 'Mã màu không được vượt quá 20 ký tự.',
            'color_code.regex'   => 'Mã màu phải đúng định dạng hex, ví dụ: #fff hoặc #ffffff.',
        ];
    }
}
