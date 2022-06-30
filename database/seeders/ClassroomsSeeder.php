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
            'class_id'=>1,
            'created_at'=>now()
        ]);
        DB::table('classrooms')->insert([
            'name'=>'2A',
            'capacity'=>20,
            'class_id'=>2,
            'created_at'=>now()
        ]);

    }
}
