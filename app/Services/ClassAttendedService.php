<?php

namespace App\Services;

use App\Models\ClassAttended;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

    public function viewDosenClassAttended(int $classId)
    {
        $user = Auth::user()->id;
        $classAttended =  Classes::with(['classAttended' => function ($q) {
                    $q->select(
                        'id',
                        'class_id',
                        'student_id',
                        'attendance',
                        'absent',
                        'mid_exam',
                        'final_exam',
                        'final_score'
                    );
                },
                'classAttended.student' => function ($q) {
                    $q->select('id', 'npm', 'name', 'email');
                }])
            ->where('id', $classId)
            ->whereHas('dosen', function ($query) use ($user) {
                $query->where('user_id', $user);
            })->first();

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


    public function createClassAttended(int $classId)
    {
        DB::transaction(function () use ($classId) {
            $class = Classes::where('id', $classId)
                            ->lockForUpdate()
                            ->first();

            if ($class->current_quota >= $class->quota) {
                throw new \Exception('Class is full');
            }
            
            $user = Auth::user()->id;
            $student = Student::where('user_id', $user)->first();
            $classAttended = ClassAttended::create([
                'class_id' => $classId,
                'student_id' => $student
            ]);

            $class::increment('current_quota');

            Cache::forget($this->cacheKey($user));
            return $classAttended;
        });
     
        
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
