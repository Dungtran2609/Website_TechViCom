<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
            'content' => 'required|string|min:10|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'order_id.required' => 'Vui lòng chọn đơn hàng để đánh giá!',
            'order_id.exists' => 'Đơn hàng không tồn tại!',
            'content.required' => 'Nội dung bình luận không được để trống!',
            'content.min' => 'Nội dung bình luận phải có ít nhất 10 ký tự!',
            'content.max' => 'Nội dung bình luận không được quá 500 ký tự!',
            'rating.required' => 'Vui lòng chọn đánh giá!',
            'rating.min' => 'Đánh giá phải từ 1-5 sao!',
            'rating.max' => 'Đánh giá phải từ 1-5 sao!',
        ];
    }
} 