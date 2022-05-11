<?php

namespace App\Http\Controllers;

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
            'content' => $new->content,
            'id'=>$date->id
        ];
        return response()->json(
            $final
            , 200);
    }

    public function all(Request $request)
    {
        $events = Event::all();
        //  $collection = collect();
        $final = [];
        for ($i = 0; $i < count($events); $i++) {
            $final[$events[$i]->date_id][] = $events[$i]->content;
        }
        $final2 = null;
        foreach ($final as $key => $value) {
            $date= Date::query()->Where('id','=',$key)->first();
            $final2[] = [
                'date' => $date->date,
                'data' => $value,
                'id'=>$key
            ];
        }

        return response()->json(
            $final2
        );


    }
}
