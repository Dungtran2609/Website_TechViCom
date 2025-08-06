<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = optional($this->route('user'))->id; // Sử dụng id thay vì user_id

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId, 'id')], // id là khóa chính mặc định
            'password' => $this->isMethod('post') ? 'required|string|min:8' : 'nullable|string|min:8',
            'phone_number' => 'nullable|string|max:15',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id', // Sử dụng id thay vì role_id
            'is_active' => 'nullable|boolean',

            // Validation cho địa chỉ (nếu cần)
            'address_line' => 'nullable|string|max:500',
            'ward' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên người dùng là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'phone_number.max' => 'Số điện thoại không được vượt quá :max ký tự.',
            'roles.required' => 'Vui lòng chọn ít nhất một vai trò.',
            'roles.*.exists' => 'Vai trò không hợp lệ.',
        ];
    }
}
