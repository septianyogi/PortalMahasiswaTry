<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassAttended;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassAttendedController extends Controller
{   
    public function getclassAttended($class_code) {
        try {
            $classAttended = ClassAttended::where('class_code', $class_code)->get();

            return $this->responseOk($classAttended, 'Data kelas berhasil diambil');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function studentGetClasses(Request $request) {
        $student_npm = Auth::user()->id_number;

        try {
            $classes = ClassAttended::with('class')->where('student_id', $student_npm)->get();

            return $this->responseOk($classes, 'Data kelas berhasil diambil');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }

    }

    public function create(Request $request){
        $request->validate([
            'student_id' => 'required',
            'npm' => 'required',
            'name' => 'required',
        ]);

        try {
            $classAttended = ClassAttended::create([
                'class_id' => $request->class_id,
                'student_id' => $request->npm,
                'student_name' => $request->name,
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
                'student_id' => $request->npm,
                'student_name' => $request->student_name,
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
