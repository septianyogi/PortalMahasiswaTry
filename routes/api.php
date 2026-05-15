<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassAttendedController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:30,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('class/get', [ClassesController::class, 'getAllClass']);
    Route::get('class/student/get', [ClassesController::class, 'getStudentClass']);
    Route::post('class/create', [ClassesController::class, 'create']);
    
    Route::get('classAttended/dosen/get', [ClassesController::class, 'getDosenClass']);
    Route::get('classAttended/dosen/get/{classId}', [ClassAttendedController::class, 'viewDosenClassAttended']);

    Route::get('classAttended/get', [ClassAttendedController::class, 'viewClassAttended']);
    Route::post('classAttended/create/{classId}',[ClassAttendedController::class, 'create']);
    Route::patch('classAttended/update/{id}', [ClassAttendedController::class, 'update']);
    Route::delete('classAttended/delete/{id}', [ClassAttendedController::class, 'delete']);

    Route::post('classSession/create', [ClassSessionController::class, 'create']);
    Route::post('attendance/create', [AttendanceController::class, 'createAttendance']);
});


