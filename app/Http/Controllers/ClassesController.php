<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassRequest;
use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Services\ClassesService;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $classesService;
    
    public function __construct(ClassesService $classesService)
    {
        $this->classesService = $classesService;
    }

    public function getDosenClass()
    {
        try {
            $classes = $this->classesService->viewDosenClass();
            return $this->responseOk($classes, 'Success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), 400);
        }
    }


    public function getAllClass()
    {
        try {
            $classes = Classes::with('dosen')->get();
            return $this->responseOk($classes, 'Success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreClassRequest $request)
    {
        try {
            $data = $request->validated();

            $class = $this->classesService->createClass($data);
            return $this->responseOk($class, 'Class created successfully');
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
    public function show(Classes $classes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classes $classes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classes $classes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classes $classes)
    {
        //
    }
}
