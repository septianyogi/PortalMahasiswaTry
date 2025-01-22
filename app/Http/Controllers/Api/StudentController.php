<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function create(Request $request) {
        $request->validate([
            'npm' => 'required',
            'name' => 'required',
            'dob' => 'required',
            'email' => 'required|email',
            'jurusan_id' => 'required',
            'fakultas_id' => 'required',
        ]);

        try {
            User::create([
                'id_number' => $request->npm,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student'
            ]);

            $student = Student::create([
                'npm' => $request->npm,
                'name' => $request->name,
                'dob' => $request->dob,
                'semester' => $request->semester,
                'email' => $request->email,
                'jurusan_id' => $request->jurusan_id,
                'fakultas_id' => $request->fakultas_id,
            ]);

            return $this->responseOk($student, 'Student berhasil ditambahkan');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }
}
