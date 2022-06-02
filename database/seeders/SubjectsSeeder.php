<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('subjects')->insert([
            'name'=>'Algebra'
        ]);
        DB::table('subjects')->insert([
            'name'=>'Physics'
        ]);
        DB::table('subjects')->insert([
            'name'=>'Sport'
        ]);
        DB::table('subjects')->insert([
            'name'=>'Science'
        ]);
        DB::table('subjects')->insert([
            'name'=>'analysis'
        ]);

            }
}
