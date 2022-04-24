<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\classStudentsController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);



    Route::group(['middleware' => 'isAdmin'], function () {
        //class
        Route::get('classes/all', [SchoolClassController::class, 'all']);
        Route::post('classes/add', [SchoolClassController::class, 'add']);
        Route::post('/class/edit', [SchoolClassController::class, 'edit']);
        Route::post('/class/delete', [SchoolClassController::class, 'delete']);
        Route::post('/class/students',[classStudentsController::class,'all']);
        Route::post('/class',[SchoolClassController::class,'one']);


        //student
        Route::post('/students/add', [StudentController::class, 'add']);
        Route::post('/students/delete', [StudentController::class, 'delete']);
        Route::post('/students/update', [StudentController::class, 'update']);
        Route::post('/students/all', [StudentController::class, 'all']);
        Route::post('/student',[StudentController::class,'one']);

        //parents
        Route::post('/parents/add', [\App\Http\Controllers\ParentController::class, 'add']);
        Route::post('/parents/delete', [\App\Http\Controllers\ParentController::class, 'delete']);
        Route::post('/parents/update', [\App\Http\Controllers\ParentController::class, 'update']);
        Route::get('/parents/all', [\App\Http\Controllers\ParentController::class, 'all']);
        Route::post('/parents',[\App\Http\Controllers\ParentController::class,'one']);

    });

    //marks
    Route::post('/marks/add', [\App\Http\Controllers\MarkController::class, 'add']);
    Route::post('/marks/update', [\App\Http\Controllers\MarkController::class, 'update']);
    Route::post('/marks/delete', [\App\Http\Controllers\MarkController::class, 'delete']);
    Route::post('/marks/one', [\App\Http\Controllers\MarkController::class, 'getStudentMarks']);


    //admins
    Route::post('/admins/add', [\App\Http\Controllers\AdminController::class, 'add']);
    Route::post('/admins/update', [\App\Http\Controllers\AdminController::class, 'update']);
    Route::post('/admins/delete', [\App\Http\Controllers\AdminController::class, 'delete']);

    //classes
    Route::post('/classes/add', [\App\Http\Controllers\ClassController::class, 'add']);
    Route::post('/classes/update', [\App\Http\Controllers\ClassController::class, 'update']);
    Route::post('/classes/delete', [\App\Http\Controllers\ClassController::class, 'delete']);
    Route::post('/classes/show_classrooms', [\App\Http\Controllers\ClassController::class, 'show_classrooms']);
    //still add subject and .. etc

    //classrooms
    Route::post('/classrooms/add', [\App\Http\Controllers\ClassroomController::class, 'add']);
    Route::post('/classrooms/update', [\App\Http\Controllers\ClassroomController::class, 'update']);
    Route::post('/classrooms/delete', [\App\Http\Controllers\ClassroomController::class, 'delete']);
    Route::post('/classrooms/show_my_class', [\App\Http\Controllers\ClassroomController::class, 'show_my_class']);



    //weekly_schedules
    Route::post('/weekly_schedule/add', [\App\Http\Controllers\weekly_scheduleController::class, 'add_weekly_schedule']);
    Route::post('/weekly_schedule/add_subjects', [\App\Http\Controllers\weekly_scheduleController::class, 'add_subjects_to_schedules']);
    Route::post('/weekly_schedule/get', [\App\Http\Controllers\weekly_scheduleController::class, 'get_weekly_schedule']);

});



