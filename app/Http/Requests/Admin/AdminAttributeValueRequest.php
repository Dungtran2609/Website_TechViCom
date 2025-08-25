<?php

namespace App\Http\Requests\Admin;

use App\Models\Attribute;
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
        $attributeId = $this->route('attribute') ?? $this->route('attribute_id') ?? $this->input('attribute_id');
        $type = null;
        if (is_object($attributeId)) {
            $type = $attributeId->type;
        } elseif ($attributeId) {
            $attributeModel = Attribute::find($attributeId);
            $type = $attributeModel ? $attributeModel->type : null;
        }
        if (!$type) {
            $type = $this->input('type');
        }

        $rules = [
            'color_code' => 'nullable|string|max:20|regex:/^#[0-9a-fA-F]{3,6}$/',
        ];

        if ($type === 'number') {
            $rules['value'] = 'required|numeric|unique:attribute_values,value,' . $id;
        } elseif ($type === 'color') {
            $rules['value'] = 'required|string|max:100|unique:attribute_values,value,' . $id . ',id,attribute_id,' . $attributeId;
            $rules['color_code'] = [
                'required',
                'string',
                'max:20',
                'regex:/^#[0-9a-fA-F]{3,6}$/',
                'unique:attribute_values,color_code,' . $id . ',id,attribute_id,' . $attributeId
            ];
        } else { // text, select, ...
            $rules['value'] = 'required|string|max:100|unique:attribute_values,value,' . $id;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'value.required'     => 'Vui lòng nhập giá trị thuộc tính.',
            'value.string'       => 'Giá trị phải là chuỗi ký tự.',
            'value.numeric'      => 'Giá trị phải là số.',
            'value.unique'       => 'Giá trị này đã tồn tại.',
            'value.max'          => 'Giá trị không được vượt quá 100 ký tự.',

            'color_code.required' => 'Vui lòng nhập mã màu.',
            'color_code.string'  => 'Mã màu phải là chuỗi ký tự.',
            'color_code.max'     => 'Mã màu không được vượt quá 20 ký tự.',
            'color_code.regex'   => 'Mã màu phải đúng định dạng hex, ví dụ: #fff hoặc #ffffff.',
            'color_code.unique'  => 'Mã màu này đã tồn tại trong thuộc tính này.',
        ];
    }
}
