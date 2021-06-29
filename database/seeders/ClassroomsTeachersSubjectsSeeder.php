<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomsTeachersSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //classromm 1a
        DB::table('classroom_teacher_subject')->insert([
            'classroom_id'=>1,
            'teacherSubject_id'=>1,
            'created_at'=>now()
        ]);
        DB::table('classroom_teacher_subject')->insert([
            'classroom_id'=>1,
            'teacherSubject_id'=>2,
            'created_at'=>now()
        ]);
        DB::table('classroom_teacher_subject')->insert([
            'classroom_id'=>1,
            'teacherSubject_id'=>3,
            'created_at'=>now()
        ]);
//classroom 2a
        DB::table('classroom_teacher_subject')->insert([
            'classroom_id'=>2,
            'teacherSubject_id'=>5,
            'created_at'=>now()
        ]);
        DB::table('classroom_teacher_subject')->insert([
            'classroom_id'=>2,
            'teacherSubject_id'=>4,
            'created_at'=>now()
        ]);
        DB::table('classroom_teacher_subject')->insert([
            'classroom_id'=>2,
            'teacherSubject_id'=>2,
            'created_at'=>now()
        ]);

    }
}
