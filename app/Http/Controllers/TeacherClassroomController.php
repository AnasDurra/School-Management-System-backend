<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Teacher_classroom;
use App\Models\teacher_subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherClassroomController extends Controller
{
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

    public function GetTeacherClassrooms(Request $request){
        $validator=Validator::make($request->all(), [
            'teacher_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $teacher = Teacher::query()->where('user_id','=',$request->teacher_id)->first();

        if(!$teacher) {
            return response()->json([
                'error' => 'Not Found'
            ]);
        }
        $classrooms = $teacher->classrooms;

        $student=null;
        for($i=0;$i<count($classrooms);$i++){
            $student = $classrooms[$i]->students;
            for($j=0;$j<count($student);$j++)
                $student[$j]->user;

            $classrooms[$i]['students'] = $student;
        }
        return response()->json([
            'teacher' => $teacher,
        ]);



    }
}
