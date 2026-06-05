<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = [
    'class_id',
    'week',
    'code_duration',
    'qr_token',
    'expired_at',
    'is_active',
];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
            'is_active' => 'boolean'
        ];
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
