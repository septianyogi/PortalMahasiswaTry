<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassAttendedRequest;
use App\Http\Requests\UpdateClassAttendedRequest;
use App\Models\ClassAttended;
use App\Services\ClassAttendedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassAttendedController extends Controller
{
    protected $classAttendedService;

    public function __construct(ClassAttendedService $classesAttendedService)
    {
        $this->classAttendedService = $classesAttendedService;
    }

    public function viewDosenClassAttended($classId)
    {
        try {
            $classAttended = $this->classAttendedService->viewDosenClassAttended($classId);
            return $this->responseOk($classAttended, 'Success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }


    public function viewClassAttended()
    {
        try {
            $classAttended = $this->classAttendedService->viewClassAttended();
            return $this->responseOk($classAttended, 'Success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreClassAttendedRequest $request)
    {
        try {
            $data = $request->validated();
            $classAttended = $this->classAttendedService->createClassAttended($data);
            return $this->responseOk($classAttended, 'Class Attended Created Successfully');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function update(UpdateClassAttendedRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $classAttended = $this->classAttendedService->updateClassAttended($data, $data);
            return $this->responseOk($classAttended, 'Class Attended Updated Successfully');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function delete($id)
    {
        try {
            $classAttended = $this->classAttendedService->deleteClassAttended($id);
            if ($classAttended == true) {
                return $this->responseOk($classAttended, 'Class Attended Deleted Successfully');
            } else {
                return $this->responseError('Failed to delete Class Attended', 500);
            }
            
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassAttended $classAttended)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassAttended $classAttended)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassAttended $classAttended)
    {
        //
    }
}
