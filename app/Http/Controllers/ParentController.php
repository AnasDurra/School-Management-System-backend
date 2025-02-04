<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Models\Archive_Year;
use App\Models\Classes;
use App\Models\Classroom;
use App\Models\Paarent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Psy\TabCompletion\Matcher\AbstractDefaultParametersMatcher;

class ParentController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required',
            'phone_num' => 'required',
            'address' => 'required'
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
        $user->role = 3;

        $user->save();


        $paarent = new Paarent();
        $paarent->user_id = $user->id;
        $paarent->save();
        
        return response()->json([
            'message' => 'added',
        ]);

    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'username',
            'password', //Passowrd is not required for update
            'phone_num',
            'address',
            'name'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }


        $user = User::query()->where('id', '=', $request->id)->with('parent')->first();
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
        $user->save();

        $user = User::query()->where('id', '=', $request->id)->with('parent')->first();
        $user['parent']->students;
        for ($j = 0; $j < count($user['parent']->students); $j++) {
            $user['parent']->students[$j]->classroom;
            $class = Classes::query()->where('id', '=', $user['parent']->students[$j]->class_id)->first();
            $user['parent']->students[$j]->class = $class;
        }
        return response()->json([
            'message' => 'success',
            "data" => $user
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
        $parent = Paarent::query()->where('id', '=', $request->id)->firstOrFail();
        $user = User::query()->where('id', '=', $parent->user_id)->firstOrFail();
        $user->delete();
        return response()->json([
            'message' => 'deleted'
        ]);
    }

    public function all()
    {
        $parents = User::query()->where('role', '=', '3')->filterYear('created_at')->with('parent')->get();
        if ($parents)
            for ($i = 0; $i < count($parents); $i++) {
                if ($parents[$i]['parent'])
                    if ($parents[$i]['parent']['students'])
                        for ($j = 0; $j < count($parents[$i]['parent']['students']); $j++) {
                           $classroom =  Classroom::query()->where('id','=',$parents[$i]['parent']->students[$j]->classroom_id)->first();
                            $class = Classes::query()->where('id', '=', $parents[$i]['parent']['students'][$j]->class_id)->first();
                            $parents[$i]['parent']['students'][$j]->class = $class;
                            $parents[$i]['parent']['students'][$j]->classroom = $classroom;
                        }
            }
        return response()->json(
            $parents
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

        $user = User::query()->where('id', '=', $request->id)->filterYear('created_at')->with('parent')->first();
        if ($user) {
            if ($user['parent'])
                for ($i = 0; $i < count($user['parent']['students']); $i++) {
                   // $user['parent']['students'][$i]->class;
                    $class = Classes::query()->where('id', '=', $user['parent']['students'][$i]->class_id)->first();
                    $class_room = Classroom::query()->where('id', '=', $user['parent']['students'][$i]->classroom_id)->first();
                    $student_user = User::query()->where('id', '=', $user['parent']['students'][$i]->user_id)->first();
                    $user['parent']['students'][$i]=$student_user;
                    $user['parent']['students'][$i]->class = $class;
                    $user['parent']['students'][$i]->classroom = $class_room;
                }
        }
        return response()->json([
            $user
        ]);

    }
}
