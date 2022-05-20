<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    public function newAssignment(Request $request){
        $validator = Validator::make($request->all(), [
            'title'=>'required',
            'content' => 'required',
            'date' => 'required',
            'teacher_id' => 'required',
            'classroom_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $assignment = new Assignment();
        $assignment->title = $request['title'];
        $assignment->content = $request['content'];
        $assignment->date = $request->date;
        $assignment->teacher_id = $request->teacher_id;
        $assignment->classroom_id = $request->classroom_id;

        $assignment->save();

        return response()->json([
            'message' => 'added',
            'data'=>$assignment
        ]);
    }

    public function get_teacher_assig(Request $request){
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $teacher = Teacher::query()->where('user_id','=',$request->teacher_id)->with('user')->first();
        if(!$teacher) return response()->json([
            'message' => 'Not Found'
        ]);
        $teacher->assignments;
        return response()->json([
            'teacher' => $teacher
        ]);
    }

    //get all assig for classroom =>
    public function get_students_assig(Request $request){
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $classroom = Classroom::query()->where('id','=',$request->classroom_id)->first();
        if(!$classroom) return response()->json([
            'message' => 'Not Found'
        ]);

        $classroom->assignments;
        return response()->json([
            'classroom' => $classroom
        ]);
    }
}
