<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'code', 'jurusan_id', 'name', 'date', 'time_start', 'time_end',
        'credits', 'dosen_id', 'quota', 'current_quota', 'room', 'semester',
        'weight_assignment', 'weight_mid', 'weight_final',
        'weight_assignment_1', 'weight_assignment_2', 'weight_assignment_3', 'weight_assignment_4'
    ];

    protected $casts = [
        'weight_assignment' => 'float',
        'weight_mid'        => 'float',
        'weight_final'      => 'float',
        'weight_assignment_1' => 'float',
        'weight_assignment_2' => 'float',
        'weight_assignment_3' => 'float',
        'weight_assignment_4' => 'float',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function classAttended()
    {
        return $this->hasMany(ClassAttended::class, 'class_id');
    }
}
