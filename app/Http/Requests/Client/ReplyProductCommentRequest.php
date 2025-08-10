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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reply_content' => 'required|string|min:5|max:200',
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
            'reply_content.required' => 'Nội dung phản hồi không được để trống!',
            'reply_content.min' => 'Nội dung phản hồi phải có ít nhất 5 ký tự!',
            'reply_content.max' => 'Nội dung phản hồi không được quá 200 ký tự!',
        ];
    }
} 