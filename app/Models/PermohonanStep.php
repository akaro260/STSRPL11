<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanStep extends Model
{
    protected $fillable = ['permohonan_id', 'actor_name', 'role', 'status', 'note'];

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }
}
