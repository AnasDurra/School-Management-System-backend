<?php

namespace App\Http\Controllers;

use App\Models\Classes;
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
        return $class->classrooms;
    }
}
