<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeachersSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //faozia
        DB::table('teacher_subject')->insert([
           'teacher_id'=>10,
            'subject_id'=>2,
            'created_at'=>now()
        ]);
        //sami
        DB::table('teacher_subject')->insert([
            'teacher_id'=>11,
            'subject_id'=>3,
            'created_at'=>now()
        ]);
        //mohsen
        DB::table('teacher_subject')->insert([
            'teacher_id'=>12,
            'subject_id'=>1,
            'created_at'=>now()
        ]);
        DB::table('teacher_subject')->insert([
            'teacher_id'=>12,
            'subject_id'=>5,
            'created_at'=>now()
        ]);
        //salwa
        DB::table('teacher_subject')->insert([
            'teacher_id'=>13,
            'subject_id'=>4,
            'created_at'=>now()
        ]);

    }
}
