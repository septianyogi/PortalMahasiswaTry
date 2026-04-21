<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ClassAttendedController;
use App\Http\Controllers\ClassesController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('class/get', [ClassesController::class, 'getAllClass']);
    Route::post('class/create', [ClassesController::class, 'create']);


    Route::get('classAttended/get', [ClassAttendedController::class, 'viewClassAttended']);
    Route::post('classAttended/create',[ClassAttendedController::class, 'create']);
});


