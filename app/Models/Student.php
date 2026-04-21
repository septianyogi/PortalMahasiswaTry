<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'npm',
        'name',
        'email',
        'jurusan_id',
        'fakultas_id',
        'semester',
        'dob',
        'country',
        'province',
        'city',
        'subdistrict',
        'postal_code',
        'alamat',
        'pembayaran'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id', 'id');
    }
    public function classAttended()
    {
        return $this->hasMany(ClassSession::class, 'student_id', 'id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
    
}
