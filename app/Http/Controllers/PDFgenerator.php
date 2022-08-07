<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
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
            'student_id'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $student = Student::query()->where('user_id','=',$request->student_id)->with('user')
            ->with('classroom')->first();
        $student->classroom->class->subjects;
        $student->marks;
//        return response()->json([
//           $student
//        ], 400);
        $this->fpdf->SetFont('Arial', 'B', 15);
        $this->fpdf->AddPage('L','A4','0');
        $this->fpdf->Text(10, 10, $student->user->name); //student name here
        $this->fpdf->Ln('10');


        // Header
      //  for ($i = 0; $i < 4; $i++)
            $this->fpdf->Cell(50, 10, 'Subject', 1,0,'C');
        $this->fpdf->Cell(50, 10, 'Activities', 1,0,'C');
        $this->fpdf->Cell(50, 10, 'Homeworks', 1,0,'C');
        $this->fpdf->Cell(50, 10, 'Midterm', 1,0,'C');
        $this->fpdf->Cell(50, 10, 'Final', 1,0,'C');

        $this->fpdf->Ln();
        // Data
        for ($i = 0; $i < count($student->classroom->class->subjects); $i++) {
            for ($j = 0; $j < 5; $j++) {
             if($j==0){
                 $this->fpdf->Cell(50, 10, $student->classroom->class->subjects[$i]->name, 1, 0, 'C');
             }
             else   $this->fpdf->Cell(50, 10, $j, 1, 0, 'C');
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
