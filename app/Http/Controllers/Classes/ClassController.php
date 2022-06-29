<?php

namespace App\Http\Controllers\Classes;

use App\Http\Controllers\Controller;
use App\Models\Archive_Year;
use App\Models\Class_Subject;
use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'subjects_id'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $class = new Classes();
        $class->name = $request->name;
        $class->save();
        if($request->subjects_id)
        for ($i = 0; $i < count($request->subjects_id); $i++) {
            $class_subjects = new Class_Subject();
            $class_subjects->subject_id = $request->subjects_id[$i];
            $class_subjects->class_id = $class->id;
            $class_subjects->save();
        }

        $classWithSubjects = Classes::query()->where('id', '=', $class->id)->with('subjects')->first();

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
            'data' => $classWithSubjects
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $class = Classes::query()->where('id', '=', $request->id)->first();
        if (!$class) {
            return response()->json(['message' => 'NotFound']);
        }

        $class->name = $request->name;
        $class->save();

        return response()->json([
            'message' => 'updated',
            'data' => $class
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

        $class = Classes::query()->where('id', '=', $request->id)->first();
        if (!$class) {
            return response()->json(['message' => 'NotFound']);
        }

        $class->delete();

        return response()->json([
            'message' => 'deleted'
        ]);
    }

    public function all(Request $request)
    {
        $classes = Classes::query()->filterYear('created_at')->with('subjects')->get();

        if (!$classes) {
            return response()->json([
                'message' => 'No classes'
            ]);
        }

        return response()->json([
            'data' => $classes
        ]);

    }

    public function show_classrooms(Request $request)
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
        $class = Classes::query()->where('id', '=', $request->id)->first();
        if (!$class) {
            return response()->json(['message' => 'NotFound']);
        }

        return response()->json([
            'data' => $class->classrooms
        ]);
    }

    ##add subjects to a class
    public function addSubjectsToClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_ids' => 'required',  //array of ids of subjects
            'class_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        for ($i = 0; $i < count($request->subject_ids); $i++) {
            $class_subject = new Class_Subject();
            $class_subject->subject_id = $request->subject_ids[$i];
            $class_subject->class_id = $request->class_id;
            $class_subject->save();

        }
        $class = Classes::query()->where('id', '=', $request->class_id)->first();
        if($class)
        $class->subjects;

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
            'message' => 'success',
            'data' => $class

        ]);
    }

    public function deleteSubjectsFromClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_ids' => 'required',//array
            'class_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        for ($i = 0; $i < count($request->subject_ids); $i++)
            Class_Subject::query()->where('subject_id', '=', $request->subject_ids[$i])->
            where('class_id', '=', $request->class_id)->delete();

        $class = Classes::query()->where('id', '=', $request->class_id)->first();
        if($class)
        $class->subjects;

        return response()->json([
            'message' => 'success',
            'data'=>$class
        ]);
    }

    //aaa
    public function get_subjects_of_class(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $class = Classes::query()->where('id', '=', $request->class_id)->first();
        if (!$class) {
            return response()->json(['message' => 'Not Found']);
        }

        $class_subjects = $class->subjects;

        for ($i = 0; $i < count($class_subjects); $i++) {
            $class_subjects[$i] = Subject::query()->where('id', '=', $class_subjects[$i]->id)->first();
        }
        return response()->json([
            'data' => $class->subjects
        ]);
    }
}
