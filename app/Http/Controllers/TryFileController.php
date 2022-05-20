<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\TryFile;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;
//
//
//class TryFileController extends Controller
//{
//    public function newFile(Request $request){
//        $data = new TryFile();
//        $data->book_name=$request->book_name;
//        $file = $request->file;
//        $filename=time().'.'.$file->getClientOriginalExtension();
//
//        /*OR
//        $filename=getClientOriginalName;*/
//
//        $request->file->move('assets',$filename);
//        $data->file=$filename;
//
//
//
//        $data->save();
//
//        return response()->json([
//            'classroom' => $data
//        ]);
//
//    }
//
//
//    public function download(Request $request,$file){
//        return response()->download(public_path('assets/'.$file));
//    }
//}
