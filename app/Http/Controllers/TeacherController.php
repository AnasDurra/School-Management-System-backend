<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\teacher_subfiled;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required',
            'phone_num' => 'required',
            'address' => 'required',
            'subject_id' => 'required',

            'subfield_ids' => 'required', //this is a array
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
        $user->role = 2;
        $user->save();

        $teacher = new Teacher();
        $teacher->subject_id = $request->subject_id;
        $teacher->user_id = $user->id;

        $teacher->save();

        for ($i = 0; $i < count($request->subfield_ids); $i++) {
            $teacher_subfiled[$i] = new teacher_subfiled();
            $teacher_subfiled[$i]->teacher_id = $teacher->id;
            $teacher_subfiled[$i]->subfield_id = $request->subfield_ids[$i];
            $teacher_subfiled[$i]->save();
        }

        return response()->json([
            'message' => 'added',
            'user' => $user,
            'teacher' => $teacher,
            'subfields'=>$teacher_subfiled
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'username' => 'required',
            'password' => 'required',
            'phone_num' => 'required',
            'address' => 'required',
            'subject_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $teacher = Teacher::query()->where('id', '=', $request->id)->first();

        if (!$teacher) {
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        }

        $user = User::query()->where('id', '=', $teacher->user_id)->first();
        $user['username'] = $request->username;
        $user['password'] = $request->password;
        $user['phone_num'] = $request->phone_num;
        $user['address'] = $request->address;

        $teacher['subject_id'] = $request->subject_id;

        $teacher->save();
        $user->save();

        return response()->json([
            'message' => 'updated',
            'user' => $user,
            'teacher' => $teacher
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

        $teacher = Teacher::query()->where('id', '=', $request->id)->first();
        $user = User::query()->where('id', '=', $teacher->user_id)->first();
        $user->delete();

        return response()->json([
            'message' => 'deleted'
        ]);
    }

    public function all(Request $request)
    {
        $teacher = Teacher::query()->with('subject')->with('subfields')->get();
        if (!$teacher) {
            return response()->json([
                'error' => 'no teachers yet'
            ], 404);
        }

        for ($i = 0; $i < count($teacher); $i++) {
            $users[] = ['Teacher' => $teacher[$i], 'User' => User::query()->where('id', '=', $teacher[$i]->user_id)->first()];
        }
        return response()->json(
            $users
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
        $teacher = Teacher::query()->where('id', '=', $request->id)->first();
        if (!$teacher) {
            return response()->json([
                'error' => 'NotFound'
            ], 404);
        }
        $user = ['Teacher' => $teacher, 'User' => User::query()->where('id', '=', $teacher->user_id)->first()];
        return response()->json(
            $user
        );
    }

    public function teacher_subject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $teacher = Teacher::query()->where('id', '=', $request->teacher_id)->first();
        if (!$teacher) {
            return response()->json([
                'error' => 'NotFound'
            ], 404);
        }

        return response()->json([
            'data' => $teacher->subject
        ]);
    }


    //subfields

////    public function addSubfields(Request $request)
////    {
////        $validator = Validator::make($request->all(), [
////            'teacher_id' => 'required',
////            'subfield_ids' => 'required', //this is a array
////        ]);
////        if ($validator->fails()) {
////            $errors = $validator->errors();
////            return response()->json([
////                'error' => $errors
////            ], 400);
////        }
////
////        for ($i = 0; $i < count($request->subfield_ids); $i++) {
////            $teacher_subfiled[$i] = new teacher_subfiled();
////            $teacher_subfiled[$i]->teacher_id = $request->teacher_id;
////            $teacher_subfiled[$i]->subfield_id = $request->subfield_ids[$i];
////            $teacher_subfiled[$i]->save();
////        }
//
//        return response()->json([
//            'message' => 'added',
//            'data' => $teacher_subfiled
//        ]);
//    }

    //delete subfield from a teacher
    public function deleteSubfield(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required',
            'subfield_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $teacher_subfield = teacher_subfiled::query()->where('teacher_id','=',$request->teacher_id)->
            where('subfield_id','=',$request->subfield_id)->delete();
        if (!$teacher_subfield) {
            return response()->json([
                'error' => 'NotFound'
            ], 404);
        }

        return response()->json([
            'message' => 'deleted'
        ]);
    }

    //getSubfields
    public function getSubfields(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required',]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $teacher =Teacher::query()->where('id','=',$request->teacher_id)->first();
        if(!$teacher){
            return response()->json([
                'error' => 'NotFound'
            ], 404);
        }

        return response()->json([
            'data' => $teacher->subfields
        ]);
    }
}
