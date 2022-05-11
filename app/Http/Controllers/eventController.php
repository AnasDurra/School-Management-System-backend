<?php

namespace App\Http\Controllers;

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
        $new = new Event();
        $new->content = $request['content'];
        $new->date = $request->date;
        $new->save();
        $final = [
            'date' => $new->date,
            'content' => $new->content,
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
            $final[$events[$i]->date][] = $events[$i]->content;
        }
        $final2 = null;
        foreach ($final as $key => $value) {
            $final2[] = [
                'date' => $key,
                'data' => $value
            ];
        }

        return response()->json(
            $final2
        );


    }
}
