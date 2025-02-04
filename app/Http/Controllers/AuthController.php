<?php

namespace App\Http\Controllers;

use App\Models\Paarent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required',
            'phone_num' => 'required',
            'address' => 'required',
            'role'=>'required',
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
            $user=new User();
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->phone_num = $request->phone_num;
            $user->address = $request->address;
            $user->role=$request->role;



            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'data'=>$user,
                'token_type' => 'Bearer',
            ]);
        }
    }
    public function login(Request $request)
    {
        //validate
        $request->validate([
            "username"=>"required",
            "password"=>"required"
        ]);

        // compare between the two emails
        $user= User::query()->where("username","=",$request->username)->first();
        if($request->username && $user){
            if($request->password==$user->password)
            {
                $token=$user->createToken("auth_Token")->plainTextToken ;
                if($user->role==3)
                    $user->parent->students;
                if($user->role==1||$user->role==0)
                     $user->admin->responsibilities;
                return response()->json([
                    'access_token' => $token,
                    'data'=>$user,
                    'token_type' => 'Bearer',
                ]);
            } else{
                return response()->json(['state'=>false,'message'=>'wrong password'],414 );
            }
        }
        else{
            return response()->json(['state'=>false,'message'=>'wrong username'],451);
        }
    }

    public function updateAuth(Request $request){
        $validator = Validator::make($request->all(), [
            "id"=>"required",
            "username",
            "password"=>'required',
            'new_password'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $user = User::query()->find($request->id);
        if(!$user)
            return response()->json([
                'message'=>'Not Found'
            ],404);


           if( !$user->password = $request->password)
               return response()->json([
                   'message'=>'Bad Entries'
               ],404);

        if($request->username)
           $user->username=$request->username;
        if($request->new_password)
            $user->password=$request->new_password;

        $user->save();

        return response()->json([
            'message'=>'updated',
            'data'=>$user,
        ]);

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'logout'
        ],200 );
    }
//return currently auth user
    public function me(Request $request)
    {
        return $request->user();
    }
//override email field in Auth::attemp with username
    public function username()
    {
        return 'username';
    }
}
