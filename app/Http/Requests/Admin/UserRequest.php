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
        $user = $this->route('user');
        $userId = $user?->id;
        $isUpdating = $user !== null;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId, 'id')],
            'password' => $isUpdating ? ['nullable', 'string', 'min:8', 'confirmed'] : ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => $isUpdating ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],
            'phone_number' => ['nullable', 'string', 'max:15', 'regex:/^[0-9+\-\s]+$/'],
            'birthday' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'exists:roles,id'],
            'is_active' => ['nullable', 'boolean'],

            // Validation cho hình ảnh
            'image_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],

            // Validation cho địa chỉ
            'address_line' => ['nullable', 'string', 'max:500'],
            'ward' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            // Name validation messages
            'name.required' => 'Tên người dùng là bắt buộc.',
            'name.string' => 'Tên người dùng phải là chuỗi ký tự.',
            'name.max' => 'Tên người dùng không được vượt quá :max ký tự.',

            // Email validation messages
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',

            // Password validation messages
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password_confirmation.required' => 'Xác nhận mật khẩu là bắt buộc.',
            'password_confirmation.string' => 'Xác nhận mật khẩu phải là chuỗi ký tự.',
            'password_confirmation.min' => 'Xác nhận mật khẩu phải có ít nhất :min ký tự.',

            // Phone validation messages
            'phone_number.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone_number.max' => 'Số điện thoại không được vượt quá :max ký tự.',
            'phone_number.regex' => 'Số điện thoại không hợp lệ.',

            // Birthday validation messages
            'birthday.date' => 'Ngày sinh không hợp lệ.',
            'birthday.before' => 'Ngày sinh phải trước ngày hôm nay.',

            // Gender validation messages
            'gender.in' => 'Giới tính không hợp lệ.',

            // Roles validation messages
            'roles.required' => 'Vui lòng chọn ít nhất một vai trò.',
            'roles.array' => 'Dữ liệu vai trò không hợp lệ.',
            'roles.min' => 'Phải chọn ít nhất một vai trò.',
            'roles.*.required' => 'Vai trò là bắt buộc.',
            'roles.*.exists' => 'Vai trò không hợp lệ.',

            // Status validation messages
            'is_active.boolean' => 'Trạng thái hoạt động không hợp lệ.',

            // Image validation messages
            'image_profile.image' => 'Ảnh đại diện phải là hình ảnh.',
            'image_profile.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            'image_profile.max' => 'Ảnh đại diện không được vượt quá :max KB.',

            // Address validation messages
            'address_line.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address_line.max' => 'Địa chỉ không được vượt quá :max ký tự.',
            'ward.string' => 'Phường/Xã phải là chuỗi ký tự.',
            'ward.max' => 'Phường/Xã không được vượt quá :max ký tự.',
            'district.string' => 'Quận/Huyện phải là chuỗi ký tự.',
            'district.max' => 'Quận/Huyện không được vượt quá :max ký tự.',
            'city.string' => 'Tỉnh/Thành phố phải là chuỗi ký tự.',
            'city.max' => 'Tỉnh/Thành phố không được vượt quá :max ký tự.',
            'is_default.boolean' => 'Địa chỉ mặc định không hợp lệ.',
        ];
    }
}
