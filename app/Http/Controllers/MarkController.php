<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarkController extends Controller
{
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'value' => 'required',
            'subfield_id' => 'required',  //change to not required and make the variable nullable !!
            'subject_id' => 'required',
            'student_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        Mark::query()->create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'subfield_id' => $request->subfield_id,
            'value' => $request->value,
        ]);
        return response()->json([
            'message' => 'added'
        ]);
    }

    //get student marks
    public function getStudentMarks(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $student =Student::query()->where('id','=',$request->id)->first();
        if(!$student) return response()->json(['message'=>'NotFound']);

        return $student->marks;
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'subject_id'=>'required',
            'subfield_id'=>'required', //change to not required and make the variable nullable !!
            'value'=>'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $mark=Mark::query()->where('student_id','=',$request->student_id)->
        where('subject_id','=',$request->subject_id)->
        where('subfield_id','=',$request->subfield_id)->first();

        if(!$mark){
            return response()->json([
                'message' => 'NotFound'
            ]);
        }

        $mark->update([
            'value'=>$request->value
        ]);
        return response()->json([
            'message' => 'updated'
        ]);
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'subject_id'=>'required',
            'subfield_id'=>'required', //change to not required and make the variable nullable !!
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $mark=Mark::query()->where('student_id','=',$request->student_id)->
        where('subject_id','=',$request->subject_id)->
        where('subfield_id','=',$request->subfield_id)->delete();

        if(!$mark){
            return response()->json([
                'message' => 'NotFound'
            ]);
        }

        return response()->json([
            'message' => 'deleted'
        ]);

    }


}
