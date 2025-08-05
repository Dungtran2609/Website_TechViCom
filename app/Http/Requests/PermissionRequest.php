<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Kiểm tra xem người dùng có quyền thực hiện hành động này không.
     */
    public function authorize(): bool
    {
        // Kiểm tra theo phương thức HTTP để phân quyền tạo/sửa
        if ($this->isMethod('POST')) {
            return auth()->check() && auth()->user()->can('create_permission');
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return auth()->check() && auth()->user()->can('edit_permission');
        }

        return false;
    }

    /**
     * Quy tắc validate cho quyền (permission)
     */
    public function rules(): array
    {
        $permissionId = $this->route('permission')?->id ?? null;

        return [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permissionId,
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Thông báo lỗi tuỳ chỉnh
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên quyền là bắt buộc.',
            'name.unique' => 'Tên quyền đã tồn tại.',
            'name.max' => 'Tên quyền không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        ];
    }
}
