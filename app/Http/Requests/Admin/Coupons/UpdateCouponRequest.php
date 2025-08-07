<?php

namespace App\Http\Requests\Admin\Coupons;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
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
        $couponId = $this->route('coupon') ?? $this->route('id');

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('coupons', 'code')->ignore($couponId)],
            'discount_type' => ['required', Rule::in(['percent', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_usage_per_user' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'boolean'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
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
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'value.required' => 'Vui lòng nhập giá trị giảm.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
