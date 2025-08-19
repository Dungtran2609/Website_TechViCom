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
            'code' => trim($this->code),
            'status' => filter_var($this->status, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        ]);
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon') ?? $this->route('id');

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9_-]+$/', // no spaces, only letters, numbers, - and _
                Rule::unique('coupons', 'code')->ignore($couponId)
            ],
            'discount_type' => [
                'required',
                'string',
                Rule::in(['percent', 'fixed'])
            ],
            'value' => [
                'required',
                'numeric',
                'gt:0'
            ],
            'max_discount_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'min_order_value' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'max_order_value' => [
                'nullable',
                'numeric',
                'min:0',
                'gte:min_order_value'
            ],
            'max_usage_per_user' => [
                'nullable',
                'integer',
                'min:1',
                'max:1000'
            ],
            'status' => [
                'required',
                'boolean'
            ],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date'
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('max_discount_amount', 'required', function ($input) {
            return $input->discount_type === 'percent';
        });

        $validator->sometimes('min_order_value', 'required', function ($input) {
            return !is_null($input->max_order_value) && $input->max_order_value !== '';
        });

        $validator->sometimes('max_order_value', 'required', function ($input) {
            return !is_null($input->min_order_value) && $input->min_order_value !== '';
        });

        $validator->after(function ($validator) {
            $data = $this->all();
            if (isset($data['min_order_value'], $data['max_order_value']) && $data['min_order_value'] > $data['max_order_value']) {
                $validator->errors()->add('max_order_value', 'Giá trị đơn hàng tối đa phải lớn hơn hoặc bằng tối thiểu.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'code.regex' => 'Mã giảm giá chỉ được chứa chữ, số, dấu - hoặc _.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'value.required' => 'Vui lòng nhập giá trị giảm.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.gt' => 'Giá trị giảm phải lớn hơn 0.',
            'max_discount_amount.required' => 'Vui lòng nhập số tiền giảm tối đa cho loại phần trăm.',
            'max_discount_amount.numeric' => 'Số tiền giảm tối đa phải là số.',
            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'max_order_value.numeric' => 'Giá trị đơn hàng tối đa phải là số.',
            'max_order_value.gte' => 'Giá trị đơn hàng tối đa phải lớn hơn hoặc bằng tối thiểu.',
            'max_usage_per_user.integer' => 'Số lần sử dụng tối đa mỗi người phải là số nguyên.',
            'max_usage_per_user.min' => 'Số lần sử dụng tối đa mỗi người phải lớn hơn 0.',
            'max_usage_per_user.max' => 'Số lần sử dụng tối đa mỗi người không vượt quá 1000.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu không được trong quá khứ.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }
}
