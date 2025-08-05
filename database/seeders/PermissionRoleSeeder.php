<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Gán tất cả quyền cho admin (role_id = 1)
        $allPermissions = DB::table('permissions')->pluck('id')->toArray();
        $adminData = array_map(fn($id) => [
            'permission_id' => $id,
            'role_id' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ], $allPermissions);

        DB::table('permission_role')->insertOrIgnore($adminData);

        // Gán quyền cho editor (role_id = 2)
        $editorPermissions = DB::table('permissions')
    ->whereIn('name', ['view_users', 'edit_users', 'manage_content', 'manage_coupons'])
    ->pluck('id')
    ->toArray();


        $editorData = array_map(fn($id) => [
            'permission_id' => $id,
            'role_id' => 2,
            'created_at' => $now,
            'updated_at' => $now,
        ], $editorPermissions);

        DB::table('permission_role')->insertOrIgnore($editorData);

        // Gán quyền cho viewer (role_id = 3)
        $viewerPermissionId = DB::table('permissions')
            ->where('name', 'view_users')
            ->value('id');

        if ($viewerPermissionId) {
            DB::table('permission_role')->insertOrIgnore([
                'permission_id' => $viewerPermissionId,
                'role_id' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
