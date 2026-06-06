<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\ClassAttended;
use App\Models\ClassSession;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info("Processing expired session job", ['session_id' => $this->sessionId]);

        $session = ClassSession::find($this->sessionId);

        if (!$session) {
            Log::warning("Session not found", ['session_id' => $this->sessionId]);
            return;
        }

        if (!$session->is_active) {
            Log::info("Session already inactive", ['session_id' => $this->sessionId]);
            return;
        }

        // Gunakan transaction untuk memastikan semua operasi sukses
        DB::transaction(function () use ($session) {
            // Ambil daftar student_id yang sudah hadir (status 'present')
            $presentStudentIds = Attendance::where('session_id', $this->sessionId)
                ->where('status', 'present') // Hanya yang benar-benar hadir
                ->pluck('student_id')
                ->toArray();

            // Ambil semua siswa yang terdaftar di class tersebut
            $students = ClassAttended::where('class_id', $session->class_id)
                ->get();

            if ($students->isEmpty()) {
                $session->update(['is_active' => false]);
                Log::info("No students enrolled, session deactivated", ['session_id' => $this->sessionId]);
                return;
            }

            // Siapkan data insert untuk siswa absent
            $absentRecords = [];
            foreach ($students as $student) {
                if (in_array($student->student_id, $presentStudentIds)) {
                    continue; // Sudah present, skip
                }

                $absentRecords[] = [
                    'session_id' => $session->id,
                    'student_id' => $student->student_id,
                    'class_id' => $session->class_id,
                    'week' => $session->week,
                    'status' => 'absent',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($absentRecords)) {
                Attendance::insert($absentRecords);
                Log::info("Absent records inserted", [
                    'session_id' => $this->sessionId,
                    'count' => count($absentRecords)
                ]);
            }

            // Deaktivasi session setelah diproses
            $session->update(['is_active' => false]);
        });

        Log::info("Expired session job completed", ['session_id' => $this->sessionId]);
    }
}