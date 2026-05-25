<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePersonalInformationRequest;
use App\Services\StudentService;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentSetvice){
        $this->studentService = $studentSetvice;
    }

    public function updatePersonalInformation(UpdatePersonalInformationRequest $request) 
    {
        try {
            $data = $request->validated();

            $student = $this->studentService->updatePersonalInformation($data);

            return $this->responseOk($student, 'Data Updated Successfully', 200);
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }
}
