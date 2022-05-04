<?php

namespace App\Http\Controllers;

use App\Models\Classroom;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    public function all(Request $request){
        $classrooms = Classroom::all();
        if($classrooms) {
            for($i =0;$i<count($classrooms);$i++){
                if($classrooms[$i]->students){
                    for($j=0;$j<count($classrooms[$i]->students);$j++){
                     $user =  User::query()->where('id','=',  $classrooms[$i]->students[$j]->user_id)->with('student')->first();
                     $parent = User::query()->where('id','=',  $classrooms[$i]->students[$j]->parent_id)->with('parent')->first();
                        $classrooms[$i]->students[$j]=$user;
                        $classrooms[$i]->students[$j]=$parent;
                    }
                }
            }
        }
        return response()->json(
            $classrooms
        );

    }
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
            'message' => 'success',
            'data' => $classroom
        ]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name',
            'capacity' ,
            'class_id'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $classroom = Classroom::query()->where('id','=',$request->id)->first();
        if(!$classroom){return response()->json(['message'=>'NotFound']);}
        if($request->name)
        $classroom->name = $request->name;
        if($request->capacity)
        $classroom->capacity = $request->capacity;
        if($request->class_id)
        $classroom->class_id = $request->class_id;
        $classroom->save();

        return response()->json([
            'message' => 'success',
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

        $classroom = Classroom::query()->where('id','=',$request->id)->first();
        if(!$classroom){return response()->json(['message'=>'NotFound']);}
        if($classroom->students){
            for($j=0;$j<count($classroom->students);$j++){
                $user =  User::query()->where('id','=',  $classroom->students[$j]->user_id)->with('student')->first();
                $parent = User::query()->where('id','=',  $classroom->students[$j]->parent_id)->with('parent')->first();
                $classroom->students[$j]=$user;
                $classroom->students[$j]=$parent;
            }
        }


            $classroom->students;
            $classroom->delete();

        return response()->json(
            $classroom
        );
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

