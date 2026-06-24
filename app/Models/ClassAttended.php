<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAttended extends Model
{
    protected $table = 'class_attendeds';

    protected $fillable = [
       'class_id', 'student_id', 'semester', 'verified_at',
        'attendance', 'absent',
        'assignment_1', 'assignment_2', 'assignment_3', 'assignment_4',
        'mid_exam', 'final_exam',
        'final_score', 'letter_grade', 'gpa'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'final_score' => 'float',
        'gpa' => 'float',
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

    public function studentSemester()
    {
        return $this->belongsTo(StudentSemester::class, 'student_id', 'student_id')
            ->whereColumn('class_attendeds.semester', 'student_semesters.semester_number');
    }
}
