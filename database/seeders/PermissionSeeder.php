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
                'description' => 'Xem danh sách người dùng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'edit_users',
                'description' => 'Chỉnh sửa người dùng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'delete_users',
                'description' => 'Xoá người dùng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'manage_roles',
                'description' => 'Quản lý vai trò',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'manage_content',
                'description' => 'Quản lý nội dung',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
    'name' => 'manage_coupons',
    'description' => 'Quản lý mã giảm giá',
    'created_at' => now(),
    'updated_at' => now(),
],

        ]);
    }
}
