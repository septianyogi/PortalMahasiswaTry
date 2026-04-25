<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClassSessionRequest;
use App\Models\ClassSession;
use App\Services\ClassSessionService;
use Illuminate\Http\Request;

class ClassSessionController extends Controller
{

    protected $classSessionService;

    public function __construct(ClassSessionService $classSessionService)
    {
        $this->classSessionService = $classSessionService;
    }


    public function index()
    {
        //
    }

    public function create(CreateClassSessionRequest $request)
    {
        try {
            $data = $request->validated();
            $session = ClassSession::where('class_id', $data['class_id'])
                ->where('week', $data['week'])
                ->first();
            if($session){
                $classSession = $this->classSessionService->updateClassSession($data);
                return $this->responseOk($classSession, 'Class session updated successfully');
            }else{
                $classSession = $this->classSessionService->createClassSession($data);
                return $this->responseOk($classSession, 'Class session created successfully');
            }

            
        } catch (\Throwable $th) {
            $this->responseError($th->getMessage(), $th->getCode());
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
    public function show(ClassSession $classSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassSession $classSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassSession $classSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSession $classSession)
    {
        //
    }
}
