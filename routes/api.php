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



   // Route::group(['middleware' => 'isAdmin'], function () {
        //student
        Route::post('/students/add', [StudentController::class, 'add']);
        Route::post('/students/delete', [StudentController::class, 'delete']);
        Route::post('/students/update', [StudentController::class, 'update']);
        Route::get('/students/all', [StudentController::class, 'all']);
        Route::post('/student',[StudentController::class,'one']);

        //teachers
        Route::post('/teachers/add', [\App\Http\Controllers\TeacherController::class, 'add']);
        Route::post('/teachers/delete', [\App\Http\Controllers\TeacherController::class, 'delete']);
        Route::post('/teachers/update', [\App\Http\Controllers\TeacherController::class, 'update']);
        Route::get('/teachers/all', [\App\Http\Controllers\TeacherController::class, 'all']);
        Route::post('/teachers/teacher_subject', [\App\Http\Controllers\TeacherController::class, 'teacher_subject']);//show teacher subjects
        Route::post('/teachers',[\App\Http\Controllers\TeacherController::class,'one']);





        //parents
        Route::post('/parents/add', [\App\Http\Controllers\ParentController::class, 'add']);
        Route::post('/parents/delete', [\App\Http\Controllers\ParentController::class, 'delete']);
        Route::post('/parents/update', [\App\Http\Controllers\ParentController::class, 'update']);
        Route::get('/parents/all', [\App\Http\Controllers\ParentController::class, 'all']);
        Route::post('/parent',[\App\Http\Controllers\ParentController::class,'one']);

        //subjects
        Route::post('/subjects/add', [\App\Http\Controllers\SubjectController::class, 'add']);
        Route::get('/subjects/all', [\App\Http\Controllers\SubjectController::class, 'all']);
        Route::post('/subjects/delete', [\App\Http\Controllers\SubjectController::class, 'delete']);
        Route::post('/subjects/update', [\App\Http\Controllers\SubjectController::class, 'update']);
        Route::post('/subjects/subject_teachers', [\App\Http\Controllers\SubjectController::class, 'subject_teachers']); //Subject Teachers





   // });

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
    Route::get('/classes/all', [\App\Http\Controllers\ClassController::class, 'all']);
    Route::post('/classes/show_classrooms', [\App\Http\Controllers\ClassController::class, 'show_classrooms']);

    ##add subjects to a class
    Route::post('/classes/subjects/add', [\App\Http\Controllers\ClassController::class, 'addSubjectsToClass']);
    ##delete subject from a class
    Route::post('/classes/subjects/delete', [\App\Http\Controllers\ClassController::class, 'deleteSubjectsFromClass']);
    ##get subjects of a class
    Route::post('/classes/get_subjects_of_class', [\App\Http\Controllers\ClassController::class, 'get_subjects_of_class']);

    //classrooms
    Route::get('/classrooms/all', [\App\Http\Controllers\ClassroomController::class, 'all']);
    Route::post('/classrooms/add', [\App\Http\Controllers\ClassroomController::class, 'add']);
    Route::post('/classrooms/update', [\App\Http\Controllers\ClassroomController::class, 'update']);
    Route::post('/classrooms/delete', [\App\Http\Controllers\ClassroomController::class, 'delete']);
    Route::post('/classrooms/show_my_class', [\App\Http\Controllers\ClassroomController::class, 'show_my_class']);



    //weekly_schedules
    Route::post('/weekly_schedule/add', [\App\Http\Controllers\weekly_scheduleController::class, 'add_weekly_schedule']);
    Route::post('/weekly_schedule/add_subjects', [\App\Http\Controllers\weekly_scheduleController::class, 'add_subjects_to_schedules']);
    Route::post('/weekly_schedule/get', [\App\Http\Controllers\weekly_scheduleController::class, 'get_weekly_schedule']);

});



