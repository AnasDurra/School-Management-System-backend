<?php

namespace App\Http\Controllers;

use App\Models\Class_Subject;
use App\Models\Classroom;

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
                $teachers_subjects = Teacher_classroom::query()->where('classroom_id', '=', $classrooms[$i]->id)->get();
                if ($classrooms[$i]->students) {
                    for ($j = 0; $j < count($classrooms[$i]->students); $j++) {
                        $user = User::query()->where('id', '=', $classrooms[$i]->students[$j]->user_id)->with('student')->first();
                        $parent = User::query()->where('id', '=', $classrooms[$i]->students[$j]->parent_id)->with('parent')->first();
                        $classrooms[$i]->students[$j] = $user;
                        $classrooms[$i]->students[$j] = $parent;
                    }
                }
                $classrooms[$i]->teacher_subject = $teachers_subjects;
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
        if ($request->sudents_id)
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
                $teacher = Teacher::query()->where('user_id', '=', $request->teachers_id[$i]['teacher_id'])->first();
                if (!$teacher)
                    return response()->json([
                        'message' => 'invalid teachers ids',
                    ], 404);
                for ($j = 0; $j < count($request->teachers_id[$i]['subjects_id']); $j++) {
                    //checking if subjects exist
                    $subject = Subject::query()->where('id', '=', $request->teachers_id[$i]['subjects_id'][$j])->first();
                    if (!$subject)
                        return response()->json([
                            'message' => 'invalid subjects ids',
                        ], 404);
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

                }
            }
        //--------------------------------------------------------------------------------------------------------------

        $classroom = new Classroom();
        $classroom->name = $request->name;
        $classroom->capacity = $request->capacity;
        $classroom->class_id = $request->class_id;
        $classroom->save();
        if ($request->teachers_id)
            for ($i = 0; $i < count($request->teachers_id); $i++) {
                $teacher_classroom = new Teacher_classroom();
                $teacher_classroom->teacher_id = $request->teachers_id[$i]['teacher_id'];
                for ($j = 0; $j < count($request->teachers_id[$i]['subjects_id']); $j++)
                    $teacher_classroom->subject_id = $request->teachers_id[$i]['subjects_id'][$j];
                $teacher_classroom->classroom_id = $classroom->id;
                $teacher_classroom->save();
            }
        if ($request->students_id) {
            for ($i = 0; $i < count($request->students_id); $i++) {
                $student = Student::query()->where('user_id', '=', $request->students_id[$i])->first();
                if ($student) {
                    $student->classroom_id = $classroom->id;
                    $student->save();
                }
            }
        }
        $classroom->students;
        $classroom->teachers;
        $teacher_subject = Teacher_classroom::query()->where('classroom_id','=',$classroom->id)->get();
        $classroom->teacher_subject = $teacher_subject;
        return response()->json([
            'message' => 'success',
            'data' => $classroom
        ]);
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
                $teacher = Teacher::query()->where('user_id', '=', $request->teachers_id[$i]['teacher_id'])->first();
                if (!$teacher)
                    return response()->json([
                        'message' => 'invalid teachers ids',
                    ], 404);
                for ($j = 0; $j < count($request->teachers_id[$i]['subjects_id']); $j++) {
                    //checking if subjects exist
                    $subject = Subject::query()->where('id', '=', $request->teachers_id[$i]['subjects_id'][$j])->first();
                    if (!$subject)
                        return response()->json([
                            'message' => 'invalid subjects ids',
                        ], 404);
                    //checking if subject blongs to this classroom
//                    $valid = Class_Subject::query()->where('class_id', '=', $request->class_id)->where('subject_id
//                    ', '=', $subject->id)->first();
//                    if (!$valid)
//                        return response()->json([
//                            'message' => 'subject with id' . $subject->id . 'isn\'t for class with id' . $request->id,
//                        ], 404);
                }
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
            Teacher_classroom::query()->where('classroom_id', '=', $request->id)->delete();
            for ($i = 0; $i < count($request->teachers_id); $i++) {
                $teacher_classroom = new Teacher_classroom();
                $teacher_classroom->teacher_id = $request->teachers_id[$i]['teacher_id'];
                for ($j = 0; $j < count($request->teachers_id[$i]['subjects_id']); $j++)
                    $teacher_classroom->subject_id = $request->teachers_id[$i]['subjects_id'][$j];
                $teacher_classroom->classroom_id = $classroom->id;
                $teacher_classroom->save();
            }
        }
        if ($request->students_id) {
            $students = Student::query()->where('classroom_id', '=', $request->id)->get();
            if ($students) {
                for ($i = 0; $i < count($students); $i++) {
                    $students[$i]->classroom_id = null;
                }
                for ($i = 0; $i < count($request->students_id); $i++) {
                    $student = Student::query()->where('user_id', '=', $request->students_id[$i])->first();
                    if ($student) {
                        $student->classroom_id = $classroom->id;
                        $student->save();
                    }
                }
            }
            $classroom = Classroom::query()->where('id', '=', $request->id)->first();
            $classroom->students;
            $teacher_subject = Teacher_classroom::query()->where('classroom_id','=',$classroom->id)->get();
            $classroom->teachers;
            $classroom->teacher_subject = $teacher_subject;
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
        if ($classroom->students) {
            for ($j = 0; $j < count($classroom->students); $j++) {
                $user = User::query()->where('id', '=', $classroom->students[$j]->user_id)->with('student')->first();
                $parent = User::query()->where('id', '=', $classroom->students[$j]->parent_id)->with('parent')->first();
                $classroom->students[$j] = $user;
                $classroom->students[$j] = $parent;
            }
        }


        $classroom->students;
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

