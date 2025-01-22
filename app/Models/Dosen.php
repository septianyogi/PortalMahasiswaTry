<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable=[
        'nip',
        'name',
        'dob',
        'email'
    ];

    public function classes(){
        return $this->hasMany(Kelas::class, 'dosen_id', 'nip');
    }
}