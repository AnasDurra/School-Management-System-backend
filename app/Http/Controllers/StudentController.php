<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    //
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password'=> 'required',
            'phone_num' => 'required',
            'address' => 'required',
            'classroom_id'=>'required',
            'parent_id'=>'required',
            'user_id'=>'parent_id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $user = new User();
        $user->username = $request->username;
        $user->password = $request->password;
        $user->phone_num = $request->phone_num;
        $user->address = $request->address;
        $user->role = 4;
        $user->save();

        $student=new Student();
        $student['classroom_id'] = $request->classroom_id;
        $student['parent_id'] = $request->parent_id;
        $student['user_id'] = $request->user_id;

        $student->save();
        return response()->json([
            'message' => 'added'
        ]);

    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'username' => 'required|string|max:255',
            'password'=> 'required',
            'phone_num' => 'required',
            'address' => 'required',
            'classroom_id'=>'required',
            'parent_id'=>'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $student = student::query()->where('id', '=', $request->id)->first();

        if(!$student){
            return response()->json([
                'error' => 'not found'
            ], 404);
        }

        $user = User::query()->where('id', '=', $student->user_id)->first();
        $user['username'] = $request->username;
        $user['password'] = $request->password;
        $user['phone_num'] = $request->phone_num;
        $user['address'] = $request->address;

        $student['classroom_id'] = $request->classroom_id;
        $student['parent_id'] = $request->parent_id;

        $student->save();
        $user->save();

        return response()->json([
            'message' => 'success'
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

        $student = student::query()->where('id', '=', $request->id)->first();
        $user = User::query()->where('id', '=', $student->user_id)->first();
        $user->delete();

        return response()->json([
            'message' => 'deleted'
        ]);
    }


    //
    //Return all student in a specific class
    public function all(Request $request){
        $students= Student::query()->where('classroom_id','=',$request->id)->get();

        if(!$students){
            return response()->json([
                'error' => 'no students yet'
            ], 404);
        }

        for($i=0;$i<count($students);$i++) {
            $users[] = ['STUDENT_ID'=>$students[$i]->id ,User::query()->where('id', '=', $students[$i]->user_id)->first()];
        }
        return response()->json(
            $users
        );
    }


    public function one(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $student = student::query()->where('id','=',$request->id)->first() ;
        if(!$student){
            return response()->json([
                'error' => 'NotFound'
            ], 404);
        }
        $user =['STUDENT_ID'=>$student->id,User::query()->where('id','=',$student->user_id)->first()];
        return response()->json(
            $user
        );
    }
}
