<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = ['user_id', 'nisn', 'kelas', 'tgl_lahir', 'telp', 'alamat', 'wali', 'telp_wali'];

    protected function casts(): array
    {
        return ['tgl_lahir' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
