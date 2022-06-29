<?php

namespace App\Http\Controllers;

use App\Models\Archive_Year;
use App\Models\Bus;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusController extends Controller
{
    public function getAddresses(Request $request){
        $address = User::query()->filterYear('created_at')->select('address')->distinct()->get();

        return response()->json([
            $address
        ]);
    }

    public function addBus(Request $request){
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'capacity'=>'required',
            'supervisor'=>'required',
            'supervisor_num'=>'required',
            'supervisor_address'=>'required',
            'students_id' //array(not required)
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $bus = Bus::query()->create([
            'name'=>$request->name,
            'capacity'=>$request->capacity,
            'supervisor'=>$request->supervisor,
            'supervisor_num'=>$request->supervisor_num,
            'supervisor_address'=>$request->supervisor_address,
        ]);
        $bus =Bus::query()->find($bus->id);
        if($request->students_id) {
            for ($i = 0; $i < count($request->students_id); $i++) {
                Student::query()->where('user_id','=',$request->students_id[$i])->update(["bus_id"=>$bus->id]);
            }
        }
        $students=$bus->students;
        for($i=0;$i<count($students);$i++)
            $students[$i]->user;

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
            'message' => 'added',
            'data'=>$bus
        ]);
    }

    public function updateBus(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name',
            'capacity',
            'supervisor',
            'supervisor_num',
            'supervisor_address',
            'students_id' //array(not required)
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $bus = Bus::query()->find($request->id);
        $bus->update([
            "name"=> $request->name,
            "capacity"=> $request->capacity,
            "supervisor"=> $request->supervisor,
            "supervisor_num"=> $request->supervisor_num,
            "supervisor_address"=> $request->supervisor_address,
        ]);

        if($request->students_id) {
            Student::query()->where('bus_id', '=', $request->id)->update(["bus_id" => null]);
            for ($i = 0; $i < count($request->students_id); $i++) {
                Student::query()->where('user_id', '=', $request->students_id[$i])->update(["bus_id" => $bus->id]);
            }
        }

        $students=$bus->students;
        for($i=0;$i<count($students);$i++)
            $students[$i]->user;

        return response()->json([
            'message' => 'updated',
            'data'=>$bus
        ]);
    }

    public function deleteBus(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $bus = Bus::query()->find($request->id);
        $bus->delete();
        return response()->json([
            'message'=>'deleted',
            'data'=>$bus

        ]);

    }
    public function getBuses(Request $request){
        $buses = Bus::query()->filterYear('created_at')->get();

        if(!$buses)
            return response()->json(['message'=>'No buses']);

        for($i=0;$i<count($buses); $i++) {
            $students =$buses[$i]->students;
            for($j=0;$j<count($students); $j++) {
                $student_user=$students[$j]->user;
                $addresses[$j]=$student_user['address'];
                $buses[$i]['addresses']=$addresses;
            }
        }

        return response()->json([
            'data'=>$buses
        ]);
    }

}
