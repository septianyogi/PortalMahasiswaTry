<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
    'session_id',
    'student_id',
    'status',
    'scanned_at',
];
}
