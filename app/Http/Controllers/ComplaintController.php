<?php

namespace App\Http\Controllers;

use App\Models\Archive_Year;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'parent_id' => 'required',
            'date'=>'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $complaint = Complaint::query()->create([
           'title'=>$request->title,
           'content'=>$request['content'],
           'parent_id'=>$request->parent_id,
           'date'=>$request->date,
        ]);

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
            'data'=>$complaint
        ]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title',
            'content'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $complaint = Complaint::query()->find($request->id)->update([
            'title'=>$request->title,
            'content'=>$request['content'],
        ]);

        return response()->json([
            'message'=>'updated',
            'data'=>$complaint
        ]);

    }

    public function get_complaints(Request $request){
        $complaints = Complaint::query()->filterYear('created_at')->get();
        foreach ($complaints as $complaint)
            $complaint->parent->user;

        return response()->json([
            'data'=>$complaints
        ]);
    }

    public function seenComplaint(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $complaint =Complaint::query()->find($request->id);

        $complaint->seen=true;
        $complaint->admin_id= auth()->user()->id;
        $complaint->save();

        return response()->json([
            'data'=>$complaint
        ]);
    }


}
