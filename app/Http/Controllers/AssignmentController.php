<?php

namespace App\Http\Controllers;

use App\Models\Archive_Year;
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

//        //archive related
//        $archiveYears = Archive_Year::query()->get();
//        $arr = [];
//        for ($i = 0; $i < count($archiveYears); $i++) $arr[] = $archiveYears[$i]->year;
//        if (!in_array(now()->month < 9 ? now()->year - 1 : now()->year, $arr)) {
//            $archiveYear = new Archive_Year();
//            if (now()->month < 9)
//                $archiveYear->year = now()->year - 1;
//            else         $archiveYear->year = now()->year;
//            $archiveYear->save();
//        }
        $assignment['classroom']=Classroom::query()->where('id','=',$request->classroom_id)->first();

        return response()->json([
            'message' => 'added',
            'data'=>$assignment
        ]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'title',
            'content' ,
            'date' ,
            'teacher_id' ,
            'classroom_id'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $assignment = Assignment::query()->find($request->id);
        if(!$assignment)
            return response()->json([
                'message' => 'Assignment Not Found'
            ]);

        $assignment->title = $request->title;
        $assignment->content = $request['content'];
        $assignment->date = $request->date;
        $assignment->teacher_id = $request->teacher_id;
        $assignment->classroom_id = $request->classroom_id;

        $assignment->save();
        $assignment['classroom']=Classroom::query()->where('id','=',$request->classroom_id)->first();
        return response()->json([
            'message' => 'updated',
            'data'=>$assignment
        ]);

    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $assignment = Assignment::query()->find($request->id);
        if(!$assignment)
            return response()->json([
                'message' => 'Assignment Not Found'
            ]);

        $assignment->delete();
        $assignment['classroom']=Classroom::query()->where('id','=',$request->classroom_id)->first();
        return response()->json([
            'message' => 'deleted',
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
        for($i=0;$i<count($teacher->assignments);$i++){
            $teacher->assignments[$i]->classroom;
            //$classroom_name = Classroom::query()->where('id','=',$teacher->assignments[$i]->clasroom_id)
        }

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
