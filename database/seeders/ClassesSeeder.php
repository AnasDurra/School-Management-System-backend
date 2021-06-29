<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('classes')->insert([
            'name'=>'First',
            'created_at'=>now()
        ]);
        DB::table('classes')->insert([
            'name'=>'Second',
            'created_at'=>now()
        ]);
    }
}
