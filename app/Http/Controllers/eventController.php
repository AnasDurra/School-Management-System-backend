<?php

namespace App\Http\Controllers;

use App\Models\Archive_Year;
use App\Models\Date;
use App\Models\Event;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class eventController extends Controller
{
    //
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'content' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $date = Date::query()->where('date','=',$request->date)->first();
        if(!$date){
        $date= new Date();
        $date->date = $request->date;
        $date->save();
        }
        $new = new Event();
        $new->content = $request['content'];
        $new->date_id = $date->id;
        $new->save();

        $final = [
            'date' => $date->date,
            'id'=>$date->id,
            'events' => $date->events,
        ];

        return response()->json(
            $final
            , 200);
    }

    public function all(Request $request)
    {
        $events = Event::query()->filterYear('created_at')->get();
        //  $collection = collect();
        $final = [];
        for ($i = 0; $i < count($events); $i++) {
            $final[$events[$i]->date_id][] = $events[$i];
        }
        $final2 = null;
        foreach ($final as $key => $value) {
            $date= Date::query()->Where('id','=',$key)->first();
            $final2[] = [
                'date' => $date->date,
                'id'=>$key,
                'events' => $value
            ];
        }
        return response()->json(
            $final2
        );
    }
    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $event = Event::query()->where('id','=',$request->id)->first();
        $event->delete();
        $date= Date::query()->where('id','=',$event->date_id)->with('events')->first();
        $events =[];
        for($i =0;$i<count($date->events);$i++){
            $events[]=$date->events[$i];
        }
        return response()->json(
        [
            'date'=>$date->date,
            'id'=>$date->id,
            'events'=>$events

        ]
        );
    }
}
