<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function all(Request $request){
        $tags = Tag::all();

        if(!$tags)
            return response()->json([
                'message'=>'No tags',
                'data'=>$tags
            ]);

        foreach ($tags as $tag){
            $tag->admins;
        }

        return response()->json([
            'data'=>$tags
        ]);
    }
}
