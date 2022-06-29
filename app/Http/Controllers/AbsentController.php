<?php

namespace App\Http\Controllers;

use App\Models\Absent;
use App\Models\Archive_Year;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsentController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'students_id' => 'required' // array of { student_id , date , is_justified (not required ) }
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        //checking the students ids
        for ($i = 0; $i < count($request->students_id); $i++) {
            $student = Student::query()->where('user_id', '=', $request->students_id[$i]['student_id'])->first();
            if (!$student) {
                return response()->json(['message' => 'wrong student id !', 'student_id' => $request->students_id[$i]['student_id']], 404);
            }
        }

        for ($i = 0; $i < count($request->students_id); $i++) {
            $absent[$i] = new Absent();
            $absent[$i]->student_id = $request->students_id[$i]['student_id'];
            $absent[$i]->date = $request->students_id[$i]['date'];

            if($request->students_id[$i]['is_justified'])
                $absent[$i]->is_justified = $request->students_id[$i]['is_justified'];

            $absent[$i]->save();
        }

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
            'data' => $absent
        ]);
    }

    public function getStudentAbsents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $student = Student::query()->where('user_id', '=', $request->student_id)->first();

        if (!$student) return response()->json(['message' => 'Student Not Found']);
        $absents=Absent::query()->where('student_id','=',$student->user_id)
            ->where('is_justified','=',false)->get();
        $absents_j=Absent::query()->where('student_id','=',$student->user_id)
            ->where('is_justified','=',true)->get();
        $student->absents = $absents;
        $student->absents_j = $absents_j;

        $student->user;
        //$student->absents;
        return response()->json(['data'=>$student]);
    }

    public function deleteAbsent(Request $request)
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

        $absent = Absent::query()->where('id','=',$request->id)->first();
        if(!$absent)
            return response()->json([
                'message' => 'Absent Not Found'
            ], 404);

        $absent->delete();
        return response()->json([
            'message' => 'success',
            'data' => $absent
        ]);
    }

    public function justifiedAbsent(Request $request)
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

        $absent = Absent::query()->where('id','=',$request->id)->first();
        if(!$absent)
            return response()->json([
                'message' => 'Absent Not Found'
            ], 404);

        $absent->is_justified=true;
        $absent->save();
        return response()->json([
            'message' => 'success',
            'data' => $absent
        ]);
    }


}
