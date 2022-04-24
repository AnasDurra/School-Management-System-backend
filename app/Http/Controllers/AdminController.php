<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function add(Request $request){
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
        $user->role = 1;
        $user->save();


        //##
        //this not working !!
//        $user =User::query()->create([
//            'phone_num'=>$request->phone_num,
//            'username'=>$request->username,
//            'password'=>$request->password,
//            'phone_num'=>$request->phone_num,
//            'address'=>$request->address,
//            'role' => 1
//        ]);
        //##


        Admin::query()->create([
            'user_id'=>$user->id
        ]);

        return response()->json([
            'message' => 'added',
            'data'=>$user
        ]);

    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'username' => 'required|string|max:255',
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

        $user=Admin::query()->where('id','=',$request->id)->first();
        if(!$user){return response()->json(['message'=>'NotFound']);}

        $user=User::query()->where('id','=',$user->user_id)->first();
        $user->username = $request->username;
        $user->password = $request->password;
        $user->phone_num = $request->phone_num;
        $user->address = $request->address;
        $user->save();

        return response()->json([
            'message' => 'updated',
            'data'=>$user
        ]);
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required'
        ]);

        $admin = Admin::query()->where('id','=',$request->id)->first();
        if(!$admin){return response()->json(['message'=>'NotFound']);}
        $user = User::query()->where('id','=',$admin->user_id)->delete();

        return response()->json([
            'message' => 'deleted'
        ]);

    }

    }
