<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;

    protected $fillable =[
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
    
}
