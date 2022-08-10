<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Archive_Year;
use App\Models\Classroom;
use App\Models\Mark;
use App\Models\Student;
use Codedge\Fpdf\Fpdf\Fpdf;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use  Illuminate\Support\Facades\File;
use ZipArchive;

class PDFgenerator extends Controller
{
    protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new Fpdf;
    }

    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'classroom_id',
            'moderator_id'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $classroom = Classroom::query()->where('id', '=', $request->classroom_id)->first();
        //creating classroom folder
        $path = public_path('results\\'.$classroom->name . '\\');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $students = $classroom->students;
        for ($i = 0; $i < count($students); $i++) {
            $this->generateStudentResultRecord($students[$i]->user_id, $request->moderator_id, $path);
        }
        $zip = new ZipArchive();
        $fileName = $classroom->name.'.zip';
        if($zip->open(public_path('results\\'.$fileName),ZipArchive::CREATE |ZipArchive::OVERWRITE) ==TRUE){
            $files = File::files(public_path('results\\'.$classroom->name));
            foreach ($files as $key => $value){
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value,$relativeNameInZipFile);
            }
            $zip->close();
        }
        return \response()->download(public_path('results\\'.$fileName));

    }

    public function generateStudentResultRecord($student_id, $moderator_id, $path)
    {
        $this->fpdf = new Fpdf();
        $student = Student::query()->where('user_id', '=', $student_id)->with('user')
            ->with('classroom')->first();
        $admin = Admin::query()->where('user_id', '=', $moderator_id)->first();
        $student->classroom->class->subjects;
        $student->marks;
        $year = Archive_Year::query()->where('active', '=', 1)->first();
//        return response()->json([
//           $student
//        ], 400);
        $this->fpdf->AddPage('L', 'A4', '0');
        $this->fpdf->Image('C:\Users\VS\Desktop\ilumi\laravel_project\app\Http\Controllers\logo.jpg', 240, 6, 40, 40);
        //header left
        $this->fpdf->SetFont('Arial', 'B', 13);
        $this->fpdf->SetTextColor(117, 149, 168);
        $this->fpdf->Text(10, 20, 'Student :');
        $this->fpdf->Text(10, 30, 'Parent :');
        $this->fpdf->Text(10, 40, 'Class :');
        $this->fpdf->Text(10, 50, 'Classroom :');
        $this->fpdf->Text(10, 60, 'Year :');
        $this->fpdf->Text($this->fpdf->GetPageWidth() - 60, $this->fpdf->GetPageHeight() - 20, 'Date : ');
        $this->fpdf->Text($this->fpdf->GetPageWidth() / 20, $this->fpdf->GetPageHeight() - 20, 'Moderator : ');
        $this->fpdf->SetTextColor(0, 0, 0);

        $this->fpdf->Text($this->fpdf->GetPageWidth() - 40, $this->fpdf->GetPageHeight() - 20, (Carbon::now()->toDateString()));
        $this->fpdf->Text($this->fpdf->GetPageWidth() / 20 + 30, $this->fpdf->GetPageHeight() - 20, $admin->user->name);

        $this->fpdf->Text(50, 20, $student->user->name); //student name here
        $this->fpdf->Text(50, 30, $student->parent->user->name); //student name here
        $this->fpdf->Text(50, 40, $student->classroom->class->name); //student name here
        $this->fpdf->Text(50, 50, $student->classroom->name); //student name here
        $this->fpdf->Text(50, 60, $year->year . ' - ' . ($year->year + 1));
        $this->fpdf->Ln('80');


        // Colors, line width and bold font
        $this->fpdf->SetFillColor(117, 149, 168);
        $this->fpdf->SetTextColor(255, 255, 255);
        $this->fpdf->SetDrawColor(117, 149, 168);
        $this->fpdf->SetLineWidth(.3);
        $this->fpdf->SetFont('', '');
        // Header
        //  for ($i = 0; $i < 4; $i++)
        $this->fpdf->Cell(50, 10, 'Subject', 1, 0, 'C', true);
        $this->fpdf->Cell(50, 10, 'Activities', 1, 0, 'C', true);
        $this->fpdf->Cell(50, 10, 'Quizzes', 1, 0, 'C', true);
        $this->fpdf->Cell(50, 10, 'Midterm', 1, 0, 'C', true);
        $this->fpdf->Cell(50, 10, 'Final', 1, 0, 'C', true);
        $this->fpdf->Ln();

        // Color and font restoration
        $this->fpdf->SetFillColor(224, 235, 255);
        $this->fpdf->SetTextColor(0);
        $this->fpdf->SetFont('');
        // Data
        for ($i = 0; $i < count($student->classroom->class->subjects); $i++) {
            for ($j = 0; $j < 5; $j++) {
                if ($j == 0) {
                    $this->fpdf->Cell(50, 10, $student->classroom->class->subjects[$i]->name, 1, 0, 'C');
                } else {
                    $mark = Mark::query()->where('student_id', '=', $student->user_id)->
                    where('type_id', '=', $j)->where('subject_id', '=',
                        $student->classroom->class->subjects[$i]->id)->first();
                    if ($mark)
                        $this->fpdf->Cell(50, 10, $mark->value, 1, 0, 'C');
                    else   $this->fpdf->Cell(50, 10, '-', 1, 0, 'C');
                }
            }
            $this->fpdf->Ln();
        }
        $this->fpdf->Output($path . $student->user->name . '.pdf', 'F');
    }
}


