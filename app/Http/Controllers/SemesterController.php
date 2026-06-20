<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\SemesterService;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    protected $semesterService;

    public function __construct(SemesterService $semesterService)
    {
        $this->semesterService=$semesterService;
    }

     public function advance()
    {
        $result = $this->semesterService->advanceAll();

        return response()->json([
            'message' => $result['message'],
            'data' => $result,
        ]);
    }

    public function advanceSingle(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);
        $this->semesterService->advanceStudent($student);

        return response()->json([
            'message' => 'Student semester advanced successfully.',
            'data' => $student->load('studentSemesters'),
        ]);
    }

    public function history($studentId)
    {
        $student = Student::with('studentSemesters')->findOrFail($studentId);
        return response()->json([
            'student' => $student,
            'semesters' => $student->studentSemesters,
        ]);
    }

    public function studentSemester(int $studentId)
    {
        try {
            $student = $this->semesterService->studentSemester($studentId);

            return $this->responseOk($student, 'Data Retrieve Successfully');
        } catch (\Throwable $th) {
            return $this->responseError('Failed to Retrieve Data');
        }
    }
}
