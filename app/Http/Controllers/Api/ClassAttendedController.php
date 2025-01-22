<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassAttended;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassAttendedController extends Controller
{
    public function studentGetClasses(Request $request) {
        $student_id = Auth::user()->id_number;
    }

    public function create(Request $request){
        $request->validate([
            'student_id' => 'required',
            'class_id' => 'required',
        ]);

        try {
            $classAttended = ClassAttended::create([
                'class_id' => $request->class_id,
                'student_id' => $request->student_id,
                'attendance' => 0,
                'absent' => 0
            ]);

            return $this->responseOk($classAttended, 'Class Attended has been added');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function update(Request $request, $id) {
        try {
            $classAttended = ClassAttended::find($id);
            $classAttended->update([
                'class_id' => $request->class_id,
                'student_id' => $request->student_id,
                'attendance' => $request->attendance,
                'absent' => $request->absent,
                'assignment' => $request->assignment,
                'mid_exam' => $request->mid_exam,
                'final_exam' => $request->final_exam,
                'final_score' => $request->final_score,
            ]);

            return $this->responseOk($classAttended, 'Update success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function destroy($id) {
        try {
            $classAttended = ClassAttended::find($id);
            $classAttended->delete();

            return $this->responseOk($classAttended, 'Delete success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }
}
