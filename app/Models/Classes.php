<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'code',
        'jurusan_id',
        'name',
        'date',
        'time_start',
        'time_end',
        'dosen_id',
        'quota',
        'room',
        'semester',
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
