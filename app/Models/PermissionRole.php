<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class PermissionRole extends Model
{
    protected $table = 'permission_role';


    protected $fillable = [
        'permission_id',
        'role_id',
    ];


    public $timestamps = true;


    // Nếu bạn muốn dùng quan hệ:
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
