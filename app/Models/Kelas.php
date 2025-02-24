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
        'time_start',
        'time_end',
        'dosen_id',
        'quota',
        'room',
        'semester',
        'attendance',
        'expires_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kelas) {
            $kelas->code = date('dmy').'-'.$kelas->jurusan_id.'-'.date('Hi');
        });
    }

    public function dosen() {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'nip');
    }

}
