<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Admin_responsibility;
use App\Models\Responsibility;
use App\Models\Archive_Year;



use App\Models\User;
use Database\Seeders\adminSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function Symfony\Component\String\u;

class AdminController extends Controller
{
    public function all(Request $request)
    {
        $admins = User::query()->where(
            'role', '<=', 1)->filterYear('created_at')->with('admin')->get();

        foreach ($admins as $admin){
            $admin->admin->responsibilities;
        }
        return response()->json($admins);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_num' => 'required',
            'address' => 'required',
            'responsibilities' // array
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
        $user->address = $request->address;
        $user->role = 1;
        $user->save();
        $admin = new Admin();
        $admin->user_id = $user->id;
        $admin->save();

        if($request->responsibilities)
            for($i=0 ; $i < count($request->responsibilities) ; $i++){
            $admin_responsibility= new Admin_responsibility();
            $admin_responsibility->admin_id= $user->id;
            $admin_responsibility->responsibility_id = $request->responsibilities[$i];
            $admin_responsibility->save();
            }
        $user = User::query()->where('id', '=', $admin->user_id)->with('admin')->first();




        //archive related

        $archiveYears = Archive_Year::query()->get();
        $arr = [];
        for ($i = 0; $i < count($archiveYears); $i++) $arr[] = $archiveYears[$i]->year;
        if (!in_array(now()->month < 9 ? now()->year - 1 : now()->year, $arr)) {
            $archiveYear = new Archive_Year();
            if (now()->month < 9)
                $archiveYear->year = now()->year - 1;
            else         $archiveYear->year = now()->year;
            $archiveYear->save();
        }

        $user->admin->responsibilities;

        return response()->json([
            'message' => 'added',
            'data' => $user
        ]);

    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'phone_num',
            'address',
            'username',
            'password',
            'name',
            'responsibilities' //array
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $user = User::query()->where('id', '=', $request->id)->with('admin')->first();
        if (!$user || ($user->role != 1 && $user->id !=1 )) {
            return response()->json(['message' => 'NotFound']);
        }

        $test = User::query()->where('username', '=', $request->username)->first();
        if ($test) {
            return response()->json(['username is already taken !'], 404);
        }
        if ($request->name)
            $user->name = $request->name;
        if ($request->username)
            $user->username = $request->username;
        if ($request->password)
            $user->password = $request->password;
        if ($request->phone_num)
            $user->phone_num = $request->phone_num;
        if ($request->address)
            $user->address = $request->address;

        $user->save();
        Admin_responsibility::query()->where('admin_id','=',$request->id)->delete();
        if($request->responsibilities)
            for($i=0 ; $i<count($request->responsibilities) ; $i++){
                $admin_responsibility = new Admin_responsibility();
                $admin_responsibility->admin_id = $request->id;
                $admin_responsibility->responsibility_id = $request->responsibilities[$i];
                $admin_responsibility->save();
            }

        $user->admin->responsibilities;
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

        $admin = User::query()->where('id', '=', $request->id)->with('admin')->first();
        if (!$admin || ($admin->role != 1 && $admin->id !=1 )) {
            return response()->json(['message' => 'NotFound']);
        }
        $admin->delete();

        return response()->json([
            'message' => 'success',
            'data' => $admin
        ]);

    }

}
