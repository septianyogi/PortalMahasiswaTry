<?php

namespace App\Services;

use App\Models\ClassAttended;
use Illuminate\Support\Facades\Auth;

/**
 * Class ClassesService
 * @package App\Services
 */
class ClassAttendedService
{

    public function viewClassAttended()
    {
        $user = Auth::user();
        $classAttended = ClassAttended::eith('class')->where('student_id', $user->id)->get();
        return $classAttended;
    }


    public function createClassAttended(array $data)
    {

        $classAttended = ClassAttended::create([
            'class_id' => $data['class_id'],
            'student_id' => $data['student_id'],
            'attendance' => $data['attendance'] ?? 0,
            'absent' => $data['absent'] ?? 0,
            'mid_exam' => $data['mid_exam'] ?? 0,
            'final_exam' => $data['final_exam'] ?? 0,
            'final_score' => $data['final_score'] ?? 0,
        ]);
     
        return $classAttended;
    }
}
