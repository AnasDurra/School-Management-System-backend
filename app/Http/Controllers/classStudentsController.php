<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\student;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class classStudentsController extends Controller
{
    //
    public function all(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $class = SchoolClass::query()->where('id', '=', $request->id)->firstOrFail();
        $students= $class->students;
        return response()->json(
            $students
        );
    }
}
