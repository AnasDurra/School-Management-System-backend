<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'capacity' => 'required',
            'class_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $classroom = new Classroom();
        $classroom->name = $request->name;
        $classroom->capacity = $request->capacity;
        $classroom->class_id = $request->class_id;

        $classroom->save();

        return response()->json([
            'message' => 'added',
            'data' => $classroom
        ]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name' => 'required',
            'capacity' => 'required',
            'class_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $classroom = Classroom::query()->where('id','=',$request->id)->first();
        $classroom->name = $request->name;
        $classroom->capacity = $request->capacity;
        $classroom->class_id = $request->class_id;
        $classroom->save();

        if(!$classroom){return response()->json(['message'=>'NotFound']);}

        return response()->json([
            'message' => 'updated',
            'data' => $classroom
        ]);
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $classroom = Classroom::query()->where('id','=',$request->id)->delete();
        if(!$classroom){return response()->json(['message'=>'NotFound']);}

        return response()->json([
            'message' => 'deleted'
        ]);
    }

    public function show_my_class(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $classroom = Classroom::query()->where('id','=',$request->id)->first();
        if(!$classroom){return response()->json(['message'=>'NotFound']);}

        return $classroom->class;
    }
}

