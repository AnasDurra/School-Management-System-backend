<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Database\Seeders\adminSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
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
        $user->username = strtolower(Str::random(10));
        $user->password = strtolower(Str::random(6));
        $user->phone_num = $request->phone_num;
        $user->address = $request->address;
        $user->role = 1;
        $user->save();
        $admin = new Admin();
        $admin->user_id = $user->id;
        $admin->save();
        $user = User::query()->where('id','=',$admin->user_id)->with('admin')->first();
        return response()->json([
            'message' => 'added',
            'data'=>$user
        ]);

    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'phone_num',
            'address',
            'username',
            'password'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $user=User::query()->where('id','=',$request->id)->first();
        if(!$user){return response()->json(['message'=>'NotFound']);}

       $test = User::query()->where('username','=',$request->username)->first();
        if($test){
           return response
        }
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
