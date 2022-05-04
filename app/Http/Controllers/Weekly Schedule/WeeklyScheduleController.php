<?php

namespace App\Http\Controllers;

use App\Models\Week_day;
use App\Models\Week_day_subject;
use App\Models\Weekly_schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class weeklyScheduleController extends Controller
{
    //add a weekly_schedule for a classroom (just 1 day)
    public function add_weekly_schedule(Request $request){
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required'
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $weekly_schedule =new Weekly_schedule();
        $weekly_schedule->classroom_id =$request->classroom_id;
        $weekly_schedule->save();


        //Create 5 days
        for($i=1;$i<=5;$i++){
            $week_day = new Week_day();
            $week_day->day =$i ;
            $week_day->weekly_schedule_id=$weekly_schedule->id;
            $week_day->save();

        }

        return response()->json([
            'message' => 'weekly_schedule added',
            '$weekly_schedule_id'=>$weekly_schedule->id,
            'week_days'=>Week_day::query()->where('weekly_schedule_id','=',$week_day->weekly_schedule_id)->get()
        ]);
    }

    public function add_subjects_to_schedules(Request $request){
        $validator = Validator::make($request->all(), [
            'subfield_id' => 'required',
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
        $week_day_subject =new Week_day_subject();
        $week_day_subject->subfield_id =$request->subfield_id;
        $week_day_subject->subject_id =$request->subject_id;
        $week_day_subject->week_day_id =$request->week_day_id;
        $week_day_subject->order =$request->order;
        $week_day_subject->save();

        return response()->json([
            'message' => 'add',
            'Week_day_subjects'=>$week_day_subject
            ]);
    }

    public function get_weekly_schedule(Request $request){
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $weekly_schedule=Weekly_schedule::query()->where('classroom_id','=',$request->classroom_id)->first();

        $weekly_schedule=Week_day::with('week_day_subject')->where('weekly_schedule_id','=',$weekly_schedule->id)->get();
        return $weekly_schedule;
    }
}
