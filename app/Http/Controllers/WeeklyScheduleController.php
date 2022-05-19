<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Week_day;
use App\Models\Week_day_subject;
use App\Models\Weekly_schedule;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class weeklyScheduleController extends Controller
{
    //add a weekly_schedule for a classroom (just 1 day)
    public function addWeeklySchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required',
            //'data' => 'required' //array data:[1:[subject id 1, subject id 2] , 'day'...
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $days = ["sunday", "monday", "tuesday", "wednesday", "thursday"];
        for ($i = 0; $i < count($days); $i++) {
            $day = new Week_day();
            $day->classroom_id = $request->classroom_id;
            $day->order = $i + 1;
            $day->save();
            for ($j = 0; $j < 8; $j++) {
                $week_day_subject = new Week_day_subject();
                if($j<count($request[$days[$i]]))
                $week_day_subject->subject_id = $request[$days[$i]][$j];
                else $week_day_subject->subject_id = null;
                $week_day_subject->week_day_id = $day->id;
                $week_day_subject->order = $j + 1;
                $week_day_subject->save();
            }
        }
        $sunday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 1]
        ])->first();

        $subjectsSunday = Week_day_subject::query()->where('week_day_id', '=', $sunday->id)->orderBy('order')->get();
        $monday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 2]
        ])->first();
        $subjectsMonday = Week_day_subject::query()->where('week_day_id', '=', $monday->id)->orderBy('order')->get();
        $tuesday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 3]
        ])->first();
        $subjectsTuesday = Week_day_subject::query()->where('week_day_id', '=', $tuesday->id)->orderBy('order')->get();
        $wednesday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 4]
        ])->first();
        $subjectsWednesday = Week_day_subject::query()->where('week_day_id', '=', $wednesday->id)->orderBy('order')->get();
        $thursday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 5]
        ])->first();
        $subjectsThursday = Week_day_subject::query()->where('week_day_id', '=', $thursday->id)->orderBy('order')->get();

        $data = null;
        //   return response()->json($subjectsSunday);
        $sessions = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];
        $days = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday"];
        $empty = "";
        for ($i = 0; $i < 5; $i++) $data[$i]["day"] = $days[$i];
        //for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j <8; $j++) {
            if ($j < count($subjectsSunday)) {
                if ($subjectsSunday[$j]->subject_id)
                    $data[0][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsSunday[$j]->subject_id)->first()->name;
                else $data[0][$sessions[$j]] = $empty;
            } else {

                $data[0][$sessions[$j]] = $empty;
            }
            if ($j < count($subjectsMonday)) {
                if ($subjectsMonday[$j]->subject_id)
                    $data[1][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsMonday[$j]->subject_id)->first()->name;
                else $data[1][$sessions[$j]] = $empty;
            } else {

                $data[1][$sessions[$j]] = $empty;
            }

            if ($j < count($subjectsTuesday)) {
                if ($subjectsTuesday[$j]->subject_id)
                    $data[2][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsTuesday[$j]->subject_id)->first()->name;
                else  $data[2][$sessions[$j]] = $empty;
            } else {

                $data[2][$sessions[$j]] = $empty;
            }

            if ($j < count($subjectsWednesday)) {
                if ($subjectsWednesday[$j]->subject_id)
                    $data[3][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsWednesday[$j]->subject_id)->first()->name;
                else $data[3][$sessions[$j]] = $empty;
            } else {

                $data[3][$sessions[$j]] = $empty;
            }

            if ($j < count($subjectsThursday)) {
                if ($subjectsThursday[$j]->subject_id)
                    $data[4][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsThursday[$j]->subject_id)->first()->name;
                else $data[4][$sessions[$j]] = $empty;
            } else {

                $data[4][$sessions[$j]] = $empty;
            }
        }
        // }

