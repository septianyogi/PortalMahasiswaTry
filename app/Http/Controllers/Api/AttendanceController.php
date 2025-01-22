<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function studentCreateAttendance(Request $request) {
        try {
            $request->validate([
                'student_id' => 'required',
                'kelas_id' => 'required',
            ]);

            $attendance = Attendance::create([
                'student_id' => $request->student_id,
                'kelas_id' => $request->kelas_id,
                'status' => 'hadir'
            ]);

            return $this->responseOk($attendance, 'Attendance has been added');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function dosenCreateAttendance(Request $request, $class_id) {
        $attendances = $request->all();

        $class = Attendance::where('kelas_id', $class_id)->get();

        try {
            foreach($attendances as $attendance){
                Validator::make($attendance, [
                    'student_id' => 'required',
                    'kelas_id' => 'required',
                ]);
                
                if ($attendance['id'] == $class['id']) {
                    Attendance::where('id', $attendance['id'])->update([
                        'status' => $attendance['status']
                    ]);
                } else {
                    Attendance::create([
                        'student_id' => $attendance['student_id'],
                        'kelas_id' => $attendance['kelas_id'],
                        'status' => $attendance['status']
                    ]);
                }

                return $this->responseOk(null, 'Attendance has been added');
            }
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }
}
