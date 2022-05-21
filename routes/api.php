<?php

use App\Http\Controllers\AuthController;


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
Route::get('book/dow/{file}',[\App\Http\Controllers\BookController::class,'download']);
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
    Route::post('/student', [StudentController::class, 'one']);

    //teachers
    Route::post('/teachers/add', [\App\Http\Controllers\TeacherController::class, 'add']);
    Route::post('/teachers/delete', [\App\Http\Controllers\TeacherController::class, 'delete']);
    Route::post('/teachers/update', [\App\Http\Controllers\TeacherController::class, 'update']);
    Route::get('/teachers/all', [\App\Http\Controllers\TeacherController::class, 'all']);
    Route::post('/teachers/teacher_subject', [\App\Http\Controllers\TeacherController::class, 'teacherSubjects']);//show teacher subjects
    Route::post('/teachers', [\App\Http\Controllers\TeacherController::class, 'one']);

    //Get Teacher Classrooms
    Route::post('/teacher/classrooms/get', [\App\Http\Controllers\TeacherClassroomController::class, 'getTeacherClassrooms']);

    //parents
    Route::post('/parents/add', [\App\Http\Controllers\ParentController::class, 'add']);
    Route::post('/parents/delete', [\App\Http\Controllers\ParentController::class, 'delete']);
    Route::post('/parents/update', [\App\Http\Controllers\ParentController::class, 'update']);
    Route::get('/parents/all', [\App\Http\Controllers\ParentController::class, 'all']);
    Route::post('/parent', [\App\Http\Controllers\ParentController::class, 'one']);

    //subjects
    Route::post('/subjects/add', [\App\Http\Controllers\SubjectController::class, 'add']);
    Route::get('/subjects/all', [\App\Http\Controllers\SubjectController::class, 'all']);
    Route::post('/subjects/delete', [\App\Http\Controllers\SubjectController::class, 'delete']);
    Route::post('/subjects/update', [\App\Http\Controllers\SubjectController::class, 'update']);
    Route::post('/subjects/subject_teachers', [\App\Http\Controllers\SubjectController::class, 'subject_teachers']); //Subject Teachers


    // });

    //marks
    Route::post('/marks/setMarks', [\App\Http\Controllers\MarkController::class, 'setMarks']);
    Route::post('/marks/update', [\App\Http\Controllers\MarkController::class, 'update']);
    Route::post('/marks/delete', [\App\Http\Controllers\MarkController::class, 'delete']);
    Route::post('/marks/getStudentMarks', [\App\Http\Controllers\MarkController::class, 'getStudentMarks']);


    //admins
    Route::get('/admins/all', [\App\Http\Controllers\AdminController::class, 'all']);
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
    // add/delete teachers of classroom
    Route::post('/classroom/teachers/update', [\App\Http\Controllers\teacherClassroomController::class, 'update']);


    //weekly_schedules
    Route::post('/weeklySchedule/add', [\App\Http\Controllers\weeklyScheduleController::class, 'addWeeklySchedule']);
    Route::post('/weeklySchedule/edit', [\App\Http\Controllers\weeklyScheduleController::class, 'editWeeklySchedule']);
    Route::post('/weeklySchedule/delete', [\App\Http\Controllers\weeklyScheduleController::class, 'deleteWeeklySchedule']);
    Route::post('/weekly_schedule/add_subjects', [\App\Http\Controllers\weeklyScheduleController::class, 'add_subjects_to_schedules']);
    Route::post('/weeklySchedule/get', [\App\Http\Controllers\weeklyScheduleController::class, 'getWeeklySchedule']);
    Route::post('/weeklySchedule/teacher', [\App\Http\Controllers\weeklyScheduleController::class, 'getTeacherWeeklySchedule']);
    //events
    Route::post('events/add',[\App\Http\Controllers\eventController::class,'add']);
    Route::get('events/all',[\App\Http\Controllers\eventController::class,'all']);
    Route::post('events/delete',[\App\Http\Controllers\eventController::class,'delete']);


    //Assignment
    Route::post('assignment/add',[\App\Http\Controllers\AssignmentController::class,'newAssignment']);
    Route::post('assignment/get_teacher',[\App\Http\Controllers\AssignmentController::class,'get_teacher_assig']);
    Route::post('assignment/get_student',[\App\Http\Controllers\AssignmentController::class,'get_students_assig']);



    //absents
    Route::post('absents/add',[\App\Http\Controllers\AbsentController::class,'add']);
    Route::post('absents/one',[\App\Http\Controllers\AbsentController::class,'getStudentAbsents']);
    Route::post('absents/delete',[\App\Http\Controllers\AbsentController::class,'deleteAbsent']);
    Route::post('absents/justified',[\App\Http\Controllers\AbsentController::class,'justifiedAbsent']);

    //categories
    Route::post('categories/add',[\App\Http\Controllers\CategoryController::class,'add']);
    Route::post('categories/update',[\App\Http\Controllers\CategoryController::class,'update']);
    Route::post('categories/delete',[\App\Http\Controllers\CategoryController::class,'delete']);
    Route::get('categories/getAll',[\App\Http\Controllers\CategoryController::class,'getAll']);

    //book
    Route::post('book/add',[\App\Http\Controllers\BookController::class,'newBook']);
    Route::post('book/update',[\App\Http\Controllers\BookController::class,'update']);
    Route::post('book/delete',[\App\Http\Controllers\BookController::class,'delete']);

});



