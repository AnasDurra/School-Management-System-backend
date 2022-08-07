<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Mark;
use App\Models\Student;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

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
            'student_id',
            'moderator_id'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }


        $student = Student::query()->where('user_id', '=', $request->student_id)->with('user')
            ->with('classroom')->first();
        $admin= Admin::query()->where('user_id','=',$request->moderator_id)->first();
        $student->classroom->class->subjects;
        $student->marks;
//        return response()->json([
//           $student
//        ], 400);
        $this->fpdf->AddPage('L', 'A4', '0');
        $this->fpdf->Image('C:\Users\VS\Desktop\ilumi\laravel_project\app\Http\Controllers\logo.jpg',240,6,40,40);
        //header left
        $this->fpdf->SetFont('Arial', 'B', 13);
        $this->fpdf->SetTextColor(117,149,168);
        $this->fpdf->Text(10, 20, 'Student :');
        $this->fpdf->Text(10, 30, 'Parent :');
        $this->fpdf->Text(10, 40, 'Class :');
        $this->fpdf->Text(10, 50, 'Classroom :');
        $this->fpdf->Text(240, 190, 'Date : ');
        $this->fpdf->Text(10, 190, 'Moderator : ');
        $this->fpdf->SetTextColor(0,0,0);

        $this->fpdf->Text(260, 190, (Carbon::now()->toDateString()));
        $this->fpdf->Text(40, 190, $admin->user->name);

        $this->fpdf->Text(50, 20, $student->user->name); //student name here
        $this->fpdf->Text(50, 30, $student->parent->user->name); //student name here
        $this->fpdf->Text(50, 40, $student->classroom->class->name); //student name here
        $this->fpdf->Text(50, 50, $student->classroom->name); //student name here
        $this->fpdf->Ln('80');







        // Colors, line width and bold font
        $this->fpdf->SetFillColor(117,149,168);
        $this->fpdf->SetTextColor(255,255,255);
        $this->fpdf->SetDrawColor(117,149,168);
        $this->fpdf->SetLineWidth(.3);
        $this->fpdf->SetFont('','');
        // Header
        //  for ($i = 0; $i < 4; $i++)
        $this->fpdf->Cell(50, 10, 'Subject', 1, 0, 'C',true);
        $this->fpdf->Cell(50, 10, 'Activities', 1, 0, 'C',true);
        $this->fpdf->Cell(50, 10, 'Quizzes', 1, 0, 'C',true);
        $this->fpdf->Cell(50, 10, 'Midterm', 1, 0, 'C',true);
        $this->fpdf->Cell(50, 10, 'Final', 1, 0, 'C',true);
        $this->fpdf->Ln();

        // Color and font restoration
        $this->fpdf->SetFillColor(224,235,255);
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

//        // Colors, line width and bold font
//        $this->fpdf->SetFillColor(255,0,0);
//        $this->fpdf->SetTextColor(255);
//        $this->fpdf->SetDrawColor(128,0,0);
//        $this->fpdf->SetLineWidth(.3);
//        $this->fpdf->SetFont('','B');
//
//        // Header
//        $w = array(40, 40, 40, 40);
//        for($i=0;$i<4;$i++)
//            $this->fpdf->Cell($w[$i],7,'hi',1,0,'C',true);
//
//        // Color and font restoration
//        $this->fpdf->SetFillColor(224,235,255);
//        $this->fpdf->SetTextColor(0);
//        $this->fpdf->SetFont('');
//        // Data
//        $fill = false;
//        for($i=0;$i<4;$i++)
//        {
//            $this->fpdf->Cell($w[0],6,$i,'LR',0,'L',$fill);
//            $this->fpdf->Cell($w[1],6,$i,'LR',0,'L',$fill);
//            $this->fpdf->Cell($w[2],6,number_format($i),'LR',0,'R',$fill);
//            $this->fpdf->Cell($w[3],6,number_format($i),'LR',0,'R',$fill);
//            $this->fpdf->Ln();
//            $fill = !$fill;
//        }
//        // Closing line
//        $this->fpdf->Cell(array_sum($w),0,'','T');
        $this->fpdf->Output();

        exit;
    }
}
