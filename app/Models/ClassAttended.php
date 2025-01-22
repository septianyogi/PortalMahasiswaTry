<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAttended extends Model
{

    protected $fillable = [
        'student_id',
        'class_id',
        'attendance',
        'absent',
        'assignment',
        'mid_exam',
        'final_exam',
        'final_score'
    ];
}