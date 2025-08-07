<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    /**
     * Kiểm tra xem người dùng có quyền thực hiện hành động này không.
     */
    public function authorize(): bool
    {
        // Luôn cho phép nếu user đã authenticated (có thể customize theo nhu cầu)
        return true;
        
        // Hoặc sử dụng logic phân quyền chi tiết hơn:
        /*
        if ($this->isMethod('POST')) {
            return auth()->check() && auth()->user()->can('create_permission');
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return auth()->check() && auth()->user()->can('edit_permission');
        }

        return auth()->check();
        */
    }

    /**
     * Quy tắc validate cho quyền (permission)
     */
    public function rules(): array
    {
        $permission = $this->route('permission');
        $permissionId = $permission?->id;
        $isUpdating = $permission !== null;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9_\-\s]+$/',
                Rule::unique('permissions', 'name')->ignore($permissionId, 'id')
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9_\-]+$/',
                Rule::unique('permissions', 'slug')->ignore($permissionId, 'id')
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'category' => ['nullable', 'string', 'max:100'],
        ];

        return $rules;
    }

    /**
     * Thông báo lỗi tuỳ chỉnh
     */
    public function messages(): array
    {
        return [
            // Name validation messages
            'name.required' => 'Tên quyền là bắt buộc.',
            'name.string' => 'Tên quyền phải là chuỗi ký tự.',
            'name.max' => 'Tên quyền không được vượt quá :max ký tự.',
            'name.regex' => 'Tên quyền chỉ được chứa chữ cái, số, dấu gạch dưới và dấu gạch ngang.',
            'name.unique' => 'Tên quyền đã tồn tại trong hệ thống.',

            // Slug validation messages
            'slug.string' => 'Slug phải là chuỗi ký tự.',
            'slug.max' => 'Slug không được vượt quá :max ký tự.',
            'slug.regex' => 'Slug chỉ được chứa chữ cái, số, dấu gạch dưới và dấu gạch ngang.',
            'slug.unique' => 'Slug đã tồn tại trong hệ thống.',

            // Description validation messages
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá :max ký tự.',

            // Status validation messages
            'status.in' => 'Trạng thái không hợp lệ.',

            // Category validation messages
            'category.string' => 'Danh mục phải là chuỗi ký tự.',
            'category.max' => 'Danh mục không được vượt quá :max ký tự.',
        ];
    }
}
