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
            'name'=>'Algebra',
            'created_at'=>now()
        ]);
        DB::table('subjects')->insert([
            'name'=>'Physics',
            'created_at'=>now()
        ]);
        DB::table('subjects')->insert([
            'name'=>'Sport',
            'created_at'=>now()
        ]);
        DB::table('subjects')->insert([
            'name'=>'Science',
            'created_at'=>now()
        ]);
        DB::table('subjects')->insert([
            'name'=>'analysis',
            'created_at'=>now()
        ]);

            }
}
