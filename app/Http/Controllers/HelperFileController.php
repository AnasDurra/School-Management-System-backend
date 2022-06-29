<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelperFileController extends Controller
{
    public function download(Request $request,$file){
        return response()->download(public_path('helper_files/' . $file));
    }
}
