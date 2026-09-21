<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role', 'active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'      => 'hashed',
            'active'        => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function profile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function permohonans()
    {
        return $this->hasMany(Permohonan::class, 'student_id');
    }

    public function notifs()
    {
        return $this->hasMany(Notif::class);
    }
}
