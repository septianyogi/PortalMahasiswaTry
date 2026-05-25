<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;

/**
 * Class StudentService
 * @package App\Services
 */
class StudentService
{
    public function updatePersonalInformation(array $data)
    {
        $userId = Auth::user()->id;
        $student = Student::where('user_id', $userId)->firstOrFail();

        $student::update($data);
        return $student;
    }
}
