<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\at;

class MarkController extends Controller
{
    public function setMarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'info' =>'required', //array , for each one => { student_id , subject_id , value }
            'type_id'=>'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        //checking the students ids
        for ($i = 0; $i < count($request->info); $i++) {
            $student = Student::query()->where('user_id', '=', $request->info[$i]['student_id'])->first();
            if (!$student) {
                return response()->json(['message' => 'wrong student id !', 'student_id' => $request->info[$i]['student_id']], 404);
            }
        }

        //checking the subjects ids
        for ($i = 0; $i < count($request->info); $i++) {
            $subject = Subject::query()->where('id', '=', $request->info[$i]['subject_id'])->first();
            if (!$subject) {
                return response()->json(['message' => 'wrong subject id !', 'subject_id' => $request->info[$i]['subject_id']], 404);
            }
        }

        //types does not need to check

        if($request->info) {
            for ($i = 0; $i < count($request->info); $i++) {
                $mark[$i]=new Mark();
                $subject = Subject::query()->where('id', '=', $request->info[$i]['subject_id'])->first();

                $student = Student::query()->where('user_id', '=', $request->info[$i]['student_id'])->first();

                $mark[$i]['student_id'] =$request->info[$i]['student_id'] ;
                $mark[$i]['subject_id'] =$request->info[$i]['subject_id'];
                $mark[$i]['type_id'] =$request->type_id;
                $mark[$i]['value'] = $request->info[$i]['value'];

                $mark[$i]->save();
            }
        }
        return response()->json([
            'message' => 'success',
            'data' => $mark
        ]);
    }


    //get student marks
    public function getStudentMarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $student = Student::query()->where('user_id', '=', $request->student_id)->with('marks')->first();
        if (!$student) return response()->json(['message' => 'Student Not Found']);

        $student->user;
        if ($student->marks) {
            for ($i = 0; $i < count($student->marks); $i++) {
                $mark =$student->marks[$i];
                $subject = Subject::query()->where('id','=',$mark->subject_id)->first();
                $student->marks[$i]->subject_name = $subject->name;
            }
        }
        return response()->json(['data'=>$student]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'value',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $mark = Mark::query()->where('id', '=', $request->id)->first();
        if (!$mark) {
            return response()->json(['message' => 'invalid id !'], 404);
        }
        if ($request->value)
            $mark->value = $request->value;
        $mark->save();
        $subject = Subject::query()->where('id', '=', $mark->subject_id)->first();
        $mark->subject = $subject;
        return response()->json([
            'message' => 'success',
            'data' => $mark
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $mark = Mark::query()->where('id', '=', $request->id)->first();
        $subject = Subject::query()->where('id', '=', $mark->subject_id)->first();
        $mark->delete();
        $mark->subject = $subject;
        if (!$mark) {
            return response()->json([
                'message' => 'NotFound'
            ]);
        }

        return response()->json([
            'message' => 'success',
            'data' => $mark
        ]);

    }


}
