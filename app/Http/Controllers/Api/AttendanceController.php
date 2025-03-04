<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function dosenCreateCode($class_id) {
        try {
            $class = Kelas::where('id', $class_id)->first();
            $code = str()->random(40);
            $expires_at = now()->addMinutes(2);
            $class->update([
                'attendance' => $code,
                'expires_at' => $expires_at
            ]);
            

            return $this->responseOk($class, 'Attendance code has been created');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        } 
    }
    public function studentCreateAttendance(Request $request) {
        try {
            $request->validate([
                'npm' => 'required',
                'attendance' => 'required',
            ]);

            $class = Kelas::where('attendance', $request->attendance)->first();

            if ($class != null) {
                Attendance::create([
                    'student_id' => $request->npm,
                    'kelas_id' => $class->id,
                    'status' => 'hadir'
                ]);

                $attendance = Attendance::where('student_id', $request->npm)->where('kelas_id', $class->id)->get();
            }

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
                    'npm' => 'required',
                    'kelas_id' => 'required',
                ]);
                
                if ($attendance['id'] == $class['id']) {
                    Attendance::where('id', $attendance['id'])->update([
                        'status' => $attendance['status']
                    ]);
                } else {
                    Attendance::create([
                        'student_id' => $attendance['npm'],
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
