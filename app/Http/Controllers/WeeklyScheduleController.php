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
            for ($j = 0; $j < count($request[$days[$i]]); $j++) {

                $week_day_subject = new Week_day_subject();
                $week_day_subject->subject_id = $request[$days[$i]][$j];
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
        //   for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j <
        max(count($subjectsSunday), max(count($subjectsMonday), max(count($subjectsTuesday), max(count($subjectsWednesday), count($subjectsThursday)))))
        ; $j++) {
            if ($j < count($subjectsSunday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsSunday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";

            if ($j < count($subjectsMonday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsMonday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";

            if ($j < count($subjectsTuesday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsTuesday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";

            if ($j < count($subjectsWednesday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsWednesday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";

            if ($j < count($subjectsThursday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsThursday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";
        }
        //}


        return response()->json([
            'message' => 'success',
            'data' => $data
        ]);
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
        //   for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j <
        max(count($subjectsSunday), max(count($subjectsMonday), max(count($subjectsTuesday), max(count($subjectsWednesday), count($subjectsThursday)))))
        ; $j++) {
            if ($j < count($subjectsSunday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsSunday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";

            if ($j < count($subjectsMonday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsMonday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";

            if ($j < count($subjectsTuesday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsTuesday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";

            if ($j < count($subjectsWednesday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsWednesday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";

            if ($j < count($subjectsThursday))
                $data[$sessions[$j]][] = Subject::query()->where('id', '=', $subjectsThursday[$j]->subject_id)->first()->name;
            else $data[$sessions[$j]][] = "";
        }
        //}
        return response()->json($data);
    }
}
