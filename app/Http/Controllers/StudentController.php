<?php

namespace App\Http\Controllers;

use App\Models\Absent;
use App\Models\Classes;
use App\Models\Classroom;
use App\Models\Mark;
use App\Models\Paarent;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
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
            //TODO if address isn't set take the address of parents
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
        $student = new Student();
        $has_parents_in_system = false;

        if ($request->parent_username) {
            $student_parent = $user::query()->where('username', '=', $request->parent_username)->first();
            if ($student_parent) {
                $student['parent_id'] = $student_parent->id;
                $has_parents_in_system = true;
            } else {
                return response()->json("username isn't in the system!", 451);
            }
        }
        $user->save();

        if ($request->classroom_id)
            $student['classroom_id'] = $request->classroom_id;

        if (!$has_parents_in_system) {
            $student_parent = new User();
            $student_parent->name = $request->parent_name;
            $student_parent->address = $request->address;
            $student_parent->username = strtolower(Str::random(10));
            $student_parent->password = strtolower(Str::random(6));
            $student_parent->phone_num = $request->parent_phone_num;
            $student_parent->role = 3;
            $student_parent->save();
            $new_parent = new Paarent();
            $new_parent->user_id = $student_parent->id;
            $new_parent->save();
            $student['parent_id'] = $student_parent->id;
        }
        $student['user_id'] = $user->id;
        $student['class_id'] = $request->class_id;
        $student->save();
        $student = Student::query()->where('user_id', "=", $student->user_id)->first();
        $class = Classes::query()->where('id', '=', $student->class_id)->first();
        $class_room = Classroom::query()->where('id', '=', $student->classroom_id)->first();
        $parent = Paarent::query()->where('user_id', '=', $student->parent_id)->first();
        $student->class = $class;
        //$student->parent = $parent;

        $student->parent = $parent;
        $student->classroom = $class_room;
        return response()->json([
            'message' => 'success',
            'user' => $user,
            'student' => $student,
            'parent_was_in_system' => $has_parents_in_system,
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
            //  $student['classroom_id'] = $request->classroom_id;
            Student::query()->where('user_id', '=', $request->id)->update(["classroom_id" => $request->classroom_id]);
        if ($request->parent_id)
            // $student['parent_id'] = $request->parent_id;
            Student::query()->where('user_id', '=', $request->id)->update(["parent_id" => $request->parent_id]);
        if ($request->class_id)
            // $student['class_id'] = $request->class_id;
            Student::query()->where('user_id', '=', $request->id)->update(["class_id" => $request->class_id]);

        $user->save();
        // $student->save();
        $user = User::query()->where('id', '=', $student->user_id)->with('student')->first();
        $class = Classes::query()->where('id', '=', $user['student']->class_id)->first();
        $class_room = Classroom::query()->where('id', '=', $user['student']->classroom_id)->first();
        $parent = Paarent::query()->where('user_id', '=', $user['student']->parent_id)->first();
        $user->class = $class;
        $user->parent = $parent;
        $user->classroom = $class_room;

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


        $user = User::query()->where('id', '=', $request->id)->with('student')->first();
        $class = Classes::query()->where('id', '=', $user['student']->class_id)->first();
        $class_room = Classroom::query()->where('id', '=', $user['student']->classroom_id)->first();
        $parent = Paarent::query()->where('user_id', '=', $user['student']->parent_id)->first();
        $user['student']->class = $class;
        $user['student']->parent = $parent;
        $user['student']->classroom = $class_room;
        $hold = $user;
        $user->delete();

        //delete parent if he has no more children in school
        $isParent = Student::query()->where('parent_id', '=', $parent->user_id)->first();
        if (!$isParent) user::where('id', '=', $parent->user_id)->delete();

        return response()->json([
            'message' => 'success',
            "data" => $hold,

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
        if ($students)
            for ($i = 0; $i < count($students); $i++) {
                $class = Classes::query()->where('id', '=', $students[$i]['student']->class_id)->first();
                $class_room = Classroom::query()->where('id', '=', $students[$i]['student']->classroom_id)->first();
                $parent = Paarent::query()->where('user_id', '=', $students[$i]['student']->parent_id)->first();
                $marks = Mark::query()->where('student_id', '=', $students[$i]->student->user_id)->get();
                $temp=null;
                for ($j = 0; $j < count($marks); $j++) {
                    $type = $marks[$j]->type_id;
                    $temp['type'."$type"]= $marks[$j]->value;
                }
                $students[$i]->student->marks=$temp;

                //$students[$i]->student->absents;
                $absents = Absent::query()->where('student_id', '=', $students[$i]->id)
                    ->where('is_justified', '=', false)->get();
                $absents_j = Absent::query()->where('student_id', '=', $students[$i]->id)
                    ->where('is_justified', '=', true)->get();
                $students[$i]->student->absents = $absents;
                $students[$i]->student->absents_j = $absents_j;
                $students[$i]->class = $class;
                $students[$i]->parent = $parent;
                $students[$i]->classroom = $class_room;
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

        $user = User::query()->where('id', '=', $request->id)->with('student')->first();
        $user->student->marks;
        //$user->student->absents;
        $absents = Absent::query()->where('student_id', '=', $user->id)
            ->where('is_justified', '=', false)->get();
        $absents_j = Absent::query()->where('student_id', '=', $user->id)
            ->where('is_justified', '=', true)->get();
        $user->student->absents = $absents;
        $user->student->absents_j = $absents_j;

        $class = Classes::query()->where('id', '=', $user['student']->class_id)->first();
        $class_room = Classroom::query()->where('id', '=', $user['student']->classroom_id)->first();
        $parent = Paarent::query()->where('user_id', '=', $user['student']->parent_id)->first();
        $user['student']->class = $class;
        $user['student']->parent = $parent;
        $user['student']->classroom = $class_room;
        return response()->json(
            $user
        );
    }
}
