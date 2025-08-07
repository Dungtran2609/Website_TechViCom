<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Kiểm tra xem người dùng có được phép thực hiện request không
     */
    public function authorize(): bool
    {
        // Phân quyền theo hành động HTTP
        if ($this->isMethod('POST')) {
            return auth()->check() && auth()->user()->can('create_role');
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return auth()->check() && auth()->user()->can('edit_role');
        }

        return false;
    }

    /**
     * Quy tắc validate cho vai trò
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:roles,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|boolean',
        ];
    }

    /**
     * Thông báo lỗi tùy chỉnh
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên vai trò là bắt buộc.',
            'name.max' => 'Tên vai trò không được vượt quá 255 ký tự.',
            'parent_id.exists' => 'Vai trò cha không tồn tại.',
            'image.image' => 'File tải lên phải là ảnh.',
            'image.mimes' => 'Ảnh chỉ chấp nhận định dạng jpeg, png, jpg, gif, svg.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.boolean' => 'Trạng thái phải là true hoặc false.',
        ];
    }
}
