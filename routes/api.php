<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassAttendedController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassSessionController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SemesterController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:30,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});


Route::middleware(['auth:api', 'check_blacklist'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/student/password/update', [AuthController::class, 'updatePassword']);
    Route::patch('/student/pin/update', [AuthController::class, 'updatePin']);

    Route::patch('/student/personalInformation/update', [StudentController::class, 'updatePersonalInformation']);

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
    Route::post('student/attendance/create', [AttendanceController::class, 'createAttendance']);
    Route::get('student/attendance/get', [AttendanceController::class, 'getAttendance']);

    Route::get('grades/get', [GradeController::class, 'showGradeDetail']);
    Route::put('grades/{classAttendedId}', [GradeController::class, 'update']);
    Route::get('grades/student/{studentId}', [GradeController::class, 'studentGrades']);

    Route::get('studentSemester/get', [SemesterController::class, 'getSemester']);
    
    Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
        Route::post('/advanceSemester', [SemesterController::class, 'advance']);
        Route::post('/advanceSemester/{studentId}', [SemesterController::class, 'advanceSingle']);
    });

    Route::get('/student/semester/{studentId}', [SemesterController::class, 'studentSemester']);
});


