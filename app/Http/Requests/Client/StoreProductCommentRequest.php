<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
            'content' => [
                'required',
                'string',
                'min:10',
                'max:500',
                'regex:/^[^<>]*$/', // Không cho phép HTML tags
                function ($attribute, $value, $fail) {
                    // Kiểm tra nội dung spam
                    $spamWords = ['spam', 'advertisement', 'quảng cáo', 'mua ngay', 'giá rẻ', 'khuyến mãi'];
                    $lowerValue = strtolower($value);
                    
                    foreach ($spamWords as $word) {
                        if (str_contains($lowerValue, $word)) {
                            $fail('Nội dung đánh giá không được chứa từ khóa quảng cáo.');
                            break;
                        }
                    }
                    
                    // Kiểm tra ký tự lặp lại quá nhiều
                    if (preg_match('/(.)\1{4,}/', $value)) {
                        $fail('Nội dung đánh giá không được chứa ký tự lặp lại quá nhiều.');
                    }
                    
                    // Kiểm tra nội dung quá ngắn sau khi loại bỏ khoảng trắng
                    if (strlen(trim($value)) < 10) {
                        $fail('Nội dung đánh giá phải có ít nhất 10 ký tự có nghĩa.');
                    }
                }
            ],
            'rating' => 'required|integer|min:1|max:5',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'order_id.required' => 'Vui lòng chọn đơn hàng để đánh giá!',
            'order_id.exists' => 'Đơn hàng không tồn tại!',
            'order_id.integer' => 'ID đơn hàng không hợp lệ!',
            
            'content.required' => 'Nội dung bình luận không được để trống!',
            'content.string' => 'Nội dung bình luận phải là văn bản!',
            'content.min' => 'Nội dung bình luận phải có ít nhất 10 ký tự!',
            'content.max' => 'Nội dung bình luận không được quá 500 ký tự!',
            'content.regex' => 'Nội dung bình luận không được chứa HTML tags!',
            
            'rating.required' => 'Vui lòng chọn đánh giá sao!',
            'rating.integer' => 'Đánh giá phải là số nguyên!',
            'rating.min' => 'Đánh giá phải từ 1-5 sao!',
            'rating.max' => 'Đánh giá phải từ 1-5 sao!',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'order_id' => 'đơn hàng',
            'content' => 'nội dung đánh giá',
            'rating' => 'đánh giá sao',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Loại bỏ khoảng trắng thừa
        if ($this->has('content')) {
            $this->merge([
                'content' => trim($this->content)
            ]);
        }
    }
} 