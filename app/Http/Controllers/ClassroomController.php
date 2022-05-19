<?php

namespace App\Http\Controllers;

use App\Models\Class_Subject;
use App\Models\Classroom;

use App\Models\Classroom_teacherSubject;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Teacher_classroom;
use App\Models\teacher_subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    public function all(Request $request)
    {
        $classrooms = Classroom::all();
        if ($classrooms) {
            for ($i = 0; $i < count($classrooms); $i++) {
                $classroom_teacherSubject_in_classroom = Classroom_teacherSubject::query()->where('classroom_id','=',$classrooms[$i]->id)->get();
                if ($classrooms[$i]->students) {
                    for ($j = 0; $j < count($classrooms[$i]->students); $j++) {
                        $user = User::query()->where('id', '=', $classrooms[$i]->students[$j]->user_id)->with('student')->first();
                        $parent = User::query()->where('id', '=', $classrooms[$i]->students[$j]->parent_id)->with('parent')->first();
                        $classrooms[$i]->students[$j] = $user;
                        $classrooms[$i]->students[$j]->student->parent = $parent;
                    }
                }

                if ($classroom_teacherSubject_in_classroom)
                    for ($j = 0; $j < count($classroom_teacherSubject_in_classroom); $j++) {
                        $teacher_subject = teacher_subject::query()
                            ->where('id', '=', $classroom_teacherSubject_in_classroom[$j]->teacherSubject_id)->first();
                        $subject=Subject::query()->where('id','=',$teacher_subject->subject_id)->first();
                        $teacher_user=User::query()->where('id','=',$teacher_subject->teacher_id)->first();
                        $classroom_teacherSubject_in_classroom[$j]->teacher_id = $teacher_subject->teacher_id;
                        $classroom_teacherSubject_in_classroom[$j]->teacher_user = $teacher_user;
                        $classroom_teacherSubject_in_classroom[$j]->subject_name = $subject->name;
                    }
                $classrooms[$i]->teacher_subject = $classroom_teacherSubject_in_classroom;
            }
        }
        return response()->json(
            $classrooms
        );

    }

    public function add(Request $request)
    {
        //TODO make sure the sent subjects fit the teacher it is sent with also in update method
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'capacity' => 'required',
            'class_id' => 'required',
            'teachers_id', //array {teacher id ,subjects[id]}
            'students_id']);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        //checking the ids ---------------------------------------------------------------------------------------------
        if ($request->students_id)
            for ($i = 0; $i < count($request->students_id); $i++) {
                $student = Student::query()->where('user_id', '=', $request->students_id[$i])->first();
                if (!$student) {
                    return response()->json([
                        'message' => 'invalid students ids',
                    ], 404);
                }
            }

        if ($request->teachers_id)

            for ($i = 0; $i < count($request->teachers_id); $i++) {
                $teacher_subject = teacher_subject::query()->where('teacher_id', '=', $request->teachers_id[$i]['teacher_id'])
                    ->where('subject_id', '=', $request->teachers_id[$i]['subject_id'])->first();
                if (!$teacher_subject)
                    return response()->json([
                        'message' => 'invalid teachers_subject ids',
                    ], 404);


                for ($i = 0; $i < count($request->teachers_id); $i++) {
                    $teacher = Teacher::query()->where('user_id', '=', $request->teachers_id[$i]['teacher_id'])->first();
                    if (!$teacher)
                        return response()->json([
                            'message' => 'invalid teachers ids',
                        ], 404);
                    //checking if subjects exist
                    $subject = Subject::query()->where('id', '=', $request->teachers_id[$i]['subject_id'])->first();
                    if (!$subject)
                        return response()->json([
                            'message' => 'invalid subjects ids',
                        ], 404);
                }


                /*------------------------------------------------------------------------------------------------------------------------*/


                //checking if subject is for this classroom
//                    $valid = Class_Subject::query()->where([
//                        ['subject_id ', '=', $subject->id],
//                        ['class_id', '=', $request->class_id]
//
//                    ])->first();
//                    if (!$valid)
//                        return response()->json([
//                            'message' => 'subject with id' . $subject->id . 'isn\'t for class with id' . $request->id,
//                        ], 404);


                /*}*/
                //--------------------------------------------------------------------------------------------------------------


                $classroom = new Classroom();
                $classroom->name = $request->name;
                $classroom->capacity = $request->capacity;
                $classroom->class_id = $request->class_id;
                $classroom->save();
                if ($request->teachers_id)
                    for ($i = 0; $i < count($request->teachers_id); $i++) {
                        $classroom_teacherSubject = new Classroom_teacherSubject();
                        $teacher_subject = teacher_subject::query()->where('teacher_id', '=', $request->teachers_id[$i]['teacher_id'])
                            ->where('subject_id', '=', $request->teachers_id[$i]['subject_id'])->first();


                        //classroomteacherSubject
                        $classroom_teacherSubject->teacherSubject_id = $teacher_subject->id;
                        $classroom_teacherSubject->classroom_id = $classroom->id;
                        $classroom_teacherSubject->save();
                        //TeacherClassroom

                        if (!(Teacher_classroom::query()->where('teacher_id', '=', $request->teachers_id[$i]['teacher_id'])
                            ->where('classroom_id', '=', $classroom->id)->first())) {
                            $teacher_classroom = new Teacher_classroom();
                            $teacher_classroom->teacher_id = $request->teachers_id[$i]['teacher_id'];
                            $teacher_classroom->classroom_id = $classroom->id;
                            $teacher_classroom->save();
                        }


                    }
                if ($request->students_id) {
                    for ($i = 0; $i < count($request->students_id); $i++) {
                        $student = Student::query()->where('user_id', '=', $request->students_id[$i])->first();

                        // $student->classroom_id = $classroom->id;
                        Student::query()->where('user_id', '=', $request->students_id[$i])->update(['classroom_id' => $classroom->id]);
                        //$student->save();
                    }
                }
                $classroom->students;
                $classroom->teachers;


                $all_teacher_in_classroom = Classroom_teacherSubject::query()->where('classroom_id', '=', $classroom->id)->get();
                $classroom->teacher_subject = $all_teacher_in_classroom;
                if ($all_teacher_in_classroom) {
                    for ($i = 0; $i < count($all_teacher_in_classroom); $i++) {
                        $teacher_subject = teacher_subject::query()->where('id', '=', $all_teacher_in_classroom[$i]->teacherSubject_id)->first();
                        $subject = Subject::query()->where('id', '=', $teacher_subject->subject_id)->first();

                        $classroom->teacher_subject[$i]->teacher_id = $teacher_subject->teacher_id;
                        $classroom->teacher_subject[$i]->subject_id = $teacher_subject->subject_id;
                        $classroom->teacher_subject[$i]->subject_name = $subject->name;
                    }
                }
                return response()->json([
                    'message' => 'success',
                    'data' => $classroom
                ]);
            }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name',
            'capacity',
            'class_id',
            'teachers_id',
            'students_id'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $classroom = Classroom::query()->where('id', '=', $request->id)->first();
        if (!$classroom) {
            return response()->json(['message' => 'NotFound']);
        }
        //checking the ids ---------------------------------------------------------------------------------------------
        if ($request->students_id)
            for ($i = 0; $i < count($request->students_id); $i++) {
                $student = Student::query()->where('user_id', '=', $request->students_id[$i])->first();
                if (!$student) {
                    return response()->json([
                        'message' => 'invalid students ids',
                    ], 404);
                }

            }
        if ($request->teachers_id)
            for ($i = 0; $i < count($request->teachers_id); $i++) {
                $teacher_subject = teacher_subject::query()->where('subject_id', '=', $request->teachers_id[$i]['subject_id'])
                    ->where('teacher_id', '=', $request->teachers_id[$i]['teacher_id'])->first();
                if (!$teacher_subject)
                    return response()->json([
                        'message' => 'invalid teachers ids',
                    ], 404);
//                //checking if subjects exist
//                $subject = Subject::query()->where('id', '=', $request->teachers_id[$i]['subject_id'])->first();
//                if (!$subject)
//                    return response()->json([
//                        'message' => 'invalid subjects ids',
//                    ], 404);
//                //checking if subject blongs to this classroom
////                    $valid = Class_Subject::query()->where('class_id', '=', $request->class_id)->where('subject_id
////                    ', '=', $subject->id)->first();
////                    if (!$valid)
////                        return response()->json([
////                            'message' => 'subject with id' . $subject->id . 'isn\'t for class with id' . $request->id,
////                        ], 404);
//
            }

        // -------------------------------------------------------------------------------------------------------------

        if ($request->name)
            $classroom->name = $request->name;
        if ($request->capacity)
            $classroom->capacity = $request->capacity;
        if ($request->class_id)
            $classroom->class_id = $request->class_id;
        $classroom->save();
        if ($request->teachers_id) {
            Teacher_classroom::query()->where('classroom_id', '=',$request->id)->delete();
            for ($i = 0; $i < count($request->teachers_id); $i++) {
                $teacher_classroom = new Teacher_classroom();
                $classroom_teacherSubject = new Classroom_teacherSubject();
                $teacher_subject=teacher_subject::query()->where('teacher_id','=',$request->teachers_id[$i]['teacher_id'])
                    ->where('subject_id','=',$request->teachers_id[$i]['subject_id'])->first();

                /*if (!$teacher_subject)
                    return response()->json([
                        'message' => 'invalid teachers_subject ids',
                    ], 404);*/

                //classroomteacherSubject
                $classroom_teacherSubject->teacherSubject_id =$teacher_subject->id;
                $classroom_teacherSubject->classroom_id =$classroom->id;


                //TeacherClassroom
                $teacher_classroom->teacher_id = $request->teachers_id[$i]['teacher_id'];
                $teacher_classroom->classroom_id = $classroom->id;

                /*$teacher_classroom->subject_id = $request->teachers_id[$i]['subject_id'];*/


                $classroom_teacherSubject->save();
                $teacher_classroom->save();
            }
        }
        if ($request->students_id) {
            $students = Student::query()->where('classroom_id', '=', $request->id)->get();
            if ($students) {
                for ($i = 0; $i < count($students); $i++) {
                    // $students[$i]->classroom_id = null;
                    Student::query()->where('user_id', '=', $students[$i]->user_id)->update(['classroom_id' => null]);
                }
                for ($i = 0; $i < count($request->students_id); $i++) {
                    $student = Student::query()->where('user_id', '=', $request->students_id[$i])->first();
                    if ($student) {
//                        $student->classroom_id = $classroom->id;
//                        $student->save();
                        Student::query()->where('user_id', '=', $request->students_id[$i])->update(['classroom_id' => $classroom->id]);
                    }
                }
            }
            $classroom->students;
            $classroom->teachers;
            $all_teacher_in_classroom = Classroom_teacherSubject::query()->where('classroom_id', '=', $classroom->id)->get();
            $classroom->teacher_subject = $all_teacher_in_classroom;
            if ($all_teacher_in_classroom) {
                for ($i = 0; $i < count($all_teacher_in_classroom); $i++) {
                    $teacher_subject = teacher_subject::query()->where('id', '=', $all_teacher_in_classroom[$i]->teacherSubject_id)->first();
                    $subject = Subject::query()->where('id', '=', $teacher_subject->subject_id)->first();

                    $classroom->teacher_subject[$i]->teacher_id = $teacher_subject->teacher_id;
                    $classroom->teacher_subject[$i]->subject_id = $teacher_subject->subject_id;
                    $classroom->teacher_subject[$i]->subject_name = $subject->name;
                }
            }
            return response()->json([
                'message' => 'success',
                'data' => $classroom
            ]);
        }
    }

    public
    function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $classroom = Classroom::query()->where('id', '=', $request->id)->first();
        if (!$classroom) {
            return response()->json(['message' => 'NotFound']);
        }
