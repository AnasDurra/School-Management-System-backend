<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Admin_responsibility;
use App\Models\Archive_Year;
use App\Models\Class_Subject;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:YearUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add new years to the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentYear = now()->month < 9 ? now()->year - 1 : now()->year;
        $archive_years = Archive_Year::all();
        $max_year = 0;
        for ($i = 0; $i < count($archive_years); $i++) {
            if ($archive_years[$i]->year >= $currentYear) return Command::SUCCESS; //this year isn't a new year
            $max_year = max($max_year,$archive_years[$i]->year);
        }
        if($currentYear-$max_year!=1) return Command::SUCCESS; //you can only import from previous year
        //ORDER IS IMPORTANT

        //import classes to new year
        $classes = Classes::query()->filterYear('created_at')->get();
        $subjects = Subject::query()->filterYear('created_at')->get();
        $users = User::query()->filterYear('created_at')->get();

        ## prev year imported subjects id's
        $visited_id=[];
        $visited_new =[];
        //insert new classes
        for ($i = 0; $i < count($classes); $i++) {
            $new_class = new Classes();
            $new_class->name = $classes[$i]->name;
            $new_class->save();
            for($k=0;$k<count($classes[$i]->subjects);$k++){
                $f_sub_id=0;
                if(!in_array($classes[$i]->subjects[$k]->id,$visited_id)) {
                    $new_subject = new Subject();
                    $new_subject->name = $classes[$i]->subjects[$k]->name;
                    $new_subject->save();
                    $visited_new[]= $new_subject->id;
                    $f_sub_id=$new_subject->id;
                    $visited_id[]=$classes[$i]->subjects[$k]->id;
                }
                else {
                    for($l=0;$l<count($visited_id);$l++){
                        if($visited_id[$l]== $classes[$i]->subjects[$k]->id)   $f_sub_id=$visited_new[$l];
                    }

                }
                $class_subject = new Class_Subject();
                $class_subject->class_id = $new_class->id;
                $class_subject->subject_id =$f_sub_id;
                $class_subject->save();
            }
        }
        //insert new subjects
        for ($i = 0; $i < count($subjects); $i++) {
            if(!in_array($subjects[$i]->id,$visited_id)){
                $new_subject = new Subject();
                $new_subject->name = $subjects[$i]->name;
                $new_subject->save();
            }
        }
        //insert new users
        for ($i = 0; $i < count($users); $i++) {
            if ($users[$i]->role == 4 || $users[$i]->role == 3) continue; // don't add students and parents
            $new_user = new User();
            $new_user->name = $users[$i]->name;
            $new_user->address = $users[$i]->address;
            $new_user->phone_num = $users[$i]->phone_num;
            $new_user->role = $users[$i]->role;
            $new_user->username = strtolower(Str::random(10));
            $new_user->password = strtolower(Str::random(6));
            $new_user->save();
            if ($new_user->role <= 1) {//owner  or normal admin
                $new_admin = new Admin();
                $new_admin->user_id = $new_user->id;
                $new_admin->save();
                for($k=0;$k<count($users[$i]->admin->responsibilities);$k++){ //add previous responsibilities
                    $admin_res = new Admin_responsibility();
                    $admin_res->admin_id = $new_admin->user_id;
                    $admin_res->responsibility_id =$users[$i]->admin->responsibilities[$k]->id;
                    $admin_res->save();
                }
            }
            if ($new_user->role == 2) { //teacher
                $new_teacher = new Teacher();
                $new_teacher->user_id = $new_user->id;
                $new_teacher->save();
            }

        }
        //deactivate previous year
        $active_year = Archive_Year::query()->where('active', '=', 1)->first();
        $active_year->active = 0;
        $active_year->save();
        //insert new year
        DB::table('archive_years')->insert([
            'year' => $currentYear,
            'active' => 1
        ]);
        echo "year " . $currentYear . " has been inserted successfuly to the system \n";
        return Command::SUCCESS;
    }
}
