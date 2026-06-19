<?php

namespace App\Services;

use App\Models\ClassAttended;
use App\Models\Student;
use App\Models\StudentSemester;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class SemesterService
 * @package App\Services
 */
class SemesterService
{
    public function advanceAll(): array
    {
        $students = Student::whereHas('studentSemesters', function ($s){
            $s->where('status', 'active');
        })->get();

        $success = 0;
        $failed = 0;

        foreach ($students as $student) {
            try {
                $this->advanceStudent($student);
                $success++;
            } catch (\Throwable $th) {
                Log::error("Advance semester failed for student {$student->id}: " . $th->getMessage());
                $failed++;
            }
        }
        return [
            'message' => "Semester advancement completed.",
            'success' => $success,
            'failed' => $failed,
        ];
    }

    

    public function advanceStudent(Student $student): Student
    {
        DB::beginTransaction();

        try {

            $semesterData = $this->getSemesterGradeData($student);
            $studentSemester = StudentSemester::where('student_id', $student->id)
                ->where('semester_number', $student->semester)
                ->first();

            $studentSemester->update([
               'gpa' => $semesterData['gpa'],
               'credits' => $semesterData['credits'],
               'status' => $this->determineStatus($semesterData['gpa']),
               'end_date' => Carbon::now()
            ]);

            $student->semester +=1;
            $student->credits += $semesterData['credits'];
            $student->gpa = $this->calculateCumulativeGPA($student);
            $student->semester_start_date = Carbon::now();
            $student->save;

            StudentSemester::create([
                'student_id' => $student->id,
                'semester_number' => $student->semester,
                'credits' => 0,
                'start_date' => Carbon::now()->startOfYear(),
            ]);

            DB::commit();

            return $student;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function getSemesterGradeData(Student $student): array
    {
        $classAttendeds = ClassAttended::where('student_id', $student->id)
            ->where('semester', $student->semester)
            ->with('class')
            ->get();

        $totalCredits = 0;
        $totalQualityPoints = 0;

        foreach ($classAttendeds as $class) {
            if (!is_null($class->gpa)) {
                $credits = $class->class->credits;
                $totalCredits += $credits;
                $totalQualityPoints += $credits * $class->gpa;
            }
        }

        $gpa = $totalCredits > 0 ? round($totalQualityPoints / $totalCredits, 2) : 0.0;

        return[
            'gpa' => $gpa,
            'credits' => $totalCredits
        ];
    }

    private function calculateCumulativeGPA(Student $student): float
    {
        $semesters = StudentSemester::where('student_id', $student->id)
            ->where('status', 'completed')
            ->get();

        $totalCredits = 0;
        $totalQualityPoints = 0;

        foreach ($semesters as $sem) {
            $totalCredits += $sem->credits;
            $totalQualityPoints += $sem->credits * $sem->gpa;
        }

        return $totalCredits > 0 ? round($totalQualityPoints / $totalCredits, 2) : 0.0;
    }

    private function determineStatus(float $gpa): string
    {
        return $gpa >= 2.0 ? 'completed' : 'dropped';
    }

}
