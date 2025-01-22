<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassAttendedController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\FakultasController;
use App\Http\Controllers\Api\JurusanController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('debugBar')->group(function() {

Route::post('/login', [AuthController::class, 'login']);

Route::get('/dosen/class/get', [KelasController::class, 'getClassByDosen']);

Route::middleware(['auth:sanctum', 'is_admin'])->group(function() {
    Route::post('/register/admin', [AuthController::class, 'register']);
    Route::post('/register/student', [StudentController::class, 'create']);
    Route::post('/register/dosen', [DosenController::class, 'create']);

    Route::post('/fakultas/create', [FakultasController::class, 'create']);
    Route::delete('/fakultas/{id}', [FakultasController::class, 'destroy']);

    Route::post('/jurusan/create', [JurusanController::class, 'create']);
    Route::delete('/jurusan/{id}', [JurusanController::class, 'destroy']);

    Route::post('/class/create', [KelasController::class, 'create']);
    Route::patch('/class/update/{id}', [KelasController::class, 'update']);
    Route::delete('/class/delete/{id}', [KelasController::class, 'destroy']);

    Route::delete('/student/class/delete/{id}', [ClassAttendedController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function() {


    //Student
    Route::middleware('is_student')->group(function() {
        Route::delete('/student/class/delete/{id}', [ClassAttendedController::class, 'destroy']);
        
        Route::post('/student/attendance/create', [AttendanceController::class, 'studentCreateAttendance']);
    });


    //Dosen
    Route::middleware('is_dosen')->group(function() {
        Route::get('/dosen/class/get', [KelasController::class, 'getClassByDosen']);

        Route::post('/dosen/attendance/create/{class_id}', [AttendanceController::class, 'dosenCreateAttendance']);

        Route::patch('/class/grade/update/{id}', [ClassAttendedController::class, 'update']);
    });


    Route::post('/logout', [AuthController::class, 'logout']);
});

});