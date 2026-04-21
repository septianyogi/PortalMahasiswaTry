<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable=[
        'user_id',
        'nip',
        'name',
        'dob',
        'email'
    ];

    public function classes(){
        return $this->hasMany(Classes::class, 'dosen_id', 'nip');
    }
}