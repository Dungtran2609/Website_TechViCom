<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Laravel Permission
     |--------------------------------------------------------------------------
     */

    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_roles' => 'model_has_roles',
        'model_has_permissions' => 'model_has_permission',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        'model_morph_key' => 'model_id',
    ],

    'cache' => [
        'expiration_time' => 60 * 24, // 1 ngày
        'key' => 'spatie.permission.cache',
        'model_key' => 'name',
        'store' => 'default',
    ],

    'middleware' => [
        'permission' => Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role' => Spatie\Permission\Middleware\RoleMiddleware::class,
    ],

];
