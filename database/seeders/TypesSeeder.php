<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('types')->insert([
            'name'=>'Activities',
            'created_at'=>now()
        ]);
        DB::table('types')->insert([
            'name'=>'Quizzes',
            'created_at'=>now()
        ]);
        DB::table('types')->insert([
            'name'=>'Midterm Exam',
            'created_at'=>now()
        ]);
        DB::table('types')->insert([
            'name'=>'Final Exam',
            'created_at'=>now()
        ]);
    }
}
