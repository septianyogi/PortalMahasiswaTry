<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAttended extends Model
{

    protected $fillable = [
        'student_id',
        'student_name',
        'class_id',
        'attendance',
        'absent',
        'assignment',
        'mid_exam',
        'final_exam',
        'final_score'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'npm');
    }

    public function class()
    {
        return $this->belongsTo(Kelas::class, 'class_id', 'id');
    }
}