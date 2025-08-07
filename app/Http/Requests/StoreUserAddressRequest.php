<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address_line' => ['required', 'string', 'max:255'],
            'ward' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'address_line.required' => 'Địa chỉ chi tiết là bắt buộc.',
            'address_line.string' => 'Địa chỉ chi tiết phải là chuỗi ký tự.',
            'address_line.max' => 'Địa chỉ chi tiết không được vượt quá :max ký tự.',
            
            'ward.required' => 'Phường/Xã là bắt buộc.',
            'ward.string' => 'Phường/Xã phải là chuỗi ký tự.',
            'ward.max' => 'Phường/Xã không được vượt quá :max ký tự.',
            
            'district.required' => 'Quận/Huyện là bắt buộc.',
            'district.string' => 'Quận/Huyện phải là chuỗi ký tự.',
            'district.max' => 'Quận/Huyện không được vượt quá :max ký tự.',
            
            'city.required' => 'Tỉnh/Thành phố là bắt buộc.',
            'city.string' => 'Tỉnh/Thành phố phải là chuỗi ký tự.',
            'city.max' => 'Tỉnh/Thành phố không được vượt quá :max ký tự.',
            
            'is_default.boolean' => 'Trường đặt làm mặc định phải là true hoặc false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'address_line' => 'địa chỉ chi tiết',
            'ward' => 'phường/xã',
            'district' => 'quận/huyện', 
            'city' => 'tỉnh/thành phố',
            'is_default' => 'đặt làm mặc định',
        ];
    }
}
