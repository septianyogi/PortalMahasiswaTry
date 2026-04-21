<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassAttendedRequest;
use App\Models\ClassAttended;
use App\Services\ClassAttendedService;
use Illuminate\Http\Request;

class ClassAttendedController extends Controller
{
    protected $classAttendedService;

    public function _construct(ClassAttendedService $classesAttendedService)
    {
        $this->classAttendedService = $classesAttendedService;
    }


    public function index()
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
    public function update(Request $request, ClassAttended $classAttended)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassAttended $classAttended)
    {
        //
    }
}
