<?php

namespace App\Services;

use App\Models\ClassAttended;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Class ClassesService
 * @package App\Services
 */
class ClassAttendedService
{

    private function cacheKey($userId)
    {
        return "class_attended_user_" . $userId;
    }

    public function viewDosenClassAttended()
    {
        $user = Auth::user()->id;
        $classAttended = ClassAttended::with(['student', 'class'])
        ->whereHas('class.dosen', function ($q) use ($user) {
            $q->where('user_id', $user);
        })->get();

        return $classAttended;

    }

    public function viewClassAttended()
    {
        $user = Auth::user()->id;
        $cacheKey = $this->cacheKey($user);

        return Cache::remember($cacheKey, 300, function () use ($user) {
        return $classAttended = ClassAttended::with('class:id,code,name,date,time_start,time_end,dosen_id')
        ->where('student_id', $user)
        ->select('id', 'class_id', 'student_id')->get();
        });
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

        Cache::forget($this->cacheKey($data['student_id']));
     
        return $classAttended;
    }

    public function updateClassAttended(int $id, array $data)
    {
        $classAttended = ClassAttended::findOrFail($id);
        $classAttended->update([
            'class_id' => $data['class_id'] ?? $classAttended->class_id,
            'student_id' => $data['student_id'] ?? $classAttended->student_id,
            'attendance' => $data['attendance'] ?? $classAttended->attendance,
            'absent' => $data['absent'] ?? $classAttended->absent,
            'mid_exam' => $data['mid_exam'] ?? $classAttended->mid_exam,
            'final_exam' => $data['final_exam'] ?? $classAttended->final_exam,
            'final_score' => $data['final_score'] ?? $classAttended->final_score,
        ]);

        return $classAttended;
    }

    public function deleteClassAttended(int $id)
    {
        $classAttended = ClassAttended::findOrFail($id);
        $classAttended->delete();
        return true;
    }
}
