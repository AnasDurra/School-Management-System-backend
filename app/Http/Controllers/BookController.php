<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category_book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
        public function newBook(Request $request){

            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'category_id'=>'required',
                'file'=>'required'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'error' => $errors
                ], 400);
            }
            $book = new Book();
            $book->name=$request->name;
            $file = $request->file;
            $filename=time().'.'.$file->getClientOriginalExtension();
            /*OR
            $filename=getClientOriginalName;*/
            $request->file->move('assets',$filename);
            $book->file=$filename;
            $book->save();


            $category_book = new Category_book();
            $category_book->book_id = $book->id;
            $category_book->category_id = $request->category_id;
            $category_book->save();

            $book->categories;
            return response()->json([
                'classroom' => $book
            ]);
        }


        public function download(Request $request,$file)
        {
            return response()->download(public_path('assets/' . $file));
        }
}