//        $returnArray=null;
//       foreach($data as $key=>$value){
//$returnArray[]=[$key=>$value];
//    }
        return response()->json($data);
    }

    public function add_subjects_to_schedules(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'subject_id' => 'required',
            'week_day_id' => 'required',
            'order' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

//        $week_day=Week_day::query()->where('day','=',$request->day)->
//        where('weekly_schedule_id','=',$request->weekly_schedule_id)->first();
        $week_day_subject = new Week_day_subject();
        $week_day_subject->subfield_id = $request->subfield_id;
        $week_day_subject->subject_id = $request->subject_id;
        $week_day_subject->week_day_id = $request->week_day_id;
        $week_day_subject->order = $request->order;
        $week_day_subject->save();

        return response()->json([
            'message' => 'add',
            'Week_day_subjects' => $week_day_subject
        ]);
    }

    public function getWeeklySchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $sunday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 1]
        ])->first();
        if (!$sunday) {
            return response()->json([]);
        }
        $subjectsSunday = Week_day_subject::query()->where('week_day_id', '=', $sunday->id)->orderBy('order')->get();
        $monday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 2]
        ])->first();
        $subjectsMonday = Week_day_subject::query()->where('week_day_id', '=', $monday->id)->orderBy('order')->get();
        $tuesday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 3]
        ])->first();
        $subjectsTuesday = Week_day_subject::query()->where('week_day_id', '=', $tuesday->id)->orderBy('order')->get();
        $wednesday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 4]
        ])->first();
        $subjectsWednesday = Week_day_subject::query()->where('week_day_id', '=', $wednesday->id)->orderBy('order')->get();
        $thursday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 5]
        ])->first();
        $subjectsThursday = Week_day_subject::query()->where('week_day_id', '=', $thursday->id)->orderBy('order')->get();

        $data = null;
        //   return response()->json($subjectsSunday);
        $sessions = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];
        $days = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday"];
        $empty = "";
        for ($i = 0; $i < 5; $i++) $data[$i]["day"] = $days[$i];
        //for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j <8; $j++) {
            if ($j < count($subjectsSunday)) {
                if ($subjectsSunday[$j]->subject_id)
                    $data[0][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsSunday[$j]->subject_id)->first()->name;
                else $data[0][$sessions[$j]] = $empty;
            } else {

                $data[0][$sessions[$j]] = $empty;
            }
            if ($j < count($subjectsMonday)) {
                if ($subjectsMonday[$j]->subject_id)
                    $data[1][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsMonday[$j]->subject_id)->first()->name;
                else $data[1][$sessions[$j]] = $empty;
            } else {

                $data[1][$sessions[$j]] = $empty;
            }

            if ($j < count($subjectsTuesday)) {
                if ($subjectsTuesday[$j]->subject_id)
                    $data[2][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsTuesday[$j]->subject_id)->first()->name;
                else  $data[2][$sessions[$j]] = $empty;
            } else {

                $data[2][$sessions[$j]] = $empty;
            }

            if ($j < count($subjectsWednesday)) {
                if ($subjectsWednesday[$j]->subject_id)
                    $data[3][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsWednesday[$j]->subject_id)->first()->name;
                else $data[3][$sessions[$j]] = $empty;
            } else {

                $data[3][$sessions[$j]] = $empty;
            }

            if ($j < count($subjectsThursday)) {
                if ($subjectsThursday[$j]->subject_id)
                    $data[4][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsThursday[$j]->subject_id)->first()->name;
                else $data[4][$sessions[$j]] = $empty;
            } else {

                $data[4][$sessions[$j]] = $empty;
            }
        }
        // }

//        $returnArray=null;
//       foreach($data as $key=>$value){
//$returnArray[]=[$key=>$value];
//    }
        return response()->json($data);
    }

    public function editWeeklySchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required',
            'day' => 'required',
            'session_order' => 'required',
            'subject_id', //optional field
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $day = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', $request->day]
        ])->first();
        $session = Week_day_subject::query()->where([
            ['week_day_id', '=', $day->id],
            ['order', '=', $request->session_order]
        ])->first();
