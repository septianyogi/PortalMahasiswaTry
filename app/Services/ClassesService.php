<?php

namespace App\Services;

use App\Models\Classes;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

/**
 * Class ClassesService
 * @package App\Services
 */
class ClassesService
{

    public function viewDosenClass()
    {
        $user = Auth::user();

        $classes = Classes::where('dosen_id', $user->dosen->id)->get();
        return $classes;
    }

    public function getStudentClass()
    {
        $userId = Auth::user()->id;

        $student = Student::where('user_id', $userId)->first();

        $classes = Classes::where('jurusan_id', $student->jurusan_id)->get();

        return $classes;
    }
    public function createClass(array $data)
    {
       
        $class = Classes::create([
            'code' => $data['code'],
            'jurusan_id' => $data['jurusan_id'],
            'name' => $data['name'],
            'date' => $data['date'],
            'time_start' => $data['time_start'],
            'time_end' => $data['time_end'],
            'dosen_id' => $data['dosen_id'],
            'quota' => $data['quota'],
            'room' => $data['room'] ?? null,
            'semester' => $data['semester'],
        ]);

        return $class;
    }
}
