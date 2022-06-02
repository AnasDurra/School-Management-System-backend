<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ClassroomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('classrooms')->insert([
            'name'=>'1A',
            'capacity'=>20,
            'class_id'=>1
        ]);
        DB::table('classrooms')->insert([
            'name'=>'2A',
            'capacity'=>20,
            'class_id'=>2
        ]);

    }
}
