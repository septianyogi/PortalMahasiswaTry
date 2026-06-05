<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\ClassAttended;
use App\Models\ClassSession;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessExpiredSessionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $sessionId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $session = ClassSession::find($this->sessionId);

        if(!$session) {
            return;
        }

        if(!$session->is_active) {
            return;
        }

        $presentStudentIds = Attendance::where('session_id', $this->sessionId)
            ->pluck('student_id')
            ->toArray();
        
        $students = ClassAttended::where('class_id', $session->class_id)
            ->get();
        
        if($students->isEmpty()) {
            $session->update([
                'is_active' => false
            ]);

            return;
        }

        $data = [];

        foreach($students as $student) {
            if(
                in_array($student->student_id, $presentStudentIds)
            ) {
                continue;
            }

             $data[] = [
                'session_id' => $session->id,
                'student_id' => $student->student_id,
                'class_id' => $session->class_id,
                'week' => $session->week,
                'status' => 'absent',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if(!empty($data)) {
            Attendance::insert($data);
        }

        $session->update([
            'is_active' => false
        ]);

    }
}
