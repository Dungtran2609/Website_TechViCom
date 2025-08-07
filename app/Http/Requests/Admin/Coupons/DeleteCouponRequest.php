<?php

namespace App\Http\Requests\Admin\Coupons;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCouponRequest extends FormRequest
{
    public function authorize(): bool
{
    return auth()->check() && auth()->user()->roles()->whereIn('name', ['admin', 'editor'])->exists();
}


    public function rules(): array
    {
        return [
            // Đảm bảo id được gửi lên và tồn tại trong bảng coupons
            'id' => 'required|exists:coupons,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Vui lòng cung cấp ID của mã giảm giá cần xóa.',
            'id.exists' => 'Mã giảm giá không tồn tại trong hệ thống.',
        ];
    }
}
