<?php

namespace App\Http\Controllers;

use App\Models\admin;
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


        $parent = new Paarent();
        $parent->user_id = $user->id;
        $parent->save();


        return response()->json([
            'message' => 'added'
        ]);

    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'username' => 'required|string|max:255',
            'password', //Passowrd is not required for update
            'phone_num' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $parent = Paarent::query()->where('id', '=', $request->id)->firstOrFail();
        $user = User::query()->where('id', '=', $parent->user_id)->first();
        $user['username'] = $request->username;
        $user['password'] = $request->password;
        $user['phone_num'] = $request->phone_num;
        $user['address'] = $request->address;

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
        $parent = Paarent::query()->where('id', '=', $request->id)->firstOrFail();
        $user = User::query()->where('id', '=', $parent->user_id)->firstOrFail();
        $user->delete();
        return response()->json([
            'message' => 'deleted'
        ]);
    }

    public function all()
    {
        $parent = Paarent::all();
        $parent->toArray();
        for($i=0;$i<count($parent);$i++){

            $user= ['PARENT_ID'=>$parent[$i]->id,User::query()->where('id','=',$parent[$i]->user_id)->firstOrFail() ];
        }
        return response()->json(
            $user
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

        $parent = Paarent::query()->where('id','=',$request->id)->firstOrFail();
        if(!$parent) {
            return response()->json([
                'message' => 'Not found'
            ]);
        }
        $user = User::query()->where('id','=',$parent->user_id)->firstOrFail();
        $user['parent_id']=$parent->id;
            return response()->json([
                $user
            ]);

    }
}
