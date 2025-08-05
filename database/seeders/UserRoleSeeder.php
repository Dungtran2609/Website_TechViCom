<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy tất cả user id và role id cần thiết
        $users = DB::table('users')->pluck('id');
        $roles = DB::table('roles')->pluck('id');

        $userRoles = [];

        // Gán role cho user id 1 (admin)
        if ($users->contains(1) && $roles->contains(1)) {
            $userRoles[] = ['user_id' => 1, 'role_id' => 1];
        }

        // Gán role cho user id 2 (editor)
        if ($users->contains(2) && $roles->contains(2)) {
            $userRoles[] = ['user_id' => 2, 'role_id' => 2];
        }

        // Gán role cho user id 3 (user)
        if ($users->contains(3) && $roles->contains(3)) {
            $userRoles[] = ['user_id' => 3, 'role_id' => 3];
        }

        // Gán role cho user id 13 (admin)
        if ($users->contains(13) && $roles->contains(1)) {
            $userRoles[] = ['user_id' => 13, 'role_id' => 1];
        }

        if (!empty($userRoles)) {
            DB::table('user_roles')->insert($userRoles);
        }
    }
}
