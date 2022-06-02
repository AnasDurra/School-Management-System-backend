<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('class_subject')->insert([
            'class_id'=>1,
            'subject_id'=>1
        ]);
        DB::table('class_subject')->insert([
            'class_id'=>1,
            'subject_id'=>2
        ]);
        DB::table('class_subject')->insert([
            'class_id'=>1,
            'subject_id'=>3
        ]);
        DB::table('class_subject')->insert([
            'class_id'=>2,
            'subject_id'=>3
        ]);
        DB::table('class_subject')->insert([
            'class_id'=>2,
            'subject_id'=>4
        ]);
        DB::table('class_subject')->insert([
            'class_id'=>2,
            'subject_id'=>5
        ]);
    }
}
