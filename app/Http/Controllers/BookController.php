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
                'categories_id'=>'required', //array
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
            $file = $request->file('file');
            $filename=time().'.'.$file->getClientOriginalExtension();
            /*OR
            $filename=getClientOriginalName;*/
            $request->file->move('assets',$filename);
            $book->file=$filename;
            $book->save();

            for($i=0;$i<count($request->categories_id);$i++) {
                $category_book = new Category_book();
                $category_book->book_id = $book->id;
                $category_book->category_id = $request->categories_id[$i];
                $category_book->save();

            }
            $book->categories;
            return response()->json([
                'classroom' => $book
            ]);
        }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name',
            'categories_id' //array
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $book = Book::query()->where('id','=',$request->id)->first();
        if(!$book)
            return response()->json([
                'message' => 'Book not found'
            ]);
        if ($request->name)
            $book->name = $request->name;
        $book->save();
        if($request->categories_id) {
            $category_book = Category_book::query()->where('book_id', '=', $request->id)->delete();
            for ($i = 0; $i < count($request->categories_id);$i++) {
                $category_book =new Category_book ();
                $category_book->book_id = $request->id;
                $category_book->category_id = $request->categories_id[$i];
                $category_book->save();
            }
        }
        $book->categories;
        return response()->json([
            'message' => 'updated',
            'book'=>$book
        ]);

    }



        public function delete(Request $request){
            $validator = Validator::make($request->all(), [
                'id'=>'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'error' => $errors
                ], 400);
            }

            $book = Book::query()->where('id','=',$request->id)->first();
            $book->delete();
            if(!$book)
                return response()->json([
                    'message' => 'Book not found'
                ]);

            return response()->json([
                'message' => 'deleted',
                'data'=>$book
            ]);

        }

    public function getAll(Request $request){
        $books = Book::query()->get();
        if(!$books)
            return response()->json([
                'message' => 'no books yet'
            ]);

        for($i=0;$i<count($books);$i++)
            $books[$i]->categories;
        return response()->json([
            'data' => $books,
        ]);
    }
        public function download(Request $request,$file)
        {
            return response()->download(public_path('assets/' . $file));
        }
}
