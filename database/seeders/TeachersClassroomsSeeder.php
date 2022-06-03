<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeachersClassroomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('teacher_classroom')->insert([
            'classroom_id'=>2,
            'teacher_id'=>12
        ]);
        DB::table('teacher_classroom')->insert([
            'classroom_id'=>2,
            'teacher_id'=>13
        ]);
        DB::table('teacher_classroom')->insert([
            'classroom_id'=>2,
            'teacher_id'=>11
        ]);

        DB::table('teacher_classroom')->insert([
            'classroom_id'=>1,
            'teacher_id'=>10
        ]);
        DB::table('teacher_classroom')->insert([
            'classroom_id'=>1,
            'teacher_id'=>11
        ]);
        DB::table('teacher_classroom')->insert([
            'classroom_id'=>1,
            'teacher_id'=>12
        ]);
    }
}
