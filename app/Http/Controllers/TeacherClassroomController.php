<?php

namespace App\Http\Controllers;

use App\Models\Class_Subject;
use App\Models\Classes;
use App\Models\Classroom;
use App\Models\Classroom_teacherSubject;
use App\Models\Mark;
use App\Models\Obligate;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Teacher_classroom;
use App\Models\teacher_subject;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherClassroomController extends Controller
{


    public function getTeacherObjections(Request $request)
    {
        Validator::make($request->all(), [
            'teacher_id' => 'required'
        ]);
        $teacher = Teacher::query()->where('user_id', '=', $request->teacher_id)->first();
        if (!$teacher) {
            return response()->json(
                [
                    'invalid teacher id'
                ], 404
            );
        }
//        $classrooms_id = [];
//        for ($i = 0; $i < count($teacher->classrooms); $i++) {
//            $classrooms_id [] = $teacher->classrooms[$i]->id;
//        }
        $obligations = Obligate::query()->get();
        $data = [];
        $j=0;
        for ($i = 0; $i < count($obligations); $i++) {
            $obj_classroom_id = $obligations[$i]->mark->student->classroom->id;
            $obj_subject_id = $obligations[$i]->mark->subject->id;
            $teacher_subject = teacher_subject::query()->where('teacher_id', '=', $request->teacher_id)
                ->where('subject_id', '=', $obj_subject_id)->first();
            if(!$teacher_subject) continue;
            $yes =Classroom_teacherSubject::query()->where(
                'classroom_id', '=', $obj_classroom_id
            )
                ->where('teacherSubject_id', '=',$teacher_subject->id)->first();
            if(!$yes)continue;

            $data[$j]['body'] = $obligations[$i]->body;
            $data[$j]['type'] = Type::query()->where('id', '=', $obligations[$i]->mark->type_id)->first();
            $data[$j]['subject'] = Subject::query()->where('id', '=', $obligations[$i]->mark->subject_id)->first();
            $data[$j]['student'] = User::query()->where('id', '=', $obligations[$i]->mark->student_id)->first();
            $data[$j]['classroom'] = Student::query()->where('user_id', '=', $obligations[$i]->mark->student_id)->first()->classroom;
            $mark = Mark::query()->where('id', '=', $obligations[$i]->mark->id)->first();
            $mark->type;
            $data[$j]['mark'] = $mark;
            $j++;
        }

        return response()->json(
            [
                'data' => $data,
            ]
        );
    }

    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'teacher_id' => 'required',
            'classrooms_id' => 'required'//array
        ]);

        $teacher = User::query()->where('id', '=', $request->teacher_id)->first();
        for ($i = 0; $i < count($request->classrooms_id); $i++) {
            $classroom = Classroom::query()->where('id', '=', $request->classrooms_id[$i])->first();
            if (!$classroom) {
                return response()->json(
                    [
                        'invalid classroom id'
                    ], 404
                );
            }
        }
        if (!$teacher) {
            return response()->json(
                [
                    'invalid teacher id'
                ], 404
            );
        }
        Teacher_classroom::query()->where('teacher_id', '=', $request->teacher_id)->delete();
        for ($i = 0; $i < count($request->classrooms_id); $i++) {
            $teacher_classroom = new Teacher_classroom();
            $teacher_classroom->teacher_id = $request->teacher_id;
            $teacher_classroom->classroom_id = $request->classrooms_id[$i];
            $teacher_classroom->save();
        }
        return response()->json(
            [
                'message' => 'success',

            ]
        );
    }

    public function getTeacherClassrooms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $teacher = Teacher::query()->where('user_id', '=', $request->teacher_id)->first();

        if (!$teacher) {
            return response()->json([
                'error' => 'Not Found'
            ]);
        }
        $classrooms = $teacher->classrooms;

        $teacher_subject = null;
        $index = 0;
        for ($i = 0; $i < count($classrooms); $i++) {
            $classrooms[$i]['class_name'] = Classes::query()->where('id', '=', $classrooms[$i]->class_id)->first()->name;
            $index = 0;
            $teacher_subject = null;
            $classroom_teacher_subject = Classroom_teacherSubject::query()->where('classroom_id', '=', $classrooms[$i]->id)->get();
            for ($j = 0; $j < count($classroom_teacher_subject); $j++) {
                $temp = teacher_subject::query()->where('id', '=', $classroom_teacher_subject[$j]->teacherSubject_id)
                    ->where('teacher_id', '=', $request->teacher_id)->first();
                if ($temp) {
                    $subject = Subject::query()->where('id', '=', $temp->subject_id)->first();
                    $teacher_subject[$index]['teacher_id'] = $temp->teacher_id;
                    $teacher_subject[$index]['subject_id'] = $temp->subject_id;
                    $teacher_subject[$index]['subject_name'] = $subject->name;
                    $index++;

                }
            }
            $classrooms[$i]['teacher_subject'] = $teacher_subject;

            $student = $classrooms[$i]->students;
            for ($j = 0; $j < count($student); $j++)
                $student[$j]->user;

            $classrooms[$i]['students'] = $student;
        }
        return response()->json([
            'teacher' => $teacher,
        ]);


    }
}
