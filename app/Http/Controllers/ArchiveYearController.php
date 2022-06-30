<?php

namespace App\Http\Controllers;

use App\Models\Archive_Year;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Integer;

class ArchiveYearController extends Controller
{
    //
    public function getYears(Request $request)
    {
        $data = Archive_Year::all();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getActiveYear(Request $request)
    {
        $data = Archive_Year::query()->where('active', '=', 1)->first();
        return response()->json([
            'year' => $data->year
        ]);
    }

    public function switchActiveYear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $archive_years = Archive_Year::query()->get();
        $archive_years_arr = [];
        for ($i = 0; $i < count($archive_years); $i++) {
            $archive_years_arr[] = $archive_years[$i]->year;
        }
        if (!in_array($request->year, $archive_years_arr)) return response()->json([
            'error' => 'year is not in archive'
        ], 404);

        $current_active = Archive_Year::query()->where('active', '=', 1)->first();
        $to_set_active = Archive_Year::query()->where('year', '=', $request->year)->first();
        $current_active->active = 0;
        $current_active->save();
        $to_set_active->active = 1;
        $to_set_active->save();
        $data = Archive_Year::query()->where('active', '=', 1)->first();
        return response()->json([
            'active year' => $data->year
        ]);
    }

    //import from
    public function previousYearClasses(Request $request){
        $active_year = Archive_Year::query()->select('year')->where('active','=',1)->first();
        $active_year =$active_year->year;
        $classes = Classes::query()->
        where(function ($query) use ($active_year){
            $query->
            where(function ($query1) use ($active_year){
                $query1->whereMonth('created_at','>=',9)->whereYear('created_at','=',$active_year-1);
            })->
            orWhere(function ($query2) use ($active_year){
                $query2->whereMonth('created_at','<',9)->whereYear('created_at','=',$active_year);
            });
        })->get();

        return response()->json([
            'data' => $classes
        ]);

    }

}
