<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $category = new Category();
        $category->name = $request->name;

        $category->save();

        return response()->json([
            'message' => 'added',
            'data'=>$category
        ]);
    }

    public function getAll(){
        $categories = Category::all();
        for($i=0;$i<count($categories);$i++)
            $categories[$i]->books;

        return response()->json([
            'data'=>$categories
        ]);
    }
}
