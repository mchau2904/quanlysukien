<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // kế thừa để dùng Auth
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'dob',
        'gender',
        'phone',
        'email',
        'class',
        'faculty',
        'role',
        'created_at',
    ];

    protected $hidden = [
        'password',
    ];

    // Mã hóa mật khẩu tự động
    protected static function booted()
    {
        static::creating(function ($user) {
            if (!empty($user->password)) {
                $user->password = sha1($user->password);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                $user->password = sha1($user->password);
            }
        });
    }

    // Kiểm tra role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}
