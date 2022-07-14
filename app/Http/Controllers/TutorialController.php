<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Helper_file;
use App\Models\Teacher;
use Illuminate\Support\Facades\File;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class TutorialController extends Controller
{
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'description'=>'required',
            'class_id'=>'required',
            'subject_id'=>'required',
            'file'=>'required',
            'teacher_id'=>'required',
            'helper_files' // array of files
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $tutorial = new Tutorial();
        $tutorial->name = $request->name;
        $tutorial->description = $request->description;
        $tutorial->class_id = $request->class_id;
        $tutorial->subject_id = $request->subject_id;
        $tutorial->teacher_id = $request->teacher_id;

        $file= $request->file;
        $filename=time().'.'.$file->getClientOriginalExtension();

        $request->file->move('tutorials',$filename);
        $tutorial->file=$filename;
        $tutorial->save();

        if($request->helper_files)
            for($i=0;$i<count($request->helper_files);$i++){
            $helper_file = new Helper_file();
            $helper_file->tutorial_id = $tutorial->id;

            $file2=$request->helper_files[$i];
            $file2name=$file2->getClientOriginalName();

            $request->helper_files[$i]->move('helper_files',$file2name);
            $helper_file->file=$file2name;
            $helper_file->save();
        }

        $tutorial->class;
        $tutorial->subject;
        $tutorial->helper_files;
        return response()->json([
            'data' => $tutorial
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name',
            'description',
            'class_id',
            'subject_id',
            //helper_files!!
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $tutorial = Tutorial::query()->find($request->id);
        if(!$tutorial)
            return response()->json([
                'message' => 'Tutorial not found',
            ],404);

        if($request->name)
            $tutorial->name = $request->name;

        if($request->description)
            $tutorial->description = $request->description;

        if($request->class_id)
            $tutorial->class_id = $request->class_id;

        if($request->subject_id)
            $tutorial->subject_id = $request->subject_id;

        $tutorial->save();

        $tutorial->class;
        $tutorial->subject;
        $tutorial->helper_files;


        return response()->json([
            'message' => 'updated',
            'data'=>$tutorial
        ]);
    }

    public function view(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $tutorial = Tutorial::query()->find($request->id);

        if(!$tutorial)
            return response()->json([
                'message' => 'Tutorial not found',
            ],404);
        $tutorial->subject;
        return response()->json([
            'data'=>$tutorial
        ]);
    }
    public function dd(){
        return 5;
    }

    public function getall(Request $request){

        $validator = Validator::make($request->all(), [
            'class_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $class = Classes::query()->find($request->class_id);
        if(!$class)
            return response()->json([
                'message' => 'Class not found',
            ],404);

        $tutorials = $class->tutorials;
        foreach ($tutorials as $tutorial){
            $tutorial->subject;
            $tutorial->helper_files;
            $tutorial->class;
        }
        return response()->json([
            'data' => $tutorials
        ]);
    }
    public function getTeacherTutorials(Request $request){

        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $teacher = Teacher::query()->where('user_id','=',$request->teacher_id)->first();
        if(!$teacher)
            return response()->json([
                'message' => 'Teacher not found',
            ],404);

        $tutorials = $teacher->tutorials;
        foreach ($tutorials as $tutorial){
            $tutorial->subject;
            $tutorial->helper_files;
            $tutorial->class;
        }
        return response()->json([
            'data' => $tutorials
        ]);
    }

    public function download(Request $request,$file){
        return response()->download(public_path('tutorials/' . $file));
    }

    public function delete(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $tutorial = Tutorial::query()->where('id', '=', $request->id)->first();
        if (!$tutorial)
            return response()->json([
                'message' => 'Tutorial not found'
            ],404);
        $helper_files= $tutorial->helper_files;
        if($helper_files)
            foreach ($helper_files as $helper_file) {
                //delete files from helper_files folder
                if (File::exists("helper_files/{$helper_file->file}")) {
                    unlink("helper_files/{$helper_file->file}");
                }
            }


        //delete video from tutorials folder
        if (File::exists("tutorials/{$tutorial->file}")) {
            unlink("tutorials/{$tutorial->file}");
        }
        $tutorial->delete();
        return response()->json([
            'message' => 'deleted',
            'data' => $tutorial
        ]);
    }


}
