<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('permissions')->insert([
            [
                'name' => 'view_users',
                'description' => 'Xem danh sách tài khoản',
                'module' => 'users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'edit_users',
                'description' => 'Chỉnh sửa thông tin tài khoản',
                'module' => 'users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'delete_users',
                'description' => 'Xoá tài khoản',
                'module' => 'users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'manage_roles',
                'description' => 'Quản lý các vai trò hệ thống',
                'module' => 'roles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'manage_content',
                'description' => 'Quản lý nội dung website',
                'module' => 'content',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'manage_coupons',
                'description' => 'Quản lý mã giảm giá và khuyến mãi',
                'module' => 'coupons',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
