<?php

namespace App\Http\Controllers;

use App\Models\ClassAttended;
use App\Services\GradeCalculator;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected $gradeCalculator;

    public function __construct(GradeCalculator $gradeCalculator)
    {
        $this->gradeCalculator = $gradeCalculator;
    }

    /**
     * Update nilai untuk satu class_attended
     */
    public function update(Request $request, $classAttendedId)
    {
        $request->validate([
            'assignment_1' => 'nullable|integer|min:0|max:100',
            'assignment_2' => 'nullable|integer|min:0|max:100',
            'assignment_3' => 'nullable|integer|min:0|max:100',
            'assignment_4' => 'nullable|integer|min:0|max:100',
            'mid_exam'     => 'nullable|integer|min:0|max:100',
            'final_exam'   => 'nullable|integer|min:0|max:100',
        ]);

        $classAttended = ClassAttended::findOrFail($classAttendedId);

        // Pastikan user memiliki akses (misal dosen pengampu atau admin)
        // $this->authorize('update', $classAttended);

        // Update nilai komponen
        $classAttended->update($request->only([
            'assignment_1', 'assignment_2', 'assignment_3', 'assignment_4',
            'mid_exam', 'final_exam'
        ]));

        // Hitung ulang final score, letter, GPA
        $classAttended = $this->gradeCalculator->calculate($classAttended);
        
        return $this->responseOk($classAttended->load(['class', 'student.user']), 'Grade Updated Successfully');
        
    }

    /**
     * Tampilkan detail grade mahasiswa untuk satu kelas
     */
    public function show($classAttendedId)
    {
        $grade = ClassAttended::with(['class', 'student.user'])
            ->findOrFail($classAttendedId);

        return $this->responseOk($grade, 'Class loaded Successfully');
    }

    /**
     * Tampilkan semua grade mahasiswa (untuk student)
     */
    public function studentGrades($studentId)
    {
        $grades = ClassAttended::where('student_id', $studentId)
            ->with('class')
            ->get();

        $gpa = $this->gradeCalculator->calculateGPAForStudent($studentId);
        $response = [
            'grades' =>  $grades,
            'gpa' => $gpa
        ];

        return $this->responseOk($response);

    }
}