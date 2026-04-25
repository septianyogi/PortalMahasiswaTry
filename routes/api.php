<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ClassAttendedController;
use App\Http\Controllers\ClassesController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('class/get', [ClassesController::class, 'getAllClass']);
    Route::post('class/create', [ClassesController::class, 'create']);
    
    Route::get('classAttended/dosen/get', [ClassAttendedController::class, 'viewDosenClassAttended']);

    Route::get('classAttended/get', [ClassAttendedController::class, 'viewClassAttended']);
    Route::post('classAttended/create',[ClassAttendedController::class, 'create']);
    Route::patch('classAttended/update/{id}', [ClassAttendedController::class, 'update']);
    Route::delete('classAttended/delete/{id}', [ClassAttendedController::class, 'delete']);
});


