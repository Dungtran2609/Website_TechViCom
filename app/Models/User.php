<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'phone',
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
        'birthday' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $dates = ['deleted_at'];

    // You can keep other relationships like this one
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    // Custom role relationship (not using Spatie)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    // Custom hasRole method for our role system
    public function hasRole($role)
    {
        if (is_array($role)) {
            return $this->roles()->whereIn('name', $role)->exists();
        }
        return $this->roles()->where('name', $role)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
