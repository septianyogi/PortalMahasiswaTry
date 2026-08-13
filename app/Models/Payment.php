<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'student_id',
        'type',
        'description',
        'amount',
        'status',
    ];

   

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