//        if (!$session) {
//            //add new session
//            $allSessions = Week_day_subject::query()->where(['week_day_id', '=', $day->id])->get();
//            $week_day_subject = new Week_day_subject();
//            $week_day_subject->week_day_id = $day->id;
//            $week_day_subject->subject_id = $request->subject_id;
//            $week_day_subject->order = $request->session_order;
//            $week_day_subject->save();
//        }
//        $session = Week_day_subject::query()->where([
//            ['week_day_id', '=', $day->id],
//            ['order', '=', $request->session_order]
//        ])->first();
        if ($request->subject_id) {
            $session->subject_id = $request->subject_id;
            $session->save();
        } else {
            $session->subject_id = null;
            $session->save();
            //reorder sessions
//            $week_day_sessions = Week_day_subject::query()->where(
//                ['week_day_id', '=', $day->id])->get();
//            for($i=1;$i<=count($week_day_sessions);$i++) {
//                $week_day_sessions[$i-1]->order = $i;
//                $week_day_sessions->save();
//            }
        }

        //
        $sunday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 1]
        ])->first();
        if (!$sunday) {
            return response()->json([]);
        }
        $subjectsSunday = Week_day_subject::query()->where('week_day_id', '=', $sunday->id)->orderBy('order')->get();
        $monday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 2]
        ])->first();
        $subjectsMonday = Week_day_subject::query()->where('week_day_id', '=', $monday->id)->orderBy('order')->get();
        $tuesday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 3]
        ])->first();
        $subjectsTuesday = Week_day_subject::query()->where('week_day_id', '=', $tuesday->id)->orderBy('order')->get();
        $wednesday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 4]
        ])->first();
        $subjectsWednesday = Week_day_subject::query()->where('week_day_id', '=', $wednesday->id)->orderBy('order')->get();
        $thursday = Week_day::query()->where([
            ['classroom_id', '=', $request->classroom_id],
            ['order', '=', 5]
        ])->first();
        $subjectsThursday = Week_day_subject::query()->where('week_day_id', '=', $thursday->id)->orderBy('order')->get();

        $data = null;
        //   return response()->json($subjectsSunday);
        $sessions = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];
        $days = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday"];
        $empty = "";
        for ($i = 0; $i < 5; $i++) $data[$i]["day"] = $days[$i];
        //for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j <8; $j++) {
            if ($j < count($subjectsSunday)) {
                if ($subjectsSunday[$j]->subject_id)
                    $data[0][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsSunday[$j]->subject_id)->first()->name;
                else $data[0][$sessions[$j]] = $empty;
            } else {

                $data[0][$sessions[$j]] = $empty;
            }
            if ($j < count($subjectsMonday)) {
                if ($subjectsMonday[$j]->subject_id)
                    $data[1][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsMonday[$j]->subject_id)->first()->name;
                else $data[1][$sessions[$j]] = $empty;
            } else {

                $data[1][$sessions[$j]] = $empty;
            }

            if ($j < count($subjectsTuesday)) {
                if ($subjectsTuesday[$j]->subject_id)
                    $data[2][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsTuesday[$j]->subject_id)->first()->name;
                else  $data[2][$sessions[$j]] = $empty;
            } else {

                $data[2][$sessions[$j]] = $empty;
            }

            if ($j < count($subjectsWednesday)) {
                if ($subjectsWednesday[$j]->subject_id)
                    $data[3][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsWednesday[$j]->subject_id)->first()->name;
                else $data[3][$sessions[$j]] = $empty;
            } else {

                $data[3][$sessions[$j]] = $empty;
            }

            if ($j < count($subjectsThursday)) {
                if ($subjectsThursday[$j]->subject_id)
                    $data[4][$sessions[$j]] = Subject::query()->where('id', '=', $subjectsThursday[$j]->subject_id)->first()->name;
                else $data[4][$sessions[$j]] = $empty;
            } else {

                $data[4][$sessions[$j]] = $empty;
            }
        }
        // }

//        $returnArray=null;
//       foreach($data as $key=>$value){
//$returnArray[]=[$key=>$value];
//    }
        return response()->json($data);
    }

    public function deleteWeeklySchedule(Request $request){
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
         Week_day::query()->where('classroom_id','=',$request->classroom_id)->delete();

        return response()->json([]);
    }
}
