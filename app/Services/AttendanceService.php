<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class ClassSessionService
 * @package App\Services
 */
class AttendanceService
{

    public function createAttendance(array $data)
    {
        $classSession = ClassSession::where('qr_token', $data['qr_token'])
            ->first();

        $userId = Auth::user()->id;

        $student = User::where('id', $userId)
            ->first();
        

        if (!$classSession) {
            throw new \Exception('Invalid QR Token', 404);
        } elseif ($classSession->expired_at < now()) {
            throw new \Exception('QR Token has expired', 400);
        } elseif (!$classSession->is_active) {
            throw new \Exception('QR Token is not active', 400);
        }
        $existingAttendance = Attendance::where('session_id', $classSession->id)
            ->where('student_id', $student->id)
            ->first();
        
        if ($existingAttendance) {
            throw new \Exception('Attendance already recorded for this session', 400);
        } else {
            $attendance = Attendance::create([
                'session_id' => $classSession->id,
                'student_id' => $student->id,
                'class_id' => $classSession->class_id,
                'week' => $classSession->week,
                'status' => 'hadir',
                'scanned_at' => now(),
            ]);
            return $attendance;
        }

    }

    public function getAttendance(array $data) 
    {
        try {
            $userId = Auth::user()->id;
            $student = Student::where('id', $userId)->first();
            $attendance = Attendance::where('student_id', $student->id)
                ->where('class_id', $data['class_id'])
                ->get();
            
            return $attendance;
                
        } catch (\Throwable $th){
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }

    
}
