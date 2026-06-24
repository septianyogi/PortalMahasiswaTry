<?php

namespace App\Services;

use App\Models\ClassAttended;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class GradeService
{
    /**
     * Peta nilai: threshold minimum untuk mendapatkan huruf & GPA
     */
    protected $gradeMap = [
        85 => ['letter' => 'A',  'gpa' => 4.0],
        80 => ['letter' => 'A-', 'gpa' => 3.7],
        75 => ['letter' => 'B+', 'gpa' => 3.3],
        70 => ['letter' => 'B',  'gpa' => 3.0],
        65 => ['letter' => 'B-', 'gpa' => 2.7],
        60 => ['letter' => 'C+', 'gpa' => 2.3],
        55 => ['letter' => 'C',  'gpa' => 2.0],
        50 => ['letter' => 'C-', 'gpa' => 1.7],
        40 => ['letter' => 'D',  'gpa' => 1.0],
        0  => ['letter' => 'E',  'gpa' => 0.0],
    ];

    /**
     * Show all class Attended with grade detail
     */
    public function showGradeDetail()
    {
        $userId = Auth::user()->id;
        $student = Student::where('user_id', $userId)->first();

        if (!$student) {
            throw new \Exception('Student Not Found', 404);
        }

        return ClassAttended::with('class:id,code,name')
            ->where('student_id', $student->id)
            ->select(
                'id', 'class_id', 'student_id', 'final_score', 'letter_grade', 'gpa',
                'assignment_1', 'assignment_2', 'assignment_3', 'assignment_4',
                'mid_exam', 'final_exam'
            )
            ->get(); // ← selalu return collection, tidak pernah null
    }

    public function showGpaDetail(int $classId, int $studentId)
    {
        try {
            $grade = ClassAttended::with(['class:id,code,name', 'studentSemester:gpa,credits,status'])
                ->where('class_id', $classId)
                ->where('student_id', $studentId)
                ->select('id', 'class_id', 'student_id', 'final_score', 'letter_grade', 'gpa',
                'assignment_1', 'assignment_2', 'assignment_3', 'assignment_4', 'mid_exam', 'final_exam')
                ->get();

            return $grade;
        } catch (\Throwable $th) {
            throw $th;
        }
    }



    /**
     * Hitung final score, letter grade, dan GPA untuk satu class_attended
     */
    public function calculate(ClassAttended $classAttended): ClassAttended
    {
        $course = $classAttended->class;

        // 1. Ambil nilai tugas yang tidak null
        $assignments = array_filter([
            $classAttended->assignment_1,
            $classAttended->assignment_2,
            $classAttended->assignment_3,
            $classAttended->assignment_4,
        ], fn($v) => !is_null($v));

        // 2. Hitung rata-rata tugas (jika ada) atau 0
        $avgAssignment = count($assignments) > 0 ? array_sum($assignments) / count($assignments) : 0;

        // 3. Nilai ujian (anggap 0 jika null)
        $mid = $classAttended->mid_exam ?? 0;
        $final = $classAttended->final_exam ?? 0;

        // 4. Bobot (dalam desimal)
        $weightAss = $course->weight_assignment / 100;
        $weightMid = $course->weight_mid / 100;
        $weightFinal = $course->weight_final / 100;

        // 5. Hitung final score
        $finalScore = ($avgAssignment * $weightAss) + ($mid * $weightMid) + ($final * $weightFinal);
        $finalScore = round($finalScore, 2);

        // 6. Tentukan huruf dan GPA
        $letterGrade = 'E';
        $gpa = 0.0;
        foreach ($this->gradeMap as $threshold => $data) {
            if ($finalScore >= $threshold) {
                $letterGrade = $data['letter'];
                $gpa = $data['gpa'];
                break;
            }
        }

        // 7. Simpan ke model
        $classAttended->final_score = $finalScore;
        $classAttended->letter_grade = $letterGrade;
        $classAttended->gpa = $gpa;
        $classAttended->save();

        return $classAttended;
    }

    /**
     * Hitung IPK untuk seorang mahasiswa
     */
    public function calculateGPAForStudent($studentId): float
    {
        $classAttendeds = ClassAttended::where('student_id', $studentId)
            ->with('class')
            ->get();

        $totalCredits = 0;
        $totalQualityPoints = 0;

        foreach ($classAttendeds as $ca) {
            if (!is_null($ca->gpa)) {
                $credits = $ca->class->credits;
                $totalCredits += $credits;
                $totalQualityPoints += $credits * $ca->gpa;
            }
        }

        return $totalCredits > 0 ? round($totalQualityPoints / $totalCredits, 2) : 0.0;
    }
}