<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    // Thứ tự các Trait không quá quan trọng, nhưng đây là thứ tự phổ biến
    use  HasFactory, Notifiable, SoftDeletes, HasRoles;


    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'image_profile',
        'is_active',
        'birthday',
        'gender',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean', // Ép kiểu is_active về boolean
    ];


    protected $dates = ['deleted_at'];


    /**
     * Quan hệ nhiều-nhiều với Role.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }


    /**
     * Quan hệ một-nhiều với UserAddress.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }


    /**
     * Kiểm tra xem người dùng có BẤT KỲ vai trò nào trong danh sách cho trước không.
     * Phương thức này dùng cho Middleware CheckRole.
     *
     * @param array $roles Mảng các tên vai trò cần kiểm tra (ví dụ: ['admin', 'staff']).
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        // Giả sử cột tên vai trò trong bảng `roles` của bạn là 'name'. Nếu là 'slug', hãy đổi 'name' thành 'slug'.
        return $this->roles()->whereIn('name', $roles)->exists();
    }


    /**
     * Kiểm tra xem người dùng có phải admin không.
     */
    public function isAdmin(): bool
    {
        // Giả sử slug của admin là 'admin'
        return $this->roles()->where('name', 'admin')->exists();
    }
    public function hasPermission(string $permissionName): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permissionName)) {
                return true;
            }
        }

        return false;
    }
}


