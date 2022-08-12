<?php

namespace App\Http\Controllers;

use App\Models\Archive_Year;
use App\Models\Mark;
use App\Models\Obligate;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\at;

class MarkController extends Controller
{
    public function object(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'subject_id' => 'required',
            'type_id' => 'required',
            'body'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $student = Student::query()->where('user_id', '=', $request->student_id)->first();
        $subject = Subject::query()->where('id', '=', $request->subject_id)->first();
        $mark = Mark::query()->where('type_id', '=', $request->type_id)->where('subject_id', '=', $request->subject_id)->
        where('student_id', '=', $request->student_id)->first();
        if (!$mark || !$student || !$subject)
            return response()->json([
                'message' => 'wrong inputs'
            ], 404);
        $obligation = new Obligate();
        $obligation->mark_id = $mark->id;
        $obligation->body = $request->body;
        $obligation->save();

         return response()->json([
        'message' => 'ok'
    ]);


    }
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'subject_id' => 'required',
            'type_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $student = Student::query()->where('user_id', '=', $request->student_id)->first();
        $subject = Subject::query()->where('id', '=', $request->subject_id)->first();
        $mark = Mark::query()->where('type_id', '=', $request->type_id)->where('subject_id', '=', $request->subject_id)->
        where('student_id', '=', $request->student_id)->first();
        if (!$mark || !$student || !$subject)
            return response()->json([
                'result' => 0
            ], 404);
        else  return response()->json([
                'result' => 1
            ], 200);
    }

    public function setMarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'info' => 'required', //array , for each one => { student_id , subject_id ,type_id, value }

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        //checking the students ids
        for ($i = 0; $i < count($request->info); $i++) {
            $student = Student::query()->where('user_id', '=', $request->info[$i]['student_id'])->first();
            if (!$student) {
                return response()->json(['message' => 'wrong student id !', 'student_id' => $request->info[$i]['student_id']], 404);
            }
        }

        //checking the subjects ids
        for ($i = 0; $i < count($request->info); $i++) {
            $subject = Subject::query()->where('id', '=', $request->info[$i]['subject_id'])->first();
            if (!$subject) {
                return response()->json(['message' => 'wrong subject id !', 'subject_id' => $request->info[$i]['subject_id']], 404);
            }
        }

        //types does not need to check

        if ($request->info) {
            for ($i = 0; $i < count($request->info); $i++) {
                $mark[$i] = new Mark();
                $subject = Subject::query()->where('id', '=', $request->info[$i]['subject_id'])->first();

                $student = Student::query()->where('user_id', '=', $request->info[$i]['student_id'])->first();

                $mark[$i]['student_id'] = $request->info[$i]['student_id'];
                $mark[$i]['subject_id'] = $request->info[$i]['subject_id'];
                $mark[$i]['type_id'] = $request->info[$i]['type_id'];
                $mark[$i]['value'] = $request->info[$i]['value'];

                $mark[$i]->save();
            }
        }


        return response()->json([
            'message' => 'success'
            // , 'data' => $mark  no need to return anything for now
        ]);
    }


    //get student marks
    public function getStudentMarks(Request $request)
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

