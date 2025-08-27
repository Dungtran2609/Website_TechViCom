<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ReplyProductCommentRequest extends FormRequest
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
            'reply_content' => [
                'required',
                'string',
                'min:5',
                'max:200',
                'regex:/^[^<>]*$/', // Không cho phép HTML tags
                function ($attribute, $value, $fail) {
                    // Kiểm tra nội dung spam
                    $spamWords = ['spam', 'advertisement', 'quảng cáo', 'mua ngay', 'giá rẻ', 'khuyến mãi'];
                    $lowerValue = strtolower($value);
                    
                    foreach ($spamWords as $word) {
                        if (str_contains($lowerValue, $word)) {
                            $fail('Nội dung phản hồi không được chứa từ khóa quảng cáo.');
                            break;
                        }
                    }
                    
                    // Kiểm tra ký tự lặp lại quá nhiều
                    if (preg_match('/(.)\1{4,}/', $value)) {
                        $fail('Nội dung phản hồi không được chứa ký tự lặp lại quá nhiều.');
                    }
                    
                    // Kiểm tra nội dung quá ngắn sau khi loại bỏ khoảng trắng
                    if (strlen(trim($value)) < 5) {
                        $fail('Nội dung phản hồi phải có ít nhất 5 ký tự có nghĩa.');
                    }
                }
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'reply_content.required' => 'Nội dung phản hồi không được để trống!',
            'reply_content.string' => 'Nội dung phản hồi phải là văn bản!',
            'reply_content.min' => 'Nội dung phản hồi phải có ít nhất 5 ký tự!',
            'reply_content.max' => 'Nội dung phản hồi không được quá 200 ký tự!',
            'reply_content.regex' => 'Nội dung phản hồi không được chứa HTML tags!',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'reply_content' => 'nội dung phản hồi',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Loại bỏ khoảng trắng thừa
        if ($this->has('reply_content')) {
            $this->merge([
                'reply_content' => trim($this->reply_content)
            ]);
        }
    }
} 