//        if ($classroom->students) {
//            for ($j = 0; $j < count($classroom->students); $j++) {
//                $user = User::query()->where('id', '=', $classroom->students[$j]->user_id)->with('student')->first();
//                $parent = User::query()->where('id', '=', $classroom->students[$j]->parent_id)->with('parent')->first();
//                $classroom->students[$j] = $user;
//                $classroom->students[$j] = $parent;
//            }
//        }


        $classroom->students;
        $teachers_subjects = Teacher_classroom::query()->where('classroom_id', '=', $classroom->id)->get();
        if ($classroom->students) {
            for ($j = 0; $j < count($classroom->students); $j++) {
                $user = User::query()->where('id', '=', $classroom->students[$j]->user_id)->with('student')->first();
                $parent = User::query()->where('id', '=', $classroom->students[$j]->parent_id)->with('parent')->first();
                $classroom->students[$j]->student = $user;
                $classroom->students[$j]->student->parent = $parent;
                Student::query()->where('user_id','=',$user->id)->update(['classroom_id'=>null]);
            }
        }
        if ($teachers_subjects)
            for ($j = 0; $j < count($teachers_subjects); $j++) {
                $subject = Subject::query()->where('id', '=', $teachers_subjects[$j]->subject_id)->first();
                $teachers_subjects[$j]->subject_name = $subject->name;
            }
        $classroom->teacher_subject = $teachers_subjects;
        $classroom->teachers;
        $classroom->delete();

        return response()->json(
            $classroom
        );
    }

    public
    function show_my_class(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $classroom = Classroom::query()->where('id', '=', $request->id)->first();
        if (!$classroom) {
            return response()->json(['message' => 'NotFound']);
        }

        return $classroom->class;
    }
}

