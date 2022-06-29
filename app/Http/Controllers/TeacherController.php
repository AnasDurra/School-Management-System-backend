<?php

namespace App\Http\Controllers;

use App\Models\Archive_Year;
use App\Models\Subject;
use App\Models\Teacher;

use App\Models\teacher_subject;
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
            'subjects_id',//array
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
        $teacher->user_id = $user->id;
        $teacher->save();
        if ($request->subjects_id)
            for ($i = 0; $i < count($request->subjects_id); $i++) {
                $teacher_subject = new teacher_subject();
                $teacher_subject->teacher_id = $user->id;
                $teacher_subject->subject_id = $request->subjects_id[$i];
                $teacher_subject->save();
            }


        $user = User::query()->where('id', '=', $user->id)->with('teacher')->first();

        //access subjects so they became visible in user'
        $user['teacher']->subjects;

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
            'subjects_id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $user = User::query()->where('id', '=', $request->id)->with('teacher')->first();

        if ($request->username)
            $user['username'] = $request->username;
        if ($request->password)
            $user['password'] = $request->password;
        if ($request->phone_num)
            $user['phone_num'] = $request->phone_num;
        if ($request->address)
            $user['address'] = $request->address;

        if ($request->subjects_id) {
            //checking if subjects id are legit
            for ($i = 0; $i < count($request->subjects_id); $i++) {
                $subject = Subject::query()->where('id', '=', $request->subjects_id[$i])->first();
                if (!$subject) {
                    return response()->json([
                        'id ' . $request->subjects_id[$i] . ' is not an id for existing subject'
                    ], 404);
                }
            }
            //delete all existing subjects for this teacher
            teacher_subject::query()->where('teacher_id', '=', $user->id)->delete();
            for ($i = 0; $i < count($request->subjects_id); $i++) {
                $teacher_subject = new teacher_subject();
                $teacher_subject->subject_id = $request->subjects_id[$i];
                $teacher_subject->teacher_id = $request->id;
                $teacher_subject->save();
            }
        }

        $user->save();
        $user = User::query()->where('id', '=', $request->id)->with('teacher')->first();
        $user['teacher']->subjects;
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

        $user = User::query()->where('id', '=', $request->id)->with('teacher')->first();
        if($user->role !=2){
            return response()->json([
                'message' => 'invalid id'
            ], 404);
        }
        if ($user) {
            $clone = $user;
            if ($clone['teacher'])
                $clone['teacher']->subjects;
            $user->delete();
        } else {
            return response()->json([
                'message' => 'invalid id'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data'=>$clone
        ]);
    }

    public function all(Request $request)
    {
        $teachers = User::query()->where('role', '=', 2)->filterYear('created_at')->with('teacher')->get();
        if ($teachers)
            for ($i = 0; $i < count($teachers); $i++) {
                //accessing subjects so they became visibile in the returned JSON
                $teachers[$i]['teacher']->subjects;
                $teachers[$i]['teacher']->classrooms;
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
        $user = User::query()->where('id', '=', $request->id)->filterYear('created_at')->with('teacher')->first();
        if (!$user) {
            return response()->json([
                'error' => 'NotFound'
            ], 404);
        }

        return response()->json(
            $user
        );
    }

    public function teacherSubjects(Request $request)
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

        $teacher = Teacher::query()->where('user_id', '=', $request->teacher_id)->first();
        if (!$teacher) {
            return response()->json([
                'error' => 'NotFound'
            ], 404);
        }
        if ($teacher)
            $teacher_subjects = $teacher->subjects;
        return response()->json([
            'data' => $teacher_subjects
        ]);
    }
}
