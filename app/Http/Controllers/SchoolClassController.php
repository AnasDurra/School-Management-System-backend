<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User;
use http\Env\Response;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SchoolClassController extends Controller
{
    //
    public function add(Request $request){
        // Validate request data
        $validator = Validator::make($request->all(), [
            'grade' => 'required|unique:school_classes'
        ]);
        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        // Check if validation pass then create user and auth token. Return the auth token
        if ($validator->passes()) {
            $newClass = SchoolClass::create([
                'grade'=>$request->grade
            ]);


            return response()->json([
                'message'=>'class created !'
            ]);
        }
    }
    public function all(Request $request){
        $classes= SchoolClass::all();
        return response()->json(

                $classes

        );
    }
    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'grade' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $class = SchoolClass::query()->where('grade','=',$request->old)->firstOrFail() ;
        $class['grade']=$request->new;
        $class->save();
        return response()->json([
            'message'=>'success'
        ]);

    }
    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $class = SchoolClass::query()->where('id','=',$request->id)->first() ;
        $class->delete();

        return response()->json([
            'message'=>'deleted'
        ]);
    }
    public function one(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $class = SchoolClass::query()->where('id','=',$request->id)->first() ;
        return \response()->json(
            $class
        );
    }
}
