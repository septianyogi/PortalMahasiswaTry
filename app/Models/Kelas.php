<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable=[
        'code',
        'jurusan_id',
        'name',
        'date',
        'time',
        'dosen_id',
        'quota',
        'room',
        'semester',
        'attendance',
    ];

    public function dosen() {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'nip');
    }
}
