<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends Model
{
    use HasFactory, SoftDeletes;


    // Cho phép gán các thuộc tính
    protected $fillable = ['name'];


    // Dùng soft delete
    protected $dates = ['deleted_at'];


    /**
     * Quan hệ nhiều-nhiều với bảng users qua bảng user_roles.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }


    /**
     * Quan hệ nhiều-nhiều với bảng permissions qua bảng permission_role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }




    /**
     * Kiểm tra vai trò có một quyền cụ thể không
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions->contains('name', $permissionName);
    }
}
