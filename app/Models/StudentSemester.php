<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSemester extends Model
{
    protected $fillable = [
        'student_id',
        'semester_number',
        'gpa',
        'credits',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'gpa' => 'float',
        'credits' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    
    public function classAttendeds()
    {
        return $this->hasMany(ClassAttended::class, 'student_id', 'student_id')
            ->whereColumn('class_attendeds.semester', 'student_semesters.semester_number');
    }

}