//        $student = Student::query()->where('user_id', '=', $request->student_id)->with('marks')->first();
//        if (!$student) return response()->json(['message' => 'Student Not Found']);
//
//        $student->user;
//        if ($student->marks) {
//            for ($i = 0; $i < count($student->marks); $i++) {
//                $mark = $student->marks[$i];
//                $subject = Subject::query()->where('id', '=', $mark->subject_id)->first();
//                $student->marks[$i]->subject_name = $subject->name;
//            }
//        }
//        return response()->json(['data' => $student]);
        $data = null;
        $student = Student::query()->where('user_id', '=', $request->student_id)->first();
        $subjects = $student->classroom->class->subjects;
        for ($i = 0; $i < count($subjects); $i++) {
            $marks = Mark::query()->where([
                ['student_id', '=', $request->student_id],
                ['subject_id', '=', $subjects[$i]->id]
            ])->get();
            $type1 = false;
            $type2 = false;
            $type3 = false;
            $type4 = false;
            $count = 0;
            $sum = 0;
            $data[$i]['subject_name'] = $subjects[$i]->name;
            for ($j = 0; $j < count($marks); $j++) {
                if ($marks[$j]->type_id == 1) {
                    $type1 = true;
                    $data[$i]['1'] = $marks[$j]->value;
                    $count++;
                    $sum += $marks[$j]->value;
                } else if ($marks[$j]->type_id == 2) {
                    $type2 = true;
                    $data[$i]['2'] = $marks[$j]->value;
                    $count++;
                    $sum += $marks[$j]->value;
                } else if ($marks[$j]->type_id == 3) {
                    $type3 = true;
                    $data[$i]['3'] = $marks[$j]->value;
                    $count++;
                    $sum += $marks[$j]->value;
                } else if ($marks[$j]->type_id == 4) {
                    $type4 = true;
                    $data[$i]['4'] = $marks[$j]->value;
                    $count++;
                    $sum += $marks[$j]->value;
                }
            }
            if (!$type1) $data[$i]['1'] = -1;
            if (!$type2) $data[$i]['2'] = -1;
            if (!$type3) $data[$i]['3'] = -1;
            if (!$type4) $data[$i]['4'] = -1;
            if ($count == 0) $data[$i]['avg'] = -1;
            else $data[$i]['avg'] = ($sum / $count);
        }
        return response()->json(
            $data
        );
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'subject_id' => 'required',
            'type_id' => 'required',
            'value',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $mark = Mark::query()->where([
            ['student_id', '=', $request->student_id],
            ['subject_id', '=', $request->subject_id],
            ['type_id', '=', $request->type_id]
        ])->first();
        if (!$mark) {
            $mark = new Mark();
            $mark->student_id = $request->student_id;
            $mark->subject_id = $request->subject_id;
            $mark->type_id = $request->type_id;
        }
        if ($request->value)
            $mark->value = $request->value;
        else   $mark->value = 0;

        $mark->save();
//        $subject = Subject::query()->where('id', '=', $mark->subject_id)->first();
//        $mark->subject = $subject;
        return response()->json([
            'message' => 'success'
            //    , 'data' => $mark currently no data needed
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
        $mark = Mark::query()->where('id', '=', $request->id)->first();
        $subject = Subject::query()->where('id', '=', $mark->subject_id)->first();
        $mark->delete();
        $mark->subject = $subject;
        if (!$mark) {
            return response()->json([
                'message' => 'NotFound'
            ]);
        }

        return response()->json([
            'message' => 'success',
            'data' => $mark
        ]);
    }

    public function getTypes(Request $request)
    {
        $types = Type::query()->get();
        return response()->json([
            'data' => $types
        ]);
    }

    public function getClassroomSubjectMarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required',
            'subject_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        //TODO check if subject belong to classroom
        $data = null;
        $students = Student::query()->where('classroom_id', '=', $request->classroom_id)->get();
        for ($i = 0; $i < count($students); $i++) {
            $marks = Mark::query()->where('student_id', '=', $students[$i]->user_id)
                ->where('subject_id','=',$request->subject_id)->get();
            $type1 = false;
            $type2 = false;
            $type3 = false;
            $type4 = false;
            $count = 0;
            $sum = 0;
            $data[$i]['student_name'] = $students[$i]->user->name;
            for ($j = 0; $j < count($marks); $j++) {
                if ($marks[$j]->type_id == 1) {
                    $type1 = true;
                    $data[$i]['1'] = $marks[$j]->value;
                    $count++;
                    $sum += $marks[$j]->value;
                } else if ($marks[$j]->type_id == 2) {
                    $type2 = true;
                    $data[$i]['2'] = $marks[$j]->value;
                    $count++;
                    $sum += $marks[$j]->value;
                } else if ($marks[$j]->type_id == 3) {
                    $type3 = true;
                    $data[$i]['3'] = $marks[$j]->value;
                    $count++;
                    $sum += $marks[$j]->value;
                } else if ($marks[$j]->type_id == 4) {
                    $type4 = true;
                    $data[$i]['4'] = $marks[$j]->value;
                    $count++;
                    $sum += $marks[$j]->value;
                }
            }
            if (!$type1) $data[$i]['1'] = 0;
            if (!$type2) $data[$i]['2'] = 0;
            if (!$type3) $data[$i]['3'] = 0;
            if (!$type4) $data[$i]['4'] = 0;
            if ($count) $data[$i]['avg'] = number_format((float)($sum / $count), 2, '.', '') ;
            else $data[$i]['avg'] = 0;

        }
        return response()->json([
            'data' => $data
        ]);

    }


}
