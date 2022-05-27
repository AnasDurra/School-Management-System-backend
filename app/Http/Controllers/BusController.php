<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusController extends Controller
{
    public function getAddresses(Request $request){
        $address = User::query()->select('address')->distinct()->get();

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

    public function getBuses(Request $request){
        $buses = Bus::query()->get();

        if(!$buses)
            return response()->json(['message'=>'No buses']);

        foreach ($buses as $bus) {
            $students =$bus->students;
            foreach ($students as $student)
                $student->user;
        }

        return response()->json([
            'data'=>$buses
        ]);
    }

}
