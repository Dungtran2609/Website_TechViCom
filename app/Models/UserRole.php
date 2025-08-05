<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserRole extends Model
{
    use HasFactory;


    // Tên bảng
    protected $table = 'user_roles';


    // Các cột có thể điền vào
    protected $fillable = [
        'user_id',
        'role_id',
    ];


    /**
     * Liên kết với model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    /**
     * Liên kết với model Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}


