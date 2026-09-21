<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notif extends Model
{
    protected $fillable = ['user_id', 'permohonan_id', 'text', 'read'];

    protected function casts(): array
    {
        return ['read' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }
}
