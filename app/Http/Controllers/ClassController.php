<?php

namespace App\Http\Controllers;

use App\Models\Class_Subject;
use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $class = new Classes();
        $class->name = $request->name;
        $class->save();

        return response()->json([
            'message' => 'added',
            'data'=>$class
        ]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $class = Classes::query()->where('id','=',$request->id)->first();
        if(!$class){return response()->json(['message'=>'NotFound']);}

        $class->name =$request->name;
        $class->save();

        return response()->json([
            'message' => 'updated',
            'data'=>$class
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

        $class = Classes::query()->where('id','=',$request->id)->first();
        if(!$class){return response()->json(['message'=>'NotFound']);}

        $class->delete();

        return response()->json([
            'message' => 'deleted'
        ]);
    }

    public function all(Request $request){
        $classes =Classes::query()->with('subjects')->get();

        if(!$classes){
            return response()->json([
                'message' => 'No classes'
            ]);
        }
        return response()->json([
            'data' => $classes
        ]);

    }

    public function show_classrooms(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $class = Classes::query()->where('id','=',$request->id)->first();
        if(!$class){return response()->json(['message'=>'NotFound']);}

        return response()->json([
            'data'=> $class->classrooms
        ]);
    }

    ##add subjects to a class
    public function add_subjects_to_class(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_ids' => 'required',  //array of ids of subjects
            'class_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        for($i=0;$i<count($request->subject_ids);$i++){
            $class_subject[$i] =new Class_Subject();
            $class_subject[$i]->subject_id=$request->subject_ids[$i];
            $class_subject[$i]->class_id=$request->class_id;
            $class_subject[$i]->save();

        }

        return response()->json([
            'message' => 'added',
            'data'=>$class_subject
        ]);
    }

    public function delete_subject_from_class(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required',
            'class_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $class_subject = Class_Subject::query()->where('subject_id','=',$request->subject_id)->
        where('class_id','=',$request->class_id)->delete();

        if (!$class_subject) {
            return response()->json(['message' => 'Not Found']);
        }
        return response()->json([
            'message' => 'deleted'
        ]);
    }

    //aaa
    public function get_subjects_of_class(Request $request){
        $validator = Validator::make($request->all(), [
            'class_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $class=Classes::query()->where('id','=',$request->class_id)->first();
        if (!$class) {
            return response()->json(['message' => 'Not Found']);
        }

        $class_subjects =$class->subjects;

        for($i=0;$i<count($class_subjects);$i++) {
            $class_subjects[$i] = Subject::query()->where('id', '=', $class_subjects[$i]->id)->with('subfields')->first();
            }
        return response()->json([
            'data' => $class->subjects
        ]);
    }
}
