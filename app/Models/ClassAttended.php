<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAttended extends Model
{
    protected $table = 'class_attendeds';

    protected $fillable = [
        'class_id',
        'student_id',
        'verified_at',
        'attendance',
        'absent',
        'mid_exam',
        'final_exam',
        'final_score',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id', 'class_id');
    }
}
