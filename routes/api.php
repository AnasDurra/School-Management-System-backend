<?php

use App\Http\Controllers\AuthController;


use App\Http\Controllers\StudentController;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//download related
Route::get('book/dow/{file}', [\App\Http\Controllers\BookController::class, 'download']);
Route::get('tutorials/download/{file}', [\App\Http\Controllers\TutorialController::class, 'download']);
Route::get('tutorials/helper_file/download/{file}', [\App\Http\Controllers\HelperFileController::class, 'download']);
//authent
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
//archive
Route::get('archive/activeYear', [\App\Http\Controllers\ArchiveYearController::class, 'getActiveYear']);
Route::get('archive/years', [\App\Http\Controllers\ArchiveYearController::class, 'getYears']);
Route::get('archive/lastYearCheck', [\App\Http\Controllers\ArchiveYearController::class, 'lastYearIsActiveYearCheck']); //check if current year is last year
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route::group(['middleware' => 'isAdmin'], function () {

    //student
    Route::post('/students/add', [StudentController::class, 'add']);
    Route::post('/students/delete', [StudentController::class, 'delete']);
    Route::post('/students/update', [StudentController::class, 'update']);
    Route::get('/students/all', [StudentController::class, 'all']);  //f
    Route::post('/student', [StudentController::class, 'one']); //f

    //teachers
    Route::post('/teachers/add', [\App\Http\Controllers\TeacherController::class, 'add']);
    Route::post('/teachers/delete', [\App\Http\Controllers\TeacherController::class, 'delete']);
    Route::post('/teachers/update', [\App\Http\Controllers\TeacherController::class, 'update']);
    Route::get('/teachers/all', [\App\Http\Controllers\TeacherController::class, 'all']); //f
    Route::post('/teachers/teacher_subject', [\App\Http\Controllers\TeacherController::class, 'teacherSubjects']);//show teacher subjects
    Route::post('/teachers', [\App\Http\Controllers\TeacherController::class, 'one']); //f

    //Get Teacher Classrooms
    Route::post('/teacher/classrooms/get', [\App\Http\Controllers\TeacherClassroomController::class, 'getTeacherClassrooms']);

    //Get Teacher classes
    Route::post('/teacher/classes/get', [\App\Http\Controllers\TeacherController::class, 'getTeacherClasses']);

    //get teacher obligations
    Route::post('/teacher/objections', [\App\Http\Controllers\TeacherClassroomController::class, 'getTeacherObjections']);

    //parents
    Route::post('/parents/add', [\App\Http\Controllers\ParentController::class, 'add']);
    Route::post('/parents/delete', [\App\Http\Controllers\ParentController::class, 'delete']);
    Route::post('/parents/update', [\App\Http\Controllers\ParentController::class, 'update']);
    Route::get('/parents/all', [\App\Http\Controllers\ParentController::class, 'all']);//f
    Route::post('/parent', [\App\Http\Controllers\ParentController::class, 'one']);//f

    //subjects
    Route::post('/subjects/add', [\App\Http\Controllers\SubjectController::class, 'add']);
    Route::get('/subjects/all', [\App\Http\Controllers\SubjectController::class, 'all']);//f
    Route::post('/subjects/delete', [\App\Http\Controllers\SubjectController::class, 'delete']);
    Route::post('/subjects/update', [\App\Http\Controllers\SubjectController::class, 'update']);
    Route::post('/subjects/subject_teachers', [\App\Http\Controllers\SubjectController::class, 'subject_teachers']); //Subject Teachers


    // });

    //marks
    Route::post('/marks/setMarks', [\App\Http\Controllers\MarkController::class, 'setMarks']);
    Route::post('/marks/update', [\App\Http\Controllers\MarkController::class, 'update']);
    Route::post('/marks/delete', [\App\Http\Controllers\MarkController::class, 'delete']);
    //  Route::post('/marks/getStudentMarks', [\App\Http\Controllers\MarkController::class, 'getStudentMarks']);
    Route::get('/marks/getTypes', [\App\Http\Controllers\MarkController::class, 'getTypes']);
    //marks for classroom as schedule
    Route::post('/marks/classroom', [\App\Http\Controllers\MarkController::class, 'getClassroomSubjectMarks']);
    //marks for student as schedule
    Route::post('/marks/student', [\App\Http\Controllers\MarkController::class, 'getStudentMarks']);
    //check if mark is in db
    Route::post('/marks/check', [\App\Http\Controllers\MarkController::class, 'check']);
    Route::post('/mark/object', [\App\Http\Controllers\MarkController::class, 'object']);

    //admins
    Route::get('/admins/all', [\App\Http\Controllers\AdminController::class, 'all']);//f
    Route::post('/admins/add', [\App\Http\Controllers\AdminController::class, 'add']);
    Route::post('/admins/update', [\App\Http\Controllers\AdminController::class, 'update']);
    Route::post('/admins/delete', [\App\Http\Controllers\AdminController::class, 'delete']);
                //get all tags
    Route::get('admin/responsibilities/all', [\App\Http\Controllers\ResponsibilityController::class, 'all']);
    //archive
    Route::post('archive/switchActiveYear', [\App\Http\Controllers\ArchiveYearController::class, 'switchActiveYear']);
    //Assignment
    Route::post('assignment/add', [\App\Http\Controllers\AssignmentController::class, 'newAssignment']);
    Route::post('assignment/update', [\App\Http\Controllers\AssignmentController::class, 'update']);
    Route::post('assignment/delete', [\App\Http\Controllers\AssignmentController::class, 'delete']);
    Route::post('assignment/get_teacher', [\App\Http\Controllers\AssignmentController::class, 'get_teacher_assig']);
    Route::post('assignment/get_student', [\App\Http\Controllers\AssignmentController::class, 'get_students_assig']);
    //absents
    Route::group(['middleware' => 'AbsentsOfficial'], function () {
        Route::post('absents/add', [\App\Http\Controllers\AbsentController::class, 'add']);
        Route::post('absents/delete', [\App\Http\Controllers\AbsentController::class, 'deleteAbsent']);
        Route::post('absents/justified', [\App\Http\Controllers\AbsentController::class, 'justifiedAbsent']);
    });
    Route::post('absents/one', [\App\Http\Controllers\AbsentController::class, 'getStudentAbsents']);
    //bus
    Route::group(['middleware' => 'BusOfficial'], function () {
        Route::post('bus/add', [\App\Http\Controllers\BusController::class, 'addBus']);
        Route::post('bus/update', [\App\Http\Controllers\BusController::class, 'updateBus']);
        Route::post('bus/delete', [\App\Http\Controllers\BusController::class, 'deleteBus']);
    });
    Route::get('bus/all', [\App\Http\Controllers\BusController::class, 'getBuses']);//f
    Route::get('bus/getAddresses', [\App\Http\Controllers\BusController::class, 'getAddresses']);//f
    //library
    Route::group(['middleware' => 'LibraryOfficial'], function () {
        //book
        Route::post('book/add', [\App\Http\Controllers\BookController::class, 'newBook']);
        Route::post('book/update', [\App\Http\Controllers\BookController::class, 'update']);
        Route::post('book/delete', [\App\Http\Controllers\BookController::class, 'delete']);
        //categories
        Route::post('categories/add', [\App\Http\Controllers\CategoryController::class, 'add']);
        Route::post('categories/update', [\App\Http\Controllers\CategoryController::class, 'update']);
        Route::post('categories/delete', [\App\Http\Controllers\CategoryController::class, 'delete']);
    });
    Route::get('categories/getAll', [\App\Http\Controllers\CategoryController::class, 'getAll']);
    Route::get('book/getAll', [\App\Http\Controllers\BookController::class, 'getAll']);

    //classes
    Route::post('/classes/add', [\App\Http\Controllers\Classes\ClassController::class, 'add']);
    Route::post('/classes/update', [\App\Http\Controllers\Classes\ClassController::class, 'update']);
    Route::post('/classes/delete', [\App\Http\Controllers\Classes\ClassController::class, 'delete']);
    Route::get('/classes/all', [\App\Http\Controllers\Classes\ClassController::class, 'all']);//f
    Route::post('/classes/show_classrooms', [\App\Http\Controllers\Classes\ClassController::class, 'show_classrooms']);
    ##add subjects to a class
    Route::post('/classes/subjects/add', [\App\Http\Controllers\Classes\ClassController::class, 'addSubjectsToClass']);
    ##delete subject from a class
    Route::post('/classes/subjects/delete', [\App\Http\Controllers\Classes\ClassController::class, 'deleteSubjectsFromClass']);
    ##get subjects of a class
    Route::post('/classes/get_subjects_of_class', [\App\Http\Controllers\Classes\ClassController::class, 'get_subjects_of_class']);
    //classrooms
    Route::get('/classrooms/all', [\App\Http\Controllers\ClassroomController::class, 'all']);//f
    Route::post('/classrooms/add', [\App\Http\Controllers\ClassroomController::class, 'add']);
    Route::post('/classrooms/update', [\App\Http\Controllers\ClassroomController::class, 'update']);
    Route::post('/classrooms/delete', [\App\Http\Controllers\ClassroomController::class, 'delete']);
    Route::post('/classrooms/show_my_class', [\App\Http\Controllers\ClassroomController::class, 'show_my_class']);
                 //results
    Route::post('/classroom/result', [\App\Http\Controllers\PDFgenerator::class, 'index']);
                // add/delete teachers of classroom
    Route::post('/classroom/teachers/update', [\App\Http\Controllers\teacherClassroomController::class, 'update']);
    //complaint
    Route::group(['middleware' => 'ComplaintsOfficial'], function () {
        Route::post('complaint/add', [\App\Http\Controllers\ComplaintController::class, 'add']);
        Route::post('complaint/edit', [\App\Http\Controllers\ComplaintController::class, 'update']);
        Route::post('complaint/seenComplaint', [\App\Http\Controllers\ComplaintController::class, 'seenComplaint']); //set complaint as seen
    });
    Route::get('complaint/getComplaints', [\App\Http\Controllers\ComplaintController::class, 'get_complaints']);//f
    //events
    Route::group(['middleware' => 'EventsOfficial'], function () {
        Route::post('events/add', [\App\Http\Controllers\eventController::class, 'add']);
        Route::post('events/delete', [\App\Http\Controllers\eventController::class, 'delete']);
    });
    Route::get('events/all', [\App\Http\Controllers\eventController::class, 'all']); //f
    //import from
    Route::get('import/get_classes', [\App\Http\Controllers\ArchiveYearController::class, 'previousYearClasses']);
    Route::post('import/get_students', [\App\Http\Controllers\Classes\ClassController::class, 'previousYearsStudents']);
    Route::post('import/addStudent', [\App\Http\Controllers\Classes\ClassController::class, 'importStudent']);
    //marks
    Route::post('/marks/setMarks', [\App\Http\Controllers\MarkController::class, 'setMarks']);
    Route::post('/marks/update', [\App\Http\Controllers\MarkController::class, 'update']);
    Route::post('/marks/delete', [\App\Http\Controllers\MarkController::class, 'delete']);
    Route::get('/marks/getTypes', [\App\Http\Controllers\MarkController::class, 'getTypes']);
                  //marks for classroom as schedule
    Route::post('/marks/classroom', [\App\Http\Controllers\MarkController::class, 'getClassroomSubjectMarks']);
                 //marks for student as schedule
    Route::post('/marks/student', [\App\Http\Controllers\MarkController::class, 'getStudentMarks']);
                 //check if mark is in db
    Route::post('/marks/check', [\App\Http\Controllers\MarkController::class, 'check']);
    Route::post('/mark/object', [\App\Http\Controllers\MarkController::class, 'object']);
    //parents
    Route::post('/parents/add', [\App\Http\Controllers\ParentController::class, 'add']);
    Route::post('/parents/delete', [\App\Http\Controllers\ParentController::class, 'delete']);
    Route::post('/parents/update', [\App\Http\Controllers\ParentController::class, 'update']);
    Route::get('/parents/all', [\App\Http\Controllers\ParentController::class, 'all']);//f
    Route::post('/parent', [\App\Http\Controllers\ParentController::class, 'one']);//f
    //statistics for dashboard
    Route::get('statistics', [\App\Http\Controllers\StatisticsController::class, 'getStatistics']);
    //student
    Route::post('/students/add', [StudentController::class, 'add']);
    Route::post('/students/delete', [StudentController::class, 'delete']);
    Route::post('/students/update', [StudentController::class, 'update']);
    Route::get('/students/all', [StudentController::class, 'all']);  //f
    Route::post('/student', [StudentController::class, 'one']); //f
    //subjects
    Route::post('/subjects/add', [\App\Http\Controllers\SubjectController::class, 'add']);
    Route::get('/subjects/all', [\App\Http\Controllers\SubjectController::class, 'all']);//f
    Route::post('/subjects/delete', [\App\Http\Controllers\SubjectController::class, 'delete']);
    Route::post('/subjects/update', [\App\Http\Controllers\SubjectController::class, 'update']);
    Route::post('/subjects/subject_teachers', [\App\Http\Controllers\SubjectController::class, 'subject_teachers']); //Subject Teachers
    //teachers
    Route::post('/teachers/add', [\App\Http\Controllers\TeacherController::class, 'add']);
    Route::post('/teachers/delete', [\App\Http\Controllers\TeacherController::class, 'delete']);
    Route::post('/teachers/update', [\App\Http\Controllers\TeacherController::class, 'update']);
    Route::get('/teachers/all', [\App\Http\Controllers\TeacherController::class, 'all']); //f
    Route::post('/teachers/teacher_subject', [\App\Http\Controllers\TeacherController::class, 'teacherSubjects']);//show teacher subjects
    Route::post('/teachers', [\App\Http\Controllers\TeacherController::class, 'one']); //f
            //Get Teacher Classrooms
    Route::post('/teacher/classrooms/get', [\App\Http\Controllers\TeacherClassroomController::class, 'getTeacherClassrooms']);
            //get teacher obligations
    Route::post('/teacher/objections', [\App\Http\Controllers\TeacherClassroomController::class, 'getTeacherObjections']);
    //tutorials
    Route::post('tutorials/add', [\App\Http\Controllers\TutorialController::class, 'add']);
    Route::post('tutorials/update', [\App\Http\Controllers\TutorialController::class, 'update']);
    Route::post('tutorials/class/subject', [\App\Http\Controllers\TutorialController::class, 'getClassSubjectTutorials']);
    Route::post('tutorials/teacher', [\App\Http\Controllers\TutorialController::class, 'getTeacherTutorials']);
    Route::post('tutorials/class', [\App\Http\Controllers\TutorialController::class, 'getClassTutorials']);
    Route::post('tutorials/view', [\App\Http\Controllers\TutorialController::class, 'view']);
    Route::post('tutorials/delete', [\App\Http\Controllers\TutorialController::class, 'delete']);
    //update auth info
    Route::post('/update', [AuthController::class, 'updateAuth']);
    //weekly_schedules
    Route::post('/weeklySchedule/add', [\App\Http\Controllers\weeklyScheduleController::class, 'addWeeklySchedule']);
    Route::post('/weeklySchedule/edit', [\App\Http\Controllers\weeklyScheduleController::class, 'editWeeklySchedule']);
    Route::post('/weeklySchedule/delete', [\App\Http\Controllers\weeklyScheduleController::class, 'deleteWeeklySchedule']);
    Route::post('/weekly_schedule/add_subjects', [\App\Http\Controllers\weeklyScheduleController::class, 'add_subjects_to_schedules']);
    Route::post('/weeklySchedule/get', [\App\Http\Controllers\weeklyScheduleController::class, 'getWeeklySchedule']);
    Route::post('/weeklySchedule/teacher', [\App\Http\Controllers\weeklyScheduleController::class, 'getTeacherWeeklySchedule']);
});




