<?php

namespace App\Http\Controllers;

use App\Models\Archive_Year;
use App\Models\Bus;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    //
    public function getStatistics(Request $request)
    {
        //each year and it's student count
        $years = Archive_Year::query()->orderBy('year','asc')->get();
        $students_count_each_year = [];
        for ($i = 0; $i < count($years); $i++) {
            $students_count = 0;
            $students = Student::query()->filterYear('created_at',$years[$i]->year)->get();
            if ($students) $students_count = count($students);
            $students_count_each_year[$i]['year'] = $years[$i]->year;
            $students_count_each_year[$i]['count'] = $students_count;
        }

//classrooms count in current year
        $classrooms_current_year = Classroom::query()->filterYear('created_at')->get();
        $classroom_count_current_year = 0;
        if ($classrooms_current_year) $classroom_count_current_year = count($classrooms_current_year);

        //teachers count in current year
        $teachers_current_year = Teacher::query()->filterYear('created_at')->get();
        $teacher_count_current_year = 0;
        if ($teachers_current_year) $teacher_count_current_year = count($teachers_current_year);
// classrooms capacity and all students that have classrooms count (to calculate empty seats percentage)
        $students_with_classrooms = Student::query()->whereNotNull('classroom_id')->filterYear('created_at')->get();
        $students_cnt = 0;
        if ($students_with_classrooms) $students_cnt = count($students_with_classrooms);
        $full_capacity_count = 0;
        for ($i = 0; $i < count($classrooms_current_year); $i++) {
            $full_capacity_count += $classrooms_current_year[$i]->capacity;
        }
        $data['full_capacity'] = $full_capacity_count;
        $data['occupied_seats'] = $students_cnt;

        //buses count
        $buses_current_year = Bus::query()->filterYear('created_at')->get();
        $buses_count_current_year = 0;
        if ($buses_current_year) $buses_count_current_year = count($buses_current_year);


        return response()->json([
                'students_count_year' => $students_count_each_year,
                'classrooms_count_current_year' => $classroom_count_current_year,
                'teachers_count_current_year' => $teacher_count_current_year,
                'full_to_empty' => $data,
                'buses_count'=>$buses_count_current_year

            ]
        );
    }
}
