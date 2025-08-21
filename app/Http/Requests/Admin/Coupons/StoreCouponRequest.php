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

    public function messages(): array
    {
        return [
            // Mã (code)
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'code.string' => 'Mã giảm giá phải là chuỗi.',
            'code.max' => 'Mã giảm giá không được vượt quá 20 ký tự.',
            'code.regex' => 'Mã giảm giá chỉ cho phép chữ và số, không ký tự đặc biệt.',

            // Kiểu áp dụng (apply_type)
            'apply_type.required' => 'Vui lòng chọn kiểu áp dụng.',
            'apply_type.in' => 'Kiểu áp dụng không hợp lệ.',
            'product_ids.required_if' => 'Bạn phải chọn ít nhất 1 sản phẩm khi áp dụng theo sản phẩm.',
            'category_ids.required_if' => 'Bạn phải chọn ít nhất 1 danh mục khi áp dụng theo danh mục.',

            // Loại giảm giá (discount_type)
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',

            // Giá trị (value)
            'value.required' => 'Vui lòng nhập giá trị giảm.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn 0.',
            'value.max' => 'Nếu là phần trăm thì tối đa 100%.',

            // Giảm tối đa (max_discount_amount)
            'max_discount_amount.numeric' => 'Số tiền giảm tối đa phải là số.',
            'max_discount_amount.min' => 'Số tiền giảm tối đa phải lớn hơn hoặc bằng 0.',

            // Giá trị đơn tối thiểu/tối đa
            'min_order_value.numeric' => 'Giá trị đơn tối thiểu phải là số.',
            'min_order_value.min' => 'Giá trị đơn tối thiểu phải lớn hơn hoặc bằng 0.',
            'max_order_value.numeric' => 'Giá trị đơn tối đa phải là số.',
            'max_order_value.min' => 'Giá trị đơn tối đa phải lớn hơn hoặc bằng 0.',
            'max_order_value.gte' => 'Giá trị đơn tối đa phải lớn hơn hoặc bằng tối thiểu.',

            // Số lần dùng mỗi người
            'max_usage_per_user.integer' => 'Số lần dùng mỗi người phải là số nguyên.',
            'max_usage_per_user.min' => 'Số lần dùng mỗi người phải lớn hơn hoặc bằng 1.',

            // Ngày bắt đầu/kết thúc
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không đúng định dạng (YYYY-MM-DD).',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không đúng định dạng (YYYY-MM-DD).',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',

            // Trạng thái
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }



    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                'unique:coupons,code',
                'regex:/^[A-Za-z0-9]+$/',
            ],
            'apply_type' => ['required', 'in:all,product,category,user'],
            'product_ids' => ['required_if:apply_type,product'],
            'category_ids' => ['required_if:apply_type,category'],
            'discount_type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'numeric', 'min:1', 'max:100'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_order_value' => ['nullable', 'numeric', 'min:0', 'gte:min_order_value'],
            'max_usage_per_user' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:0,1'],
        ];
    }
}
