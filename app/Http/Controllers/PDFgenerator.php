<?php

namespace App\Http\Controllers;

use App\Models\Absent;
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
        $path = public_path('results\\' . $classroom->name . '\\');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $students = $classroom->students;
        for ($i = 0; $i < count($students); $i++) {
            $this->generateStudentResultRecord($students[$i]->user_id, $request->moderator_id, $path);
        }
        $zip = new ZipArchive();
        $fileName = $classroom->name . '.zip';
        if ($zip->open(public_path('results\\' . $fileName), ZipArchive::CREATE | ZipArchive::OVERWRITE) == TRUE) {
            $files = File::files(public_path('results\\' . $classroom->name));
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
            $zip->close();
        }
        return \response()->download(public_path('results\\' . $fileName));

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
        $this->fpdf->Image(public_path('logo.jpg'), 240, 6, 40, 40);
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
        $this->fpdf->Text($this->fpdf->GetPageWidth() / 2.7, $this->fpdf->GetPageHeight() - 20, 'Headmaster Signature : ');
        $this->fpdf->SetTextColor(0, 0, 0);

        $this->fpdf->Text($this->fpdf->GetPageWidth() - 40, $this->fpdf->GetPageHeight() - 20, (Carbon::now()->toDateString()));
        $this->fpdf->Text($this->fpdf->GetPageWidth() / 20 + 30, $this->fpdf->GetPageHeight() - 20, $admin->user->name);

        $this->fpdf->Text(50, 20, $student->user->name); //student name here
        $this->fpdf->Text(50, 30, $student->parent->user->name);
        $this->fpdf->Text(50, 40, $student->classroom->class->name);
        $this->fpdf->Text(50, 50, $student->classroom->name);
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
        $this->fpdf->Cell(40, 10, 'Subject', 1, 0, 'C', true);
        $this->fpdf->Cell(40, 10, 'Activities', 1, 0, 'C', true);
        $this->fpdf->Cell(40, 10, 'Quizzes', 1, 0, 'C', true);
        $this->fpdf->Cell(40, 10, 'Midterm', 1, 0, 'C', true);
        $this->fpdf->Cell(40, 10, 'Final', 1, 0, 'C', true);
        $this->fpdf->Cell(40, 10, 'Sum', 1, 0, 'C', true);
        $this->fpdf->Ln();

        // Color and font restoration
        $this->fpdf->SetFillColor(224, 235, 255);
        $this->fpdf->SetTextColor(0);
        $this->fpdf->SetFont('');
        // Data
        $full_sum = 0;
        $full_cnt = 0;
        for ($i = 0; $i < count($student->classroom->class->subjects); $i++) {
            $sum = 0;
            $cnt = 0;
            for ($j = 0; $j < 6; $j++) {
                if ($j == 0) {
                    $this->fpdf->Cell(40, 10, $student->classroom->class->subjects[$i]->name, 1, 0, 'C');
                } else if ($j == 5) {
                    if ($cnt) {
                       if($sum/$cnt<40) $this->fpdf->SetTextColor(256, 0, 0);
                        $this->fpdf->Cell(40, 10, $sum / $cnt, 1, 0, 'C');
                        $this->fpdf->SetTextColor(0);
                    }
                    else $this->fpdf->Cell(40, 10, '-', 1, 0, 'C');

                } else {
                    $mark = Mark::query()->where('student_id', '=', $student->user_id)->
                    where('type_id', '=', $j)->where('subject_id', '=',
                        $student->classroom->class->subjects[$i]->id)->first();
                    if ($mark) {
                        $cnt++;
                        $this->fpdf->Cell(40, 10, $mark->value, 1, 0, 'C');
                        $sum += $mark->value;
                    } else   $this->fpdf->Cell(40, 10, '-', 1, 0, 'C');
                }
                if ($cnt) $full_cnt++;
                $full_sum += $sum;

            }
            $this->fpdf->Ln();
        }
        //final perecentage
        $this->fpdf->SetFont('Arial', 'B', 13);
        $this->fpdf->SetTextColor(117, 149, 168);
        $this->fpdf->Text(10, 70, 'Final Percentage :');
        $this->fpdf->SetTextColor(0, 0, 0);
        if ($full_sum / $full_cnt < 40) $this->fpdf->SetTextColor(255, 0, 0);
        $this->fpdf->Text(60, 70, number_format((float)($full_sum / $full_cnt), 2, '.', '') . ' %');
        //absents
        $this->fpdf->SetTextColor(117, 149, 168);
        $justified = Absent::query()->where('student_id', '=', $student_id)->where('is_justified', '=', 1)->get();
        $not_justified = Absent::query()->where('student_id', '=', $student_id)->where('is_justified', '=', 0)->get();
        $this->fpdf->Text(90, 70, 'Absents :');
        $this->fpdf->Text(130, 70, 'Justified Absents :');
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Text(115, 70, count($not_justified));
        $this->fpdf->Text(174, 70, count($justified));


        $this->fpdf->Output($path . $student->user->name . '.pdf', 'F');
    }
}


