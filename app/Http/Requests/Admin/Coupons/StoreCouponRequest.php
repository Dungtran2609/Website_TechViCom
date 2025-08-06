<?php

namespace App\Http\Requests\Admin\Coupons;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => (int) $this->status,
        ]);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:coupons,code'],
            'discount_type' => ['required', Rule::in(['percent', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_usage_per_user' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in([0, 1])],
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('max_discount_amount', 'required', function ($input) {
            return $input->discount_type === 'percent';
        });

        $validator->sometimes('min_order_value', 'required', function ($input) {
            return !empty($input->max_order_value);
        });

        $validator->sometimes('max_order_value', 'required', function ($input) {
            return !empty($input->min_order_value);
        });
    }

    public function messages(): array
{
    return [
        'code.required' => 'Vui lòng nhập mã giảm giá.',
        'code.string' => 'Mã giảm giá phải là chuỗi.',
        'code.max' => 'Mã giảm giá không được vượt quá 20 ký tự.',
        'code.unique' => 'Mã giảm giá đã tồn tại.',

        'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
        'discount_type.in' => 'Loại giảm giá không hợp lệ.',

        'value.required' => 'Vui lòng nhập giá trị giảm.',
        'value.numeric' => 'Giá trị giảm phải là số.',
        'value.min' => 'Giá trị giảm phải lớn hơn hoặc bằng 0.',

        'max_discount_amount.numeric' => 'Giảm tối đa phải là số.',
        'max_discount_amount.min' => 'Giảm tối đa phải lớn hơn hoặc bằng 0.',
        'max_discount_amount.required' => 'Vui lòng nhập mức giảm tối đa.',

        'min_order_value.numeric' => 'Giá trị đơn tối thiểu phải là số.',
        'min_order_value.min' => 'Giá trị đơn tối thiểu phải lớn hơn hoặc bằng 0.',
        'min_order_value.required' => 'Vui lòng nhập giá trị đơn tối thiểu.',

        'max_order_value.numeric' => 'Giá trị đơn tối đa phải là số.',
        'max_order_value.min' => 'Giá trị đơn tối đa phải lớn hơn hoặc bằng 0.',
        'max_order_value.required' => 'Vui lòng nhập giá trị đơn tối đa.',

        'max_usage_per_user.integer' => 'Số lần dùng mỗi người phải là số nguyên.',
        'max_usage_per_user.min' => 'Số lần dùng mỗi người phải lớn hơn hoặc bằng 1.',

        'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
        'start_date.date' => 'Ngày bắt đầu không hợp lệ.',

        'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
        'end_date.date' => 'Ngày kết thúc không hợp lệ.',
        'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',

        'status.required' => 'Vui lòng chọn trạng thái.',
        'status.in' => 'Trạng thái không hợp lệ.',
    ];
}

}
