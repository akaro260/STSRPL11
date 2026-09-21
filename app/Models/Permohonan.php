<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    protected $table = 'permohonan';

    protected $fillable = [
        'no', 'student_id', 'category', 'title', 'description',
        'from_date', 'to_date', 'status', 'decided_at',
    ];

    protected function casts(): array
    {
        return [
            'from_date'  => 'date',
            'to_date'    => 'date',
            'decided_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function steps()
    {
        return $this->hasMany(PermohonanStep::class)->orderBy('created_at');
    }
}
