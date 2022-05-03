<?php

namespace App\Http\Controllers;

use App\Models\Class_Subject;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $subject = new Subject();
        $subject->name = $request->name;
        $subject->save();



        return response()->json([
            'message' => 'added',
            'data' => $subject,
        ]);
    }

    public function all(Request $request){
        $subjects = Subject::all();

        if(!$subjects){
            return response()->json(['message'=>'No subjects']);
        }

        return response()->json([
           'data'=>$subjects
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $subject = Subject::query()->where('id', '=', $request->id)->first();
        if (!$subject) {
            return response()->json(['message' => 'Not Found']);
        }
        $subject->name = $request->name;
        $subject->save();

        return response()->json([
            'message' => 'updated',
            'data' => $subject
        ]);
    }

    public function delete(Request $request)
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
        $subject = Subject::query()->where('id', '=', $request->id)->first();
        if (!$subject) {
            return response()->json(['message' => 'Not Found']);
        }

        $clone = $subject;
        $subject->delete();

        return response()->json([
            'message' => 'success',
            'subject'=>$clone,

        ]);
    }


    //get all teachers of a subject
    public function subject_teachers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $subject = Subject::query()->where('id', '=', $request->subject_id)->first();
        if (!$subject) {
            return response()->json(['message' => 'Not Found']);
        }

        $subject_teachers = $subject->teachers;

        for ($i = 0; $i < count($subject_teachers); $i++) {
            $subject_teachers[$i] = ['teacher' => $subject_teachers[$i], 'user' => User::query()->where('id', '=', $subject_teachers[$i]->user_id)->first()];
        }
        return response()->json([
            $subject_teachers
        ], 400);
    }


}
