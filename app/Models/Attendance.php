<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
    'session_id',
    'student_id',
    'class_id',
    'week',
    'status',
    'scanned_at',
];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function session()
    {
        return $this->belongsTo(ClassSession::class, 'class_id');
    }
}
