<?php

namespace App\Http\Controllers\Classes;

use App\Http\Controllers\Controller;
use App\Models\Archive_Year;
use App\Models\Class_Subject;
use App\Models\Classes;
use App\Models\Paarent;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Parent_;

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
        if ($request->subjects_id)
            for ($i = 0; $i < count($request->subjects_id); $i++) {
                $class_subjects = new Class_Subject();
                $class_subjects->subject_id = $request->subjects_id[$i];
                $class_subjects->class_id = $class->id;
                $class_subjects->save();
            }

        $classWithSubjects = Classes::query()->where('id', '=', $class->id)->with('subjects')->first();


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

        for ($i = 0; $i < count($classes); $i++) {
            for ($j = 0; $j < count($classes[$i]->subjects); $j++) {
                $classes[$i]->subjects[$j]['class_id']=$classes[$i]->id;
                $classes[$i]->subjects[$j]['class_name']=$classes[$i]->name;
            }
        }

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
        if ($class)
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
        if ($class)
            $class->subjects;

        return response()->json([
            'message' => 'success',
            'data' => $class
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

    public function previousYearsStudents(Request $request)
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
        $active_year = Archive_Year::query()->select('year')->where('active', '=', 1)->first();
        $active_year = $active_year->year;

        $students = Student::query()->where('class_id', '=', $request->class_id)
            ->whereNotExists(function ($query) use ($active_year) {
                $query->
                where(function ($query1) use ($active_year) {
                    $query1->whereMonth('created_at', '>=', 9)->whereYear('created_at', '=', $active_year);
                })->
                orWhere(function ($query2) use ($active_year) {
                    $query2->whereMonth('created_at', '<', 9)->whereYear('created_at', '=', $active_year + 1);
                });
            })->with('user')->get();

        return response()->json([
            'data' => $students
        ]);
    }

    public function importStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'student_ids' => 'required' //array of

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        for ($i = 0; $i < count($request->student_ids); $i++) {
            $student = Student::query()->where('user_id', '=', $request->student_ids[$i])->with('user')->first();
            $parent = Paarent::query()->where('user_id','=',$student->parent->user_id)->with('user')->first();

            //user student
            $user = new User();
            $user->name = $student->user->name;
            $user->username = strtok($user->name, " ").'_'. strtolower(Str::random(3));
            $user->password = strtolower(Str::random(6));
            $user->phone_num = $student->user->phone_num;
            $user->address = $student->user->address;
            $user->role = $student->user->role;
            $user->save();
    //user parent
            //if this parent hasn't been imported before
            if(!$parent->imported) {
                $user2 = new User();
                $user2->name = $parent->user->name;
                $user2->username = strtok($user2->name, " ").'_'. strtolower(Str::random(3));
                $user2->password = strtolower(Str::random(6));
                $user2->phone_num = $parent->user->phone_num;
                $user2->address = $parent->user->address;
                $user2->role = $parent->user->role;
                $user2->save();

                $new_parent = new Paarent();
                $new_parent->user_id = $user2->id;
                $new_parent->save();
                $parent->new_id=$user2->id;
                $parent->imported=true;
                $parent->save();
            }

            $new_student = new Student();

            $new_student->user_id = $user->id;
            $new_student->classroom_id = $student->classroom_id;
            $new_student->parent_id = $parent->new_id;
            $new_student->class_id = $request->class_id;
            $new_student->bus_id = $request->bus_id;
            $new_student->save();
            $new_student->user;
            $students[] = $new_student;
        }

        return response()->json([
            'data' => $students
            //'paremt'=>$parent
        ]);
    }
}
