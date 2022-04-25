<?php

namespace App\Http\Controllers;

use App\Models\Subfield;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubfieldController extends Controller
{
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            "subject_id"  //this is not required
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $subfield = Subfield::query()->where('id', '=', $request->id)->first();
        if (!$subfield) {
            return response()->json(['message' => 'Not Found']);
        }
        $subfield->name = $request->name;
        $subfield->subject_id = $request->subject_id;
        $subfield->save();

        return response()->json([
            'message' => 'updated',
            'data' => $subfield
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $subfield = Subfield::query()->where('id', '=', $request->id)->first();
        if (!$subfield) {
            return response()->json(['message' => 'Not Found']);
        }
        $subfield->delete();

        return response()->json([
            'message' => 'deleted'
        ]);
    }


}
