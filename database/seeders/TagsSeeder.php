<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tags')->insert([
            'tag_name'=>'EVENT'
        ]);
        DB::table('tags')->insert([
            'tag_name'=>'TRANSPORTATION'
        ]);
        DB::table('tags')->insert([
            'tag_name'=>'COMPLAINS'
        ]);
        DB::table('tags')->insert([
            'tag_name'=>'LIBRARY'
        ]);
    }
}
