<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\teacher_subfiled;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_num' => 'required',
            'address' => 'required',
            'subject_id',
            'subfield_ids', //this is a array
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
        $user->role = 2;
        $user->save();

        $teacher = new Teacher();
        if ($request->subject_id)
            $teacher->subject_id = $request->subject_id;
        $teacher->user_id = $user->id;
        $teacher->save();
        if (!$request->subject_id)
            for ($i = 0; $i < count($request->subfield_ids); $i++) {
                $teacher_subfield = new teacher_subfiled();
                $teacher_subfield->teacher_id = $user->id;
                $teacher_subfield->subfield_id = $request->subfield_ids[$i];
                $teacher_subfield->save();
            }
        if ($request->subject_id) {
            $subject = Subject::query()->where('id', '=', $request->subject_id)->first();
            for ($i = 0; $i < count($subject->subfields); $i++) {
                $teacher_subfield = new teacher_subfiled();
                $teacher_subfield->teacher_id = $user->id;
                $teacher_subfield->subfield_id = $subject->subfields[$i]->id;
                $teacher_subfield->save();
            }
        }
        $user = User::query()->where('id', '=', $user->id)->with('teacher')->first();

        //access subfields so they became visible in user'
        $user['teacher']->subfields;
        return response()->json([
            'message' => 'added',
            'user' => $user,

        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'username',
            'password',
            'phone_num',
            'address',
            'subject_id',
            'subfields_id' //array
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        // $teacher = Teacher::query()->where('id', '=', $request->id)->first();

//        if (!$teacher) {
//            return response()->json([
//                'error' => 'Not Found'
//            ], 404);
//        }
        $user = User::query()->where('id', '=', $request->id)->with('teacher')->first();

        //  $user = User::query()->where('id', '=', $teacher->user_id)->first();
        if ($request->username)
            $user['username'] = $request->username;
        if ($request->password)
            $user['password'] = $request->password;
        if ($request->phone_num)
            $user['phone_num'] = $request->phone_num;
        if ($request->address)
            $user['address'] = $request->address;

        if ($request->subject_id) {
            $user['teacher']['subject_id'] = $request->subject_id;

            //delete all existing teacher subfields
            $teacher_subfields = teacher_subfiled::query()->where('teacher_id', '=', $user->id)->get();
            if ($teacher_subfields)
                for ($i = 0; $i < count($teacher_subfields); $i++) $teacher_subfields[$i]->delete();

            //add all subject subfields to the teacher
            $subject = Subject::query()->where('id', '=', $request->subject_id)->first();
            for ($i = 0; $i < count($subject->subfields); $i++) {
                $teacher_subfield = new teacher_subfiled();
                $teacher_subfield->teacher_id = $user->id;
                $teacher_subfield->subfield_id = $subject->subfields[$i]->id;
                $teacher_subfield->save();
            }
        } else if ($request->subfields_id) {
            //no subject but there are subfields


            //delete all existing teacher subfields
            $teacher_subfields = teacher_subfiled::query()->where('teacher_id', '=', $user->id)->get();
            if ($teacher_subfields)
                for ($i = 0; $i < count($teacher_subfields); $i++) $teacher_subfields[$i]->delete();
            //assign new subfields from request
            for ($i = 0; $i < count($request->subfields_id); $i++) {
                $teacher_subfield = new teacher_subfiled();
                $teacher_subfield->teacher_id = $user->id;
                $teacher_subfield->subfield_id = $request->subfields_id[$i];
                $teacher_subfield->save();
            }
        }

        $user->save();
        //access subfields so the became visible in the returned JSON
        $user->teacher->subfields;
        return response()->json([
            'message' => 'success',
            'data' => $user,
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
//        $teacher = Teacher::query()->with('subject')->with('subfields')->get();
//        if (!$teacher) {
//            return response()->json([
//                'error' => 'no teachers yet'
//            ], 404);
//        }
        $teachers = User::query()->where('role', '=', 2)->with('teacher')->get();

//        for ($i = 0; $i < count($teacher); $i++) {
//            $users[] = ['Teacher' => $teacher[$i], 'User' => User::query()->where('id', '=', $teacher[$i]->user_id)->first()];
//        }
        if ($teachers)
            for ($i = 0; $i < count($teachers); $i++) {
                //accessing subfields so they became visibile in the returned JSON
                $teachers[$i]['teacher']->subfields;
            }

        return response()->json(
            $teachers
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

        $teacher_subfield = teacher_subfiled::query()->where('teacher_id', '=', $request->teacher_id)->
        where('subfield_id', '=', $request->subfield_id)->delete();
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
        $teacher = Teacher::query()->where('id', '=', $request->teacher_id)->first();
        if (!$teacher) {
            return response()->json([
                'error' => 'NotFound'
            ], 404);
        }

        return response()->json([
            'data' => $teacher->subfields
        ]);
    }
}
