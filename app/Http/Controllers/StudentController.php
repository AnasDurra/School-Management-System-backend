<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Classroom;
use App\Models\Paarent;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    //
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'parent_name',
            'parent_phone_num',
            'phone_num' => 'required',
            'address',
            'classroom_id', // optional
            "class_id" => 'required',
            'parent_username', // if parent already existing
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->username = strtolower(Str::random(10));
        $user->password = strtolower(Str::random(6));
        $user->phone_num = $request->phone_num;
        if ($request->address)
            $user->address = $request->address;
        $user->role = 4;
        $user->save();
        $has_parents_in_system = false;
        $student = new Student();
        if ($request->classroom_id)
            $student['classroom_id'] = $request->classroom_id;
        if ($request->parent_username) {
            $student_parent = $user::query()->where('username', '=', $request->parent_username)->first();
            $student['parent_id'] = $student_parent->id;
            $has_parents_in_system = true;
        }
        if (!$has_parents_in_system) {
            $user2 = new User();
            $user2->name = $request->parent_name;
            $user2->address = $request->address;
            $user2->username = strtolower(Str::random(10));
            $user2->password = strtolower(Str::random(6));
            $user2->phone_num = $request->parent_phone_num;
            $user2->role = 3;
            $user2->save();
            $student['parent_id'] = $user2->id;
            $new_parent = new Paarent();
            $new_parent->user_id = $user2->id;
            $new_parent->save();
        }
        $student['user_id'] = $user->id;
        $student['class_id'] = $request->class_id;
        $student->save();
      $student=Student::query()->where('user_id',"=",$student->user_id)->with('parent')->first();
        $class = Classes::query()->where('id', '=', $student->class_id)->first();
        $class_room = Classroom::query()->where('id','=',$student->classroom_id)->first();
        $parent = Paarent::query()->where('user_id','=',$student->parent_id)->first();
        $student->class =$class;
        $student->parent =$parent;
        $student->classroom =$class_room;
        return response()->json([
            'message' => 'success',
            'user' => $user,
            'student' => $student,
            'parent_was_in_system'=>$has_parents_in_system

        ]);

    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required', //for user
            'name',
            'username',
            'password',
            'phone_num',
            'address',
            'classroom_id',
            'parent_id',
            'class_id'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }


//
//        if (!$student) {
//            return response()->json([
//                'error' => 'not found'
//            ], 404);
//        }

        $user = User::query()->where('id', '=', $request->id)->with('student')->first();
        $student = Student::query()->where('user_id', '=', $user->id)->first();
        if ($request->name)
            $user['name'] = $request->name;
        if ($request->username)
            $user['username'] = $request->username;
        if ($request->password)
            $user['password'] = $request->password;
        if ($request->phone_num)
            $user['phone_num'] = $request->phone_num;
        if ($request->address)
            $user['address'] = $request->address;

        if ($request->classroom_id)
            $student['classroom_id'] = $request->classroom_id;
        if ($request->parent_id)
            $student['parent_id'] = $request->parent_id;
        if ($request->class_id)
            $student['class_id'] = $request->class_id;

        $user->save();
        $student->save();


        return response()->json([
            'message' => 'success',
            'data' => $user
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

        $student = Student::query()->where('id', '=', $request->id)->first();
        $user = User::query()->where('id', '=', $student->user_id)->first();
        $user->delete();

        return response()->json([
            'message' => 'deleted'
        ]);
    }


    //
    //Return all student
    public function all(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'classroom_id' => 'required'
//        ]);
//        if ($validator->fails()) {
//            $errors = $validator->errors();
//            return response()->json([
//                'error' => $errors
//            ], 400);
//        }
        $students = User::query()->where('role', '=', 4)->with('student')->get();
if($students)
for($i=0;$i<count($students);$i++){
    $class = Classes::query()->where('id', '=', $students[$i]['student']->class_id)->first();
    $class_room = Classroom::query()->where('id','=',$students[$i]['student']->classroom_id)->first();
    $parent = Paarent::query()->where('user_id','=',$students[$i]['student']->parent_id)->first();
    $students[$i]->class =$class;
    $students[$i]->parent =$parent;
    $students[$i]->classroom =$class_room;
}
        if (!$students) {
            return response()->json([
                'error' => 'no students yet'
            ], 404);
        }

//        for ($i = 0; $i < count($students); $i++) {
//            $users[] = ['STUDENT_ID' => $students[$i]->id, User::query()->where('id', '=', $students[$i]->user_id)->first()];
//        }
        return response()->json(
            $students
        );
    }


    public function one(Request $request)
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
        $student = Student::query()->where('id', '=', $request->id)->first();
        if (!$student) {
            return response()->json([
                'error' => 'NotFound'
            ], 404);
        }
        $user = ['STUDENT_ID' => $student->id, User::query()->where('id', '=', $student->user_id)->first()];
        return response()->json(
            $user
        );
    }
}
