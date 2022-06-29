<?php

namespace App\Http\Controllers;

use App\Models\Archive_Year;
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
            'data'=>$category
        ]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $category = Category::query()->find($request->id);
        if(!$category)
            return response()->json([
                'message' => 'Category Notfound',
            ]);
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'message' => 'updated',
            'data'=>$category
        ]);
    }


    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $category = Category::query()->find($request->id);
        if(!$category)
            return response()->json([
                'message' => 'Category Notfound',
            ]);
        $category->delete();

        return response()->json([
            'message' => 'deleted',
            'data'=>$category
        ]);
    }


    public function getAll(){
        $categories = Category::all();
        for($i=0;$i<count($categories);$i++) {
            $books = $categories[$i]->books;
            for($j=0;$j<count($books);$j++) {
                $books[$j]->categories;
        }
        }

        return response()->json([
            'data'=>$categories
        ]);
    }


}
